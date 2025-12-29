

<?php $__env->startSection('content'); ?>
<div class="minimal-container">
    <?php if(session('success')): ?>
        <div class="minimal-alert">
            <i class="bi bi-check-circle"></i>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="minimal-alert minimal-alert-error">
            <i class="bi bi-exclamation-circle"></i>
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('projects.index')); ?>">Проекты</a></li>
            <li class="breadcrumb-item active" aria-current="page">Мои сотрудники</li>
        </ol>
    </nav>

    <div class="minimal-header">
        <h1>Мои сотрудники</h1>
        <button onclick="openAddEmployeeModal()" class="minimal-btn minimal-btn-primary">
            <i class="bi bi-plus-lg"></i>
            Добавить 
        </button>
    </div>

    <?php if($employees->isEmpty()): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-people"></i>
            </div>
            <h3>Список сотрудников пуст</h3>
            <p>Добавьте своих сотрудников, чтобы быстро добавлять их в проекты</p>
           
        </div>
    <?php else: ?>
        <div class="employees-list">
            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="employee-card">
                    <div class="employee-card-content">
                        <div class="employee-icon">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="employee-info">
                            <h3><?php echo e($employee->name); ?></h3>
                            <p class="employee-phone">
                                <i class="bi bi-telephone"></i>
                                <?php echo e($employee->phone); ?>

                            </p>
                            <?php if($employee->description): ?>
                                <p class="employee-description"><?php echo e($employee->description); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="employee-actions">
                        <button onclick="editEmployee(<?php echo e($employee->id); ?>, '<?php echo e($employee->name); ?>', '<?php echo e($employee->phone); ?>', '<?php echo e(addslashes($employee->description ?? '')); ?>')" 
                                class="minimal-btn minimal-btn-ghost minimal-btn-sm">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form action="<?php echo e(route('employees.destroy', $employee)); ?>" method="POST" 
                              onsubmit="return confirm('Удалить сотрудника <?php echo e($employee->name); ?>?')" 
                              style="display: inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="minimal-btn minimal-btn-ghost minimal-btn-sm text-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>

<!-- Модальное окно добавления сотрудника -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen m-0">
        <div class="modal-content">
            <form action="<?php echo e(route('employees.store')); ?>" method="POST" class="d-flex flex-column h-100">
                <?php echo csrf_field(); ?>
                <div class="modal-header border-0 pb-2">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="px-4">
                    <div class="wizard-header text-center">
                        <h2>Добавить сотрудника</h2>
                        <p>Укажите имя, телефон и описание</p>
                    </div>
                </div>
                <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                    <div class="wizard-container" style="max-width: 600px; width: 100%;">
                        <div class="form-group-minimal">
                            <label>Имя *</label>
                            <input type="text" class="minimal-input" id="add_name" name="name" 
                                   placeholder="Иван Петров"
                                   required>
                        </div>
                        <div class="form-group-minimal">
                            <label>Номер телефона *</label>
                            <input type="tel" class="minimal-input phone-mask" id="add_phone" name="phone" 
                                   placeholder="+7 (999) 999-99-99"
                                   required>
                        </div>
                        <div class="form-group-minimal">
                            <label>Описание</label>
                            <textarea class="minimal-input" id="add_description" name="description" rows="3" 
                                      placeholder="Должность, специализация или другая информация"></textarea>
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

<!-- Модальное окно редактирования сотрудника -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen m-0">
        <div class="modal-content">
            <form id="editEmployeeForm" method="POST" class="d-flex flex-column h-100">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-header border-0 pb-2">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="px-4">
                    <div class="wizard-header text-center">
                        <h2>Редактировать сотрудника</h2>
                        <p>Измените данные сотрудника</p>
                    </div>
                </div>
                <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                    <div class="wizard-container" style="max-width: 600px; width: 100%;">
                        <div class="form-group-minimal">
                            <label>Имя *</label>
                            <input type="text" class="minimal-input" id="edit_name" name="name" required>
                        </div>
                        <div class="form-group-minimal">
                            <label>Номер телефона *</label>
                            <input type="tel" class="minimal-input phone-mask" id="edit_phone" name="phone" required>
                        </div>
                        <div class="form-group-minimal">
                            <label>Описание</label>
                            <textarea class="minimal-input" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="minimal-btn minimal-btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.employees-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.employee-card {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.2s;
}

.employee-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.employee-card-content {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex: 1;
}

.employee-icon {
    font-size: 3rem;
    color: #007bff;
}

.employee-info {
    flex: 1;
}

.employee-info h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
}

.employee-phone {
    margin: 0.25rem 0;
    color: #666;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.employee-description {
    margin: 0.5rem 0 0 0;
    color: #888;
    font-size: 0.9rem;
}

.employee-actions {
    display: flex;
    gap: 0.5rem;
}

.text-danger {
    color: #dc3545 !important;
}

.text-danger:hover {
    color: #c82333 !important;
}
</style>

<script>
function openAddEmployeeModal() {
    const modal = new bootstrap.Modal(document.getElementById('addEmployeeModal'));
    modal.show();
    
    // Инициализация маски телефона для нового сотрудника
    const addPhoneInput = document.getElementById('add_phone');
    if (addPhoneInput && typeof IMask !== 'undefined') {
        IMask(addPhoneInput, {
            mask: '+{7} (000) 000-00-00',
            lazy: false,
            placeholderChar: '_'
        });
    }
}

function editEmployee(id, name, phone, description) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_phone').value = phone;
    document.getElementById('edit_description').value = description;
    document.getElementById('editEmployeeForm').action = `/employees/${id}`;
    
    const modal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
    modal.show();
    
    // Инициализация маски телефона для редактирования
    const editPhoneInput = document.getElementById('edit_phone');
    if (editPhoneInput && typeof IMask !== 'undefined') {
        IMask(editPhoneInput, {
            mask: '+{7} (000) 000-00-00',
            lazy: false,
            placeholderChar: '_'
        });
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views/employees/index.blade.php ENDPATH**/ ?>