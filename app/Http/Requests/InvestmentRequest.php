<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvestmentRequest extends FormRequest
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
            "name" => "required|string",
            "type" => "required|string",
            "amount" => "required|numeric|between:0,999999999",
            "duration" => "required|numeric",
            "interest_rate" => "required|numeric",
            "file" => "required|mimes:jpg,png,PNG,svg,SVG,JPG,jpeg,JPEG|max:6024",
            "amount_per_slot" => "required|numeric",
            "description" => "required|string"
        ];
    }
}
