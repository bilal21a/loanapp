<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgentApprovalResource extends JsonResource
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
            'user_profile_id' => $this->user_profile_id,
            'kyc_id' => $this->kyc->id,
            'points' => $this->points,
            'status' => $this->status,
            'status_text' => $this->status_text,
            'created_at' => (string) $this->created_at->toFormattedDateString(),
            'updated_at' => (string) $this->updated_at->toFormattedDateString(),
        ];
    }
}
