/**
 * CSRF Token Auto-Refresh System
 * Автоматически обновляет CSRF токен для предотвращения ошибки 419
 */

class CsrfTokenManager {
    constructor(options = {}) {
        // Настройки по умолчанию
        this.options = {
            // Обновлять токен каждые 100 минут (сессия по умолчанию 120 минут)
            refreshInterval: options.refreshInterval || 100 * 60 * 1000,
            // URL для получения нового токена
            refreshUrl: options.refreshUrl || '/refresh-csrf',
            // Автоматически обновлять при загрузке страницы
            refreshOnLoad: options.refreshOnLoad !== false,
            // Показывать логи в консоли
            debug: options.debug || false,
        };

        this.refreshTimer = null;
        this.lastRefreshTime = Date.now();
        
        this.init();
    }

    init() {
        this.log('CSRF Token Manager инициализирован');
        
        // Запускаем автоматическое обновление
        this.startAutoRefresh();
        
        // Обновляем токен при загрузке страницы (если прошло много времени)
        if (this.options.refreshOnLoad) {
            this.checkAndRefreshIfNeeded();
        }

        // Обновляем токен при активности пользователя после долгого простоя
        this.setupActivityListener();
        
        // Обновляем токен перед отправкой форм
        this.setupFormListener();
    }

    log(message, ...args) {
        if (this.options.debug) {
            console.log(`[CSRF Manager] ${message}`, ...args);
        }
    }

    /**
     * Получает новый CSRF токен с сервера
     */
    async refreshToken() {
        try {
            this.log('Запрос нового токена...');
            
            const response = await fetch(this.options.refreshUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.csrf_token) {
                this.updateToken(data.csrf_token);
                this.lastRefreshTime = Date.now();
                this.log('Токен успешно обновлен:', data.csrf_token.substring(0, 10) + '...');
                return true;
            } else {
                throw new Error('Токен не получен от сервера');
            }
        } catch (error) {
            console.error('[CSRF Manager] Ошибка обновления токена:', error);
            return false;
        }
    }

    /**
     * Обновляет токен во всех местах на странице
     */
    updateToken(newToken) {
        // Обновляем meta тег
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            metaTag.setAttribute('content', newToken);
            this.log('Meta тег обновлен');
        }

        // Обновляем все скрытые поля _token в формах
        const tokenInputs = document.querySelectorAll('input[name="_token"]');
        tokenInputs.forEach(input => {
            input.value = newToken;
        });
        this.log(`Обновлено ${tokenInputs.length} полей _token в формах`);

        // Обновляем заголовок axios, если он используется
        if (window.axios && window.axios.defaults.headers.common) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = newToken;
            this.log('Axios заголовок обновлен');
        }

        // Dispatch события для других скриптов
        window.dispatchEvent(new CustomEvent('csrf-token-refreshed', { 
            detail: { token: newToken } 
        }));
    }

    /**
     * Запускает автоматическое периодическое обновление
     */
    startAutoRefresh() {
        // Очищаем предыдущий таймер, если есть
        if (this.refreshTimer) {
            clearInterval(this.refreshTimer);
        }

        this.log(`Автообновление запущено (интервал: ${this.options.refreshInterval / 60000} минут)`);
        
        this.refreshTimer = setInterval(() => {
            this.log('Плановое обновление токена');
            this.refreshToken();
        }, this.options.refreshInterval);
    }

    /**
     * Останавливает автоматическое обновление
     */
    stopAutoRefresh() {
        if (this.refreshTimer) {
            clearInterval(this.refreshTimer);
            this.refreshTimer = null;
            this.log('Автообновление остановлено');
        }
    }

    /**
     * Проверяет, не истек ли срок токена и обновляет при необходимости
     */
    checkAndRefreshIfNeeded() {
        const timeSinceLastRefresh = Date.now() - this.lastRefreshTime;
        const threshold = this.options.refreshInterval * 0.8; // 80% от интервала

        if (timeSinceLastRefresh > threshold) {
            this.log('Токен устарел, обновляем...');
            this.refreshToken();
        }
    }

    /**
     * Отслеживает активность пользователя
     */
    setupActivityListener() {
        let userInactive = false;
        let inactivityTimer = null;

        const resetInactivityTimer = () => {
            clearTimeout(inactivityTimer);
            
            if (userInactive) {
                userInactive = false;
                this.checkAndRefreshIfNeeded();
            }

            // Считаем пользователя неактивным через 30 минут
            inactivityTimer = setTimeout(() => {
                userInactive = true;
                this.log('Пользователь неактивен более 30 минут');
            }, 30 * 60 * 1000);
        };

        // Отслеживаем активность
        ['mousedown', 'keydown', 'scroll', 'touchstart'].forEach(event => {
            document.addEventListener(event, resetInactivityTimer, { passive: true });
        });

        resetInactivityTimer();
    }

    /**
     * Обновляет токен перед отправкой форм
     */
    setupFormListener() {
        document.addEventListener('submit', async (e) => {
            const form = e.target;
            
            // Проверяем, нужна ли форме CSRF защита
            const method = (form.method || 'GET').toUpperCase();
            if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) {
                const timeSinceLastRefresh = Date.now() - this.lastRefreshTime;
                
                // Если токен старше 90 минут, обновляем перед отправкой
                if (timeSinceLastRefresh > 90 * 60 * 1000) {
                    this.log('Токен устарел, обновляем перед отправкой формы');
                    e.preventDefault();
                    
                    const success = await this.refreshToken();
                    if (success) {
                        // Повторно отправляем форму
                        form.submit();
                    } else {
                        alert('Ошибка обновления токена. Попробуйте обновить страницу.');
                    }
                }
            }
        }, true);
    }

    /**
     * Вручную обновить токен
     */
    async refresh() {
        return await this.refreshToken();
    }
}

// Создаем глобальный экземпляр
window.csrfManager = new CsrfTokenManager({
    debug: false, // Установите true для отладки
});

export default CsrfTokenManager;
