<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $table = 'replies';
    protected $fillable = ['ticket_id', 'user_id', 'attachment', 'body', 'status'];

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }
}
