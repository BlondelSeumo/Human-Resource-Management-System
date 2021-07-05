<?php

namespace App\Http\Requests\Admin\Language;

use App\Http\Requests\AdminCoreRequest;

class StoreRequest extends AdminCoreRequest
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


    public function rules()
    {
        return [
            'locale'   => 'required|unique:languages',
            'language' => 'required|unique:languages',
        ];
    }
}
