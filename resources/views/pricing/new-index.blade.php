@extends('layouts.app')

@section('content')
<div class="minimal-container py-4">
    <div class="pricing-page">
        <div class="text-center mb-4">
            <h1 class="mb-2">Тарифы "Объект+"</h1>
            <p class="text-muted">Выберите тариф, соответствующий масштабу вашего бизнеса</p>
        </div>

        <!-- Переключатель месяц/год -->
        <div class="text-center mb-4">
            <div class="btn-group" role="group">
                <input type="radio" class="btn-check" name="billingCycle" id="monthly" autocomplete="off" checked>
                <label class="btn btn-outline-dark" for="monthly">Месячная оплата</label>

                <input type="radio" class="btn-check" name="billingCycle" id="yearly" autocomplete="off">
                <label class="btn btn-outline-dark" for="yearly">Годовая оплата <span class="badge bg-danger ms-1">-17%</span></label>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            @php
                $plansData = [
                    'free' => [
                        'icon' => '🆓',
                        'name' => 'Бесплатный',
                        'subtitle' => 'Для тестирования системы',
                        'price_monthly' => 0,
                        'price_yearly' => 0,
                        'period' => '14 дней',
                        'features' => [
                            ['text' => 'До 1 проекта', 'available' => true],
                            ['text' => 'До 5 участников', 'available' => true],
                            ['text' => 'Базовые функции', 'available' => true],
                            ['text' => 'Push-уведомления', 'available' => true],
                            ['text' => 'Без генерации документов', 'available' => false],
                            ['text' => 'Без смет', 'available' => false],
                        ],
                    ],
                    'starter' => [
                        'icon' => '🥉',
                        'name' => 'Стартовый',
                        'subtitle' => 'Для прорабов-одиночек',
                        'price_monthly' => 490,
                        'price_yearly' => 4900,
                        'period' => 'месяц',
                        'features' => [
                            ['text' => 'До 3 активных проектов', 'available' => true, 'highlight' => true],
                            ['text' => 'До 10 участников на проект', 'available' => true],
                            ['text' => 'Генерация смет (PDF/Excel)', 'available' => true, 'highlight' => true],
                            ['text' => 'Генерация договоров и актов', 'available' => true, 'highlight' => true],
                            ['text' => 'Push-уведомления', 'available' => true],
                            ['text' => 'Архивирование проектов', 'available' => true],
                        ],
                    ],
                    'professional' => [
                        'icon' => '🥈',
                        'name' => 'Профессиональный',
                        'subtitle' => 'Для опытных прорабов',
                        'price_monthly' => 1290,
                        'price_yearly' => 12900,
                        'period' => 'месяц',
                        'popular' => true,
                        'features' => [
                            ['text' => 'До 10 активных проектов', 'available' => true, 'highlight' => true],
                            ['text' => 'До 30 участников на проект', 'available' => true],
                            ['text' => 'Все функции стартового тарифа', 'available' => true],
                            ['text' => 'Вечный архив проектов', 'available' => true, 'highlight' => true],
                            ['text' => 'Расширенные шаблоны', 'available' => true],
                            ['text' => 'Push-уведомления', 'available' => true],
                        ],
                    ],
                    'corporate' => [
                        'icon' => '🥇',
                        'name' => 'Корпоративный',
                        'subtitle' => 'Для строительных компаний',
                        'price_monthly' => 2990,
                        'price_yearly' => 29900,
                        'period' => 'месяц',
                        'features' => [
                            ['text' => 'Неограниченно проектов', 'available' => true, 'highlight' => true],
                            ['text' => 'Неограниченно участников', 'available' => true, 'highlight' => true],
                            ['text' => 'Все функции профессионального', 'available' => true],
                            ['text' => 'Несколько прорабов/менеджеров', 'available' => true],
                            ['text' => 'Персональный менеджер', 'available' => true],
                            ['text' => 'Поддержка 24/7', 'available' => true],
                            ['text' => 'Обучение команды', 'available' => true],
                            ['text' => 'Кастомные доработки', 'available' => true],
                        ],
                    ],
                ];

                $userPlan = auth()->check() ? auth()->user()->subscription_type : null;
            @endphp

            @foreach($plansData as $slug => $planData)
            <div class="col-md-6 col-lg-3">
                <div class="pricing-card @if($planData['popular'] ?? false) pricing-card-popular @endif" data-plan="{{ $slug }}">
                    @if($planData['popular'] ?? false)
                    <div class="pricing-ribbon">⭐ ПОПУЛЯРНЫЙ</div>
                    @endif

                    <div class="pricing-header">
                        <div class="pricing-icon">{{ $planData['icon'] }}</div>
                        <h3 class="pricing-title">{{ $planData['name'] }}</h3>
                        <p class="pricing-subtitle">{{ $planData['subtitle'] }}</p>
                        
                        <div class="pricing-price">
                            @if($slug === 'free')
                                <span class="price">Бесплатно</span>
                                <span class="period">{{ $planData['period'] }}</span>
                            @else
                                <div class="price-monthly">
                                    <span class="price">{{ number_format($planData['price_monthly'], 0, ',', ' ') }} ₽</span>
                                    <span class="period">/{{ $planData['period'] }}</span>
                                </div>
                                <div class="price-yearly" style="display: none;">
                                    <span class="price">{{ number_format($planData['price_yearly'], 0, ',', ' ') }} ₽</span>
                                    <span class="period">/год</span>
                                    <div class="price-save">Экономия {{ number_format($planData['price_monthly'] * 12 - $planData['price_yearly'], 0, ',', ' ') }} ₽</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <ul class="pricing-features">
                        @foreach($planData['features'] as $feature)
                        <li class="@if($feature['available']) available @else unavailable @endif">
                            <i class="bi @if($feature['available']) bi-check-circle-fill @else bi-x-circle-fill @endif"></i>
                            <span @if($feature['highlight'] ?? false) class="feature-highlight" @endif>{{ $feature['text'] }}</span>
                        </li>
                        @endforeach
                    </ul>

                    @auth
                        @php
                            $isActive = $userPlan === $slug || 
                                       ($userPlan === 'starter_yearly' && $slug === 'starter') ||
                                       ($userPlan === 'professional_yearly' && $slug === 'professional') ||
                                       ($userPlan === 'corporate_yearly' && $slug === 'corporate');
                        @endphp
                        
                        @if($isActive)
                            <button class="pricing-btn active" disabled>
                                <i class="bi bi-check-circle"></i> Ваш тариф
                            </button>
                        @else
                            @if($slug === 'free')
                                <form action="{{ route('payment.create') }}" method="POST" class="plan-form">
                                    @csrf
                                    <input type="hidden" name="plan_slug" class="plan-slug-input" value="{{ $slug }}">
                                    <button type="submit" class="pricing-btn">
                                        <i class="bi bi-gift"></i> Активировать
                                    </button>
                                </form>
                            @else
                                <button type="button" class="pricing-btn @if($planData['popular'] ?? false) popular @endif open-payment-modal"
                                        data-plan="{{ $slug }}"
                                        data-name="{{ $planData['name'] }}"
                                        data-price-monthly="{{ $planData['price_monthly'] }}"
                                        data-price-yearly="{{ $planData['price_yearly'] }}">
                                    <i class="bi bi-credit-card"></i> Выбрать тариф
                                </button>
                                <small class="pricing-note">
                                    <i class="bi bi-shield-check"></i> Безопасная оплата через ЮKassa
                                </small>
                            @endif
                        @endif
                    @else
                        <button class="pricing-btn" onclick="window.location.href='{{ route('login') }}'">
                            <i class="bi bi-box-arrow-in-right"></i> Войти для активации
                        </button>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>

        <!-- Сравнительная таблица -->
        <div class="comparison-table mt-5">
            <h4 class="text-center mb-4">Подробное сравнение тарифов</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Функция</th>
                            <th class="text-center">🆓 Бесплатный</th>
                            <th class="text-center">🥉 Стартовый</th>
                            <th class="text-center bg-light">🥈 Профессиональный</th>
                            <th class="text-center">🥇 Корпоративный</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Активных проектов</strong></td>
                            <td class="text-center">1</td>
                            <td class="text-center">3</td>
                            <td class="text-center bg-light"><strong>10</strong></td>
                            <td class="text-center">∞</td>
                        </tr>
                        <tr>
                            <td><strong>Участников на проект</strong></td>
                            <td class="text-center">5</td>
                            <td class="text-center">10</td>
                            <td class="text-center bg-light">30</td>
                            <td class="text-center">∞</td>
                        </tr>
                        <tr>
                            <td>Этапы и задачи</td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center bg-light"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                        </tr>
                        <tr>
                            <td>Комментарии и фото</td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center bg-light"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                        </tr>
                        <tr>
                            <td><strong>Генерация смет (PDF/Excel)</strong></td>
                            <td class="text-center"><i class="bi bi-x-circle-fill text-danger"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center bg-light"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                        </tr>
                        <tr>
                            <td><strong>Генерация договоров и актов</strong></td>
                            <td class="text-center"><i class="bi bi-x-circle-fill text-danger"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center bg-light"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                        </tr>
                        <tr>
                            <td>Push-уведомления</td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center bg-light"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                        </tr>
                        <tr>
                            <td><strong>Архивирование проектов</strong></td>
                            <td class="text-center"><i class="bi bi-x-circle-fill text-danger"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center bg-light"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                        </tr>
                        <tr>
                            <td>Персональный менеджер</td>
                            <td class="text-center"><i class="bi bi-x-circle-fill text-danger"></i></td>
                            <td class="text-center"><i class="bi bi-x-circle-fill text-danger"></i></td>
                            <td class="text-center bg-light"><i class="bi bi-x-circle-fill text-danger"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                        </tr>
                        <tr>
                            <td>Обучение команды</td>
                            <td class="text-center"><i class="bi bi-x-circle-fill text-danger"></i></td>
                            <td class="text-center"><i class="bi bi-x-circle-fill text-danger"></i></td>
                            <td class="text-center bg-light"><i class="bi bi-x-circle-fill text-danger"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FAQ -->
        <div class="pricing-faq mt-5">
            <h4 class="text-center mb-4">Часто задаваемые вопросы</h4>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            Как работает бесплатный тариф?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Бесплатный тариф предоставляется на 14 дней для знакомства с системой. Вы можете создать 1 проект, 
                            добавить до 5 участников и протестировать базовые функции управления проектами. 
                            Генерация смет и документов недоступна на бесплатном тарифе.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            В чем разница между стартовым и профессиональным тарифами?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <strong>Стартовый</strong> (490₽/мес) - до 3 проектов, 10 участников, все документы включены.<br>
                            <strong>Профессиональный</strong> (1 290₽/мес) - до 10 проектов, 30 участников, + архивирование проектов и расширенные шаблоны.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            Что произойдет с данными при переходе на другой тариф?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Все ваши проекты, задачи, документы и данные остаются в системе при любом переходе между тарифами. 
                            При понижении тарифа просто могут быть ограничены некоторые функции (например, создание новых проектов 
                            сверх лимита текущего тарифа).
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            Выгодна ли годовая подписка?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Да! При годовой подписке вы экономите <strong>17%</strong>:<br>
                            • Стартовый: 4 900₽ вместо 5 880₽ (экономия 980₽)<br>
                            • Профессиональный: 12 900₽ вместо 15 480₽ (экономия 2 580₽)<br>
                            • Корпоративный: 29 900₽ вместо 35 880₽ (экономия 5 980₽)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.pricing-page {
    max-width: 1200px;
    margin: 0 auto;
}

