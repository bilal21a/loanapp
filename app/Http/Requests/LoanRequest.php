<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanRequest extends FormRequest
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
            "name" => "string|required",
            "type" => "string|required",
            "max_duration" => "numeric|required",
            "max_amount" => "required|numeric|between:0,99.99",
            "interest_amount" => "required|numeric|between:0,99.99",
            "interest_rate" => "required|numeric|between:0,99.99",
            "interest_on_default" => "required|string",
            "interest_type" => "string|required",
        ];
    }
}
