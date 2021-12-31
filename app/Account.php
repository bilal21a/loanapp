<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AutoSaving;
use App\Transaction;
use App\Services;

class Account extends Model
{
    protected $table = "user_account";
    protected $foreignKey = "user_profile_id";

    protected $fillable = [
        "user_profile_id",
        "account_category_id",
        "amount",
        "current_balance",
        "prev_balance",
        "status",
        "account_number",
        "currency",
        "bank_code",
        "bank_name",
        "account_ref"
    ];

    public function user()
    {
        return $this->belongsTo(Profile::class, "user_profile_id");
    }

    public function withdrawalSettings()
    {
        return $this->hasMany(Withdrawal::class, "user_account_id");
    }

    public function accountType()
    {
        return $this->belongsTo(AccountCategory::class, "account_category_id");
    }

    public function userAccount($id)
    {
        return self::where("user_profile_id", $id)->first();
    }

    public function deposit(float $amount, float $current_bal, float $prev_bal) :array
    {
        $cur =  trim($current_bal) + trim($amount);
        $prev = trim($current_bal);
        $data = json_encode(["current_balance" => $cur, "prev_balance" => $prev, "deposit_amount" => $amount]);
        return  json_decode($data, true);
    }

    public function withdrawal(float $amount, float $current_bal, float $prev_bal)
    {
        $cur =  trim($current_bal) - trim($amount);
        $prev = trim($current_bal);
        $data = json_encode(["current_balance" => $cur, "prev_balance" => $prev, "withdrawal_amount" => $amount]);
        return  json_decode($data, true);
    }

    public function refund($ref)
    {
        $transaction = Transaction::where("ref", $ref)->where("status", "pending")->latest()->first();
        $account = self::find($transaction->account_id);
        $deposit = $this->deposit($transaction->amount, $account->current_balance, $account->prev_balance);
        $update = $account->update([
            "amount" => $deposit["amount"],
            "current_balance" => $deposit["current_balance"],
            "prev_balance" => $deposit["prev_balance"],
        ]);

        if ($update) {
            $transaction->update(["status" => "failed"]);
        }
    }

    public function auto_saving()
    {
        return AutoSaving::select("user_profile_id", "time_for_charge", "amount")->get();
    }

    public function autoSaveSettings($id)
    {
        return AutoSaving::where("user_profile_id", $id)->first();
    }

    public function account_balance()
    {
        return self::where("status", 1)->sum("current_balance");
    }

    public function interestCalculator($amount, $rate, $time, $event)
    {
        $rate = $rate/100;
        $principal = $amount;
        $t = $event === "investment" ? $time/12 : $time/365;

        return   $principal*$rate*$t;
    }

    public function cashBack($serviceId, $amount)
    {
        $service = Services::find($serviceId);
        // $discountedAmt = $service->discount > 0 ?
        // $amount - ($amount * $service->discount) : 0;
        if ($service->discount > 0) {
            $discountedAmt = ($amount * $service->discount) / 100;
        } else {
            $discountedAmt = $amount;
        }
        // $discountedAmt = $service && !empty($service->discount) ?
        // $amount - ($amount * $service->discount) : 0;

        return $discountedAmt;
    }
}
