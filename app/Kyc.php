<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    protected $table ="kyc";

    protected $fillable = [
        "user_profile_id",
        "dob",
        "profile_photo",
        "means_of_identity",
        "bvn",
        "status",
        "last_location",
        "national_id",
        "residential_document",
        "residential_address",
        "points"
    ];

    public function user() {
        return $this->belongsTo(Profile::class);
    }
}
