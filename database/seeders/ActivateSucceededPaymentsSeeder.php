<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;

class ActivateSucceededPaymentsSeeder extends Seeder
{
    /**
     * Активирует подписки для всех успешных платежей без subscription_id
     */
    public function run(): void
    {
        $payments = Payment::where('status', 'succeeded')
            ->whereNull('subscription_id')
            ->with(['user', 'plan'])
            ->get();

        echo "Найдено платежей для активации: {$payments->count()}\n\n";

        foreach ($payments as $payment) {
            try {
                // Создаем подписку
                $subscription = Subscription::create([
                    'user_id' => $payment->user_id,
                    'plan_id' => $payment->plan_id,
                    'status' => 'active',
                    'started_at' => now(),
                    'expires_at' => null, // Будет установлено в методе activate()
                ]);

                // Связываем платеж с подпиской
                $payment->update(['subscription_id' => $subscription->id]);

                // Активируем подписку (обновит данные пользователя)
                $subscription->activate();

                // Обновляем тип аккаунта пользователя на "прораб"
                $user = $payment->user;
                if (!$user->isForeman()) {
                    $user->upgradeToForeman();
                }

                echo "✓ Активирована подписка для пользователя #{$user->id} ({$user->name})\n";
                echo "  Тариф: {$payment->plan->name}\n";
                echo "  Сумма: {$payment->amount} ₽\n\n";

            } catch (\Exception $e) {
                echo "✗ Ошибка для платежа #{$payment->id}: {$e->getMessage()}\n\n";
                Log::error('Error activating subscription for payment', [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        echo "Готово!\n";
    }
}
