<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushSubscriptionController extends Controller
{
    /**
     * Сохранить или обновить подписку на push-уведомления
     */
    public function subscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
            'contentEncoding' => 'nullable|string',
        ]);

        try {
            $userId = Auth::id();
            
            $subscription = PushSubscription::updateOrCreateSubscription(
                $userId,
                $validated
            );

            return response()->json([
                'success' => true,
                'message' => 'Подписка успешно сохранена',
                'subscription_id' => $subscription->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при сохранении подписки: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Отписаться от push-уведомлений
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => 'required|string',
        ]);

        try {
            $deleted = PushSubscription::where('endpoint', $validated['endpoint'])
                ->delete();

            return response()->json([
                'success' => true,
                'message' => $deleted ? 'Подписка успешно удалена' : 'Подписка не найдена',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении подписки: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Получить публичный VAPID ключ
     */
    public function getVapidPublicKey(): JsonResponse
    {
        return response()->json([
            'publicKey' => config('webpush.vapid.public_key'),
        ]);
    }

    /**
     * Получить список всех подписок текущего пользователя
     */
    public function getSubscriptions(): JsonResponse
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Пользователь не авторизован',
            ], 401);
        }

        $subscriptions = PushSubscription::where('user_id', $userId)->get();

        return response()->json([
            'success' => true,
            'subscriptions' => $subscriptions,
        ]);
    }
}
