<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = "user_cards";

    protected $fillable = [
        "user_profile_id", 
        "card_number", 
        "card_type", 
        "auth_code", 
        "expiry",
        "bank_name",
        "is_default"
    ];

    public function user() {
        return $this->belongsTo(Profile::class, "user_profile_id");
    }
}
