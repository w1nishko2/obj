<!-- Компонент кнопки установки PWA -->
<div id="pwa-install-container"></div>

<!-- Модальное окно инструкции для iOS -->
<div class="modal fade" id="iosInstallModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-apple text-dark me-2"></i>
                    Установка на iPhone/iPad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="ios-instruction">
                    <div class="instruction-step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <p class="mb-1 fw-bold">Откройте меню Safari</p>
                            <p class="text-muted small mb-0">
                                Нажмите на кнопку "Поделиться" 
                                <i class="bi bi-box-arrow-up" style="font-size: 1.2rem; vertical-align: middle;"></i>
                                внизу экрана
                            </p>
                        </div>
                    </div>
                    
                    <div class="instruction-step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <p class="mb-1 fw-bold">Найдите пункт меню</p>
                            <p class="text-muted small mb-0">
                                Прокрутите вниз и выберите 
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-plus-square"></i> 
                                    На экран "Домой"
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="instruction-step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <p class="mb-1 fw-bold">Подтвердите установку</p>
                            <p class="text-muted small mb-0">
                                Нажмите "Добавить" в правом верхнем углу
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3 mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    <small>После установки приложение будет доступно на главном экране вашего устройства</small>
                </div>
                
                <div class="text-center mt-3">
                    <a href="/ios-install.html" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-book"></i>
                        Подробная инструкция
                    </a>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">
                    Понятно
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.pwa-install-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    background: linear-gradient(135deg, #6ba97f 0%, #5a9170 100%);
    color: white;
    border: none;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    font-weight: 600;
    font-size: 24px;
    box-shadow: 0 4px 16px rgba(107, 169, 127, 0.4);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    animation: slideInUp 0.5s ease;
}

.pwa-install-button:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 24px rgba(107, 169, 127, 0.5);
    background: linear-gradient(135deg, #5a9170 0%, #498862 100%);
}

.pwa-install-button:active {
    transform: scale(0.95);
}

.pwa-install-button i {
    font-size: 24px;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(50px) scale(0.8);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.ios-instruction {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.instruction-step {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.step-number {
    width: 32px;
    height: 32px;
    min-width: 32px;
    background: linear-gradient(135deg, #6ba97f 0%, #5a9170 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
}

.step-content {
    flex: 1;
}

@media (max-width: 768px) {
    .pwa-install-button {
        bottom: 16px;
        right: 16px;
        width: 56px;
        height: 56px;
        font-size: 22px;
    }
    
    .pwa-install-button i {
        font-size: 22px;
    }
}

@media (max-width: 400px) {
    .pwa-install-button {
        bottom: 14px;
        right: 14px;
        width: 52px;
        height: 52px;
        font-size: 20px;
    }
    
    .pwa-install-button i {
        font-size: 20px;
    }
}

/* Анимация пульсации для привлечения внимания */
@keyframes pulse {
    0%, 100% {
        box-shadow: 0 4px 16px rgba(107, 169, 127, 0.4);
    }
    50% {
        box-shadow: 0 6px 24px rgba(107, 169, 127, 0.6);
    }
}

.pwa-install-button.pulse {
    animation: pulse 3s infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Проверяем, не установлено ли приложение уже
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches 
                      || window.navigator.standalone 
                      || document.referrer.includes('android-app://');
    
    // Проверяем, не скрывал ли пользователь кнопку ранее
    const installButtonHidden = localStorage.getItem('pwa-install-hidden');
    
    if (isStandalone) {
        console.log('PWA уже установлено');
        return;
    }
    
    if (installButtonHidden === 'true') {
        console.log('Пользователь скрыл кнопку установки');
        return;
    }
    
    // Определяем устройство
    const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    const isAndroid = /Android/.test(navigator.userAgent);
    
    let deferredPrompt;
    const container = document.getElementById('pwa-install-container');
    
    // Для Android и других поддерживающих beforeinstallprompt
    if (!isIOS) {
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            showInstallButton(false);
        });
        
        // Показываем кнопку для всех платформ (Android, Desktop)
        setTimeout(() => {
            if (!deferredPrompt) {
                showInstallButton(false);
            }
        }, 2000);
    } else {
        // Для iOS показываем кнопку с инструкцией
        showInstallButton(true);
    }
    
    function showInstallButton(isIOSDevice) {
        const button = document.createElement('button');
        button.className = 'pwa-install-button pulse';
        button.innerHTML = `<i class="bi bi-download"></i>`;
        
        button.addEventListener('click', async () => {
            if (isIOSDevice) {
                // Показываем инструкцию для iOS
                const iosModal = new bootstrap.Modal(document.getElementById('iosInstallModal'));
                iosModal.show();
            } else if (deferredPrompt) {
                // Показываем стандартный промпт для Android
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                
                if (outcome === 'accepted') {
                    console.log('Пользователь принял установку');
                    button.remove();
                } else {
                    console.log('Пользователь отклонил установку');
                    // Добавляем возможность скрыть кнопку
                    button.classList.remove('pulse');
                }
                
                deferredPrompt = null;
            }
        });
        
        // Добавляем кнопку закрытия (для тех, кто не хочет устанавливать)
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '<i class="bi bi-x"></i>';
        closeBtn.style.cssText = `
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #ef4444;
            color: white;
            border: 2px solid white;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        `;
        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            button.remove();
            localStorage.setItem('pwa-install-hidden', 'true');
        });
        
        button.style.position = 'relative';
        button.appendChild(closeBtn);
        
        container.appendChild(button);
        
        // Убираем пульсацию через 10 секунд
        setTimeout(() => {
            button.classList.remove('pulse');
        }, 10000);
    }
    
    // Отслеживаем успешную установку
    window.addEventListener('appinstalled', () => {
        console.log('PWA успешно установлено!');
        const button = document.querySelector('.pwa-install-button');
        if (button) {
            button.remove();
        }
        
        // Показываем уведомление
        if (typeof showNotification === 'function') {
            showNotification('Приложение успешно установлено!', 'success');
        }
    });
});
</script>
