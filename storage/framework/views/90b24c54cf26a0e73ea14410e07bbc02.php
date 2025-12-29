<div class="user-row d-flex align-items-center" onclick="openEditModal(<?php echo e($user->id); ?>)">
    <div class="user-info">
        <div class="user-name">
            <?php echo e($user->name); ?>

            <?php if($user->account_type): ?>
                <?php if($user->account_type == 'foreman'): ?>
                    <span class="role-badge role-foreman">Прораб</span>
                <?php elseif($user->account_type == 'executor'): ?>
                    <span class="role-badge role-executor">Исполнитель</span>
                <?php elseif($user->account_type == 'client'): ?>
                    <span class="role-badge role-client">Клиент</span>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="user-phone">
            <i class="bi bi-phone"></i>
            <?php echo e($user->phone ?? 'Не указан'); ?>

        </div>
    </div>
    <div class="user-status">
        <div class="status-badge">
            <?php if($user->activeSubscription): ?>
                <span class="badge bg-success"><?php echo e($user->activeSubscription->plan->name); ?></span>
            <?php else: ?>
                <span class="text-muted small">Нет подписки</span>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\OSPanel\domains\work\resources\views\admin\partials\user-row.blade.php ENDPATH**/ ?>