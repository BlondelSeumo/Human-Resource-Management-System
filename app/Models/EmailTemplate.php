<?php

namespace App\Models;

use App\Mail\DefaultMail;
use Illuminate\Support\Facades\Mail;

class EmailTemplate extends BaseModel
{

    // Add your validation rules here
    public static $rules = ['subject' => 'required', 'body' => 'required',];

    protected $table = 'email_templates';

    // Don't forget to fill this array
    protected $fillable = ['subject', 'body'];

    public function scopeCompany($query, $id)
    {
        return $query->where('company_id', '=', $id);
    }

    public static function getEmailTemplate($email_id)
    {
        return EmailTemplate::where('email_id', $email_id)->first();
    }

    public static function prepareAndSendEmail($email_id, $emailInfo, $fieldValues, $throw = false)
    {
        $template = EmailTemplate::getEmailTemplate($email_id);
        $emailText = $template->body;

        foreach ($fieldValues as $key => $value) {
            $emailText = str_replace('##' . $key . '##', $value, $emailText);
            $emailInfo['subject'] = str_replace('##' . $key . '##', $value, $template->subject);
        }

        $setting = Setting::first()->toArray();
        $setting['logo_image_url'] = ($emailInfo['active_company']) ? ($emailInfo['active_company'])->logo_image_url:$setting['logo_image_url'];

        $data = [
            'body' => $emailText,
            'active_company' => $emailInfo['active_company']->toArray(),
            'setting' => $setting,
            'subject' => $emailInfo['subject'],
            'from' => [
                'from_email' => $emailInfo['from_email'],
                'from_name' => $emailInfo['from_name']
            ]
        ];

        Mail::to($emailInfo['to'])->send(new DefaultMail($data));

    }

}
