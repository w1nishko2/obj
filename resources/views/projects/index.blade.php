@extends('layouts.app')

@section('content')
<div class="minimal-container">
    @if(session('success'))
        <div class="minimal-alert">
            <i class="bi bi-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="minimal-alert minimal-alert-error">
            <i class="bi bi-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Информация о лимите для бесплатного тарифа --}}
    @if(Auth::user()->isForeman() && Auth::user()->isFreePlan())
        <div class="subscription-warning">
            <div class="subscription-warning-content">
                <i class="bi bi-info-circle"></i>
                <div>
                    <strong>Текущий тариф: @if(Auth::user()->subscription_type === 'free') Бесплатный @elseif(str_contains(Auth::user()->subscription_type, 'starter')) Стартовый @elseif(str_contains(Auth::user()->subscription_type, 'professional')) Профессиональный @elseif(str_contains(Auth::user()->subscription_type, 'corporate')) Корпоративный @else Не активен @endif</strong>
                    @php
                        $remaining = Auth::user()->getRemainingProjectsCount();
                        $plan = \App\Models\Plan::where('slug', Auth::user()->subscription_type)->first();
                        $maxProjects = $plan ? ($plan->features['max_projects'] ?? 0) : 0;
                    @endphp
                    <p>Доступно проектов: 
                        @if($remaining === null)
                            безлимит
                        @else
                            {{ $remaining }} из {{ $maxProjects }}
                        @endif 
                    <a href="{{ route('pricing.index') }}">Оформите подписку</a> для снятия ограничений.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="minimal-header">
        <h1>Проекты</h1>
        <div style="display: flex; gap: 0.5rem;">
            @if(Auth::user()->isForeman())
                <a href="{{ route('projects.archived') }}" class="minimal-btn minimal-btn-ghost">
                    <i class="bi bi-archive"></i>
                    
                </a>
                <a href="{{ route('projects.create') }}" class="minimal-btn minimal-btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Проект
                </a>
            @else
                <a href="{{ route('projects.archived') }}" class="minimal-btn minimal-btn-ghost">
                    <i class="bi bi-archive"></i>
                    
                </a>
            @endif
        </div>
    </div>

    @if($projects->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-folder2-open"></i>
            </div>
            @if(Auth::user()->isForeman())
                <h3>Создайте первый проект</h3>
                <p>Нажмите на кнопку ниже, чтобы начать</p>
            @else
                <h3>Проектов пока нет</h3>
                <p>Прораб добавит вас в проекты</p>
            @endif
        </div>
    @else

        <div class="projects-grid">
            @foreach($projects as $project)
                @php
                    $userRole = Auth::user()->getRoleInProject($project);
                    $isOwner = $userRole && $userRole->role === 'owner';
                    $roleLabel = $userRole ? match($userRole->role) {
                        'owner' => 'Владелец',
                        'client' => 'Клиент',
                        'executor' => 'Исполнитель',
                        default => $userRole->role
                    } : 'Участник';
                    $roleColor = $userRole ? match($userRole->role) {
                        'owner' => '#28a745',
                        'client' => '#007bff',
                        'executor' => '#fd7e14',
                        default => '#6c757d'
                    } : '#6c757d';
                @endphp
                <div class="project-card-wrapper" 
                     @if($isOwner)
                     data-long-press="true" 
                     data-project-id="{{ $project->id }}"
                     data-archive-modal="#archiveModal{{ $project->id }}"
                     @endif>
                    @if($isOwner)
                        <div class="long-press-indicator"></div>
                    @endif
                    <a href="{{ route('projects.show', $project) }}" class="project-card">
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

                        <div class="project-footer">
                            @if($project->nearest_deadline)
                                <div class="project-deadline">
                                    <i class="bi bi-calendar3"></i>
                                    До {{ \Carbon\Carbon::parse($project->nearest_deadline)->format('d.m.Y') }}
                                </div>
                            @endif
                            <span class="project-role-badge-small" style="background-color: {{ $roleColor }};">
                                {{ $roleLabel }}
                            </span>
                        </div>
                    </a>
                </div>

                @if($isOwner)
                    <!-- Модальное окно архивирования -->
                    <div class="modal fade" id="archiveModal{{ $project->id }}" tabindex="-1">
                        <div class="modal-dialog modal-fullscreen m-0">
                            <div class="modal-content">
                                <form action="{{ route('projects.archive', $project) }}" method="POST" class="d-flex flex-column h-100">
                                    @csrf
                                    <div class="modal-header border-0 pb-2">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                                        <div class="wizard-container text-center" style="max-width: 600px;">
                                            <div class="mb-4">
                                                <i class="bi bi-archive" style="font-size: 4rem; color: #e8a66b;"></i>
                                            </div>
                                            <h2 class="mb-3">Архивировать проект?</h2>
                                            <p class="mb-2"><strong>{{ $project->name }}</strong></p>
                                            <p class="text-muted">
                                                Проект будет перемещен в архив. Вы сможете восстановить его в любое время.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 justify-content-center">
                                        <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                                        <button type="submit" class="minimal-btn minimal-btn-primary" style="background: #e8a66b;">
                                            <i class="bi bi-archive"></i>
                                            В архив
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

<style>
/* Стили для долгого нажатия */
.project-card-wrapper {
    position: relative;
}

.long-press-indicator {
    position: absolute;
    top: 0;
    left: 0;
    width: 0;
    height: 4px;
    background: linear-gradient(90deg, #e8a66b, #f4b87e);
    border-radius: 12px 0 0 0;
    transition: width 2s linear;
    z-index: 10;
    opacity: 0;
}

.long-press-indicator.active {
    opacity: 1;
    width: 100%;
}

.project-card {
    cursor: pointer;
    user-select: none;
    transition: transform 0.2s;
}

.project-card-wrapper:active .project-card {
    transform: scale(0.98);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Функция для всех карточек проектов с долгим нажатием
    document.querySelectorAll('[data-long-press="true"]').forEach(wrapper => {
        let pressTimer = null;
        let isLongPress = false;
        
        const indicator = wrapper.querySelector('.long-press-indicator');
        const archiveModal = wrapper.getAttribute('data-archive-modal');
        const card = wrapper.querySelector('.project-card');

        if (!card) {
            return;
        }

        // Начало нажатия
        const startPress = (e) => {
            isLongPress = false;
            
            if (indicator) {
                indicator.classList.add('active');
            }

            pressTimer = setTimeout(() => {
                isLongPress = true;
                
                // Убираем индикатор
                if (indicator) {
                    indicator.classList.remove('active');
                }

                // Вибрация (если поддерживается)
                if (navigator.vibrate) {
                    navigator.vibrate(100);
                }

                // Открываем модальное окно
                if (archiveModal) {
                    const modalElement = document.querySelector(archiveModal);
                    if (modalElement) {
                        // Используем Bootstrap через глобальный объект window или нативный метод
                        if (typeof window.bootstrap !== 'undefined') {
                            const modal = new window.bootstrap.Modal(modalElement);
                            modal.show();
                        } else {
                            // Fallback: используем data-атрибуты Bootstrap
                            modalElement.classList.add('show');
                            modalElement.style.display = 'block';
                            document.body.classList.add('modal-open');
                            
                            // Создаём backdrop
                            const backdrop = document.createElement('div');
                            backdrop.className = 'modal-backdrop fade show';
                            document.body.appendChild(backdrop);
                            
                            // Закрытие модального окна
                            const closeModal = () => {
                                modalElement.classList.remove('show');
                                modalElement.style.display = 'none';
                                document.body.classList.remove('modal-open');
                                backdrop.remove();
                            };
                            
                            modalElement.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
                                btn.addEventListener('click', closeModal);
                            });
                            
                            backdrop.addEventListener('click', closeModal);
                        }
                    }
                }
            }, 1000); // 1 секунда
        };

        // Конец нажатия
        const endPress = () => {
            if (pressTimer) {
                clearTimeout(pressTimer);
                pressTimer = null;
            }
            
            if (indicator) {
                indicator.classList.remove('active');
            }
        };

        // Блокируем клик по ссылке и переходим вручную
        card.addEventListener('click', (e) => {
            e.preventDefault();
            
            if (!isLongPress) {
                // Обычный клик - переходим по ссылке
                window.location.href = card.href;
            }
            
            // Сбрасываем флаг
            isLongPress = false;
        });

        // События для мобильных устройств
        card.addEventListener('touchstart', startPress);
        card.addEventListener('touchend', endPress);
        card.addEventListener('touchcancel', endPress);
        card.addEventListener('touchmove', endPress);

        // События для десктопа
        card.addEventListener('mousedown', startPress);
        card.addEventListener('mouseup', endPress);
        card.addEventListener('mouseleave', endPress);
    });
});
</script>

