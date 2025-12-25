@extends('layouts.app')

@section('content')
<div class="minimal-container">
    @if(session('success'))
        <div class="minimal-alert">
            <i class="bi bi-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Хедер проекта -->
    <div class="project-header-minimal">
        <div class="back-button">
            <a href="{{ route('projects.index') }}" class="minimal-btn minimal-btn-ghost">
                <i class="bi bi-arrow-left"></i>
                Проекты
            </a>
        </div>

        <div class="project-title-section">
            <h1>{{ $project->name }}</h1>
            @if($project->address)
                <div class="project-meta">
                    <i class="bi bi-geo-alt"></i>
                    {{ $project->address }}
                </div>
            @endif
        </div>

        <div class="project-stats">
            <div class="stat-item">
                <div class="stat-label">Прогресс</div>
                <div class="stat-value">{{ $project->progress }}%</div>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" style="width: {{ $project->progress }}%"></div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Статус</div>
                <span class="project-status status-{{ strtolower(str_replace(' ', '-', $project->status)) }}">
                    {{ $project->status }}
                </span>
            </div>
        </div>

        @can('generateReports', $project)
            <div class="project-actions">
                <div class="dropdown">
                    <button class="minimal-btn minimal-btn-ghost" data-bs-toggle="dropdown">
                        <i class="bi bi-file-earmark-text"></i>
                        Смета
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu minimal-dropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('projects.estimate.pdf', $project) }}" target="_blank">
                                <i class="bi bi-file-pdf"></i>
                                Скачать PDF
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('projects.estimate.excel', $project) }}">
                                <i class="bi bi-file-excel"></i>
                                Скачать Excel
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('projects.documents.templates', $project) }}" class="minimal-btn minimal-btn-ghost">
                    <i class="bi bi-file-earmark-text"></i>
                    Документы
                </a>
            </div>
        @endcan
        @can('update', $project)
            <a href="{{ route('projects.edit', $project) }}" class="minimal-btn minimal-btn-ghost">
                <i class="bi bi-pencil"></i>
                Изменить
            </a>
        @endcan
    </div>

    <!-- Табы -->
    <div class="minimal-tabs">
        <button class="tab-button active" data-tab="stages">
            <i class="bi bi-list-check"></i>
            Этапы
            <span class="tab-badge">{{ $project->stages->count() }}</span>
        </button>
        @can('manageTeam', $project)
            <button class="tab-button" data-tab="participants">
                <i class="bi bi-people"></i>
                Команда
                <span class="tab-badge">{{ $project->participants->count() }}</span>
            </button>
        @else
            <button class="tab-button" data-tab="foreman">
                <i class="bi bi-person"></i>
                Прораб
            </button>
        @endcan
    </div>

    <!-- Контент табов -->
    <div class="tab-content-minimal">
        <!-- Вкладка Этапы -->
        <div class="tab-panel active" data-panel="stages">
            @if($project->stages->isEmpty())
                <div class="empty-state-small">
                    <i class="bi bi-list-check"></i>
                    <p>Этапы еще не добавлены</p>
                </div>
            @else
                <div class="stages-list">
                    @foreach($project->stages as $stage)
                        <a href="{{ route('stages.show', [$project, $stage]) }}" class="stage-item-minimal">
                            <div class="stage-item-header">
                                <h3>{{ $stage->name }}</h3>
                                <div class="stage-status-badges">
                                    @if($stage->tasks->count() > 0)
                                        <span class="mini-badge">{{ $stage->tasks->count() }} задач</span>
                                    @endif
                                    <span class="stage-status status-{{ strtolower(str_replace(' ', '-', $stage->status)) }}">
                                        {{ $stage->status }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="stage-dates">
                                <i class="bi bi-calendar3"></i>
                                {{ $stage->start_date->format('d.m.Y') }} — {{ $stage->end_date->format('d.m.Y') }}
                            </div>

                            @can('manageTeam', $project)
                                @if($stage->participants->count() > 0)
                                <div class="stage-participants">
                                    <i class="bi bi-people"></i>
                                    {{ $stage->participants->pluck('name')->join(', ') }}
                                </div>
                                @endif
                            @endcan

                            @if($stage->description)
                                <div class="stage-description">
                                    {{ Str::limit($stage->description, 100) }}
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Вкладка Участники -->
        @can('manageTeam', $project)
            <div class="tab-panel" data-panel="participants">
                @if($project->participants->isEmpty())
                    <div class="empty-state-small">
                        <i class="bi bi-people"></i>
                        <p>Участники еще не добавлены</p>
                    </div>
                @else
                    <div class="participants-list">
                        @foreach($project->participants as $participant)
                            <div class="participant-item-minimal">
                                <div class="participant-avatar">
                                    {{ strtoupper(substr($participant->name, 0, 1)) }}
                                </div>
                                <div class="participant-info">
                                    <div class="participant-name">{{ $participant->name }}</div>
                                    <div class="participant-role">{{ $participant->role }}</div>
                                </div>
                                <a href="tel:{{ $participant->phone }}" class="minimal-btn minimal-btn-ghost minimal-btn-icon">
                                    <i class="bi bi-telephone"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div class="tab-panel" data-panel="foreman">
                @php
                    // Находим владельца проекта
                    $foreman = $project->owner;
                @endphp
                @if($foreman)
                    <div class="foreman-contact-card">
                        <div class="foreman-avatar-large">
                            {{ strtoupper(substr($foreman->name, 0, 1)) }}
                        </div>
                        <h3>{{ $foreman->name }}</h3>
                        <p>Прораб проекта</p>
                        <a href="tel:{{ $foreman->phone }}" class="minimal-btn minimal-btn-primary minimal-btn-large">
                            <i class="bi bi-telephone"></i>
                            Позвонить
                        </a>
                    </div>
                @else
                    <div class="empty-state-small">
                        <i class="bi bi-person"></i>
                        <p>Информация о прорабе недоступна</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
// Переключение табов
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
        const tabName = button.dataset.tab;
        
        // Убираем активность со всех
        document.querySelectorAll('.tab-button').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        
        // Добавляем активность к выбранным
        button.classList.add('active');
        document.querySelector(`[data-panel="${tabName}"]`).classList.add('active');
    });
});
</script>
@endsection
