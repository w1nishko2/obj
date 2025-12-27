<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUserStatus extends Command
{
    protected $signature = 'user:status {user_id=1}';
    protected $description = 'Показать статус пользователя';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("❌ Пользователь #{$userId} не найден!");
            return 1;
        }

        $this->info("📊 Статус пользователя: {$user->name} (ID: {$user->id})");
        $this->newLine();
        $this->line("   subscription_type: " . ($user->subscription_type ?? 'null'));
        $this->line("   account_type: {$user->account_type}");
        $this->line("   subscription_expires_at: " . ($user->subscription_expires_at ? $user->subscription_expires_at->format('d.m.Y H:i') : 'null'));
        $this->newLine();
        
        // Проверка статусов
        $this->info("🔍 Проверки:");
        $this->line("   hasAnyPlan(): " . ($user->hasAnyPlan() ? '✅ true' : '❌ false'));
        $this->line("   isSubscriptionExpired(): " . ($user->isSubscriptionExpired() ? '⚠️ true (истекла)' : '✅ false'));
        $this->line("   hasActiveSubscription(): " . ($user->hasActiveSubscription() ? '✅ true' : '❌ false'));
        $this->line("   isForeman(): " . ($user->isForeman() ? '✅ true' : '❌ false'));
        $this->line("   canCreateProjects(): " . ($user->canCreateProjects() ? '✅ true' : '❌ false'));
        
        return 0;
    }
}
