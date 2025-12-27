<?php

namespace App\Http\Controllers;

use App\Models\WorkTemplateType;
use App\Models\WorkTemplateStage;
use App\Models\WorkTemplateTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PriceTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isForeman()) {
                return redirect()->route('home')
                    ->with('error', 'Доступ к управлению прайсами имеют только прорабы.');
            }
            return $next($request);
        });
    }

    /**
     * Отображение страницы управления прайсами
     */
    public function index()
    {
        $templateTypes = WorkTemplateType::with(['stages.tasks'])
            ->forUser(Auth::id())
            ->active()
            ->orderBy('order')
            ->get();

        return view('prices.index', compact('templateTypes'));
    }

    /**
     * Сохранение/обновление типа работы
     */
    public function storeType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $type = WorkTemplateType::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'order' => WorkTemplateType::forUser(Auth::id())->max('order') + 1,
        ]);

        return response()->json([
            'success' => true,
            'type' => $type->load('stages.tasks'),
        ]);
    }

    /**
     * Обновление типа работы
     */
    public function updateType(Request $request, WorkTemplateType $type)
    {
        // Проверяем что тип принадлежит текущему пользователю
        if ($type->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Доступ запрещён'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $type->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'type' => $type->load('stages.tasks'),
        ]);
    }

    /**
     * Удаление типа работы
     */
    public function deleteType(WorkTemplateType $type)
    {
        // Проверяем что тип принадлежит текущему пользователю
        if ($type->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Доступ запрещён'], 403);
        }

        $type->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Сохранение этапа
     */
    public function storeStage(Request $request)
    {
        $request->validate([
            'work_template_type_id' => 'required|exists:work_template_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_days' => 'required|integer|min:1',
        ]);

        // Проверяем что тип принадлежит текущему пользователю
        $type = WorkTemplateType::findOrFail($request->work_template_type_id);
        if ($type->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Доступ запрещён'], 403);
        }

        $stage = WorkTemplateStage::create([
            'work_template_type_id' => $request->work_template_type_id,
            'name' => $request->name,
            'description' => $request->description,
            'duration_days' => $request->duration_days,
            'order' => WorkTemplateStage::where('work_template_type_id', $request->work_template_type_id)->max('order') + 1,
        ]);

        return response()->json([
            'success' => true,
            'stage' => $stage->load('tasks'),
        ]);
    }

    /**
     * Обновление этапа
     */
    public function updateStage(Request $request, WorkTemplateStage $stage)
    {
        // Проверяем что этап принадлежит текущему пользователю
        if ($stage->templateType->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Доступ запрещён'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_days' => 'required|integer|min:1',
        ]);

        $stage->update([
            'name' => $request->name,
            'description' => $request->description,
            'duration_days' => $request->duration_days,
        ]);

        return response()->json([
            'success' => true,
            'stage' => $stage->load('tasks'),
        ]);
    }

    /**
     * Удаление этапа
     */
    public function deleteStage(WorkTemplateStage $stage)
    {
        // Проверяем что этап принадлежит текущему пользователю
        if ($stage->templateType->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Доступ запрещён'], 403);
        }

        $stage->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Сохранение задачи
     */
    public function storeTask(Request $request)
    {
        $request->validate([
            'work_template_stage_id' => 'required|exists:work_template_stages,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        // Проверяем что этап принадлежит текущему пользователю
        $stage = WorkTemplateStage::findOrFail($request->work_template_stage_id);
        if ($stage->templateType->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Доступ запрещён'], 403);
        }

        $task = WorkTemplateTask::create([
            'work_template_stage_id' => $request->work_template_stage_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'order' => WorkTemplateTask::where('work_template_stage_id', $request->work_template_stage_id)->max('order') + 1,
        ]);

        return response()->json([
            'success' => true,
            'task' => $task,
        ]);
    }

    /**
     * Обновление задачи
     */
    public function updateTask(Request $request, WorkTemplateTask $task)
    {
        // Проверяем что задача принадлежит текущему пользователю
        if ($task->stage->templateType->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Доступ запрещён'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json([
            'success' => true,
            'task' => $task,
        ]);
    }

    /**
     * Удаление задачи
     */
    public function deleteTask(WorkTemplateTask $task)
    {
        // Проверяем что задача принадлежит текущему пользователю
        if ($task->stage->templateType->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Доступ запрещён'], 403);
        }

        $task->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Получение всех шаблонов пользователя
     */
    public function getTemplates()
    {
        $templates = WorkTemplateType::with(['stages.tasks'])
            ->forUser(Auth::id())
            ->active()
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'templates' => $templates,
        ]);
    }
}
