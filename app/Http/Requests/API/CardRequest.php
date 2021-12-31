<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\FormRequest;

class CardRequest extends FormRequest
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
            "cardNumber" => "required|numeric",
            "cardPin" => "required|numeric",
            "cardcvv" => "required|numeric",
            "expiryMonth" => "required|numeric",
            "expiryYear" => "required|numeric"
        ];
    }

    public function message() {
        return [
            "cardNumber.required" => "Enter your card number",
            "cardPin.required" => "PIN is required",
            "cardPin.min" => "PIN must be four digits",
            "cardPin.required" => "cvv is required",
            "cardCvv.int" => "cvv must be a number",
            "expiryMonth.required" => "expiry month is required",
            "expiryMonth.min" => "expiry month must be two digits",
            "expiryYear.required" => "expiryYear is required",
            "expiryYear.min" => "expiryYear must be four digits",

        ];
    }
}
