<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class FixUserAccountType extends Command
{
    protected $signature = 'user:fix-account-type {user_id=1}';
    protected $description = 'Исправить account_type для пользователя с активной подпиской';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("❌ Пользователь #{$userId} не найден!");
            return 1;
        }

        $this->info("👤 Пользователь: {$user->name} (ID: {$user->id})");
        $this->line("   Текущий account_type: {$user->account_type}");
        $this->line("   subscription_type: " . ($user->subscription_type ?? 'null'));
        $this->newLine();

        // Если есть активная подписка, но account_type != foreman
        if ($user->hasActiveSubscription() && $user->account_type !== 'foreman') {
            $user->account_type = 'foreman';
            $user->save();
            
            $this->info("✅ account_type изменен на 'foreman'");
            $this->newLine();
            
            // Показываем новый статус
            $user->refresh();
            $this->info("📊 Новый статус:");
            $this->line("   account_type: {$user->account_type}");
            $this->line("   isForeman(): " . ($user->isForeman() ? '✅ true' : '❌ false'));
            $this->line("   canCreateProjects(): " . ($user->canCreateProjects() ? '✅ true' : '❌ false'));
            
            return 0;
        }

        if (!$user->hasActiveSubscription()) {
            $this->warn("⚠️  У пользователя нет активной подписки!");
            return 1;
        }

        $this->info("✅ account_type уже корректен (foreman)");
        return 0;
    }
}
