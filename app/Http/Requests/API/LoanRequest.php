<?php

namespace App\Http\Requests\API;

// use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\API\FormRequest;

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
            "amount" => "required|numeric|between:0,999999.99",
            "duration" => "required|numeric",
        ];
    }

    public function messages() {
        return [
            "amount.required" => "An amount is required",
            "amount.numeric" => "The amount must be numeric",
            "duration.required" => "Duration is required",
            "duration.numeric" => "Duration must be numeric",
        ];
    }
}
