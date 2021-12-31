<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            "cardNumber" => "required",
            "cardPin" => "required|min:4",
            "cardCvv" => "required|int",
            "expiryMonth" => "required|min:2",
            "expiryYear" => "required|min:4"
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
