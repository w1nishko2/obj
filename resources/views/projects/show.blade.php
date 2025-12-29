@extends('layouts.app')

@section('content')
<style>
.breadcrumb-compact {
    white-space: nowrap;
    overflow: hidden;
}
.breadcrumb-compact .breadcrumb-item {
    display: inline-block;
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    vertical-align: middle;
}
.project-show-container {
    max-width: 1400px;
    margin: 0 auto;
}
@media (min-width: 1600px) {
    .project-show-container {
        max-width: 1600px;
    }
}
</style>
<div class="container project-show-container">
    <div class="row mb-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-compact mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Проекты</a></li>
                    <li class="breadcrumb-item active" title="{{ $project->name }}">{{ Str::limit($project->name, 20) }}</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h4 class="mb-0 project-title-mobile">{{ $project->name }}</h4>
                                
                            </div>
                            <div class="text-muted project-address-mobile"><i class="bi bi-geo-alt"></i> {{ $project->address }}</div>
                        </div>
                    </div>
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-12">
                            <div class="d-flex justify-content-between mb-2 small text-muted">
                                <span>Прогресс</span>
                                  @if($project->status === 'В работе')
                                <span class="badge bg-primary px-3 py-2">{{ $project->status }}</span>
                            @elseif($project->status === 'На паузе')
                                <span class="badge bg-warning px-3 py-2">{{ $project->status }}</span>
                            @else
                                <span class="badge bg-success px-3 py-2">{{ $project->status }}</span>
                            @endif
                                <strong class="text-dark">{{ $project->progress }}%</strong>
                            </div>
                            <div class="progress" style="height: 6px; border-radius: 10px;">
                                <div class="progress-bar" style="width: {{ $project->progress }}%; border-radius: 10px;"></div>
                            </div>
                        </div>
                      
                        @can('viewCosts', $project)
                            <div class="col-lg-12 ">
                                <strong class="fs-5">{{ number_format($project->total_cost, 0, ',', ' ') }} ₽</strong>
                            </div>
                        @endcan
                     
                        <div class="col-lg-12 text-end">
                            <div class="d-flex flex-wrap gap-2 justify-content-end">
                                @can('generateReports', $project)
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-file-earmark-spreadsheet"></i> Смета
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('projects.estimate.pdf', $project) }}" target="_blank" rel="noopener noreferrer">
                                                    <i class="bi bi-file-pdf text-danger me-2"></i>
                                                    PDF формат
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('projects.estimate.excel', $project) }}">
                                                    <i class="bi bi-file-excel text-success me-2"></i>
                                                    Excel формат
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <a href="{{ route('projects.documents.templates', $project) }}" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-file-earmark-text"></i> Документы
                                    </a>
                                @else
                                    @if($currentUserRole && $currentUserRole->role === 'owner')
                                        <button type="button" class="btn btn-sm btn-outline-secondary" disabled data-bs-toggle="tooltip" title="Доступно на платных тарифах">
                                            <i class="bi bi-lock"></i> Смета
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" disabled data-bs-toggle="tooltip" title="Доступно на платных тарифах">
                                            <i class="bi bi-lock"></i> Документы
                                        </button>
                                    @endif
                                @endcan
                                @can('update', $project)
                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i> Изменить
                                </a>
                                @endcan
                                @can('archive', $project)
                                    <form action="{{ route('projects.archive', $project) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning" 
                                                onclick="return confirm('Переместить проект в архив?')">
                                            <i class="bi bi-archive"></i> В архив
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Вкладки -->
    <ul class="nav nav-tabs mb-4" id="projectTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="stages-tab" data-bs-toggle="tab" 
                    data-bs-target="#stages" type="button" role="tab">
                Этапы ({{ $project->stages->count() }})
            </button>
        </li>
        @can('manageTeam', $project)
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="participants-tab" data-bs-toggle="tab" 
                    data-bs-target="#participants" type="button" role="tab">
                Участники ({{ $project->participants->count() }})
            </button>
        </li>
        @else
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contacts-tab" data-bs-toggle="tab" 
                    data-bs-target="#contacts" type="button" role="tab">
                Контакты ({{ $project->participants->count() + 1 }})
            </button>
        </li>
        @endcan
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="documents-tab" data-bs-toggle="tab" 
                    data-bs-target="#documents" type="button" role="tab">
                Документы ({{ $project->documents->count() }})
            </button>
        </li>
    </ul>

    <div class="tab-content" id="projectTabsContent">
        <!-- Вкладка Этапы -->
        <div class="tab-pane fade show active" id="stages" role="tabpanel">
            @can('createStages', $project)
            <div class="row mb-3">
                <div class="col text-end">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStageModal">
                        <i class="bi bi-plus"></i> Создать этап
                    </button>
                </div>
            </div>
            @endif

            <!-- Поисковая строка (показывается если > 15 этапов) -->
            @if($totalStages > 15)
            <div class="row mb-3">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" 
                               class="form-control" 
                               id="stagesSearchInput" 
                               placeholder="Поиск этапа..."
                               autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button" id="clearStagesSearch" style="display: none;">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <small class="text-muted">Начните вводить название этапа для поиска</small>
                </div>
            </div>
            @endif

            @if($project->stages->isEmpty())
                <div class="alert alert-info" id="noStagesMessage">
                    У этого проекта пока нет этапов
                </div>
            @else
                <div class="row" id="stagesContainer" data-project-id="{{ $project->id }}">
                    @foreach($project->stages as $stage)
                        <div class="col-md-6 mb-3">
                            <div class="card stage-card position-relative" 
                                 style="cursor: pointer;" 
                                 data-stage-id="{{ $stage->id }}"
                                 @can('createStages', $project)
                                 data-long-press="true"
                                 data-delete-url="{{ route('projects.stages.destroy', [$project, $stage]) }}"
                                 data-delete-target="#deleteStageModal{{ $stage->id }}"
                                 @endcan
                                 onclick="window.location.href='{{ route('stages.show', [$project, $stage]) }}'">
                                <div class="long-press-indicator"></div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title">{{ $stage->name }}</h5>
                                        @if($stage->tasks->count() > 0)
                                            <span class="badge bg-info">{{ $stage->tasks->count() }} задач</span>
                                        @endif
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <p class="mb-0">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar"></i> 
                                                {{ $stage->start_date->format('d.m.Y') }} - {{ $stage->end_date->format('d.m.Y') }}
                                            </small>
                                        </p>

                                        @can('createStages', $project)
                                        <div onclick="event.stopPropagation()" class="d-flex align-items-center gap-2">
                                            <form action="{{ route('projects.stages.status', [$project, $stage]) }}" 
                                                  method="POST" class="d-inline stage-status-form" data-stage-id="{{ $stage->id }}">
                                                @csrf
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <input type="radio" class="btn-check status-radio" name="status" value="Не начат" 
                                                           id="status-{{ $stage->id }}-not-started" 
                                                           {{ $stage->status === 'Не начат' ? 'checked' : '' }}
                                                           data-stage-id="{{ $stage->id }}">
                                                    <label class="btn btn-outline-secondary px-2 py-1" 
                                                           for="status-{{ $stage->id }}-not-started"
                                                           style="font-size: 0.75rem;">
                                                        <i class="bi bi-circle"></i>
                                                    </label>

                                                    <input type="radio" class="btn-check status-radio" name="status" value="В работе" 
                                                           id="status-{{ $stage->id }}-in-progress" 
                                                           {{ $stage->status === 'В работе' ? 'checked' : '' }}
                                                           data-stage-id="{{ $stage->id }}">
                                                    <label class="btn btn-outline-primary px-2 py-1" 
                                                           for="status-{{ $stage->id }}-in-progress"
                                                           style="font-size: 0.75rem;">
                                                        <i class="bi bi-hourglass-split"></i>
                                                    </label>

                                                    <input type="radio" class="btn-check status-radio" name="status" value="Готово" 
                                                           id="status-{{ $stage->id }}-done" 
                                                           {{ $stage->status === 'Готово' ? 'checked' : '' }}
                                                           data-stage-id="{{ $stage->id }}">
                                                    <label class="btn btn-outline-success px-2 py-1" 
                                                           for="status-{{ $stage->id }}-done"
                                                           style="font-size: 0.75rem;">
                                                        <i class="bi bi-check-circle"></i>
                                                    </label>
                                                </div>
                                            </form>
                                            @can('viewCosts', $project)
                                                @if($stage->total_cost > 0)
                                                    <span class="badge bg-light text-dark">
                                                        <i class="bi bi-currency-dollar"></i> {{ number_format($stage->total_cost, 0, ',', ' ') }} ₽
                                                    </span>
                                                @endif
                                            @endcan
                                        </div>
                                        @endcan
                                    </div>
                                    @cannot('createStages', $project)
                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            @if($stage->status === 'Готово')
                                                <span class="badge bg-success">Завершен</span>
                                            @elseif($stage->status === 'В работе')
                                                <span class="badge bg-primary">В работе</span>
                                            @else
                                                <span class="badge bg-secondary">Не начат</span>
                                            @endif
                                        </div>
                                        @can('viewCosts', $project)
                                            @if($stage->total_cost > 0)
                                                <span class="badge bg-light text-dark">
                                                    <i class="bi bi-currency-dollar"></i> {{ number_format($stage->total_cost, 0, ',', ' ') }} ₽
                                                </span>
                                            @endif
                                        @endcan
                                    </div>
                                    @endcannot

                                    @if($stage->status === 'Готово')
                                        <div class="alert alert-success mb-0 py-2">
                                            <i class="bi bi-check-circle"></i> Этап завершен
                                        </div>
                                    @elseif($stage->status === 'В работе')
                                        <div class="alert alert-primary mb-0 py-2">
                                            <i class="bi bi-hourglass-split"></i> В процессе выполнения
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Индикатор загрузки для infinite scroll -->
                <div class="col-12 text-center py-3 d-none" id="stagesLoader">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                </div>
                
                <!-- Сообщение об отсутствии результатов поиска -->
                <div class="col-12 d-none" id="noStagesFound">
                    <div class="alert alert-info">
                        <i class="bi bi-search"></i> Ничего не найдено. Попробуйте изменить запрос.
                    </div>
                </div>
            @endif

            {{-- Модальные окна удаления этапов --}}
            @can('createStages', $project)
                @foreach($project->stages as $stage)
                {{-- Скрытая кнопка-триггер для модалки --}}
                <button type="button" class="d-none" id="triggerDeleteStage{{ $stage->id }}" data-bs-toggle="modal" data-bs-target="#deleteStageModal{{ $stage->id }}"></button>
                <div class="modal fade" id="deleteStageModal{{ $stage->id }}" tabindex="-1">
                    <div class="modal-dialog modal-fullscreen m-0">
                        <div class="modal-content">
                            <div class="modal-header border-0 pb-2">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="px-4">
                                <div class="wizard-header text-center">
                                    <h2 class="text-danger"><i class="bi bi-exclamation-triangle"></i> Удалить этап?</h2>
                                    <p>Это действие нельзя отменить</p>
                                </div>
                            </div>
                            <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                                <div class="wizard-container" style="max-width: 600px; width: 100%;">
                                    <div class="alert alert-warning">
                                        <h5 class="alert-heading">{{ $stage->name }}</h5>
                                        <hr>
                                        <p class="mb-1"><strong>Будет удалено:</strong></p>
                                        <ul class="mb-0">
                                            <li>{{ $stage->tasks_count ?? $stage->tasks->count() }} {{ Str::plural('задача', $stage->tasks_count ?? $stage->tasks->count()) }}</li>
                                            <li>{{ $stage->tasks->sum('photos_count') }} {{ Str::plural('фото', $stage->tasks->sum('photos_count')) }}</li>
                                            <li>{{ $stage->tasks->sum('comments_count') }} {{ Str::plural('комментарий', $stage->tasks->sum('comments_count')) }}</li>
                                            <li>{{ $stage->materials_count ?? $stage->materials->count() }} {{ Str::plural('материал', $stage->materials_count ?? $stage->materials->count()) }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 justify-content-center">
                                <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                                <form action="{{ route('projects.stages.destroy', [$project, $stage]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="minimal-btn minimal-btn-danger">Удалить безвозвратно</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <!-- Вкладка Участники (для владельцев) -->
        @can('manageTeam', $project)
        <div class="tab-pane fade" id="participants" role="tabpanel">
            <div class="row mb-3">
                <div class="col text-end">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addParticipantModal">
                        <i class="bi bi-plus"></i> Добавить участника
                    </button>
                </div>
            </div>

            @if($project->participants->isEmpty())
                <div class="alert alert-info">
                    У этого проекта пока нет участников
                </div>
            @else
                <div class="row">
                    @foreach($project->participants as $participant)
                        @php
                            $participantUserRole = $participant->user_id 
                                ? $project->userRoles->where('user_id', $participant->user_id)->first() 
                                : null;
                            $isOwnerParticipant = $participantUserRole && $participantUserRole->role === 'owner';
                        @endphp
                        <div class="col-md-6 mb-3">
                            <div class="participant-card-wrapper position-relative"
                                 @if(!$isOwnerParticipant)
                                 data-long-press="true"
                                 data-participant-id="{{ $participant->id }}"
                                 data-remove-modal="#removeParticipantModal{{ $participant->id }}"
                                 @endif>
                                @if(!$isOwnerParticipant)
                                    <div class="long-press-indicator"></div>
                                @endif
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $participant->name }}</h6>
                                                <p class="mb-1 text-muted small">
                                                    <i class="bi bi-telephone"></i> {{ $participant->phone }}
                                                </p>
                                                @if($participant->user_id)
                                                    @if($participantUserRole)
                                                        <span class="badge bg-{{ $participantUserRole->role === 'owner' ? 'primary' : ($participantUserRole->role === 'client' ? 'info' : 'success') }}">
                                                            {{ $participantUserRole->role === 'owner' ? 'Владелец' : ($participantUserRole->role === 'client' ? 'Клиент' : 'Исполнитель') }}
                                                        </span>
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle"></i> Есть доступ
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning">Роль не назначена</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">Без аккаунта</span>
                                                @endif
                                            </div>
                                            <a href="tel:{{ $participant->phone }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-telephone-fill"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(!$isOwnerParticipant)
                            <!-- Модальное окно удаления участника -->
                            <div class="modal fade" id="removeParticipantModal{{ $participant->id }}" tabindex="-1">
                                <div class="modal-dialog modal-fullscreen m-0">
                                    <div class="modal-content">
                                        <form action="{{ route('projects.participants.remove', [$project, $participant]) }}" method="POST" class="d-flex flex-column h-100">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header border-0 pb-2">
                                                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                                                <div class="wizard-container text-center" style="max-width: 600px;">
                                                    <div class="mb-4">
                                                        <i class="bi bi-person-x" style="font-size: 4rem; color: #dc3545;"></i>
                                                    </div>
                                                    <h2 class="mb-3">Удалить участника?</h2>
                                                    <p class="mb-2"><strong>{{ $participant->name }}</strong></p>
                                                    <p class="text-muted mb-2">{{ $participant->phone }}</p>
                                                    <p class="text-muted">
                                                        Участник будет удален из проекта и потеряет доступ ко всем данным.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 justify-content-center">
                                                <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                                                <button type="submit" class="minimal-btn minimal-btn-primary" style="background: #dc3545;">
                                                    <i class="bi bi-person-x"></i>
                                                    Удалить
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
        @else
        <!-- Вкладка Контакты (для клиентов и исполнителей) -->
        <div class="tab-pane fade" id="contacts" role="tabpanel">
            @php
                $foreman = $project->user; // Владелец проекта (прораб)
                $isExecutor = $currentUserRole && $currentUserRole->role === 'executor';
                $isClient = $currentUserRole && $currentUserRole->role === 'client';
                $isForeman = auth()->user()->id === $foreman->id; // Является ли текущий пользователь прорабом
            @endphp
            
            <div class="row">
                <!-- Карточка прораба (исполнители НЕ видят телефон) -->
                @if($foreman && !$isExecutor)
                <div class="col-md-6 mb-3">
                    <div class="card border-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $foreman->name }}</h6>
                                    <p class="mb-1 text-muted small">
                                        <i class="bi bi-telephone"></i> {{ $foreman->phone }}
                                    </p>
                                    <span class="badge bg-primary">Прораб</span>
                                </div>
                                <a href="tel:{{ $foreman->phone }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-telephone-fill"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Карточки участников (с фильтрацией по роли) -->
                @foreach($project->participants as $participant)
                    @php
                        $participantUserRole = $participant->user_id 
                            ? $project->userRoles->where('user_id', $participant->user_id)->first() 
                            : null;
                        
                        // Участники без user_id (статус "Участник") видны только прорабу
                        if (!$participant->user_id && !$isForeman) {
                            continue;
                        }
                        
                        // Исполнители не видят клиентов
                        if ($isExecutor && $participantUserRole && $participantUserRole->role === 'client') {
                            continue;
                        }
                        
                        // Клиенты не видят других клиентов (только прораба и исполнителей)
                        if ($isClient && $participantUserRole && $participantUserRole->role === 'client') {
                            continue;
                        }
                    @endphp
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $participant->name }}</h6>
                                        <p class="mb-1 text-muted small">
                                            <i class="bi bi-telephone"></i> {{ $participant->phone }}
                                        </p>
                                        @if($participant->user_id)
                                            @if($participantUserRole)
                                                <span class="badge bg-{{ $participantUserRole->role === 'client' ? 'info' : 'success' }}">
                                                    {{ $participantUserRole->role === 'client' ? 'Клиент' : 'Исполнитель' }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">{{ $participant->role ?? 'Участник' }}</span>
                                        @endif
                                    </div>
                                    <a href="tel:{{ $participant->phone }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-telephone-fill"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if(!$foreman && $project->participants->isEmpty())
                <div class="alert alert-info">
                    Контакты участников недоступны
                </div>
            @endif
        </div>
        @endcan

        <!-- Вкладка Документы -->
        <div class="tab-pane fade" id="documents" role="tabpanel">
            @can('uploadDocuments', $project)
            <div class="row mb-3">
                <div class="col text-end">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                        <i class="bi bi-upload"></i> Загрузить 
                    </button>
                </div>
            </div>
            @endif

            @if($project->documents->isEmpty())
                <div class="alert alert-info">
                    У этого проекта пока нет документов
                </div>
            @else
                <div class="row">
                    @foreach($project->documents as $document)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start mb-2">
                                        <i class="bi {{ $document->file_icon }} fs-1 text-primary me-3"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $document->name }}</h6>
                                            <small class="text-muted">{{ $document->file_size_formatted }}</small>
                                        </div>
                                    </div>

                                    @if($document->description)
                                        <p class="small text-muted mb-2">{{ $document->description }}</p>
                                    @endif

                                    <div class="small text-muted mb-3">
                                        <div>Загрузил: {{ $document->uploader->name }}</div>
                                        <div>{{ $document->created_at->format('d.m.Y H:i') }}</div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ $document->secure_url }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           download>
                                            <i class="bi bi-download"></i> Скачать
                                        </a>
                                        @php
                                            $isOwner = $currentUserRole && $currentUserRole->role === 'owner';
                                            $canDeleteDoc = $document->uploaded_by === Auth::id() || $isOwner;
                                        @endphp
                                        @if($canDeleteDoc)
                                            <form action="{{ route('projects.documents.delete', [$project, $document->id]) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Удалить документ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Модальное окно добавления участника -->
