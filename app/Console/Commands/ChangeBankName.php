<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Account;

class ChangeBankName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank:change';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change bank name on the accounts table';

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
        $accounts = Account::where('bank_name', 'Wema Bank')->get();
        foreach ($accounts as $account) {
            $account->update(['bank_name' => 'Mavunif Wallet']);
        }
    }
}
