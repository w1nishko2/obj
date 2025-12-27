@extends('layouts.app')

@section('content')
<div class="minimal-container">
    {{-- Предупреждение для бесплатного тарифа --}}
    @if(Auth::user()->isFreePlan())
        <div class="subscription-warning mb-3">
            <div class="subscription-warning-content">
                <i class="bi bi-info-circle"></i>
                <div>
                    <strong>Текущий тариф: @if(Auth::user()->subscription_type === 'free') Бесплатный (1 проект) @elseif(str_contains(Auth::user()->subscription_type, 'starter')) Стартовый (до 3 проектов) @elseif(str_contains(Auth::user()->subscription_type, 'professional')) Профессиональный (до 10 проектов) @elseif(str_contains(Auth::user()->subscription_type, 'corporate')) Корпоративный (безлимит) @else Не активен @endif</strong>
                    @php
                        $remaining = Auth::user()->getRemainingProjectsCount();
                        $plan = \App\Models\Plan::where('slug', Auth::user()->subscription_type)->first();
                        $maxProjects = $plan ? ($plan->features['max_projects'] ?? 0) : 0;
                    @endphp
                    <p>У вас осталось 
                    @if($remaining === null)
                        безлимитное количество проектов
                    @else
                        {{ $remaining }} {{ $remaining === 1 ? 'проект' : ($remaining > 1 && $remaining < 5 ? 'проекта' : 'проектов') }} из {{ $maxProjects }}
                    @endif. 
                    <a href="{{ route('pricing.index') }}">Оформите подписку</a> для снятия ограничений.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="wizard-container">
        <!-- Прогресс визард -->
        <div class="wizard-progress">
            <div class="wizard-step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-label">Основное</div>
            </div>
            <div class="wizard-line"></div>
            <div class="wizard-step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-label">Этапы</div>
            </div>
            <div class="wizard-line"></div>
            <div class="wizard-step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-label">Команда</div>
            </div>
        </div>

        <form action="{{ route('projects.store') }}" method="POST" id="projectForm">
            @csrf

            <!-- Шаг 1: Основная информация -->
            <div class="wizard-content active" data-step="1">
                <div class="wizard-header">
                    <h2>Основная информация</h2>
                    <p>Укажите название и адрес объекта</p>
                </div>

                <div class="form-group-minimal">
                    <label>Название проекта</label>
                    <input type="text" 
                           class="minimal-input @error('name') is-invalid @enderror" 
                           name="name" 
                           value="{{ old('name') }}" 
                           placeholder="Например: Ремонт квартиры на ул. Ленина"
                           maxlength="255"
                           required>
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-minimal">
                    <label>Адрес объекта</label>
                    <input type="text" 
                           class="minimal-input @error('address') is-invalid @enderror" 
                           name="address" 
                           value="{{ old('address') }}" 
                           placeholder="ул. Ленина, д. 10, кв. 25"
                           maxlength="255"
                           required>
                    @error('address')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-minimal">
                    <label>Тип работ</label>
                    <select class="minimal-input" name="template_id" id="templateSelect">
                        <option value="">Без шаблона (добавить этапы вручную)</option>
                        @if(isset($templates) && $templates->count() > 0)
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" {{ old('template_id') == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }}
                                    @if($template->description)
                                        - {{ Str::limit($template->description, 50) }}
                                    @endif
                                    ({{ $template->stages->count() }} этапов)
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i>
                        При выборе типа работ автоматически создадутся этапы и задачи с ценами и временем.
                        <a href="{{ route('prices.index') }}" target="_blank">Управление шаблонами</a>
                    </small>
                </div>

                <div class="form-group-minimal">
                    <label>Наценка на весь проект (%)</label>
                    <input type="number" 
                           class="minimal-input @error('markup_percent') is-invalid @enderror" 
                           name="markup_percent" 
                           value="{{ old('markup_percent', 0) }}" 
                           placeholder="0"
                           step="0.01"
                           min="0"
                           max="999.99">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i>
                        Общая наценка будет применена ко всем задачам проекта
                    </small>
                    @error('markup_percent')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Шаг 2: Этапы работ -->
            <div class="wizard-content" data-step="2">
                <div class="wizard-header">
                    <h2>Этапы работ</h2>
                    <p>Добавьте этапы, можно пропустить</p>
                </div>

                <div id="stages-container"></div>

                <button type="button" class="minimal-btn minimal-btn-ghost minimal-btn-add" onclick="addStage()">
                    <i class="bi bi-plus-lg"></i>
                    Добавить этап
                </button>
            </div>

            <!-- Шаг 3: Команда -->
            <div class="wizard-content" data-step="3">
                <div class="wizard-header">
                    <h2>Команда проекта</h2>
                    <p>Добавьте участников, можно пропустить</p>
                </div>

                <div id="participants-container"></div>

                <button type="button" class="minimal-btn minimal-btn-ghost minimal-btn-add" onclick="addParticipant()">
                    <i class="bi bi-plus-lg"></i>
                    Добавить участника
                </button>
            </div>

            <!-- Навигация визарда -->
            <div class="wizard-actions">
                <!-- Кнопка "Создать проект" (только на шаге 3) -->
                <button type="submit" class="minimal-btn minimal-btn-primary" id="submitBtn" style="display: none; width: 100%;">
                    <i class="bi bi-check-lg"></i>
                    Создать проект
                </button>
                
                <!-- Кнопка "Назад" для шага 3 -->
                <button type="button" class="minimal-btn minimal-btn-ghost" id="prevBtnStep3" onclick="changeStep(-1)" style="display: none; width: 100%;">
                    <i class="bi bi-arrow-left"></i>
                    Назад
                </button>
                
                <!-- Кнопки навигации (шаги 1-2) -->
                <div id="navigationButtons" style="display: flex; flex-direction: column; gap: 0.75rem; width: 100%;">
                    <div style="display: flex; justify-content: space-between; gap: 1rem;">
                        <button type="button" class="minimal-btn minimal-btn-ghost" id="prevBtn" onclick="changeStep(-1)" style="display: none;">
                            <i class="bi bi-arrow-left"></i>
                            Назад
                        </button>
                        <button type="button" class="minimal-btn minimal-btn-primary" id="nextBtn" onclick="changeStep(1)">
                            Далее
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                    <a href="{{ route('projects.index') }}" class="minimal-btn minimal-btn-ghost" style="width: 100%;">
                        Отмена
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let stageCount = 0;
let participantCount = 0;
let currentStep = 1;
let templatesLoaded = false;

