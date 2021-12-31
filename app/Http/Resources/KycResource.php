<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KycResource extends JsonResource
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
            "kyc_id" => $this->id,
            "dob" => $this->dob,
            "bvn" => $this->bvn,
            "national_id" => $this->national_id,
            "residential_address" => $this->residential_address,
            "status" => $this->status,
            "residential_document" => asset('/storage/means_of_identity/'.$this->residential_document),
            "profile_photo" => asset('/storage/profile_photos/'.$this->profile_photo),
            "means_of_identity" => asset('/storage/means_of_identity/'.$this->means_of_identity),
            "updated_at" => (string) $this->updated_at,
        ];
    }
}
