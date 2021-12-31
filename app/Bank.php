<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = "user_banks";

    protected $fillable = [
        "user_profile_id", 
        "bank_name", 
        "bank_code",
        "account_number", 
        "recipient_code"
    ];

    public function user() {
        return $this->belongsTo(Profile::class);
    }
}
