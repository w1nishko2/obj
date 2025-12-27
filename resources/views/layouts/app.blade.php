<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2563eb">
    
    <!-- Мета-теги для мобильных устройств -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
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

    <title>{{ config('app.name', 'Laravel') }}</title>

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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="minimal-nav">
            <div class="minimal-container">
                <a class="minimal-logo" href="{{ url('/home') }}">
                    <i class="bi bi-building"></i>
                    {{ config('app.name', 'Laravel') }}
                </a>
                
                @auth
                <div class="minimal-user-menu">
                    <button class="user-avatar" 
                            type="button" 
                            id="userDropdown" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </button>
                    
                    <ul class="dropdown-menu dropdown-menu-end minimal-dropdown" 
                        aria-labelledby="userDropdown">
                        <li class="dropdown-header" style="cursor: pointer;" onclick="window.location='{{ route('profile') }}'">
                            <div class="user-info">
                                <div class="user-name">{{ Auth::user()->name }}</div>
                                <div class="user-email">{{ Auth::user()->email }}</div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile') }}">
                                <i class="bi bi-person-circle"></i>
                                Профиль
                            </a>
                        </li>
                        @if(Auth::user()->isForeman())
                        <li>
                            <a class="dropdown-item" href="{{ route('prices.index') }}">
                                <i class="bi bi-currency-dollar"></i>
                                Прайсы
                            </a>
                        </li>
                        @endif
                        <li>
                            <a class="dropdown-item" href="{{ route('pricing.index') }}">
                                <i class="bi bi-star"></i>
                                Тарифы и подписка
                            </a>
                        </li>
                        @if(Auth::user()->isForeman())
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#tutorialModal">
                                <i class="bi bi-play-circle"></i>
                                Инструкция по работе
                            </a>
                        </li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('privacy-policy') }}">
                                <i class="bi bi-shield-check"></i>
                                Политика конфиденциальности
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('terms-of-service') }}">
                                <i class="bi bi-file-earmark-text"></i>
                                Оферта
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('requisites') }}">
                                <i class="bi bi-bank"></i>
                                Реквизиты
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="https://t.me/objectplus" target="_blank" rel="noopener noreferrer">
                                <i class="bi bi-telegram"></i>
                                Поддержка в Telegram
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i>
                                Выход
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <div class="minimal-auth-buttons">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="minimal-btn minimal-btn-ghost">Вход</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="minimal-btn minimal-btn-primary">Регистрация</a>
                    @endif
                </div>
                @endauth
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
        
        <!-- PWA Install Button Component -->
        @auth
            @include('components.pwa-install')
        @endauth
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
    @auth
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

            // Если ещё не спрашивали - показываем браузерный диалог сразу (только для десктопа)
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
                            (window.matchMedia && window.matchMedia('(max-width: 768px)').matches);
            
            if (!isMobile && Notification.permission === 'default') {
                console.log('⏳ Запрашиваем разрешение у браузера (десктоп)...');
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
                
                // Показываем баннер пользователю (только для десктопа)
                if (!isMobile) {
                    const banner = document.createElement('div');
                    banner.style.cssText = 'position: fixed; top: 60px; left: 50%; transform: translateX(-50%); background: #ffc107; color: #000; padding: 15px 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.3); z-index: 10000; max-width: 90%; text-align: center;';
                    banner.innerHTML = `
                        <strong>⚠️ Уведомления заблокированы</strong><br>
                        <small>Кликните на замок в адресной строке → Разрешить уведомления → F5</small>
                        <button onclick="this.parentElement.remove()" style="margin-left: 15px; background: #fff; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer;">Закрыть</button>
                    `;
                    document.body.appendChild(banner);
                }
            }
        });
    </script>
    @endauth

    <!-- Мобильный баннер для подписки на уведомления -->
    @auth
        @include('components.mobile-push-banner')
    @endauth

    <!-- Модальное окно с инструкциями (доступно для прорабов) -->
    @auth
        @if(Auth::user()->isForeman())
        <div class="modal fade" id="tutorialModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-fullscreen m-0">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-2 position-sticky top-0 bg-white" style="z-index: 1000;">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-0 overflow-auto" style="padding-bottom: 100px;">
                        <div class="d-flex justify-content-center py-4">
                            <div class="wizard-container text-center" style="max-width: 600px; width: 100%; padding: 1rem;">
                                <div class="mb-4">
                                    <i class="bi bi-lightbulb" style="font-size: 4rem; color: #a70000;"></i>
                                </div>
                                <h2 class="mb-3">Быстрая подсказка</h2>
                                <p class="text-muted mb-4">Узнайте основные возможности работы с проектом</p>
                                
                                <!-- Первая подсказка -->
                                <div class="tutorial-item mb-4 text-start">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="tutorial-number me-3" style="background: #a70000; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">
                                            1
                                        </div>
                                        <div>
                                            <h5 class="mb-2">Удаление элементов</h5>
                                            <p class="text-muted mb-3">Если хотите удалить что-либо, нажмите и подержите элемент</p>
                                        </div>
                                    </div>
                                    <div class="tutorial-image-placeholder" style="background: #f8f9fa; border-radius: 8px; padding: 2rem 1rem; text-align: center; border: 2px dashed #dee2e6;">
                                        <img src="/images/tutorial-delete.png" alt="Удаление элементов" style="max-width: 100%; height: auto; border-radius: 8px; display: none;" onerror="this.style.display='none'" onload="this.style.display='block'; this.parentElement.querySelector('.placeholder-text').style.display='none';">
                                        <div class="placeholder-text text-muted">
                                            <i class="bi bi-hand-index-thumb" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                                            <small>Место для изображения tutorial-delete.png</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Видео инструкция -->
                                <div class="tutorial-item mb-4 text-start">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="tutorial-number me-3" style="background: #a70000; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">
                                            2
                                        </div>
                                        <div>
                                            <h5 class="mb-2">Видео инструкция</h5>
                                            <p class="text-muted mb-3">Подробное видео о работе с проектами (7 минут)</p>
                                        </div>
                                    </div>
                                    <div class="tutorial-video-container" style="position: relative; width: 100%; max-width: 360px; margin: 0 auto; aspect-ratio: 9/16; background: #000; border-radius: 12px; overflow: hidden;">
                                        <video 
                                            id="tutorialVideo"
                                            controls 
                                            playsinline
                                            preload="metadata"
                                            style="width: 100%; height: 100%; object-fit: contain;"
                                            poster="/images/tutorial-video-poster.jpg">
                                            <source src="/videos/instruction.mp4" type="video/mp4">
                                            <p class="text-muted p-3">Ваш браузер не поддерживает воспроизведение видео. <a href="/videos/instruction.mp4" download>Скачайте видео</a></p>
                                        </video>
                                        <!-- Индикатор загрузки -->
                                        <div id="videoLoader" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: none;">
                                            <div class="spinner-border text-light" role="status">
                                                <span class="visually-hidden">Загрузка...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle"></i>
                                            Видео загружается потоково - страница не зависнет (93 МБ)
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        // Остановка видео при закрытии модалки
        document.addEventListener('DOMContentLoaded', function() {
            const tutorialModal = document.getElementById('tutorialModal');
            const tutorialVideo = document.getElementById('tutorialVideo');
            
            if (tutorialModal && tutorialVideo) {
                tutorialModal.addEventListener('hidden.bs.modal', function() {
                    tutorialVideo.pause();
                    tutorialVideo.currentTime = 0;
                });
                
                // Показываем loader при загрузке видео
                const videoLoader = document.getElementById('videoLoader');
                if (videoLoader) {
                    tutorialVideo.addEventListener('loadstart', function() {
                        videoLoader.style.display = 'block';
                    });
                    tutorialVideo.addEventListener('canplay', function() {
                        videoLoader.style.display = 'none';
                    });
                }
            }
        });
        </script>
        @endif
    @endauth
</body>
</html>
