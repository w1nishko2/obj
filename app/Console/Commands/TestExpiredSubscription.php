<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Plan;
use Carbon\Carbon;

class TestExpiredSubscription extends Command
{
    protected $signature = 'test:expired-subscription {user_id=1}';
    protected $description = 'Создает тестовую просроченную подписку для проверки системы';

    public function handle()
    {
        $userId = $this->argument('user_id');
        
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("❌ Пользователь с ID {$userId} не найден!");
            return 1;
        }

        $this->info("👤 Пользователь: {$user->name} (ID: {$user->id})");
        $this->info("📊 Текущий статус:");
        $this->line("   subscription_type: " . ($user->subscription_type ?? 'null'));
        $this->line("   account_type: {$user->account_type}");
        $this->line("   subscription_expires_at: " . ($user->subscription_expires_at ? $user->subscription_expires_at->format('d.m.Y H:i') : 'null'));
        $this->newLine();

        // Находим или создаем подписку
        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$subscription) {
            // Создаем новую просроченную подписку
            $plan = Plan::where('slug', 'starter')->first();
            
            if (!$plan) {
                $this->error("❌ План 'starter' не найден! Выполните: php artisan db:seed --class=PlansSeeder");
                return 1;
            }

            $this->warn("⚠️  Активная подписка не найдена. Создаю тестовую подписку...");
            
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'started_at' => now()->subDays(45), // Началась 45 дней назад
                'expires_at' => now()->subDays(15), // Истекла 15 дней назад
            ]);

            // Обновляем данные пользователя
            $user->subscription_type = 'starter';
            $user->subscription_expires_at = $subscription->expires_at;
            $user->account_type = 'foreman'; // Устанавливаем статус прораба
            $user->save();

            $this->info("✅ Создана просроченная подписка #{$subscription->id}");
        } else {
            // Меняем дату истечения существующей подписки
            $this->info("📝 Найдена активная подписка #{$subscription->id}");
            
            $oldExpires = $subscription->expires_at ? $subscription->expires_at->format('d.m.Y H:i') : 'null';
            
            $subscription->expires_at = Carbon::parse('2025-01-01 00:00:00');
            $subscription->save();

            $user->subscription_expires_at = $subscription->expires_at;
            if ($user->account_type !== 'foreman') {
                $user->account_type = 'foreman'; // Устанавливаем прораба для теста
            }
            $user->save();

            $this->info("✅ Дата истечения изменена:");
            $this->line("   Было: {$oldExpires}");
            $this->line("   Стало: " . $subscription->expires_at->format('d.m.Y H:i') . " (просрочено)");
        }

        $this->newLine();
        $this->info("📊 Новый статус:");
        $user->refresh();
        $this->line("   subscription_type: " . ($user->subscription_type ?? 'null'));
        $this->line("   account_type: {$user->account_type}");
        $this->line("   subscription_expires_at: " . ($user->subscription_expires_at ? $user->subscription_expires_at->format('d.m.Y H:i') : 'null'));
        
        $this->newLine();
        $this->info("✅ Тестовая просроченная подписка готова!");
        $this->info("🔍 Теперь запустите: php artisan subscriptions:check-expired");
        
        return 0;
    }
}
