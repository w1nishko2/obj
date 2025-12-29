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
    // =============== ОБРАБОТКА ОЧЕРЕДЕЙ (РАЗДЕЛЕНЫ ПО ТИПАМ) ===============
    
    // Очередь обработки изображений (высокий приоритет)
    // Обрабатываем каждую минуту, останавливаемся когда очередь пуста
    $schedule->command('queue:work --queue=images --stop-when-empty --tries=3 --timeout=300')
        ->everyMinute()
        ->withoutOverlapping()
        ->runInBackground();
    
    // Очередь push-уведомлений (низкий приоритет)
    // Обрабатываем каждые 2 минуты, не перегружаем сервер
    $schedule->command('queue:work --queue=push-notifications --stop-when-empty --tries=3 --timeout=120')
        ->everyTwoMinutes()
        ->withoutOverlapping()
        ->runInBackground();
    
    // Дефолтная очередь для остальных задач
    $schedule->command('queue:work --queue=default --stop-when-empty --tries=3')
        ->everyMinute()
        ->withoutOverlapping()
        ->runInBackground();
    
    // Проверка истёкших подписок (ежедневно в 00:00 по московскому времени)
    $schedule->command('subscriptions:check-expired')
        ->dailyAt('00:00')
        ->timezone('Europe/Moscow');

    // =============== PUSH-УВЕДОМЛЕНИЯ ДЛЯ ПРОРАБОВ ===============
    
    // Уведомления о скором окончании подписки (3 дня и 1 день)
    // Проверяем каждый день в 10:00 утра по МСК
    $schedule->command('notifications:send-foreman expiring')
        ->dailyAt('10:00')
        ->timezone('Europe/Moscow')
        ->withoutOverlapping()
        ->runInBackground();

    // Обработка истекших подписок и смена роли
    // Проверяем каждый час с 00:00 до 23:00
    $schedule->command('notifications:send-foreman expired')
        ->hourly()
        ->between('00:00', '23:00')
        ->timezone('Europe/Moscow')
        ->withoutOverlapping()
        ->runInBackground();

    // Уведомления о Telegram канале (раз в 3 дня, порциями)
    // Отправляем в 14:00, 18:00 и 21:00 по МСК
    $schedule->command('notifications:send-foreman telegram')
        ->days([0, 3, 6]) // Каждые 3 дня (воскресенье, среда, суббота)
        ->at('14:00')
        ->timezone('Europe/Moscow')
        ->withoutOverlapping()
        ->runInBackground();

    $schedule->command('notifications:send-foreman telegram')
        ->days([0, 3, 6])
        ->at('18:00')
        ->timezone('Europe/Moscow')
        ->withoutOverlapping()
        ->runInBackground();

    $schedule->command('notifications:send-foreman telegram')
        ->days([0, 3, 6])
        ->at('21:00')
        ->timezone('Europe/Moscow')
        ->withoutOverlapping()
        ->runInBackground();
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
