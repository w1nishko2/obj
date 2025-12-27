

<?php $__env->startSection('content'); ?>
<div class="minimal-container">
    <div class="minimal-header">
        <h1>Настройки профиля</h1>
    </div>

    <?php if(session('success')): ?>
        <div class="minimal-alert">
            <i class="bi bi-check-circle"></i>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <!-- Изменение имени -->
    <div class="settings-section">
        <div class="settings-section-header">
            <h3>Личная информация</h3>
        </div>
        <div class="settings-section-body">
            <form method="POST" action="<?php echo e(route('profile.update.name')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                
                <div class="minimal-form-group">
                    <label for="name" class="minimal-label">Имя</label>
                    <input type="text" class="minimal-input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> minimal-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="name" name="name" value="<?php echo e(old('name', Auth::user()->name)); ?>" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="minimal-error"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <button type="submit" class="minimal-btn minimal-btn-primary">
                    <i class="bi bi-check-lg"></i> Сохранить
                </button>
            </form>
        </div>
    </div>

    <!-- Изменение email -->
    <div class="settings-section">
        <div class="settings-section-header">
            <h3>Email адрес</h3>
        </div>
        <div class="settings-section-body">
            <p class="text-muted mb-4">Текущий email: <strong><?php echo e(Auth::user()->email); ?></strong></p>
            
            <div id="emailForm">
                <div class="minimal-form-group">
                    <label for="email" class="minimal-label">Новый email</label>
                    <input type="email" class="minimal-input" id="email" name="email">
                    <span class="minimal-error" id="emailError" style="display: none;"></span>
                </div>
                
                <button type="button" class="minimal-btn minimal-btn-primary" onclick="sendVerificationCode()">
                    <i class="bi bi-envelope"></i> Отправить код подтверждения
                </button>
            </div>

            <div id="codeVerificationForm" style="display: none;">
                <div class="minimal-alert" style="margin-bottom: 1rem;">
                    <i class="bi bi-info-circle"></i>
                    Код подтверждения отправлен на новый email адрес
                </div>
                
                <div class="minimal-form-group">
                    <label for="code" class="minimal-label">Введите код из письма (4 цифры)</label>
                    <input type="text" class="minimal-input" id="code" name="code" 
                           maxlength="4" pattern="[0-9]{4}" placeholder="0000">
                    <span class="minimal-error" id="codeError" style="display: none;"></span>
                </div>
                
                <div style="display: flex; gap: 0.5rem;">
                    <button type="button" class="minimal-btn minimal-btn-primary" onclick="verifyCode()">
                        <i class="bi bi-check-lg"></i> Подтвердить
                    </button>
                    <button type="button" class="minimal-btn minimal-btn-ghost" onclick="resetEmailForm()">
                        Отмена
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php if(Auth::user()->isForeman()): ?>
    <!-- Данные прораба -->
    <div class="settings-section">
        <div class="settings-section-header">
            <h3><i class="bi bi-person-workspace"></i> Данные прораба для документов</h3>
            <p class="section-hint">Эти данные будут автоматически подставляться в формы редактирования документов</p>
        </div>
        <div class="settings-section-body">
            <form method="POST" action="<?php echo e(route('profile.update.foreman-data')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                
                <div class="minimal-form-group">
                    <label for="full_name" class="minimal-label">Полное ФИО</label>
                    <input type="text" class="minimal-input <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> minimal-input-error <?php unset($message);
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
                        <span class="minimal-error"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <span class="minimal-hint">Пример: Иванов Иван Иванович</span>
                </div>

                <div class="minimal-form-group">
                    <label for="address" class="minimal-label">Адрес проживания</label>
                    <textarea class="minimal-input <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> minimal-input-error <?php unset($message);
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
                        <span class="minimal-error"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <h4 style="margin: 2rem 0 1rem;">Паспортные данные</h4>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div class="minimal-form-group">
                        <label for="passport_series" class="minimal-label">Серия</label>
                        <input type="text" class="minimal-input <?php $__errorArgs = ['passport_series'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> minimal-input-error <?php unset($message);
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
                            <span class="minimal-error"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <span class="minimal-hint">4 цифры</span>
                    </div>

                    <div class="minimal-form-group">
                        <label for="passport_number" class="minimal-label">Номер</label>
                        <input type="text" class="minimal-input <?php $__errorArgs = ['passport_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> minimal-input-error <?php unset($message);
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
                            <span class="minimal-error"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <span class="minimal-hint">6 цифр</span>
                    </div>

                    <div class="minimal-form-group">
                        <label for="passport_issued_date" class="minimal-label">Дата выдачи</label>
                        <input type="text" class="minimal-input flatpickr-single <?php $__errorArgs = ['passport_issued_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> minimal-input-error <?php unset($message);
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
                            <span class="minimal-error"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="minimal-form-group">
                    <label for="passport_issued_by" class="minimal-label">Кем выдан</label>
                    <input type="text" class="minimal-input <?php $__errorArgs = ['passport_issued_by'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> minimal-input-error <?php unset($message);
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
                        <span class="minimal-error"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <h4 style="margin: 2rem 0 1rem;">Для юридических лиц (необязательно)</h4>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                    <div class="minimal-form-group">
                        <label for="organization_name" class="minimal-label">Название организации</label>
                        <input type="text" class="minimal-input <?php $__errorArgs = ['organization_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> minimal-input-error <?php unset($message);
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
                            <span class="minimal-error"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="minimal-form-group">
                        <label for="inn" class="minimal-label">ИНН</label>
                        <input type="text" class="minimal-input <?php $__errorArgs = ['inn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> minimal-input-error <?php unset($message);
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
                            <span class="minimal-error"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <span class="minimal-hint">10 цифр (юр. лицо) или 12 цифр (физ. лицо)</span>
                    </div>
                </div>
                
                <button type="submit" class="minimal-btn minimal-btn-primary">
                    <i class="bi bi-check-lg"></i> Сохранить данные
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Изменение пароля -->
    <div class="settings-section">
        <div class="settings-section-header">
            <h3>Изменить пароль</h3>
        </div>
        <div class="settings-section-body">
            <form method="POST" action="<?php echo e(route('profile.update.password')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                
                <div class="minimal-form-group">
                    <label for="current_password" class="minimal-label">Текущий пароль</label>
                    <input type="password" class="minimal-input <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> minimal-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="current_password" name="current_password" required>
                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="minimal-error"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="minimal-form-group">
                    <label for="password" class="minimal-label">Новый пароль</label>
                    <input type="password" class="minimal-input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> minimal-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="password" name="password" required>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="minimal-error"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="minimal-form-group">
                    <label for="password_confirmation" class="minimal-label">Подтвердите новый пароль</label>
                    <input type="password" class="minimal-input" 
                           id="password_confirmation" name="password_confirmation" required>
                </div>
                
                <button type="submit" class="minimal-btn minimal-btn-primary">
                    <i class="bi bi-key"></i> Изменить пароль
                </button>
            </form>
        </div>
    </div>

    <!-- Push-уведомления -->
    <div class="settings-section">
        <div class="settings-section-header">
            <h3><i class="bi bi-bell"></i> Push-уведомления</h3>
            <p class="section-hint">Управляйте уведомлениями о событиях в ваших проектах</p>
        </div>
        <div class="settings-section-body">
            <div id="notificationStatus" class="notification-status-wrapper">
                <div class="notification-status">
                    <div>
                        <strong>Статус:</strong>
                        <span id="statusText" class="ms-2">Проверка...</span>
                    </div>
                    <div id="notificationButtons">
                        <!-- Кнопки будут добавлены через JavaScript -->
                    </div>
                </div>
            </div>
            
            <div class="minimal-info-box">
                <i class="bi bi-info-circle"></i>
                <div>
                    <strong>О уведомлениях:</strong> Вы будете получать уведомления о новых задачах, изменениях этапов, 
                    комментариях и других событиях в проектах, где вы являетесь участником.
                </div>
            </div>
        </div>
    </div>

    <!-- Удаление аккаунта -->
    <div class="settings-section danger-zone">
        <div class="settings-section-header">
            <h3><i class="bi bi-exclamation-triangle"></i> Опасная зона</h3>
        </div>
        <div class="settings-section-body">
            <h4 style="color: #a70000;">Удалить аккаунт</h4>
            <p class="text-muted" style="margin-bottom: 1rem;">После удаления аккаунта все ваши данные будут безвозвратно удалены. Это действие нельзя отменить.</p>
            <button type="button" class="minimal-btn minimal-btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                <i class="bi bi-trash"></i> Удалить аккаунт
            </button>
        </div>
    </div>
