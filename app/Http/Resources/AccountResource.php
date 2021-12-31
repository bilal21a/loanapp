<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
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
            "user_id" => $this->user_profile_id,
            "category_id" => $this->account_category_id,
            "account_number" => $this->account_number,
            "bank_name" => $this->bank_name,
            "current_bal" => $this->current_balance,
            "previous_bal" => $this->prev_balance,
            "status" => $this->status,
            "created_at" => (string) $this->created_at,
            "updated_at" => (string) $this->updated_at,
            "account_type" => new AccountCategoryResource($this->accountType),
            "user" => $this->user,

        ];
    }
}
