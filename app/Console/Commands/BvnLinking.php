<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Account;
use App\Monnify;
use App\Profile;

class BvnLinking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bvn:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link bvn to individual virtual account numbers on Monnify';

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
        $profiles = Profile::where("is_active", 1)->get();
        if(count($profiles) >= 1) {
            foreach($profiles as $key =>  $profile) {
                if(!empty($profile->kyc->bvn)) {
                    if(count($profile->accounts) > 0) {
                        //\Log::info($profile->kyc->bvn);
                      $response =  \Monnify::updateBVN($profile->accounts()->first()->account_ref, $profile->kyc->bvn);
    
                        if($response["responseMessage"] === "success" && isset($response["responseBody"])) {
                            \Log::info("BVN Linked for user ".$profile->accounts()->first()->account_number." ".$profile->first_name.' '.$profile->last_name);
                            //file_put_contents(public_path('/bvn_update_list.txt'), $key.'. '.$profile->accounts()->first()->account_number." ".$profile->first_name.' '.$profile->last_name);
                        } 
                        else {
                            \Log::info((array) $response);  
                        }
                    }
                    else {
                        \Log::info("No account found");
                    }
                }
                else {
                    \Log::info("Yet to verify BVN");
                }

            }
        }
        $this->info("BVN linker running");
    }
}
