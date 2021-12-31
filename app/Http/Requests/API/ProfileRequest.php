<?php

namespace App\Http\Requests\API;

//use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\API\FormRequest;

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
            "first_name" => "required|string",
            "last_name" => "required|string",
            "gender" => "sometimes|required|string",
            "city" => "sometimes|tring",
            "email" => "required|email",
            "phone_number" => "required",
            "password" => "required|min:6|confirmed"
        ];
    }
}
