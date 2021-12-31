<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\AutoSave;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\AutosaveCron::class,
        Commands\SavingsInterest::class,
        Commands\InvestmentInterest::class,
        Commands\AccountNumbers::class,
        Commands\LoanCommission::class,
        Commands\PartnerCommission::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command("savings:autosave")->daily("05:00");
         $schedule->command("savings:interest")->daily()->at("0:00")
                            ->emailOutputOnFailure("support@mavunifs.com");
         $schedule->command("loan-commission:generate")->monthlyOn(5, "00:00");
         $schedule->command("commission:generate")->monthlyOn(5, "06:00");
         //$schedule->command("investment:interest")->monthly()->at("00:00");
        // $schedule->command("loan:monitor")->daily()->at("12:00")->emailOutputOnFailure("support@Mavunifs.com");
        // $schedule->job(new AutoSave);
                // start the queue worker, if its not running
        if (!$this->osProcessIsRunning('queue:work')) {
            $schedule->command('queue:work')->everyMinute()->withoutOverlapping();
        }
    }


    protected function osProcessIsRunning($needle)
    {
        // get process status. the "-ww"-option is important to get the full output!
        exec('ps aux -ww', $process_status);

        // search $needle in process status
        $result = array_filter($process_status, function($var) use ($needle) {
            return strpos($var, $needle);
        });

        // if the result is not empty, the needle exists in running processes
        if (!empty($result)) {
            return true;
        }
        return false;
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
