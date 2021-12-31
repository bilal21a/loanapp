<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Profile;
use App\Loan;

class LoanCommission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan-commission:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates loan commission to agents based on referrals';

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
        $agents = Profile::where("user_type", "agent")->where("isVerified", 1)->where("is_active", 1)->get();

        foreach ($agents as $agent) {
            if(count($agent->referees) > 0) {
                (new Loan)->commissionGenerator($agent);
            }
        }
    }
}