.pricing-card {
    background: #ffffff;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 2rem;
    position: relative;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.pricing-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    transform: translateY(-4px);
}

.pricing-card-popular {
    border-color: #a70000;
    border-width: 3px;
}

.pricing-ribbon {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #a70000, #d60000);
    color: #ffffff;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(167, 0, 0, 0.3);
}

.pricing-icon {
    font-size: 3rem;
    text-align: center;
    margin-bottom: 0.5rem;
}

.pricing-title {
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 0.25rem;
    color: #111827;
}

.pricing-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    text-align: center;
    margin-bottom: 1.5rem;
}

.pricing-price {
    text-align: center;
    margin-bottom: 1.5rem;
}

.pricing-price .price {
    font-size: 2.5rem;
    font-weight: 800;
    color: #111827;
    display: block;
    line-height: 1;
}

.pricing-card-popular .pricing-price .price {
    color: #a70000;
}

.pricing-price .period {
    font-size: 1rem;
    color: #6b7280;
    display: block;
    margin-top: 0.5rem;
}

.price-save {
    font-size: 0.85rem;
    color: #059669;
    font-weight: 600;
    margin-top: 0.5rem;
}

.pricing-features {
    list-style: none;
    padding: 0;
    margin: 0 0 1.5rem 0;
    flex-grow: 1;
}

