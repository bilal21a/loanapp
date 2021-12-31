<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Account;
use App\Savings;
use App\AccountCategory;
use Carbon\Carbon;

class SavingsInterest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'savings:interest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily interest on users account';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     *
     */
    public function handle()
    {
        $accounts = Account::select("id","account_category_id","user_profile_id","current_balance", "prev_balance")
                            ->where("status", 1)->where("current_balance", ">", 5000)->get();
        foreach($accounts as $account) {

            $category = AccountCategory::find($account->account_category_id);
            if($category->type === "default" && $category->interest_rate !== null) {
                $interest = (new Account)->interestCalculator($account->current_balance, $category->interest_rate, 1, "savings");
                $deposit = (new Account)->deposit($interest, $account->current_balance, $account->prev_balance);
                $userInterest = \DB::table("interest")->where("user_profile_id", $account->user_profile_id)->first();
                $last_withdrawal = Transaction::where("type", "debit")->where("account_id", $account->id)
                ->where("user_profile_id", $account->user_profile_id)->latest()->first()->created_at;
                $current_date = Carbon::now();

                if($last_withdrawal->diffInDays($current_date) >= $category->interest_interval ) {
                    $deposit = (new Account)->deposit($userInterest->value, $account->current_balance, $account->prev_balance);
                       $update = $account->update([
                                       "current_balance" => $deposit["current_balance"],
                                       "prev_balance" => $deposit["prev_balance"],
                                       "amount" => $deposit["deposit_amount"],
                                    ]);
                       $resetInterest = \DB::update(" update interest set value ='". 0.00 ."'where user_profile_id", [$account->user_profile_id]);
                       if($update && $resetInterest) {
                        //  $history =  (new Savings)->history($account->user_profile_id, $account->id, $userInterest->value, "Interest on savings");
                         $transaction =  (new Transaction)->transaction(
                            $account->user, bin2hex(random_bytes(5)),
                            $interest, $account->id,
                            config("settings.sitename"), "success", "credit",
                            "Savings", "Interest on savings"
                          );
                        }
                } else {
                    $interest = $interest+$userInterest->value;
                    $resetInterest = \DB::update(" update interest set value = '".$interest."'where user_profile_id", [$account->user_profile_id]);
                }


            }
        }
        $this->info("Interest generated successfully");
    }
}
