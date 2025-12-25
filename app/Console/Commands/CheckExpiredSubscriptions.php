<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;

class CheckExpiredSubscriptions extends Command
{
    /**
     * Название и сигнатура консольной команды.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expired';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Проверяет и деактивирует истёкшие подписки';

    /**
     * Выполнение консольной команды.
     */
    public function handle()
    {
        $this->info('🔍 Проверка истёкших подписок...');

        // Находим все активные подписки с истёкшим сроком
        $expiredSubscriptions = Subscription::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->with(['user', 'plan'])
            ->get();

        if ($expiredSubscriptions->isEmpty()) {
            $this->info('✅ Истёкших подписок не найдено.');
            return 0;
        }

        $this->warn("⚠️  Найдено истёкших подписок: {$expiredSubscriptions->count()}");

        $successCount = 0;
        $errorCount = 0;

        foreach ($expiredSubscriptions as $subscription) {
            try {
                $user = $subscription->user;
                $plan = $subscription->plan;

                // Деактивируем подписку
                $subscription->checkExpiration();

                $this->line("✓ Деактивирована подписка #{$subscription->id}");
                $this->line("  Пользователь: {$user->name} (ID: {$user->id})");
                $this->line("  Тариф: {$plan->name}");
                $this->line("  Дата истечения: {$subscription->expires_at->format('d.m.Y H:i')}");
                $this->line('');

                // Логируем в файл
                Log::info('Subscription expired and deactivated', [
                    'subscription_id' => $subscription->id,
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'plan_name' => $plan->name,
                    'expired_at' => $subscription->expires_at->toDateTimeString(),
                ]);

                $successCount++;

            } catch (\Exception $e) {
                $this->error("✗ Ошибка при деактивации подписки #{$subscription->id}: {$e->getMessage()}");
                
                Log::error('Error deactivating expired subscription', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                $errorCount++;
            }
        }

        // Итоговая статистика
        $this->info('');
        $this->info('📊 Результаты проверки:');
        $this->info("   Успешно деактивировано: {$successCount}");
        
        if ($errorCount > 0) {
            $this->error("   Ошибок: {$errorCount}");
        }

        $this->info('');
        $this->info('✅ Проверка завершена!');

        return 0;
    }
}
