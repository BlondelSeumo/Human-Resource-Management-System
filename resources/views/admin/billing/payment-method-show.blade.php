<style>
    .stripe-button-el{
        display: none;
    }
    .displayNone {
        display: none;
    }
    .checkbox-inline, .radio-inline {
        vertical-align: top !important;
    }
    .payment-type {
        border: 1px solid #e1e1e1;
        padding: 20px;
        background-color: #f3f3f3;
        border-radius: 10px;

    }
    .box-height {
        height: 78px;
    }
    .button-center{
        display: flex;
        justify-content: center;
    }
    .paymentMethods{display: none; transition: 0.3s;}
    .paymentMethods.show{display: block;}

    .stripePaymentForm{display: none; transition: 0.3s;}
    .stripePaymentForm.show{display: block;}
    div#card-element{
        width: 100% !important;
        color: #4a5568 !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
        line-height: 1.25 !important;
        border-width: 1px !important;
        border-radius: 0.25rem !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
        border-style: solid !important;
        border-color: #e2e8f0 !important;
    }
    button#card-button {
        border-radius: 0 !important;
        border-color: #27a1ab;
    }
    .row.stripePaymentForm {
        margin: 5px;
    }
</style>
<div id="event-detail">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa fa-cash"></i> Choose Payment Method</h4>
    </div>
    <div class="modal-body">
        <div class="form-body">
            <div class="row paymentMethods show">
                <div class="col-12 col-sm-12 mt-40 text-center" id="onlineBox">
                    @if(($setting->paypal_status == 1 || $setting->stripe_status == 1))
                        <div class="form-group payment-type box-height">
                            @if($setting->paypal_client_id != null && $setting->paypal_secret != null && $setting->paypal_status == 1)
                                <button type="submit" class="btn btn-warning waves-effect waves-light paypalPayment pull-left" data-toggle="tooltip" data-placement="top" title="Choose Plan">
                                    <i class="icon-anchor display-small"></i><span>
                                    <i class="fa fa-paypal"></i> @lang('core.payPaypal')</span>
                                </button>
                            @endif

                            @if($setting->stripe_key != null && $setting->stripe_secret != null  && $setting->stripe_status == 1)
                                <button type="submit" class="btn btn-success waves-effect waves-light stripePay" data-toggle="tooltip" data-placement="top" title="Choose Plan">
                                    <i class="icon-anchor display-small"></i><span>
                                <i class="fa fa-cc-stripe"></i> @lang('core.payStripe')</span></button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div class="row stripePaymentForm">
                @if($setting->stripe_key != null && $setting->stripe_secret != null && $setting->stripe_status == 1)
                    <div class="m-l-10">
                        <form id="stripe-form" action="{{ route('admin.billing.stripe_payment') }}" method="POST">
                            <input type="hidden" id="name" name="name" value="{{ admin()->name }}">
                            <input type="hidden" id="stripeEmail" name="stripeEmail" value="{{ $company->email }}">
                            <input type="hidden" name="plan_id" value="{{ $package->id }}">
                            <input type="hidden" name="type" value="{{ $type }}">
                            {{ csrf_field() }}

                            <div class="flex flex-wrap mb-6">
                                <label for="card-element" class="block text-gray-700 text-sm font-bold mb-2">
                                    Card Info
                                </label>
                                <div id="card-element" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></div>
                                <div id="card-errors" class="text-red-400 text-bold mt-2 text-sm font-medium"></div>
                            </div>

                            <!-- Stripe Elements Placeholder -->
                            <div class="flex flex-wrap mt-6" style="margin-top: 15px; text-align: center">
                                <button type="submit" id="card-button" class="btn btn-success inline-block align-middle text-center select-none border font-bold whitespace-no-wrap py-2 px-4 rounded text-base leading-normal no-underline text-gray-100 bg-blue-500 hover:bg-blue-700">
                                    <i class="fa fa-cc-stripe"></i> {{ __('Pay') }}
                                </button>
                            </div>
                        </form>

                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Close</button>
    </div>
</div>
@if($setting->stripe_key != null && $setting->stripe_secret != null && $setting->stripe_status == 1)
<script>
    const stripe = Stripe('{{ config("cashier.key") }}');

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('name');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;
    let validCard = false;
    const cardError = document.getElementById('card-errors');

    cardElement.addEventListener('change', function(event) {

        if (event.error) {
            validCard = false;
            cardError.textContent = event.error.message;
        } else {
            validCard = true;
            cardError.textContent = '';
        }
    });

    var form = document.getElementById('stripe-form');

    form.addEventListener('submit', async (e) => {
        event.preventDefault();

        const { paymentMethod, error } = await stripe.createPaymentMethod(
            'card', cardElement, {
                billing_details: { name: cardHolderName.value }
            }
        );

        if (error) {
            // Display "error.message" to the user...
            console.log(error);
        } else {
            // The card has been verified successfully...
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_method');
            hiddenInput.setAttribute('value', paymentMethod.id);
            form.appendChild(hiddenInput);
            $.easyAjax({
                type:'POST',
                url:'{{route('admin.billing.stripe_payment')}}',
                data: $('#stripe-form').serialize(),
                redirect:true,
                success:function(response){
                    // window.location.href = "javascript: loadView('{{URL::to('admin.billing.ajax_billing')}}')";
                }
            })


        }

    });

</script>
@endif
<script>
    $('.stripePay').click(function(e){
        e.preventDefault();
        $('.paymentMethods').removeClass('show');
        $('.stripePaymentForm').addClass('show');
        $('.modal-title').text('Enter Your Card Details');
    });

    // redirect on paypal payment page
    $('body').on('click', '.paypalPayment', function(){
        $.easyBlockUI('#package-select-form', 'Redirecting Please Wait...');
        var url = "{{ route('admin.paypal', [$package->id, $type]) }}";
        window.location.href = url;
    });

</script>

