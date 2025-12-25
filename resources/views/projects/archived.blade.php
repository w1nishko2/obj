@extends('layouts.app')

@section('content')
<div class="minimal-container">
    @if(session('success'))
        <div class="minimal-alert">
            <i class="bi bi-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="minimal-header">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <a href="{{ route('projects.index') }}" class="minimal-btn minimal-btn-ghost">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1>Архив проектов</h1>
        </div>
    </div>

    @if($projects->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-archive"></i>
            </div>
            <h3>Архив пуст</h3>
            <p>Здесь будут отображаться завершенные и неактуальные проекты</p>
            <a href="{{ route('projects.index') }}" class="minimal-btn minimal-btn-primary">
                <i class="bi bi-arrow-left"></i>
                Вернуться к проектам
            </a>
        </div>
    @else
        <div class="projects-grid">
            @foreach($projects as $project)
                @php
                    $userRole = Auth::user()->getRoleInProject($project);
                    $isOwner = $userRole && $userRole->role === 'owner';
                @endphp
                <div class="project-card archived-project">
                    <div class="archived-badge">
                        <i class="bi bi-archive"></i>
                        Архив
                    </div>
                    
                    <a href="{{ route('projects.show', $project) }}" style="text-decoration: none; color: inherit;">
                        <div class="project-card-header">
                            <h3>{{ $project->name }}</h3>
                            <span class="project-status status-{{ strtolower(str_replace(' ', '-', $project->status)) }}">
                                {{ $project->status }}
                            </span>
                        </div>
                        
                        @if($project->address)
                            <div class="project-address">
                                <i class="bi bi-geo-alt"></i>
                                {{ $project->address }}
                            </div>
                        @endif

                        <div class="project-progress">
                            <div class="progress-info">
                                <span class="progress-label">Прогресс</span>
                                <span class="progress-value">{{ $project->progress }}%</span>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" style="width: {{ $project->progress }}%"></div>
                            </div>
                        </div>
                    </a>

                    @if($isOwner)
                        <div class="archived-actions">
                            <form action="{{ route('projects.unarchive', $project) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="minimal-btn minimal-btn-ghost" 
                                        onclick="return confirm('Восстановить проект из архива?')">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                    Восстановить
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.archived-project {
    position: relative;
    opacity: 0.85;
    border: 2px solid var(--color-gray-light);
}

.archived-project:hover {
    opacity: 1;
}

.archived-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: var(--color-gray);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    z-index: 1;
}

.archived-actions {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--color-gray-light);
    display: flex;
    justify-content: center;
}

.archived-actions button {
    width: 100%;
}
</style>
@endsection
