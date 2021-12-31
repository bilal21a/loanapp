<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $table = 'social_links';
    protected  $fillable = [
        'user_profile_id', 'name', 'handle', 'status', 'approval_status', 'last_location', 'points'
    ];

    public function user() {
        return $this->belongsTo(Profile::class, 'user_profile_id');
    }
}
