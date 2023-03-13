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
         'App\Console\Commands\Biometrico',
         'App\Console\Commands\Biometrico5dic',
         'App\Console\Commands\Biometrico5dic',
         'App\Console\Commands\BiometricoTime'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('biometrico:checadas')
		->weekdays()
                ->twiceDaily(11,23);
    }
}
