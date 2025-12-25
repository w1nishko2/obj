# 🔔 Web Push уведомления - Руководство

## Полностью независимая система без FCM/Firebase

Система использует **Web Push API** и работает полностью автономно через ваш сервер, без зависимости от Google FCM или других зарубежных сервисов. Работает в России! 🇷🇺

## ✨ Возможности

- ✅ Работает даже когда браузер свернут или в фоне
- ✅ Полностью независимая система (без FCM/Firebase)
- ✅ Поддержка Chrome, Firefox, Edge, Opera, Safari
- ✅ Автоматическая очистка недействительных подписок
- ✅ Кастомные действия в уведомлениях
- ✅ Поддержка изображений и иконок
- ✅ Вибрация и звуки

## 🚀 Быстрый старт

### 1. Тестовая страница

Откройте в браузере: **http://ваш-сайт/push-test**

Здесь можно:
- Проверить поддержку браузера
- Подписаться на уведомления
- Отправить тестовое уведомление
- Посмотреть статус подписки

### 2. Добавить на свою страницу

```html
<!-- В <head> вашего layout -->
<script src="/js/webpush-manager.js"></script>

<!-- Кнопка подписки -->
<button id="subscribe-btn">Включить уведомления</button>

<script>
    let pushManager = null;

    // Инициализация
    document.addEventListener('DOMContentLoaded', async () => {
        if (!WebPushManager.isSupported()) {
            console.log('Push не поддерживается');
            return;
        }

        pushManager = new WebPushManager();
        await pushManager.init();

        // Обработчик кнопки
        document.getElementById('subscribe-btn').addEventListener('click', async () => {
            try {
                await pushManager.subscribe();
                alert('Подписка оформлена!');
            } catch (error) {
                console.error('Ошибка подписки:', error);
            }
        });
    });
</script>
```

## 📤 Отправка уведомлений из кода

### Простое уведомление

```php
use App\Services\WebPushService;

$webPushService = app(WebPushService::class);

// Одному пользователю
$webPushService->sendToUser(
    userId: 1,
    payload: [
        'title' => 'Новая задача',
        'body' => 'Вам назначена новая задача в проекте',
        'icon' => '/images/icons/icon.svg',
        'data' => ['url' => '/projects/123']
    ]
);
```

### Отправка нескольким пользователям

```php
// Нескольким пользователям
$webPushService->sendToUsers(
    userIds: [1, 2, 3],
    payload: [
        'title' => 'Обновление проекта',
        'body' => 'Статус проекта изменен на "В работе"',
    ]
);

// Всем пользователям
$webPushService->sendToAll(
    payload: [
        'title' => 'Важное объявление',
        'body' => 'Планируются технические работы',
    ]
);
```

### Уведомление с действиями

```php
use App\Services\WebPushService;

$notification = WebPushService::createActionNotification(
    title: 'Новый комментарий',
    body: 'Иван оставил комментарий к вашей задаче',
    actions: [
        [
            'action' => 'view',
            'title' => 'Посмотреть',
        ],
        [
            'action' => 'reply',
            'title' => 'Ответить',
        ]
    ],
    extra: [
        'icon' => '/images/icons/comment.png',
        'image' => '/images/task-preview.jpg',
        'data' => [
            'url' => '/tasks/456',
            'task_id' => 456
        ]
    ]
);

$webPushService->sendToUser(userId: 1, payload: $notification);
```

### Уведомление с изображением

```php
$webPushService->sendToUser(
    userId: 1,
    payload: [
        'title' => 'Фото загружено',
        'body' => 'Новое фото добавлено в проект',
        'icon' => '/images/icons/icon.svg',
        'image' => '/storage/photos/photo-123.jpg', // Большое изображение
        'vibrate' => [200, 100, 200],
        'data' => ['url' => '/projects/123/photos']
    ]
);
```

## 🎯 Примеры использования

### При создании задачи

```php
// В контроллере после создания задачи
$task = Task::create($request->all());

// Отправляем уведомление исполнителю
app(WebPushService::class)->sendToUser(
    userId: $task->assignee_id,
    payload: [
        'title' => 'Новая задача',
        'body' => $task->title,
        'icon' => '/images/icons/task.svg',
        'data' => [
            'url' => route('tasks.show', $task->id),
            'task_id' => $task->id
        ]
    ]
);
```

### При изменении статуса

```php
// В контроллере при обновлении проекта
$project->update(['status' => 'completed']);

// Уведомляем всех участников
$userIds = $project->participants->pluck('id')->toArray();

app(WebPushService::class)->sendToUsers(
    userIds: $userIds,
    payload: [
        'title' => 'Проект завершен',
        'body' => "Проект \"{$project->name}\" завершен!",
        'icon' => '/images/icons/success.svg',
        'requireInteraction' => true, // Требует действия пользователя
        'data' => ['url' => route('projects.show', $project->id)]
    ]
);
```

### В Laravel Events

```php
// App/Events/TaskAssigned.php
class TaskAssigned
{
    public function __construct(public Task $task) {}
}

// App/Listeners/SendTaskNotification.php
class SendTaskNotification
{
    public function handle(TaskAssigned $event)
    {
        app(WebPushService::class)->sendToUser(
            userId: $event->task->assignee_id,
            payload: [
                'title' => 'Задача назначена',
                'body' => $event->task->title,
                'data' => ['url' => route('tasks.show', $event->task->id)]
            ]
        );
    }
}
```

### В Laravel Jobs (фоновая отправка)

