<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectUserRole;

class FixExecutorPermissionsSeeder extends Seeder
{
    /**
     * Исправить права всех исполнителей - убрать возможность создания задач
     */
    public function run(): void
    {
        $executors = ProjectUserRole::where('role', 'executor')->get();
        
        $updated = 0;
        foreach ($executors as $executor) {
            // Убираем права на создание этапов/задач
            if ($executor->can_create_stages) {
                $executor->can_create_stages = false;
                $executor->save();
                $updated++;
            }
        }

        $this->command->info("Обновлено прав исполнителей: {$updated}");
        $this->command->info("Всего исполнителей в системе: {$executors->count()}");
    }
}
