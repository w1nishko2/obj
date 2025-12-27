

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row mb-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Главная</a></li>
                    <li class="breadcrumb-item active">Прайсы</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-2 p-md-3">
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-2 gap-2">
                        <div style="width: 100%;">
                            <h4 class="mb-1 d-flex align-items-center justify-content-between gap-2" >
                                Управление прайсами  <button type="button" class="btn btn-primary" onclick="showAddTypeModal()">
                            <i class="bi bi-plus-circle"></i>
                            <span class=" d-md-inline"> Добавить </span>
                        </button>
                            </h4>
                            <p class="mb-0 text-muted small">
                                Шаблоны работ для автоматического создания проектов
                            </p>
                        </div>
                       
                    </div>

                    <!-- Список типов работ -->
                    <?php $__empty_1 = true; $__currentLoopData = $templateTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="card price-card mb-2" data-type-id="<?php echo e($type->id); ?>">
                            <div class="card-header bg-light cursor-pointer p-2" onclick="toggleType(<?php echo e($type->id); ?>)">
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0">
                                            <i class="bi bi-chevron-right toggle-icon" id="icon-type-<?php echo e($type->id); ?>"></i>
                                            <?php echo e($type->name); ?>

                                        </h5>
                                        <?php if($type->description): ?>
                                            <small class="text-muted"><?php echo e($type->description); ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="btn-group" onclick="event.stopPropagation()">
                                        <button class="btn btn-sm btn-outline-primary" onclick="editType(<?php echo e($type->id); ?>)" title="Редактировать">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="showAddStageModal(<?php echo e($type->id); ?>)" title="Добавить этап">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteType(<?php echo e($type->id); ?>)" title="Удалить">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-1 p-md-2" id="type-content-<?php echo e($type->id); ?>" style="display: none;">
                                <?php $__empty_2 = true; $__currentLoopData = $type->stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                    <div class="card stage-card mb-1" data-stage-id="<?php echo e($stage->id); ?>">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-start justify-content-between gap-2 mb-1 cursor-pointer" onclick="toggleStage(<?php echo e($stage->id); ?>)">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">
                                                        <i class="bi bi-chevron-right toggle-icon" id="icon-stage-<?php echo e($stage->id); ?>"></i>
                                                        <?php echo e($stage->name); ?>

                                                        <span class="badge bg-info ms-2"><?php echo e($stage->duration_days); ?> дн.</span>
                                                    </h6>
                                                    <?php if($stage->description): ?>
                                                        <small class="text-muted"><?php echo e($stage->description); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="btn-group" onclick="event.stopPropagation()">
                                                    <button class="btn btn-sm btn-outline-primary" onclick="editStage(<?php echo e($stage->id); ?>)" title="Редактировать">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-success" onclick="showAddTaskModal(<?php echo e($stage->id); ?>)" title="Добавить задачу">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteStage(<?php echo e($stage->id); ?>)" title="Удалить">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div id="stage-content-<?php echo e($stage->id); ?>" style="display: none;">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-hover mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Название задачи</th>
                                                                <th class="text-end" width="120">Цена (₽)</th>
                                                                <th class="text-center" width="100">Действия</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tasks-<?php echo e($stage->id); ?>">
                                                            <?php $__empty_3 = true; $__currentLoopData = $stage->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
                                                                <tr data-task-id="<?php echo e($task->id); ?>" data-task-data='<?php echo json_encode($task, 15, 512) ?>'>
                                                                    <td class="editable-cell" 
                                                                        contenteditable="true" 
                                                                        data-field="name"
                                                                        data-task-id="<?php echo e($task->id); ?>"
                                                                        onblur="saveTaskField(this)"
                                                                        onkeydown="handleEnter(event, this)"><?php echo e($task->name); ?></td>
                                                                    <td class="editable-cell text-end" 
                                                                        contenteditable="true" 
                                                                        data-field="price"
                                                                        data-task-id="<?php echo e($task->id); ?>"
                                                                        data-type="number"
                                                                        onblur="saveTaskField(this)"
                                                                        onkeydown="handleEnter(event, this)"><?php echo e($task->price); ?></td>
                                                                    <td class="text-center">
                                                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteTask(<?php echo e($task->id); ?>)" title="Удалить">
                                                                            <i class="bi bi-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_3): ?>
                                                                <tr>
                                                                    <td colspan="3" class="text-center text-muted py-3">
                                                                        <i class="bi bi-inbox"></i> Задачи не добавлены
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-info-circle"></i> Этапы не добавлены. Нажмите <i class="bi bi-plus"></i> для добавления.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            У вас пока нет шаблонов работ. Нажмите "Добавить тип работы" для начала.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для добавления/редактирования типа работы -->
<div class="modal fade" id="typeModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen m-0">
        <div class="modal-content">
            <form id="typeForm" class="d-flex flex-column h-100">
                <div class="modal-header border-0 pb-2">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="px-4">
                    <div class="wizard-header text-center">
                        <h2 id="typeModalTitle">Добавить тип работы</h2>
                        <p>Создайте шаблон для типа работ</p>
                    </div>
                </div>
                <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                    <div class="wizard-container" style="max-width: 600px; width: 100%;">
                        <input type="hidden" id="typeId" value="">
                        <div class="form-group-minimal">
                            <label>Название типа работы <span class="text-danger">*</span></label>
                            <input type="text" class="minimal-input" id="typeName" required placeholder="Например: Капитальный ремонт">
                        </div>
                        <div class="form-group-minimal">
                            <label>Описание</label>
                            <textarea class="minimal-input" id="typeDescription" rows="3" placeholder="Краткое описание (необязательно)"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="minimal-btn minimal-btn-primary" onclick="saveType()">
                        <i class="bi bi-check-lg"></i> Сохранить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно для добавления/редактирования этапа -->
