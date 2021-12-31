<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RefererResource extends JsonResource
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
            'referer_id' => $this->referer_id,
            'approval_status' => $this->approval_status,
            'status' => $this->status,
            'created_at' => (string) $this->created_at->toFormattedDateString(),
            'updated_at' => (string) $this->updated_at->toFormattedDateString(),
            'user' => new ProfileResource($this->user),
            'referer' => $this->referer,
            'kyc' => $this->user->kyc,
        ];
    }
}