<style>
/* Стили для предупреждения о подписке */
.subscription-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #fff8e1 100%);
    border: 2px solid #ffc107;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.1);
}

.subscription-warning-content {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.subscription-warning-content i {
    font-size: 1.5rem;
    color: #f59e0b;
    flex-shrink: 0;
    margin-top: 0.1rem;
}

.subscription-warning-content strong {
    display: block;
    color: #d97706;
    margin-bottom: 0.25rem;
    font-size: 1rem;
}

.subscription-warning-content p {
    margin: 0;
    color: #92400e;
    font-size: 0.9rem;
    line-height: 1.5;
}

.subscription-warning-content a {
    color: #d97706;
    text-decoration: underline;
    font-weight: 600;
}

.subscription-warning-content a:hover {
    color: #b45309;
}

.minimal-alert-error {
    background: linear-gradient(135deg, #fee 0%, #fdd 100%);
    border-color: #dc3545;
    color: #721c24;
}

.minimal-alert-error i {
    color: #dc3545;
}

@media (max-width: 768px) {
    .subscription-warning {
        padding: 0.875rem;
    }
    
    .subscription-warning-content {
        gap: 0.75rem;
    }
    
    .subscription-warning-content i {
        font-size: 1.25rem;
    }
    
    .subscription-warning-content strong {
        font-size: 0.9rem;
    }
    
    .subscription-warning-content p {
        font-size: 0.85rem;
    }
}
</style>

<!-- Модальное окно выбора роли (только для новых пользователей) -->
@if(!Auth::user()->role_selected)
<div class="modal fade" id="roleSelectionModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-fullscreen m-0">
        <div class="modal-content">
            <div class="modal-header border-0 pb-2">
            </div>
            <div class="px-4">
                <div class="wizard-header text-center">
                    <h2>Вы прораб?</h2>
                    <p>Выберите роль для работы в системе</p>
                </div>
            </div>
            <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                <div class="wizard-container text-center" style="max-width: 500px; width: 100%;">
                    <div class="mb-5">
                        <i class="bi bi-person-badge" style="font-size: 5rem; color: #a70000;"></i>
                    </div>
                    
                    <div class="d-grid gap-3">
                        <form action="{{ route('user.select-role') }}" method="POST">
                            @csrf
                            <input type="hidden" name="is_foreman" value="1">
                            <button type="submit" class="minimal-btn minimal-btn-primary w-100" style="padding: 1rem 2rem; font-size: 1.1rem;">
                                <i class="bi bi-briefcase-fill me-2"></i>
                                Я прораб
                            </button>
                        </form>
                        
                        <form action="{{ route('user.select-role') }}" method="POST">
                            @csrf
                            <input type="hidden" name="is_foreman" value="0">
                            <button type="submit" class="minimal-btn minimal-btn-ghost w-100" style="padding: 1rem 2rem; font-size: 1.1rem;">
                                <i class="bi bi-person-fill me-2"></i>
                                Я не прораб
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleModal = new bootstrap.Modal(document.getElementById('roleSelectionModal'));
    roleModal.show();
});
</script>
@endif

@endsection
