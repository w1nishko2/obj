<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Диагностика Push в Chrome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 { color: #333; }
        h2 { color: #666; margin-top: 0; }
        .step { 
            margin: 15px 0; 
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #4285f4;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
        }
        .success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 15px 0;
        }
        button {
            background: #4285f4;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }
        button:hover { background: #357ae8; }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        #results {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 4px;
            white-space: pre-wrap;
            font-family: monospace;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>🔍 Диагностика Push-уведомлений в Chrome</h1>

    <div class="card">
        <h2>1. Проверка настроек Chrome</h2>
        <div class="step">
            <strong>Шаг 1:</strong> Откройте настройки Chrome<br>
            1. Нажмите на три точки (⋮) в правом верхнем углу<br>
            2. Выберите "Настройки"<br>
            3. Перейдите в "Конфиденциальность и безопасность" → "Настройки сайта"<br>
            4. Найдите "Уведомления"
        </div>
        
        <div class="step">
            <strong>Шаг 2:</strong> Проверьте, что уведомления не заблокированы<br>
            - Убедитесь, что ваш сайт в разделе "Разрешено"<br>
            - Если он в "Заблокировано" - удалите его оттуда
        </div>

        <div class="warning">
            <strong>⚠️ Важно:</strong> Chrome может блокировать уведомления если:
            <ul>
                <li>Вы случайно нажали "Заблокировать" при запросе разрешения</li>
                <li>Сайт находится в списке заблокированных</li>
                <li>Включен режим "Не беспокоить" в Windows</li>
            </ul>
        </div>
    </div>

    <div class="card">
        <h2>2. Быстрая диагностика</h2>
        <button onclick="checkPermissions()">Проверить разрешения</button>
        <button onclick="checkServiceWorker()">Проверить Service Worker</button>
        <button onclick="checkSubscription()">Проверить подписку</button>
        <button onclick="checkNotificationAPI()">Тест Notification API</button>
        <button onclick="clearAndResubscribe()">Очистить и переподписаться</button>
        
        <div id="results"></div>
    </div>

    <div class="card">
        <h2>3. Проверка через DevTools</h2>
        <div class="step">
            <strong>Откройте DevTools (F12)</strong><br>
            1. Перейдите на вкладку "Application"<br>
            2. В левом меню найдите "Service Workers"<br>
            3. Убедитесь, что Service Worker активен (зеленый индикатор)<br>
            4. Нажмите "Update" для обновления<br>
            5. Проверьте "Push Messaging" - должна быть активная подписка
        </div>
    </div>

    <div class="card">
        <h2>4. Сброс всех настроек</h2>
        <div class="step">
            В Chrome введите в адресной строке:<br>
            <code>chrome://settings/content/notifications</code><br><br>
            Найдите ваш сайт и удалите его из списка (если есть)
        </div>
    </div>

    <div class="card">
        <h2>5. Проверка уведомлений Windows</h2>
        <div class="step">
            <strong>Windows 10/11:</strong><br>
            1. Откройте "Параметры Windows" (Win + I)<br>
            2. Перейдите в "Система" → "Уведомления"<br>
            3. Убедитесь, что уведомления включены<br>
            4. Найдите Chrome в списке приложений и включите уведомления
        </div>
    </div>

    <script>
        const log = (message, type = 'info') => {
            const results = document.getElementById('results');
            const timestamp = new Date().toLocaleTimeString();
            const prefix = type === 'error' ? '❌' : type === 'success' ? '✅' : 'ℹ️';
            results.textContent += `[${timestamp}] ${prefix} ${message}\n`;
        };

        async function checkPermissions() {
            document.getElementById('results').textContent = '';
            log('Проверка разрешений...');
            
            const permission = Notification.permission;
            log(`Текущее разрешение: ${permission}`, permission === 'granted' ? 'success' : 'error');
            
            if (permission === 'denied') {
                log('❌ Уведомления ЗАБЛОКИРОВАНЫ!', 'error');
                log('Решение: Перейдите в chrome://settings/content/notifications');
                log('Найдите ваш сайт и удалите его, затем обновите страницу');
            } else if (permission === 'default') {
                log('⚠️ Разрешение не запрошено. Нажмите "Подписаться"');
            } else {
                log('✅ Разрешение получено!', 'success');
            }
        }

        async function checkServiceWorker() {
            document.getElementById('results').textContent = '';
            log('Проверка Service Worker...');
            
            if (!('serviceWorker' in navigator)) {
                log('❌ Service Worker не поддерживается', 'error');
                return;
            }
            
            try {
                const registration = await navigator.serviceWorker.getRegistration();
                if (registration) {
                    log('✅ Service Worker зарегистрирован', 'success');
                    log(`Scope: ${registration.scope}`);
                    log(`State: ${registration.active?.state || 'none'}`);
                } else {
                    log('❌ Service Worker не зарегистрирован', 'error');
                }
            } catch (error) {
                log(`❌ Ошибка: ${error.message}`, 'error');
            }
        }

        async function checkSubscription() {
            document.getElementById('results').textContent = '';
            log('Проверка подписки...');
            
            try {
                const registration = await navigator.serviceWorker.ready;
                const subscription = await registration.pushManager.getSubscription();
                
                if (subscription) {
                    log('✅ Подписка активна!', 'success');
                    log(`Endpoint: ${subscription.endpoint.substring(0, 50)}...`);
                } else {
                    log('❌ Подписка отсутствует', 'error');
                    log('Нажмите "Подписаться на уведомления"');
                }
            } catch (error) {
                log(`❌ Ошибка: ${error.message}`, 'error');
            }
        }

        async function checkNotificationAPI() {
            document.getElementById('results').textContent = '';
            log('Тестирование Notification API...');
            
            if (!('Notification' in window)) {
                log('❌ Notification API не поддерживается', 'error');
                return;
            }
            
            if (Notification.permission !== 'granted') {
                log('❌ Нет разрешения на уведомления', 'error');
                const permission = await Notification.requestPermission();
                log(`Новое разрешение: ${permission}`);
                if (permission !== 'granted') return;
            }
            
            try {
                log('Отправка тестового уведомления...');
                const notification = new Notification('🔔 Тест Chrome', {
                    body: 'Если вы видите это - Notification API работает!',
                    icon: '/images/icons/icon.svg',
                    badge: '/images/icons/icon.svg',
                    tag: 'test-' + Date.now(),
                    requireInteraction: false
                });
                
                log('✅ Уведомление отправлено!', 'success');
                log('Проверьте правый нижний угол экрана');
                
                notification.onclick = () => {
                    log('✅ Клик по уведомлению зарегистрирован!', 'success');
                    notification.close();
                };
            } catch (error) {
                log(`❌ Ошибка: ${error.message}`, 'error');
            }
        }

        async function clearAndResubscribe() {
            document.getElementById('results').textContent = '';
            log('Очистка и переподписка...');
            
            try {
                // Отписываемся
                const registration = await navigator.serviceWorker.ready;
                const subscription = await registration.pushManager.getSubscription();
                
                if (subscription) {
                    await subscription.unsubscribe();
                    log('✅ Старая подписка удалена', 'success');
                }
                
                // Удаляем Service Worker
                const allRegistrations = await navigator.serviceWorker.getRegistrations();
                for (let reg of allRegistrations) {
                    await reg.unregister();
                }
                log('✅ Service Workers удалены', 'success');
                
                log('Перезагрузите страницу и подпишитесь заново', 'success');
                
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } catch (error) {
                log(`❌ Ошибка: ${error.message}`, 'error');
            }
        }
    </script>
</body>
</html>
