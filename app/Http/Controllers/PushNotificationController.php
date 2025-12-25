<?php

namespace App\Http\Controllers;

use App\Services\WebPushService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushNotificationController extends Controller
{
    protected WebPushService $webPushService;

    public function __construct(WebPushService $webPushService)
    {
        $this->webPushService = $webPushService;
    }

    /**
     * Отправить тестовое уведомление текущему пользователю
     */
    public function sendTestNotification(Request $request): JsonResponse
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Пользователь не авторизован',
            ], 401);
        }

        $payload = WebPushService::createNotification(
            'Тестовое уведомление',
            'Это тестовое push-уведомление от Объект+. Если вы видите это сообщение, значит всё работает!',
            [
                'icon' => '/images/icons/icon.svg',
                'badge' => '/images/icons/icon.svg',
                'data' => [
                    'url' => route('home'),
                    'test' => true,
                ],
                'actions' => [
                    [
                        'action' => 'open',
                        'title' => 'Открыть',
                    ],
                    [
                        'action' => 'close',
                        'title' => 'Закрыть',
                    ]
                ]
            ]
        );

        $result = $this->webPushService->sendToUser($userId, $payload);

        return response()->json($result);
    }

    /**
     * Отправить кастомное уведомление
     */
    public function sendCustomNotification(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'nullable|integer|exists:users,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'integer|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'icon' => 'nullable|string',
            'image' => 'nullable|string',
            'url' => 'nullable|string',
            'actions' => 'nullable|array',
            'requireInteraction' => 'nullable|boolean',
        ]);

        $payload = [
            'title' => $validated['title'],
            'body' => $validated['body'],
            'icon' => $validated['icon'] ?? null,
            'image' => $validated['image'] ?? null,
            'data' => [
                'url' => $validated['url'] ?? '/',
            ],
            'actions' => $validated['actions'] ?? [],
            'requireInteraction' => $validated['requireInteraction'] ?? false,
        ];

        // Определяем кому отправлять
        if (isset($validated['user_id'])) {
            $result = $this->webPushService->sendToUser($validated['user_id'], $payload);
        } elseif (isset($validated['user_ids'])) {
            $result = $this->webPushService->sendToUsers($validated['user_ids'], $payload);
        } else {
            $result = $this->webPushService->sendToAll($payload);
        }

        return response()->json($result);
    }

    /**
     * Отправить уведомление всем пользователям
     */
    public function sendToAll(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'icon' => 'nullable|string',
            'image' => 'nullable|string',
            'url' => 'nullable|string',
        ]);

        $payload = WebPushService::createNotification(
            $validated['title'],
            $validated['body'],
            [
                'icon' => $validated['icon'] ?? null,
                'image' => $validated['image'] ?? null,
                'data' => [
                    'url' => $validated['url'] ?? '/',
                ],
            ]
        );

        $result = $this->webPushService->sendToAll($payload);

        return response()->json($result);
    }
}
