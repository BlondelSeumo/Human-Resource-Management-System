<?php
/**
 * Created by PhpStorm.
 * User: DEXTER
 * Date: 24/05/17
 * Time: 11:29 PM
 */

namespace App\Traits;

use App\Models\Setting;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Support\Facades\Config;

trait Settings
{

    public function setStripeConfigs()
    {
        $settings = Setting::first();
        Config::set('cashier.key', $settings->stripe_key);
        Config::set('cashier.secret', $settings->stripe_secret);
        Config::set('cashier.webhook.secret', $settings->stripe_webhook_secret);

    }

    public function setMailConfigs()
    {
        $smtpSetting = Setting::first();
        $company = company();

        $companyName = $company ? $company->company_name : $smtpSetting->mail_from_name;
        $companyEmail = $company ? $company->company_email : $smtpSetting->mail_from_email;
        if (env('APP_ENV') !== 'development') {
            Config::set('mail.driver', $smtpSetting->mail_driver);
            Config::set('mail.host', $smtpSetting->mail_host);
            Config::set('mail.port', $smtpSetting->mail_port);
            Config::set('mail.username', $smtpSetting->mail_username);
            Config::set('mail.password', $smtpSetting->mail_password);
            Config::set('mail.encryption', $smtpSetting->mail_encryption);
        }

        Config::set('mail.reply_to.name', $companyName);
        Config::set('mail.reply_to.address', $companyEmail);

        // SES and other mail services which require email from verified sources
        if(\config('mail.verified') === true){
            Config::set('mail.from.name', $smtpSetting->mail_from_name);
            Config::set('mail.from.address', $smtpSetting->mail_from_email);

        }else{
            Config::set('mail.from.name', $companyName);
            Config::set('mail.from.address', $companyEmail);
        }


        Config::set('app.url', url('/'));
        Config::set('app.name', $smtpSetting->main_name);


        (new MailServiceProvider(app()))->register();
    }

}
