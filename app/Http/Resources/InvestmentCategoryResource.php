<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "name" => $this->name,
            "type" => $this->type,
            "description" => $this->description,
            "amount_per_investor" => $this->amount_per_investor,
            "duration" => $this->duration,
            "interest_rate" => $this->interest_rate,
            "status" => $this->status,
            "cover_photo" => asset("/storage/$this->cover_photo"),
            "created_at" => (string) $this->created_at,
            "updated_at" => (string) $this->updated_at,
        ];
    }
}