.pricing-features li {
    padding: 0.75rem 0;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.pricing-features li.available {
    color: #111827;
}

.pricing-features li.unavailable {
    color: #9ca3af;
}

.pricing-features i {
    font-size: 1.25rem;
    flex-shrink: 0;
}

.pricing-features li.available i {
    color: #059669;
}

.pricing-features li.unavailable i {
    color: #d1d5db;
}

.feature-highlight {
    font-weight: 600;
}

.pricing-btn {
    width: 100%;
    padding: 1rem;
    background: #111827;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.pricing-btn:hover {
    background: #000000;
    transform: translateY(-2px);
}

.pricing-btn.popular {
    background: linear-gradient(135deg, #a70000, #d60000);
}

.pricing-btn.popular:hover {
    background: linear-gradient(135deg, #8b0000, #a70000);
}

.pricing-btn.active {
    background: #059669;
    cursor: not-allowed;
}

.pricing-note {
    display: block;
    text-align: center;
    margin-top: 0.75rem;
    font-size: 0.8rem;
    color: #6b7280;
}

.comparison-table {
    background: #f9fafb;
    border-radius: 12px;
    padding: 2rem;
}

.comparison-table th {
    background: #111827;
    color: #ffffff;
    font-weight: 600;
    padding: 1rem;
}

.comparison-table td {
    padding: 0.875rem;
    vertical-align: middle;
}

@media (max-width: 991px) {
    .pricing-card {
        padding: 1.5rem;
    }
    
    .pricing-title {
        font-size: 1.25rem;
    }
    
    .pricing-price .price {
        font-size: 2rem;
    }
}

@media (max-width: 767px) {
    .comparison-table {
        overflow-x: auto;
    }
    
    .comparison-table table {
        font-size: 0.85rem;
    }
}

/* Модалка оплаты */
#paymentModal .wizard-header h2 {
    margin-bottom: 0.5rem;
}

#paymentModal .wizard-header p {
    color: #6b7280;
    font-size: 1rem;
}

#paymentModal .minimal-card-body {
    background: #f8f9fa;
}