<div class="modal fade" id="addParticipantModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen m-0">
        <div class="modal-content">
            <form action="{{ route('projects.participants.add', $project) }}" method="POST" class="d-flex flex-column h-100">
                @csrf
                <div class="modal-header border-0 pb-2">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="px-4">
                    <div class="wizard-header text-center">
                        <h2>Добавить участника</h2>
                        <p>Укажите роль, имя и телефон участника</p>
                    </div>
                </div>
                <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                    <div class="wizard-container" style="max-width: 600px; width: 100%;">
                    
                    <!-- Быстрый выбор из сотрудников -->
                    <div class="form-group-minimal">
                        <label>Выбрать из моих сотрудников</label>
                        <select class="minimal-input" id="employeeSelect" onchange="fillEmployeeData(this)">
                            <option value="">— Выбрать сотрудника —</option>
                        </select>
                        <small class="text-muted">Или заполните данные вручную ниже</small>
                    </div>

                    <div class="form-group-minimal">
                        <label>Роль</label>
                        <select class="minimal-input" id="role" name="role" required>
                            <option value="">Выберите роль</option>
                            <option value="Клиент">Клиент</option>
                            <option value="Исполнитель">Исполнитель</option>
                        </select>
                    </div>

                    <div class="form-group-minimal">
                        <label>Имя</label>
                        <input type="text" class="minimal-input" id="name" name="name" 
                               placeholder="Иван Петров" 
                               maxlength="255"
                               required>
                    </div>

                    <div class="form-group-minimal">
                        <label>Номер телефона</label>
                        <input type="tel" class="minimal-input phone-mask" id="participant_phone" name="phone" 
                               placeholder="+7 (999) 123-45-67"
                               required>
                    </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="minimal-btn minimal-btn-primary">Добавить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно загрузки документа -->
