<?php $__env->startSection('content'); ?>
<div class="minimal-container" style="display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 120px);">
    <div class="wizard-container" style="width: 100%; max-width: 600px;">
        <!-- Индикатор прогресса -->
        <div class="wizard-progress">
            <div class="progress-bar">
                <div class="progress-fill" id="progressBar"></div>
            </div>
            <div class="progress-steps">
                <div class="progress-step active" data-step="1">1</div>
                <div class="progress-step" data-step="2">2</div>
                <div class="progress-step" data-step="3">3</div>
                <div class="progress-step" data-step="4">4</div>
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('register')); ?>" id="registerForm">
            <?php echo csrf_field(); ?>

            <!-- Шаг 1: Приветствие -->
            <div class="wizard-step active" data-step="1">
                <div class="wizard-header text-center">
                    <div class="welcome-icon mb-4">
                        <i class="bi bi-building" style="font-size: 4rem; color: #a70000;"></i>
                    </div>
                    <h2 style="font-size: 2rem; margin-bottom: 1rem;">Добро пожаловать!</h2>
                    <h3 style="font-size: 1.5rem; color: #a70000; margin-bottom: 1rem;">Вы пришли в сервис Объект+</h3>
                    <p style="font-size: 1.1rem; color: #6b7280; max-width: 500px; margin: 0 auto;">
                        Пройдите дальше, если хотите зарегистрироваться
                    </p>
                </div>
            </div>

            <!-- Шаг 2: Имя -->
            <div class="wizard-step" data-step="2">
                <div class="wizard-header">
                    <h2>Напишите свое имя</h2>
                    <p>Напишите свое имя в поле ниже</p>
                </div>

                <div class="form-group-minimal">
                    <label>Ваше имя</label>
                    <input id="name" 
                           type="text" 
                           class="minimal-input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           name="name" 
                           value="<?php echo e(old('name')); ?>" 
                           placeholder="Иван Петров"
                           maxlength="255"
                           required 
                           autocomplete="name">
                    <?php $__errorArgs = ['name'];
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
            </div>

            <!-- Шаг 3: Телефон -->
            <div class="wizard-step" data-step="3">
                <div class="wizard-header">
                    <h2>Осталось немного</h2>
                    <p>Напишите свой номер телефона ниже</p>
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
                           autocomplete="tel">
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
                    <label>Email (необязательно)</label>
                    <input id="email" 
                           type="email" 
                           class="minimal-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           name="email" 
                           value="<?php echo e(old('email')); ?>" 
                           placeholder="example@mail.com"
                           maxlength="255"
                           autocomplete="email">
                    <?php $__errorArgs = ['email'];
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
            </div>

            <!-- Шаг 4: Пароль -->
            <div class="wizard-step" data-step="4">
                <div class="wizard-header">
                    <h2>Конец!</h2>
                    <p>Останется придумать пароль и всё!</p>
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
                           placeholder="Минимум 8 символов"
                           required 
                           autocomplete="new-password">
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
                    <label>Подтверждение пароля</label>
                    <input id="password-confirm" 
                           type="password" 
                           class="minimal-input" 
                           name="password_confirmation" 
                           placeholder="Повторите пароль"
                           required 
                           autocomplete="new-password">
                </div>

                <div class="form-group-minimal">
                    <div class="form-check">
                        <input class="form-check-input <?php $__errorArgs = ['terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               type="checkbox" 
                               name="terms" 
                               id="terms" 
                               required>
                        <label class="form-check-label" for="terms" style="font-size: 0.9rem; line-height: 1.5;">
                            Я согласен(на) с 
                            <a href="<?php echo e(route('terms-of-service')); ?>" target="_blank" rel="noopener noreferrer" style="color: #a70000; text-decoration: underline;">Пользовательским соглашением</a> 
                            и 
                            <a href="<?php echo e(route('privacy-policy')); ?>" target="_blank" rel="noopener noreferrer" style="color: #a70000; text-decoration: underline;">Политикой конфиденциальности</a>, 
                            а также даю согласие на обработку моих персональных данных
                        </label>
                        <?php $__errorArgs = ['terms'];
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
                </div>
            </div>

            <!-- Кнопки навигации -->
            <div class="wizard-actions">
                <button type="button" class="minimal-btn minimal-btn-primary" id="nextBtn">
                    Далее
                    <i class="bi bi-arrow-right"></i>
                </button>
                <button type="submit" class="minimal-btn minimal-btn-primary" id="submitBtn" style="display: none;">
                    <i class="bi bi-check-lg"></i>
                    Зарегистрироваться
                </button>
                <button type="button" class="minimal-btn minimal-btn-ghost" id="prevBtn" style="display: none;">
                    <i class="bi bi-arrow-left"></i>
                    Назад
                </button>
                
                <?php if(Route::has('login')): ?>
                    <a href="<?php echo e(route('login')); ?>" class="minimal-btn minimal-btn-ghost" id="loginLink">
                        Уже есть аккаунт
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<style>
.wizard-progress {
    margin-bottom: 3rem;
}

