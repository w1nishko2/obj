

<?php $__env->startSection('content'); ?>
<div class="minimal-container py-3 py-md-4">
    <!-- Заголовок -->
    <div class="d-flex align-items-center mb-3 mb-md-4">
        <a href="<?php echo e(route('admin.promocodes')); ?>" class="btn btn-light me-2">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="mb-0"><?php echo e($promocode->code); ?></h1>
                <?php if($promocode->is_active): ?>
                    <span class="badge bg-success">Активен</span>
                <?php else: ?>
                    <span class="badge bg-secondary">Неактивен</span>
                <?php endif; ?>
            </div>
            <div class="text-muted">
                <i class="bi bi-percent me-1"></i>
                <?php echo e($promocode->discount_percent); ?>% скидка
            </div>
        </div>
    </div>

    <!-- Общая статистика -->
    <div class="card mb-3 mb-md-4">
        <div class="card-header">
            <strong>Общая статистика</strong>
        </div>
        <div class="card-body p-2 p-md-3">
            <div class="row g-2 g-md-3">
                <div class="col-6 col-md-3">
                    <div class="stat-card stat-card-primary">
                        <div class="stat-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo e($stats['total_usages']); ?></div>
                            <div class="stat-label">Использований</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card stat-card-success">
                        <div class="stat-icon">
                            <i class="bi bi-currency-ruble"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo e(number_format($stats['total_revenue'], 0, ',', ' ')); ?> ₽</div>
                            <div class="stat-label">Выручка</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card stat-card-danger">
                        <div class="stat-icon">
                            <i class="bi bi-tag-fill"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo e(number_format($stats['total_discount'], 0, ',', ' ')); ?> ₽</div>
                            <div class="stat-label">Скидка дана</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card stat-card-info">
                        <div class="stat-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo e($stats['unique_users']); ?></div>
                            <div class="stat-label">Пользователей</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- История использований -->
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <i class="bi bi-clock-history me-2"></i>
            <strong>История использований</strong>
        </div>
        <div class="card-body p-0">
            <?php if($usages->count() > 0): ?>
                <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $usages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item px-2 px-md-3 py-2">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="flex-grow-1">
                                    <div class="fw-bold mb-1"><?php echo e($usage->user->name); ?></div>
                                    <div class="small text-muted mb-1">
                                        <i class="bi bi-phone me-1"></i><?php echo e($usage->user->phone ?? 'Не указан'); ?>

                                    </div>
                                    <div class="small">
                                        <i class="bi bi-calendar me-1"></i><?php echo e($usage->created_at->format('d.m.Y H:i')); ?>

                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success"><?php echo e(number_format($usage->final_amount, 0, ',', ' ')); ?> ₽</div>
                                    <div class="small text-muted text-decoration-line-through"><?php echo e(number_format($usage->original_amount, 0, ',', ' ')); ?> ₽</div>
                                    <div class="small text-danger">-<?php echo e(number_format($usage->discount_amount, 0, ',', ' ')); ?> ₽</div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                    <p class="mb-0 mt-2">Промокод еще не использовался</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Используем те же стили stat-card что и на главной */
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
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.stat-label {
    font-size: 0.7rem;
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

.stat-card-info .stat-icon {
    background: #cfe2ff;
    color: #0dcaf0;
}

/* Мобильная адаптация */
@media (max-width: 767px) {
    .stat-card {
        padding: 0.6rem;
        gap: 0.6rem;
    }

    .stat-icon {
        width: 35px;
        height: 35px;
        font-size: 1.1rem;
    }

    .stat-value {
        font-size: 1.1rem;
    }

    .stat-label {
        font-size: 0.65rem;
    }

    .list-group-item {
        padding: 0.75rem !important;
        font-size: 0.875rem;
    }
}

/* Защита от выделения */
.stat-card,
.list-group-item {
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
    -webkit-touch-callout: none;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views\admin\promocode-analytics.blade.php ENDPATH**/ ?>