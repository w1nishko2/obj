/**
 * Web Push Notification Manager
 * Управление push-уведомлениями через Web Push API
 */

class WebPushManager {
    constructor() {
        this.vapidPublicKey = null;
        this.isSubscribed = false;
        this.swRegistration = null;
    }

    /**
     * Инициализация
     */
    async init() {
        if (!('serviceWorker' in navigator)) {
            console.error('Service Worker не поддерживается в этом браузере');
            return false;
        }

        if (!('PushManager' in window)) {
            console.error('Push-уведомления не поддерживаются в этом браузере');
            return false;
        }

        try {
            // Регистрируем Service Worker если еще не зарегистрирован
            this.swRegistration = await navigator.serviceWorker.register('/service-worker.js');
            console.log('Service Worker зарегистрирован:', this.swRegistration);

            // Получаем публичный VAPID ключ с сервера
            await this.fetchVapidPublicKey();

            // Проверяем текущий статус подписки
            await this.checkSubscription();

            return true;
        } catch (error) {
            console.error('Ошибка инициализации Web Push:', error);
            return false;
        }
    }

    /**
     * Получить VAPID публичный ключ с сервера
     */
    async fetchVapidPublicKey() {
        try {
            const response = await fetch('/push/vapid-public-key');
            const data = await response.json();
            this.vapidPublicKey = data.publicKey;
            console.log('VAPID публичный ключ получен');
        } catch (error) {
            console.error('Ошибка получения VAPID ключа:', error);
            throw error;
        }
    }

    /**
     * Проверить текущую подписку
     */
    async checkSubscription() {
        try {
            const subscription = await this.swRegistration.pushManager.getSubscription();
            this.isSubscribed = subscription !== null;
            console.log('Статус подписки:', this.isSubscribed);
            return subscription;
        } catch (error) {
            console.error('Ошибка проверки подписки:', error);
            return null;
        }
    }

    /**
     * Запросить разрешение на уведомления
     */
    async requestPermission() {
        if (!('Notification' in window)) {
            console.error('Уведомления не поддерживаются');
            return false;
        }

        if (Notification.permission === 'granted') {
            return true;
        }

        if (Notification.permission === 'denied') {
            console.warn('Пользователь запретил уведомления');
            return false;
        }

        const permission = await Notification.requestPermission();
        return permission === 'granted';
    }

    /**
     * Подписаться на push-уведомления
     */
    async subscribe() {
        try {
            // Запрашиваем разрешение
            const permissionGranted = await this.requestPermission();
            if (!permissionGranted) {
                throw new Error('Разрешение на уведомления не получено');
            }

            // Проверяем наличие VAPID ключа
            if (!this.vapidPublicKey) {
                await this.fetchVapidPublicKey();
            }

            // Создаем подписку
            const subscription = await this.swRegistration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlBase64ToUint8Array(this.vapidPublicKey)
            });

            console.log('Подписка создана:', subscription);

            // Отправляем подписку на сервер
            await this.sendSubscriptionToServer(subscription);

            this.isSubscribed = true;
            return subscription;
        } catch (error) {
            console.error('Ошибка подписки:', error);
            throw error;
        }
    }

    /**
     * Отписаться от push-уведомлений
     */
    async unsubscribe() {
        try {
            const subscription = await this.swRegistration.pushManager.getSubscription();
            
            if (!subscription) {
                console.log('Подписка не найдена');
                return true;
            }

            // Отписываемся
            await subscription.unsubscribe();
            console.log('Подписка отменена');

            // Удаляем подписку с сервера
            await this.removeSubscriptionFromServer(subscription);

            this.isSubscribed = false;
            return true;
        } catch (error) {
            console.error('Ошибка отписки:', error);
            throw error;
        }
    }

    /**
     * Отправить подписку на сервер
     */
    async sendSubscriptionToServer(subscription) {
        try {
            const response = await fetch('/push/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify(subscription)
            });

            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'Ошибка сохранения подписки');
            }

            console.log('Подписка сохранена на сервере:', data);
            return data;
        } catch (error) {
            console.error('Ошибка отправки подписки на сервер:', error);
            throw error;
        }
    }

    /**
     * Удалить подписку с сервера
     */
    async removeSubscriptionFromServer(subscription) {
        try {
            const response = await fetch('/push/unsubscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    endpoint: subscription.endpoint
                })
            });

            const data = await response.json();
            console.log('Подписка удалена с сервера:', data);
            return data;
        } catch (error) {
            console.error('Ошибка удаления подписки с сервера:', error);
            throw error;
        }
    }

    /**
     * Переключить подписку (подписаться/отписаться)
     */
    async toggleSubscription() {
        if (this.isSubscribed) {
            return await this.unsubscribe();
        } else {
            return await this.subscribe();
        }
    }

    /**
     * Показать тестовое уведомление
     */
    async showTestNotification() {
        if (!('Notification' in window)) {
            console.error('Уведомления не поддерживаются');
            return;
        }

        if (Notification.permission !== 'granted') {
            console.warn('Разрешение на уведомления не получено');
            return;
        }

        try {
            await this.swRegistration.showNotification('Тестовое уведомление', {
                body: 'Push-уведомления работают!',
                icon: '/images/icons/icon.svg',
                badge: '/images/icons/icon.svg',
                vibrate: [200, 100, 200],
                tag: 'test-notification',
                data: {
                    url: window.location.href
                }
            });
        } catch (error) {
            console.error('Ошибка показа тестового уведомления:', error);
        }
    }

    /**
     * Конвертировать base64 строку в Uint8Array
     */
    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/\-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }

        return outputArray;
    }

    /**
     * Получить статус разрешения
     */
    getPermissionStatus() {
        if (!('Notification' in window)) {
            return 'unsupported';
        }
        return Notification.permission;
    }

    /**
     * Проверить поддержку браузером
     */
    static isSupported() {
        return 'serviceWorker' in navigator && 'PushManager' in window;
    }
}

// Экспортируем класс для использования
window.WebPushManager = WebPushManager;

// Автоматическая инициализация при загрузке страницы
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        console.log('Web Push Manager готов к использованию');
    });
} else {
    console.log('Web Push Manager готов к использованию');
}
