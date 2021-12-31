<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Savings;
use App\AccountCategory;
use Carbon\Carbon;

class MonnifyController extends Controller
{

    public function index() {
        return \Monnify::index();
    }

    public function getToken(Request $request) {

        $accounts = Account::select("id","account_category_id","user_profile_id","current_balance", "prev_balance")
                            ->where("status", 1)->where("current_balance", ">", 5000)->get();
        foreach($accounts as $account) {
            $category = AccountCategory::find($account->account_category_id);
            if($category->type === "default" && $category->interest_rate !== null) {
                
                $interest = (new Account)->interestCalculator($account->current_balance, $category->interest_rate, 1, "savings");
                $deposit = (new Account)->deposit($interest, $account->current_balance, $account->prev_balance, );
                $userInterest = \DB::table("interest")->where("user_profile_id", $account->user_profile_id)->first();
                $last_withdrawal = Savings::where("channel", "withdrawal")->where("account_id", $account->id)
                ->where("user_profile_id", $account->user_profile_id)->latest()->first()->created_at;
                $current_date = Carbon::now();

                if($last_withdrawal->diffInDays($current_date) >= $category->interest_interval ) {
                    $deposit = (new Account)->deposit($userInterest->value, $account->current_balance, $account->prev_balance, );
                       $update = $account->update([
                                       "current_balance" => $deposit["current_balance"],
                                       "prev_balance" => $deposit["prev_balance"],
                                       "amount" => $deposit["deposit_amount"],
                                    ]);
                       $resetInterest = \DB::update(" update interest set value ='". 0.00 ."'where user_profile_id", [$account->user_profile_id]);
                       if($update && $resetInterest) {
                         $history =  (new Savings)->history($account->user_profile_id, $account->id, $userInterest->value, "Interest on savings");
                       }                
                } else {
                    $interest = $interest+$userInterest->value;
                    $resetInterest = \DB::update(" update interest set value = '".$interest."'where user_profile_id", [$account->user_profile_id]);
                }


            }
        }
        //return \Monnify::getAuthToken();
    }

    public function initializeTransaction() {
        return \Monnify::initializeTransaction();
    } 

    public function getTransactionResponse(Request $request) {
        return $request->all();
        //return \Monnify::webHooKNotification();
    }

    public function getAllTransactions() {}
    

    public function getUserTransaction() {}
}
