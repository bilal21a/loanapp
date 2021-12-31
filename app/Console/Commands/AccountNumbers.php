<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Account;
use App\Monnify;

class AccountNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:gen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new virtual account numbers on Monnify';

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
        $accounts = Account::where("bank_name", "Providus Bank")->get();
        if(count($accounts) >= 1) {
            foreach($accounts as $account) {

              $response =  \Monnify::getAccountNumbers($account->account_ref);

                if($response["responseMessage"] === "success" && isset($response["responseBody"])) {
                    $account->update([
                        "account_number" => $response["responseBody"]["accounts"][0]["accountNumber"],
                        "bank_name" => $response["responseBody"]["accounts"][0]["bankName"],
                        "bank_code" => $response["responseBody"]["accounts"][0]["bankCode"]
                    ]);
                    //\Log::info((array) $account);
                } 
                else {

                    \Log::info((array) $response);  
                    $account->update(["bank_name" => "Mavunifs Wallet"]);
              }
            }
        }
        $this->info("Account numbers updater running");
    }
}
