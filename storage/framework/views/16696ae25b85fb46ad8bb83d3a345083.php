

<?php $__env->startSection('content'); ?>
<div class="minimal-container py-3 py-md-4">
    <!-- Заголовок -->
    <div class="d-flex align-items-center justify-content-between mb-3 mb-md-4 flex-wrap gap-2">
        <div class="d-flex align-items-center">
            <i class="bi bi-speedometer2 text-primary me-2" style="font-size: 1.5rem;"></i>
            <h1 class="mb-0">Админ-панель</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('admin.promocodes')); ?>" class="btn btn-success">
                <i class="bi bi-tag-fill me-1"></i>
                Промокоды
            </a>
            <a href="<?php echo e(route('admin.clients')); ?>" class="btn btn-primary">
                <i class="bi bi-people-fill me-1"></i>
                Клиенты
            </a>
        </div>
    </div>

    <!-- Статистика пользователей -->
    <div class="card mb-3 mb-md-4">
        <div class="card-header d-flex align-items-center">
            <i class="bi bi-people-fill me-2"></i>
            <strong>Статистика пользователей</strong>
        </div>
        <div class="card-body p-2 p-md-3">
            <!-- Мобильный вид: вертикальные карточки -->
            <div class="row g-2 g-md-3">
                <div class="col-6 col-md-4">
                    <div class="stat-card stat-card-primary">
                        <div class="stat-icon">
                            <i class="bi bi-person-fill-check"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo e($usersStats['total']); ?></div>
                            <div class="stat-label">Всего</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stat-card stat-card-danger">
                        <div class="stat-icon">
                            <i class="bi bi-shield-fill-check"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo e($usersStats['admins']); ?></div>
                            <div class="stat-label">Админы</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stat-card stat-card-success">
                        <div class="stat-icon">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo e($usersStats['foremen']); ?></div>
                            <div class="stat-label">Прорабы</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stat-card stat-card-info">
                        <div class="stat-icon">
                            <i class="bi bi-tools"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo e($usersStats['executors']); ?></div>
                            <div class="stat-label">Исполнители</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stat-card stat-card-warning">
                        <div class="stat-icon">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo e($usersStats['clients']); ?></div>
                            <div class="stat-label">Клиенты</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Статистика подписок -->
    <div class="card mb-3 mb-md-4">
        <div class="card-header d-flex align-items-center">
            <i class="bi bi-star-fill me-2"></i>
            <strong>Статистика подписок</strong>
        </div>
        <div class="card-body p-2 p-md-3">
            <div class="row g-2 g-md-3">
                <div class="col-6 col-md-4">
                    <div class="stat-card stat-card-success">
                        <div class="stat-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo e($subscriptionsStats['active']); ?></div>
                            <div class="stat-label">Активные</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stat-card stat-card-warning">
                        <div class="stat-icon">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo e($subscriptionsStats['expired']); ?></div>
                            <div class="stat-label">Истекли</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stat-card stat-card-danger">
                        <div class="stat-icon">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo e($subscriptionsStats['cancelled']); ?></div>
                            <div class="stat-label">Отменены</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Статистика тарифов -->
    <div class="card mb-3 mb-md-4">
        <div class="card-header d-flex align-items-center">
            <i class="bi bi-credit-card-fill me-2"></i>
            <strong>Оплаченные тарифы</strong>
        </div>
        <div class="card-body p-2 p-md-3">
            <?php if($paidPlans->count() > 0): ?>
                <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $paidPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-2 px-md-3">
                            <span class="fw-medium"><?php echo e($plan['plan_name']); ?></span>
                            <span class="badge bg-primary rounded-pill"><?php echo e($plan['count']); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center text-muted py-3">
                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                    <p class="mb-0 mt-2">Нет оплаченных тарифов</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Статистика платежей -->
    <div class="card mb-3 mb-md-4">
        <div class="card-header d-flex align-items-center">
            <i class="bi bi-cash-stack me-2"></i>
            <strong>Платежи</strong>
        </div>
        <div class="card-body p-2 p-md-3">
            <div class="row g-2 g-md-3">
                <div class="col-12 col-md-4">
                    <div class="stat-card stat-card-success">
                        <div class="stat-icon">
                            <i class="bi bi-currency-ruble"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo e(number_format($paymentsStats['total_revenue'], 0, ',', ' ')); ?> ₽</div>
                            <div class="stat-label">Общая выручка</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stat-card stat-card-primary">
                        <div class="stat-icon">
                            <i class="bi bi-check2-all"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo e($paymentsStats['total_payments']); ?></div>
                            <div class="stat-label">Успешные</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stat-card stat-card-warning">
                        <div class="stat-icon">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo e($paymentsStats['pending_payments']); ?></div>
                            <div class="stat-label">В ожидании</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Стили для карточек статистики с акцентом на мобильные */
.stat-card {
    background: white;
    border-radius: 12px;
    padding: 0.75rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: transform 0.2s, box-shadow 0.2s;
    height: 100%;
}

.stat-card:active {
    transform: scale(0.98);
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.12);
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.stat-content {
    flex: 1;
    min-width: 0;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.stat-label {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 500;
    margin-top: 2px;
}

/* Цветовые варианты */
.stat-card-primary .stat-icon {
    background: #e7f1ff;
    color: #0d6efd;
}

.stat-card-success .stat-icon {
    background: #d1f4e0;
    color: #198754;
}

.stat-card-danger .stat-icon {
    background: #ffe5e5;
    color: #dc3545;
}

.stat-card-warning .stat-icon {
    background: #fff3cd;
    color: #ffc107;
}

.stat-card-info .stat-icon {
    background: #cfe2ff;
    color: #0dcaf0;
}

/* Адаптация для планшетов и десктопа */
@media (min-width: 768px) {
    .stat-card {
        padding: 1.25rem;
        gap: 1rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }

    .stat-value {
        font-size: 2rem;
    }

    .stat-label {
        font-size: 0.875rem;
    }
}

/* Улучшение списков для мобильных */
@media (max-width: 767px) {
    .list-group-item {
        padding: 0.75rem !important;
        font-size: 0.875rem;
    }

    .badge {
        font-size: 0.7rem;
    }

    .card-header {
        font-size: 0.9rem;
        padding: 0.75rem 1rem;
    }
}

/* Убираем горизонтальный скролл */
.minimal-container {
    max-width: 100%;
    overflow-x: hidden;
}

/* Защита от выделения на мобильных */
.stat-card,
.list-group-item {
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
    -webkit-touch-callout: none;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views\admin\index.blade.php ENDPATH**/ ?>