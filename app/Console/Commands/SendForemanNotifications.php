<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\PushSubscription;
use App\Jobs\SendPushNotificationJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SendForemanNotifications extends Command
{
    /**
     * Название и сигнатура консольной команды.
     *
     * @var string
     */
    protected $signature = 'notifications:send-foreman {type? : Тип уведомления (expiring|expired|telegram)}';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Отправляет push-уведомления прорабам о подписке и новостях';

    /**
     * Выполнение консольной команды.
     */
    public function handle()
    {
        $type = $this->argument('type');

        if (!$type) {
            // Если тип не указан - отправляем все типы
            $this->sendExpiringNotifications();
            $this->sendExpiredNotifications();
            return 0;
        }

        switch ($type) {
            case 'expiring':
                $this->sendExpiringNotifications();
                break;
            case 'expired':
                $this->sendExpiredNotifications();
                break;
            case 'telegram':
                $this->sendTelegramNotifications();
                break;
            default:
                $this->error("Неизвестный тип уведомления: {$type}");
                return 1;
        }

        return 0;
    }

    /**
     * Отправка уведомлений о скором окончании подписки (3 дня и 1 день)
     */
    protected function sendExpiringNotifications()
    {
        $this->info('📅 Проверка прорабов с истекающей подпиской...');

        // Проверяем подписки которые истекают через 3 дня
        $threeDaysUsers = User::where('account_type', 'foreman')
            ->whereNotNull('subscription_expires_at')
            ->whereDate('subscription_expires_at', now()->addDays(3)->startOfDay())
            ->get();

        foreach ($threeDaysUsers as $user) {
            $this->queuePushNotification($user, [
                'title' => '⏰ До конца подписки 3 дня',
                'body' => 'Ваша подписка "Прораб" истекает через 3 дня. Продлите подписку, чтобы не потерять доступ к функциям.',
                'icon' => '/images/icons/icon-192x192.png',
                'badge' => '/images/icons/badge-72x72.png',
                'url' => '/profile/subscription',
                'tag' => 'subscription-expiring-3days',
                'requireInteraction' => true
            ]);
        }

        $this->line("✅ Поставлено в очередь уведомлений (3 дня): {$threeDaysUsers->count()}");

        // Проверяем подписки которые истекают через 1 день
        $oneDayUsers = User::where('account_type', 'foreman')
            ->whereNotNull('subscription_expires_at')
            ->whereDate('subscription_expires_at', now()->addDay()->startOfDay())
            ->get();

        foreach ($oneDayUsers as $user) {
            $this->queuePushNotification($user, [
                'title' => '🚨 До конца подписки 1 день!',
                'body' => 'Ваша подписка "Прораб" истекает завтра! Продлите её прямо сейчас, чтобы не потерять доступ.',
                'icon' => '/images/icons/icon-192x192.png',
                'badge' => '/images/icons/badge-72x72.png',
                'url' => '/profile/subscription',
                'tag' => 'subscription-expiring-1day',
                'requireInteraction' => true
            ]);
        }

        $this->line("✅ Поставлено в очередь уведомлений (1 день): {$oneDayUsers->count()}");
    }

    /**
     * Отправка уведомлений об окончании подписки и смене роли
     */
    protected function sendExpiredNotifications()
    {
        $this->info('❌ Проверка прорабов с истекшей подпиской...');

        // Находим прорабов с истекшей подпиской, которые ещё не получили уведомление
        $expiredUsers = User::where('account_type', 'foreman')
            ->whereNotNull('subscription_expires_at')
            ->where('subscription_expires_at', '<', now())
            ->whereDoesntHave('notifications', function ($query) {
                $query->where('type', 'subscription_expired')
                    ->where('created_at', '>', now()->subHour());
            })
            ->get();

        foreach ($expiredUsers as $user) {
            // Меняем роль на клиента
            $user->account_type = 'client';
            $user->save();

            $this->queuePushNotification($user, [
                'title' => '⚠️ Подписка закончилась',
                'body' => 'Ваша подписка "Прораб" истекла. Ваша роль изменена на "Клиент". Продлите подписку для восстановления доступа.',
                'icon' => '/images/icons/icon-192x192.png',
                'badge' => '/images/icons/badge-72x72.png',
                'url' => '/profile/subscription',
                'tag' => 'subscription-expired',
                'requireInteraction' => true
            ]);

            // Создаем запись в notifications для отслеживания
            DB::table('notifications')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'subscription_expired',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $user->id,
                'data' => json_encode(['message' => 'Подписка истекла, роль изменена на клиента']),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $this->line("✅ Пользователь {$user->name} ({$user->phone}): подписка истекла, роль изменена на 'client'");
        }

        $this->line("✅ Обработано прорабов с истекшей подпиской: {$expiredUsers->count()}");
    }

    /**
     * Отправка уведомлений о подписке на Telegram канал (раз в 3 дня)
     */
    protected function sendTelegramNotifications()
    {
        $this->info('📢 Отправка уведомлений о Telegram канале...');

        // Находим прорабов, которые не получали это уведомление последние 3 дня
        $users = User::where('account_type', 'foreman')
            ->whereDoesntHave('notifications', function ($query) {
                $query->where('type', 'telegram_channel')
                    ->where('created_at', '>', now()->subDays(3));
            })
            ->inRandomOrder() // Случайный порядок для равномерной нагрузки
            ->limit(100) // Лимит для безопасности
            ->get();

        foreach ($users as $user) {
            $this->queuePushNotification($user, [
                'title' => '📱 Подпишитесь на наш Telegram',
                'body' => 'Будьте в курсе всех новых обновлений и функций ObjectPlus! Подпишитесь на наш канал.',
                'icon' => '/images/icons/icon-192x192.png',
                'badge' => '/images/icons/badge-72x72.png',
                'url' => 'https://t.me/objectplus',
                'tag' => 'telegram-channel',
                'requireInteraction' => false,
                'actions' => [
                    [
                        'action' => 'open',
                        'title' => '📢 Подписаться',
                        'icon' => '/images/icons/telegram.png'
                    ],
                    [
                        'action' => 'close',
                        'title' => 'Закрыть'
                    ]
                ]
            ]);

            // Создаем запись для отслеживания
            DB::table('notifications')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'telegram_channel',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $user->id,
                'data' => json_encode(['message' => 'Уведомление о Telegram канале отправлено']),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $this->line("✅ Поставлено в очередь уведомлений о Telegram: {$users->count()}");
    }

    /**
     * Добавление push-уведомления в очередь (оптимизация нагрузки)
     */
    protected function queuePushNotification(User $user, array $payload)
    {
        // Проверяем есть ли у пользователя активные подписки
        $hasSubscription = PushSubscription::where('user_id', $user->id)
            ->where('is_active', true)
            ->exists();

        if (!$hasSubscription) {
            $this->line("⚠️  Пользователь {$user->name} не имеет активных push-подписок");
            return;
        }

        // Добавляем задачу в очередь с задержкой для распределения нагрузки
        SendPushNotificationJob::dispatch($user->id, $payload)
            ->delay(now()->addSeconds(rand(1, 30))); // Случайная задержка 1-30 секунд
    }
}