@can('uploadDocuments', $project)
<div class="modal fade" id="uploadDocumentModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen m-0">
        <div class="modal-content">
            <form action="{{ route('projects.documents.upload', $project) }}" method="POST" enctype="multipart/form-data" id="uploadDocumentForm" class="d-flex flex-column h-100">
                @csrf
                <div class="modal-header border-0 pb-2">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="px-4">
                    <div class="wizard-header text-center">
                        <h2>Загрузить документы</h2>
                        <p>Выберите файлы или перетащите их в область загрузки</p>
                    </div>
                </div>
                <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                    <div class="wizard-container" style="max-width: 700px; width: 100%;">
                    <!-- Drag and Drop зона -->
                    <div class="upload-zone mb-4" id="uploadZone">
                        <input type="file" name="documents[]" id="documentFiles" class="d-none" multiple>
                        <div class="upload-content" id="uploadContent">
                            <i class="bi bi-cloud-arrow-up upload-icon"></i>
                            <h5 class="upload-title">Перетащите файлы сюда</h5>
                            <p class="upload-text">или</p>
                            <button type="button" class="btn btn-primary btn-lg" onclick="document.getElementById('documentFiles').click()">
                                <i class="bi bi-folder2-open"></i> Выбрать файлы
                            </button>
                            <p class="upload-info mt-3">Можно загрузить несколько файлов. Максимальный размер: 200 МБ</p>
                        </div>
                        <div class="upload-preview d-none" id="uploadPreview">
                            <div class="files-list" id="filesList"></div>
                            <button type="button" class="btn btn-sm btn-outline-danger mt-3" onclick="clearFiles()">
                                <i class="bi bi-x-circle"></i> Очистить все
                            </button>
                        </div>
                    </div>

                    <div class="form-group-minimal">
                        <label>Название документа</label>
                        <input type="text" class="minimal-input" id="document_name" name="name" 
                               placeholder="Оставьте пустым для использования имени файла"
                               maxlength="255">
                    </div>

                    <div class="form-group-minimal">
                        <label>Описание</label>
                        <textarea class="minimal-input" id="document_description" name="description" 
                                  rows="3" 
                                  placeholder="Краткое описание документа"
                                  maxlength="1000"></textarea>
                    </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="minimal-btn minimal-btn-primary" id="uploadSubmitBtn" disabled>
                        <i class="bi bi-upload"></i> Загрузить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.upload-zone {
    border: 3px dashed var(--color-gray-light);
    border-radius: var(--border-radius);
    padding: 3rem 2rem;
    text-align: center;
    transition: all 0.3s ease;
    background: var(--color-white);
    min-height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.upload-zone:hover {
    border-color: var(--color-primary);
    background: #f8f9fa;
}

.upload-zone.drag-over {
    border-color: var(--color-primary);
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    border-style: solid;
    transform: scale(1.02);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.upload-content {
    width: 100%;
}

.upload-icon {
    font-size: 4.5rem;
    color: var(--color-primary);
    margin-bottom: 1rem;
    display: block;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-12px); }
}