<div class="modal fade" id="stageModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen m-0">
        <div class="modal-content">
            <form id="stageForm" class="d-flex flex-column h-100">
                <div class="modal-header border-0 pb-2">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="px-4">
                    <div class="wizard-header text-center">
                        <h2 id="stageModalTitle">Добавить этап</h2>
                        <p>Создайте этап работ с длительностью</p>
                    </div>
                </div>
                <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                    <div class="wizard-container" style="max-width: 600px; width: 100%;">
                        <input type="hidden" id="stageId" value="">
                        <input type="hidden" id="stageTypeId" value="">
                        <div class="form-group-minimal">
                            <label>Название этапа <span class="text-danger">*</span></label>
                            <input type="text" class="minimal-input" id="stageName" required placeholder="Например: Демонтажные работы">
                        </div>
                        <div class="form-group-minimal">
                            <label>Описание</label>
                            <textarea class="minimal-input" id="stageDescription" rows="2" placeholder="Краткое описание (необязательно)"></textarea>
                        </div>
                        <div class="form-group-minimal">
                            <label>Длительность (дни) <span class="text-danger">*</span></label>
                            <input type="number" class="minimal-input" id="stageDuration" min="1" value="1" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="minimal-btn minimal-btn-primary" onclick="saveStage()">
                        <i class="bi bi-check-lg"></i> Сохранить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно для добавления/редактирования задачи -->
<div class="modal fade" id="taskModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen m-0">
        <div class="modal-content">
            <form id="taskForm" class="d-flex flex-column h-100">
                <div class="modal-header border-0 pb-2">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="px-4">
                    <div class="wizard-header text-center">
                        <h2 id="taskModalTitle">Добавить задачу</h2>
                        <p>Создайте задачу с ценой и длительностью</p>
                    </div>
                </div>
                <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                    <div class="wizard-container" style="max-width: 600px; width: 100%;">
                        <input type="hidden" id="taskId" value="">
                        <input type="hidden" id="taskStageId" value="">
                        <div class="form-group-minimal">
                            <label>Название задачи <span class="text-danger">*</span></label>
                            <input type="text" class="minimal-input" id="taskName" required placeholder="Например: Демонтаж старых обоев">
                        </div>
                        <div class="form-group-minimal">
                            <label>Описание</label>
                            <textarea class="minimal-input" id="taskDescription" rows="2" placeholder="Краткое описание (необязательно)"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group-minimal">
                                    <label>Цена (₽) <span class="text-danger">*</span></label>
                                    <input type="number" class="minimal-input" id="taskPrice" min="0" step="0.01" value="0" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="minimal-btn minimal-btn-primary" onclick="saveTask()">
                        <i class="bi bi-check-lg"></i> Сохранить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.cursor-pointer {
    cursor: pointer;
}

