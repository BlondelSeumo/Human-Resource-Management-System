<?php

namespace App\Http\Requests\Site;

use App\Http\Requests\SiteCoreRequest;

class SignupRequest extends SiteCoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_name' => 'required',
            'sub_domain' => module_enabled('Subdomain') ? 'required|min:4|unique:companies,sub_domain|max:50|sub_domain' : '',
            'contact' => 'required',
            'address' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:admins|unique:companies',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required|min:5'
        ];
    }

    public function prepareForValidation()
    {
        if (empty($this->sub_domain)) {
            return;
        }

        // Add servername domain suffix at the end
        $subdomain = trim($this->sub_domain, '.') . '.' . get_domain();
        $this->merge(['sub_domain' => $subdomain]);
        request()->merge(['sub_domain' => $subdomain]);
    }

}
