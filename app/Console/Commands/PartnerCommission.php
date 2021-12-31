<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PartnerReferer;
use App\UserInvestment;

class PartnerCommission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates monthly partnership commission on investment';

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
        $investments = UserInvestment::where("status", 1)->get();
        foreach ($investments as $investment) {
            $investment->update(["interest" => ($investment->amount/100) * 2]);
            if(is_array($investment->referees) && count($investment->referees) > 0 && count($investment->referees) <= 9) {
                (new PartnerReferer)->interestGenerator($investment->amount, $investment->referees, $investment->referer->id);
            }
        }
        return 1;
    }
}