// Шаблоны работ из базы данных
const dbTemplates = @json($templates ?? []);

// Отслеживание изменения типа работ (теперь это template_id)
document.addEventListener('DOMContentLoaded', function() {
    const templateSelect = document.getElementById('templateSelect');
    
    // Ничего не делаем, больше нет автозаполнения checkbox
});

// Функции управления визардом
function changeStep(direction) {
    const steps = document.querySelectorAll('.wizard-content');
    const stepIndicators = document.querySelectorAll('.wizard-step');
    
    // Скрываем текущий шаг
    steps[currentStep - 1].classList.remove('active');
    stepIndicators[currentStep - 1].classList.remove('active');
    
    // Переходим к следующему шагу
    currentStep += direction;
    
    // Проверяем, нужно ли загрузить шаблоны при переходе на шаг 2
    if (currentStep === 2 && direction === 1 && !templatesLoaded) {
        const templateId = document.getElementById('templateSelect').value;
        
        if (templateId) {
            loadTemplateStages(templateId);
            templatesLoaded = true;
        }
    }
    
    // Показываем новый шаг
    steps[currentStep - 1].classList.add('active');
    stepIndicators[currentStep - 1].classList.add('active');
    
    // Управляем кнопками
    const navigationButtons = document.getElementById('navigationButtons');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const prevBtnStep3 = document.getElementById('prevBtnStep3');
    
    if (currentStep === 3) {
        // На шаге 3 показываем кнопку "Создать проект" и "Назад" ниже
        navigationButtons.style.display = 'none';
        submitBtn.style.display = 'inline-flex';
        prevBtnStep3.style.display = 'inline-flex';
    } else {
        // На шагах 1-2 показываем навигацию
        navigationButtons.style.display = 'flex';
        submitBtn.style.display = 'none';
        prevBtnStep3.style.display = 'none';
        prevBtn.style.display = currentStep === 1 ? 'none' : 'inline-flex';
        nextBtn.style.display = 'inline-flex';
    }
}

