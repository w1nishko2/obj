

<?php $__env->startSection('content'); ?>
<div class="minimal-container">
    <form action="<?php echo e(route('projects.update', $project)); ?>" method="POST" class="d-flex flex-column" style="min-height: calc(100vh - 80px);">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="modal-header border-0 pb-2">
            <a href="<?php echo e(route('projects.show', $project)); ?>" class="btn-close position-absolute top-0 end-0 m-3" style="text-decoration: none;"></a>
        </div>
        
        <div class="px-4">
            <div class="wizard-header text-center">
                <h2>Редактировать проект</h2>
                <p>Измените параметры проекта</p>
            </div>
        </div>
        
        <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
            <div class="wizard-container" style="max-width: 600px; width: 100%;">
                
                <div class="form-group-minimal">
                    <label>Название проекта</label>
                    <input type="text" class="minimal-input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           name="name" value="<?php echo e(old('name', $project->name)); ?>" 
                           placeholder="Ремонт квартиры на ул. Ленина"
                           maxlength="255"
                           required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group-minimal">
                    <label>Адрес объекта</label>
                    <input type="text" class="minimal-input <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           name="address" value="<?php echo e(old('address', $project->address)); ?>" 
                           placeholder="ул. Ленина, д. 10, кв. 25"
                           maxlength="255"
                           required>
                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group-minimal">
                    <label>Тип работ</label>
                    <select class="minimal-input <?php $__errorArgs = ['work_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            name="work_type">
                        <option value="">Выберите тип работ</option>
                        <option value="Капитальный ремонт" <?php echo e(old('work_type', $project->work_type) == 'Капитальный ремонт' ? 'selected' : ''); ?>>Капитальный ремонт</option>
                        <option value="Косметический ремонт" <?php echo e(old('work_type', $project->work_type) == 'Косметический ремонт' ? 'selected' : ''); ?>>Косметический ремонт</option>
                        <option value="Строительство" <?php echo e(old('work_type', $project->work_type) == 'Строительство' ? 'selected' : ''); ?>>Строительство</option>
                        <option value="Реконструкция" <?php echo e(old('work_type', $project->work_type) == 'Реконструкция' ? 'selected' : ''); ?>>Реконструкция</option>
                        <option value="Отделочные работы" <?php echo e(old('work_type', $project->work_type) == 'Отделочные работы' ? 'selected' : ''); ?>>Отделочные работы</option>
                        <option value="Электромонтажные работы" <?php echo e(old('work_type', $project->work_type) == 'Электромонтажные работы' ? 'selected' : ''); ?>>Электромонтажные работы</option>
                        <option value="Сантехнические работы" <?php echo e(old('work_type', $project->work_type) == 'Сантехнические работы' ? 'selected' : ''); ?>>Сантехнические работы</option>
                        <option value="Кровельные работы" <?php echo e(old('work_type', $project->work_type) == 'Кровельные работы' ? 'selected' : ''); ?>>Кровельные работы</option>
                        <option value="Фасадные работы" <?php echo e(old('work_type', $project->work_type) == 'Фасадные работы' ? 'selected' : ''); ?>>Фасадные работы</option>
                        <option value="Ландшафтные работы" <?php echo e(old('work_type', $project->work_type) == 'Ландшафтные работы' ? 'selected' : ''); ?>>Ландшафтные работы</option>
                        <option value="Дизайн интерьера" <?php echo e(old('work_type', $project->work_type) == 'Дизайн интерьера' ? 'selected' : ''); ?>>Дизайн интерьера</option>
                        <option value="Другое" <?php echo e(old('work_type', $project->work_type) == 'Другое' ? 'selected' : ''); ?>>Другое</option>
                    </select>
                    <?php $__errorArgs = ['work_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group-minimal">
                    <label>Статус проекта</label>
                    <select class="minimal-input <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            name="status" required>
                        <option value="В работе" <?php echo e(old('status', $project->status) == 'В работе' ? 'selected' : ''); ?>>В работе</option>
                        <option value="На паузе" <?php echo e(old('status', $project->status) == 'На паузе' ? 'selected' : ''); ?>>На паузе</option>
                        <option value="Завершен" <?php echo e(old('status', $project->status) == 'Завершен' ? 'selected' : ''); ?>>Завершен</option>
                    </select>
                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group-minimal">
                    <label>Общая наценка проекта (%)</label>
                    <input type="number" 
                           class="minimal-input <?php $__errorArgs = ['markup_percent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           name="markup_percent" 
                           value="<?php echo e(old('markup_percent', $project->markup_percent)); ?>"
                           placeholder="Например: 20"
                           step="0.01"
                           min="0"
                           max="999.99">
                    <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">
                        Общая наценка будет применена ко всем задачам, материалам и доставкам. Можно переопределить для конкретного элемента.
                    </small>
                    <?php $__errorArgs = ['markup_percent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

            </div>
        </div>
        
        <div class="modal-footer border-0 justify-content-center">
            <a href="<?php echo e(route('projects.show', $project)); ?>" class="minimal-btn minimal-btn-ghost">Отмена</a>
            <button type="submit" class="minimal-btn minimal-btn-primary">Сохранить</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views\projects\edit.blade.php ENDPATH**/ ?>