.upload-title {
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--color-dark);
    margin-bottom: 0.5rem;
}

.upload-text {
    color: var(--color-gray);
    margin: 1rem 0;
    font-size: 1rem;
    font-weight: 500;
}

.upload-info {
    font-size: 0.85rem;
    color: var(--color-gray);
}

.upload-preview {
    width: 100%;
}

.files-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.file-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.2s ease;
    position: relative;
}

.file-item:hover {
    border-color: var(--color-primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.file-item-icon {
    font-size: 2.5rem;
    color: var(--color-success);
    margin-bottom: 0.5rem;
}

.file-item-name {
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--color-dark);
    word-break: break-all;
    text-align: center;
    margin: 0;
}

.file-item-size {
    font-size: 0.75rem;
    color: var(--color-gray);
    margin-top: 0.25rem;
}

.file-item-remove {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--color-danger);
    color: white;
    border: 2px solid white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.75rem;
    transition: transform 0.2s ease;
}

.file-item-remove:hover {
    transform: scale(1.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // ЗАГРУЗКА СОТРУДНИКОВ ДЛЯ ВЫБОРА
    // ========================================
    loadEmployeesForSelect();
    
    function loadEmployeesForSelect() {
        fetch('/employees-json')
            .then(response => response.json())
            .then(employees => {
                const employeeSelect = document.getElementById('employeeSelect');
                if (employeeSelect && employees.length > 0) {
                    employees.forEach(employee => {
                        const option = document.createElement('option');
                        option.value = JSON.stringify({
                            name: employee.name,
                            phone: employee.phone
                        });
                        option.textContent = `${employee.name} — ${employee.phone}`;
                        employeeSelect.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Ошибка загрузки сотрудников:', error));
    }
    
    // Функция для заполнения данных из выбранного сотрудника
    window.fillEmployeeData = function(select) {
        if (select.value) {
            const data = JSON.parse(select.value);
            document.getElementById('name').value = data.name;
            document.getElementById('participant_phone').value = data.phone;
        }
    };

    // ========================================
    // AJAX ОБНОВЛЕНИЕ СТАТУСОВ ЭТАПОВ
    // ========================================
    
    // Обработка изменения статуса этапа через AJAX
    document.querySelectorAll('.status-radio').forEach(radio => {
        radio.addEventListener('change', function(e) {
            const form = this.closest('.stage-status-form');
            const formData = new FormData(form);
            const stageId = this.getAttribute('data-stage-id');
            const newStatus = this.value;
            
            // Показываем индикатор загрузки (опционально)
            const btnGroup = this.closest('.btn-group');
            const originalHtml = btnGroup.innerHTML;
            
            // Отправляем AJAX запрос
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Обновляем бейдж статуса под этапом (если он есть)
                    const stageCard = document.querySelector(`#stage-${stageId}`);
                    if (stageCard) {
                        const statusBadge = stageCard.querySelector('.badge.bg-success, .badge.bg-primary, .badge.bg-secondary');
                        if (statusBadge) {
                            // Обновляем текст и класс бейджа
                            statusBadge.classList.remove('bg-success', 'bg-primary', 'bg-secondary');
                            if (newStatus === 'Готово') {
                                statusBadge.classList.add('bg-success');
                                statusBadge.innerHTML = '<i class="bi bi-check-circle"></i> Завершен';
                            } else if (newStatus === 'В работе') {
                                statusBadge.classList.add('bg-primary');
                                statusBadge.textContent = 'В работе';
                            } else {
                                statusBadge.classList.add('bg-secondary');
                                statusBadge.textContent = 'Не начат';
                            }
                        }
                    }
                    
                    // Показываем уведомление об успехе (опционально)
                    showNotification('Статус этапа обновлен!', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Ошибка при обновлении статуса', 'error');
                // Восстанавливаем предыдущее состояние
                btnGroup.innerHTML = originalHtml;
            });
        });
    });
    
    // Функция для показа уведомлений
    function showNotification(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
        
        // Автоматически удаляем уведомление через 3 секунды
        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 150);
        }, 3000);
    }
    
    // ========================================
    // ВОССТАНОВЛЕНИЕ СОСТОЯНИЯ СТРАНИЦЫ
    // ========================================
    
    const projectId = @json($project->id);
    const storageKey = `project_${projectId}_active_tab`;

    // Сохранение активной вкладки при переключении
    const tabButtons = document.querySelectorAll('#projectTabs button[data-bs-toggle="tab"]');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function(e) {
            const targetTab = e.target.getAttribute('data-bs-target').replace('#', '');
            localStorage.setItem(storageKey, targetTab);
        });
    });

    // Восстановление активной вкладки
    let activeTabToRestore = null;
    
    // Приоритет 1: вкладка из сессии (после отправки формы)
    @if(session('tab'))
        activeTabToRestore = '{{ session("tab") }}';
    @endif
    
    // Приоритет 2: вкладка из localStorage (после перезагрузки страницы)
    if (!activeTabToRestore) {
        const savedTab = localStorage.getItem(storageKey);
        if (savedTab) {
            activeTabToRestore = savedTab;
        }
    }
    
    // Восстанавливаем вкладку
    if (activeTabToRestore) {
        const tabButton = document.querySelector(`button[data-bs-target="#${activeTabToRestore}"]`);
        if (tabButton) {
            const tab = new bootstrap.Tab(tabButton);
            tab.show();
        }
    }

    // Прокрутка к определенному этапу
    @if(session('stage_id'))
        setTimeout(function() {
            const stageElement = document.getElementById('stage-{{ session("stage_id") }}');
            if (stageElement) {
                stageElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                // Подсветка элемента
                stageElement.style.transition = 'background-color 0.3s';
                stageElement.style.backgroundColor = '#fff3cd';
                setTimeout(function() {
                    stageElement.style.backgroundColor = '';
                }, 1000);
            }
        }, 500);
    @endif

    // ========================================
    // ЗАГРУЗКА ДОКУМЕНТОВ
    // ========================================
    
    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('documentFiles');
    const uploadContent = document.getElementById('uploadContent');
    const uploadPreview = document.getElementById('uploadPreview');
    const filesList = document.getElementById('filesList');
    const submitBtn = document.getElementById('uploadSubmitBtn');
    const modal = document.getElementById('uploadDocumentModal');

    // Клик по зоне = клик по input (только если файлы не выбраны)
    uploadZone.addEventListener('click', function(e) {
        // Открываем выбор файлов только если клик по upload-content и файлы еще не выбраны
        if (!uploadContent.classList.contains('d-none') && 
            (e.target === uploadZone || e.target.closest('.upload-content'))) {
            fileInput.click();
        }
    });

    // Предотвращаем стандартное поведение drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Подсветка при наведении файла
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        uploadZone.classList.add('drag-over');
    }

    function unhighlight(e) {
        uploadZone.classList.remove('drag-over');
    }

    // Обработка drop
    uploadZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            handleFiles(files);
        }
    }

    // Обработка выбора файла через input
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleFiles(this.files);
        }
    });

    function handleFiles(files) {
        filesList.innerHTML = '';
        
        let hasValidFiles = false;
        Array.from(files).forEach((file, index) => {
            // Проверка размера файла (200 МБ - соответствует бэкенду)
            const MAX_FILE_SIZE = 204800 * 1024; // 200 MB в байтах
            if (file.size > MAX_FILE_SIZE) {
                alert(`Файл "${file.name}" слишком большой! Максимальный размер: 200 МБ`);
                return;
            }
            
            hasValidFiles = true;
            
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            fileItem.dataset.index = index;
            
            const icon = document.createElement('i');
            icon.className = 'file-item-icon bi ' + getFileIcon(file.name);
            
            const name = document.createElement('p');
            name.className = 'file-item-name';
            name.textContent = file.name;
            
            const size = document.createElement('p');
            size.className = 'file-item-size';
            size.textContent = formatFileSize(file.size);
            
            const removeBtn = document.createElement('div');
            removeBtn.className = 'file-item-remove';
            removeBtn.innerHTML = '<i class="bi bi-x"></i>';
            removeBtn.onclick = function(e) {
                e.stopPropagation();
                removeFile(index);
            };
            
            fileItem.appendChild(removeBtn);
            fileItem.appendChild(icon);
            fileItem.appendChild(name);
            fileItem.appendChild(size);
            
            filesList.appendChild(fileItem);
        });
        
        if (hasValidFiles) {
            uploadContent.classList.add('d-none');
            uploadPreview.classList.remove('d-none');
            submitBtn.disabled = false;
        }
    }

    function getFileIcon(filename) {
        const extension = filename.split('.').pop().toLowerCase();
        
        if (['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'].includes(extension)) {
            return 'bi-file-image';
        } else if (['pdf'].includes(extension)) {
            return 'bi-file-pdf';
        } else if (['doc', 'docx'].includes(extension)) {
            return 'bi-file-word';
        } else if (['xls', 'xlsx'].includes(extension)) {
            return 'bi-file-excel';
        } else if (['zip', 'rar', '7z'].includes(extension)) {
            return 'bi-file-zip';
        } else if (['dwg', 'dxf'].includes(extension)) {
            return 'bi-file-earmark-ruled';
        } else {
            return 'bi-file-earmark-text';
        }
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) {
            return bytes + ' Б';
        } else if (bytes < 1048576) {
            return (bytes / 1024).toFixed(1) + ' КБ';
        } else {
            return (bytes / 1048576).toFixed(1) + ' МБ';
        }
    }

    function removeFile(index) {
        const dt = new DataTransfer();
        const files = Array.from(fileInput.files);
        
        files.forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        fileInput.files = dt.files;
        
        if (fileInput.files.length > 0) {
            handleFiles(fileInput.files);
        } else {
            clearFiles();
        }
    }

    // Очистка файлов
    window.clearFiles = function() {
        fileInput.value = '';
        filesList.innerHTML = '';
        uploadContent.classList.remove('d-none');
        uploadPreview.classList.add('d-none');
        submitBtn.disabled = true;
    };

    // Сброс формы при закрытии модального окна
    modal.addEventListener('hidden.bs.modal', function() {
        clearFiles();
        document.getElementById('document_name').value = '';
        document.getElementById('document_description').value = '';
    });
});
</script>

