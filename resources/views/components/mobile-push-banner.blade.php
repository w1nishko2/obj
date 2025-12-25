<!-- Баннер для подписки на уведомления (мобильные устройства) -->
<div id="mobilePushBanner" class="mobile-push-banner" style="display: none;">
    <div class="mobile-push-content">
        <div class="mobile-push-icon">
            <i class="bi bi-bell"></i>
        </div>
        <div class="mobile-push-text">
            <strong>Включите уведомления</strong>
            <small>Получайте обновления о задачах и проектах</small>
        </div>
        <div class="mobile-push-actions">
            <button id="mobilePushAllow" class="btn btn-primary btn-sm">
                <i class="bi bi-check-circle"></i> Включить
            </button>
            <button id="mobilePushClose" class="btn btn-link btn-sm text-muted">
                ✕
            </button>
        </div>
    </div>
</div>

<style>
    .mobile-push-banner {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        box-shadow: 0 -4px 12px rgba(0,0,0,0.3);
        z-index: 9999;
        animation: slideUp 0.3s ease-out;
    }

    .mobile-push-content {
        display: flex;
        align-items: center;
        gap: 12px;
        max-width: 600px;
        margin: 0 auto;
    }

    .mobile-push-icon {
        font-size: 32px;
        flex-shrink: 0;
    }

    .mobile-push-text {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .mobile-push-text strong {
        font-size: 15px;
    }

    .mobile-push-text small {
        font-size: 12px;
        opacity: 0.9;
    }

    .mobile-push-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }

    .mobile-push-banner .btn {
        white-space: nowrap;
    }

    .mobile-push-banner .btn-primary {
        background: white;
        color: #667eea;
        border: none;
        font-weight: bold;
    }

    .mobile-push-banner .btn-link {
        color: white;
        text-decoration: none;
        padding: 4px 8px;
    }

    @keyframes slideUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @media (max-width: 576px) {
        .mobile-push-content {
            flex-wrap: wrap;
        }
        
        .mobile-push-actions {
            width: 100%;
            justify-content: stretch;
        }

        .mobile-push-actions .btn {
            flex: 1;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    const banner = document.getElementById('mobilePushBanner');
    const allowBtn = document.getElementById('mobilePushAllow');
    const closeBtn = document.getElementById('mobilePushClose');

    // Функция определения мобильного устройства
    function isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
               (window.matchMedia && window.matchMedia('(max-width: 768px)').matches);
    }

    // Функция проверки PWA
    function isPWA() {
        return window.matchMedia('(display-mode: standalone)').matches ||
               window.navigator.standalone ||
               document.referrer.includes('android-app://');
    }

    // Проверяем нужно ли показывать баннер
    async function shouldShowBanner() {
        // Проверяем localStorage
        const bannerClosed = localStorage.getItem('mobilePushBannerClosed');
        if (bannerClosed === 'true') {
            return false;
        }

        // Проверяем разрешение браузера
        if (!('Notification' in window)) {
            console.log('[Mobile Banner] Notifications not supported');
            return false;
        }

        const permission = Notification.permission;
        console.log('[Mobile Banner] Permission:', permission);

        // Если уже разрешено - проверяем подписку в базе
        if (permission === 'granted') {
            try {
                if (typeof WebPushManager !== 'undefined') {
                    const pushManager = new WebPushManager();
                    await pushManager.init();
                    const subscription = await pushManager.checkSubscription();
                    
                    if (subscription) {
                        console.log('[Mobile Banner] Subscription exists, checking server');
                        // Проверяем на сервере
                        const response = await fetch('/push/subscriptions');
                        const data = await response.json();
                        
                        if (data.success && data.subscriptions && data.subscriptions.length > 0) {
                            console.log('[Mobile Banner] Server subscription found');
                            return false;
                        }
                        
                        console.log('[Mobile Banner] No server subscription, showing banner');
                        return true;
                    }
                }
            } catch (error) {
                console.error('[Mobile Banner] Error checking subscription:', error);
            }
        }

        // Показываем баннер если разрешения нет или default
        return permission === 'default' || permission === 'granted';
    }

    // Показываем баннер если нужно
    if (isMobileDevice() || isPWA()) {
        console.log('[Mobile Banner] Mobile/PWA detected');
        
        const show = await shouldShowBanner();
        console.log('[Mobile Banner] Should show:', show);
        
        if (show) {
            setTimeout(() => {
                banner.style.display = 'block';
            }, 2000);
        }
    }

    // Обработчик кнопки "Включить"
    allowBtn.addEventListener('click', async function() {
        allowBtn.disabled = true;
        allowBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Подключение...';

        try {
            // Запрашиваем разрешение
            const permission = await Notification.requestPermission();
            console.log('[Mobile Banner] Permission result:', permission);

            if (permission === 'granted') {
                // Инициализируем Web Push
                if (typeof WebPushManager !== 'undefined') {
                    const pushManager = new WebPushManager();
                    await pushManager.init();
                    
                    // Проверяем существующую подписку
                    let subscription = await pushManager.checkSubscription();
                    
                    if (!subscription) {
                        // Создаём новую подписку
                        subscription = await pushManager.subscribe();
                        console.log('[Mobile Banner] New subscription created');
                    } else {
                        // Переподписываемся (обновляем на сервере)
                        console.log('[Mobile Banner] Re-subscribing with existing subscription');
                        await pushManager.subscribe();
                    }

                    allowBtn.innerHTML = '<i class="bi bi-check-circle"></i> Готово!';
                    allowBtn.classList.remove('btn-primary');
                    allowBtn.classList.add('btn-success');

                    setTimeout(() => {
                        banner.style.animation = 'slideDown 0.3s ease-in';
                        setTimeout(() => {
                            banner.style.display = 'none';
                            localStorage.setItem('mobilePushBannerClosed', 'true');
                        }, 300);
                    }, 1500);
                } else {
                    throw new Error('WebPushManager not found');
                }
            } else {
                alert('Разрешение отклонено. Включите уведомления в настройках браузера/приложения.');
                banner.style.display = 'none';
                localStorage.setItem('mobilePushBannerClosed', 'true');
            }
        } catch (error) {
            console.error('[Mobile Banner] Error:', error);
            alert('Ошибка: ' + error.message + '\n\nПопробуйте перезагрузить приложение.');
            allowBtn.innerHTML = '<i class="bi bi-check-circle"></i> Включить';
            allowBtn.disabled = false;
        }
    });

    // Обработчик кнопки "Закрыть"
    closeBtn.addEventListener('click', function() {
        banner.style.animation = 'slideDown 0.3s ease-in';
        setTimeout(() => {
            banner.style.display = 'none';
            localStorage.setItem('mobilePushBannerClosed', 'true');
        }, 300);
    });
});

// Добавляем анимацию закрытия
const style = document.createElement('style');
style.textContent = `
    @keyframes slideDown {
        from {
            transform: translateY(0);
            opacity: 1;
        }
        to {
            transform: translateY(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