#paymentModal #discount-info {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 576px) {
    #paymentModal .wizard-container {
        padding: 0 0.5rem;
    }
    
    #paymentModal .minimal-card-body {
        padding: 0.75rem;
    }
    
    #paymentModal .form-group-minimal .d-flex {
        flex-direction: column;
    }
    
    #paymentModal #apply-promocode {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthlyRadio = document.getElementById('monthly');
    const yearlyRadio = document.getElementById('yearly');
    const priceMonthly = document.querySelectorAll('.price-monthly');
    const priceYearly = document.querySelectorAll('.price-yearly');
    const planForms = document.querySelectorAll('.plan-form');

    function updatePricing() {
        const isYearly = yearlyRadio.checked;
        
        priceMonthly.forEach(el => el.style.display = isYearly ? 'none' : 'block');
        priceYearly.forEach(el => el.style.display = isYearly ? 'block' : 'none');
        
        // Обновляем slug для форм
        planForms.forEach(form => {
            const input = form.querySelector('.plan-slug-input');
            const planSlug = input.value.replace('_yearly', '');
            input.value = isYearly ? `${planSlug}_yearly` : planSlug;
        });
    }

    monthlyRadio.addEventListener('change', updatePricing);
    yearlyRadio.addEventListener('change', updatePricing);
});
</script>

