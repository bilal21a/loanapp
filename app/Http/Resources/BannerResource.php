<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            "banner_url" => asset("storage/banners/".$this->banner_url),
            "status" => $this->status,
            "created_at" => (string) $this->created_at,
            "updated_at" => (string) $this->updated_at,
        ];
    }
}
