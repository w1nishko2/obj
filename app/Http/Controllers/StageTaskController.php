<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectStage;
use App\Models\StageTask;
use App\Models\TaskComment;
use App\Models\TaskPhoto;
use App\Models\StageMaterial;
use App\Models\StageDelivery;
use App\Services\ProjectNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StageTaskController extends Controller
{
    protected ProjectNotificationService $notificationService;

    public function __construct(ProjectNotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    // Показать детали этапа со всеми задачами
    public function showStage(Project $project, ProjectStage $stage)
    {
        $this->authorize('view', $project);

        // Загружаем только первые 20 задач для начального отображения
        $stage->load([
            'tasks' => function($query) {
                $query->withCount(['photos', 'comments'])
                      ->orderBy('order')
                      ->limit(20);
            },
            'tasks.creator', 
            'tasks.assignedUser', 
            'tasks.comments.user', 
            'tasks.photos.user',
            'materials.user',
            'deliveries.user'
        ]);
        
        // Получаем общее количество задач для условного отображения поиска
        $totalTasks = \App\Models\StageTask::where('stage_id', $stage->id)->count();
        
        // Кешируем роль текущего пользователя для предотвращения N+1 запросов
        $currentUserRole = Auth::user()->getRoleInProject($project);

        return view('stages.show', compact('project', 'stage', 'currentUserRole', 'totalTasks'));
    }

    // Создать задачу (только Прораб)
    public function storeTask(Request $request, Project $project, ProjectStage $stage)
    {
        $this->authorize('createStages', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'cost' => 'nullable|numeric|min:0',
            'markup_percent' => 'nullable|numeric|min:0|max:999.99',
        ]);

        $task = $stage->tasks()->create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'assigned_to' => $validated['assigned_to'] ?? null,
            'created_by' => Auth::id(),
            'cost' => $validated['cost'] ?? 0,
            'markup_percent' => $validated['markup_percent'] ?? null,
            'order' => $stage->tasks()->count(),
        ]);

        // Отправляем push-уведомление участникам проекта
        try {
            $this->notificationService->notifyTaskCreated($project, $stage, $task, Auth::user());
        } catch (\Exception $e) {
            Log::error('Failed to send push notification for task created: ' . $e->getMessage());
        }

        return back()->with('success', 'Задача создана!')
            ->with('tab', 'tasksTab')
            ->with('task_id', $task->id);
    }

    // Обновить настройки задачи (только владелец проекта)
    public function updateTask(Request $request, Project $project, ProjectStage $stage, StageTask $task)
    {
        // Только владелец проекта может изменять настройки задачи
        $userRole = Auth::user()->getRoleInProject($project);
        if (!$userRole || $userRole->role !== 'owner') {
            abort(403, 'Только владелец проекта может изменять настройки задачи');
        }

        $validated = $request->validate([
            'status' => 'required|in:Не начата,В работе,На проверке,Завершена',
            'cost' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|exists:users,id',
            'markup_percent' => 'nullable|numeric|min:0|max:999.99',
        ]);

        // Сохраняем старый статус для проверки изменения
        $oldStatus = $task->status;

        $task->update([
            'status' => $validated['status'],
            'cost' => $validated['cost'] ?? 0,
            'assigned_to' => $validated['assigned_to'] ?? null,
            'markup_percent' => $validated['markup_percent'] ?? null,
        ]);

        // Отправляем push-уведомление если статус изменился
        if ($oldStatus !== $validated['status']) {
            try {
                $this->notificationService->notifyTaskStatusChanged($project, $stage, $task, $validated['status'], Auth::user());
            } catch (\Exception $e) {
                Log::error('Failed to send push notification for task status changed: ' . $e->getMessage());
            }
        }

        // Если AJAX запрос - возвращаем JSON
        if ($request->wantsJson() || $request->ajax()) {
            $task->load('assignedUser');
            
            $statusClassMap = [
                'Не начата' => 'bg-secondary',
                'В работе' => 'bg-primary',
                'На проверке' => 'bg-warning',
                'Завершена' => 'bg-success',
            ];

            return response()->json([
                'success' => true,
                'message' => 'Настройки задачи обновлены!',
                'task' => [
                    'id' => $task->id,
                    'status' => $task->status,
                    'status_class' => $statusClassMap[$task->status] ?? 'bg-secondary',
                    'cost' => $task->cost,
                    'final_cost' => $task->final_cost,
                    'final_cost_formatted' => number_format($task->final_cost, 2, ',', ' '),
                    'assigned_user' => $task->assignedUser ? [
                        'id' => $task->assignedUser->id,
                        'name' => $task->assignedUser->name,
                        'phone' => $task->assignedUser->phone,
                    ] : null,
                ]
            ]);
        }

        return back()->with('success', 'Настройки задачи обновлены!')
            ->with('tab', 'tasksTab')
            ->with('task_id', $task->id)
            ->with('scroll_to', 'task-' . $task->id);
    }

    // Обновить статус задачи (Владелец проекта или назначенный исполнитель)
    public function updateTaskStatus(Request $request, Project $project, ProjectStage $stage, StageTask $task)
    {
        $this->authorize('view', $project);

        // Проверяем: либо владелец проекта, либо назначенный исполнитель
        $userRole = Auth::user()->getRoleInProject($project);
        $isOwner = $userRole && $userRole->role === 'owner';
        $isAssignedExecutor = $task->assigned_to === Auth::id();

        if (!$isOwner && !$isAssignedExecutor) {
            abort(403, 'Только владелец проекта или назначенный исполнитель может менять статус задачи');
        }

        $validated = $request->validate([
            'status' => 'required|in:Не начата,В работе,На проверке,Завершена',
        ]);

        $task->update(['status' => $validated['status']]);

        // Отправляем push-уведомление участникам проекта
        try {
            $this->notificationService->notifyTaskStatusChanged($project, $stage, $task, $validated['status'], Auth::user());
        } catch (\Exception $e) {
            Log::error('Failed to send push notification for task status changed: ' . $e->getMessage());
        }

        return back()->with('success', 'Статус задачи обновлен!')
            ->with('tab', 'tasksTab')
            ->with('task_id', $task->id)
            ->with('scroll_to', 'task-' . $task->id);
    }

    // Добавить комментарий к задаче (Прораб и Сотрудник)
    public function addComment(Request $request, Project $project, ProjectStage $stage, StageTask $task)
    {
        $this->authorize('editTasks', $project);

        $validated = $request->validate([
            'comment' => 'required|string',
        ]);

        $comment = $task->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        // Отправляем push-уведомление участникам проекта
        try {
            $this->notificationService->notifyCommentAdded($project, $stage, $task, $comment, Auth::user());
        } catch (\Exception $e) {
            Log::error('Failed to send push notification for comment added: ' . $e->getMessage());
        }

        // Если AJAX запрос - возвращаем JSON
        if ($request->wantsJson() || $request->ajax()) {
            $userRole = Auth::user()->getRoleInProject($project);
            $isOwner = $userRole && $userRole->role === 'owner';
            $canDelete = $comment->user_id === Auth::id() || $isOwner;

            return response()->json([
                'success' => true,
                'message' => 'Комментарий добавлен!',
                'comment' => [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'user_name' => $comment->user->name,
                    'created_at' => $comment->created_at->format('d.m.Y H:i'),
                    'can_delete' => $canDelete,
                    'delete_url' => $canDelete ? route('stages.tasks.comments.destroy', [$project, $stage, $task, $comment]) : null,
                ]
            ]);
        }

        return back()->with('success', 'Комментарий добавлен!')
            ->with('tab', 'tasksTab')
            ->with('task_id', $task->id)
            ->with('scroll_to', 'task-' . $task->id);
    }

    // Добавить фотоотчет к задаче (Прораб и Сотрудник)
    public function addPhoto(Request $request, Project $project, ProjectStage $stage, StageTask $task)
    {
        $this->authorize('uploadDocuments', $project);

        $validated = $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'file|mimes:jpeg,jpg,png,gif,bmp,webp,heic,heif|max:102400', // максимум 100MB
            'description' => 'nullable|string',
        ]);

        $uploadedCount = 0;
        $lastPhoto = null;
        
        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('task-photos', 'public');

            $taskPhoto = $task->photos()->create([
                'user_id' => Auth::id(),
                'photo_path' => $path,
                'description' => $validated['description'] ?? null,
            ]);

            // Отправляем в очередь на обработку
            \App\Jobs\ProcessImageJob::dispatch($path, 'public');
            
            $lastPhoto = $taskPhoto;
            $uploadedCount++;
        }

        // Отправляем push-уведомление участникам проекта о первом фото
        if ($lastPhoto) {
            try {
                $this->notificationService->notifyPhotoAdded($project, $stage, $task, $lastPhoto, Auth::user());
            } catch (\Exception $e) {
                Log::error('Failed to send push notification for photo added: ' . $e->getMessage());
            }
        }

        return back()->with('success', "Загружено фотографий: {$uploadedCount}. Обработка началась...")
            ->with('tab', 'tasksTab')
            ->with('task_id', $task->id)
            ->with('scroll_to', 'task-' . $task->id);
    }

    // Удалить задачу (только Прораб)
    public function destroyTask(Project $project, ProjectStage $stage, StageTask $task)
    {
        // Только владелец проекта может удалять задачи
        $userRole = Auth::user()->getRoleInProject($project);
        if (!$userRole || $userRole->role !== 'owner') {
            abort(403, 'Только владелец проекта может удалять задачи');
        }

        // Удаляем все фото задачи
        foreach ($task->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
            $photo->delete();
        }

        // Удаляем все комментарии задачи
        $task->comments()->delete();

        // Удаляем саму задачу
        $task->delete();

        return back()->with('success', 'Задача удалена вместе со всеми фотоотчетами и комментариями!')
            ->with('tab', 'tasksTab');
    }

    // Удалить комментарий (автор или владелец проекта)
    public function destroyComment(Project $project, ProjectStage $stage, StageTask $task, TaskComment $comment)
    {
        $this->authorize('view', $project);

        $userRole = Auth::user()->getRoleInProject($project);
        $isOwner = $userRole && $userRole->role === 'owner';
        
        if ($comment->user_id !== Auth::id() && !$isOwner) {
            abort(403, 'Вы можете удалить только свой комментарий');
        }

        $comment->delete();

        return back()->with('success', 'Комментарий удален!')
            ->with('tab', 'tasksTab')
            ->with('task_id', $task->id)
            ->with('scroll_to', 'task-' . $task->id);
    }

    // Удалить фото (автор или владелец проекта)
    public function destroyPhoto(Project $project, ProjectStage $stage, StageTask $task, TaskPhoto $photo)
    {
        $this->authorize('view', $project);

        $userRole = Auth::user()->getRoleInProject($project);
        $isOwner = $userRole && $userRole->role === 'owner';
        
        if ($photo->user_id !== Auth::id() && !$isOwner) {
            abort(403, 'Вы можете удалить только своё фото');
        }

        Storage::disk('public')->delete($photo->photo_path);
        $photo->delete();

        return back()->with('success', 'Фотоотчет удален!')
            ->with('tab', 'tasksTab')
            ->with('task_id', $task->id)
            ->with('scroll_to', 'task-' . $task->id);
    }

    // Добавить материал (только Прораб)
    public function storeMaterial(Request $request, Project $project, ProjectStage $stage)
    {
        $this->authorize('createStages', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0.01',
            'price_per_unit' => 'required|numeric|min:0',
            'markup_percent' => 'nullable|numeric|min:0|max:999.99',
        ]);

        $material = $stage->materials()->create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'unit' => $validated['unit'],
            'quantity' => $validated['quantity'],
            'price_per_unit' => $validated['price_per_unit'],
            'markup_percent' => $validated['markup_percent'] ?? null,
        ]);

        // Отправляем push-уведомление участникам проекта
        try {
            $this->notificationService->notifyMaterialAdded($project, $stage, $material, Auth::user());
        } catch (\Exception $e) {
            Log::error('Failed to send push notification for material added: ' . $e->getMessage());
        }

        return back()->with('success', 'Материал добавлен!')
            ->with('tab', 'materialsTab');
    }

    // Удалить материал (только владелец проекта)
    public function destroyMaterial(Project $project, ProjectStage $stage, StageMaterial $material)
    {
        $userRole = Auth::user()->getRoleInProject($project);
        if (!$userRole || $userRole->role !== 'owner') {
            abort(403, 'Только владелец проекта может удалять материалы');
        }

        $material->delete();

        return back()->with('success', 'Материал удален!')
            ->with('tab', 'materialsTab');
    }

    // Добавить доставку (только Прораб)
    public function storeDelivery(Request $request, Project $project, ProjectStage $stage)
    {
        $this->authorize('createStages', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0.01',
            'price_per_unit' => 'required|numeric|min:0',
            'markup_percent' => 'nullable|numeric|min:0|max:999.99',
        ]);

        $delivery = $stage->deliveries()->create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'unit' => $validated['unit'],
            'quantity' => $validated['quantity'],
            'price_per_unit' => $validated['price_per_unit'],
            'markup_percent' => $validated['markup_percent'] ?? null,
        ]);

        // Отправляем push-уведомление участникам проекта
        try {
            $this->notificationService->notifyDeliveryAdded($project, $stage, $delivery, Auth::user());
        } catch (\Exception $e) {
            Log::error('Failed to send push notification for delivery added: ' . $e->getMessage());
        }

        return back()->with('success', 'Доставка добавлена!')
            ->with('tab', 'deliveriesTab');
    }

    // Удалить доставку (только владелец проекта)
    public function destroyDelivery(Project $project, ProjectStage $stage, StageDelivery $delivery)
    {
        $userRole = Auth::user()->getRoleInProject($project);
        if (!$userRole || $userRole->role !== 'owner') {
            abort(403, 'Только владелец проекта может удалять доставки');
        }

        $delivery->delete();

        return back()->with('success', 'Доставка удалена!')
            ->with('tab', 'deliveriesTab');
    }

    /**
     * Поиск и пагинация задач этапа (Ajax)
     */
    public function searchTasks(Request $request, Project $project, ProjectStage $stage)
    {
        $this->authorize('view', $project);

        $query = StageTask::where('stage_id', $stage->id)
            ->withCount(['photos', 'comments'])
            ->with(['creator', 'assignedUser']);

        // Нечеткий поиск (fuzzy search) с ~70% совпадением
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                // Точное совпадение по имени и описанию
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                  // Неточное совпадение (похожие слова)
                  ->orWhereRaw('SOUNDEX(name) = SOUNDEX(?)', [$searchTerm])
                  // Поиск по частям слова
                  ->orWhere('name', 'LIKE', '%' . str_replace(' ', '%', $searchTerm) . '%');
            });
        }

        $tasks = $query->orderBy('order')->paginate(20);

        return response()->json([
            'tasks' => $tasks->items(),
            'has_more' => $tasks->hasMorePages(),
            'next_page' => $tasks->currentPage() + 1,
        ]);
    }
}
