<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectStage;
use App\Models\ProjectParticipant;
use App\Models\WorkTemplateType;
use App\Models\WorkTemplateStage;
use App\Models\WorkTemplateTask;
use App\Services\ProjectNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ProjectController extends Controller
{
    protected ProjectNotificationService $notificationService;

    public function __construct(ProjectNotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        // Получаем проекты где пользователь - владелец ИЛИ участник
        $projects = Auth::user()->allAccessibleProjects()
            ->where(function($query) {
                $query->where('is_archived', 0)
                      ->orWhereNull('is_archived');
            })
            ->get()
            ->sortBy(function($project) {
                // Сортируем: сначала неготовые проекты (прогресс < 100%), потом готовые (прогресс = 100%)
                return $project->progress >= 100 ? 1 : 0;
            });

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        // Проверяем наличие подписки
        if (!Auth::user()->hasAnyPlan()) {
            return redirect()->route('pricing.index')
                ->with('error', 'Для создания проектов необходимо оформить подписку.');
        }

        // Только прорабы могут создавать проекты
        if (!Auth::user()->isForeman()) {
            return redirect()->route('pricing.index')
                ->with('error', 'Для создания проектов необходимо активировать тариф Прораба.');
        }

        // Получаем шаблоны текущего пользователя для выбора типа работ
        $templates = WorkTemplateType::with(['stages.tasks'])
            ->forUser(Auth::id())
            ->active()
            ->orderBy('order')
            ->get();

        return view('projects.create', compact('templates'));
    }

    public function store(Request $request)
    {
        Log::info('=== Начало создания проекта ===', [
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);

        // Только прорабы могут создавать проекты
        if (!Auth::user()->isForeman()) {
            Log::warning('Попытка создания проекта не прорабом', [
                'user_id' => Auth::id(),
                'account_type' => Auth::user()->account_type
            ]);
            return redirect()->route('pricing.index')
                ->with('error', 'Для создания проектов необходимо активировать тариф Прораба.');
        }

        // Проверка лимита проектов
        if (!Auth::user()->canCreateProjects()) {
            $user = Auth::user();
            $plan = \App\Models\Plan::where('slug', $user->subscription_type)->first();
            // Правильно обрабатываем null (безлимит)
            $maxProjects = $plan && isset($plan->features['max_projects']) 
                ? $plan->features['max_projects'] 
                : 0;
            
            Log::warning('Достигнут лимит проектов', [
                'user_id' => $user->id,
                'current_projects' => $user->projects()->count(),
                'max_projects' => $maxProjects, // null = безлимит
                'subscription_type' => $user->subscription_type,
                'plan_found' => $plan ? 'yes' : 'no'
            ]);
            
            $message = $maxProjects === 1 
                ? 'Достигнут лимит проектов для бесплатного тарифа (1 проект). Оформите подписку для создания большего количества проектов.'
                : "Достигнут лимит проектов ({$maxProjects} проектов). Оформите подписку на более высокий тариф.";
            
            return redirect()->back()->with('error', $message);
        }

        Log::info('Начало валидации данных');

        // Фильтруем пустые этапы и участников перед валидацией
        $requestData = $request->all();
        
        // Фильтруем пустые этапы (где нет имени)
        if (isset($requestData['stages']) && is_array($requestData['stages'])) {
            $requestData['stages'] = array_filter($requestData['stages'], function($stage) {
                return !empty($stage['name']) || !empty($stage['start_date']) || !empty($stage['end_date']);
            });
            $requestData['stages'] = array_values($requestData['stages']); // Переиндексация
        }
        
        // Фильтруем пустых участников (где нет имени или телефона)
        if (isset($requestData['participants']) && is_array($requestData['participants'])) {
            $requestData['participants'] = array_filter($requestData['participants'], function($participant) {
                return !empty($participant['name']) || !empty($participant['phone']);
            });
            $requestData['participants'] = array_values($requestData['participants']); // Переиндексация
        }
        
        // Заменяем данные запроса на отфильтрованные
        $request->merge($requestData);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'work_type' => 'nullable|string|max:255',
            'markup_percent' => 'nullable|numeric|min:0|max:999.99',
            'template_id' => 'nullable|exists:work_template_types,id',
            'use_templates' => 'nullable|boolean',
            'stages' => 'nullable|array',
            'stages.*.name' => 'required|string|max:255',
            'stages.*.start_date' => 'required|date',
            'stages.*.end_date' => 'required|date|after_or_equal:stages.*.start_date',
            'stages.*.template_tasks' => 'nullable|string',
            'participants' => 'nullable|array',
            'participants.*.name' => 'required|string|max:255',
            'participants.*.phone' => 'required|string|max:20',
            'participants.*.role' => 'required|in:Клиент,Исполнитель',
        ]);

        Log::info('Валидация успешна', ['validated_data' => $validated]);

        // Используем транзакцию для атомарности операции
        try {
            $project = DB::transaction(function () use ($validated) {
                Log::info('Начало транзакции создания проекта');
                
                $project = Auth::user()->projects()->create([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'work_type' => $validated['work_type'] ?? null,
                'markup_percent' => $validated['markup_percent'] ?? 0,
                'status' => 'В работе',
            ]);
            
                Log::info('Проект создан в БД', ['project_id' => $project->id]);

        // Создаём роль владельца в новой системе ролей
        \App\Models\ProjectUserRole::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'role' => 'owner',
            ...(\App\Models\ProjectUserRole::getDefaultPermissions('owner'))
        ]);

        // Если выбран шаблон, создаём этапы и задачи из него
        if (isset($validated['template_id'])) {
            $template = WorkTemplateType::with(['stages.tasks'])
                ->findOrFail($validated['template_id']);
            
            // Сохраняем название типа работ из шаблона
            $project->update(['work_type' => $template->name]);
            
            $currentDate = now();
            
            foreach ($template->stages as $index => $templateStage) {
                $endDate = $currentDate->copy()->addDays($templateStage->duration_days);
                
                $stage = $project->stages()->create([
                    'name' => $templateStage->name,
                    'start_date' => $currentDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'order' => $index,
                    'status' => 'Не начат',
                ]);

                // Создаём задачи из шаблона
                foreach ($templateStage->tasks as $taskIndex => $templateTask) {
                    $stage->tasks()->create([
                        'name' => $templateTask->name,
                        'description' => $templateTask->description,
                        'price' => $templateTask->price,
                        'status' => 'Не начата',
                        'order' => $taskIndex,
                        'created_by' => Auth::id(),
                    ]);
                }
                
                // Следующий этап начинается после окончания текущего
                $currentDate = $endDate->addDay();
            }
        } elseif (isset($validated['stages'])) {
            // Создаём этапы вручную (старая логика)
            foreach ($validated['stages'] as $index => $stageData) {
                $stage = $project->stages()->create([
                    'name' => $stageData['name'],
                    'start_date' => $stageData['start_date'],
                    'end_date' => $stageData['end_date'],
                    'order' => $index,
                    'status' => 'Не начат',
                ]);

                // Если есть шаблонные задачи, создаём их
                if (isset($stageData['template_tasks'])) {
                    $tasks = is_string($stageData['template_tasks']) 
                        ? json_decode($stageData['template_tasks'], true) 
                        : $stageData['template_tasks'];
                    
                    if (is_array($tasks)) {
                        foreach ($tasks as $taskIndex => $taskData) {
                            // Задача может быть строкой или массивом с данными
                            if (is_string($taskData)) {
                                $stage->tasks()->create([
                                    'name' => $taskData,
                                    'status' => 'Не начата',
                                    'order' => $taskIndex,
                                    'created_by' => Auth::id(),
                                ]);
                            } elseif (is_array($taskData)) {
                                // Если задача содержит полные данные (id, name, price и т.д.)
                                $stage->tasks()->create([
                                    'name' => $taskData['name'] ?? 'Задача',
                                    'description' => $taskData['description'] ?? null,
                                    'price' => $taskData['price'] ?? 0,
                                    'status' => 'Не начата',
                                    'order' => $taskIndex,
                                    'created_by' => Auth::id(),
                                ]);
                            }
                        }
                    }
                }
            }
        }

        if (isset($validated['participants'])) {
            foreach ($validated['participants'] as $participantData) {
                // Ищем пользователя по телефону
                $user = \App\Models\User::where('phone', $participantData['phone'])->first();

                Log::info('Adding participant to project', [
                    'phone' => $participantData['phone'],
                    'user_found' => $user ? 'yes' : 'no',
                    'user_id' => $user?->id,
                    'project_id' => $project->id,
                ]);

                // Создаём запись контакта
                $project->participants()->create([
                    'user_id' => $user?->id,
                    'name' => $participantData['name'],
                    'phone' => $participantData['phone'],
                ]);

                // Если пользователь найден - создаём роль с доступом
                if ($user) {
                    $role = match($participantData['role']) {
                        'Клиент' => 'client',
                        'Исполнитель' => 'executor',
                        default => 'client',
                    };

                    $permissions = \App\Models\ProjectUserRole::getDefaultPermissions($role);
                    $createdRole = $project->userRoles()->create([
                        'user_id' => $user->id,
                        'role' => $role,
                        ...$permissions,
                    ]);
                    
                    Log::info('Created user role in project', [
                        'role_id' => $createdRole->id,
                        'user_id' => $user->id,
                        'project_id' => $project->id,
                        'role' => $role,
                    ]);
                }
            }
        }

            return $project; // Возвращаем созданный проект из транзакции
        });

            Log::info('Транзакция завершена успешно', [
                'project_id' => $project->id,
                'project_name' => $project->name
            ]);
            
        } catch (\Exception $e) {
            Log::error('Ошибка при создании проекта', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Произошла ошибка при создании проекта: ' . $e->getMessage());
        }

        Log::info('Проект успешно создан', [
            'project_id' => $project->id,
            'project_name' => $project->name
        ]);

        return redirect()->route('projects.show', $project)->with([
            'success' => 'Проект успешно создан!',
            'show_tutorial' => true
        ]);
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        // Загружаем только первые 12 этапов для начального отображения
        $project->load([
            'stages' => function($query) {
                $query->withCount(['tasks', 'materials'])
                      ->orderBy('order')
                      ->limit(12);
            },
            'participants', 
            'documents.uploader', 
            'userRoles'
        ]);
        
        // Получаем общее количество этапов для условного отображения поиска
        $totalStages = \App\Models\ProjectStage::where('project_id', $project->id)->count();
        
        // Кешируем роль текущего пользователя для предотвращения N+1 запросов
        $currentUserRole = Auth::user()->getRoleInProject($project);

        return view('projects.show', compact('project', 'currentUserRole', 'totalStages'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'work_type' => 'nullable|string|max:255',
            'status' => 'required|in:В работе,На паузе,Завершен',
            'markup_percent' => 'nullable|numeric|min:0|max:999.99',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)->with('success', 'Проект обновлен!');
    }

    // Удаление проекта запрещено - используем архивирование
    public function destroy(Project $project)
    {
        abort(403, 'Удаление проектов запрещено. Используйте архивирование.');
    }

    // Архивирование проекта
    public function archive(Project $project)
    {
        $this->authorize('archive', $project);

        $project->update(['is_archived' => true]);

        return redirect()->route('projects.index')->with('success', 'Проект перемещен в архив!');
    }

    // Восстановление из архива
    public function unarchive(Project $project)
    {
        $this->authorize('archive', $project);

        $project->update(['is_archived' => false]);

        return redirect()->route('projects.archived')->with('success', 'Проект восстановлен из архива!');
    }

    // Просмотр архивных проектов
    public function archived()
    {
        $projects = Auth::user()->allAccessibleProjects()
            ->where('is_archived', 1)
            ->get()
            ->sortByDesc('updated_at');

        return view('projects.archived', compact('projects'));
    }

    public function storeStage(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $stage = $project->stages()->create([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'] ?? now(),
            'end_date' => $validated['end_date'] ?? now()->addDays(7),
            'order' => $project->stages()->count(),
            'status' => 'Не начат',
        ]);

        // Отправляем push-уведомление участникам проекта
        try {
            $this->notificationService->notifyStageCreated($project, $stage, Auth::user());
        } catch (\Exception $e) {
            Log::error('Failed to send push notification for stage created: ' . $e->getMessage());
        }

        return back()->with('success', 'Этап успешно создан!')
            ->with('tab', 'stages');
    }

    public function updateStage(Request $request, Project $project, ProjectStage $stage)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:Не начат,В работе,Готово',
        ]);

        $stage->update([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'] ?? $stage->start_date,
            'end_date' => $validated['end_date'] ?? $stage->end_date,
            'status' => $validated['status'] ?? $stage->status,
        ]);

        return redirect()->route('stages.show', [$project, $stage])
            ->with('success', 'Этап успешно обновлен!');
    }

    public function updateStageStatus(Request $request, Project $project, ProjectStage $stage)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'status' => 'required|in:Не начат,В работе,Готово',
        ]);

        $stage->update(['status' => $validated['status']]);

        // Отправляем push-уведомление участникам проекта
        try {
            $this->notificationService->notifyStageStatusChanged($project, $stage, $validated['status'], Auth::user());
        } catch (\Exception $e) {
            Log::error('Failed to send push notification for stage status changed: ' . $e->getMessage());
        }

        // Проверяем, если это AJAX запрос
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Статус этапа обновлен!',
                'stage_id' => $stage->id,
                'new_status' => $validated['status']
            ]);
        }

        return back()->with('success', 'Статус этапа обновлен!')
            ->with('tab', 'stages')
            ->with('stage_id', $stage->id);
    }

    public function addParticipant(Request $request, Project $project)
    {
        $this->authorize('manageTeam', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'regex:/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/'],
            'role' => 'required|in:Клиент,Исполнитель',
        ]);

        // Ищем пользователя по номеру телефона
        $user = \App\Models\User::where('phone', $validated['phone'])->first();

        Log::info('Adding participant via addParticipant method', [
            'phone' => $validated['phone'],
            'user_found' => $user ? 'yes' : 'no',
            'user_id' => $user?->id,
            'project_id' => $project->id,
        ]);

        // Определяем роль для project_user_roles
        $projectRole = match($validated['role']) {
            'Клиент' => 'client',
            'Исполнитель' => 'executor',
            default => 'client',
        };

        if ($user) {
            // Проверяем, не добавлен ли уже
            $existingRole = $project->userRoles()->where('user_id', $user->id)->first();
            if ($existingRole) {
                return back()->with('error', 'Этот пользователь уже участвует в проекте!')
                    ->with('tab', 'participants');
            }

            // Создаём запись контакта (для отображения)
            $project->participants()->create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'phone' => $validated['phone'],
            ]);

            // Создаём роль с правами доступа
            $permissions = \App\Models\ProjectUserRole::getDefaultPermissions($projectRole);
            $createdRole = $project->userRoles()->create([
                'user_id' => $user->id,
                'role' => $projectRole,
                ...$permissions,
            ]);
            
            Log::info('Created user role via addParticipant', [
                'role_id' => $createdRole->id,
                'user_id' => $user->id,
                'project_id' => $project->id,
                'role' => $projectRole,
            ]);

            // Отправляем push-уведомление о добавлении участника
            try {
                $this->notificationService->notifyParticipantAdded($project, $user, Auth::user());
            } catch (\Exception $e) {
                Log::error('Failed to send push notification for participant added: ' . $e->getMessage());
            }

            $message = 'Участник добавлен и получил доступ к проекту!';
        } else {
            // Если пользователя нет - просто сохраняем контакт
            $project->participants()->create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
            ]);

            $message = 'Контакт добавлен! (Для доступа к проекту пользователь должен зарегистрироваться с этим номером)';
        }

        return back()->with('success', $message)
            ->with('tab', 'participants');
    }

    // Удаление участника проекта
    public function removeParticipant(Project $project, ProjectParticipant $participant)
    {
        $this->authorize('manageTeam', $project);

        // Проверяем, что участник принадлежит этому проекту
        if ($participant->project_id !== $project->id) {
            abort(404);
        }

        // Проверяем, что это не владелец проекта
        if ($participant->user_id) {
            $userRole = $project->userRoles()->where('user_id', $participant->user_id)->first();
            if ($userRole && $userRole->role === 'owner') {
                return back()->with('error', 'Нельзя удалить владельца проекта!')
                    ->with('tab', 'participants');
            }

            // Удаляем роль пользователя в проекте
            if ($userRole) {
                $userRole->delete();
            }
        }

        // Удаляем участника
        $participant->delete();

        return back()->with('success', 'Участник удален из проекта!')
            ->with('tab', 'participants');
    }

    public function uploadDocument(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'documents' => 'required|array',
            'documents.*' => 'file|max:204800|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,gif,svg,webp,dwg,dxf,zip,rar,7z,txt,csv', // максимум 200MB на файл
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $uploadedCount = 0;
        $imageFormats = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp', 'image/webp'];

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('project_documents/' . $project->id, $fileName, 'public');
                $mimeType = $file->getClientMimeType();

                $project->documents()->create([
                    'uploaded_by' => Auth::id(),
                    'name' => $validated['name'] ?? $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $mimeType,
                    'file_size' => $file->getSize(),
                    'description' => $validated['description'] ?? null,
                ]);

                // Если это изображение - отправляем в очередь на обработку
                if (in_array($mimeType, $imageFormats) || str_starts_with($mimeType, 'image/')) {
                    \App\Jobs\ProcessImageJob::dispatch($filePath, 'public');
                }

                $uploadedCount++;
            }

            return back()->with('success', "Загружено файлов: {$uploadedCount}")
                ->with('tab', 'documents');
        }

        return back()->with('error', 'Ошибка загрузки документа');
    }

    public function deleteDocument(Project $project, $documentId)
    {
        $this->authorize('view', $project);

        $document = $project->documents()->findOrFail($documentId);
        
        // Проверяем: либо автор документа, либо владелец проекта
        $userRole = Auth::user()->getRoleInProject($project);
        $isOwner = $userRole && $userRole->role === 'owner';
        
        if ($document->uploaded_by !== Auth::id() && !$isOwner) {
            abort(403, 'Вы можете удалить только свой документ');
        }
        
        // Удаляем файл из хранилища
        Storage::disk('public')->delete($document->file_path);
        
        $document->delete();

        return back()->with('success', 'Документ удален!')
            ->with('tab', 'documents');
    }

    public function generateEstimatePDF(Project $project)
    {
        $this->authorize('generateReports', $project);

        // Проверяем, может ли пользователь генерировать сметы
        if (!Auth::user()->canGenerateEstimates()) {
            return redirect()->back()->with('error', 'Генерация смет доступна только на платных тарифах. Оформите подписку для доступа к этой функции.');
        }

        // Загружаем все данные проекта
        $project->load([
            'stages.tasks.assignedUser',
            'stages.materials.user',
            'stages.deliveries.user'
        ]);

        // Подготавливаем данные для сметы
        $estimateData = $this->prepareEstimateData($project);

        // Генерируем PDF
        $pdf = Pdf::loadView('estimates.pdf', $estimateData);
        
        return $pdf->download('smeta_' . Str::slug($project->name) . '.pdf');
    }

    public function generateEstimateExcel(Project $project)
    {
        $this->authorize('generateReports', $project);

        // Проверяем, может ли пользователь генерировать сметы
        if (!Auth::user()->canGenerateEstimates()) {
            return redirect()->back()->with('error', 'Генерация смет доступна только на платных тарифах. Оформите подписку для доступа к этой функции.');
        }

        // Загружаем все данные проекта
        $project->load([
            'stages.tasks.assignedUser',
            'stages.materials.user',
            'stages.deliveries.user'
        ]);

        // Подготавливаем данные для сметы
        $estimateData = $this->prepareEstimateData($project);

        // Генерируем Excel
        return Excel::download(
            new \App\Exports\EstimateExport($estimateData), 
            'smeta_' . Str::slug($project->name) . '.xlsx'
        );
    }

    private function prepareEstimateData(Project $project)
    {
        $data = [
            'project' => $project,
            'stages' => [],
            'total_tasks_cost' => 0,
            'total_materials_cost' => 0,
            'total_deliveries_cost' => 0,
            'grand_total' => 0,
        ];

        foreach ($project->stages as $stage) {
            $stageData = [
                'name' => $stage->name,
                'status' => $stage->status,
                'start_date' => $stage->start_date,
                'end_date' => $stage->end_date,
                'tasks' => [],
                'materials' => [],
                'deliveries' => [],
                'stage_tasks_cost' => 0,
                'stage_materials_cost' => 0,
                'stage_deliveries_cost' => 0,
                'stage_total' => 0,
            ];

            // Собираем задачи
            foreach ($stage->tasks as $task) {
                $taskCost = $task->final_cost ?? 0;
                $stageData['tasks'][] = [
                    'name' => $task->name,
                    'description' => $task->description,
                    'status' => $task->status,
                    'assigned_to' => $task->assignedUser?->name ?? 'Не назначен',
                    'cost' => $taskCost,
                ];
                $stageData['stage_tasks_cost'] += $taskCost;
            }

            // Собираем материалы
            foreach ($stage->materials as $material) {
                $materialTotal = $material->final_cost ?? 0;
                $stageData['materials'][] = [
                    'name' => $material->name,
                    'description' => $material->description,
                    'unit' => $material->unit,
                    'quantity' => $material->quantity,
                    'price_per_unit' => $material->price_per_unit,
                    'total_cost' => $materialTotal,
                ];
                $stageData['stage_materials_cost'] += $materialTotal;
            }

            // Собираем доставки
            foreach ($stage->deliveries as $delivery) {
                $deliveryTotal = $delivery->final_cost ?? 0;
                $stageData['deliveries'][] = [
                    'name' => $delivery->name,
                    'description' => $delivery->description,
                    'unit' => $delivery->unit,
                    'quantity' => $delivery->quantity,
                    'price_per_unit' => $delivery->price_per_unit,
                    'total_cost' => $deliveryTotal,
                ];
                $stageData['stage_deliveries_cost'] += $deliveryTotal;
            }

            $stageData['stage_total'] = $stageData['stage_tasks_cost'] + $stageData['stage_materials_cost'] + $stageData['stage_deliveries_cost'];
            
            $data['stages'][] = $stageData;
            $data['total_tasks_cost'] += $stageData['stage_tasks_cost'];
            $data['total_materials_cost'] += $stageData['stage_materials_cost'];
            $data['total_deliveries_cost'] += $stageData['stage_deliveries_cost'];
        }

        $data['grand_total'] = $data['total_tasks_cost'] + $data['total_materials_cost'] + $data['total_deliveries_cost'];

        return $data;
    }

    // Удаление этапа (только Прораб)
    public function destroyStage(Project $project, ProjectStage $stage)
    {
        // Только владелец проекта может удалять этапы
        $userRole = Auth::user()->getRoleInProject($project);
        if (!$userRole || $userRole->role !== 'owner') {
            abort(403, 'Только владелец проекта может удалять этапы');
        }

        // Удаляем все задачи этапа вместе со связанными данными
        foreach ($stage->tasks as $task) {
            // Удаляем фото задач
            foreach ($task->photos as $photo) {
                Storage::disk('public')->delete($photo->photo_path);
                $photo->delete();
            }

            // Удаляем комментарии задач
            $task->comments()->delete();

            // Удаляем задачу
            $task->delete();
        }

        // Удаляем материалы этапа
        $stage->materials()->delete();

        // Удаляем сам этап
        $stage->delete();

        return redirect()->route('projects.show', $project)
            ->with('success', 'Этап удален вместе со всеми задачами, материалами и фотоотчетами!')
            ->with('tab', 'stages');
    }

    /**
     * Поиск и пагинация этапов проекта (Ajax)
     */
    public function searchStages(Request $request, Project $project)
    {
        $this->authorize('view', $project);

        $query = ProjectStage::where('project_id', $project->id)
            ->with(['tasks', 'materials', 'deliveries'])
            ->withCount(['tasks', 'materials']);

        // Нечеткий поиск (fuzzy search) с ~70% совпадением
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                // Точное совпадение
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  // Поиск по частям слова
                  ->orWhere('name', 'LIKE', '%' . str_replace(' ', '%', $searchTerm) . '%');
            });
        }

        $stages = $query->orderBy('order')->paginate(12);

        return response()->json([
            'stages' => $stages->items(),
            'has_more' => $stages->hasMorePages(),
            'next_page' => $stages->currentPage() + 1,
        ]);
    }
}