</div>

<!-- Модальное окно подтверждения удаления -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content minimal-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="bi bi-exclamation-triangle" style="color: #a70000;"></i> Предупреждение!
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="minimal-alert-danger" style="margin-bottom: 1.5rem;">
                    <strong>Внимание!</strong> Это действие необратимо!
                </div>
                <p><strong>При удалении аккаунта будут безвозвратно удалены:</strong></p>
                <ul style="margin: 1rem 0; padding-left: 1.5rem;">
                    <li>Все ваши проекты и задачи</li>
                    <li>Все документы и файлы</li>
                    <li>Все материалы и комментарии</li>
                    <li>История и архив проектов</li>
                    <li>Личные данные и настройки</li>
                </ul>
                <p style="color: #a70000; font-weight: 600;"><strong>Восстановить данные после удаления будет невозможно!</strong></p>
                
                <form id="deleteAccountForm" method="POST" action="<?php echo e(route('profile.delete')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    
                    <div class="minimal-form-group">
                        <label for="confirm_password" class="minimal-label">Для подтверждения введите ваш пароль:</label>
                        <input type="password" class="minimal-input" id="confirm_password" name="password" required
                               placeholder="Введите пароль">
                        <span class="minimal-error" id="passwordError" style="display: none;"></span>
                    </div>
                    
                    <div class="form-check" style="margin-bottom: 1rem;">
                        <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                        <label class="form-check-label" for="confirmDelete">
                            Я понимаю, что это действие необратимо и все мои данные будут удалены навсегда
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 1rem;">
                <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="minimal-btn minimal-btn-danger" onclick="deleteAccount()">
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
    
    emailInput.classList.remove('minimal-input-error');
    emailError.style.display = 'none';
    emailError.textContent = '';
    
    if (!email) {
        emailInput.classList.add('minimal-input-error');
        emailError.style.display = 'block';
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
            emailInput.classList.add('minimal-input-error');
            emailError.style.display = 'block';
            emailError.textContent = data.message || 'Ошибка отправки кода';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        emailInput.classList.add('minimal-input-error');
        emailError.style.display = 'block';
        emailError.textContent = 'Произошла ошибка при отправке кода';
    });
}