function addStage() {
    const container = document.getElementById('stages-container');
    // Получаем текущее количество этапов в контейнере для правильного индекса
    const currentStagesCount = container.querySelectorAll('.minimal-card').length;
    stageCount++;
    
    const stageHtml = `
        <div class="minimal-card" id="stage-${stageCount}" data-stage-index="${currentStagesCount}">
            <div class="minimal-card-header">
                <span>Этап ${stageCount}</span>
                <button type="button" class="minimal-btn-icon" onclick="removeStage(${stageCount})">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="minimal-card-body">
                <div class="form-group-minimal">
                    <label>Название этапа</label>
                    <input type="text" class="minimal-input" name="stages[${currentStagesCount}][name]" 
                           placeholder="Например: Черновая электрика"
                           maxlength="255">
                </div>

                <div class="form-group-minimal">
                    <label>Период выполнения</label>
                    <input type="text" class="minimal-input date-range-picker" 
                           id="dateRange${stageCount}" 
                           placeholder="Выберите даты" readonly>
                    <input type="hidden" name="stages[${currentStagesCount}][start_date]" id="startDate${stageCount}">
                    <input type="hidden" name="stages[${currentStagesCount}][end_date]" id="endDate${stageCount}">
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', stageHtml);
    
    // Инициализация datepicker для нового этапа
    const currentStageCount = stageCount;
    flatpickr(`#dateRange${currentStageCount}`, {
        mode: 'range',
        locale: 'ru',
        dateFormat: 'd.m.Y',
        altInput: false,
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                const year1 = selectedDates[0].getFullYear();
                const month1 = String(selectedDates[0].getMonth() + 1).padStart(2, '0');
                const day1 = String(selectedDates[0].getDate()).padStart(2, '0');
                
                const year2 = selectedDates[1].getFullYear();
                const month2 = String(selectedDates[1].getMonth() + 1).padStart(2, '0');
                const day2 = String(selectedDates[1].getDate()).padStart(2, '0');
                
                document.getElementById(`startDate${currentStageCount}`).value = `${year1}-${month1}-${day1}`;
                document.getElementById(`endDate${currentStageCount}`).value = `${year2}-${month2}-${day2}`;
            }
        }
    });
}

function removeStage(id) {
    const stageElement = document.getElementById(`stage-${id}`);
    if (!stageElement) return;
    
    stageElement.remove();
    
    // Пересчитываем индексы для всех оставшихся этапов
    reindexStages();
}

// Функция для пересчета индексов всех этапов после добавления/удаления
function reindexStages() {
    const container = document.getElementById('stages-container');
    const stages = container.querySelectorAll('.minimal-card');
    
    stages.forEach((stage, index) => {
        // Обновляем атрибут data-stage-index
        stage.dataset.stageIndex = index;
        
        // Обновляем name атрибуты для всех input внутри этапа
        const inputs = stage.querySelectorAll('input[name^="stages["]');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            // Заменяем старый индекс на новый
            const newName = name.replace(/stages\[\d+\]/, `stages[${index}]`);
            input.setAttribute('name', newName);
        });
    });
}

