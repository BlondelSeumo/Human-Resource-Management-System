<?php

namespace App\Http\Requests\Admin\Company;

use App\Http\Requests\AdminCoreRequest;

class UpdateRequest extends AdminCoreRequest
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
            'sub_domain' => module_enabled('Subdomain') && admin()->type == 'superadmin'?'required|min:4|max:50|sub_domain|unique:companies,sub_domain,'.$this->route('company'):'',
            'email' => 'required|email',
            'name' => 'required',
            'logo' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg|max:1000'
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
