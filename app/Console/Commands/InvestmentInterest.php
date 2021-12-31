<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Account;
use App\Savings;
use App\Investment;
use App\Transaction;
use App\UserInvestment;
use Carbon\Carbon;

class InvestmentInterest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'investment:interest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly interest on investments';

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
     */
    public function handle()
    {
        $userinvestment = UserInvestment::where("status", 1)->get();
        foreach($userinvestment as $record) {
            $investment = Investment::find($record->investment_id);

            if($record->charge_count == $investment->duration) {
                $record->update(["status" => 0]);
            }

            if($investment->interest_rate !== null && $investment->status) {
                if($record->charge_count < $investment->duration) {
                    $account = Account::where("user_profile_id", $record->user_profile_id)->first();
                    $interest = (new Account)->interestCalculator($record->amount, $investment->interest_rate, 1, "investment");
                    $deposit = (new Account)->deposit($interest, $account->current_balance, $account->prev_balance);
                    $update = $account->update([
                                    "current_balance" => $deposit["current_balance"],
                                    "prev_balance" => $deposit["prev_balance"],
                                    "amount" => $deposit["deposit_amount"],
                                    ]);
                                $record->update(["charge_count" => $record->charge_count+1]);
                    if($update) {
                    //   $history =  (new Savings)->history($account->user_profile_id, $account->id, $interest, "Interest on Investment");
                      $transaction =  (new Transaction)->transaction(
                        $account->user, bin2hex(random_bytes(5)),
                        $interest, $account->id,
                        config("settings.sitename"), "success", "credit",
                        "Investemnt", "Interest on investment"
                      );
                    }
                }
            }
        }

        $this->info("Investment Interest generated successfully");
    }
}
