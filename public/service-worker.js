const CACHE_NAME = 'obekt-plus-v2';
const urlsToCache = [
  '/offline.html',
  '/manifest.json',
  '/js/webpush-manager.js'
];

// Установка Service Worker и кеширование ресурсов
self.addEventListener('install', (event) => {
  console.log('[Service Worker] Installing...');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('[Service Worker] Caching app shell');
        // Кешируем каждый ресурс отдельно, игнорируя ошибки
        return Promise.allSettled(
          urlsToCache.map(url => 
            cache.add(url).catch(err => {
              console.warn(`[Service Worker] Failed to cache ${url}:`, err);
              return null;
            })
          )
        );
      })
      .then(() => {
        console.log('[Service Worker] Caching completed');
      })
      .catch((error) => {
        console.error('[Service Worker] Cache failed:', error);
      })
  );
  self.skipWaiting();
});

// Активация Service Worker и очистка старых кешей
self.addEventListener('activate', (event) => {
  console.log('[Service Worker] Activating...');
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            console.log('[Service Worker] Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  return self.clients.claim();
});

// Стратегия кеширования: Network First, затем Cache
self.addEventListener('fetch', (event) => {
  // Игнорируем не-GET запросы
  if (event.request.method !== 'GET') {
    return;
  }

  // Игнорируем chrome extensions и другие схемы
  if (!event.request.url.startsWith('http')) {
    return;
  }

  event.respondWith(
    fetch(event.request)
      .then((response) => {
        // Проверяем валидность ответа
        if (!response || response.status !== 200 || response.type === 'error') {
          return response;
        }

        // Клонируем ответ для кеширования
        const responseToCache = response.clone();

        caches.open(CACHE_NAME)
          .then((cache) => {
            cache.put(event.request, responseToCache);
          });

        return response;
      })
      .catch(() => {
        // Если сеть недоступна, используем кеш
        return caches.match(event.request)
          .then((cachedResponse) => {
            if (cachedResponse) {
              return cachedResponse;
            }

            // Если запрос к HTML странице и нет в кеше - показываем офлайн страницу
            if (event.request.headers.get('accept').includes('text/html')) {
              return caches.match('/offline.html');
            }
            
            // Для остальных ресурсов - возвращаем главную страницу из кеша
            return caches.match('/');
          });
      })
  );
});

// Обработка push-уведомлений
self.addEventListener('push', (event) => {
  console.log('[Service Worker] Push notification received', event);
  console.log('[Service Worker] Has data:', event.data !== null);

  let notificationData = {
    title: 'Объект+',
    body: 'Новое уведомление',
    icon: '/images/icons/icon.svg',
    badge: '/images/icons/icon.svg',
    vibrate: [200, 100, 200],
    data: {
      dateOfArrival: Date.now(),
      url: '/'
    },
    actions: [],
    tag: 'push-notification-' + Date.now(),
    requireInteraction: false,
    silent: false  // Звук включён
  };

  // Парсим данные из push-уведомления
  if (event.data) {
    try {
      const payload = event.data.json();
      console.log('[Service Worker] Push payload:', payload);

      notificationData = {
        title: payload.title || notificationData.title,
        body: payload.body || notificationData.body,
        icon: payload.icon || notificationData.icon,
        badge: payload.badge || notificationData.badge,
        image: payload.image,
        vibrate: payload.vibrate || notificationData.vibrate,
        tag: payload.tag || notificationData.tag,
        requireInteraction: payload.requireInteraction || false,
        renotify: payload.renotify || false,
        silent: false,  // Звук всегда включён
        timestamp: payload.timestamp || Date.now(),
        data: payload.data || notificationData.data,
        actions: payload.actions || []
      };
    } catch (error) {
      console.error('[Service Worker] Failed to parse push data:', error);
      notificationData.body = event.data.text();
    }
  }

  event.waitUntil(
    self.registration.showNotification(notificationData.title, notificationData)
      .then(() => {
        console.log('[Service Worker] Notification shown successfully with sound');
        
        // Дополнительно: отправляем сообщение всем клиентам для воспроизведения звука
        return self.clients.matchAll().then(clients => {
          clients.forEach(client => {
            client.postMessage({
              type: 'PLAY_NOTIFICATION_SOUND',
              notification: notificationData
            });
          });
        });
      })
      .catch((error) => {
        console.error('[Service Worker] Failed to show notification:', error);
      })
  );
});

// Обработка клика по уведомлению
self.addEventListener('notificationclick', (event) => {
  console.log('[Service Worker] Notification click received', event);
  
  event.notification.close();

  // Обработка действий кнопок
  if (event.action) {
    console.log('[Service Worker] Action clicked:', event.action);
    
    // Если есть кастомная обработка действий
    if (event.notification.data && event.notification.data.actions) {
      const actionData = event.notification.data.actions[event.action];
      if (actionData && actionData.url) {
        event.waitUntil(
          clients.openWindow(actionData.url)
        );
        return;
      }
    }
  }

  // Обработка клика по самому уведомлению
  const urlToOpen = event.notification.data?.url || '/';
  
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true })
      .then((windowClients) => {
        // Ищем уже открытое окно с нужным URL
        for (let client of windowClients) {
          if (client.url === urlToOpen && 'focus' in client) {
            return client.focus();
          }
        }
        
        // Если окно не найдено, открываем новое
        if (clients.openWindow) {
          return clients.openWindow(urlToOpen);
        }
      })
      .catch((error) => {
        console.error('[Service Worker] Failed to handle notification click:', error);
      })
  );
});

// Обработка закрытия уведомления
self.addEventListener('notificationclose', (event) => {
  console.log('[Service Worker] Notification closed', event.notification.tag);
  
  // Здесь можно отправить аналитику о закрытии уведомления
  if (event.notification.data && event.notification.data.closeUrl) {
    event.waitUntil(
      fetch(event.notification.data.closeUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          tag: event.notification.tag,
          timestamp: Date.now()
        })
      }).catch(error => {
        console.error('[Service Worker] Failed to report notification close:', error);
      })
    );
  }
});

// Синхронизация в фоне (на будущее)
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-data') {
    event.waitUntil(syncData());
  }
});

async function syncData() {
  // Здесь можно реализовать логику синхронизации данных
  console.log('[Service Worker] Background sync');
}
