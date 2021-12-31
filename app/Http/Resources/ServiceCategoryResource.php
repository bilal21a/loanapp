<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceCategoryResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'service_charge' => $this->service_charge,
            'type' => $this->type,
            'logo' => asset('imgs/logos/'.$this->logo),
            'created_at' => (string) $this->created_at,
            'services' => $this->services,
            'status' => $this->status
        ];
    }
}
