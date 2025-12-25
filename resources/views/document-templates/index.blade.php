@extends('layouts.app')

@section('content')
<style>
.doc-template-btn {
    padding: 2rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    transition: all 0.2s;
    text-decoration: none;
    display: block;
    background: white;
}
.doc-template-btn:hover {
    border-color: #2563eb;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
    transform: translateY(-2px);
}
.doc-template-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}
</style>

<div class="container" style="max-width: 900px;">
    <!-- Шапка -->
    <div class="mb-4">
        <a href="{{ route('projects.show', $project) }}" class="btn btn-link text-decoration-none ps-0">
            <i class="bi bi-arrow-left"></i> Назад
        </a>
        <h2 class="mt-2">Документы для клиента</h2>
    </div>

    @if(!$project->client_full_name)
        <div class="alert alert-warning mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle fs-4"></i>
                <div>
                    <strong>Сначала заполните данные клиента</strong>
                    <div class="mt-1">
                        <a href="{{ route('projects.client-data.edit', $project) }}" class="btn btn-sm btn-warning">
                            Заполнить данные
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Информация о клиенте (компактно) -->
    @if($project->client_full_name)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small mb-1">Клиент</div>
                        <h5 class="mb-1">{{ $project->client_full_name }}</h5>
                        @if($project->client_phone)
                            <small class="text-muted">{{ $project->client_phone }}</small>
                        @endif
                    </div>
                    <a href="{{ route('projects.client-data.edit', $project) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Шаблоны документов -->
    <div class="row g-3">
        @forelse($templates as $template)
            <div class="col-md-6">
                <a href="{{ route('projects.documents.generate', [$project, $template]) }}" 
                   class="doc-template-btn"
                   @if(!$project->client_full_name) 
                       onclick="return confirm('Сначала заполните данные клиента')"
                   @endif>
                    <div class="text-center">
                        @if($template->type === 'estimate')
                            <i class="bi bi-file-earmark-spreadsheet text-primary doc-template-icon"></i>
                        @elseif($template->type === 'contract')
                            <i class="bi bi-file-earmark-text text-info doc-template-icon"></i>
                        @elseif($template->type === 'act')
                            <i class="bi bi-file-earmark-check text-success doc-template-icon"></i>
                        @elseif($template->type === 'invoice')
                            <i class="bi bi-receipt text-warning doc-template-icon"></i>
                        @else
                            <i class="bi bi-file-earmark-pdf text-danger doc-template-icon"></i>
                        @endif
                        <h5 class="mb-2">{{ $template->name }}</h5>
                        <p class="text-muted small mb-0">{{ $template->description }}</p>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Шаблоны документов не найдены
                </div>
            </div>
        @endforelse
    </div>
</div>


@endsection
