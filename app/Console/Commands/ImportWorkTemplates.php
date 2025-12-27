<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WorkTemplateType;
use App\Models\WorkTemplateStage;
use App\Models\WorkTemplateTask;
use Illuminate\Support\Facades\DB;

class ImportWorkTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'work:import-templates {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт шаблонов работ из конфига в базу данных';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if (!$userId) {
            // Если ID не указан, запрашиваем у пользователя
            $this->info('Доступные пользователи:');
            $users = \App\Models\User::all();
            
            foreach ($users as $user) {
                $this->line("  [{$user->id}] {$user->name} ({$user->email})");
            }
            
            $userId = $this->ask('Введите ID пользователя для импорта шаблонов');
        }
        
        // Проверяем существование пользователя
        $user = \App\Models\User::find($userId);
        if (!$user) {
            $this->error('Пользователь с ID ' . $userId . ' не найден!');
            return 1;
        }
        
        $this->info("Импорт шаблонов для пользователя: {$user->name}");
        
        // Спрашиваем, нужно ли очистить существующие шаблоны
        if ($this->confirm('Очистить существующие шаблоны этого пользователя?', false)) {
            WorkTemplateType::where('user_id', $userId)->delete();
            $this->info('Существующие шаблоны удалены.');
        }
        
        // Получаем данные из конфига
        $templates = config('work_templates');
        
        if (!$templates) {
            $this->error('Файл конфигурации work_templates.php пуст или не найден!');
            return 1;
        }
        
        DB::beginTransaction();
        
        try {
            $typeOrder = WorkTemplateType::where('user_id', $userId)->max('order') ?? -1;
            $totalTypes = 0;
            $totalStages = 0;
            $totalTasks = 0;
            
            foreach ($templates as $typeName => $typeData) {
                $this->line("📁 Импорт типа работы: {$typeName}");
                
                // Создаем тип работы
                $templateType = WorkTemplateType::create([
                    'user_id' => $userId,
                    'name' => $typeName,
                    'description' => $typeData['description'] ?? null,
                    'order' => ++$typeOrder,
                    'is_active' => true,
                ]);
                
                $totalTypes++;
                
                if (isset($typeData['stages'])) {
                    $stageOrder = 0;
                    
                    foreach ($typeData['stages'] as $stageData) {
                        $this->line("  ⮑ Этап: {$stageData['name']}");
                        
                        // Создаем этап
                        $templateStage = WorkTemplateStage::create([
                            'work_template_type_id' => $templateType->id,
                            'name' => $stageData['name'],
                            'description' => $stageData['description'] ?? null,
                            'duration_days' => $stageData['duration_days'] ?? 1,
                            'order' => $stageOrder++,
                        ]);
                        
                        $totalStages++;
                        
                        if (isset($stageData['tasks'])) {
                            $taskOrder = 0;
                            $taskCount = 0;
                            
                            foreach ($stageData['tasks'] as $taskData) {
                                // Определяем название задачи
                                $taskName = is_string($taskData) ? $taskData : ($taskData['name'] ?? 'Задача');
                                
                                // Создаем задачу
                                WorkTemplateTask::create([
                                    'work_template_stage_id' => $templateStage->id,
                                    'name' => $taskName,
                                    'description' => is_array($taskData) && isset($taskData['description']) ? $taskData['description'] : null,
                                    'duration_days' => is_array($taskData) && isset($taskData['duration_days']) ? $taskData['duration_days'] : 1,
                                    'price' => is_array($taskData) && isset($taskData['price']) ? $taskData['price'] : 0,
                                    'order' => $taskOrder++,
                                ]);
                                
                                $taskCount++;
                                $totalTasks++;
                            }
                            
                            $this->line("    ✓ Создано задач: {$taskCount}");
                        }
                    }
                }
            }
            
            DB::commit();
            
            $this->newLine();
            $this->info('✅ Импорт завершен успешно!');
            $this->table(
                ['Показатель', 'Количество'],
                [
                    ['Типов работ', $totalTypes],
                    ['Этапов', $totalStages],
                    ['Задач', $totalTasks],
                ]
            );
            
            $this->info('Шаблоны доступны на странице: ' . route('prices.index'));
            
            return 0;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Ошибка при импорте: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
