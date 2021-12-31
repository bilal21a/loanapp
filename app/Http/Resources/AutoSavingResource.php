<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AutoSavingResource extends JsonResource
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
            "user_id" => auth()->guard("profile")->user()->id,
            "prefered_date" => $this->prefered_date,
            "prefered_time" => $this->prefered_time,
            "amount" => $this->amount,
            "prefered_type" => $this->prefered_type,
            "next_charge_date" => $this->next_charge_date,
            "status" => $this->status,
        ];
    }
}
