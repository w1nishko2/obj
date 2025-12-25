<?php $__env->startSection('content'); ?>
<div class="minimal-container" style="display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 120px);">
    <div class="wizard-container" style="width: 100%; max-width: 500px;">
        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>

            <div class="wizard-content active">
                <div class="wizard-header">
                    <h2>Вход в систему</h2>
                    <p>Введите свои данные для входа</p>
                </div>

                <div class="form-group-minimal">
                    <label>Номер телефона</label>
                    <input id="phone" 
                           type="tel" 
                           class="minimal-input phone-mask <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           name="phone" 
                           value="<?php echo e(old('phone')); ?>" 
                           placeholder="+7 (999) 123-45-67"
                           required 
                           autocomplete="tel" 
                           autofocus>
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group-minimal">
                    <label>Пароль</label>
                    <input id="password" 
                           type="password" 
                           class="minimal-input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           name="password" 
                           placeholder="Введите пароль"
                           required 
                           autocomplete="current-password">
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group-minimal">
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="remember" 
                               id="remember" 
                               <?php echo e(old('remember') ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="remember">
                            Запомнить меня
                        </label>
                    </div>
                </div>
            </div>

            <div class="wizard-actions">
                <?php if(Route::has('register')): ?>
                    <a href="<?php echo e(route('register')); ?>" class="minimal-btn minimal-btn-ghost">
                        Регистрация
                    </a>
                <?php endif; ?>
                <?php if(Route::has('password.request')): ?>
                    <a href="<?php echo e(route('password.request')); ?>" class="minimal-btn minimal-btn-ghost">
                        Забыли пароль?
                    </a>
                <?php endif; ?>
                <button type="submit" class="minimal-btn minimal-btn-primary">
                    Войти
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views\auth\login.blade.php ENDPATH**/ ?>