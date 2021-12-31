<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServicesResource extends JsonResource
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
            'id' => $this->id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'code' => $this->code,
            'discount' => $this->discount,
            'amount' => $this->amount,
            'service_charge' => $this->category->service_charge,
            'category' => $this->category->name,
            'category_service_charge' => $this->category_service_charge,
            'category_logo' => asset('imgs/logos/'.$this->category->logo),
            'created_at' => (string) $this->created_at

        ];
    }
}
