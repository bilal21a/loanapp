<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "card_type" => $this->card_type,
            "card_number" => $this->card_number,
            "expiry" => $this->expiry,
            "bank_name" => $this->bank_name,
            "is_default" => $this->is_default,
            "created_at" => (string) $this->created_at->toFormattedDateString(),
            "user" => $this->user
        ];
    }
}
