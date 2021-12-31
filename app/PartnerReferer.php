<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserInvestment;

class PartnerReferer extends Model
{
    protected  $table = "partner_referers";
    protected  $fillable = ["partner_id", "user_profile_id"];

    public function partner() {
        return $this->belongsTo(Investment::class, 'partner_id');
    }

    public function user() {
        return $this->belongsTo(Profile::class, 'user_profile_id');
    }

    public function interestGenerator($amount, $investments, $referer_id) {
        $referer = UserInvestment::where("user_profile_id", $referer_id)->first();
        if($referer) {
            foreach ($investments as $key => $investment) {
                $first_level = ($investment->amount/100) * 0.2;
                $last_level = ($investment->amount/100) * 0.4;
                if ($key == 5 || $key == 6 || $key == 7 || $key == 8) {
                    $referer->update(["interest" => $referer->interest + $last_level]);
                }
                else {
                    $referer->update(["interest" => $referer->interest + $first_level]);
                }
                if(is_array($investment->referees) && count($investment->referees) > 0 && count($investment->referees) <= 9) {
                    $this->interestGenerator($investment->amount, $investment->referees, $investment->referer->id);
                }
                return;
            }
        }
        return;
    }
}
