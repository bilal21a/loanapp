<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table = "user_loans";

    protected $fillable = [
        "loan_id",
        "user_profile_id",
        "amount",
        "due_date",
        "status",
        "approval_status",
        "request_date",
        "duration",
        "interest",
        "is_settled"
    ];

    public function user() {
        return $this->belongsTo(Profile::class, "user_profile_id");
    }

    public function recoveryPlan() {
        return $this->hasOne(DebtRecovery::class, "loan_id");
    }

    public function category() {
        return $this->belongsTo(LoanCategory::class, "loan_id");
    }

    public function loans() {
        return self::where("status", 1)->where("approval_status", "approved")->get();
    }

    public function loanInterest($amount, $rate, $time, $event) {
        $rate = $rate/100;
        $principal = $amount;

        switch ($event) {
            case "daily":
                $t = $time/365;
                break;

            case "weekly":
                $t = $time/365;
                break;

            case "monthly":
                $t = $time/12;
                break;

            default:
                $t = $time/12;
                break;
        }

        $interest = ($principal*$rate) / $time;

       return    number_format($interest, 2);
    //    return    number_format($principal*$rate, 2);
    }

    public function commissionGenerator($agent) {
        $wallet = Account::where("user_profile_id", $agent->id)->first();
        $loan_sum = 0;
        $interest = 0;
        foreach ($agent->referees as $key => $subagent) {
                $level1_percentage = 1.5;
                $level2_percentage = 1.0;
                if(count($subagent->user->loans) > 0) {
                    foreach ($subagent->user->loans as $loan) {
                        if($loan->amount > 0 && $loan->approval_status === "approved" && $loan->is_settled = 1) {
                            $loan_sum += $loan->amount;
                        }
                    }
                    if($loan_sum <= 50000 && $loan_sum > 0) {
                        if ($key == 0) {
                            $interest = ($loan_sum / 100) * $level1_percentage;
                            $agent->accounts()->first()->update([
                                "current_balance" => $agent->accounts()->first()->current_balance + $interest,
                                "prev_balance" => $agent->accounts()->first()->current_balance,
                                "amount" => $interest
                            ]);
                        }
                        elseif ($key == 1) {
                            $interest = ($loan_sum / 100) * $level2_percentage;
                            $agent->accounts()->first()->update([
                                "current_balance" => $agent->accounts()->first()->current_balance + $interest,
                                "prev_balance" => $agent->accounts()->first()->current_balance,
                                "amount" => $interest
                            ]);
                            $subagent->user->accounts()->first()->update([
                                "current_balance" => $subagent->accounts()->first()->current_balance + ($loan_sum / 100) * $level1_percentage,
                                "prev_balance" => $subagent->accounts()->first()->current_balance,
                                "amount" => ($loan_sum / 100) * $level1_percentage
                            ]);

                        }
                        elseif ($key == 2) {
                            $interest = ($loan_sum / 100) * $level2_percentage;
                            $agent->accounts()->first()->update([
                                "current_balance" => $wallet->current_balance + $interest,
                                "prev_balance" => $wallet->current_balance,
                                "amount" => $interest
                            ]);
                            $subagent->user->accounts()->first()->update([
                                "current_balance" => $subagent->accounts()->first()->current_balance + ($loan_sum / 100) * $level1_percentage,
                                "prev_balance" => $subagent->accounts()->first()->current_balance,
                                "amount" => ($loan_sum / 100) * $level1_percentage
                            ]);
                        }
                        if( is_array($subagent->referees) && count($subagent->referees) > 0) {
                            $this->commissionGenerator($subagent);
                        }
                        return;
                    }
                }
        }
        return;
    }

}
