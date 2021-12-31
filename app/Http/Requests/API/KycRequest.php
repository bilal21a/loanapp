<?php

namespace App\Http\Requests\API;

// use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\API\FormRequest;

class KycRequest extends FormRequest
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
            "photo" => "sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:5120",
            "mode_of_id" => "sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:5120",
            "dob" => "sometimes|date"
        ];
    }
}
