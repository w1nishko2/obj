

<?php $__env->startSection('content'); ?>
<div class="minimal-container py-4">
    <div class="payment-history-page">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>История платежей</h1>
            <a href="<?php echo e(route('pricing.index')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Купить подписку
            </a>
        </div>

        <?php if($payments->isEmpty()): ?>
            <div class="empty-state">
                <i class="bi bi-receipt" style="font-size: 3rem; color: #9ca3af;"></i>
                <h4>История платежей пуста</h4>
                <p class="text-muted">У вас пока нет совершенных платежей</p>
                <a href="<?php echo e(route('pricing.index')); ?>" class="btn btn-primary mt-3">
                    Выбрать тариф
                </a>
            </div>
        <?php else: ?>
            <div class="payments-list">
                <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="payment-item">
                    <div class="payment-item-header">
                        <div>
                            <h5><?php echo e($payment->plan->name); ?></h5>
                            <small class="text-muted">Платеж #<?php echo e($payment->id); ?></small>
                        </div>
                        <div class="text-end">
                            <div class="payment-amount"><?php echo e(number_format($payment->amount, 0, ',', ' ')); ?> ₽</div>
                            <?php if($payment->status === 'succeeded'): ?>
                                <span class="badge bg-success">Оплачено</span>
                            <?php elseif($payment->status === 'pending'): ?>
                                <span class="badge bg-warning">Ожидается</span>
                            <?php elseif($payment->status === 'cancelled'): ?>
                                <span class="badge bg-secondary">Отменено</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Ошибка</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="payment-item-details">
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">Дата создания</small>
                                <div><?php echo e($payment->created_at->format('d.m.Y H:i')); ?></div>
                            </div>
                            <?php if($payment->paid_at): ?>
                            <div class="col-md-4">
                                <small class="text-muted">Дата оплаты</small>
                                <div><?php echo e($payment->paid_at->format('d.m.Y H:i')); ?></div>
                            </div>
                            <?php endif; ?>
                            <?php if($payment->subscription): ?>
                            <div class="col-md-4">
                                <small class="text-muted">Подписка</small>
                                <div>
                                    <?php if($payment->subscription->isActive()): ?>
                                        <span class="text-success"><i class="bi bi-check-circle-fill"></i> Активна</span>
                                    <?php else: ?>
                                        <span class="text-muted">Истекла</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if($payment->description): ?>
                    <div class="payment-item-description">
                        <small class="text-muted"><?php echo e($payment->description); ?></small>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="mt-4">
                <?php echo e($payments->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.payment-history-page {
    max-width: 900px;
    margin: 0 auto;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.payments-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.payment-item {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1.25rem;
    transition: box-shadow 0.2s;
}

.payment-item:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.payment-item-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f3f4f6;
}

.payment-item-header h5 {
    margin: 0;
    font-size: 1.1rem;
    color: #000000;
}

.payment-amount {
    font-size: 1.25rem;
    font-weight: 600;
    color: #000000;
    margin-bottom: 0.25rem;
}

.payment-item-details {
    margin-bottom: 0.75rem;
}

.payment-item-details .col-md-4 {
    margin-bottom: 0.5rem;
}

.payment-item-description {
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid #f3f4f6;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views\payment\history.blade.php ENDPATH**/ ?>