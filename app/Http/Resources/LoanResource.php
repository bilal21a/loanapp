<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
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
            "user_profile_id" => $this->user_profile_id,
            "amount" => $this->amount,
            "duration" => $this->duration,
            "due_date" => $this->due_date,
            "interest" => $this->interest,
            "request_date" => $this->request_date,
            "expiry" => $this->expiry,
            "approval_status" => $this->approval_status,
            "status" => $this->status,
            "is_settled" => $this->is_settled,
            "created_at" => (string) $this->created_at->toFormattedDateString(),
            "updated_at" => (string) $this->updated_at->toFormattedDateString(),
            "recovery_plan" => $this->recoveryPlan,
            "category" => $this->category,
            "user" => $this->user,
        ];
    }
}