<style>
/* Дополнительные стили для страницы */
.card {
    border-radius: 8px;
}

.badge {
    font-weight: 500;
    font-size: 0.875rem;
}

.dropdown-menu {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 0.5rem;
    box-shadow: 0 4px 12px -2px rgb(0 0 0 / 0.08);
}

.dropdown-item {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    transition: all 0.15s ease;
}

.dropdown-item:hover {
    background: #f9fafb;
}

.nav-tabs .nav-link {
    border: none;
    color: #6b7280;
    padding: 0.75rem 1.5rem;
    transition: all 0.15s ease;
}

.nav-tabs .nav-link:hover {
    color: #2563eb;
    background: transparent;
}

.nav-tabs .nav-link.active {
    color: #2563eb;
    background: transparent;
    border-bottom: 2px solid #2563eb;
}

.btn-sm {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    transition: all 0.15s ease;
}

.btn-outline-primary:hover,
.btn-outline-success:hover,
.btn-outline-secondary:hover {
    transform: none;
}
</style>
@endif

<!-- Модальное окно создания этапа -->
@if(Auth::user()->isForemanOfProject($project))
<div class="modal fade" id="createStageModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen m-0">
        <div class="modal-content">
            <form action="{{ route('projects.stages.store', $project) }}" method="POST" class="d-flex flex-column h-100">
                @csrf
                <div class="modal-header border-0 pb-2">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="px-4">
                    <div class="wizard-header text-center">
                        <h2>Создать этап</h2>
                        <p>Укажите название и период выполнения</p>
                    </div>
                </div>
                <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                    <div class="wizard-container" style="max-width: 600px; width: 100%;">
                    <div class="form-group-minimal">
                        <label>Название этапа</label>
                        <input type="text" class="minimal-input" id="stage_name" name="name" 
                               placeholder="Например: Черновая отделка"
                               maxlength="255"
                               required>
                    </div>

                    <div class="form-group-minimal">
                        <label>Период выполнения</label>
                        <input type="text" class="minimal-input date-range-picker" 
                               id="stage_dates" 
                               placeholder="Выберите даты"
                               readonly>
                        <input type="hidden" name="start_date" id="stage_start_date">
                        <input type="hidden" name="end_date" id="stage_end_date">
                    </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="minimal-btn minimal-btn-primary">Создать</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация Flatpickr для дат этапа
    const stageDateInput = document.getElementById('stage_dates');
    if (stageDateInput) {
        flatpickr(stageDateInput, {
            mode: 'range',
            locale: 'ru',
            dateFormat: 'd.m.Y',
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    const year1 = selectedDates[0].getFullYear();
                    const month1 = String(selectedDates[0].getMonth() + 1).padStart(2, '0');
                    const day1 = String(selectedDates[0].getDate()).padStart(2, '0');
                    
                    const year2 = selectedDates[1].getFullYear();
                    const month2 = String(selectedDates[1].getMonth() + 1).padStart(2, '0');
                    const day2 = String(selectedDates[1].getDate()).padStart(2, '0');
                    
                    document.getElementById('stage_start_date').value = `${year1}-${month1}-${day1}`;
                    document.getElementById('stage_end_date').value = `${year2}-${month2}-${day2}`;
                }
            }
        });
    }
});
</script>

