<?php

namespace App\Http\Requests\API;

// use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\API\FormRequest;

class ServiceRequest extends FormRequest
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
            "serviceId" => "sometimes",
            "amount" => "required|numeric|between: 0,9999999"
        ];
    }
}