<!-- Модалка оплаты с промокодом -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen m-0">
        <div class="modal-content">
            <form action="{{ route('payment.create') }}" method="POST" id="payment-form" class="d-flex flex-column h-100">
                @csrf
                <input type="hidden" name="plan_slug" id="payment-plan-slug">
                <input type="hidden" name="promocode" id="payment-promocode">
                
                <div class="modal-header border-0 pb-2">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="px-4">
                    <div class="wizard-header text-center">
                        <h2>Оформление подписки</h2>
                        <p id="modal-plan-name-subtitle">Выбранный тариф</p>
                    </div>
                </div>
                
                <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                    <div class="wizard-container" style="max-width: 600px; width: 100%;">
                        <!-- Информация о тарифе -->
                        <div class="minimal-card mb-3">
                            <div class="minimal-card-header">
                                <span><i class="bi bi-credit-card"></i> <span id="modal-plan-name">Тариф</span></span>
                            </div>
                            <div class="minimal-card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span style="font-size: 0.95rem;">Стоимость:</span>
                                    <span style="font-size: 1.25rem; font-weight: 600;"><span id="modal-original-price">0</span> ₽</span>
                                </div>
                                
                                <div id="discount-info" class="d-none">
                                    <div class="d-flex justify-content-between align-items-center text-success mb-2" style="padding-top: 0.75rem; border-top: 1px dashed #dee2e6;">
                                        <span style="font-size: 0.95rem;">Скидка (<span id="discount-percent">0</span>%):</span>
                                        <span style="font-size: 1.1rem; font-weight: 600;">-<span id="discount-amount">0</span> ₽</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center" style="padding-top: 0.75rem; border-top: 2px solid #dee2e6;">
                                        <span style="font-size: 1.1rem; font-weight: 700;">Итого к оплате:</span>
                                        <span style="font-size: 1.5rem; font-weight: 700; color: #a70000;"><span id="modal-final-price">0</span> ₽</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Промокод -->
                        <div class="form-group-minimal">
                            <label><i class="bi bi-tag"></i> Промокод (необязательно)</label>
                            <div class="d-flex gap-2">
                                <input type="text" class="minimal-input" id="promocode" placeholder="Введите промокод" style="flex: 1;">
                                <button type="button" class="minimal-btn minimal-btn-ghost" id="apply-promocode" style="white-space: nowrap;">
                                    Применить
                                </button>
                            </div>
                            <div id="promocode-error" class="text-danger mt-2 d-none" style="font-size: 0.9rem;"></div>
                            <div id="promocode-success" class="text-success mt-2 d-none" style="font-size: 0.9rem;">
                                <i class="bi bi-check-circle-fill"></i> <span></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="minimal-btn minimal-btn-primary">
                        <i class="bi bi-credit-card"></i> Оплатить <span id="pay-button-price"></span>
                    </button>
                </div>
                
                <div class="text-center pb-3">
                    <small class="text-muted">
                        <i class="bi bi-shield-check"></i> Безопасная оплата через ЮKassa
                    </small>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    const yearlyRadio = document.getElementById('yearly');
    
    let selectedPlan = null;
    let currentPrice = 0;
    let appliedPromocode = null;

    // Открытие модалки при клике на "Выбрать тариф"
    document.querySelectorAll('.open-payment-modal').forEach(button => {
        button.addEventListener('click', function() {
            const planSlug = this.dataset.plan;
            const planName = this.dataset.name;
            const priceMonthly = parseFloat(this.dataset.priceMonthly);
            const priceYearly = parseFloat(this.dataset.priceYearly);
            
            const isYearly = yearlyRadio.checked;
            currentPrice = isYearly ? priceYearly : priceMonthly;
            selectedPlan = isYearly ? `${planSlug}_yearly` : planSlug;
            
            // Заполняем модалку
            const planDisplayName = planName + (isYearly ? ' (Годовая)' : ' (Месячная)');
            document.getElementById('modal-plan-name').textContent = planDisplayName;
            document.getElementById('modal-plan-name-subtitle').textContent = planDisplayName;
            document.getElementById('modal-original-price').textContent = currentPrice;
            document.getElementById('payment-plan-slug').value = selectedPlan;
            document.getElementById('pay-button-price').textContent = currentPrice + ' ₽';
            
            // Сбрасываем промокод
            document.getElementById('promocode').value = '';
            document.getElementById('payment-promocode').value = '';
            document.getElementById('discount-info').classList.add('d-none');
            document.getElementById('promocode-error').classList.add('d-none');
            document.getElementById('promocode-success').classList.add('d-none');
            appliedPromocode = null;
            
            paymentModal.show();
        });
    });

    // Применение промокода
    document.getElementById('apply-promocode').addEventListener('click', function() {
        const promocode = document.getElementById('promocode').value.trim();
        
        if (!promocode) {
            showError('Введите промокод');
            return;
        }

        // AJAX запрос на проверку промокода
        fetch('{{ route('api.promocode.validate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                code: promocode,
                amount: currentPrice
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                appliedPromocode = promocode;
                
                // Показываем информацию о скидке
                document.getElementById('discount-percent').textContent = data.discount_percent;
                document.getElementById('discount-amount').textContent = data.discount_amount.toFixed(2);
                document.getElementById('modal-final-price').textContent = data.final_amount.toFixed(2);
                document.getElementById('pay-button-price').textContent = data.final_amount.toFixed(2) + ' ₽';
                document.getElementById('payment-promocode').value = promocode;
                
                document.getElementById('discount-info').classList.remove('d-none');
                showSuccess('Промокод применен! Скидка ' + data.discount_percent + '%');
            } else {
                showError(data.message || 'Промокод недействителен');
                resetDiscount();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Ошибка при проверке промокода');
            resetDiscount();
        });
    });

    function showError(message) {
        const errorDiv = document.getElementById('promocode-error');
        errorDiv.textContent = message;
        errorDiv.classList.remove('d-none');
        document.getElementById('promocode-success').classList.add('d-none');
    }

    function showSuccess(message) {
        const successDiv = document.getElementById('promocode-success');
        successDiv.querySelector('span').textContent = message;
        successDiv.classList.remove('d-none');
        document.getElementById('promocode-error').classList.add('d-none');
    }

    function resetDiscount() {
        document.getElementById('discount-info').classList.add('d-none');
        document.getElementById('pay-button-price').textContent = currentPrice + ' ₽';
        document.getElementById('payment-promocode').value = '';
        appliedPromocode = null;
    }
});
</script>
@endsection
