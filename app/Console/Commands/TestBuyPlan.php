<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Plan;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Str;

class TestBuyPlan extends Command
{
    protected $signature = 'test:buy-plan {user_id=1} {plan_slug=starter}';
    protected $description = 'Эмулирует покупку тарифа для локального тестирования';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $planSlug = $this->argument('plan_slug');
        
        $user = User::find($userId);
        if (!$user) {
            $this->error("❌ Пользователь #{$userId} не найден!");
            return 1;
        }

        $plan = Plan::where('slug', $planSlug)->where('is_active', true)->first();
        if (!$plan) {
            $this->error("❌ Тариф '{$planSlug}' не найден!");
            $this->line("\nДоступные тарифы:");
            Plan::where('is_active', true)->get()->each(function($p) {
                $this->line("  - {$p->slug} ({$p->name}) - {$p->price}₽");
            });
            return 1;
        }

        $this->info("🛒 Эмуляция покупки тарифа");
        $this->line("   Пользователь: {$user->name} (ID: {$user->id})");
        $this->line("   Тариф: {$plan->name} ({$plan->slug})");
        $this->line("   Цена: {$plan->price}₽");
        $this->newLine();

        try {
            // 1. Создаем запись платежа
            $payment = Payment::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'yookassa_payment_id' => 'test_' . Str::uuid(),
                'status' => 'pending',
                'amount' => $plan->price,
                'currency' => 'RUB',
                'description' => "TEST: Оплата тарифа \"{$plan->name}\"",
            ]);
            
            $this->line("✓ Payment создан (ID: {$payment->id})");

            // 2. Помечаем платеж как успешный
            $payment->markAsSucceeded();
            $this->line("✓ Payment.status = 'succeeded'");

            // 3. Создаем подписку
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'started_at' => now(),
                'expires_at' => null, // Будет установлено в activate()
            ]);
            
            $this->line("✓ Subscription создан (ID: {$subscription->id})");

            // 4. Связываем платеж с подпиской
            $payment->update(['subscription_id' => $subscription->id]);

            // 5. Активируем подписку
            $subscription->activate($payment->paid_at ?? now());
            $this->line("✓ Subscription активирован");
            $this->line("   expires_at: " . ($subscription->expires_at ? $subscription->expires_at->format('d.m.Y H:i') : 'бессрочно'));

            // 6. Выдаем права прораба
            if (!$user->isForeman()) {
                $user->upgradeToForeman();
                $this->line("✓ Права прораба выданы (account_type = foreman)");
            } else {
                $this->line("✓ Пользователь уже прораб");
            }

            $this->newLine();
            $this->info("✅ Тестовая покупка завершена!");
            $this->newLine();

            // Показываем финальный статус
            $user->refresh();
            $this->info("📊 Финальный статус пользователя:");
            $this->line("   subscription_type: {$user->subscription_type}");
            $this->line("   account_type: {$user->account_type}");
            $this->line("   subscription_expires_at: " . ($user->subscription_expires_at ? $user->subscription_expires_at->format('d.m.Y H:i') : 'null'));
            $this->newLine();
            
            $this->info("🔍 Проверки:");
            $this->line("   hasActiveSubscription(): " . ($user->hasActiveSubscription() ? '✅ true' : '❌ false'));
            $this->line("   isForeman(): " . ($user->isForeman() ? '✅ true' : '❌ false'));
            $this->line("   canCreateProjects(): " . ($user->canCreateProjects() ? '✅ true' : '❌ false'));
            $this->line("   canGenerateEstimates(): " . ($user->canGenerateEstimates() ? '✅ true' : '❌ false'));
            $this->line("   canGenerateDocuments(): " . ($user->canGenerateDocuments() ? '✅ true' : '❌ false'));
            
            $maxProjects = $plan->features['max_projects'] ?? 0;
            $remaining = $user->getRemainingProjectsCount();
            $this->line("   Лимит проектов: " . ($maxProjects === null ? 'безлимит' : "{$maxProjects} (осталось: " . ($remaining === null ? '∞' : $remaining) . ")"));

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Ошибка: " . $e->getMessage());
            $this->line($e->getTraceAsString());
            return 1;
        }
    }
}