function verifyCode() {
    const code = document.getElementById('code').value;
    const codeInput = document.getElementById('code');
    const codeError = document.getElementById('codeError');
    
    codeInput.classList.remove('minimal-input-error');
    codeError.style.display = 'none';
    codeError.textContent = '';
    
    if (!code || code.length !== 4) {
        codeInput.classList.add('minimal-input-error');
        codeError.style.display = 'block';
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
            codeInput.classList.add('minimal-input-error');
            codeError.style.display = 'block';
            codeError.textContent = data.message || 'Неверный код';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        codeInput.classList.add('minimal-input-error');
        codeError.style.display = 'block';
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
    
    passwordInput.classList.remove('minimal-input-error');
    passwordError.style.display = 'none';
    passwordError.textContent = '';
    
    if (!password) {
        passwordInput.classList.add('minimal-input-error');
        passwordError.style.display = 'block';
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
        statusText.innerHTML = '<span class="notification-badge notification-badge-disabled">Не поддерживается</span>';
        notificationButtons.innerHTML = '<small class="text-muted">Ваш браузер не поддерживает push-уведомления</small>';
        return;
    }
    
    try {
        const registration = await navigator.serviceWorker.ready;
        const subscription = await registration.pushManager.getSubscription();
        
        if (subscription) {
            statusText.innerHTML = '<span class="notification-badge notification-badge-enabled">Включены</span>';
            notificationButtons.innerHTML = `
                <button class="minimal-btn minimal-btn-danger minimal-btn-sm" onclick="disableNotifications()">
                    <i class="bi bi-bell-slash"></i> Отключить
                </button>
            `;
        } else {
            statusText.innerHTML = '<span class="notification-badge notification-badge-warning">Отключены</span>';
            notificationButtons.innerHTML = `
                <button class="minimal-btn minimal-btn-primary minimal-btn-sm" onclick="enableNotifications()">
                    <i class="bi bi-bell"></i> Включить
                </button>
            `;
        }
    } catch (error) {
        console.error('Ошибка проверки подписки:', error);
        statusText.innerHTML = '<span class="notification-badge notification-badge-disabled">Ошибка</span>';
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