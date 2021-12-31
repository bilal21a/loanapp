<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RepliesResource extends JsonResource
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
            'titcket_id' => $this->ticket_id,
            'user_id' => $this->user_id,
            'body' => $this->body,
            'status' => $this->status,
            'attachment' => asset('storage/'.$this->attachment),
            'created_at' => (string) $this->created_at->toFormattedDateString(),
        ];
    }
}
