

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="mb-4">Настройки профиля</h2>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <!-- Изменение имени -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Личная информация</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('profile.update.name')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Имя</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="name" name="name" value="<?php echo e(old('name', Auth::user()->name)); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </form>
                </div>
            </div>

            <!-- Изменение email -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Email адрес</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Текущий email: <strong><?php echo e(Auth::user()->email); ?></strong></p>
                    
                    <div id="emailForm">
                        <div class="mb-3">
                            <label for="email" class="form-label">Новый email</label>
                            <input type="email" class="form-control" id="email" name="email">
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>
                        
                        <button type="button" class="btn btn-primary" onclick="sendVerificationCode()">
                            Отправить код подтверждения
                        </button>
                    </div>

                    <div id="codeVerificationForm" style="display: none;">
                        <div class="alert alert-info">
                            Код подтверждения отправлен на новый email адрес
                        </div>
                        
                        <div class="mb-3">
                            <label for="code" class="form-label">Введите код из письма (4 цифры)</label>
                            <input type="text" class="form-control" id="code" name="code" 
                                   maxlength="4" pattern="[0-9]{4}" placeholder="0000">
                            <div class="invalid-feedback" id="codeError"></div>
                        </div>
                        
                        <button type="button" class="btn btn-success" onclick="verifyCode()">
                            Подтвердить
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="resetEmailForm()">
                            Отмена
                        </button>
                    </div>
                </div>
            </div>

            <?php if(Auth::user()->isForeman()): ?>
            <!-- Данные прораба -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-workspace"></i> Данные прораба для документов</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-4">Эти данные будут автоматически подставляться в формы редактирования документов</p>
                    
                    <form method="POST" action="<?php echo e(route('profile.update.foreman-data')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Полное ФИО</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="full_name" name="full_name" 
                                   value="<?php echo e(old('full_name', Auth::user()->full_name)); ?>"
                                   placeholder="Иванов Иван Иванович"
                                   pattern="[\u0410-\u042f\u0451\u0430-\u044f\s\-]+"
                                   title="Только русские буквы, пробелы и дефисы"
                                   maxlength="255">
                            <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">Пример: Иванов Иван Иванович</small>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Адрес проживания</label>
                            <textarea class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="address" name="address" rows="2"
                                      placeholder="г. Москва, ул. Примерная, д. 1, кв. 1"><?php echo e(old('address', Auth::user()->address)); ?></textarea>
                            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <h6 class="mb-3 mt-4">Паспортные данные</h6>
                        
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="passport_series" class="form-label">Серия</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['passport_series'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="passport_series" name="passport_series" 
                                       value="<?php echo e(old('passport_series', Auth::user()->passport_series)); ?>"
                                       placeholder="1234" pattern="[0-9]{4}" 
                                       title="4 цифры"
                                       maxlength="4">
                                <?php $__errorArgs = ['passport_series'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="form-text text-muted">4 цифры</small>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="passport_number" class="form-label">Номер</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['passport_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="passport_number" name="passport_number" 
                                       value="<?php echo e(old('passport_number', Auth::user()->passport_number)); ?>"
                                       placeholder="567890" pattern="[0-9]{6}" 
                                       title="6 цифр"
                                       maxlength="6">
                                <?php $__errorArgs = ['passport_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="form-text text-muted">6 цифр</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="passport_issued_date" class="form-label">Дата выдачи</label>
                                <input type="text" class="form-control flatpickr-single <?php $__errorArgs = ['passport_issued_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="passport_issued_date" name="passport_issued_date" 
                                       value="<?php echo e(old('passport_issued_date', Auth::user()->passport_issued_date ? Auth::user()->passport_issued_date->format('d.m.Y') : '')); ?>"
                                       placeholder="Выберите дату">
                                <?php $__errorArgs = ['passport_issued_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="passport_issued_by" class="form-label">Кем выдан</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['passport_issued_by'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="passport_issued_by" name="passport_issued_by" 
                                   value="<?php echo e(old('passport_issued_by', Auth::user()->passport_issued_by)); ?>"
                                   placeholder="Отделом УФМС России"
                                   maxlength="255">
                            <?php $__errorArgs = ['passport_issued_by'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <h6 class="mb-3 mt-4">Для юридических лиц (необязательно)</h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="organization_name" class="form-label">Название организации</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['organization_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="organization_name" name="organization_name" 
                                       value="<?php echo e(old('organization_name', Auth::user()->organization_name)); ?>"
                                       placeholder='ООО "Пример"' maxlength="255">
                                <?php $__errorArgs = ['organization_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="inn" class="form-label">ИНН</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['inn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="inn" name="inn" 
                                       value="<?php echo e(old('inn', Auth::user()->inn)); ?>"
                                       placeholder="1234567890 или 123456789012" 
                                       pattern="[0-9]{10}|[0-9]{12}" 
                                       title="10 цифр (юр. лицо) или 12 цифр (физ. лицо)"
                                       maxlength="12">
                                <?php $__errorArgs = ['inn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="form-text text-muted">10 цифр (юр. лицо) или 12 цифр (физ. лицо)</small>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Сохранить данные
                        </button>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <!-- Изменение пароля -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Изменить пароль</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('profile.update.password')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Текущий пароль</label>
                            <input type="password" class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="current_password" name="current_password" required>
                            <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Новый пароль</label>
                            <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="password" name="password" required>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Подтвердите новый пароль</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Изменить пароль</button>
                    </form>
                </div>
            </div>

            <!-- Push-уведомления -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bell"></i> Push-уведомления</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Управляйте уведомлениями о событиях в ваших проектах</p>
                    
                    <div id="notificationStatus" class="mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <strong>Статус:</strong>
                                <span id="statusText" class="ms-2">Проверка...</span>
                            </div>
                            <div id="notificationButtons">
                                <!-- Кнопки будут добавлены через JavaScript -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info" role="alert">
                        <small>
                            <i class="bi bi-info-circle"></i>
                            <strong>О уведомлениях:</strong> Вы будете получать уведомления о новых задачах, изменениях этапов, 
                            комментариях и других событиях в проектах, где вы являетесь участником.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Удаление аккаунта -->
            <div class="card mb-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Опасная зона</h5>
                </div>
                <div class="card-body">
                    <h6>Удалить аккаунт</h6>
                    <p class="text-muted">После удаления аккаунта все ваши данные будут безвозвратно удалены. Это действие нельзя отменить.</p>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="bi bi-trash"></i> Удалить аккаунт
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно подтверждения удаления -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="bi bi-exclamation-triangle"></i> Предупреждение!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert">
                    <strong>Внимание!</strong> Это действие необратимо!
                </div>
                <p><strong>При удалении аккаунта будут безвозвратно удалены:</strong></p>
                <ul>
                    <li>Все ваши проекты и задачи</li>
                    <li>Все документы и файлы</li>
                    <li>Все материалы и комментарии</li>
                    <li>История и архив проектов</li>
                    <li>Личные данные и настройки</li>
                </ul>
                <p class="text-danger"><strong>Восстановить данные после удаления будет невозможно!</strong></p>
                
                <form id="deleteAccountForm" method="POST" action="<?php echo e(route('profile.delete')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Для подтверждения введите ваш пароль:</label>
                        <input type="password" class="form-control" id="confirm_password" name="password" required
                               placeholder="Введите пароль">
                        <div class="invalid-feedback" id="passwordError"></div>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                        <label class="form-check-label" for="confirmDelete">
                            Я понимаю, что это действие необратимо и все мои данные будут удалены навсегда
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-danger" onclick="deleteAccount()">
                    <i class="bi bi-trash"></i> Удалить аккаунт навсегда
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function sendVerificationCode() {
    const email = document.getElementById('email').value;
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    
    emailInput.classList.remove('is-invalid');
    emailError.textContent = '';
    
    if (!email) {
        emailInput.classList.add('is-invalid');
        emailError.textContent = 'Введите email адрес';
        return;
    }
    
    fetch('<?php echo e(route('profile.send-verification-code')); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('emailForm').style.display = 'none';
            document.getElementById('codeVerificationForm').style.display = 'block';
        } else {
            emailInput.classList.add('is-invalid');
            emailError.textContent = data.message || 'Ошибка отправки кода';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        emailInput.classList.add('is-invalid');
        emailError.textContent = 'Произошла ошибка при отправке кода';
    });
}

function verifyCode() {
    const code = document.getElementById('code').value;
    const codeInput = document.getElementById('code');
    const codeError = document.getElementById('codeError');
    
    codeInput.classList.remove('is-invalid');
    codeError.textContent = '';
    
    if (!code || code.length !== 4) {
        codeInput.classList.add('is-invalid');
        codeError.textContent = 'Введите 4-значный код';
        return;
    }
    
    fetch('<?php echo e(route('profile.verify-code')); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ code: code })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            codeInput.classList.add('is-invalid');
            codeError.textContent = data.message || 'Неверный код';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        codeInput.classList.add('is-invalid');
        codeError.textContent = 'Произошла ошибка при проверке кода';
    });
}

function resetEmailForm() {
    document.getElementById('emailForm').style.display = 'block';
    document.getElementById('codeVerificationForm').style.display = 'none';
    document.getElementById('email').value = '';
    document.getElementById('code').value = '';
}

function deleteAccount() {
    const password = document.getElementById('confirm_password').value;
    const checkbox = document.getElementById('confirmDelete');
    const passwordInput = document.getElementById('confirm_password');
    const passwordError = document.getElementById('passwordError');
    
    passwordInput.classList.remove('is-invalid');
    passwordError.textContent = '';
    
    if (!password) {
        passwordInput.classList.add('is-invalid');
        passwordError.textContent = 'Введите пароль для подтверждения';
        return;
    }
    
    if (!checkbox.checked) {
        alert('Пожалуйста, подтвердите, что вы понимаете последствия удаления аккаунта');
        return;
    }
    
    // Финальное подтверждение
    if (confirm('Вы уверены? Это последнее предупреждение! Все ваши данные будут удалены НАВСЕГДА!')) {
        document.getElementById('deleteAccountForm').submit();
    }
}

// ========================================
// УПРАВЛЕНИЕ PUSH-УВЕДОМЛЕНИЯМИ
// ========================================
document.addEventListener('DOMContentLoaded', async function() {
    const statusText = document.getElementById('statusText');
    const notificationButtons = document.getElementById('notificationButtons');
    
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        statusText.innerHTML = '<span class="badge bg-secondary">Не поддерживается</span>';
        notificationButtons.innerHTML = '<small class="text-muted">Ваш браузер не поддерживает push-уведомления</small>';
        return;
    }
    
    try {
        const registration = await navigator.serviceWorker.ready;
        const subscription = await registration.pushManager.getSubscription();
        
        if (subscription) {
            statusText.innerHTML = '<span class="badge bg-success">Включены</span>';
            notificationButtons.innerHTML = `
                <button class="btn btn-danger btn-sm" onclick="disableNotifications()">
                    <i class="bi bi-bell-slash"></i> Отключить
                </button>
            `;
        } else {
            statusText.innerHTML = '<span class="badge bg-warning">Отключены</span>';
            notificationButtons.innerHTML = `
                <button class="btn btn-primary btn-sm" onclick="enableNotifications()">
                    <i class="bi bi-bell"></i> Включить
                </button>
            `;
        }
    } catch (error) {
        console.error('Ошибка проверки подписки:', error);
        statusText.innerHTML = '<span class="badge bg-secondary">Ошибка</span>';
    }
});

async function enableNotifications() {
    try {
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            alert('Разрешение на уведомления не предоставлено');
            return;
        }
        
        // Используем глобальный WebPushManager если он доступен
        if (window.webPushManager) {
            await window.webPushManager.subscribe();
            location.reload();
        } else {
            alert('Менеджер уведомлений недоступен. Перезагрузите страницу.');
        }
    } catch (error) {
        console.error('Ошибка включения уведомлений:', error);
        alert('Не удалось включить уведомления. Попробуйте позже.');
    }
}

async function disableNotifications() {
    if (!confirm('Вы уверены, что хотите отключить push-уведомления?')) {
        return;
    }
    
    try {
        const registration = await navigator.serviceWorker.ready;
        const subscription = await registration.pushManager.getSubscription();
        
        if (subscription) {
            await subscription.unsubscribe();
            
            // Удаляем подписку на сервере
            await fetch('/push/unsubscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    endpoint: subscription.endpoint
                })
            });
            
            location.reload();
        }
    } catch (error) {
        console.error('Ошибка отключения уведомлений:', error);
        alert('Не удалось отключить уведомления. Попробуйте позже.');
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views\profile\index.blade.php ENDPATH**/ ?>