{{-- CSS и JS для долгого нажатия --}}
<style>
.long-press-indicator {
    position: absolute;
    top: 0;
    left: 0;
    width: 0;
    height: 4px;
    background: linear-gradient(90deg, #dc3545, #ff6b6b);
    border-radius: 4px 0 0 0;
    transition: width 2s linear;
    z-index: 10;
    opacity: 0;
}

.long-press-indicator.active {
    opacity: 1;
    width: 100%;
}

.minimal-btn-danger {
    background-color: #dc3545;
    color: white;
    border: 2px solid #dc3545;
}

.minimal-btn-danger:hover {
    background-color: #bb2d3b;
    border-color: #bb2d3b;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let pressTimer;
    let isLongPress = false;

    // Функция для всех элементов с долгим нажатием
    document.querySelectorAll('[data-long-press="true"]').forEach(element => {
        const indicator = element.querySelector('.long-press-indicator');
        const deleteTarget = element.getAttribute('data-delete-target');
        
        // Mouse events
        element.addEventListener('mousedown', function(e) {
            // Игнорируем клик по интерактивным элементам
            if (e.target.closest('form, button, input, select, a, .dropdown')) {
                return;
            }
            
            isLongPress = false;
            if (indicator) {
                indicator.classList.add('active');
            }
            
            pressTimer = setTimeout(() => {
                isLongPress = true;
                if (indicator) {
                    indicator.classList.remove('active');
                }
                // Открываем модальное окно через клик по скрытой кнопке
                if (deleteTarget) {
                    e.stopPropagation();
                    // Извлекаем ID из селектора (например: #deleteStageModal123 -> 123)
                    const modalId = deleteTarget.replace('#deleteStageModal', '').replace('#deleteTaskModal', '');
                    const triggerBtn = document.getElementById('triggerDeleteStage' + modalId) || 
                                      document.getElementById('triggerDeleteTask' + modalId);
                    if (triggerBtn) {
                        triggerBtn.click();
                    }
                }
            }, 1000);
        });

        element.addEventListener('mouseup', function() {
            clearTimeout(pressTimer);
            if (indicator) {
                indicator.classList.remove('active');
            }
        });

        element.addEventListener('mouseleave', function() {
            clearTimeout(pressTimer);
            if (indicator) {
                indicator.classList.remove('active');
            }
        });

        // Touch events для мобильных устройств
        element.addEventListener('touchstart', function(e) {
            if (e.target.closest('form, button, input, select, a, .dropdown')) {
                return;
            }
            
            isLongPress = false;
            if (indicator) {
                indicator.classList.add('active');
            }
            
            pressTimer = setTimeout(() => {
                isLongPress = true;
                if (indicator) {
                    indicator.classList.remove('active');
                }
                if (deleteTarget) {
                    e.preventDefault();
                    e.stopPropagation();
                    const modalId = deleteTarget.replace('#deleteStageModal', '').replace('#deleteTaskModal', '');
                    const triggerBtn = document.getElementById('triggerDeleteStage' + modalId) || 
                                      document.getElementById('triggerDeleteTask' + modalId);
                    if (triggerBtn) {
                        triggerBtn.click();
                    }
                }
            }, 1000);
        });

        element.addEventListener('touchend', function(e) {
            clearTimeout(pressTimer);
            if (indicator) {
                indicator.classList.remove('active');
            }
            // Предотвращаем обычный клик, если было долгое нажатие
            if (isLongPress) {
                e.preventDefault();
                e.stopPropagation();
            }
        });

        element.addEventListener('touchmove', function() {
            clearTimeout(pressTimer);
            if (indicator) {
                indicator.classList.remove('active');
            }
        });

        // Предотвращаем открытие стандартного модального окна при долгом нажатии
        element.addEventListener('click', function(e) {
            if (isLongPress) {
                e.preventDefault();
                e.stopPropagation();
                isLongPress = false;
            }
        });
    });

    // ========================================
    // ДОЛГОЕ НАЖАТИЕ НА УЧАСТНИКАХ
    // ========================================
    document.querySelectorAll('.participant-card-wrapper[data-long-press="true"]').forEach(wrapper => {
        let pressTimer = null;
        let isLongPress = false;
        
        const indicator = wrapper.querySelector('.long-press-indicator');
        const removeModal = wrapper.getAttribute('data-remove-modal');

        const startPress = (e) => {
            // Игнорируем клик по интерактивным элементам
            if (e.target.closest('a, button, input, select')) {
                return;
            }
            
            isLongPress = false;
            if (indicator) {
                indicator.classList.add('active');
            }
            
            pressTimer = setTimeout(() => {
                isLongPress = true;
                if (indicator) {
                    indicator.classList.remove('active');
                }
                
                // Вибрация (если поддерживается)
                if (navigator.vibrate) {
                    navigator.vibrate(100);
                }
                
                // Открываем модальное окно
                if (removeModal) {
                    const modalElement = document.querySelector(removeModal);
                    if (modalElement) {
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                    }
                }
            }, 1000);
        };

        const endPress = () => {
            clearTimeout(pressTimer);
            if (indicator) {
                indicator.classList.remove('active');
            }
        };

        // Mouse events
        wrapper.addEventListener('mousedown', startPress);
        wrapper.addEventListener('mouseup', endPress);
        wrapper.addEventListener('mouseleave', endPress);

        // Touch events
        wrapper.addEventListener('touchstart', startPress);
        wrapper.addEventListener('touchend', function(e) {
            endPress();
            if (isLongPress) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
        wrapper.addEventListener('touchmove', endPress);

        // Предотвращаем клик при долгом нажатии
        wrapper.addEventListener('click', function(e) {
            if (isLongPress) {
                e.preventDefault();
                e.stopPropagation();
                isLongPress = false;
            }
        });
    });
});
</script>

<style>
/* Стили для долгого нажатия на участниках */
.participant-card-wrapper {
    position: relative;
}

.participant-card-wrapper .long-press-indicator {
    position: absolute;
    top: 0;
    left: 0;
    width: 0;
    height: 4px;
    background: linear-gradient(90deg, #dc3545, #ff6b7a);
    border-radius: 0.25rem 0.25rem 0 0;
    transition: width 2s linear;
    z-index: 10;
}

.participant-card-wrapper .long-press-indicator.active {
    width: 100%;
}

.participant-card-wrapper .card {
    cursor: pointer;
    user-select: none;
    transition: transform 0.2s;
}

.participant-card-wrapper:active .card {
    transform: scale(0.98);
}

/* Адаптивное сокращение текста на мобильных */
@media (max-width: 768px) {
    .project-title-mobile {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 1.1rem !important;
        line-height: 1.4;
    }
    
    .project-address-mobile {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 0.875rem;
    }
}

@media (max-width: 450px) {
    .project-title-mobile {
        font-size: 1rem !important;
        -webkit-line-clamp: 2;
    }
    
    .project-address-mobile {
        font-size: 0.8rem;
    }
}
</style>
@endif

<!-- Модальное окно с инструкциями после создания проекта -->
@if(session('show_tutorial'))
<div class="modal fade" id="tutorialModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-fullscreen m-0">
        <div class="modal-content">
            <div class="modal-header border-0 pb-2 position-sticky top-0 bg-white" style="z-index: 1000;">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" onclick="closeTutorial()"></button>
            </div>
            <div class="modal-body pt-0 overflow-auto" style="padding-bottom: 100px;">
                <div class="d-flex justify-content-center py-4">
                    <div class="wizard-container text-center" style="max-width: 600px; width: 100%; padding: 1rem;">
                        <div class="mb-4">
                            <i class="bi bi-lightbulb" style="font-size: 4rem; color: #a70000;"></i>
                        </div>
                        <h2 class="mb-3">Быстрая подсказка</h2>
                        <p class="text-muted mb-4">Узнайте основные возможности работы с проектом</p>
                        
                        <!-- Первая подсказка -->
                        <div class="tutorial-item mb-4 text-start">
                            <div class="d-flex align-items-start mb-3">
                                <div class="tutorial-number me-3" style="background: #a70000; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">
                                    1
                                </div>
                                <div>
                                    <h5 class="mb-2">Удаление элементов</h5>
                                    <p class="text-muted mb-3">Если хотите удалить что-либо, нажмите и подержите элемент</p>
                                </div>
                            </div>
                            <div class="tutorial-image-placeholder" style="background: #f8f9fa; border-radius: 8px; padding: 2rem 1rem; text-align: center; border: 2px dashed #dee2e6;">
                                <img src="/images/tutorial-delete.png" alt="Удаление элементов" style="max-width: 100%; height: auto; border-radius: 8px; display: none;" onerror="this.style.display='none'" onload="this.style.display='block'; this.parentElement.querySelector('.placeholder-text').style.display='none';">
                                <div class="placeholder-text text-muted">
                                    <i class="bi bi-hand-index-thumb" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                                    <small>Место для изображения tutorial-delete.png</small>
                                </div>
                            </div>
                        </div>

                        <!-- Видео инструкция -->
                        <div class="tutorial-item mb-4 text-start">
                            <div class="d-flex align-items-start mb-3">
                                <div class="tutorial-number me-3" style="background: #a70000; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">
                                    2
                                </div>
                                <div>
                                    <h5 class="mb-2">Видео инструкция</h5>
                                    <p class="text-muted mb-3">Подробное видео о работе с проектами (7 минут)</p>
                                </div>
                            </div>
                            <div class="tutorial-video-container" style="position: relative; width: 100%; max-width: 360px; margin: 0 auto; aspect-ratio: 9/16; background: #000; border-radius: 12px; overflow: hidden;">
                                <video 
                                    id="tutorialVideo"
                                    controls 
                                    playsinline
                                    preload="metadata"
                                    style="width: 100%; height: 100%; object-fit: contain;"
                                    poster="/images/tutorial-video-poster.jpg">
                                    <source src="/videos/instruction.mp4" type="video/mp4">
                                    <p class="text-muted p-3">Ваш браузер не поддерживает воспроизведение видео. <a href="/videos/instruction.mp4" download>Скачайте видео</a></p>
                                </video>
                                <!-- Индикатор загрузки -->
                                <div id="videoLoader" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: none;">
                                    <div class="spinner-border text-light" role="status">
                                        <span class="visually-hidden">Загрузка...</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i>
                                    Видео загружается потоково - страница не зависнет (93 МБ)
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tutorialModal = document.getElementById('tutorialModal');
    if (tutorialModal) {
        const modal = new bootstrap.Modal(tutorialModal);
        modal.show();
    }
});

function closeTutorial() {
    const tutorialModal = document.getElementById('tutorialModal');
    if (tutorialModal) {
        const modal = bootstrap.Modal.getInstance(tutorialModal);
        if (modal) {
            modal.hide();
        }
    }
}

// ========================================
// Поиск и пагинация этапов
// ========================================
const stagesContainer = document.getElementById('stagesContainer');
const stagesSearchInput = document.getElementById('stagesSearchInput');
const clearStagesSearch = document.getElementById('clearStagesSearch');
const stagesLoader = document.getElementById('stagesLoader');
const noStagesFound = document.getElementById('noStagesFound');

console.log('Поиск этапов - элементы:', {
    stagesContainer: !!stagesContainer,
    stagesSearchInput: !!stagesSearchInput,
    clearStagesSearch: !!clearStagesSearch,
    stagesLoader: !!stagesLoader,
    noStagesFound: !!noStagesFound
});

// Глобальные переменные для поиска этапов
let stagesPage = 2;
let stagesLoading = false;
let stagesHasMore = {{ $totalStages > 12 ? 'true' : 'false' }};
let stagesSearchQuery = '';
let stagesSearchTimeout = null;

// Функция загрузки этапов
async function loadStages() {
    if (stagesLoading || !stagesHasMore || !stagesContainer) return;

    console.log('loadStages вызвана. Page:', stagesPage, 'Query:', stagesSearchQuery);
    
    stagesLoading = true;
    if (stagesLoader) stagesLoader.classList.remove('d-none');
    if (noStagesFound) noStagesFound.classList.add('d-none');

    try {
        const projectId = stagesContainer.dataset.projectId;
        const url = `/projects/${projectId}/search-stages?page=${stagesPage}${stagesSearchQuery ? '&search=' + encodeURIComponent(stagesSearchQuery) : ''}`;
        
        console.log('Запрос к URL:', url);
        
        const response = await fetch(url);
        const data = await response.json();

        console.log('Ответ от сервера:', data);

        if (data.stages && data.stages.length > 0) {
            data.stages.forEach(stage => {
                stagesContainer.insertAdjacentHTML('beforeend', renderStageCard(stage));
            });

            stagesHasMore = data.has_more;
            if (stagesHasMore) {
                stagesPage = data.next_page;
            }
        } else if (stagesPage === 1) {
            console.log('Результаты поиска пусты');
            if (noStagesFound) noStagesFound.classList.remove('d-none');
        }

    } catch (error) {
        console.error('Ошибка загрузки этапов:', error);
    } finally {
        stagesLoading = false;
        if (stagesLoader) stagesLoader.classList.add('d-none');
    }
}

function renderStageCard(stage) {
    const projectId = stagesContainer.dataset.projectId;
    const tasksCount = stage.tasks_count || 0;
    const totalCost = stage.total_cost || 0;
    
    return `
        <div class="col-md-6 mb-3">
            <div class="card stage-card position-relative" 
                 style="cursor: pointer;" 
                 onclick="window.location.href='/projects/${projectId}/stages/${stage.id}'">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title">${escapeHtml(stage.name)}</h5>
                        ${tasksCount > 0 ? `<span class="badge bg-info">${tasksCount} задач</span>` : ''}
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="mb-0">
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i> 
                                ${formatDate(stage.start_date)} - ${formatDate(stage.end_date)}
                            </small>
                        </p>
                        
                        ${totalCost > 0 ? `
                            <span class="badge bg-light text-dark">
                                <i class="bi bi-currency-dollar"></i> ${formatCurrency(totalCost)} ₽
                            </span>
                        ` : ''}
                    </div>
                    
                    ${stage.status === 'Готово' ? `
                        <div class="alert alert-success mb-0 py-2">
                            <i class="bi bi-check-circle"></i> Этап завершен
                        </div>
                    ` : stage.status === 'В работе' ? `
                        <div class="alert alert-primary mb-0 py-2">
                            <i class="bi bi-hourglass-split"></i> В процессе выполнения
                        </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('ru-RU');
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('ru-RU').format(amount);
}

// Инициализация поиска этапов
if (stagesSearchInput) {
    console.log('Инициализация поиска этапов');
    stagesSearchInput.addEventListener('input', function() {
        console.log('Ввод в поиск этапов:', this.value);
        clearTimeout(stagesSearchTimeout);
        
        if (this.value.trim()) {
            if (clearStagesSearch) clearStagesSearch.style.display = 'block';
        } else {
            if (clearStagesSearch) clearStagesSearch.style.display = 'none';
        }
        
        stagesSearchTimeout = setTimeout(() => {
            stagesSearchQuery = this.value.trim();
            console.log('Запуск поиска этапов с запросом:', stagesSearchQuery);
            stagesPage = 1;
            stagesHasMore = true;
            if (stagesContainer) {
                stagesContainer.innerHTML = '';
                loadStages();
            }
        }, 800);
    });
}

if (clearStagesSearch) {
    clearStagesSearch.addEventListener('click', function() {
        if (stagesSearchInput) stagesSearchInput.value = '';
        this.style.display = 'none';
        stagesSearchQuery = '';
        window.location.reload();
    });
}

if (stagesContainer) {
if (stagesContainer) {
    // Infinite scroll
    const stagesObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !stagesLoading && stagesHasMore) {
                loadStages();
            }
        });
    }, { threshold: 0.1 });

    if (stagesLoader) {
        stagesObserver.observe(stagesLoader);
    }
}

</script>
@endif

@endsection
