<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\FormRequest;

class LoginRequest extends FormRequest
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
            "email" => "required|email",
            "password" => "required",
        ];
    }

    public function messages() {
        return [
            "email.required" => "An email is required for this operation",
            "email.email" => "Use a valid email address",
            "password.required" => "Your password is required for this operation"
        ];
    }
}
