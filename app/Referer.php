<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referer extends Model
{
    protected $table = 'referers';

    protected  $fillable = [
        'user_profile_id', 'referer_id',
        'status', 'approval_status'
    ];

    public function referer() {
        return $this->belongsTo(Profile::class, 'referer_id');
    }

    public function user() {
        return $this->belongsTo(Profile::class, 'user_profile_id');
    }
}
