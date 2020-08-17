<?php

namespace App\Console;

use App\Console\Commands\DeleteOldStats;
use App\Jobs\TriggerGetStatsJobsForUrls;
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
        DeleteOldStats::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('monitor:clear')->everyTenMinutes();
        $schedule->job($this->app->make(TriggerGetStatsJobsForUrls::class))->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
