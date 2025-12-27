<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkTemplateType;
use App\Models\WorkTemplateStage;
use App\Models\WorkTemplateTask;
use Symfony\Component\HttpFoundation\Response;

class EnsureTemplatesExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем только для авторизованных прорабов
        if (Auth::check() && Auth::user()->isForeman()) {
            $this->ensureTemplatesExist();
        }
        
        return $next($request);
    }

    /**
     * Проверка и создание шаблонов если их нет
     */
    private function ensureTemplatesExist()
    {
        $count = WorkTemplateType::forUser(Auth::id())->count();
        
        if ($count < 1) {
            // Импортируем шаблоны из конфига
            $templates = config('work_templates');
            
            if (!$templates || !is_array($templates)) return;
            
            foreach ($templates as $typeName => $templateData) {
                // Пропускаем если нет stages
                if (!isset($templateData['stages']) || !is_array($templateData['stages'])) {
                    continue;
                }
                
                $type = WorkTemplateType::create([
                    'user_id' => Auth::id(),
                    'name' => $typeName,
                    'description' => $templateData['description'] ?? null,
                    'order' => WorkTemplateType::forUser(Auth::id())->max('order') + 1,
                ]);

                foreach ($templateData['stages'] as $stageData) {
                    if (!isset($stageData['name'])) continue;
                    
                    $stage = WorkTemplateStage::create([
                        'work_template_type_id' => $type->id,
                        'name' => $stageData['name'],
                        'description' => $stageData['description'] ?? null,
                        'duration_days' => $stageData['duration_days'] ?? 1,
                        'order' => WorkTemplateStage::where('work_template_type_id', $type->id)->max('order') + 1,
                    ]);

                    if (isset($stageData['tasks']) && is_array($stageData['tasks'])) {
                        foreach ($stageData['tasks'] as $taskData) {
                            // Если задача - это строка, создаем базовую задачу
                            if (is_string($taskData)) {
                                WorkTemplateTask::create([
                                    'work_template_stage_id' => $stage->id,
                                    'name' => $taskData,
                                    'description' => null,
                                    'duration_days' => 1,
                                    'price' => 0,
                                    'order' => WorkTemplateTask::where('work_template_stage_id', $stage->id)->max('order') + 1,
                                ]);
                            } 
                            // Если задача - это массив с данными
                            elseif (is_array($taskData) && isset($taskData['name'])) {
                                WorkTemplateTask::create([
                                    'work_template_stage_id' => $stage->id,
                                    'name' => $taskData['name'],
                                    'description' => $taskData['description'] ?? null,
                                    'duration_days' => $taskData['duration_days'] ?? 1,
                                    'price' => $taskData['price'] ?? 0,
                                    'order' => WorkTemplateTask::where('work_template_stage_id', $stage->id)->max('order') + 1,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
