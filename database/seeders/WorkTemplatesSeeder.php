<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkTemplateType;
use App\Models\WorkTemplateStage;
use App\Models\WorkTemplateTask;
use Illuminate\Support\Facades\DB;

class WorkTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем данные из конфига
        $templates = config('work_templates');
        
        // ID пользователя для шаблонов (можно указать конкретного или сделать для всех)
        // По умолчанию создаем для первого пользователя-прораба
        $userId = \App\Models\User::where('subscription_plan', '!=', 'free')
            ->orWhere('subscription_plan', null)
            ->first()?->id ?? 1;

        DB::beginTransaction();
        
        try {
            $typeOrder = 0;
            
            foreach ($templates as $typeName => $typeData) {
                echo "Импорт типа работы: {$typeName}\n";
                
                // Создаем тип работы
                $templateType = WorkTemplateType::create([
                    'user_id' => $userId,
                    'name' => $typeName,
                    'description' => null,
                    'order' => $typeOrder++,
                    'is_active' => true,
                ]);
                
                if (isset($typeData['stages'])) {
                    $stageOrder = 0;
                    
                    foreach ($typeData['stages'] as $stageData) {
                        echo "  - Импорт этапа: {$stageData['name']}\n";
                        
                        // Создаем этап
                        $templateStage = WorkTemplateStage::create([
                            'work_template_type_id' => $templateType->id,
                            'name' => $stageData['name'],
                            'description' => null,
                            'duration_days' => $stageData['duration_days'] ?? 1,
                            'order' => $stageOrder++,
                        ]);
                        
                        if (isset($stageData['tasks'])) {
                            $taskOrder = 0;
                            
                            foreach ($stageData['tasks'] as $taskName) {
                                // Создаем задачу
                                WorkTemplateTask::create([
                                    'work_template_stage_id' => $templateStage->id,
                                    'name' => is_string($taskName) ? $taskName : ($taskName['name'] ?? 'Задача'),
                                    'description' => null,
                                    'duration_days' => is_array($taskName) && isset($taskName['duration_days']) ? $taskName['duration_days'] : 1,
                                    'price' => is_array($taskName) && isset($taskName['price']) ? $taskName['price'] : 0,
                                    'order' => $taskOrder++,
                                ]);
                            }
                            
                            echo "    * Создано задач: " . count($stageData['tasks']) . "\n";
                        }
                    }
                }
            }
            
            DB::commit();
            
            echo "\n✅ Успешно импортировано:\n";
            echo "   - Типов работ: " . WorkTemplateType::count() . "\n";
            echo "   - Этапов: " . WorkTemplateStage::count() . "\n";
            echo "   - Задач: " . WorkTemplateTask::count() . "\n";
            
        } catch (\Exception $e) {
            DB::rollBack();
            echo "\n❌ Ошибка при импорте: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
