<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2563eb">
    
    <!-- Мета-теги для мобильных устройств -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="<?php echo e(config('app.name')); ?>">
    <meta name="format-detection" content="telephone=no">
    
    <!-- iOS Icons -->
    <link rel="apple-touch-icon" href="/images/icons/icon.svg">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/icons/icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/icons/icon.svg">
    <link rel="apple-touch-icon" sizes="167x167" href="/images/icons/icon.svg">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/images/icons/icon.svg">
    <link rel="icon" type="image/png" sizes="192x192" href="/images/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/images/icons/icon-512x512.png">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- IMask.js для маски телефона -->
    <script src="https://unpkg.com/imask@7.6.1/dist/imask.min.js"></script>

    <!-- Flatpickr для выбора даты -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_red.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body>
    <div id="app">
        <nav class="minimal-nav">
            <div class="minimal-container">
                <a class="minimal-logo" href="<?php echo e(url('/home')); ?>">
                    <i class="bi bi-building"></i>
                    <?php echo e(config('app.name', 'Laravel')); ?>

                </a>
                
                <?php if(auth()->guard()->check()): ?>
                <div class="minimal-user-menu">
                    <button class="user-avatar" 
                            type="button" 
                            id="userDropdown" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                        <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                    </button>
                    
                    <ul class="dropdown-menu dropdown-menu-end minimal-dropdown" 
                        aria-labelledby="userDropdown">
                        <li class="dropdown-header" style="cursor: pointer;" onclick="window.location='<?php echo e(route('profile')); ?>'">
                            <div class="user-info">
                                <div class="user-name"><?php echo e(Auth::user()->name); ?></div>
                                <div class="user-email"><?php echo e(Auth::user()->email); ?></div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('profile')); ?>">
                                <i class="bi bi-person-circle"></i>
                                Профиль
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('pricing.index')); ?>">
                                <i class="bi bi-star"></i>
                                Тарифы и подписка
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('privacy-policy')); ?>">
                                <i class="bi bi-shield-check"></i>
                                Политика конфиденциальности
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('terms-of-service')); ?>">
                                <i class="bi bi-file-earmark-text"></i>
                                Оферта
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('requisites')); ?>">
                                <i class="bi bi-bank"></i>
                                Реквизиты
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i>
                                Выход
                            </a>
                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                <?php echo csrf_field(); ?>
                            </form>
                        </li>
                    </ul>
                </div>
                <?php else: ?>
                <div class="minimal-auth-buttons">
                    <?php if(Route::has('login')): ?>
                        <a href="<?php echo e(route('login')); ?>" class="minimal-btn minimal-btn-ghost">Вход</a>
                    <?php endif; ?>
                    <?php if(Route::has('register')): ?>
                        <a href="<?php echo e(route('register')); ?>" class="minimal-btn minimal-btn-primary">Регистрация</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </nav>

        <main>
            <?php echo $__env->yieldContent('content'); ?>
        </main>
        
        <!-- PWA Install Button Component -->
        <?php if(auth()->guard()->check()): ?>
            <?php echo $__env->make('components.pwa-install', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ===== МАСКИ ТЕЛЕФОНОВ =====
            const phoneInputs = document.querySelectorAll('input[name="phone"], input[type="tel"], .phone-mask');
            
            phoneInputs.forEach(function(input) {
                IMask(input, {
                    mask: '+{7} (000) 000-00-00',
                    lazy: false,
                    placeholderChar: '_'
                });
            });

            // ===== FLATPICKR ДЛЯ ОДИНОЧНЫХ ДАТ =====
            const singleDateInputs = document.querySelectorAll('.flatpickr-single, input[type="date"]:not(.no-picker)');
            
            singleDateInputs.forEach(function(input) {
                // Получаем текущее значение если есть
                const currentValue = input.value;
                let parsedDate = null;
                
                // Парсим дату в формате Y-m-d или d.m.Y
                if (currentValue) {
                    if (currentValue.includes('-')) {
                        // Формат Y-m-d
                        parsedDate = currentValue;
                    } else if (currentValue.includes('.')) {
                        // Формат d.m.Y - конвертируем в Y-m-d
                        const parts = currentValue.split('.');
                        if (parts.length === 3) {
                            parsedDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
                        }
                    }
                }
                
                // Меняем тип на text для Flatpickr
                input.type = 'text';
                input.classList.add('flatpickr-initialized');
                input.setAttribute('readonly', 'readonly');
                
                const fp = flatpickr(input, {
                    locale: 'ru',
                    dateFormat: 'd.m.Y',
                    defaultDate: parsedDate,
                    onChange: function(selectedDates, dateStr, instance) {
                        if (selectedDates.length > 0) {
                            const date = selectedDates[0];
                            const year = date.getFullYear();
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const day = String(date.getDate()).padStart(2, '0');
                            // Сохраняем формат Y-m-d для Laravel
                            input.setAttribute('data-value', `${year}-${month}-${day}`);
                        }
                    }
                });
                
                // При отправке формы конвертируем формат для валидации
                const form = input.closest('form');
                if (form && !form.hasAttribute('data-flatpickr-handler')) {
                    form.setAttribute('data-flatpickr-handler', 'true');
                    form.addEventListener('submit', function(e) {
                        singleDateInputs.forEach(function(dateInput) {
                            const dataValue = dateInput.getAttribute('data-value');
                            if (dataValue) {
                                dateInput.value = dataValue;
                            }
                        });
                    });
                }
            });
        });
    </script>

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then((registration) => {
                        console.log('ServiceWorker registration successful:', registration.scope);
                    })
                    .catch((error) => {
                        console.log('ServiceWorker registration failed:', error);
                    });
            });
        }
    </script>

    <!-- Web Push Manager -->
    <script src="/js/webpush-manager.js"></script>

    <!-- Обработчик звука уведомлений -->
    <script>
        // Слушаем сообщения от Service Worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.addEventListener('message', (event) => {
                if (event.data && event.data.type === 'PLAY_NOTIFICATION_SOUND') {
                    console.log('🔔 Воспроизведение звука уведомления');
                    
                    // Воспроизводим стандартный звук уведомления браузера
                    // Создаём короткий звуковой сигнал
                    try {
                        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                        const oscillator = audioContext.createOscillator();
                        const gainNode = audioContext.createGain();
                        
                        oscillator.connect(gainNode);
                        gainNode.connect(audioContext.destination);
                        
                        // Частота звука (Hz) - приятный звук уведомления
                        oscillator.frequency.value = 800;
                        oscillator.type = 'sine';
                        
                        // Громкость
                        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
                        
                        // Воспроизводим 0.5 секунды
                        oscillator.start(audioContext.currentTime);
                        oscillator.stop(audioContext.currentTime + 0.5);
                    } catch (error) {
                        console.warn('Не удалось воспроизвести звук:', error);
                    }
                }
            });
        }
    </script>

    <!-- Автоматический запрос разрешения на уведомления -->
    <?php if(auth()->guard()->check()): ?>
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            // Проверяем поддержку
            if (!('Notification' in window)) {
                console.warn('❌ Браузер не поддерживает уведомления');
                return;
            }

            if (!('serviceWorker' in navigator)) {
                console.warn('❌ Браузер не поддерживает Service Worker');
                return;
            }

            // Показываем текущий статус
            console.log('🔔 Статус уведомлений:', Notification.permission);
            console.log('📍 Для работы уведомлений нужно: Notification.permission === "granted"');

            // Если разрешение уже есть - подписываемся
            if (Notification.permission === 'granted') {
                console.log('✅ Разрешение уже есть, подписываемся...');
                try {
                    const pushManager = new WebPushManager();
                    await pushManager.init();
                    const subscription = await pushManager.checkSubscription();
                    if (!subscription) {
                        await pushManager.subscribe();
                        console.log('✅ Подписка на уведомления активирована');
                    } else {
                        console.log('✅ Подписка уже существует');
                    }
                } catch (error) {
                    console.error('❌ Ошибка подписки:', error);
                }
                return;
            }

            // Если ещё не спрашивали - показываем браузерный диалог сразу
            if (Notification.permission === 'default') {
                console.log('⏳ Запрашиваем разрешение у браузера...');
                try {
                    const permission = await Notification.requestPermission();
                    console.log('📝 Результат запроса:', permission);
                    
                    if (permission === 'granted') {
                        console.log('✅ Разрешение получено! Подписываемся...');
                        const pushManager = new WebPushManager();
                        await pushManager.init();
                        await pushManager.subscribe();
                        console.log('✅ Уведомления включены!');
                    } else if (permission === 'denied') {
                        console.warn('❌ Разрешение отклонено.');
                    } else {
                        console.log('ℹ️ Диалог закрыт без выбора.');
                    }
                } catch (error) {
                    console.error('❌ Ошибка запроса разрешений:', error);
                }
            }

            // Если заблокировано - показываем подсказку
            if (Notification.permission === 'denied') {
                console.warn('⚠️ УВЕДОМЛЕНИЯ ЗАБЛОКИРОВАНЫ');
                console.warn('📌 Как разблокировать:');
                console.warn('1. Кликните на ЗАМОК слева от адреса сайта');
                console.warn('2. Найдите "Уведомления"');
                console.warn('3. Выберите "Разрешить"');
                console.warn('4. Обновите страницу (F5)');
                
                // Показываем баннер пользователю
                const banner = document.createElement('div');
                banner.style.cssText = 'position: fixed; top: 60px; left: 50%; transform: translateX(-50%); background: #ffc107; color: #000; padding: 15px 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.3); z-index: 10000; max-width: 90%; text-align: center;';
                banner.innerHTML = `
                    <strong>⚠️ Уведомления заблокированы</strong><br>
                    <small>Кликните на замок в адресной строке → Разрешить уведомления → F5</small>
                    <button onclick="this.parentElement.remove()" style="margin-left: 15px; background: #fff; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer;">Закрыть</button>
                `;
                document.body.appendChild(banner);
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
<?php /**PATH C:\OSPanel\domains\work\resources\views/layouts/app.blade.php ENDPATH**/ ?>