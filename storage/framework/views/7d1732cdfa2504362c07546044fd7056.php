

<?php $__env->startSection('content'); ?>
<div class="minimal-container">
    <?php if(session('success')): ?>
        <div class="minimal-alert">
            <i class="bi bi-check-circle"></i>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <!-- Хедер проекта -->
    <div class="project-header-minimal">
        <div class="back-button">
            <a href="<?php echo e(route('projects.index')); ?>" class="minimal-btn minimal-btn-ghost">
                <i class="bi bi-arrow-left"></i>
                Проекты
            </a>
        </div>

        <div class="project-title-section">
            <h1><?php echo e($project->name); ?></h1>
            <?php if($project->address): ?>
                <div class="project-meta">
                    <i class="bi bi-geo-alt"></i>
                    <?php echo e($project->address); ?>

                </div>
            <?php endif; ?>
        </div>

        <div class="project-stats">
            <div class="stat-item">
                <div class="stat-label">Прогресс</div>
                <div class="stat-value"><?php echo e($project->progress); ?>%</div>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" style="width: <?php echo e($project->progress); ?>%"></div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Статус</div>
                <span class="project-status status-<?php echo e(strtolower(str_replace(' ', '-', $project->status))); ?>">
                    <?php echo e($project->status); ?>

                </span>
            </div>
        </div>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('generateReports', $project)): ?>
            <div class="project-actions">
                <div class="dropdown">
                    <button class="minimal-btn minimal-btn-ghost" data-bs-toggle="dropdown">
                        <i class="bi bi-file-earmark-text"></i>
                        Смета
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu minimal-dropdown">
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('projects.estimate.pdf', $project)); ?>" target="_blank">
                                <i class="bi bi-file-pdf"></i>
                                Скачать PDF
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('projects.estimate.excel', $project)); ?>">
                                <i class="bi bi-file-excel"></i>
                                Скачать Excel
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="<?php echo e(route('projects.documents.templates', $project)); ?>" class="minimal-btn minimal-btn-ghost">
                    <i class="bi bi-file-earmark-text"></i>
                    Документы
                </a>
            </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $project)): ?>
            <a href="<?php echo e(route('projects.edit', $project)); ?>" class="minimal-btn minimal-btn-ghost">
                <i class="bi bi-pencil"></i>
                Изменить
            </a>
        <?php endif; ?>
    </div>

    <!-- Табы -->
    <div class="minimal-tabs">
        <button class="tab-button active" data-tab="stages">
            <i class="bi bi-list-check"></i>
            Этапы
            <span class="tab-badge"><?php echo e($project->stages->count()); ?></span>
        </button>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manageTeam', $project)): ?>
            <button class="tab-button" data-tab="participants">
                <i class="bi bi-people"></i>
                Команда
                <span class="tab-badge"><?php echo e($project->participants->count()); ?></span>
            </button>
        <?php else: ?>
            <button class="tab-button" data-tab="foreman">
                <i class="bi bi-person"></i>
                Прораб
            </button>
        <?php endif; ?>
    </div>

    <!-- Контент табов -->
    <div class="tab-content-minimal">
        <!-- Вкладка Этапы -->
        <div class="tab-panel active" data-panel="stages">
            <?php if($project->stages->isEmpty()): ?>
                <div class="empty-state-small">
                    <i class="bi bi-list-check"></i>
                    <p>Этапы еще не добавлены</p>
                </div>
            <?php else: ?>
                <div class="stages-list">
                    <?php $__currentLoopData = $project->stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('stages.show', [$project, $stage])); ?>" class="stage-item-minimal">
                            <div class="stage-item-header">
                                <h3><?php echo e($stage->name); ?></h3>
                                <div class="stage-status-badges">
                                    <?php if($stage->tasks->count() > 0): ?>
                                        <span class="mini-badge"><?php echo e($stage->tasks->count()); ?> задач</span>
                                    <?php endif; ?>
                                    <span class="stage-status status-<?php echo e(strtolower(str_replace(' ', '-', $stage->status))); ?>">
                                        <?php echo e($stage->status); ?>

                                    </span>
                                </div>
                            </div>
                            
                            <div class="stage-dates">
                                <i class="bi bi-calendar3"></i>
                                <?php echo e($stage->start_date->format('d.m.Y')); ?> — <?php echo e($stage->end_date->format('d.m.Y')); ?>

                            </div>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manageTeam', $project)): ?>
                                <?php if($stage->participants->count() > 0): ?>
                                <div class="stage-participants">
                                    <i class="bi bi-people"></i>
                                    <?php echo e($stage->participants->pluck('name')->join(', ')); ?>

                                </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if($stage->description): ?>
                                <div class="stage-description">
                                    <?php echo e(Str::limit($stage->description, 100)); ?>

                                </div>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Вкладка Участники -->
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manageTeam', $project)): ?>
            <div class="tab-panel" data-panel="participants">
                <?php if($project->participants->isEmpty()): ?>
                    <div class="empty-state-small">
                        <i class="bi bi-people"></i>
                        <p>Участники еще не добавлены</p>
                    </div>
                <?php else: ?>
                    <div class="participants-list">
                        <?php $__currentLoopData = $project->participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="participant-item-minimal">
                                <div class="participant-avatar">
                                    <?php echo e(strtoupper(substr($participant->name, 0, 1))); ?>

                                </div>
                                <div class="participant-info">
                                    <div class="participant-name"><?php echo e($participant->name); ?></div>
                                    <div class="participant-role"><?php echo e($participant->role); ?></div>
                                </div>
                                <a href="tel:<?php echo e($participant->phone); ?>" class="minimal-btn minimal-btn-ghost minimal-btn-icon">
                                    <i class="bi bi-telephone"></i>
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="tab-panel" data-panel="foreman">
                <?php
                    // Находим владельца проекта
                    $foreman = $project->owner;
                ?>
                <?php if($foreman): ?>
                    <div class="foreman-contact-card">
                        <div class="foreman-avatar-large">
                            <?php echo e(strtoupper(substr($foreman->name, 0, 1))); ?>

                        </div>
                        <h3><?php echo e($foreman->name); ?></h3>
                        <p>Прораб проекта</p>
                        <a href="tel:<?php echo e($foreman->phone); ?>" class="minimal-btn minimal-btn-primary minimal-btn-large">
                            <i class="bi bi-telephone"></i>
                            Позвонить
                        </a>
                    </div>
                <?php else: ?>
                    <div class="empty-state-small">
                        <i class="bi bi-person"></i>
                        <p>Информация о прорабе недоступна</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Переключение табов
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
        const tabName = button.dataset.tab;
        
        // Убираем активность со всех
        document.querySelectorAll('.tab-button').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        
        // Добавляем активность к выбранным
        button.classList.add('active');
        document.querySelector(`[data-panel="${tabName}"]`).classList.add('active');
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views\projects\show-minimal.blade.php ENDPATH**/ ?>