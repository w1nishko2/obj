<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MigrateToProjectUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Начинаем миграцию ролей в project_user_roles...');
        
        // Очищаем таблицу перед миграцией
        \App\Models\ProjectUserRole::truncate();
        
        $ownersCount = 0;
        $participantsCount = 0;
        
        // 1. Мигрируем владельцев проектов (owner)
        $projects = \App\Models\Project::all();
        
        foreach ($projects as $project) {
            // Создаём роль владельца для прораба проекта
            \App\Models\ProjectUserRole::create([
                'project_id' => $project->id,
                'user_id' => $project->user_id,
                'role' => 'owner',
                ...(\App\Models\ProjectUserRole::getDefaultPermissions('owner'))
            ]);
            $ownersCount++;
        }
        
        $this->command->info("✅ Создано {$ownersCount} владельцев проектов (owner)");
        
        // 2. Мигрируем участников из project_participants
        $participants = \App\Models\ProjectParticipant::whereNotNull('user_id')->get();
        
        foreach ($participants as $participant) {
            // Определяем роль в новой системе
            $newRole = match($participant->role) {
                'Клиент' => 'client',
                'Исполнитель' => 'executor',
                default => 'executor' // На случай других значений
            };
            
            // Проверяем, не является ли этот пользователь уже владельцем проекта
            $existingOwner = \App\Models\ProjectUserRole::where('project_id', $participant->project_id)
                ->where('user_id', $participant->user_id)
                ->where('role', 'owner')
                ->exists();
            
            if ($existingOwner) {
                $this->command->warn("⚠️ Пользователь {$participant->user_id} уже владелец проекта {$participant->project_id}, пропускаем...");
                continue;
            }
            
            // Проверяем, нет ли уже этого участника
            $existingRole = \App\Models\ProjectUserRole::where('project_id', $participant->project_id)
                ->where('user_id', $participant->user_id)
                ->first();
            
            if ($existingRole) {
                $this->command->warn("⚠️ Роль для пользователя {$participant->user_id} в проекте {$participant->project_id} уже существует");
                continue;
            }
            
            // Создаём роль участника
            \App\Models\ProjectUserRole::create([
                'project_id' => $participant->project_id,
                'user_id' => $participant->user_id,
                'role' => $newRole,
                ...(\App\Models\ProjectUserRole::getDefaultPermissions($newRole))
            ]);
            $participantsCount++;
        }
        
        $this->command->info("✅ Создано {$participantsCount} ролей участников");
        
        // Статистика
        $totalRoles = \App\Models\ProjectUserRole::count();
        $ownerRoles = \App\Models\ProjectUserRole::where('role', 'owner')->count();
        $clientRoles = \App\Models\ProjectUserRole::where('role', 'client')->count();
        $executorRoles = \App\Models\ProjectUserRole::where('role', 'executor')->count();
        
        $this->command->info('');
        $this->command->info('📊 Итоговая статистика:');
        $this->command->table(
            ['Роль', 'Количество'],
            [
                ['owner (владелец)', $ownerRoles],
                ['client (клиент)', $clientRoles],
                ['executor (исполнитель)', $executorRoles],
                ['ВСЕГО', $totalRoles],
            ]
        );
        
        $this->command->info('');
        $this->command->info('✨ Миграция завершена успешно!');
    }
}
