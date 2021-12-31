<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\TransactionSuccessMail;
use App\Mail\TransactionFailedMail;
use App\Mail\AutosaveReportMail;
use App\Profile;
use App\AutoSaving;
use App\Transaction;
use Carbon\Carbon;

class AutoSave implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    { 
        $user = Profile::with("autoSavingConfig")->with("cards")->get();
 
        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();
        $accounts = array();
        foreach ($user as $key => $value) {
            foreach ($value->cards as $key => $card) {
                foreach ($value->autoSavingConfig as $key => $setting) {
                    if($setting->status && $setting->prefered_date === $currentDate && $setting->prefered_time !== $currentTime) {
                        $data = (object) array(
                            "auth_code" => $card->auth_code,
                            "amount" => $setting->amount,
                            "email" => $value->email,
                        );
                        $date = (new AutoSaving)->dateCalculator($setting->prefered_type);
                        $setting->update([
                            "prefered_date" => $date->toDateString(),
                            "next_charge_date" => $date->toDateString(),
                        ]);

                        $response = \Payment::chargeAuthorization($data);

                        if(!$response["status"]) {
                            \Mail::to("support@mavunifs.com")->send(new AutosaveReportMail($response["message"]));
                            return;
                        }
                
                        if($response["status"] && $response["data"]["status"] === "failed") {
                            $user = Profile::where("email", $response["data"]["customer"]["email"])->first();
                            $result = (object) array(
                                "name" => $user->first_name,
                                "amount" => ($response["data"]["amount"])/100,
                                "status" => $response["data"]["status"],
                                "response" => $response["data"]["gateway_response"],
                            );
                            \Mail::to($user->email)->send(new TransactionFailedMail($result));
                            return;
                        }
                
                        if($response["status"] && $response["data"]["status"] === "success") {
                            $user = Profile::where("email", $response["data"]["customer"]["email"])->first();
                            $account = Account::where("user_profile_id", $user->id)->first();
                            $result = (object) array(
                                "name" => $user->first_name,
                                "amount" => ($response["data"]["amount"])/100,
                                "status" => $response["data"]["status"],
                                "response" => $response["data"]["gateway_response"],
                            );
                            $data = (new Account)->deposit(
                                ($response["data"]["amount"])/100,  
                                $account->current_balance, 
                                $account->prev_balance
                            );
                            if($account) {
                                $account->update([
                                    "amount" => ($data["deposit_amount"])/100,
                                    "current_balance" => $data["current_balance"],
                                    "prev_balance" => $data["prev_balance"],
                                ]);
                                // (new Savings)->history($user->id, $data["deposit_amount"], "AutoSave")
                                $transaction =  (new Transaction)->transaction(
                                    $account->user, bin2hex(random_bytes(5)), 
                                    $data["deposit_amount"], $account->id, 
                                    config("settings.sitename"), "success", "credit", 
                                    "Savings", "Auto save" 
                                )
                                ? 
                                    \Mail::to($user->email)->send(new TransactionSuccessMail($result))
                                :   \Mail::to("support@mavunifs.com")->send(new AutosaveReportMail(["An error occured saving user transaction"]));
                            }
                            //\Mail::to($user->email)->send(new TransactionSuccessMail($result));
                            return;
                        }                   
                    }
                } 
            }
        }   
    }
}
