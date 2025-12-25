<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateExistingUsersSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Устанавливаем бесплатный тариф всем существующим пользователям
        \App\Models\User::whereNull('subscription_type')
            ->orWhere('subscription_type', '')
            ->update(['subscription_type' => 'free']);

        $this->command->info('Все существующие пользователи обновлены на бесплатный тариф.');
    }
}
