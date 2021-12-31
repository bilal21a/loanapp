<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            "first_name" => "required|string|min:4",
            "last_name" => "required|string|min:4",
            "gender" => "required|string",
            "city" => "string",
            "email" => "required|email",
            "phone_number" => "required",
            "password" => "required|min:6|confirmed"
        ]; 
    }

    public function messages() {
        return [
            "first_name.required" => "Enter your first name, field_name should be first_name",
            "first_name.string" => "First must be string not numbers",
            "first_name.min" => "First name must be more than 3 characters",
            "last_name.required" => "Enter your first name, field_name should be last_name",
            "last_name.string" => "First must be string not numbers",
            "last_name.min" => "First name must be more than 3 characters",
            "email.required" => "An email is required for this operation",
            "email.email" => "Use a valid email address",
            "city.required" => "City must be a string",
            "phone_number.required" => "Enter your phone number",
            "password.required" => "Password is required", 
            "gender.required" => "Your gender is required",
            "gender.string" => "Gender must be a string"

        ];
    }
}
