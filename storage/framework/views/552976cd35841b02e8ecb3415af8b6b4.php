

<?php $__env->startSection('content'); ?>
<div class="minimal-container py-4">
    <div class="pricing-page">
        <div class="text-center mb-4">
            <h1 class="mb-2">Тарифы и подписка</h1>
            <p class="text-muted">Выберите подходящий тариф для расширения возможностей</p>
        </div>

        <div class="row g-3 justify-content-center">
            <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <!-- Тариф <?php echo e($plan->name); ?> -->
            <div class="col-md-6 col-lg-4">
                <div class="pricing-card <?php echo e($plan->slug === 'yearly' ? 'pricing-card-best' : ''); ?>">
                    <?php if($plan->slug === 'yearly'): ?>
                    <div class="pricing-label">Экономия 25%</div>
                    <?php endif; ?>
                    
                    <div class="pricing-header">
                        <div class="pricing-badge <?php echo e($plan->slug === 'free' ? 'pricing-badge-free' : ($plan->slug === 'yearly' ? 'pricing-badge-best' : '')); ?>">
                            <?php if($plan->slug === 'free'): ?>
                                <i class="bi bi-gift"></i> Дешёвый вход
                            <?php else: ?>
                                <i class="bi bi-star<?php echo e($plan->slug === 'yearly' ? '-fill' : ''); ?>"></i> Статус Прораба
                            <?php endif; ?>
                        </div>
                        <h3><?php echo e($plan->name); ?></h3>
                        <div class="pricing-price">
                            <span class="price"><?php echo e(number_format($plan->price, 0, ',', ' ')); ?> ₽</span>
                            <span class="period">/<?php echo e($plan->duration_days ? ($plan->duration_days === 365 ? 'год' : 'месяц') : 'навсегда'); ?></span>
                        </div>
                        <?php if($plan->slug === 'yearly'): ?>
                        <p class="pricing-save">Выгодно! Вместо 24 000 ₽</p>
                        <?php endif; ?>
                    </div>

                    <ul class="pricing-features">
                        <?php if($plan->slug === 'free'): ?>
                            <li><i class="bi bi-check-circle-fill"></i> <strong>До 2 проектов одновременно</strong></li>
                            <li><i class="bi bi-check-circle-fill"></i> Создание этапов работ</li>
                            <li><i class="bi bi-check-circle-fill"></i> Управление задачами с фото</li>
                            <li><i class="bi bi-check-circle-fill"></i> Добавление участников проекта</li>
                            <li><i class="bi bi-check-circle-fill"></i> Распределение ролей (владелец, прораб, бригадир, заказчик)</li>
                            <li><i class="bi bi-check-circle-fill"></i> Комментарии к задачам</li>
                            <li><i class="bi bi-check-circle-fill"></i> Загрузка файлов и документов</li>
                            <li><i class="bi bi-check-circle-fill"></i> Отслеживание прогресса проектов</li>
                            <li><i class="bi bi-check-circle-fill"></i> Управление материалами по этапам</li>
                            <li><i class="bi bi-x-circle-fill text-muted"></i> <span class="text-muted">Генерация смет (PDF/Excel) недоступна</span></li>
                            <li><i class="bi bi-x-circle-fill text-muted"></i> <span class="text-muted">Генерация документов недоступна</span></li>
                            <li><i class="bi bi-x-circle-fill text-muted"></i> <span class="text-muted">Архивирование проектов недоступно</span></li>
                        <?php else: ?>
                            <li><i class="bi bi-check-circle-fill"></i> <strong>Неограниченное количество проектов</strong></li>
                            <li><i class="bi bi-check-circle-fill"></i> Все возможности бесплатного тарифа</li>
                            <li><i class="bi bi-check-circle-fill"></i> <strong>Генерация смет в PDF и Excel</strong></li>
                            <li><i class="bi bi-check-circle-fill"></i> <strong>Генерация актов выполненных работ</strong></li>
                            <li><i class="bi bi-check-circle-fill"></i> <strong>Генерация договоров подряда</strong></li>
                            <li><i class="bi bi-check-circle-fill"></i> Архивирование завершенных проектов</li>
                            <li><i class="bi bi-check-circle-fill"></i> Экспорт данных проектов</li>
                            <li><i class="bi bi-check-circle-fill"></i> Расширенные шаблоны документов</li>
                            <li><i class="bi bi-check-circle-fill"></i> Приоритетная техническая поддержка</li>
                            <?php if($plan->slug === 'yearly'): ?>
                            <li><i class="bi bi-check-circle-fill"></i> <strong>Экономия 6 000 ₽ в год (25%)</strong></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>

                    <?php if(auth()->guard()->check()): ?>
                        <?php
                            $userPlan = Auth::user()->subscription_type;
                            $hasActivePlan = $userPlan === $plan->slug;
                        ?>
                        
                        <?php if($hasActivePlan): ?>
                            <button class="pricing-btn <?php echo e($plan->slug === 'yearly' ? 'pricing-btn-best' : ''); ?> <?php echo e($plan->slug === 'free' ? 'pricing-btn-free' : ''); ?>" disabled style="opacity: 0.6; cursor: not-allowed;">
                                <i class="bi bi-check-circle"></i> Уже активирован
                            </button>
                            <small class="pricing-note">
                                <i class="bi bi-check-circle text-success"></i> Вы используете этот тариф
                            </small>
                        <?php else: ?>
                            <form action="<?php echo e(route('payment.create')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="plan_slug" value="<?php echo e($plan->slug); ?>">
                                <button type="submit" class="pricing-btn <?php echo e($plan->slug === 'yearly' ? 'pricing-btn-best' : ''); ?> <?php echo e($plan->slug === 'free' ? 'pricing-btn-free' : ''); ?>">
                                    <i class="bi bi-credit-card"></i> <?php echo e($plan->slug === 'free' ? 'Оплатить 50 ₽' : 'Оформить подписку'); ?>

                                </button>
                            </form>
                            <small class="pricing-note">
                                <i class="bi bi-shield-check"></i> Безопасная оплата через ЮKassa
                            </small>
                        <?php endif; ?>
                    <?php else: ?>
                        <button class="pricing-btn <?php echo e($plan->slug === 'yearly' ? 'pricing-btn-best' : ''); ?> <?php echo e($plan->slug === 'free' ? 'pricing-btn-free' : ''); ?>" onclick="window.location.href='<?php echo e(route('login')); ?>'">
                            <i class="bi bi-credit-card"></i> <?php echo e($plan->slug === 'free' ? 'Оплатить 1 ₽' : 'Оформить подписку'); ?>

                        </button>
                        <small class="pricing-note">
                            <i class="bi bi-shield-check"></i> Требуется авторизация
                        </small>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Информация о преимуществах -->
        <div class="pricing-benefits mt-4">
            <h4 class="text-center mb-3">Что дает статус Прораба?</h4>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="benefit-item">
                        <i class="bi bi-infinity"></i>
                        <h5>Без ограничений</h5>
                        <p>Создавайте неограниченное количество проектов для управления всеми объектами одновременно</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefit-item">
                        <i class="bi bi-file-earmark-pdf"></i>
                        <h5>Профессиональная документация</h5>
                        <p>Автоматическая генерация смет, актов и договоров в форматах PDF и Excel с готовыми шаблонами</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefit-item">
                        <i class="bi bi-archive"></i>
                        <h5>Архив проектов</h5>
                        <p>Храните историю завершенных проектов с возможностью экспорта данных для отчетности</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ -->
        <div class="pricing-faq mt-4">
            <h4 class="text-center mb-3">Часто задаваемые вопросы</h4>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            Что именно включает генерация документов?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            С платной подпиской вы сможете автоматически генерировать профессиональные сметы в форматах PDF и Excel, 
                            акты выполненных работ и договоры подряда на основе данных из ваших проектов. Все документы формируются 
                            по готовым шаблонам с возможностью настройки.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            Есть ли ограничения на 1 тарифе?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            На стартовом тарифе "Прораб Старт" вы можете создать максимум 2 проекта одновременно. 
                            Вам доступно создание этапов, задач, добавление участников, загрузка файлов и отслеживание прогресса. 
                            Однако генерация документов (смет, актов, договоров) и архивирование проектов доступны только на платных тарифах.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            Что произойдет с проектами после окончания подписки?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Все ваши проекты и данные останутся в системе и будут доступны для просмотра. Однако функции генерации 
                            документов станут недоступны, а количество активных проектов будет ограничено 2 (как на бесплатном тарифе). 
                            Для работы с большим количеством проектов нужно будет продлить подписку.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            Чем отличаются месячная и годовая подписки?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Функционал обеих подписок полностью идентичен. Разница только в стоимости: 
                            годовая подписка обходится на 6 000 ₽ дешевле (экономия 25%), что составляет 1 500 ₽/месяц вместо 2 000 ₽/месяц.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.pricing-page {
    max-width: 1000px;
    margin: 0 auto;
}

