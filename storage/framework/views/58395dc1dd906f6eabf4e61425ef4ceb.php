

<?php $__env->startSection('content'); ?>
<div class="minimal-container">
    <?php if(session('success')): ?>
        <div class="minimal-alert">
            <i class="bi bi-check-circle"></i>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="minimal-header">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <a href="<?php echo e(route('projects.index')); ?>" class="minimal-btn minimal-btn-ghost">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1>Архив проектов</h1>
        </div>
    </div>

    <?php if($projects->isEmpty()): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-archive"></i>
            </div>
            <h3>Архив пуст</h3>
            <p>Здесь будут отображаться завершенные и неактуальные проекты</p>
            <a href="<?php echo e(route('projects.index')); ?>" class="minimal-btn minimal-btn-primary">
                <i class="bi bi-arrow-left"></i>
                Вернуться к проектам
            </a>
        </div>
    <?php else: ?>
        <div class="projects-grid">
            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $userRole = Auth::user()->getRoleInProject($project);
                    $isOwner = $userRole && $userRole->role === 'owner';
                ?>
                <div class="project-card archived-project">
                    <div class="archived-badge">
                        <i class="bi bi-archive"></i>
                        Архив
                    </div>
                    
                    <a href="<?php echo e(route('projects.show', $project)); ?>" style="text-decoration: none; color: inherit;">
                        <div class="project-card-header">
                            <h3><?php echo e($project->name); ?></h3>
                            <span class="project-status status-<?php echo e(strtolower(str_replace(' ', '-', $project->status))); ?>">
                                <?php echo e($project->status); ?>

                            </span>
                        </div>
                        
                        <?php if($project->address): ?>
                            <div class="project-address">
                                <i class="bi bi-geo-alt"></i>
                                <?php echo e($project->address); ?>

                            </div>
                        <?php endif; ?>

                        <div class="project-progress">
                            <div class="progress-info">
                                <span class="progress-label">Прогресс</span>
                                <span class="progress-value"><?php echo e($project->progress); ?>%</span>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" style="width: <?php echo e($project->progress); ?>%"></div>
                            </div>
                        </div>
                    </a>

                    <?php if($isOwner): ?>
                        <div class="archived-actions">
                            <form action="<?php echo e(route('projects.unarchive', $project)); ?>" method="POST" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="minimal-btn minimal-btn-ghost" 
                                        onclick="return confirm('Восстановить проект из архива?')">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                    Восстановить
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>

<style>
.archived-project {
    position: relative;
    opacity: 0.85;
    border: 2px solid var(--color-gray-light);
}

.archived-project:hover {
    opacity: 1;
}

.archived-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: var(--color-gray);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    z-index: 1;
}

.archived-actions {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--color-gray-light);
    display: flex;
    justify-content: center;
}

.archived-actions button {
    width: 100%;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views\projects\archived.blade.php ENDPATH**/ ?>