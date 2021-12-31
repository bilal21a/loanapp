<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $fillable = ['profile_id', 'title', 'attachment', 'body', 'status'];

    public function replies() {
        return $this->hasMany(Reply::class);
    }

    public function user() {
        return $this->belongsTo(Profile::class, 'profile_id');
    }
}
