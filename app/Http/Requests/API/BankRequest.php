<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\FormRequest;

class BankRequest extends FormRequest
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
            "bankCode" => "required",
            "bank" => "required|string",
            "account_number" => "required"
        ];
    }
}
