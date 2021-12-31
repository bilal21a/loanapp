<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            "ref" => $this->ref,
            "user_profile_id" => $this->user_profile_id,
            "amount"  => $this->amount,
            "account_id"  => $this->account_id,
            "vendor"  => $this->vendor,
            "description" => $this->description,
            "status"  => $this->status,
            "type"  => $this->type,
            "sub_type"  => $this->sub_type,
            "beneficiary" => $this->beneficiary,
            "created_at" => $this->created_at->toFormattedDateString(),
            "transaction_time" => $this->created_at->format("H:i:s"),
        ];
    }
}