.toggle-icon {
    transition: transform 0.3s ease;
    display: inline-block;
}

.toggle-icon.rotated {
    transform: rotate(90deg);
}

.price-card {
    border: 1px solid #e8e8e8 !important;
}

.price-card .card-header {
    border-bottom: 1px solid #f0f0f0;
}

.stage-card {
    border: none;
    border-left: 2px solid #e3e3e3 !important;
    background-color: #fafafa;
}

.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.4rem 0.5rem;
    border-color: #f0f0f0;
}

.table td {
    vertical-align: middle;
    padding: 0.4rem 0.5rem;
    border-color: #f5f5f5;
}

.editable-cell {
    cursor: text;
    transition: background-color 0.2s;
    position: relative;
}

.editable-cell:hover {
    background-color: #f8f9fa;
}

.editable-cell:focus {
    background-color: #fff3cd;
    outline: 2px solid #0d6efd;
    outline-offset: -2px;
}

.editable-cell.saving {
    background-color: #d1e7dd;
    pointer-events: none;
}

.editable-cell.error {
    background-color: #f8d7da;
    animation: shake 0.3s;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

@media (max-width: 768px) {
    .btn-group {
        display: flex;
        flex-wrap: nowrap;
    }
    
    .btn-group .btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.8rem;
    }
    
    .table {
        font-size: 0.85rem;
    }
    
    .table th,
    .table td {
        padding: 0.3rem 0.4rem;
    }
    
    .card-body {
        padding: 0.5rem !important;
    }
    
    .card-header {
        padding: 0.5rem !important;
    }
}
</style>

<script>
let typeModal, stageModal, taskModal;
let templates = <?php echo json_encode($templateTypes, 15, 512) ?>;

document.addEventListener('DOMContentLoaded', function() {
    typeModal = new bootstrap.Modal(document.getElementById('typeModal'));
    stageModal = new bootstrap.Modal(document.getElementById('stageModal'));
    taskModal = new bootstrap.Modal(document.getElementById('taskModal'));
});

// Переключение видимости типа работы
function toggleType(typeId) {
    const content = document.getElementById(`type-content-${typeId}`);
    const icon = document.getElementById(`icon-type-${typeId}`);
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.classList.add('rotated');
    } else {
        content.style.display = 'none';
        icon.classList.remove('rotated');
    }
}

// Переключение видимости этапа
function toggleStage(stageId) {
    const content = document.getElementById(`stage-content-${stageId}`);
    const icon = document.getElementById(`icon-stage-${stageId}`);
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.classList.add('rotated');
    } else {
        content.style.display = 'none';
        icon.classList.remove('rotated');
    }
}

// Модальные окна для типа работы
function showAddTypeModal() {
    document.getElementById('typeModalTitle').textContent = 'Добавить тип работы';
    document.getElementById('typeId').value = '';
    document.getElementById('typeName').value = '';
    document.getElementById('typeDescription').value = '';
    typeModal.show();
}

function editType(typeId) {
    const type = templates.find(t => t.id === typeId);
    if (!type) return;
    
    document.getElementById('typeModalTitle').textContent = 'Редактировать тип работы';
    document.getElementById('typeId').value = type.id;
    document.getElementById('typeName').value = type.name;
    document.getElementById('typeDescription').value = type.description || '';
    typeModal.show();
}

function saveType() {
    const typeId = document.getElementById('typeId').value;
    const name = document.getElementById('typeName').value.trim();
    const description = document.getElementById('typeDescription').value.trim();
    
    if (!name) {
        alert('Введите название типа работы');
        return;
    }
    
    const url = typeId ? `/prices/types/${typeId}` : '/prices/types';
    const method = typeId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ name, description })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            typeModal.hide();
            
            if (typeId) {
                // Редактирование - обновляем существующий
                updateTypeInDOM(data.type);
            } else {
                // Создание - добавляем новый
                addTypeToDOM(data.type);
            }
        } else {
            alert(data.message || 'Ошибка при сохранении');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ошибка при сохранении');
    });
}

