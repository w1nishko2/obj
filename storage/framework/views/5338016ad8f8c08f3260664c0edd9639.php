

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-gear-fill"></i> Инструкция по настройке webhook ЮKassa</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill"></i> Для корректной работы системы оплаты необходимо настроить webhook в личном кабинете ЮKassa
                    </div>

                    <h5 class="mt-4">Шаг 1: Откройте личный кабинет ЮKassa</h5>
                    <p>Перейдите по ссылке: <a href="https://yookassa.ru/my" target="_blank">https://yookassa.ru/my</a></p>

                    <h5 class="mt-4">Шаг 2: Настройте HTTP-уведомления</h5>
                    <ol>
                        <li>В меню выберите <strong>"Настройки"</strong> → <strong>"HTTP-уведомления"</strong></li>
                        <li>Включите HTTP-уведомления</li>
                        <li>В поле "URL для уведомлений" введите:</li>
                    </ol>
                    <div class="alert alert-success">
                        <code style="font-size: 1.1rem;"><?php echo e(config('app.url')); ?>/payment/webhook</code>
                        <button class="btn btn-sm btn-outline-success float-end" onclick="copyWebhookUrl()">
                            <i class="bi bi-clipboard"></i> Копировать
                        </button>
                    </div>

                    <h5 class="mt-4">Шаг 3: Выберите события</h5>
                    <p>Отметьте следующие события для уведомлений:</p>
                    <ul>
                        <li>✅ <code>payment.succeeded</code> - Успешный платеж</li>
                        <li>✅ <code>payment.canceled</code> - Отмена платежа (опционально)</li>
                    </ul>

                    <h5 class="mt-4">Шаг 4: Сохраните настройки</h5>
                    <p>Нажмите кнопку "Сохранить" в личном кабинете ЮKassa</p>

                    <hr class="my-4">

                    <h5>Тестирование оплаты</h5>
                    <p>Для тестирования используйте тестовую карту:</p>
                    <table class="table table-bordered">
                        <tr>
                            <td><strong>Номер карты</strong></td>
                            <td><code>5555 5555 5555 4477</code></td>
                        </tr>
                        <tr>
                            <td><strong>Срок действия</strong></td>
                            <td>Любая будущая дата</td>
                        </tr>
                        <tr>
                            <td><strong>CVC</strong></td>
                            <td>Любые 3 цифры</td>
                        </tr>
                        <tr>
                            <td><strong>3-D Secure код</strong></td>
                            <td>Любое значение</td>
                        </tr>
                    </table>

                    <div class="alert alert-warning mt-4">
                        <i class="bi bi-exclamation-triangle-fill"></i> <strong>Важно!</strong> 
                        Webhook должен быть доступен из интернета. Если вы тестируете на локальной машине, используйте ngrok или localtunnel.
                    </div>

                    <div class="text-center mt-4">
                        <a href="<?php echo e(route('pricing.index')); ?>" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-left"></i> Вернуться к тарифам
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyWebhookUrl() {
    const url = "<?php echo e(config('app.url')); ?>/payment/webhook";
    navigator.clipboard.writeText(url).then(function() {
        alert('URL скопирован в буфер обмена!');
    }, function(err) {
        console.error('Ошибка копирования: ', err);
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views\payment\webhook-setup.blade.php ENDPATH**/ ?>