function addParticipant() {
    participantCount++;
    const container = document.getElementById('participants-container');
    const participantHtml = `
        <div class="minimal-card" id="participant-${participantCount}">
            <div class="minimal-card-header">
                <span>Участник ${participantCount}</span>
                <button type="button" class="minimal-btn-icon" onclick="removeParticipant(${participantCount})">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="minimal-card-body">
                <div class="form-group-minimal">
                    <label>Роль</label>
                    <select class="minimal-input" name="participants[${participantCount - 1}][role]">
                        <option value="">Выберите роль</option>
                        <option value="Клиент">Клиент</option>
                        <option value="Исполнитель">Исполнитель</option>
                    </select>
                </div>

                <div class="form-group-minimal">
                    <label>Имя</label>
                    <input type="text" class="minimal-input" name="participants[${participantCount - 1}][name]" 
                           placeholder="Иван Петров"
                           maxlength="255">
                </div>

                <div class="form-group-minimal">
                    <label>Номер телефона</label>
                    <input type="tel" class="minimal-input phone-mask" 
                           id="phone${participantCount}"
                           name="participants[${participantCount - 1}][phone]" 
                           placeholder="+7 (___) ___-__-__">
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', participantHtml);
    
    // Инициализация маски телефона для нового участника
    const phoneInput = document.getElementById(`phone${participantCount}`);
    IMask(phoneInput, {
        mask: '+{7} (000) 000-00-00',
        lazy: false,
        placeholderChar: '_'
    });
}

function removeParticipant(id) {
    document.getElementById(`participant-${id}`).remove();
}

// Загрузка шаблонных этапов
function loadTemplateStages(templateId) {
    const template = dbTemplates.find(t => t.id == templateId);
    if (!template || !template.stages) return;
    
    console.log('Загружен шаблон:', template);
    console.log('Этапы шаблона:', template.stages);
    
    const container = document.getElementById('stages-container');
    container.innerHTML = ''; // Очищаем контейнер
    stageCount = 0;
    
    // Вычисляем начальную дату (сегодня + 3 дня)
    let currentDate = new Date();
    currentDate.setDate(currentDate.getDate() + 3);
    
    template.stages.forEach((stage, index) => {
        console.log(`Этап ${index}:`, stage);
        console.log(`Задачи этапа ${index}:`, stage.tasks);
        
        stageCount++;
        
        // Вычисляем даты этапа
        const startDate = new Date(currentDate);
        const endDate = new Date(currentDate);
        endDate.setDate(endDate.getDate() + (stage.duration_days || 1) - 1);
        
        // Форматируем даты для отображения
        const startDateStr = formatDate(startDate);
        const endDateStr = formatDate(endDate);
        const dateRangeStr = `${startDateStr} - ${endDateStr}`;
        
        // Форматируем даты для input
        const startDateValue = formatDateForInput(startDate);
        const endDateValue = formatDateForInput(endDate);
        
        // Создаём HTML для задач
        let tasksHtml = '';
        if (stage.tasks && stage.tasks.length > 0) {
            tasksHtml = `
                <div class="template-tasks-info">
                    <div class="template-tasks-header">
                        <i class="bi bi-list-check"></i>
                        <span>Типовые задачи (${stage.tasks.length})</span>
                    </div>
                    <input type="hidden" name="stages[${index}][template_tasks]" value='${JSON.stringify(stage.tasks)}'>
                </div>
            `;
        }
        
        const stageHtml = `
            <div class="minimal-card template-stage" id="stage-${stageCount}" data-stage-index="${index}">
                <div class="minimal-card-header">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-magic" style="color: #6366f1;"></i>
                        <span>Этап ${stageCount}</span>
                    </div>
                    <button type="button" class="minimal-btn-icon" onclick="removeStage(${stageCount})">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="minimal-card-body">
                    <div class="form-group-minimal">
                        <label>Название этапа</label>
                        <input type="text" class="minimal-input" name="stages[${index}][name]" 
                               value="${stage.name || ''}"
                               placeholder="Например: Черновая электрика"
                               maxlength="255">
                    </div>

                    <div class="form-group-minimal">
                        <label>Период выполнения</label>
                        <input type="text" class="minimal-input date-range-picker" 
                               id="dateRange${stageCount}" 
                               value="${dateRangeStr}"
                               placeholder="Выберите даты" readonly>
                        <input type="hidden" name="stages[${index}][start_date]" id="startDate${stageCount}" value="${startDateValue}">
                        <input type="hidden" name="stages[${index}][end_date]" id="endDate${stageCount}" value="${endDateValue}">
                    </div>
                    ${tasksHtml}
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', stageHtml);
        
        // Инициализация datepicker для этапа
        const currentStageCount = stageCount;
        flatpickr(`#dateRange${currentStageCount}`, {
            mode: 'range',
            locale: 'ru',
            dateFormat: 'd.m.Y',
            defaultDate: [startDate, endDate],
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    document.getElementById(`startDate${currentStageCount}`).value = formatDateForInput(selectedDates[0]);
                    document.getElementById(`endDate${currentStageCount}`).value = formatDateForInput(selectedDates[1]);
                }
            }
        });
        
        // Следующий этап начинается на следующий день после окончания текущего
        currentDate = new Date(endDate);
        currentDate.setDate(currentDate.getDate() + 1);
    });
}

