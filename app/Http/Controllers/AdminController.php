<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\Payment;
use App\Models\Promocode;
use App\Models\PromocodeUsage;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Отображение главной страницы админки со статистикой
     */
    public function index()
    {
        // Статистика пользователей по ролям
        $usersStats = [
            'total' => User::count(),
            'admins' => User::where('is_admin', true)->count(),
            'foremen' => User::where('account_type', 'foreman')->count(),
            'executors' => User::where('account_type', 'executor')->count(),
            'clients' => User::where('account_type', 'client')->count(),
        ];

        // Статистика по подпискам
        $subscriptionsStats = [
            'active' => Subscription::where('status', 'active')->count(),
            'expired' => Subscription::where('status', 'expired')->count(),
            'cancelled' => Subscription::where('status', 'cancelled')->count(),
        ];

        // Статистика по тарифам (оплаченные подписки)
        $paidPlans = Subscription::select('plan_id', DB::raw('count(*) as total'))
            ->whereIn('status', ['active', 'expired'])
            ->groupBy('plan_id')
            ->with('plan')
            ->get()
            ->map(function($item) {
                return [
                    'plan_name' => $item->plan ? $item->plan->name : 'Неизвестный тариф',
                    'count' => $item->total,
                ];
            });

        // Общая статистика по платежам
        $paymentsStats = [
            'total_revenue' => Payment::where('status', 'succeeded')->sum('amount'),
            'total_payments' => Payment::where('status', 'succeeded')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
        ];

        return view('admin.index', compact(
            'usersStats',
            'subscriptionsStats',
            'paidPlans',
            'paymentsStats'
        ));
    }

    /**
     * Страница управления клиентами и подписками
     */
    public function clients(Request $request)
    {
        $search = $request->get('search', '');
        $page = $request->get('page', 1);
        $perPage = 20;

        $query = User::with(['activeSubscription.plan'])
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->paginate($perPage);
        $plans = Plan::where('is_active', true)->get();

        // Если запрос AJAX - возвращаем JSON
        if ($request->ajax()) {
            $usersData = collect($users->items())->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'account_type' => $user->account_type,
                    'active_subscription' => $user->activeSubscription ? [
                        'id' => $user->activeSubscription->id,
                        'expires_at' => $user->activeSubscription->expires_at,
                        'plan' => [
                            'id' => $user->activeSubscription->plan->id,
                            'name' => $user->activeSubscription->plan->name
                        ]
                    ] : null
                ];
            });

            return response()->json([
                'users' => $usersData,
                'hasMore' => $users->hasMorePages(),
                'nextPage' => $users->currentPage() + 1
            ]);
        }

        return view('admin.clients', compact('users', 'plans', 'search'));
    }

    /**
     * Обновление роли пользователя
     */
    public function updateUserRole(Request $request, $userId)
    {
        $request->validate([
            'account_type' => 'nullable|in:foreman,executor,client'
        ]);

        $user = User::findOrFail($userId);
        $user->account_type = $request->account_type;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Роль пользователя обновлена',
            'user' => $user
        ]);
    }

    /**
     * Обновление подписки пользователя
     */
    public function updateUserSubscription(Request $request, $userId)
    {
        $request->validate([
            'plan_id' => 'nullable|exists:plans,id',
            'action' => 'required|in:add,remove'
        ]);

        $user = User::findOrFail($userId);

        if ($request->action === 'remove') {
            // Удаляем активную подписку
            Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update(['status' => 'cancelled']);

            // Обнуляем subscription_type
            $user->subscription_type = null;
            $user->subscription_expires_at = null;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Подписка отменена'
            ]);
        }

        if ($request->action === 'add' && $request->plan_id) {
            // Отменяем старые активные подписки
            Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update(['status' => 'cancelled']);

            // Создаем новую подписку
            $plan = Plan::findOrFail($request->plan_id);
            $expiresAt = now()->addDays($plan->duration_days);
            
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'started_at' => now(),
                'expires_at' => $expiresAt,
            ]);

            // Обновляем subscription_type и subscription_expires_at в таблице users
            $user->subscription_type = $plan->slug;
            $user->subscription_expires_at = $expiresAt;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Подписка установлена',
                'subscription' => $subscription->load('plan')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Неверные параметры'
        ], 400);
    }

    /**
     * Страница управления промокодами
     */
    public function promocodes()
    {
        $promocodes = Promocode::withCount('usages')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($promo) {
                $stats = $promo->getStats();
                $promo->stats = $stats;
                return $promo;
            });

        return view('admin.promocodes', compact('promocodes'));
    }

    /**
     * Создание промокода
     */
    public function createPromocode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:promocodes,code|max:50',
            'discount_percent' => 'required|integer|min:1|max:100',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:now',
            'description' => 'nullable|string|max:500',
        ]);

        $promocode = Promocode::create([
            'code' => strtoupper($request->code),
            'discount_percent' => $request->discount_percent,
            'is_active' => true,
            'usage_limit' => $request->usage_limit,
            'usage_count' => 0,
            'expires_at' => $request->expires_at,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Промокод создан успешно',
            'promocode' => $promocode
        ]);
    }

    /**
     * Обновление промокода
     */
    public function updatePromocode(Request $request, $id)
    {
        $request->validate([
            'discount_percent' => 'sometimes|integer|min:1|max:100',
            'is_active' => 'sometimes|boolean',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'description' => 'nullable|string|max:500',
        ]);

        $promocode = Promocode::findOrFail($id);
        $promocode->update($request->only([
            'discount_percent',
            'is_active',
            'usage_limit',
            'expires_at',
            'description'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Промокод обновлен',
            'promocode' => $promocode
        ]);
    }

    /**
     * Удаление промокода
     */
    public function deletePromocode($id)
    {
        $promocode = Promocode::findOrFail($id);
        $promocode->delete();

        return response()->json([
            'success' => true,
            'message' => 'Промокод удален'
        ]);
    }

    /**
     * Аналитика по промокоду
     */
    public function promocodeAnalytics($id)
    {
        $promocode = Promocode::with(['usages.user', 'usages.payment'])
            ->findOrFail($id);

        $stats = $promocode->getStats();
        $usages = $promocode->usages()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.promocode-analytics', compact('promocode', 'stats', 'usages'));
    }

    /**
     * Проверка промокода (API для пользователей)
     */
    public function validatePromocode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $promocode = Promocode::where('code', strtoupper($request->code))->first();

        if (!$promocode) {
            return response()->json([
                'valid' => false,
                'message' => 'Промокод не найден'
            ], 404);
        }

        if (!$promocode->isValid()) {
            $message = 'Промокод неактивен';
            if ($promocode->expires_at && $promocode->expires_at->isPast()) {
                $message = 'Промокод истек';
            } elseif ($promocode->usage_limit && $promocode->usage_count >= $promocode->usage_limit) {
                $message = 'Лимит использований исчерпан';
            }
            
            return response()->json([
                'valid' => false,
                'message' => $message
            ], 400);
        }

        $result = $promocode->apply($request->amount);

        return response()->json([
            'valid' => true,
            'promocode_id' => $promocode->id,
            'discount_percent' => $result['discount_percent'],
            'original_amount' => $result['original_amount'],
            'discount_amount' => $result['discount_amount'],
            'final_amount' => $result['final_amount'],
            'message' => "Промокод применен! Скидка {$result['discount_percent']}%"
        ]);
    }
}
