<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "account_type" => $this->name,
            "parent" => $this->parent,
            "status" => $this->status,
            "created_at" => (string) $this->created_at,
            "updated_at" => (string) $this->updated_at,
            "categories" => $this->subCategories,
        ];
    }
}
