<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use App\Mail\CompanyPackageUpdate;
use App\Models\Admin;
use App\Models\Setting;
use App\Models\StripeInvoice;
use App\Traits\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Common\PayPalModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;


/** All Paypal Details class **/

use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use Carbon\Carbon;
use Stripe\Subscription;

class AdminPaypalController extends AdminBaseController
{
    private $_api_context;
    use Settings;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        /** setup PayPal api context **/
        config(['paypal.settings.mode' => $this->setting->paypal_mode]);
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($this->setting->paypal_client_id, $this->setting->paypal_secret));
        $this->_api_context->setConfig($paypal_conf['settings']);
        $this->pageTitle = 'modules.paymentSetting.paypal';
    }

    /**
     * Show the application paywith paypalpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function payWithPaypal()
    {
        return view('paywithpaypal', $this->data);
    }

    /**
     * Store a details of payment with paypal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paymentWithpaypal(Request $request, $invoiceId, $type)
    {
        $package = \App\Models\Plan::where('id', $invoiceId)->first();

        if($type == 'annual'){
            $totalAmount = $package->annual_price;
            $frequency = 'year';
            $cycle = 0;
        } else {
            $totalAmount = $package->monthly_price;
            $frequency = 'month';
            $cycle = 0;
        }

        $this->companyName = $this->company->company_name;

        $plan = new Plan();
        $plan->setName('#'.$package->plan_name)
            ->setDescription('Payment for package '.$package->plan_name)
            ->setType('INFINITE');

        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Payment for package '.$package->plan_name)
            ->setType('REGULAR')
            ->setFrequency(strtoupper($frequency))
            ->setFrequencyInterval(1)
            ->setCycles($cycle)
            ->setAmount(new Currency(array('value' => $totalAmount, 'currency' => $this->setting->currency)));

        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl(route('admin.paypal-recurring')."?success=true&invoice_id=".$invoiceId)
            ->setCancelUrl(route('admin.paypal-recurring')."?success=false&invoice_id=".$invoiceId)
            ->setAutoBillAmount("yes")
            ->setInitialFailAmountAction("CONTINUE")
            ->setMaxFailAttempts("0")
            ->setSetupFee(new Currency(array('value' => $totalAmount, 'currency' => $this->setting->currency)));

        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);

        try {
            $output = $plan->create($this->_api_context);

        } catch (\Exception $ex) {
            if (\Config::get('app.debug')) {
                if($ex->getCode() == 401) {
                    \Session::put('error','Please contact to administrator for palpal settings.');
                    return Redirect::route('admin.billing.change_plan');
                }
                \Session::put('error','Connection timeout');
                return Redirect::route('admin.billing.change_plan');
            } else {
                \Session::put('error','Some error occur, sorry for inconvenient');
                return Redirect::route('admin.billing.change_plan');
            }
        }

        try {
            $patch = new Patch();
            $value = new PayPalModel('{
               "state":"ACTIVE"
             }');
            $patch->setOp('replace')
                ->setPath('/')
                ->setValue($value);

            $patchRequest = new PatchRequest();
            $patchRequest->addPatch($patch);
            $output->update($patchRequest, $this->_api_context);
            $newPlan = Plan::get($output->getId(), $this->_api_context);

        } catch (\Exception $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error','Connection timeout');
                return Redirect::route('admin.billing.change_plan');
            } else {
                \Session::put('error','Some error occur, sorry for inconvenient');
                return Redirect::route('admin.billing.change_plan');
            }
        }

        $company = $this->company;


        // Calculating next billing date
        $today = Carbon::now()->addDays(1); //payment will deduct after 1 day

        $startingDate = $today->toIso8601String();

        $agreement = new Agreement();
        $agreement->setName($package->plan_name)
            ->setDescription('Payment for package # '.$package->plan_name)
            ->setStartDate("$startingDate");

        $plan1 = new Plan();
        $plan1->setId($newPlan->getId());
        $agreement->setPlan($plan1);

        // Add Payer
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $agreement->setPayer($payer);

        // ### Create Agreement
        try {
            // Please note that as the agreement has not yet activated, we wont be receiving the ID just yet.

            $agreement = $agreement->create($this->_api_context);

            $approvalUrl = $agreement->getApprovalLink();
        } catch (\Exception $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error','Connection timeout');
                return Redirect::route('admin.billing.change_plan');
            } else {
                \Session::put('error','Some error occur, sorry for inconvenient');
                return Redirect::route('admin.billing.change_plan');
            }
        }
        /** add payment ID to session **/
        Session::put('paypal_payment_id', $newPlan->getId());

        $paypalInvoice = new StripeInvoice();
        $paypalInvoice->company_id = $this->company->id;
        $paypalInvoice->payment_method = 'paypal';
        $paypalInvoice->subscription_plan_id = $package->id;
        $paypalInvoice->amount = $totalAmount;
        $paypalInvoice->pay_date = Carbon::now()->format('Y-m-d');
        $paypalInvoice->plan_id = $newPlan->getId();
        $paypalInvoice->billing_frequency = $frequency;
        $paypalInvoice->save();

        if(isset($approvalUrl)) {
            /** redirect to paypal **/
            return Redirect::away($approvalUrl);
        }

        \Session::put('error','Unknown error occurred');
        return Redirect::route('admin.billing.change_plan');

    }

    public function getPaymentStatus(Request $request)
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        $invoice_id = Session::get('invoice_id');
        $clientPayment =  StripeInvoice::where('plan_id', $payment_id)->first();
        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        if (empty($request->PayerID) || empty($request->token)) {
            \Session::put('error','Payment failed');
            return redirect(route('admin.billing.change_plan'));
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        /** PaymentExecution object includes information necessary **/
        /** to execute a PayPal account payment. **/
        /** The payer_id is added to the request query parameters **/
        /** when the user is redirected from paypal back to your site **/
        $execution = new PaymentExecution();
        $execution->setPayerId(request()->get('PayerID'));
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        /** dd($result);exit; /** DEBUG RESULT, remove it later **/
        if ($result->getState() == 'approved') {

            /** it's all right **/
            /** Here Write your database logic like that insert record or value in database if you want **/

            Session::put('success','Payment success');
            return Redirect::route('admin.billing.change_plan');
        }
        Session::put('error','Payment failed');

        return Redirect::route('admin.billing.change_plan');
    }

    public function payWithPaypalRecurrring(Request $requestObject)
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        $clientPayment =  StripeInvoice::where('plan_id', $payment_id)->first();
        $company = $this->company;
        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');

        if($requestObject->get('success') == true && $requestObject->has('token') && $requestObject->get('success') != "false" )
        {
            $token = $requestObject->get('token');
            $agreement = new Agreement();

            try {
                // ## Execute Agreement
                // Execute the agreement by passing in the token
                $agreement->execute($token, $this->_api_context);

                if($agreement->getState() == 'Active' || $agreement->getState() == 'Pending') {

                    $this->cancelSubscription();
                    // Calculating next billing date
                    $today = Carbon::now();


                    $clientPayment->transaction_id = $agreement->getId();

                    $clientPayment->pay_date = Carbon::now()->format('Y-m-d');
                    $clientPayment->save();

                    $company->subscription_plan_id = $clientPayment->subscription_plan_id;
                    $company->package_type = ($clientPayment->billing_frequency == 'year') ? 'annual' : 'monthly';
                    $company->status = 'active';// Set company status active
                    $company->licence_expire_on = null;
                    $company->save();

                    if( $company->package_type == 'monthly') {
                        $today = $today->addMonth();
                    } else {
                        $today = $today->addYear();
                    }

                    $clientPayment->next_pay_date = $today->format('Y-m-d');
                    $clientPayment->save();

                    //send superadmin notification
                    $superAdmins = Admin::whereNull('company_id')->get();
                    foreach($superAdmins as $admin) {
                        $inputs = [];
                        $inputs['company'] = $this->company;
                        $inputs['name'] = $admin->name;
                        Mail::to($admin->email)->queue(new CompanyPackageUpdate($inputs));
                    }


                    \Session::put('success','Payment successfully done');
                    return Redirect::route('admin.billing.change_plan');
                }

                \Session::put('error','Payment failed');

                return Redirect::route('admin.billing.change_plan');

            } catch (PayPalConnectionException $ex) {
                $errCode = $ex->getCode();
                $errData = json_decode($ex->getData());
                if ($errCode == 400 && $errData->name == 'INVALID_CURRENCY'){
                    \Session::put('error', $errData->message);
                    return Redirect::route('admin.billing.change_plan');
                }
                elseif (\Config::get('app.debug')) {
                    \Session::put('error','Connection timeout');
                    return Redirect::route('admin.billing.change_plan');
                }
                else {
                    \Session::put('error','Some error occur, sorry for inconvenient');
                    return Redirect::route('admin.billing.change_plan');
                }
            }

        }
        else if($requestObject->get('fail') == true || $requestObject->get('success') == "false")
        {
            \Session::put('error','Payment failed');

            return Redirect::route('admin.billing.change_plan');

        }else {
            abort(403);
        }

    }

    public function cancelAgreement()
    {
        $paypalInvoice = StripeInvoice::whereNotNull('transaction_id')->where('id', $this->company->id)->first();

        $agreementId = $paypalInvoice->transaction_id;
        $agreement = new Agreement();

        $agreement->setId($agreementId);
        $agreementStateDescriptor = new AgreementStateDescriptor();
        $agreementStateDescriptor->setNote("Cancel the agreement");

        try {
            $agreement->cancel($agreementStateDescriptor, $this->_apiContext);
            $cancelAgreementDetails = Agreement::get($agreement->getId(), $this->_apiContext);

            // Set subscription end date
//            $paypalInvoice->end_on = Carbon::parse($cancelAgreementDetails->agreement_details->final_payment_date)->format('Y-m-d H:i:s');
//            $paypalInvoice->save();
        } catch (\Exception $ex) {

        }

    }

    public function cancelSubscription() {
        $company = $this->company;
        $firstInvoice = DB::table("stripe_invoices")
            ->join('subscription_plans', 'subscription_plans.id', 'stripe_invoices.subscription_plan_id')
            ->selectRaw('stripe_invoices.id , stripe_invoices.payment_method as method, stripe_invoices.pay_date as paid_on ,stripe_invoices.next_pay_date')
            ->whereNotNull('stripe_invoices.pay_date')
            ->where('stripe_invoices.company_id', $company->id)
            ->orderBy('paid_on', 'desc')
            ->skip(1)
            ->take(1)
            ->first();

        if(!is_null($firstInvoice) && $firstInvoice->method == 'paypal'){
            $credential = Setting::first();
            config(['paypal.settings.mode' => $credential->paypal_mode]);
            $paypal_conf = Config::get('paypal');
            $api_context = new ApiContext(new OAuthTokenCredential($credential->paypal_client_id, $credential->paypal_secret));
            $api_context->setConfig($paypal_conf['settings']);

            $paypalInvoice = StripeInvoice::whereNotNull('transaction_id')->where('company_id', $this->company->id)->first();

            if($paypalInvoice) {
                $agreementId = $paypalInvoice->transaction_id;
                $agreement = new Agreement();

                $agreement->setId($agreementId);
                $agreementStateDescriptor = new AgreementStateDescriptor();
                $agreementStateDescriptor->setNote("Cancel the agreement");

                try {
                    $agreement->cancel($agreementStateDescriptor, $api_context);
                    $cancelAgreementDetails = Agreement::get($agreement->getId(), $api_context);

                    // Set subscription end date
                    $paypalInvoice->next_pay_date = Carbon::parse($cancelAgreementDetails->agreement_details->final_payment_date)->format('Y-m-d H:i:s');
                    $paypalInvoice->save();

                    $company->licence_expire_on = $paypalInvoice->next_pay_date;
                    $company->save();

                } catch (\Exception $ex) {
                   // \Session::put('error','Some error occur, sorry for inconvenient');
                }
            }

        } elseif(!is_null($firstInvoice) && $firstInvoice->method == 'stripe') {
            $this->setStripeConfigs();

            $subscription = \App\Models\Subscription::where('company_id', $this->company->id)->whereNull('ends_at')->first();
            if($subscription){
                try {
                    $company->subscription('main')->cancel();

                    $company->licence_expire_on = $subscription->ends_at;
                    $company->save();

                } catch (\Exception $ex) {
                    //\Session::put('error','Some error occur, sorry for inconvenient');
                }
            }
        }
    }

    public function paypalInvoiceDownload($id)
    {
        //        header('Content-type: application/pdf');
        $this->invoice = StripeInvoice::with(['company','plan'])->findOrFail($id);
//        $this->settings = $this->company;
        //        return view('invoices.'.$this->invoiceSetting->template, $this->data);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('paypal-invoice.invoice-1', $this->data);
        $filename = $this->invoice->paid_on->format($this->global->date_format).'-'.$this->invoice->next_pay_date->format($this->global->date_format);
        //       return $pdf->stream();
        return $pdf->download($filename . '.pdf');
    }

}
