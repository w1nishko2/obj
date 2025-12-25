@extends('layouts.app')

@section('content')
<div class="minimal-container py-4">
    <div class="payment-success-page">
        <div class="text-center mb-4">
            @if($payment->status === 'succeeded')
                <div class="success-icon mb-3">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                </div>
                <h1 class="mb-2">Оплата прошла успешно!</h1>
                <p class="text-muted">Спасибо за покупку. Ваша подписка активирована.</p>
            @elseif($payment->status === 'pending')
                <div class="pending-icon mb-3">
                    <i class="bi bi-clock-fill text-warning" style="font-size: 4rem;"></i>
                </div>
                <h1 class="mb-2">Ожидается оплата</h1>
                <p class="text-muted">Платеж в обработке. Это может занять несколько минут.</p>
            @else
                <div class="error-icon mb-3">
                    <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                </div>
                <h1 class="mb-2">Ошибка оплаты</h1>
                <p class="text-muted">К сожалению, платеж не прошел. Попробуйте снова.</p>
            @endif
        </div>

        <div class="payment-details-card">
            <h4 class="mb-3">Детали платежа</h4>
            <table class="table">
                <tbody>
                    <tr>
                        <td><strong>Номер платежа:</strong></td>
                        <td>#{{ $payment->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Тариф:</strong></td>
                        <td>{{ $payment->plan->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Сумма:</strong></td>
                        <td>{{ number_format($payment->amount, 0, ',', ' ') }} ₽</td>
                    </tr>
                    <tr>
                        <td><strong>Статус:</strong></td>
                        <td>
                            @if($payment->status === 'succeeded')
                                <span class="badge bg-success">Оплачено</span>
                            @elseif($payment->status === 'pending')
                                <span class="badge bg-warning">Ожидается</span>
                            @elseif($payment->status === 'cancelled')
                                <span class="badge bg-secondary">Отменено</span>
                            @else
                                <span class="badge bg-danger">Ошибка</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Дата создания:</strong></td>
                        <td>{{ $payment->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                    @if($payment->paid_at)
                    <tr>
                        <td><strong>Дата оплаты:</strong></td>
                        <td>{{ $payment->paid_at->format('d.m.Y H:i') }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if($payment->status === 'succeeded' && $payment->subscription)
        <div class="subscription-info-card mt-3">
            <h4 class="mb-3"><i class="bi bi-star-fill text-warning"></i> Информация о подписке</h4>
            <p><strong>Статус:</strong> 
                <span class="badge bg-success">Активна</span>
            </p>
            <p><strong>Начало:</strong> {{ $payment->subscription->started_at->format('d.m.Y H:i') }}</p>
            @if($payment->subscription->expires_at)
            <p><strong>Истекает:</strong> {{ $payment->subscription->expires_at->format('d.m.Y H:i') }}</p>
            @else
            <p><strong>Длительность:</strong> Бессрочно</p>
            @endif
        </div>
        @endif

        <div class="text-center mt-4">
            @if($payment->status === 'succeeded')
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-house-fill"></i> Перейти к проектам
                </a>
                <a href="{{ route('payment.history') }}" class="btn btn-outline-secondary btn-lg ms-2">
                    <i class="bi bi-clock-history"></i> История платежей
                </a>
            @elseif($payment->status === 'pending')
                <a href="{{ route('pricing.index') }}" class="btn btn-secondary btn-lg">
                    <i class="bi bi-arrow-left"></i> Вернуться к тарифам
                </a>
                <button onclick="location.reload()" class="btn btn-primary btn-lg ms-2">
                    <i class="bi bi-arrow-clockwise"></i> Обновить статус
                </button>
            @else
                <a href="{{ route('pricing.index') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-arrow-left"></i> Вернуться к тарифам
                </a>
            @endif
        </div>
    </div>
</div>

<style>
.payment-success-page {
    max-width: 700px;
    margin: 0 auto;
}

.payment-details-card,
.subscription-info-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1.5rem;
}

.payment-details-card h4,
.subscription-info-card h4 {
    color: #000000;
    font-weight: 600;
}

.payment-details-card table td {
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.payment-details-card table tr:last-child td {
    border-bottom: none;
}
</style>
@endsection