.progress-bar {
    height: 4px;
    background: #f5f5f5;
    border-radius: 2px;
    margin-bottom: 1rem;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #a70000, #b91c1c);
    width: 25%;
    transition: width 0.3s ease;
}

.progress-steps {
    display: flex;
    justify-content: space-between;
    padding: 0 1rem;
}

.progress-step {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f5f5f5;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.progress-step.active {
    background: #a70000;
    color: white;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(167, 0, 0, 0.3);
}

.progress-step.completed {
    background: #a70000;
    color: white;
}

.wizard-step {
    display: none;
    animation: fadeIn 0.4s ease;
}

.wizard-step.active {
    display: block;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.wizard-header {
    margin-bottom: 2rem;
}

.wizard-header.text-center {
    text-align: center;
}

.wizard-header h2 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #a70000;
    margin-bottom: 0.5rem;
}

.wizard-header p {
    font-size: 1rem;
    color: #6b7280;
}

.wizard-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #f5f5f5;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 4;
    const steps = document.querySelectorAll('.wizard-step');
    const progressSteps = document.querySelectorAll('.progress-step');
    const progressBar = document.getElementById('progressBar');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('registerForm');

    // Проверка ошибок валидации и переход к нужному шагу
    <?php if($errors->any()): ?>
        <?php if($errors->has('name')): ?>
            goToStep(2);
        <?php elseif($errors->has('phone') || $errors->has('email')): ?>
            goToStep(3);
        <?php elseif($errors->has('password') || $errors->has('terms')): ?>
            goToStep(4);
        <?php endif; ?>
    <?php endif; ?>

    function updateProgress() {
        const progress = (currentStep / totalSteps) * 100;
        progressBar.style.width = progress + '%';
        
        progressSteps.forEach((step, index) => {
            const stepNumber = index + 1;
            if (stepNumber < currentStep) {
                step.classList.add('completed');
                step.classList.remove('active');
                step.innerHTML = '<i class="bi bi-check-lg"></i>';
            } else if (stepNumber === currentStep) {
                step.classList.add('active');
                step.classList.remove('completed');
                step.textContent = stepNumber;
            } else {
                step.classList.remove('active', 'completed');
                step.textContent = stepNumber;
            }
        });
    }

    function goToStep(step) {
        if (step < 1 || step > totalSteps) return;
        
        // Скрыть все шаги
        steps.forEach(s => s.classList.remove('active'));
        
        // Показать нужный шаг
        const targetStep = document.querySelector(`.wizard-step[data-step="${step}"]`);
        if (targetStep) {
            targetStep.classList.add('active');
        }
        
        currentStep = step;
        updateProgress();
        
        // Управление кнопками
        const loginLink = document.getElementById('loginLink');
        prevBtn.style.display = currentStep === 1 ? 'none' : 'flex';
        nextBtn.style.display = currentStep === totalSteps ? 'none' : 'flex';
        submitBtn.style.display = currentStep === totalSteps ? 'flex' : 'none';
        if (loginLink) {
            loginLink.style.display = currentStep === 1 ? 'flex' : 'none';
        }
        
        // Фокус на первом поле
        setTimeout(() => {
            const firstInput = targetStep.querySelector('input');
            if (firstInput && currentStep > 1) {
                firstInput.focus();
            }
        }, 100);
    }

    function validateStep(step) {
        if (step === 1) {
            return true; // Приветственный экран
        } else if (step === 2) {
            const nameInput = document.getElementById('name');
            if (!nameInput.value.trim()) {
                nameInput.focus();
                alert('Пожалуйста, введите ваше имя');
                return false;
            }
        } else if (step === 3) {
            const phoneInput = document.getElementById('phone');
            if (!phoneInput.value.trim()) {
                phoneInput.focus();
                alert('Пожалуйста, введите номер телефона');
                return false;
            }
        }
        return true;
    }

    nextBtn.addEventListener('click', function() {
        if (validateStep(currentStep)) {
            goToStep(currentStep + 1);
        }
    });

    prevBtn.addEventListener('click', function() {
        goToStep(currentStep - 1);
    });

    // Enter для перехода на следующий шаг
    form.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && currentStep < totalSteps) {
            e.preventDefault();
            if (validateStep(currentStep)) {
                goToStep(currentStep + 1);
            }
        }
    });

    // Инициализация
    updateProgress();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views\auth\register.blade.php ENDPATH**/ ?>