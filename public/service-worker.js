const CACHE_NAME = 'obekt-plus-v8';
const urlsToCache = [
  '/offline.html'
];

// Установка Service Worker
self.addEventListener('install', (event) => {
  console.log('[Service Worker] Installing...');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('[Service Worker] Caching basic resources');
        // Кэшируем только offline.html при установке
        return cache.addAll(urlsToCache).catch((error) => {
          console.error('[Service Worker] Failed to cache resources:', error);
          // Продолжаем установку даже если кэширование не удалось
        });
      })
  );
  self.skipWaiting();
});

// Активация Service Worker
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

// Fetch - простое кеширование страниц
self.addEventListener('fetch', (event) => {
  // Игнорируем не-GET запросы
  if (event.request.method !== 'GET') {
    return;
  }

  // Игнорируем chrome extensions
  if (!event.request.url.startsWith('http')) {
    return;
  }

  // Игнорируем range requests (partial content)
  if (event.request.headers.get('range')) {
    return;
  }

  const url = new URL(event.request.url);
  
  // Игнорируем Vite dev server
  if (url.port === '5173' || url.pathname.includes('/@vite/') || url.pathname.includes('/resources/')) {
    return;
  }

  // Игнорируем внешние домены кроме CDN иконок
  const isOwnDomain = url.hostname === 'work' || 
                      url.hostname === 'localhost' || 
                      url.hostname === '127.0.0.1' ||
                      url.hostname.endsWith('.work');
  
  const isAllowedCDN = url.hostname === 'cdn.jsdelivr.net';
  
  if (!isOwnDomain && !isAllowedCDN) {
    return;
  }

  // Network First для всех запросов
  event.respondWith(
    fetch(event.request.clone())
      .then((response) => {
        // Кешируем только полные успешные ответы
        if (response && response.status === 200 && response.type !== 'opaque') {
          // Не кешируем видео, аудио и большие файлы
          const contentType = response.headers.get('content-type') || '';
          const isMediaFile = contentType.includes('video') || 
                            contentType.includes('audio') ||
                            url.pathname.match(/\.(mp4|webm|ogg|mp3|wav|avi|mov)$/i);
          
          if (!isMediaFile) {
            const responseToCache = response.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(event.request, responseToCache).catch((error) => {
                console.warn('[Service Worker] Cache put failed:', error.message);
              });
            });
          }
        }
        return response;
      })
      .catch(() => {
        // Возвращаем из кеша если есть
        return caches.match(event.request)
          .then((cachedResponse) => {
            if (cachedResponse) {
              return cachedResponse;
            }

            // Для HTML страниц показываем offline.html
            const acceptHeader = event.request.headers.get('accept');
            if (acceptHeader && acceptHeader.includes('text/html')) {
              return caches.match('/offline.html');
            }

            // Для изображений возвращаем пустой ответ
            if (event.request.destination === 'image') {
              return new Response('', { status: 200 });
            }

            // Для CSS/JS возвращаем пустой файл
            if (event.request.destination === 'style' || event.request.destination === 'script') {
              return new Response('', { status: 200 });
            }

            return new Response('', { status: 200 });
          });
      })
  );
});

// Обработка push-уведомлений
self.addEventListener('push', (event) => {
  console.log('[Service Worker] Push notification received');
  
  let notificationData = {
    title: 'Объект+',
    body: 'Новое уведомление',
    icon: '/images/icons/icon.svg',
    badge: '/images/icons/icon.svg',
    vibrate: [200, 100, 200],
    data: { url: '/' }
  };

  if (event.data) {
    try {
      const payload = event.data.json();
      notificationData = { ...notificationData, ...payload };
    } catch (error) {
      notificationData.body = event.data.text();
    }
  }

  const title = notificationData.title;
  delete notificationData.title;

  event.waitUntil(
    self.registration.showNotification(title, notificationData)
  );
});

// Обработка клика по уведомлению
self.addEventListener('notificationclick', (event) => {
  event.notification.close();
  
  const urlToOpen = event.notification.data?.url || '/';
  
  event.waitUntil(
    clients.matchAll({ type: 'window' }).then((windowClients) => {
      for (let client of windowClients) {
        if (client.url === urlToOpen && 'focus' in client) {
          return client.focus();
        }
      }
      if (clients.openWindow) {
        return clients.openWindow(urlToOpen);
      }
    })
  );
});
