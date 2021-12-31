<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInvestment extends Model
{
    protected $table = "user_investments";

    protected $fillable = [
        "user_profile_id",
        "investment_id",
        "amount",
        "interest",
        "status",
        "charge_count",
        "slots",
        "referer_code"
    ];

    public function investment() {
        return $this->belongsTo(Investment::class, "id");
    }

    public function user() {
        return $this->belongsTo(Profile::class, "user_profile_id");
    }

    public function total_investment() {
        return self::where("status", 1)->sum("amount");
    }
}
