<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemoveAutoSubscriptionSeeder extends Seeder
{
    /**
     * Убрать автоматическую подписку у пользователей
     */
    public function run(): void
    {
        // Обнуляем подписки у всех пользователей, у которых нет записи в subscriptions
        DB::statement("
            UPDATE users u
            LEFT JOIN subscriptions s ON u.id = s.user_id AND s.status = 'active'
            SET u.subscription_type = NULL,
                u.subscription_expires_at = NULL
            WHERE s.id IS NULL
        ");

        echo "Автоматические подписки удалены. Теперь пользователи должны купить тариф за 1₽\n";
    }
}
