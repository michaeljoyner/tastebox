<?php

namespace App\Console;

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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('backup:clean')->dailyAt('01:00');
        $schedule->command('backup:run')->dailyAt('01:30');
        $schedule->command('menus:clear-recipes')->dailyAt('03:30');
        $schedule->command('instagram-feed:refresh')->dailyAt('02:00');
        $schedule->command('instagram-feed:refresh-tokens')->monthly();
        $schedule->command('batch:round-up')->weeklyOn(5, '13:30');
        $schedule->command('batch:report-latest')->weeklyOn(5, '05:00');
        $schedule->command('menus:weekly-recipes')->weeklyOn(2, '04:30');
        $schedule->command('menus:next')->weeklyOn(5, '06:30');
        $schedule->command('orders:notify-long-pending')->everyThirtyMinutes();
        $schedule->command('newsletter:sync')->weekly()->thursdays()->at('07:00');

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