.pricing-card {
    background: #ffffff;
    border: 1px solid #000000;
    border-radius: 8px;
    padding: 1.5rem;
    position: relative;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.pricing-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.pricing-card-best {
    border: 2px solid #a70000;
}

.pricing-label {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: #a70000;
    color: #ffffff;
    padding: 0.25rem 0.75rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.pricing-header {
    text-align: center;
    margin-bottom: 1.5rem;
}

.pricing-badge {
    display: inline-block;
    background: #000000;
    color: #ffffff;
    padding: 0.35rem 0.75rem;
    border-radius: 4px;
    font-size: 0.8rem;
    margin-bottom: 0.75rem;
    font-weight: 500;
}

.pricing-badge-best {
    background: #a70000;
}

.pricing-badge-free {
    background: #28a745;
}

.pricing-header h3 {
    font-size: 1.25rem;
    margin-bottom: 0.75rem;
    color: #000000;
}

.pricing-price {
    margin-bottom: 0.5rem;
}

.pricing-price .price {
    font-size: 2rem;
    font-weight: 700;
    color: #000000;
}

.pricing-card-best .pricing-price .price {
    color: #a70000;
}

.pricing-price .period {
    font-size: 0.9rem;
    color: #6b7280;
}

.pricing-save {
    font-size: 0.85rem;
    color: #a70000;
    font-weight: 600;
    margin: 0;
}

.pricing-features {
    list-style: none;
    padding: 0;
    margin: 0 0 1.5rem 0;
    flex-grow: 1;
}

.pricing-features li {
    padding: 0.5rem 0;
    font-size: 0.9rem;
    color: #000000;
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
}

.pricing-features i {
    color: #a70000;
    font-size: 1rem;
    margin-top: 0.1rem;
    flex-shrink: 0;
}

.pricing-btn {
    width: 100%;
    padding: 0.75rem;
    background: #000000;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: not-allowed;
    opacity: 0.6;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.pricing-btn-best {
    background: #a70000;
}

.pricing-btn-free {
    background: #28a745;
    cursor: pointer;
    opacity: 1;
}

.pricing-btn-free:hover {
    background: #218838;
}

.pricing-note {
    display: block;
    text-align: center;
    margin-top: 0.5rem;
    font-size: 0.75rem;
    color: #6b7280;
}

/* Преимущества */
.pricing-benefits {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1.5rem;
}

.benefit-item {
    text-align: center;
    padding: 1rem;
}

.benefit-item i {
    font-size: 2rem;
    color: #a70000;
    margin-bottom: 0.75rem;
}

.benefit-item h5 {
    font-size: 1rem;
    margin-bottom: 0.5rem;
    color: #000000;
}

.benefit-item p {
    font-size: 0.85rem;
    color: #6b7280;
    margin: 0;
}

/* FAQ */
.pricing-faq .accordion-button {
    font-size: 0.95rem;
    font-weight: 500;
    color: #000000;
}

.pricing-faq .accordion-button:not(.collapsed) {
    background: #f9fafb;
    color: #a70000;
}

.pricing-faq .accordion-body {
    font-size: 0.9rem;
    color: #4b5563;
}

/* Мобильная версия */
@media (max-width: 768px) {
    .pricing-card {
        padding: 1.25rem;
    }
    
    .pricing-header h3 {
        font-size: 1.1rem;
    }
    
    .pricing-price .price {
        font-size: 1.75rem;
    }
    
    .pricing-features li {
        font-size: 0.85rem;
        padding: 0.4rem 0;
    }
    
    .benefit-item {
        padding: 0.75rem;
    }
    
    .benefit-item i {
        font-size: 1.5rem;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views\pricing\index.blade.php ENDPATH**/ ?>