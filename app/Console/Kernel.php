<?php
// app/Console/Kernel.php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Send appointment reminders daily at 10 AM
        $schedule->command('appointments:send-reminders')
            ->dailyAt('10:00')
            ->withoutOverlapping();

        // Database backups daily at midnight
        $schedule->command('backup:clean')->daily()->at('00:00');
        $schedule->command('backup:run')->daily()->at('00:30');

        // Cache and log cleanup weekly on Sundays at 1 AM
        $schedule->command('cache:clear')->weekly()->sundays()->at('01:00');
        $schedule->command('log:clear')->weekly()->sundays()->at('01:15');
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