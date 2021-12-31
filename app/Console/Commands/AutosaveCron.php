<?php



namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Profile;
use App\AutoSaving;
use Carbon\Carbon;
use App\Jobs\TransactionJob;
use App\Transaction;
use App\Account;

class AutosaveCron extends Command

{

    /**

     * The name and signature of the console command.

     *

     * @var string

     */

    protected $signature = 'savings:autosave';



    /**

     * The console command description.

     *

     * @var string

     */

    protected $description = 'Scheduler to debit users on autosave';



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
        \Log::info("Working");
        //return;

        $users = Profile::with("autoSavingConfig")->with("cards")->get();
        //\Log::info($users);
        $data = AutoSaving::where("status", 1)->get();
        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();
        $reference = \Monnify::refCode();
        //$accounts = array();
        foreach ($users as $key => $user) {
            $card = $user->cards()->where("is_default", 1)->first();
            if($card) {
                foreach ($user->autoSavingConfig as $key => $setting) {
                    if($setting->status && $setting->prefered_date === $currentDate && $setting->prefered_time >= $currentTime) {
                        $data = (object) array(
                            "auth_code" => $card->auth_code,
                            "amount" => $setting->amount,
                            "email" => $user->email,
                            "reference" => $reference
                        );
                        $date = (new AutoSaving)->dateCalculator($setting->prefered_type);
                        $setting->update([
                            "prefered_date" => $date->toDateString(),
                            "next_charge_date" => $date->toDateString(),
                        ]);
                        $response = \Payment::chargeAuthorization($data);
                        if($response["status"] && isset($response["data"]["status"]) && $response["data"]["status"] === "success" ) {
                            $account = Account::where("user_profile_id", $user->id)->first();
                            $account->update([
                                "amount" => $setting->amount,
                                "current_balance" => $account->current_balance + $setting->amount,
                                "prev_balance" => $account->current_balance
                            ]);
                            Notification::create([
                                "type" => "savings",
                                "message" => "Your wallet has been credited with N".$setting->amount,
                                "user_profile_id" => $user->id,
                                "status" => 1
                           ]);
                           
                           Transaction::create([
                               "user_profile_id" => $user->id,        
                               "ref" => $reference,        
                               "account_id" => $account->id,        
                               "amount" => $setting->amount,        
                               "type" => "credit",        
                               "sub_type" => "auto save ",        
                               "beneficiary" => $user->first_name.' '.$user->last_name,        
                               "vendor" => "Mavunifs",        
                               "description" => "Auto saving",        
                               "status" => "pending",        
                            ]);
                               dispatch(new TransactionJob($response))->delay(Carbon::now()->addMinutes(5));
                        }
                        else {
                        //     Notification::create([
                        //         "type" => "savings",
                        //         "message" => "Auto saving attempt failed amount N".$setting->amount,
                        //         "user_profile_id" => $user->id,
                        //         "status" => 1
                        //   ]);
                            // Transaction::create([
                            //     "user_profile_id" => $user->id,        
                            //     "ref" => $reference,        
                            //     "account_id" => $account->id,        
                            //     "amount" => $setting->amount,        
                            //     "type" => "credit",        
                            //     "sub_type" => "auto save ",        
                            //     "beneficiary" => $user->first_name.' '.$user->last_name,        
                            //     "vendor" => "Mavunifs",        
                            //     "description" => "Auto saving",        
                            //     "status" => "failed",        
                            // ]);
                        }
                    }
                } 
            }
            // foreach ($user->cards as $key => $card) {
            // } 

        }

        $this->info("Auto save was made successfully");

    }

}

