<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use App\Services\WebPushService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushTestController extends Controller
{
    protected WebPushService $webPushService;

    public function __construct(WebPushService $webPushService)
    {
        $this->webPushService = $webPushService;
    }

    /**
     * Отправить тестовое push-уведомление на конкретную подписку
     */
    public function sendTestPush(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'icon' => 'nullable|string',
            'url' => 'nullable|string',
            'actions' => 'nullable|array',
            'endpoint' => 'nullable|string', // Для отправки на конкретную подписку
        ]);

        $payload = [
            'title' => $validated['title'],
            'body' => $validated['body'],
            'icon' => $validated['icon'] ?? '/images/icons/icon.svg',
            'data' => [
                'url' => $validated['url'] ?? '/',
            ],
            'actions' => $validated['actions'] ?? [],
        ];

        // Если указан endpoint, отправляем только на него
        if (isset($validated['endpoint'])) {
            $subscription = PushSubscription::where('endpoint', $validated['endpoint'])->first();
            
            if (!$subscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Подписка не найдена',
                    'sent' => 0,
                    'failed' => 0,
                ], 404);
            }

            $result = $this->webPushService->sendToSubscriptions(
                collect([$subscription]),
                $payload
            );
        } else {
            // Отправляем всем подпискам (для тестирования)
            $result = $this->webPushService->sendToAll($payload);
        }

        return response()->json($result);
    }
}
