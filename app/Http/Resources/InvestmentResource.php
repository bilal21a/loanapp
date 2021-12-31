<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentResource extends JsonResource
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
            "investment_id" => $this->investment_id,
            "amount" => $this->amount,
            "interest" => $this->interest,
            "status" => $this->status,
            "slots" => $this->slots,
            "referer_code" => $this->referer_code,
            "created_at" => (string) $this->created_at,
            "investment" => new InvestmentCategoryResource($this->investment),
        ];
    }
}
