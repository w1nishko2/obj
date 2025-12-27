

<?php $__env->startSection('content'); ?>
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
            <?php
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
            ?>

            <?php $__currentLoopData = $plansData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slug => $planData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-6 col-lg-3">
                <div class="pricing-card <?php if($planData['popular'] ?? false): ?> pricing-card-popular <?php endif; ?>" data-plan="<?php echo e($slug); ?>">
                    <?php if($planData['popular'] ?? false): ?>
                    <div class="pricing-ribbon">⭐ ПОПУЛЯРНЫЙ</div>
                    <?php endif; ?>

                    <div class="pricing-header">
                        <div class="pricing-icon"><?php echo e($planData['icon']); ?></div>
                        <h3 class="pricing-title"><?php echo e($planData['name']); ?></h3>
                        <p class="pricing-subtitle"><?php echo e($planData['subtitle']); ?></p>
                        
                        <div class="pricing-price">
                            <?php if($slug === 'free'): ?>
                                <span class="price">Бесплатно</span>
                                <span class="period"><?php echo e($planData['period']); ?></span>
                            <?php else: ?>
                                <div class="price-monthly">
                                    <span class="price"><?php echo e(number_format($planData['price_monthly'], 0, ',', ' ')); ?> ₽</span>
                                    <span class="period">/<?php echo e($planData['period']); ?></span>
                                </div>
                                <div class="price-yearly" style="display: none;">
                                    <span class="price"><?php echo e(number_format($planData['price_yearly'], 0, ',', ' ')); ?> ₽</span>
                                    <span class="period">/год</span>
                                    <div class="price-save">Экономия <?php echo e(number_format($planData['price_monthly'] * 12 - $planData['price_yearly'], 0, ',', ' ')); ?> ₽</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <ul class="pricing-features">
                        <?php $__currentLoopData = $planData['features']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="<?php if($feature['available']): ?> available <?php else: ?> unavailable <?php endif; ?>">
                            <i class="bi <?php if($feature['available']): ?> bi-check-circle-fill <?php else: ?> bi-x-circle-fill <?php endif; ?>"></i>
                            <span <?php if($feature['highlight'] ?? false): ?> class="feature-highlight" <?php endif; ?>><?php echo e($feature['text']); ?></span>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>

                    <?php if(auth()->guard()->check()): ?>
                        <?php
                            $isActive = $userPlan === $slug || 
                                       ($userPlan === 'starter_yearly' && $slug === 'starter') ||
                                       ($userPlan === 'professional_yearly' && $slug === 'professional') ||
                                       ($userPlan === 'corporate_yearly' && $slug === 'corporate');
                        ?>
                        
                        <?php if($isActive): ?>
                            <button class="pricing-btn active" disabled>
                                <i class="bi bi-check-circle"></i> Ваш тариф
                            </button>
                        <?php else: ?>
                            <form action="<?php echo e(route('payment.create')); ?>" method="POST" class="plan-form">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="plan_slug" class="plan-slug-input" value="<?php echo e($slug); ?>">
                                <button type="submit" class="pricing-btn <?php if($planData['popular'] ?? false): ?> popular <?php endif; ?>">
                                    <?php if($slug === 'free'): ?>
                                        <i class="bi bi-gift"></i> Активировать
                                    <?php else: ?>
                                        <i class="bi bi-credit-card"></i> Выбрать тариф
                                    <?php endif; ?>
                                </button>
                            </form>
                            <small class="pricing-note">
                                <i class="bi bi-shield-check"></i> Безопасная оплата через ЮKassa
                            </small>
                        <?php endif; ?>
                    <?php else: ?>
                        <button class="pricing-btn" onclick="window.location.href='<?php echo e(route('login')); ?>'">
                            <i class="bi bi-box-arrow-in-right"></i> Войти для активации
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views/pricing/new-index.blade.php ENDPATH**/ ?>