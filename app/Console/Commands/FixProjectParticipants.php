<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProjectParticipant;
use App\Models\ProjectUserRole;
use App\Models\User;

class FixProjectParticipants extends Command
{
    protected $signature = 'project:fix-participants';
    protected $description = 'Fix participants without user_id and create missing roles';

    public function handle()
    {
        $this->info('Fixing project participants...');
        
        // Находим всех участников без user_id, но с телефоном
        $participants = ProjectParticipant::whereNull('user_id')
            ->whereNotNull('phone')
            ->get();
        
        $fixed = 0;
        $rolesCreated = 0;
        
        foreach ($participants as $participant) {
            // Ищем пользователя по телефону
            $user = User::where('phone', $participant->phone)->first();
            
            if ($user) {
                $this->line("Found user for phone {$participant->phone}: {$user->name} (ID: {$user->id})");
                
                // Обновляем user_id у участника
                $participant->user_id = $user->id;
                $participant->save();
                $fixed++;
                
                // Проверяем, есть ли уже роль в проекте
                $existingRole = ProjectUserRole::where('project_id', $participant->project_id)
                    ->where('user_id', $user->id)
                    ->first();
                
                if (!$existingRole) {
                    // Создаем роль клиента по умолчанию
                    $permissions = ProjectUserRole::getDefaultPermissions('client');
                    ProjectUserRole::create([
                        'project_id' => $participant->project_id,
                        'user_id' => $user->id,
                        'role' => 'client',
                        ...$permissions,
                    ]);
                    
                    $this->info("  → Created 'client' role for user {$user->name} in project {$participant->project_id}");
                    $rolesCreated++;
                } else {
                    $this->warn("  → User already has role '{$existingRole->role}' in project");
                }
            } else {
                $this->warn("User not found for phone: {$participant->phone}");
            }
        }
        
        $this->info("\n✓ Fixed {$fixed} participants");
        $this->info("✓ Created {$rolesCreated} roles");
        
        return 0;
    }
}
