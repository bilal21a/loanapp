<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        "next_withdrawal_day",
        "withdrawal_day",
        "user_profile_id",
        "user_account_id",
        "last_withdrawal_day",
        "status",
    ];
    public function user() {
        return $this->hasMany(Profile::class, "id");
    }

    public function account() {
        return $this->belongTo(Account::class, "id");
    }
}
