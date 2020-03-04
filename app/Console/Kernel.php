<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\DailyDigest;
use App\Console\Commands\InstantlyDigest;
use App\Console\Commands\NoRegisteredHoursChecker;
use App\Console\Commands\WeeklyDigest;
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
        InstantlyDigest::class,
        DailyDigest::class,
        WeeklyDigest::class,
        NoRegisteredHoursChecker::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(InstantlyDigest::class)->everyMinute();//->appendOutputTo('/proc/1/fd/1');
        $schedule->command(DailyDigest::class)->dailyAt('08:00');
        $schedule->command(WeeklyDigest::class)->weeklyOn(1, '08:00');

        $schedule->command(NoRegisteredHoursChecker::class)->dailyAt('08:00');//->appendOutputTo('/proc/1/fd/1');
    }

    /**
     * Register the Closure based commands for the application.
     */
    protected function commands(): void
    {
        require base_path('routes/console.php');
    }
}
