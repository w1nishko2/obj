

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-lock-fill" style="font-size: 4rem; color: #a70000;"></i>
                    </div>
                    <h2 class="mb-3">Требуется подписка</h2>
                    <p class="text-muted mb-4">
                        Для создания проектов необходимо оформить подписку. <br>
                        Начните с тарифа <strong>"Бесплатный"</strong> на 14 дней бесплатно или <strong>"Стартовый"</strong> всего за 490 рублей!
                    </p>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill"></i> 
                        После оплаты вы сможете создать до 2 проектов и получите доступ ко всем базовым функциям системы.
                    </div>

                    <a href="<?php echo e(route('pricing.index')); ?>" class="btn btn-primary btn-lg mt-3">
                        <i class="bi bi-star-fill"></i> Выбрать тариф
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views\subscription-required.blade.php ENDPATH**/ ?>