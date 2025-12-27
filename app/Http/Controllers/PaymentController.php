<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Payment;
use App\Models\Subscription;
use YooKassa\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->middleware('auth')->except(['webhook']);
        
        // Инициализация клиента YooKassa
        $this->client = new Client();
        $this->client->setAuth(
            config('yookassa.shop_id'),
            config('yookassa.secret_key')
        );
    }

    /**
     * Создание платежа для покупки тарифа
     */
    public function createPayment(Request $request)
    {
        $request->validate([
            'plan_slug' => 'required|string|exists:plans,slug',
        ]);

        $user = auth()->user();
        $plan = Plan::where('slug', $request->plan_slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Проверяем, что пользователь еще не имеет активной подписки на этот тариф
        $existingSubscription = Subscription::where('user_id', $user->id)
            ->where('plan_id', $plan->id)
            ->where('status', 'active')
            ->first();

        if ($existingSubscription && $existingSubscription->isActive()) {
            return redirect()->route('pricing.index')
                ->with('error', 'У вас уже есть активная подписка на этот тариф.');
        }

        // ВАЖНО: Бесплатный тариф активируется напрямую без оплаты
        if ($plan->slug === 'free' || $plan->price == 0) {
            return $this->activateFreePlan($user, $plan);
        }

        try {
            // Генерируем уникальный идентификатор платежа
            $idempotenceKey = Str::uuid()->toString();

            // Создаем запись о платеже в БД
            $payment = Payment::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'yookassa_payment_id' => 'pending_' . $idempotenceKey,
                'status' => 'pending',
                'amount' => $plan->price,
                'currency' => 'RUB',
                'description' => "Оплата тарифа \"{$plan->name}\"",
            ]);

            // Создаем платеж в YooKassa
            $yookassaPayment = $this->client->createPayment([
                'amount' => [
                    'value' => number_format((float)$plan->price, 2, '.', ''),
                    'currency' => 'RUB',
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => route('payment.success', ['payment' => $payment->id]),
                ],
                'capture' => true,
                'description' => "Оплата тарифа \"{$plan->name}\" для пользователя {$user->name}",
                'metadata' => [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'payment_id' => $payment->id,
                ],
            ], $idempotenceKey);

            // Обновляем ID платежа от YooKassa
            $payment->update([
                'yookassa_payment_id' => $yookassaPayment->getId(),
                'metadata' => [
                    'confirmation_url' => $yookassaPayment->getConfirmation()->getConfirmationUrl(),
                ],
            ]);

            // Перенаправляем на страницу оплаты YooKassa
            return redirect($yookassaPayment->getConfirmation()->getConfirmationUrl());

        } catch (\Exception $e) {
            Log::error('YooKassa payment creation error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('pricing.index')
                ->with('error', 'Произошла ошибка при создании платежа. Попробуйте позже.');
        }
    }

    /**
     * Страница успешной оплаты
     */
    public function success(Request $request, Payment $payment)
    {
        // Проверяем, что платеж принадлежит текущему пользователю
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        // Проверяем статус платежа в YooKassa
        try {
            $yookassaPayment = $this->client->getPaymentInfo($payment->yookassa_payment_id);
            
            if ($yookassaPayment->getStatus() === 'succeeded' && $payment->status !== 'succeeded') {
                $this->processSuccessfulPayment($payment, $yookassaPayment);
            }
        } catch (\Exception $e) {
            Log::error('Error checking payment status: ' . $e->getMessage());
        }

        return view('payment.success', compact('payment'));
    }

    /**
     * Webhook для обработки уведомлений от YooKassa
     */
    public function webhook(Request $request)
    {
        try {
            // Получаем JSON из тела запроса
            $source = file_get_contents('php://input');
            $requestBody = json_decode($source, true);

            // Логируем входящий webhook
            Log::info('YooKassa webhook received', $requestBody);

            // Проверяем, что это уведомление о платеже
            if (!isset($requestBody['event']) || $requestBody['event'] !== 'payment.succeeded') {
                return response()->json(['status' => 'ignored'], 200);
            }

            $yookassaPaymentId = $requestBody['object']['id'];
            
            // Находим платеж в БД
            $payment = Payment::where('yookassa_payment_id', $yookassaPaymentId)->first();

            if (!$payment) {
                Log::warning('Payment not found for webhook', ['yookassa_payment_id' => $yookassaPaymentId]);
                return response()->json(['status' => 'not_found'], 404);
            }

            // Если платеж уже обработан, игнорируем
            if ($payment->status === 'succeeded') {
                return response()->json(['status' => 'already_processed'], 200);
            }

            // Получаем полную информацию о платеже
            $yookassaPayment = $this->client->getPaymentInfo($yookassaPaymentId);

            if ($yookassaPayment->getStatus() === 'succeeded') {
                $this->processSuccessfulPayment($payment, $yookassaPayment);
                
                return response()->json(['status' => 'success'], 200);
            }

            return response()->json(['status' => 'pending'], 200);

        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'error' => $e->getMessage(),
            ]);

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Обработка успешного платежа
     */
    protected function processSuccessfulPayment(Payment $payment, $yookassaPayment)
    {
        try {
            // Обновляем статус платежа
            $payment->markAsSucceeded();
            $payment->update([
                'metadata' => array_merge($payment->metadata ?? [], [
                    'yookassa_data' => [
                        'status' => $yookassaPayment->getStatus(),
                        'captured_at' => $yookassaPayment->getCapturedAt()?->format('Y-m-d H:i:s'),
                    ],
                ]),
            ]);

            // Создаем или обновляем подписку пользователя
            $subscription = Subscription::create([
                'user_id' => $payment->user_id,
                'plan_id' => $payment->plan_id,
                'status' => 'active',
                'started_at' => now(),
                'expires_at' => null, // Будет установлено в методе activate()
            ]);

            // Связываем платеж с подпиской
            $payment->update(['subscription_id' => $subscription->id]);

            // Активируем подписку с датой оплаты (обновит данные пользователя)
            $subscription->activate($payment->paid_at ?? now());

            // Обновляем тип аккаунта пользователя на "прораб"
            $user = $payment->user;
            if (!$user->isForeman()) {
                $user->upgradeToForeman();
            }

            Log::info('Payment processed successfully', [
                'payment_id' => $payment->id,
                'user_id' => $payment->user_id,
                'plan_id' => $payment->plan_id,
                'subscription_id' => $subscription->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing successful payment: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Активация бесплатного тарифа (без оплаты)
     */
    protected function activateFreePlan($user, $plan)
    {
        try {
            // Создаем подписку напрямую
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'started_at' => now(),
                'expires_at' => null, // Будет установлено в activate()
            ]);

            // Активируем подписку
            $subscription->activate();

            // ВАЖНО: Даем права прораба и для бесплатного тарифа
            if (!$user->isForeman()) {
                $user->upgradeToForeman();
            }

            Log::info('Free plan activated', [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'subscription_id' => $subscription->id,
            ]);

            return redirect()->route('home')
                ->with('success', 'Бесплатный тариф активирован на 14 дней! Начните создавать проекты.');

        } catch (\Exception $e) {
            Log::error('Error activating free plan: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('pricing.index')
                ->with('error', 'Произошла ошибка при активации тарифа. Попробуйте позже.');
        }
    }

    /**
     * История платежей пользователя
     */
    public function history()
    {
        $payments = auth()->user()->payments()
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('payment.history', compact('payments'));
    }
}