function addTypeToDOM(type) {
    // Убираем alert "нет шаблонов" если он есть
    const emptyAlert = document.querySelector('.alert-info');
    if (emptyAlert && emptyAlert.textContent.includes('У вас пока нет шаблонов')) {
        emptyAlert.remove();
    }
    
    const html = `
        <div class="card price-card mb-2" data-type-id="${type.id}">
            <div class="card-header bg-light cursor-pointer p-2" onclick="toggleType(${type.id})">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <div class="flex-grow-1">
                        <h5 class="mb-0">
                            <i class="bi bi-chevron-right toggle-icon" id="icon-type-${type.id}"></i>
                            ${type.name}
                        </h5>
                        ${type.description ? `<small class="text-muted">${type.description}</small>` : ''}
                    </div>
                    <div class="btn-group" onclick="event.stopPropagation()">
                        <button class="btn btn-sm btn-outline-primary" onclick="editType(${type.id})" title="Редактировать">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="showAddStageModal(${type.id})" title="Добавить этап">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteType(${type.id})" title="Удалить">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-1 p-md-2" id="type-content-${type.id}" style="display: none;">
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i> Этапы не добавлены. Нажмите <i class="bi bi-plus"></i> для добавления.
                </div>
            </div>
        </div>
    `;
    
    const cardBody = document.querySelector('.card-body.p-2.p-md-3');
    cardBody.insertAdjacentHTML('beforeend', html);
    
    // Добавляем в templates
    templates.push(type);
}

function updateTypeInDOM(type) {
    const card = document.querySelector(`[data-type-id="${type.id}"]`);
    if (!card) return;
    
    const header = card.querySelector('.card-header h5');
    const descriptionEl = card.querySelector('.card-header small');
    
    // Обновляем название
    const iconHtml = header.querySelector('i').outerHTML;
    header.innerHTML = `${iconHtml} ${type.name}`;
    
    // Обновляем описание
    if (type.description) {
        if (descriptionEl) {
            descriptionEl.textContent = type.description;
        } else {
            header.insertAdjacentHTML('afterend', `<small class="text-muted">${type.description}</small>`);
        }
    } else if (descriptionEl) {
        descriptionEl.remove();
    }
    
    // Обновляем в templates
    const index = templates.findIndex(t => t.id === type.id);
    if (index !== -1) {
        templates[index].name = type.name;
        templates[index].description = type.description;
    }
}

