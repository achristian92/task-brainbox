<?php

namespace App\Console;

use App\Console\Commands\ActivitiesDeadline;
use App\Console\Commands\ActivitiesNotLoadedByCounters;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ActivitiesNotLoadedByCounters::class,
        ActivitiesDeadline::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('actnotloaded:users')
            ->dailyAt('07:00')
            ->emailOutputOnFailure('alan.ruiz@brainbox.pe');

        $schedule->command('actdeadline:users')
            ->dailyAt('18:00')
            ->emailOutputOnFailure('alan.ruiz@brainbox.pe');

        $schedule->command('actbyapproval:users')
            ->dailyAt('07:30')
            ->emailOutputOnFailure('alan.ruiz@brainbox.pe');
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
