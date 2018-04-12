<?php

namespace App\Console;

use App\Console\Commands\OrderSettled;
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
        Commands\MakeService::class,
        Commands\OrderSettled::class,
        Commands\AutoWithdraw::class,
        Commands\ShopSettled::class,
        Commands\TradeStat::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('TradeStat')->dailyAt('1:00'); //前一天的交易额、交易笔数
        $schedule->command('AutoWithdraw')->dailyAt('9:30'); //前一天的交易额、交易笔数
        $schedule->command('OrderSettled --tableSuffix=36')->dailyAt('1:10');
        $schedule->command('OrderSettled --tableSuffix=17')->dailyAt('1:20');
        $schedule->command('OrderSettled --tableSuffix=28')->dailyAt('1:30');
        $schedule->command('OrderSettled --tableSuffix=29')->dailyAt('1:40');
        $schedule->command('OrderSettled --tableSuffix=12')->dailyAt('1:50');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
