<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AffiliateResource extends JsonResource
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
            "partner_id" => $this->partner_id,
            "user_profile_id" => $this->user_profile_id,
            "created_at" => (string) $this->created_at,
            "updated_at" => (string) $this->updated_at,
            "profile" => new ProfileResource($this->user)
        ];
    }
}
