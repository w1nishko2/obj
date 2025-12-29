<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\PushSubscription;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class SendPushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Количество попыток выполнения задачи
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Таймаут выполнения задачи (секунды)
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * @var int
     */
    protected $userId;

    /**
     * @var array
     */
    protected $payload;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userId, array $payload)
    {
        $this->userId = $userId;
        $this->payload = $payload;
        
        // Устанавливаем очередь с низким приоритетом для снижения нагрузки
        $this->onQueue('push-notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $user = User::find($this->userId);
            
            if (!$user) {
                Log::warning("Пользователь {$this->userId} не найден для отправки push");
                return;
            }

            // Получаем активные подписки пользователя
            $subscriptions = PushSubscription::where('user_id', $user->id)
                ->where('is_active', true)
                ->get();

            if ($subscriptions->isEmpty()) {
                Log::info("У пользователя {$user->id} ({$user->name}) нет активных push-подписок");
                return;
            }

            // Инициализируем WebPush
            $auth = [
                'VAPID' => [
                    'subject' => config('app.url'),
                    'publicKey' => config('webpush.vapid.public_key'),
                    'privateKey' => config('webpush.vapid.private_key'),
                ],
            ];

            $webPush = new WebPush($auth);
            $webPush->setAutomaticPadding(false);

            $sentCount = 0;
            foreach ($subscriptions as $sub) {
                try {
                    $subscription = Subscription::create([
                        'endpoint' => $sub->endpoint,
                        'publicKey' => $sub->public_key,
                        'authToken' => $sub->auth_token,
                        'contentEncoding' => $sub->content_encoding ?? 'aes128gcm',
                    ]);

                    $report = $webPush->sendOneNotification(
                        $subscription,
                        json_encode($this->payload),
                        ['TTL' => 86400] // 24 часа
                    );

                    if ($report->isSuccess()) {
                        $sentCount++;
                        Log::info("Push успешно отправлен для подписки {$sub->id} пользователя {$user->name}");
                    } else {
                        Log::warning("Push не отправлен для подписки {$sub->id}: " . $report->getReason());
                        
                        // Если подписка истекла - деактивируем
                        if ($report->isSubscriptionExpired()) {
                            $sub->is_active = false;
                            $sub->save();
                            Log::info("Подписка {$sub->id} деактивирована (истекла)");
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Ошибка отправки push для подписки {$sub->id}: " . $e->getMessage());
                }
            }

            if ($sentCount > 0) {
                Log::info("✅ Отправлено {$sentCount} push-уведомлений для пользователя {$user->name} (ID: {$user->id})");
            }

        } catch (\Exception $e) {
            Log::error("Критическая ошибка в SendPushNotificationJob для пользователя {$this->userId}: " . $e->getMessage());
            throw $e; // Перебрасываем для повторной попытки
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("SendPushNotificationJob failed для пользователя {$this->userId}: " . $exception->getMessage());
    }
}
