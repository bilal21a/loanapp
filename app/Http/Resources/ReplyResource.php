<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
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
            'ticket_id' => $this->ticket_id,
            'body' => $this->body,
            'attachment' => asset('storage/'.$this->attachment),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'ticket' => $this->ticket
        ];
    }
}
