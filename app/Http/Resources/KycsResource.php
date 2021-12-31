<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KycsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $value = $this->value;

        if($this->type === 'means_of_identity') {
            $value = asset('/storage/'.$this->value);
        }
        if($this->type === 'profile_photo') {
            $value = asset('/storage/'.$this->value);
        }

        if($this->type === 'residential_document') {
            $value = asset('/storage/'.$this->value);
        }

        return [
            "kyc_id" => $this->id,
            "type" => $this->type,
            "value" => $value,
            "points" => $this->points,
            "status" => $this->status,
            "approval_status" => $this->approval_status,
            "reason_for_approval" => $this->reason_for_approval,
            "reason_for_disapproval" => $this->reason_for_disapproval,
            "updated_at" => (string) $this->updated_at,
            "creaated_at" => (string) $this->created_at,
        ];
    }
}
