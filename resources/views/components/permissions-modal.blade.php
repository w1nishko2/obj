<!-- Модальное окно для запроса разрешений -->
<div id="permissionsModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content permissions-modal-content">
            <div class="modal-header permissions-modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-bell-fill me-2"></i>Включить уведомления
                </h5>
            </div>

            <div class="modal-body">
                <p class="mb-3">
                    Получайте уведомления о новых задачах, комментариях и обновлениях проектов.
                </p>

                <div class="alert alert-light border permissions-alert">
                    <i class="bi bi-info-circle me-2 text-secondary"></i>
                    <span>Уведомления приходят даже когда браузер закрыт.</span>
                </div>

                <div id="permissionStatus" class="mt-3"></div>
            </div>

            <div class="modal-footer border-0 permissions-modal-footer">
                <button type="button" id="skipPermissionsBtn" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Позже
                </button>
                <button type="button" id="allowPermissionsBtn" class="btn btn-permissions-primary">
                    <i class="bi bi-check-circle-fill me-1"></i> Включить
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .permissions-modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    }

    .permissions-modal-header {
        border-bottom: 1px solid #e0e0e0;
        padding: 20px 24px;
    }

    .permissions-modal-header .modal-title {
        font-weight: 600;
        font-size: 18px;
        color: #0a0a0a;
    }

    .permissions-modal-header .modal-title i {
        color: #6ba97f;
    }

    .permissions-modal-footer {
        padding: 16px 24px;
        gap: 12px;
    }

    .permissions-alert {
        background: #f8f9fa;
        border-color: #e0e0e0 !important;
        color: #4a4a4a;
        border-radius: 8px;
        padding: 12px 16px;
    }

    .btn-permissions-primary {
        background: linear-gradient(135deg, #6ba97f 0%, #5a9170 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-permissions-primary:hover {
        background: linear-gradient(135deg, #5a9170 0%, #498862 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(107, 169, 127, 0.3);
    }

    .btn-permissions-primary:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .btn-outline-secondary {
        border-color: #e0e0e0;
        color: #4a4a4a;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
    }

    .btn-outline-secondary:hover {
        background: #f8f9fa;
        border-color: #d0d0d0;
        color: #0a0a0a;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('permissionsModal');
    const allowBtn = document.getElementById('allowPermissionsBtn');
    const skipBtn = document.getElementById('skipPermissionsBtn');

    // Проверяем, показывали ли уже это окно
    const permissionsAsked = localStorage.getItem('permissionsAsked');
    
    // Проверяем текущий статус разрешений
    const hasPermission = 'Notification' in window && Notification.permission === 'granted';
    const isBlocked = 'Notification' in window && Notification.permission === 'denied';

    // Показываем модалку только если:
    // 1. Ещё не показывали И
    // 2. Разрешения нет
    if (!permissionsAsked && !hasPermission) {
        setTimeout(() => {
            const bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
            
            // Показываем статус если уведомления заблокированы
            if (isBlocked) {
                document.getElementById('permissionStatus').innerHTML = `
                    <div class="alert alert-warning">
                        <strong>⚠️ Уведомления заблокированы</strong><br>
                        <small>Разблокируйте в адресной строке браузера (иконка замка/колокольчика)</small>
                    </div>
                `;
            }
        }, 2000);
    }

    // Обработчик кнопки "Включить"
    allowBtn.addEventListener('click', async function() {
        const originalText = allowBtn.innerHTML;
        allowBtn.disabled = true;
        allowBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Подключение...';

        try {
            // Проверяем поддержку
            if (!('Notification' in window)) {
                throw new Error('Ваш браузер не поддерживает уведомления');
            }

            if (!('serviceWorker' in navigator)) {
                throw new Error('Ваш браузер не поддерживает Service Worker');
            }

            // Проверяем текущий статус
            console.log('Current permission:', Notification.permission);

            // Если уже заблокировано - показываем инструкцию
            if (Notification.permission === 'denied') {
                throw new Error('Уведомления заблокированы.\n\nРазблокируйте их:\n1. Кликните на иконку замка слева от адреса сайта\n2. Найдите "Уведомления"\n3. Выберите "Разрешить"\n4. Обновите страницу (F5)');
            }

            // Запрашиваем разрешение у браузера
            const permission = await Notification.requestPermission();
            console.log('New permission:', permission);

            if (permission === 'granted') {
                // Инициализируем Web Push Manager
                if (typeof WebPushManager !== 'undefined') {
                    const pushManager = new WebPushManager();
                    const initialized = await pushManager.init();
                    
                    if (initialized) {
                        await pushManager.subscribe();
                        allowBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Готово!';
                        allowBtn.classList.remove('btn-primary');
                        allowBtn.classList.add('btn-success');
                        
                        localStorage.setItem('permissionsAsked', 'true');
                        
                        setTimeout(() => {
                            const bootstrapModal = bootstrap.Modal.getInstance(modal);
                            bootstrapModal.hide();
                        }, 1000);
                    } else {
                        throw new Error('Не удалось инициализировать Web Push. Проверьте, не блокирует ли антивирус Service Worker.');
                    }
                } else {
                    throw new Error('WebPushManager не найден');
                }
            } else if (permission === 'denied') {
                throw new Error('Разрешение отклонено. Сбросьте разрешения сайта в браузере.');
            } else {
                throw new Error('Разрешение не получено. Попробуйте снова.');
            }

        } catch (error) {
            console.error('Error requesting permissions:', error);
            
            // Показываем ошибку
            const statusDiv = document.getElementById('permissionStatus');
            statusDiv.innerHTML = `
                <div class="alert alert-danger">
                    <strong>❌ Ошибка</strong><br>
                    <small style="white-space: pre-line;">${error.message}</small>
                </div>
            `;
            
            // Восстанавливаем кнопку
            allowBtn.innerHTML = originalText;
            allowBtn.disabled = false;
            
            // Помечаем что пытались
            localStorage.setItem('permissionsAsked', 'true');
        }
    });

    // Обработчик кнопки "Позже"
    skipBtn.addEventListener('click', function() {
        localStorage.setItem('permissionsAsked', 'true');
    });
});
</script>
