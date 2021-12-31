<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Loan;
use Carbon\Carbon;
use App\Jobs\LoanChargesFailureJob;
use App\Jobs\LoanChargesSuccessJob;

class LoanMonitor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan:monitor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor loan expiry on daily and charge users whose loan expiry are met';

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
        $loans = (new Loan)->loans();
        $today = Carbon::now()->toDateString();
        if(count($loans) >= 1) {
            foreach($loans as $loan) {

                if(!$loan->is_settled && $loan->due_date === $today ) {
                    $card =  $loan->user->cards->first();

                    $data = (object) array(
                        "auth_code" => $card->auth_code,
                        "amount" => $loan->amount + $loan->interest,
                        "email" => $loan->user->email,
                    );
                    
                    $res = \Payment::chargeAuthorization($data);

                    $loanData = (object) array(
                        "loan" => $loan,
                        "gateway_response" => $res["message"],
                        "user" => $loan->user,
                    );

                    if(!$res["status"] || $res["data"]["status"] === "failed") {
                       \dispatch(new LoanChargesFailureJob($loanData))->delay(Carbon::now()->addMinutes(5));
                    }
                    
                    $loan->update(["is_settled" => 1, "status" => 0]);
                    \dispatch(new LoanChargesSuccessJob($loanData))->delay(Carbon::now()->addMinutes(5));
                } 
                else if(!$loan->is_settled && $today > $loan->due_date) {
                    if($loan->category->interest_on_default === "fixed") {
                        $loan->update(["interest" => $loan->interest + $loan->category->interest_amount]);
                    }
                    if($loan->category->interest_on_default === "compound") {
                        $interest = ($loan->interest / 100) * $loan->category->interest_amount;
                        $loan->update(["interest" => $loan->interest + $interest]);
                    }
                }
            }
        }
        $this->info("Loan watcher running");
    }
}
