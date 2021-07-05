<?php

namespace App\Http\Requests\Admin\Language;

use App\Http\Requests\AdminCoreRequest;
use App\Models\Admin;

class EditRequest extends AdminCoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
       return  true;
    }


    public function rules()
    {
        return [
        ];
    }
}
