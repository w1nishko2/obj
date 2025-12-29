<!-- Баннер для подписки на уведомления (мобильные устройства) -->
<div id="mobilePushBanner" class="mobile-push-banner" style="display: none;">
    <div class="mobile-push-content">
        <div class="mobile-push-icon">
            <i class="bi bi-bell-fill"></i>
        </div>
        <div class="mobile-push-text">
            <strong>Включите уведомления</strong>
            <small>Получайте важные обновления о проектах</small>
        </div>
        <button id="mobilePushAllow" class="btn-push-enable">
            <i class="bi bi-check-circle-fill"></i>
            <span>Включить</span>
        </button>
        <button id="mobilePushClose" class="btn-push-close">
            <i class="bi bi-x"></i>
        </button>
    </div>
</div>

<style>
    .mobile-push-banner {
        position: fixed;
        bottom: 20px;
        left: 20px;
        right: 20px;
        background: white;
        color: #0a0a0a;
        padding: 16px;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.08);
        z-index: 9999;
        animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e0e0e0;
    }

    .mobile-push-content {
        display: grid;
        grid-template-columns: auto 1fr auto auto;
        align-items: center;
        gap: 12px;
    }

    .mobile-push-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #6f6f6f 0%, #4a4a4a 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        flex-shrink: 0;
    }

    .mobile-push-text {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }

    .mobile-push-text strong {
        font-size: 15px;
        font-weight: 600;
        color: #0a0a0a;
    }

    .mobile-push-text small {
        font-size: 13px;
        color: #4a4a4a;
        line-height: 1.3;
    }

    .btn-push-enable {
        background: #6ba97f;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 16px;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(107, 169, 127, 0.3);
    }

    .btn-push-enable:hover {
        background: #5a9170;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(107, 169, 127, 0.4);
    }

    .btn-push-enable:active {
        transform: translateY(0);
    }

    .btn-push-enable:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .btn-push-close {
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        color: #4a4a4a;
        border-radius: 8px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 18px;
    }

    .btn-push-close:hover {
        background: #e0e0e0;
        color: #0a0a0a;
    }

    @keyframes slideUp {
        from {
            transform: translateY(120%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes slideDown {
        from {
            transform: translateY(0);
            opacity: 1;
        }
        to {
            transform: translateY(120%);
            opacity: 0;
        }
    }

    /* Мобильные устройства < 400px */
    @media (max-width: 400px) {
        .mobile-push-banner {
            bottom: 16px;
            left: 12px;
            right: 12px;
            padding: 14px;
        }

        .mobile-push-content {
            grid-template-columns: auto 1fr;
            grid-template-rows: auto auto;
            gap: 10px;
        }

        .mobile-push-icon {
            width: 44px;
            height: 44px;
            font-size: 22px;
            grid-row: 1 / 3;
        }

        .mobile-push-text {
            grid-column: 2;
            grid-row: 1;
        }

        .mobile-push-text strong {
            font-size: 14px;
        }

        .mobile-push-text small {
            font-size: 12px;
        }

        .btn-push-enable {
            grid-column: 2;
            grid-row: 2;
            justify-content: center;
            padding: 10px 14px;
            font-size: 13px;
        }

        .btn-push-close {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 28px;
            height: 28px;
            font-size: 16px;
        }
    }

    /* Очень маленькие экраны */
    @media (max-width: 360px) {
        .mobile-push-banner {
            bottom: 12px;
            left: 8px;
            right: 8px;
            padding: 12px;
        }

        .mobile-push-icon {
            width: 40px;
            height: 40px;
            font-size: 20px;
            border-radius: 10px;
        }

        .btn-push-enable span {
            display: inline;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const banner = document.getElementById('mobilePushBanner');
    const allowBtn = document.getElementById('mobilePushAllow');
    const closeBtn = document.getElementById('mobilePushClose');

    console.log('[Mobile Banner] Initializing...');

    // Проверяем поддержку уведомлений
    if (!('Notification' in window)) {
        console.log('[Mobile Banner] Notifications not supported');
        return;
    }

    // Функция показа баннера
    function showBanner() {
        setTimeout(() => {
            console.log('[Mobile Banner] Showing banner');
            banner.style.display = 'block';
        }, 2000);
    }

    // Проверяем статус уведомлений
    const permission = Notification.permission;
    console.log('[Mobile Banner] Permission:', permission);

    // Если нет разрешения - показываем баннер
    if (permission === 'default') {
        console.log('[Mobile Banner] No permission yet, showing banner');
        showBanner();
        return;
    }

    // Если разрешение есть, но подписки нет - проверяем и показываем
    if (permission === 'granted') {
        console.log('[Mobile Banner] Permission granted, checking subscription...');
        
        // Проверяем подписку асинхронно
        setTimeout(async () => {
            try {
                if (typeof WebPushManager !== 'undefined') {
                    const pushManager = new WebPushManager();
                    await pushManager.init();
                    const subscription = await pushManager.checkSubscription();
                    
                    if (subscription) {
                        // Проверяем на сервере
                        const response = await fetch('/push/subscriptions');
                        const data = await response.json();
                        
                        if (data.success && data.subscriptions && data.subscriptions.length > 0) {
                            console.log('[Mobile Banner] Active subscription found, hiding banner');
                            return;
                        }
                    }
                    
                    console.log('[Mobile Banner] No active subscription, showing banner');
                    banner.style.display = 'block';
                } else {
                    console.log('[Mobile Banner] WebPushManager not found, showing banner');
                    banner.style.display = 'block';
                }
            } catch (error) {
                console.error('[Mobile Banner] Error checking subscription:', error);
                banner.style.display = 'block';
            }
        }, 2000);
        
        return;
    }

    // Если отклонено - не показываем
    if (permission === 'denied') {
        console.log('[Mobile Banner] Permission denied, not showing banner');
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
                        }, 300);
                    }, 1500);
                } else {
                    throw new Error('WebPushManager not found');
                }
            } else {
                alert('Разрешение отклонено. Включите уведомления в настройках браузера/приложения.');
                banner.style.display = 'none';
            }
        } catch (error) {
            console.error('[Mobile Banner] Error:', error);
            alert('Ошибка: ' + error.message + '\n\nПопробуйте перезагрузить приложение.');
            allowBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Включить';
            allowBtn.disabled = false;
        }
    });

    // Обработчик кнопки "Закрыть"
    closeBtn.addEventListener('click', function() {
        banner.style.animation = 'slideDown 0.3s ease-in';
        setTimeout(() => {
            banner.style.display = 'none';
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
<?php /**PATH C:\OSPanel\domains\work\resources\views/components/mobile-push-banner.blade.php ENDPATH**/ ?>