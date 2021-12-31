<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'title' => $this->title,
            'body' => $this->body,
            'status' => $this->status,
            'attachment' => asset('storage/'.$this->attachment),
            'created_at' => (string) $this->created_at->toFormattedDateString(),
            'replies' => RepliesResource::collection($this->replies)
        ];
    }
}