```php
// App/Jobs/SendPushNotification.php
class SendPushNotification implements ShouldQueue
{
    public function __construct(
        public int $userId,
        public array $payload
    ) {}

    public function handle(WebPushService $webPushService)
    {
        $webPushService->sendToUser($this->userId, $this->payload);
    }
}

// Использование
SendPushNotification::dispatch(
    userId: 1,
    payload: ['title' => 'Тест', 'body' => 'Сообщение']
);
```

## 🔧 API Endpoints

### Для фронтенда

```javascript
// Получить публичный VAPID ключ
GET /api/push/vapid-public-key

// Подписаться (авторизация опциональна)
POST /api/push/subscribe
{
  "endpoint": "...",
  "keys": {
    "p256dh": "...",
    "auth": "..."
  }
}

// Отписаться
POST /api/push/unsubscribe
{
  "endpoint": "..."
}

// Получить свои подписки (требует авторизации)
GET /api/push/subscriptions
```

### Для отправки (требует авторизации)

```javascript
// Отправить тестовое уведомление себе
POST /push/send-test

// Отправить кастомное уведомление
POST /push/send
{
  "user_id": 1,
  "title": "Заголовок",
  "body": "Текст",
  "icon": "/icon.png",
  "url": "/page"
}

// Отправить всем
POST /push/send-all
{
  "title": "Важно",
  "body": "Сообщение для всех"
}
```

## ⚙️ Конфигурация

Файл `config/webpush.php`:

```php
return [
    'vapid' => [
        'subject' => env('VAPID_SUBJECT'),      // Ваш email
        'public_key' => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
    ],
    'ttl' => 2419200,                            // 4 недели
    'auto_cleanup' => true,                      // Автоудаление невалидных
];
```

## 🔐 Безопасность

### VAPID ключи

Уже сгенерированы и добавлены в `.env`:
```env
VAPID_PUBLIC_KEY=BLATpkagW9wdhuAgXLaZjEgGD1tMPgZULxmVw7LpONNTBNLAPRTKpYas82gs6_sH9Mzjbt6FcqmTeJxgSm_8yqE
VAPID_PRIVATE_KEY=kMpvXsHt4ykvUqoASwJoAY5U-t4Ygx-Pw2aBw3yV6p4
VAPID_SUBJECT=mailto:your-email@example.com
```

⚠️ **Измените VAPID_SUBJECT на ваш реальный email!**

### Перегенерация ключей

```bash
php artisan webpush:vapid --force
```

## 📱 Поддержка браузеров

| Браузер | Поддержка | Фоновые уведомления |
|---------|-----------|---------------------|
| Chrome  | ✅ Да     | ✅ Да               |
| Firefox | ✅ Да     | ✅ Да               |
| Edge    | ✅ Да     | ✅ Да               |
| Opera   | ✅ Да     | ✅ Да               |
| Safari  | ✅ Да (16.4+) | ✅ Да           |

## 🐛 Отладка

### Проверка Service Worker

```javascript
navigator.serviceWorker.getRegistration().then(reg => {
    console.log('Service Worker:', reg);
});
```

### Проверка подписки

```javascript
navigator.serviceWorker.ready.then(reg => {
    reg.pushManager.getSubscription().then(sub => {
        console.log('Подписка:', sub);
    });
});
```

### Логи

Все ошибки отправки записываются в `storage/logs/laravel.log`

## 📊 Мониторинг

```php
// Получить количество активных подписок
$totalSubscriptions = \App\Models\PushSubscription::count();

// Подписки пользователя
$userSubscriptions = \App\Models\PushSubscription::where('user_id', 1)->count();

// Недавние подписки
$recentSubscriptions = \App\Models\PushSubscription::where('created_at', '>', now()->subDay())->count();
```

## 🚨 Частые проблемы

### Уведомления не приходят

1. Проверьте, что браузер дал разрешение
2. Убедитесь, что Service Worker активен
3. Проверьте VAPID ключи в `.env`
4. Выполните `php artisan config:clear`

### Service Worker не регистрируется

1. Проверьте, что используется HTTPS (или localhost)
2. Убедитесь, что файл `/service-worker.js` доступен
3. Проверьте консоль браузера на ошибки

### Подписка не сохраняется

1. Проверьте, что миграция выполнена
2. Убедитесь, что CSRF токен передается
3. Проверьте логи Laravel

## 💡 Полезные советы

### Группировка уведомлений

```php
// Используйте одинаковый tag для группировки
$payload = [
    'title' => 'Новые сообщения',
    'body' => 'У вас 5 новых сообщений',
    'tag' => 'messages', // Заменит предыдущее уведомление с этим tag
    'renotify' => true,  // Заново вибрировать
];
```

### Тихие уведомления

```php
$payload = [
    'title' => 'Обновление',
    'body' => 'Данные обновлены',
    'silent' => true,    // Без звука и вибрации
];
```

### Уведомление требует действия

```php
$payload = [
    'title' => 'Критическое событие',
    'body' => 'Требуется ваше внимание',
    'requireInteraction' => true, // Не исчезнет автоматически
];
```

## 🎨 Кастомизация иконок

По умолчанию используется `/images/icons/icon.svg`. Измените в `config/webpush.php`:

```php
'notification_defaults' => [
    'icon' => '/path/to/your/icon.png',
    'badge' => '/path/to/your/badge.png',
],
```

## 📞 Поддержка

При возникновении проблем:
1. Проверьте логи: `storage/logs/laravel.log`
2. Проверьте консоль браузера
3. Протестируйте на странице `/push-test`

---

**Система полностью работает! Протестируйте на /push-test** 🚀
