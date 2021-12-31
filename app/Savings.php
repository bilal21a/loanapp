<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Savings extends Model
{
    protected $table = "user_savings";
    protected $fillable = ["user_profile_id", "amount", "channel", "account_id"];

    public function user() {
        return $this->belongsTo(Profile::class, "user_profile_id");
    }

    public function history(int $user_id, int $account_id, float $amount, string $channel) {
       return self::create([
            "user_profile_id" => $user_id, 
            "account_id" => $account_id,
            "amount" => $amount, 
            "channel" => $channel
        ]);
    }

    public function total_withdrawal() {
        return self::where("channel", "withdrawal")->sum("amount");
    }

    public function quick_savings() {
        return self::where("channel", "Quick save")->sum("amount");
    }

    public function auto_savings() {
        return self::where("channel", "AutoSave")->sum("amount");
    }
}
