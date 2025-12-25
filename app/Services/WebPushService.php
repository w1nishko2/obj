<?php

namespace App\Services;

use App\Models\PushSubscription;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class WebPushService
{
    private WebPush $webPush;

    public function __construct()
    {
        $auth = [
            'VAPID' => [
                'subject' => config('webpush.vapid.subject'),
                'publicKey' => config('webpush.vapid.public_key'),
                'privateKey' => config('webpush.vapid.private_key'),
            ],
        ];

        $this->webPush = new WebPush($auth);

        // Настройки по умолчанию
        $this->webPush->setAutomaticPadding(true);
    }

    /**
     * Отправить уведомление конкретному пользователю
     */
    public function sendToUser(int $userId, array $payload, array $options = []): array
    {
        $subscriptions = PushSubscription::where('user_id', $userId)->get();

        if ($subscriptions->isEmpty()) {
            Log::warning("No push subscriptions found for user {$userId}");
            return [
                'success' => false,
                'message' => 'Подписки не найдены',
                'sent' => 0,
                'failed' => 0,
            ];
        }

        return $this->sendToSubscriptions($subscriptions, $payload, $options);
    }

    /**
     * Отправить уведомление всем пользователям
     */
    public function sendToAll(array $payload, array $options = []): array
    {
        $subscriptions = PushSubscription::all();

        if ($subscriptions->isEmpty()) {
            Log::warning("No push subscriptions found");
            return [
                'success' => false,
                'message' => 'Нет активных подписок',
                'sent' => 0,
                'failed' => 0,
            ];
        }

        return $this->sendToSubscriptions($subscriptions, $payload, $options);
    }

    /**
     * Отправить уведомление нескольким пользователям
     */
    public function sendToUsers(array $userIds, array $payload, array $options = []): array
    {
        $subscriptions = PushSubscription::whereIn('user_id', $userIds)->get();

        if ($subscriptions->isEmpty()) {
            Log::warning("No push subscriptions found for users: " . implode(', ', $userIds));
            return [
                'success' => false,
                'message' => 'Подписки не найдены',
                'sent' => 0,
                'failed' => 0,
            ];
        }

        return $this->sendToSubscriptions($subscriptions, $payload, $options);
    }

    /**
     * Отправить уведомление конкретным подпискам
     */
    public function sendToSubscriptions(Collection $subscriptions, array $payload, array $options = []): array
    {
        $payloadJson = json_encode($this->preparePayload($payload));
        $sendOptions = $this->prepareOptions($options);

        $sent = 0;
        $failed = 0;
        $failedSubscriptions = [];

        foreach ($subscriptions as $subscription) {
            try {
                $pushSubscription = Subscription::create($subscription->getSubscriptionArray());

                $this->webPush->queueNotification(
                    $pushSubscription,
                    $payloadJson,
                    $sendOptions
                );
            } catch (\Exception $e) {
                Log::error("Failed to queue notification for subscription {$subscription->id}: {$e->getMessage()}");
                $failed++;
                $failedSubscriptions[] = $subscription->id;
            }
        }

        // Отправка всех уведомлений
        try {
            foreach ($this->webPush->flush() as $report) {
                if ($report->isSuccess()) {
                    $sent++;
                } else {
                    $failed++;
                    $endpoint = $report->getEndpoint();
                    
                    Log::warning("Push notification failed for endpoint: {$endpoint}", [
                        'reason' => $report->getReason(),
                        'expired' => $report->isSubscriptionExpired(),
                    ]);

                    // Автоматическое удаление недействительных подписок
                    if (config('webpush.auto_cleanup') && $report->isSubscriptionExpired()) {
                        PushSubscription::where('endpoint', $endpoint)->delete();
                        Log::info("Removed expired subscription: {$endpoint}");
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to flush notifications: {$e->getMessage()}");
        }

        return [
            'success' => $sent > 0,
            'message' => "Отправлено: {$sent}, Ошибок: {$failed}",
            'sent' => $sent,
            'failed' => $failed,
            'failed_subscriptions' => $failedSubscriptions,
        ];
    }

    /**
     * Подготовить payload для отправки
     */
    private function preparePayload(array $payload): array
    {
        $defaults = config('webpush.notification_defaults', []);

        // Основные данные уведомления
        $notification = [
            'title' => $payload['title'] ?? 'Уведомление',
            'body' => $payload['body'] ?? '',
            'icon' => $payload['icon'] ?? $defaults['icon'] ?? null,
            'badge' => $payload['badge'] ?? $defaults['badge'] ?? null,
            'image' => $payload['image'] ?? null,
            'tag' => $payload['tag'] ?? $defaults['tag'] ?? 'notification',
            'requireInteraction' => $payload['requireInteraction'] ?? $defaults['requireInteraction'] ?? false,
            'renotify' => $payload['renotify'] ?? $defaults['renotify'] ?? false,
            'silent' => false, // Звук всегда включён
            'timestamp' => $payload['timestamp'] ?? time() * 1000,
            'vibrate' => $payload['vibrate'] ?? $defaults['vibrate'] ?? [200, 100, 200],
            'data' => $payload['data'] ?? [],
            'actions' => $payload['actions'] ?? []
        ];

        // Удаляем null значения
        return array_filter($notification, function($value) {
            return $value !== null;
        });
    }

    /**
     * Подготовить опции для отправки
     */
    private function prepareOptions(array $options): array
    {
        return [
            'TTL' => $options['ttl'] ?? config('webpush.ttl', 2419200),
            'urgency' => $options['urgency'] ?? config('webpush.urgency', 'normal'),
            'topic' => $options['topic'] ?? config('webpush.topic'),
            'batchSize' => $options['batchSize'] ?? 200,
        ];
    }

    /**
     * Создать простое текстовое уведомление
     */
    public static function createNotification(string $title, string $body, array $extra = []): array
    {
        return array_merge([
            'title' => $title,
            'body' => $body,
        ], $extra);
    }

    /**
     * Создать уведомление с действиями
     */
    public static function createActionNotification(string $title, string $body, array $actions, array $extra = []): array
    {
        return array_merge([
            'title' => $title,
            'body' => $body,
            'actions' => $actions,
            'requireInteraction' => true,
        ], $extra);
    }
}