function deleteType(typeId) {
    if (!confirm('Вы уверены что хотите удалить этот тип работы? Будут удалены все связанные этапы и задачи.')) {
        return;
    }
    
    fetch(`/prices/types/${typeId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Удаляем из DOM
            const card = document.querySelector(`[data-type-id="${typeId}"]`);
            if (card) {
                card.remove();
            }
            
            // Удаляем из templates
            const index = templates.findIndex(t => t.id === typeId);
            if (index !== -1) {
                templates.splice(index, 1);
            }
            
            // Если больше нет типов, показываем сообщение
            if (templates.length === 0) {
                const cardBody = document.querySelector('.card-body.p-2.p-md-3');
                cardBody.innerHTML = `
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        У вас пока нет шаблонов работ. Нажмите "Добавить тип работы" для начала.
                    </div>
                `;
            }
        } else {
            alert(data.message || 'Ошибка при удалении');
        }
    });
}

// Модальные окна для этапа
function showAddStageModal(typeId) {
    document.getElementById('stageModalTitle').textContent = 'Добавить этап';
    document.getElementById('stageId').value = '';
    document.getElementById('stageTypeId').value = typeId;
    document.getElementById('stageName').value = '';
    document.getElementById('stageDescription').value = '';
    document.getElementById('stageDuration').value = '1';
    stageModal.show();
}

function editStage(stageId) {
    const type = templates.find(t => t.stages.some(s => s.id === stageId));
    if (!type) return;
    
    const stage = type.stages.find(s => s.id === stageId);
    if (!stage) return;
    
    document.getElementById('stageModalTitle').textContent = 'Редактировать этап';
    document.getElementById('stageId').value = stage.id;
    document.getElementById('stageTypeId').value = type.id;
    document.getElementById('stageName').value = stage.name;
    document.getElementById('stageDescription').value = stage.description || '';
    document.getElementById('stageDuration').value = stage.duration_days;
    stageModal.show();
}

function saveStage() {
    const stageId = document.getElementById('stageId').value;
    const typeId = document.getElementById('stageTypeId').value;
    const name = document.getElementById('stageName').value.trim();
    const description = document.getElementById('stageDescription').value.trim();
    const duration_days = parseInt(document.getElementById('stageDuration').value);
    
    if (!name || !duration_days) {
        alert('Заполните все обязательные поля');
        return;
    }
    
    const url = stageId ? `/prices/stages/${stageId}` : '/prices/stages';
    const method = stageId ? 'PUT' : 'POST';
    
    const data = { name, description, duration_days };
    if (!stageId) {
        data.work_template_type_id = typeId;
    }
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            stageModal.hide();
            
            if (stageId) {
                // Редактирование - обновляем существующий
                updateStageInDOM(data.stage, typeId);
            } else {
                // Создание - добавляем новый
                addStageToDOM(data.stage, typeId);
            }
        } else {
            alert(data.message || 'Ошибка при сохранении');
        }
    });
}

function addStageToDOM(stage, typeId) {
    const typeContent = document.getElementById(`type-content-${typeId}`);
    if (!typeContent) return;
    
    // Убираем alert "этапы не добавлены" если он есть
    const emptyAlert = typeContent.querySelector('.alert-info');
    if (emptyAlert) {
        emptyAlert.remove();
    }
    
    const html = `
        <div class="card stage-card mb-1" data-stage-id="${stage.id}">
            <div class="card-body p-2">
                <div class="d-flex align-items-start justify-content-between gap-2 mb-1 cursor-pointer" onclick="toggleStage(${stage.id})">
                    <div class="flex-grow-1">
                        <h6 class="mb-0">
                            <i class="bi bi-chevron-right toggle-icon" id="icon-stage-${stage.id}"></i>
                            ${stage.name}
                            <span class="badge bg-info ms-2">${stage.duration_days} дн.</span>
                        </h6>
                        ${stage.description ? `<small class="text-muted">${stage.description}</small>` : ''}
                    </div>
                    <div class="btn-group" onclick="event.stopPropagation()">
                        <button class="btn btn-sm btn-outline-primary" onclick="editStage(${stage.id})" title="Редактировать">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="showAddTaskModal(${stage.id})" title="Добавить задачу">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteStage(${stage.id})" title="Удалить">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <div id="stage-content-${stage.id}" style="display: none;">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Название задачи</th>
                                    <th class="text-center" width="80">Дни</th>
                                    <th class="text-end" width="120">Цена (₽)</th>
                                    <th class="text-center" width="100">Действия</th>
                                </tr>
                            </thead>
                            <tbody id="tasks-${stage.id}">
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        <i class="bi bi-inbox"></i> Задачи не добавлены
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    typeContent.insertAdjacentHTML('beforeend', html);
    
    // Добавляем в templates
    const type = templates.find(t => t.id == typeId);
    if (type) {
        if (!type.stages) type.stages = [];
        type.stages.push(stage);
    }
}

function updateStageInDOM(stage, typeId) {
    const card = document.querySelector(`[data-stage-id="${stage.id}"]`);
    if (!card) return;
    
    const header = card.querySelector('.card-body > div > div h6');
    const descriptionEl = card.querySelector('.card-body > div > div small');
    
    // Обновляем название и длительность
    const iconHtml = header.querySelector('i').outerHTML;
    header.innerHTML = `${iconHtml} ${stage.name} <span class="badge bg-info ms-2">${stage.duration_days} дн.</span>`;
    
    // Обновляем описание
    const parentDiv = header.parentElement;
    if (stage.description) {
        if (descriptionEl) {
            descriptionEl.textContent = stage.description;
        } else {
            parentDiv.insertAdjacentHTML('beforeend', `<small class="text-muted">${stage.description}</small>`);
        }
    } else if (descriptionEl) {
        descriptionEl.remove();
    }
    
    // Обновляем в templates
    const type = templates.find(t => t.id == typeId);
    if (type) {
        const index = type.stages.findIndex(s => s.id === stage.id);
        if (index !== -1) {
            type.stages[index] = stage;
        }
    }
}

function deleteStage(stageId) {
    if (!confirm('Вы уверены что хотите удалить этот этап? Будут удалены все связанные задачи.')) {
        return;
    }
    
    fetch(`/prices/stages/${stageId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Удаляем из DOM
            const card = document.querySelector(`[data-stage-id="${stageId}"]`);
            if (card) {
                const typeContent = card.closest('[id^="type-content-"]');
                card.remove();
                
                // Если больше нет этапов, показываем сообщение
                const remainingStages = typeContent.querySelectorAll('.stage-card');
                if (remainingStages.length === 0) {
                    typeContent.innerHTML = `
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i> Этапы не добавлены. Нажмите <i class="bi bi-plus"></i> для добавления.
                        </div>
                    `;
                }
            }
            
            // Удаляем из templates
            templates.forEach(type => {
                const index = type.stages.findIndex(s => s.id === stageId);
                if (index !== -1) {
                    type.stages.splice(index, 1);
                }
            });
        } else {
            alert(data.message || 'Ошибка при удалении');
        }
    });
}

// Inline редактирование задачи
function handleEnter(event, cell) {
    if (event.key === 'Enter') {
        event.preventDefault();
        cell.blur();
    }
}

// Модальные окна для задачи
function showAddTaskModal(stageId) {
    document.getElementById('taskModalTitle').textContent = 'Добавить задачу';
    document.getElementById('taskId').value = '';
    document.getElementById('taskStageId').value = stageId;
    document.getElementById('taskName').value = '';
    document.getElementById('taskDescription').value = '';
    document.getElementById('taskPrice').value = '0';
    taskModal.show();
}

function saveTask() {
    const taskId = document.getElementById('taskId').value;
    const stageId = document.getElementById('taskStageId').value;
    const name = document.getElementById('taskName').value.trim();
    const description = document.getElementById('taskDescription').value.trim();
    const price = parseFloat(document.getElementById('taskPrice').value);
    
    if (!name || price < 0) {
        alert('Заполните все обязательные поля');
        return;
    }
    
    const url = taskId ? `/prices/tasks/${taskId}` : '/prices/tasks';
    const method = taskId ? 'PUT' : 'POST';
    
    const data = { name, description, price };
    if (!taskId) {
        data.work_template_stage_id = stageId;
    }
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            taskModal.hide();
            addTaskToDOM(data.task, stageId);
        } else {
            alert(data.message || 'Ошибка при сохранении');
        }
    });
}

function addTaskToDOM(task, stageId) {
    const tbody = document.getElementById(`tasks-${stageId}`);
    if (!tbody) return;
    
    // Убираем сообщение "задачи не добавлены" если оно есть
    const emptyRow = tbody.querySelector('td[colspan="3"]');
    if (emptyRow) {
        emptyRow.parentElement.remove();
    }
    
    const html = `
        <tr data-task-id="${task.id}" data-task-data='${JSON.stringify(task)}'>
            <td class="editable-cell" 
                contenteditable="true" 
                data-field="name"
                data-task-id="${task.id}"
                onblur="saveTaskField(this)"
                onkeydown="handleEnter(event, this)">${task.name}</td>
            <td class="editable-cell text-end" 
                contenteditable="true" 
                data-field="price"
                data-task-id="${task.id}"
                data-type="number"
                onblur="saveTaskField(this)"
                onkeydown="handleEnter(event, this)">${task.price}</td>
            <td class="text-center">
                <button class="btn btn-sm btn-outline-danger" onclick="deleteTask(${task.id})" title="Удалить">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    `;
    
    tbody.insertAdjacentHTML('beforeend', html);
    
    // Добавляем в templates
    templates.forEach(type => {
        const stage = type.stages?.find(s => s.id == stageId);
        if (stage) {
            if (!stage.tasks) stage.tasks = [];
            stage.tasks.push(task);
        }
    });
}

function saveTaskField(cell) {
    const taskId = cell.dataset.taskId;
    const field = cell.dataset.field;
    const type = cell.dataset.type;
    let value = cell.textContent.trim();
    
    // Валидация
    if (!value) {
        cell.classList.add('error');
        setTimeout(() => cell.classList.remove('error'), 500);
        // Восстанавливаем старое значение
        const row = cell.closest('tr');
        const oldData = JSON.parse(row.dataset.taskData);
        cell.textContent = field === 'price' ? oldData.price : oldData[field];
        return;
    }
    
    if (type === 'number') {
        value = parseFloat(value.replace(/\s/g, ''));
        if (isNaN(value) || value < 0) {
            cell.classList.add('error');
            setTimeout(() => cell.classList.remove('error'), 500);
            const row = cell.closest('tr');
            const oldData = JSON.parse(row.dataset.taskData);
            cell.textContent = field === 'price' ? oldData.price : oldData[field];
            return;
        }
    }
    
    // Получаем текущие данные задачи
    const row = cell.closest('tr');
    const currentData = JSON.parse(row.dataset.taskData);
    
    // Проверяем, изменилось ли значение
    const oldValue = field === 'price' ? parseFloat(currentData.price) : currentData[field];
    if (type === 'number' && parseFloat(value) === parseFloat(oldValue)) {
        return;
    }
    if (type !== 'number' && value === oldValue) {
        return;
    }
    
    // Показываем индикатор сохранения
    cell.classList.add('saving');
    
    // Формируем данные для обновления
    const updateData = {
        name: currentData.name,
        description: currentData.description || '',
        price: parseFloat(currentData.price)
    };
    
    // Обновляем изменённое поле
    if (field === 'price') {
        updateData.price = parseFloat(value);
    } else {
        updateData[field] = value;
    }
    
    // Отправляем на сервер
    fetch(`/prices/tasks/${taskId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(updateData)
    })
    .then(response => response.json())
    .then(data => {
        cell.classList.remove('saving');
        if (data.success) {
            // Обновляем данные в атрибуте
            row.dataset.taskData = JSON.stringify(data.task);
            
            // Форматируем отображение для цены
            if (field === 'price') {
                cell.textContent = parseFloat(data.task.price).toFixed(2);
            }
            
            // Показываем успех
            cell.style.backgroundColor = '#d1e7dd';
            setTimeout(() => {
                cell.style.backgroundColor = '';
            }, 500);
        } else {
            cell.classList.add('error');
            setTimeout(() => cell.classList.remove('error'), 500);
            // Восстанавливаем старое значение
            cell.textContent = field === 'price' ? currentData.price : currentData[field];
        }
    })
    .catch(error => {
        console.error('Error:', error);
        cell.classList.remove('saving');
        cell.classList.add('error');
        setTimeout(() => cell.classList.remove('error'), 500);
        // Восстанавливаем старое значение
        cell.textContent = field === 'price' ? currentData.price : currentData[field];
    });
}

function deleteTask(taskId) {
    if (!confirm('Вы уверены что хотите удалить эту задачу?')) {
        return;
    }
    
    fetch(`/prices/tasks/${taskId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Удаляем из DOM
            const row = document.querySelector(`tr[data-task-id="${taskId}"]`);
            if (row) {
                const tbody = row.parentElement;
                row.remove();
                
                // Если больше нет задач, показываем сообщение
                const remainingTasks = tbody.querySelectorAll('tr[data-task-id]');
                if (remainingTasks.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                <i class="bi bi-inbox"></i> Задачи не добавлены
                            </td>
                        </tr>
                    `;
                }
            }
            
            // Удаляем из templates
            templates.forEach(type => {
                type.stages?.forEach(stage => {
                    const index = stage.tasks?.findIndex(t => t.id === taskId);
                    if (index !== -1) {
                        stage.tasks.splice(index, 1);
                    }
                });
            });
        } else {
            alert(data.message || 'Ошибка при удалении');
        }
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views/prices/index.blade.php ENDPATH**/ ?>