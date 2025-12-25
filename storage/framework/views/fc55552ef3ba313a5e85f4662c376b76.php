

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 800px;">
    <div class="row mb-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-compact mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Проекты</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('projects.show', $project)); ?>"><?php echo e(Str::limit($project->name, 30)); ?></a></li>
                    <li class="breadcrumb-item active">Данные клиента</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="text-center mb-4">
        <h2><i class="bi bi-person-badge"></i> Данные клиента</h2>
        <p class="text-muted">Эти данные будут автоматически подставляться во все генерируемые документы</p>
    </div>

    <form action="<?php echo e(route('projects.client-data.update', $project)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Основная информация клиента -->
        <div class="minimal-card mb-3">
            <div class="minimal-card-header">
                <span><i class="bi bi-person"></i> Основная информация клиента</span>
            </div>
            <div class="minimal-card-body">
                <div class="form-group-minimal">
                    <label>ФИО клиента <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="minimal-input <?php $__errorArgs = ['client_full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           name="client_full_name" 
                           value="<?php echo e(old('client_full_name', $project->client_full_name)); ?>"
                           placeholder="Иванов Иван Иванович"
                           maxlength="255"
                           required>
                    <?php $__errorArgs = ['client_full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">Пример: Иванов Иван Иванович</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>Телефон</label>
                            <input type="text" 
                                   class="minimal-input <?php $__errorArgs = ['client_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="client_phone" 
                                   value="<?php echo e(old('client_phone', $project->client_phone)); ?>"
                                   placeholder="+7 (999) 123-45-67">
                            <?php $__errorArgs = ['client_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">Формат: +7 (XXX) XXX-XX-XX</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>Email</label>
                            <input type="email" 
                                   class="minimal-input <?php $__errorArgs = ['client_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="client_email" 
                                   value="<?php echo e(old('client_email', $project->client_email)); ?>"
                                   placeholder="client@example.com"
                                   maxlength="255">
                            <?php $__errorArgs = ['client_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group-minimal">
                    <label>Адрес проживания клиента</label>
                    <textarea class="minimal-input <?php $__errorArgs = ['client_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                              name="client_address" 
                              rows="2"
                              style="resize: vertical;"
                              placeholder="г. Москва, ул. Примерная, д. 1, кв. 1"><?php echo e(old('client_address', $project->client_address)); ?></textarea>
                    <?php $__errorArgs = ['client_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Паспортные данные клиента -->
        <div class="minimal-card mb-3">
            <div class="minimal-card-header">
                <span><i class="bi bi-card-text"></i> Паспортные данные клиента</span>
            </div>
            <div class="minimal-card-body">
                <p class="text-muted small mb-3">Необходимы для договоров и актов</p>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group-minimal">
                            <label>Серия</label>
                            <input type="text" 
                                   class="minimal-input <?php $__errorArgs = ['client_passport_series'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="client_passport_series" 
                                   value="<?php echo e(old('client_passport_series', $project->client_passport_series)); ?>"
                                   placeholder="1234"
                                   pattern="[0-9]{4}"
                                   maxlength="4">
                            <?php $__errorArgs = ['client_passport_series'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">4 цифры</small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group-minimal">
                            <label>Номер</label>
                            <input type="text" 
                                   class="minimal-input <?php $__errorArgs = ['client_passport_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="client_passport_number" 
                                   value="<?php echo e(old('client_passport_number', $project->client_passport_number)); ?>"
                                   placeholder="567890"
                                   pattern="[0-9]{6}"
                                   maxlength="6">
                            <?php $__errorArgs = ['client_passport_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">6 цифр</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>Дата выдачи</label>
                            <input type="text" 
                                   class="minimal-input flatpickr-single <?php $__errorArgs = ['client_passport_issued_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="client_passport_issued_date" 
                                   name="client_passport_issued_date" 
                                   value="<?php echo e(old('client_passport_issued_date', $project->client_passport_issued_date ? $project->client_passport_issued_date->format('d.m.Y') : '')); ?>"
                                   placeholder="Выберите дату">
                            <?php $__errorArgs = ['client_passport_issued_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group-minimal">
                    <label>Кем выдан</label>
                    <input type="text" 
                           class="minimal-input <?php $__errorArgs = ['client_passport_issued_by'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           name="client_passport_issued_by" 
                           value="<?php echo e(old('client_passport_issued_by', $project->client_passport_issued_by)); ?>"
                           placeholder="Отделом УФМС России по г. Москва"
                           maxlength="255">
                    <?php $__errorArgs = ['client_passport_issued_by'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Юридические данные клиента -->
        <div class="minimal-card mb-3">
            <div class="minimal-card-header">
                <span><i class="bi bi-building"></i> Для юридических лиц (необязательно)</span>
            </div>
            <div class="minimal-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>Название организации</label>
                            <input type="text" 
                                   class="minimal-input <?php $__errorArgs = ['client_organization_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="client_organization_name" 
                                   value="<?php echo e(old('client_organization_name', $project->client_organization_name)); ?>"
                                   placeholder='ООО "Пример"'
                                   maxlength="255">
                            <?php $__errorArgs = ['client_organization_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>ИНН</label>
                            <input type="text" 
                                   class="minimal-input <?php $__errorArgs = ['client_inn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="client_inn" 
                                   value="<?php echo e(old('client_inn', $project->client_inn)); ?>"
                                   placeholder="1234567890 или 123456789012"
                                   pattern="[0-9]{10}|[0-9]{12}"
                                   maxlength="12">
                            <?php $__errorArgs = ['client_inn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">10 цифр (юр. лицо) или 12 цифр (физ. лицо)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Основная информация прораба -->
        <div class="minimal-card mb-3">
            <div class="minimal-card-header">
                <span><i class="bi bi-person-workspace"></i> Основная информация прораба</span>
            </div>
            <div class="minimal-card-body">
                <p class="text-muted small mb-3">Эти данные также будут использоваться в документах</p>

                <div class="form-group-minimal">
                    <label>ФИО прораба <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="minimal-input <?php $__errorArgs = ['foreman_full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           name="foreman_full_name" 
                           value="<?php echo e(old('foreman_full_name', $project->foreman_full_name)); ?>"
                           placeholder="Петров Петр Петрович"
                           maxlength="255"
                           required>
                    <?php $__errorArgs = ['foreman_full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">Пример: Петров Петр Петрович</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>Телефон прораба</label>
                            <input type="text" 
                                   class="minimal-input <?php $__errorArgs = ['foreman_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="foreman_phone" 
                                   value="<?php echo e(old('foreman_phone', $project->foreman_phone)); ?>"
                                   placeholder="+7 (999) 123-45-67">
                            <?php $__errorArgs = ['foreman_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">Формат: +7 (XXX) XXX-XX-XX</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>Email прораба</label>
                            <input type="email" 
                                   class="minimal-input <?php $__errorArgs = ['foreman_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="foreman_email" 
                                   value="<?php echo e(old('foreman_email', $project->foreman_email)); ?>"
                                   placeholder="foreman@example.com"
                                   maxlength="255">
                            <?php $__errorArgs = ['foreman_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group-minimal">
                    <label>Адрес проживания прораба</label>
                    <textarea class="minimal-input <?php $__errorArgs = ['foreman_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                              name="foreman_address" 
                              rows="2"
                              style="resize: vertical;"
                              placeholder="г. Москва, ул. Строительная, д. 10, кв. 5"><?php echo e(old('foreman_address', $project->foreman_address)); ?></textarea>
                    <?php $__errorArgs = ['foreman_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Паспортные данные прораба -->
        <div class="minimal-card mb-3">
            <div class="minimal-card-header">
                <span><i class="bi bi-card-text"></i> Паспортные данные прораба</span>
            </div>
            <div class="minimal-card-body">
                <p class="text-muted small mb-3">Необходимы для договоров и актов</p>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group-minimal">
                            <label>Серия</label>
                            <input type="text" 
                                   class="minimal-input <?php $__errorArgs = ['foreman_passport_series'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="foreman_passport_series" 
                                   value="<?php echo e(old('foreman_passport_series', $project->foreman_passport_series)); ?>"
                                   placeholder="1234"
                                   pattern="[0-9]{4}"
                                   maxlength="4">
                            <?php $__errorArgs = ['foreman_passport_series'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">4 цифры</small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group-minimal">
                            <label>Номер</label>
                            <input type="text" 
                                   class="minimal-input <?php $__errorArgs = ['foreman_passport_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="foreman_passport_number" 
                                   value="<?php echo e(old('foreman_passport_number', $project->foreman_passport_number)); ?>"
                                   placeholder="567890"
                                   pattern="[0-9]{6}"
                                   maxlength="6">
                            <?php $__errorArgs = ['foreman_passport_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">6 цифр</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>Дата выдачи</label>
                            <input type="text" 
                                   class="minimal-input flatpickr-single <?php $__errorArgs = ['foreman_passport_issued_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="foreman_passport_issued_date" 
                                   name="foreman_passport_issued_date" 
                                   value="<?php echo e(old('foreman_passport_issued_date', $project->foreman_passport_issued_date ? $project->foreman_passport_issued_date->format('d.m.Y') : '')); ?>"
                                   placeholder="Выберите дату">
                            <?php $__errorArgs = ['foreman_passport_issued_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group-minimal">
                    <label>Кем выдан</label>
                    <input type="text" 
                           class="minimal-input <?php $__errorArgs = ['foreman_passport_issued_by'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           name="foreman_passport_issued_by" 
                           value="<?php echo e(old('foreman_passport_issued_by', $project->foreman_passport_issued_by)); ?>"
                           placeholder="Отделом УФМС России по г. Москва"
                           maxlength="255">
                    <?php $__errorArgs = ['foreman_passport_issued_by'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Юридические данные прораба -->
        <div class="minimal-card mb-3">
            <div class="minimal-card-header">
                <span><i class="bi bi-building"></i> Для юридических лиц прораба (необязательно)</span>
            </div>
            <div class="minimal-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>Название организации</label>
                            <input type="text" 
                                   class="minimal-input <?php $__errorArgs = ['foreman_organization_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="foreman_organization_name" 
                                   value="<?php echo e(old('foreman_organization_name', $project->foreman_organization_name)); ?>"
                                   placeholder='ООО "СтройСервис"'
                                   maxlength="255">
                            <?php $__errorArgs = ['foreman_organization_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>ИНН прораба</label>
                            <input type="text" 
                                   class="minimal-input <?php $__errorArgs = ['foreman_inn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="foreman_inn" 
                                   value="<?php echo e(old('foreman_inn', $project->foreman_inn)); ?>"
                                   placeholder="1234567890 или 123456789012"
                                   pattern="[0-9]{10}|[0-9]{12}"
                                   maxlength="12">
                            <?php $__errorArgs = ['foreman_inn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger" style="font-size: 0.85rem;"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">10 цифр (юр. лицо) или 12 цифр (физ. лицо)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Кнопки -->
        <div class="d-flex justify-content-between mb-4">
            <a href="<?php echo e(route('projects.show', $project)); ?>" class="minimal-btn minimal-btn-ghost">
                <i class="bi bi-arrow-left"></i> Отмена
            </a>
            <button type="submit" class="minimal-btn minimal-btn-primary">
                <i class="bi bi-check-lg"></i> Сохранить данные
            </button>
        </div>

        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            <strong>Важно:</strong> После заполнения этих данных вы сможете генерировать готовые документы для клиента 
            (сметы, договоры, акты и т.д.) с автоматической подстановкой информации.
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация Flatpickr для дат паспортов
    const passportDateInputs = document.querySelectorAll('.flatpickr-single');
    passportDateInputs.forEach(input => {
        if (input) {
            flatpickr(input, {
                locale: 'ru',
                dateFormat: 'd.m.Y',
                maxDate: 'today'
            });
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views\document-templates\edit-client.blade.php ENDPATH**/ ?>