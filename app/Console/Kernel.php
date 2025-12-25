<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
   protected function schedule(Schedule $schedule)
{
    // Обработка очереди задач
    $schedule->command('queue:work --stop-when-empty')->everyMinute();
    
    // Проверка истёкших подписок (ежедневно в 00:00 по московскому времени)
    $schedule->command('subscriptions:check-expired')
        ->dailyAt('00:00')
        ->timezone('Europe/Moscow');
}

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
