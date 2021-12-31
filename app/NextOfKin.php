<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NextOfKin extends Model
{
    protected $table = "next_of_kin";
    protected $fillable = ["name", "relationship", "email", "phoneNumber", "user_profile_id", "status"];

    public function user() {
        return $this->belongsTo(Profile::class, 'id');
    }
}
