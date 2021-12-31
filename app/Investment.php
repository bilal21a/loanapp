<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $fillable = [
        "name",
        "type",
        "description",
        "amount",
        "duration",
        "interest_rate",
        "total_investment",
        "amount_per_investor",
        "status",
        "cover_photo",
        "referer_code"
    ];

    public function investors() {
        return $this->hasMany(UserInvestment::class, "investment_id");
    }

    public function referees() {
        return $this->hasMany(PartnerReferer::class, "partner_id");
    }

    public function referer() {
        return $this->belongsTo(PartnerReferer::class, "user_id");
    }

    public function interestCalculator($amount, $rate, $time, $event) {
        $rate = $rate/100;
        $principal = $amount;
        $t = $event === "investment" ? $time/12 : $time/365;

       return    number_format($principal*$rate*$t, 2);
    }

    public function slotChecker($amount_per_slot, $slots, $investment_amount, $total_invested) {

        $amount_to_invest =  $amount_per_slot * $slots;
        $remaining_slots = $investment_amount - $total_invested;

        if($amount_to_invest <= $remaining_slots) {
            return true;
        }

        return false;
    }
}
