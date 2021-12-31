<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NextOfKinResource extends JsonResource
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
            "name" => $this->name,
            "relationship" => $this->relationship,
            "phoneNumber" => $this->phoneNumber,
            "email" => $this->email,
            "user" => $this->user,
            "status" => $this->status,
        ];
    }
}
