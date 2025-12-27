<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Тестирование Push-уведомлений - Объект+</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .status-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .status-row:last-child {
            margin-bottom: 0;
        }

        .status-label {
            color: #666;
            font-weight: 500;
        }

        .status-value {
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 14px;
        }

        .status-success {
            background: #d4edda;
            color: #155724;
        }

        .status-error {
            background: #f8d7da;
            color: #721c24;
        }

        .status-warning {
            background: #fff3cd;
            color: #856404;
        }

        .status-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .button-group {
            display: grid;
            gap: 12px;
            margin-bottom: 20px;
        }

        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        button:active {
            transform: translateY(0);
        }

        button:disabled {
            background: #ccc;
            cursor: not-allowed;
            box-shadow: none;
        }

        button.secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
        }

        button.secondary:hover {
            box-shadow: 0 6px 20px rgba(245, 87, 108, 0.6);
        }

        button.success {
            background: linear-gradient(135deg, #a70000 0%, #8b0000 100%);
            box-shadow: 0 4px 15px rgba(167, 0, 0, 0.4);
        }

        button.success:hover {
            box-shadow: 0 6px 20px rgba(167, 0, 0, 0.6);
        }

        .log-container {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            max-height: 300px;
            overflow-y: auto;
        }

        .log-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .log-entry {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            padding: 8px;
            margin-bottom: 4px;
            border-radius: 4px;
            background: white;
            color: #333;
        }

        .log-entry.success {
            border-left: 3px solid #28a745;
        }

        .log-entry.error {
            border-left: 3px solid #dc3545;
        }

        .log-entry.info {
            border-left: 3px solid #17a2b8;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔔 Push-уведомления</h1>
        <p class="subtitle">Тестирование Web Push API</p>

        <div id="unsupported-alert" class="alert alert-danger" style="display: none;">
            ⚠️ Ваш браузер не поддерживает push-уведомления
        </div>

        <div class="status-card">
            <div class="status-row">
                <span class="status-label">Поддержка браузера:</span>
                <span id="support-status" class="status-value">Проверка...</span>
            </div>
            <div class="status-row">
                <span class="status-label">Разрешение:</span>
                <span id="permission-status" class="status-value">Проверка...</span>
            </div>
            <div class="status-row">
                <span class="status-label">Подписка:</span>
                <span id="subscription-status" class="status-value">Проверка...</span>
            </div>
            <div class="status-row">
                <span class="status-label">Service Worker:</span>
                <span id="sw-status" class="status-value">Проверка...</span>
            </div>
        </div>

        <div class="button-group">
            <button id="subscribeBtn" onclick="handleSubscribe()">
                Подписаться на уведомления
            </button>
            <button id="unsubscribeBtn" onclick="handleUnsubscribe()" class="secondary" style="display: none;">
                Отписаться от уведомлений
            </button>
            <button onclick="showTestNotification()" class="success">
                Локальное уведомление (тест)
            </button>
            <button onclick="sendRealPushNotification()" class="success" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                🔔 Реальное Push от сервера
            </button>
        </div>

        <div class="log-container">
            <div class="log-title">Журнал событий:</div>
            <div id="log"></div>
        </div>
    </div>

    <script src="/js/webpush-manager.js"></script>
    <script>
        let pushManager = null;

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', async () => {
            logMessage('Инициализация...', 'info');

            // Проверяем поддержку
            if (!WebPushManager.isSupported()) {
                document.getElementById('unsupported-alert').style.display = 'block';
                updateStatus('support-status', 'Не поддерживается', 'status-error');
                logMessage('Браузер не поддерживает push-уведомления', 'error');
                return;
            }

            updateStatus('support-status', 'Поддерживается', 'status-success');
            logMessage('Браузер поддерживает push-уведомления', 'success');

            // Создаем экземпляр менеджера
            pushManager = new WebPushManager();

            try {
                await pushManager.init();
                logMessage('Web Push Manager инициализирован', 'success');
                
                updatePermissionStatus();
                await updateSubscriptionStatus();
                updateSwStatus();
            } catch (error) {
                logMessage('Ошибка инициализации: ' + error.message, 'error');
            }
        });

        // Обработчик подписки
        async function handleSubscribe() {
            if (!pushManager) {
                logMessage('Push Manager не инициализирован', 'error');
                return;
            }

            try {
                logMessage('Запрос подписки...', 'info');
                await pushManager.subscribe();
                logMessage('Подписка успешно создана!', 'success');
                await updateSubscriptionStatus();
                updatePermissionStatus();
            } catch (error) {
                logMessage('Ошибка подписки: ' + error.message, 'error');
            }
        }

        // Обработчик отписки
        async function handleUnsubscribe() {
            if (!pushManager) {
                logMessage('Push Manager не инициализирован', 'error');
                return;
            }

            if (!confirm('Вы уверены, что хотите отписаться от уведомлений?')) {
                return;
            }

            try {
                logMessage('Отмена подписки...', 'info');
                await pushManager.unsubscribe();
                logMessage('Подписка успешно отменена', 'success');
                await updateSubscriptionStatus();
            } catch (error) {
                logMessage('Ошибка отписки: ' + error.message, 'error');
            }
        }

        // Показать тестовое уведомление
        async function showTestNotification() {
            if (!pushManager) {
                logMessage('Push Manager не инициализирован', 'error');
                return;
            }

            try {
                logMessage('Показ локального уведомления...', 'info');
                await pushManager.showTestNotification();
                logMessage('Локальное уведомление отправлено', 'success');
            } catch (error) {
                logMessage('Ошибка показа уведомления: ' + error.message, 'error');
            }
        }

        // Отправить реальное push-уведомление с сервера
        async function sendRealPushNotification() {
            const subscription = await pushManager.checkSubscription();
            
            if (!subscription) {
                logMessage('Сначала подпишитесь на уведомления!', 'error');
                alert('Сначала нажмите "Подписаться на уведомления"');
                return;
            }

            try {
                logMessage('Отправка реального push с сервера...', 'info');
                
                const response = await fetch('/push/send-test-push', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        endpoint: subscription.endpoint,
                        title: '🔔 Реальное Push-уведомление',
                        body: 'Это настоящее push-уведомление от сервера! Оно работает даже когда браузер свернут.',
                        icon: '/images/icons/icon.svg',
                        url: '/push-test',
                        actions: [
                            {
                                action: 'open',
                                title: '👀 Открыть'
                            },
                            {
                                action: 'close',
                                title: '❌ Закрыть'
                            }
                        ]
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    logMessage('✅ Push успешно отправлен! Отправлено: ' + result.sent, 'success');
                    alert('✅ Push-уведомление отправлено с сервера!\n\nПроверьте:\n1. Уведомления браузера (правый нижний угол)\n2. Центр уведомлений Windows\n3. Даже если браузер свернут - уведомление придет!');
                } else {
                    logMessage('❌ Ошибка отправки: ' + result.message, 'error');
                    alert('Ошибка: ' + result.message);
                }
            } catch (error) {
                logMessage('Ошибка отправки push: ' + error.message, 'error');
                alert('Ошибка: ' + error.message);
            }
        }

        // Обновить статус разрешения
        function updatePermissionStatus() {
            const permission = Notification.permission;
            const statusMap = {
                'granted': { text: 'Разрешено', class: 'status-success' },
                'denied': { text: 'Запрещено', class: 'status-error' },
                'default': { text: 'Не запрошено', class: 'status-warning' }
            };

            const status = statusMap[permission] || statusMap['default'];
            updateStatus('permission-status', status.text, status.class);
        }

        // Обновить статус подписки
        async function updateSubscriptionStatus() {
            if (!pushManager) return;

            const subscription = await pushManager.checkSubscription();
            const isSubscribed = subscription !== null;

            const subscribeBtn = document.getElementById('subscribeBtn');
            const unsubscribeBtn = document.getElementById('unsubscribeBtn');

            if (isSubscribed) {
                updateStatus('subscription-status', 'Активна', 'status-success');
                subscribeBtn.style.display = 'none';
                unsubscribeBtn.style.display = 'block';
            } else {
                updateStatus('subscription-status', 'Неактивна', 'status-warning');
                subscribeBtn.style.display = 'block';
                unsubscribeBtn.style.display = 'none';
            }
        }

        // Обновить статус Service Worker
        function updateSwStatus() {
            if (pushManager && pushManager.swRegistration) {
                updateStatus('sw-status', 'Активен', 'status-success');
            } else {
                updateStatus('sw-status', 'Неактивен', 'status-error');
            }
        }

        // Обновить статус
        function updateStatus(elementId, text, className) {
            const element = document.getElementById(elementId);
            element.textContent = text;
            element.className = 'status-value ' + className;
        }

        // Добавить сообщение в лог
        function logMessage(message, type = 'info') {
            const log = document.getElementById('log');
            const timestamp = new Date().toLocaleTimeString('ru-RU');
            const entry = document.createElement('div');
            entry.className = 'log-entry ' + type;
            entry.textContent = `[${timestamp}] ${message}`;
            log.insertBefore(entry, log.firstChild);

            // Ограничиваем количество записей
            while (log.children.length > 50) {
                log.removeChild(log.lastChild);
            }
        }
    </script>
</body>
</html>
