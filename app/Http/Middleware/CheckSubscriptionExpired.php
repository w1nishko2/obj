<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Если пользователь не авторизован, пропускаем проверку
        if (!$user) {
            return $next($request);
        }

        // Проверяем, не истекла ли подписка
        if ($user->hasAnyPlan() && $user->isSubscriptionExpired()) {
            // Сбрасываем просроченную подписку
            $user->subscription_type = null;
            $user->subscription_expires_at = null;
            
            // ВАЖНО: Снимаем статус прораба
            if ($user->account_type === 'foreman') {
                $user->account_type = 'client';
            }
            
            $user->save();

            // Если это не AJAX запрос, показываем уведомление
            if (!$request->expectsJson()) {
                return redirect()->route('pricing.index')
                    ->with('warning', 'Ваша подписка истекла. Пожалуйста, оформите новую подписку для продолжения работы.');
            }

            // Для AJAX запросов возвращаем ошибку
            return response()->json([
                'error' => 'Subscription expired',
                'message' => 'Ваша подписка истекла. Пожалуйста, оформите новую подписку.'
            ], 402); // Payment Required
        }

        return $next($request);
    }
}
