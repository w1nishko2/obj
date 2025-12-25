<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Models\ProjectParticipant;
use App\Models\ProjectUserRole;
use Illuminate\Support\Facades\Log;

class LinkUserToProjects
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;
        
        // Ищем все записи участников с этим телефоном, но без user_id
        $participants = ProjectParticipant::whereNull('user_id')
            ->where('phone', $user->phone)
            ->get();
        
        if ($participants->isEmpty()) {
            return;
        }
        
        Log::info('Linking new user to existing projects', [
            'user_id' => $user->id,
            'phone' => $user->phone,
            'projects_count' => $participants->count(),
        ]);
        
        foreach ($participants as $participant) {
            // Обновляем user_id у участника
            $participant->user_id = $user->id;
            $participant->save();
            
            // Проверяем, нет ли уже роли в проекте
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
                
                Log::info('Created project role for new user', [
                    'user_id' => $user->id,
                    'project_id' => $participant->project_id,
                    'role' => 'client',
                ]);
            }
        }
        
        Log::info('Successfully linked user to projects', [
            'user_id' => $user->id,
            'projects_linked' => $participants->count(),
        ]);
    }
}
