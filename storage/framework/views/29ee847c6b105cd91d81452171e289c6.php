<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Тест CSRF Token Auto-Refresh</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
        }
        .test-section {
            background: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .test-section h3 {
            margin-top: 0;
            color: #333;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        button:hover {
            background: #0056b3;
        }
        .status {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .status.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .status.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .token-display {
            background: #fff;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: monospace;
            word-break: break-all;
            margin: 10px 0;
        }
        .log {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            max-height: 300px;
            overflow-y: auto;
            margin: 10px 0;
        }
        .log div {
            margin: 3px 0;
        }
        .log .time {
            color: #569cd6;
        }
        .log .success {
            color: #4ec9b0;
        }
        .log .error {
            color: #f48771;
        }
    </style>
</head>
<body>
    <h1>🔐 Тест системы автоматического обновления CSRF токена</h1>
    
    <div class="status info">
        <strong>ℹ️ Информация:</strong> Эта страница тестирует механизм автоматического обновления CSRF токена для предотвращения ошибки 419.
    </div>

    <!-- Текущий токен -->
    <div class="test-section">
        <h3>📋 Текущий CSRF токен</h3>
        <div class="token-display" id="currentToken"><?php echo e(csrf_token()); ?></div>
        <button onclick="displayCurrentToken()">🔄 Обновить отображение</button>
    </div>

    <!-- Тест 1: Ручное обновление токена -->
    <div class="test-section">
        <h3>🧪 Тест 1: Ручное обновление токена</h3>
        <p>Нажмите кнопку, чтобы вручную обновить CSRF токен.</p>
        <button onclick="manualRefresh()">🔄 Обновить токен</button>
        <div id="test1Result"></div>
    </div>

    <!-- Тест 2: AJAX запрос с Axios -->
    <div class="test-section">
        <h3>🧪 Тест 2: AJAX запрос с правильным токеном</h3>
        <p>Выполнить тестовый POST запрос с текущим токеном.</p>
        <button onclick="testAxiosRequest()">📤 Отправить запрос</button>
        <div id="test2Result"></div>
    </div>

    <!-- Тест 3: Симуляция 419 ошибки -->
    <div class="test-section">
        <h3>🧪 Тест 3: Симуляция устаревшего токена</h3>
        <p>Установить неверный токен и отправить запрос. Система должна автоматически обновить токен и повторить запрос.</p>
        <button onclick="simulateExpiredToken()">⚠️ Симулировать устаревший токен</button>
        <div id="test3Result"></div>
    </div>

    <!-- Тест 4: Проверка информации о системе -->
    <div class="test-section">
        <h3>📊 Информация о системе</h3>
        <button onclick="showSystemInfo()">ℹ️ Показать информацию</button>
        <div id="systemInfo"></div>
    </div>

    <!-- Лог событий -->
    <div class="test-section">
        <h3>📝 Лог событий</h3>
        <button onclick="clearLog()">🗑️ Очистить лог</button>
        <button onclick="toggleDebug()">🐛 Вкл/Выкл отладку</button>
        <div class="log" id="eventLog"></div>
    </div>

    <!-- HTML форма для теста -->
    <div class="test-section">
        <h3>🧪 Тест 4: HTML форма</h3>
        <form id="testForm" action="<?php echo e(route('landing.contact')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="text" name="name" placeholder="Ваше имя" style="padding: 8px; margin: 5px; width: 200px;">
            <button type="submit">📤 Отправить форму</button>
        </form>
        <p style="font-size: 12px; color: #666;">Токен формы будет автоматически обновлен перед отправкой, если он устарел.</p>
    </div>

    <script>
        let logEntries = [];

        // Добавление записи в лог
        function addLog(message, type = 'info') {
            const time = new Date().toLocaleTimeString();
            const entry = { time, message, type };
            logEntries.push(entry);
            
            const logDiv = document.getElementById('eventLog');
            const entryDiv = document.createElement('div');
            entryDiv.innerHTML = `<span class="time">[${time}]</span> <span class="${type}">${message}</span>`;
            logDiv.appendChild(entryDiv);
            logDiv.scrollTop = logDiv.scrollHeight;
        }

        // Отображение текущего токена
        function displayCurrentToken() {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            document.getElementById('currentToken').textContent = token || 'Токен не найден';
            addLog('Токен отображен в интерфейсе', 'success');
        }

        // Тест 1: Ручное обновление
        async function manualRefresh() {
            const resultDiv = document.getElementById('test1Result');
            resultDiv.innerHTML = '<div class="status info">⏳ Обновление токена...</div>';
            addLog('Запуск ручного обновления токена...', 'info');

            try {
                const oldToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                await window.csrfManager.refresh();
                const newToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                if (oldToken !== newToken) {
                    resultDiv.innerHTML = `
                        <div class="status success">
                            ✅ <strong>Токен успешно обновлен!</strong><br>
                            Старый: ${oldToken.substring(0, 20)}...<br>
                            Новый: ${newToken.substring(0, 20)}...
                        </div>
                    `;
                    addLog('✅ Токен успешно обновлен', 'success');
                    displayCurrentToken();
                } else {
                    resultDiv.innerHTML = '<div class="status error">⚠️ Токен не изменился (возможно, сессия не обновилась)</div>';
                    addLog('⚠️ Токен не изменился', 'error');
                }
            } catch (error) {
                resultDiv.innerHTML = `<div class="status error">❌ Ошибка: ${error.message}</div>`;
                addLog(`❌ Ошибка обновления: ${error.message}`, 'error');
            }
        }

        // Тест 2: AJAX запрос
        async function testAxiosRequest() {
            const resultDiv = document.getElementById('test2Result');
            resultDiv.innerHTML = '<div class="status info">⏳ Отправка запроса...</div>';
            addLog('Отправка тестового AJAX запроса...', 'info');

            try {
                // Простой GET запрос для теста
                const response = await axios.get('/refresh-csrf');
                
                resultDiv.innerHTML = `
                    <div class="status success">
                        ✅ <strong>Запрос выполнен успешно!</strong><br>
                        Получен токен: ${response.data.csrf_token.substring(0, 20)}...
                    </div>
                `;
                addLog('✅ AJAX запрос выполнен успешно', 'success');
            } catch (error) {
                resultDiv.innerHTML = `<div class="status error">❌ Ошибка: ${error.message}</div>`;
                addLog(`❌ Ошибка AJAX запроса: ${error.message}`, 'error');
            }
        }

        // Тест 3: Симуляция устаревшего токена
        async function simulateExpiredToken() {
            const resultDiv = document.getElementById('test3Result');
            resultDiv.innerHTML = '<div class="status info">⏳ Установка неверного токена и отправка запроса...</div>';
            addLog('Симуляция устаревшего токена...', 'info');

            try {
                // Сохраняем правильный токен
                const correctToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Устанавливаем неверный токен
                const fakeToken = 'invalid-token-' + Math.random().toString(36).substring(7);
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', fakeToken);
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = fakeToken;
                
                addLog(`Установлен неверный токен: ${fakeToken}`, 'info');
                
                resultDiv.innerHTML = '<div class="status info">⏳ Отправка запроса с неверным токеном...</div>';
                
                // Пытаемся отправить запрос с неверным токеном
                // Это должно вызвать 419 ошибку, которая будет автоматически обработана
                const response = await axios.post('/landing/contact', {
                    name: 'Test User',
                    email: 'test@example.com',
                    message: 'Testing token refresh'
                });
                
                const newToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                resultDiv.innerHTML = `
                    <div class="status success">
                        ✅ <strong>Тест пройден!</strong><br>
                        Система автоматически обработала ошибку и обновила токен.<br>
                        Новый токен: ${newToken.substring(0, 20)}...
                    </div>
                `;
                addLog('✅ Система автоматически восстановила токен и повторила запрос', 'success');
                
            } catch (error) {
                // Восстанавливаем правильный токен
                await window.csrfManager.refresh();
                
                if (error.response && error.response.status === 419) {
                    resultDiv.innerHTML = `
                        <div class="status error">
                            ⚠️ Получена ошибка 419, но автоматическое восстановление не сработало.<br>
                            Проверьте консоль для деталей.
                        </div>
                    `;
                    addLog('⚠️ Ошибка 419 не была автоматически обработана', 'error');
                } else {
                    resultDiv.innerHTML = `<div class="status error">❌ Ошибка: ${error.message}</div>`;
                    addLog(`❌ Ошибка теста: ${error.message}`, 'error');
                }
            }
        }

        // Показать информацию о системе
        function showSystemInfo() {
            const infoDiv = document.getElementById('systemInfo');
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const lastRefresh = new Date(window.csrfManager.lastRefreshTime).toLocaleString();
            const interval = window.csrfManager.options.refreshInterval / 60000;
            
            infoDiv.innerHTML = `
                <div style="background: white; padding: 15px; margin: 10px 0; border-radius: 5px;">
                    <p><strong>Текущий токен:</strong> ${token.substring(0, 30)}...</p>
                    <p><strong>Последнее обновление:</strong> ${lastRefresh}</p>
                    <p><strong>Интервал обновления:</strong> ${interval} минут</p>
                    <p><strong>URL обновления:</strong> ${window.csrfManager.options.refreshUrl}</p>
                    <p><strong>Автообновление активно:</strong> ${window.csrfManager.refreshTimer ? 'Да ✅' : 'Нет ❌'}</p>
                </div>
            `;
            addLog('Информация о системе отображена', 'info');
        }

        // Очистить лог
        function clearLog() {
            document.getElementById('eventLog').innerHTML = '';
            logEntries = [];
            addLog('Лог очищен', 'info');
        }

        // Переключить режим отладки
        function toggleDebug() {
            window.csrfManager.options.debug = !window.csrfManager.options.debug;
            const status = window.csrfManager.options.debug ? 'включена' : 'выключена';
            addLog(`Отладка ${status}`, 'info');
        }

        // Слушаем событие обновления токена
        window.addEventListener('csrf-token-refreshed', (event) => {
            addLog(`🔄 Токен автоматически обновлен: ${event.detail.token.substring(0, 20)}...`, 'success');
        });

        // Инициализация при загрузке
        window.addEventListener('load', () => {
            addLog('🚀 Страница загружена. Система мониторинга токена активна.', 'success');
            addLog(`⚙️ Интервал обновления: ${window.csrfManager.options.refreshInterval / 60000} минут`, 'info');
        });
    </script>
</body>
</html>
<?php /**PATH C:\OSPanel\domains\work\resources\views\csrf-test.blade.php ENDPATH**/ ?>