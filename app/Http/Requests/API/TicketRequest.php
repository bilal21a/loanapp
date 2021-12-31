<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\FormRequest;

class TicketRequest extends FormRequest
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
          'title' => 'sometimes|string|required',
          'body'   => 'string|required',
        ];
    }

    public function messages() {
        return [
            "title.required" => "Give the ticket a title",
            "body.required" => "Enter a message you want to send",
            "title.string" => "Title must be a string",
            "body.string" => "Body of the message must string",
        ];
    }
}