// Вспомогательные функции для форматирования дат
function formatDate(date) {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}.${month}.${year}`;
}

function formatDateForInput(date) {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${year}-${month}-${day}`;
}
</script>

<style>
/* Стили для предупреждения о подписке */
.subscription-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #fff8e1 100%);
    border: 2px solid #ffc107;
    border-radius: 12px;
    padding: 1rem;
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
}

/* Стили для чекбокса автозаполнения */
.custom-checkbox {
    position: relative;
    padding: 1rem;
    padding-right: 4rem;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 2px solid #3b82f6;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.custom-checkbox:hover {
    background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
    border-color: #2563eb;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.custom-checkbox input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

/* Визуальный индикатор чекбокса */
.checkbox-indicator {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 48px;
    height: 48px;
    background: #ef4444;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

.checkbox-indicator::before {
    content: '\2715';
    color: white;
    font-size: 24px;
    font-weight: bold;
    transition: all 0.3s ease;
}

/* Когда чекбокс выбран */
.custom-checkbox input[type="checkbox"]:checked ~ .checkbox-indicator {
    background: #10b981;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.custom-checkbox input[type="checkbox"]:checked ~ .checkbox-indicator::before {
    content: '\2713';
}

.custom-checkbox label {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    cursor: pointer;
    margin: 0;
    color: #1e40af;
}

.custom-checkbox label > strong {
    display: flex;
    align-items: center;
    font-weight: 600;
    font-size: 1rem;
}

.custom-checkbox label i {
    margin-right: 0.5rem;
    font-size: 1.1rem;
}

.custom-checkbox .help-text {
    display: block;
    font-weight: normal;
    font-size: 0.875rem;
    color: #475569;
    line-height: 1.6;
    margin-top: 0.25rem;
    padding-left: 1.6rem;
}

.custom-checkbox .help-text strong {
    color: #1e40af;
}

.custom-checkbox input[type="checkbox"]:checked ~ label {
    color: #1e3a8a;
}

.custom-checkbox input[type="checkbox"]:checked ~ label i {
    color: #6366f1;
}

/* Стили для карточек с шаблонами */
.template-stage {
    border-left: 4px solid #6366f1;
    background: linear-gradient(135deg, #fafafa 0%, #ffffff 100%);
}

.template-stage .minimal-card-header i {
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.7;
        transform: scale(1.1);
    }
}

.template-tasks-info {
    margin-top: 1rem;
    padding: 0.875rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid #cbd5e1;
    border-radius: 8px;
}

.template-tasks-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #475569;
    font-size: 0.875rem;
    font-weight: 600;
}

.template-tasks-header i {
    color: #6366f1;
    font-size: 1rem;
}

@media (max-width: 768px) {
    .custom-checkbox {
        padding: 0.875rem;
        padding-right: 3.5rem;
    }
    
    .checkbox-indicator {
        width: 40px;
        height: 40px;
        top: 0.875rem;
        right: 0.875rem;
    }
    
    .checkbox-indicator::before {
        font-size: 20px;
    }
    
    .custom-checkbox label {
        font-size: 0.9rem;
    }
    
    .custom-checkbox .help-text {
        font-size: 0.8rem;
    }
}
</style>
@endsection
