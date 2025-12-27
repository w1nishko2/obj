/**
 * Axios Interceptor для автоматической обработки 419 ошибок
 * Перехватывает ошибки 419 (Token Mismatch) и автоматически обновляет токен
 */

import CsrfTokenManager from './csrf-refresh.js';

class AxiosErrorHandler {
    constructor(axiosInstance) {
        this.axios = axiosInstance;
        this.isRefreshing = false;
        this.failedQueue = [];
        
        this.setupInterceptor();
    }

    setupInterceptor() {
        // Перехватчик ответов
        this.axios.interceptors.response.use(
            (response) => {
                // Успешный ответ - просто возвращаем
                return response;
            },
            async (error) => {
                const originalRequest = error.config;

                // Проверяем, является ли это ошибкой 419
                if (error.response && error.response.status === 419) {
                    console.log('[Axios Interceptor] Обнаружена ошибка 419 - Token Mismatch');

                    // Предотвращаем бесконечный цикл
                    if (originalRequest._retry) {
                        console.error('[Axios Interceptor] Повторная попытка не удалась');
                        return Promise.reject(error);
                    }

                    // Если уже идет процесс обновления токена
                    if (this.isRefreshing) {
                        console.log('[Axios Interceptor] Токен уже обновляется, добавляем запрос в очередь');
                        return new Promise((resolve, reject) => {
                            this.failedQueue.push({ resolve, reject, config: originalRequest });
                        });
                    }

                    originalRequest._retry = true;
                    this.isRefreshing = true;

                    try {
                        // Обновляем токен
                        console.log('[Axios Interceptor] Обновление токена...');
                        const success = await window.csrfManager.refresh();

                        if (success) {
                            // Получаем новый токен
                            const newToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            
                            if (newToken) {
                                // Обновляем заголовок в оригинальном запросе
                                originalRequest.headers['X-CSRF-TOKEN'] = newToken;
                                
                                // Если это POST/PUT/PATCH/DELETE с FormData, обновляем _token
                                if (originalRequest.data instanceof FormData && originalRequest.data.has('_token')) {
                                    originalRequest.data.set('_token', newToken);
                                }
                                // Если это JSON данные
                                else if (typeof originalRequest.data === 'object' && originalRequest.data !== null) {
                                    if (originalRequest.data._token) {
                                        originalRequest.data._token = newToken;
                                    }
                                }

                                console.log('[Axios Interceptor] Токен обновлен, повторяем запрос');
                                
                                // Обрабатываем очередь неудавшихся запросов
                                this.processQueue(null, newToken);
                                
                                // Повторяем оригинальный запрос
                                return this.axios(originalRequest);
                            }
                        }

                        throw new Error('Не удалось обновить токен');
                    } catch (refreshError) {
                        console.error('[Axios Interceptor] Ошибка обновления токена:', refreshError);
                        this.processQueue(refreshError, null);
                        return Promise.reject(refreshError);
                    } finally {
                        this.isRefreshing = false;
                    }
                }

                // Для других ошибок просто пробрасываем дальше
                return Promise.reject(error);
            }
        );

        console.log('[Axios Interceptor] Перехватчик для обработки 419 ошибок установлен');
    }

    /**
     * Обрабатывает очередь неудавшихся запросов
     */
    processQueue(error, token = null) {
        this.failedQueue.forEach(promise => {
            if (error) {
                promise.reject(error);
            } else {
                // Обновляем токен в запросе
                promise.config.headers['X-CSRF-TOKEN'] = token;
                promise.resolve(this.axios(promise.config));
            }
        });

        this.failedQueue = [];
    }
}

// Автоматически применяем к глобальному axios, если он существует
if (window.axios) {
    window.axiosErrorHandler = new AxiosErrorHandler(window.axios);
    console.log('[Axios Interceptor] Инициализирован для window.axios');
}

export default AxiosErrorHandler;
