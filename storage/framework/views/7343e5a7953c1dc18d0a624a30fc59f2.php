

<?php $__env->startSection('content'); ?>
    <style>
        .breadcrumb-compact {
            white-space: nowrap;
            overflow: hidden;
        }

        .breadcrumb-compact .breadcrumb-item {
            display: inline-block;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: middle;
        }

        .breadcrumb-compact .breadcrumb-item:first-child {
            max-width: 100px;
        }

        .breadcrumb-compact .breadcrumb-item.active {
            max-width: 250px;
        }

        .stage-show-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        @media (min-width: 1600px) {
            .stage-show-container {
                max-width: 1600px;
            }
        }
    </style>
    <div class="container stage-show-container">
        <div class="row mb-3">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-compact mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>" title="Проекты">Проекты</a></li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('projects.show', $project)); ?>" title="<?php echo e($project->name); ?>">
                                <?php echo e(Str::limit($project->name, 20)); ?>

                            </a>
                        </li>
                        <li class="breadcrumb-item active" title="<?php echo e($stage->name); ?>"><?php echo e(Str::limit($stage->name, 20)); ?>

                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <h3 class="mb-0"><?php echo e($stage->name); ?></h3>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('createStages', $project)): ?>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editStageModal" title="Редактировать" style="padding: 0.25rem 0.5rem;">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <p class="text-muted mb-1">
                                    <i class="bi bi-calendar"></i>
                                    <?php echo e($stage->start_date->format('d.m.Y')); ?> - <?php echo e($stage->end_date->format('d.m.Y')); ?>

                                </p>
                            </div>
                            <div class="text-end">
                                <div class="mb-2">
                                    <?php if($stage->status === 'Готово'): ?>
                                        <span class="badge bg-success fs-6"><?php echo e($stage->status); ?></span>
                                    <?php elseif($stage->status === 'В работе'): ?>
                                        <span class="badge bg-primary fs-6"><?php echo e($stage->status); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary fs-6"><?php echo e($stage->status); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewCosts', $project)): ?>
                                    <div class="text-muted small">
                                        <div><strong>Задачи:</strong> <?php echo e(number_format($stage->tasks_cost, 0, ',', ' ')); ?>

                                            ₽</div>
                                        <div><strong>Материалы:</strong>
                                            <?php echo e(number_format($stage->materials_cost, 0, ',', ' ')); ?> ₽</div>
                                        <div><strong>Доставки:</strong>
                                            <?php echo e(number_format($stage->deliveries_cost, 0, ',', ' ')); ?> ₽</div>
                                        <div class="mt-1"><strong>Итого:</strong> <span
                                                class="text-dark"><?php echo e(number_format($stage->total_cost, 0, ',', ' ')); ?>

                                                ₽</span></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Вкладки -->
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tasksTab">
                    <i class="bi bi-check2-square"></i> Задачи (<?php echo e($stage->tasks->count()); ?>)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#materialsTab">
                    <i class="bi bi-box-seam"></i> Материалы (<?php echo e($stage->materials->count()); ?>)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#deliveriesTab">
                    <i class="bi bi-truck"></i> Доставки (<?php echo e($stage->deliveries->count()); ?>)
                </button>
            </li>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('createStages', $project)): ?>
                <div class="ms-auto">
                    <button class="btn btn-primary" id="dynamicCreateBtn" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                        <i class="bi bi-plus"></i> <span id="btnText">Создать задачу</span>
                    </button>
                </div>
            <?php endif; ?>
        </ul>

        <div class="tab-content">
            <!-- Вкладка Задачи -->
            <div class="tab-pane fade show active" id="tasksTab">
                <!-- Поисковая строка (показывается если > 20 задач) -->
                <?php if($totalTasks > 20): ?>
                <div class="row mb-3">
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   id="tasksSearchInput" 
                                   placeholder="Поиск задачи..."
                                   autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" id="clearTasksSearch" style="display: none;">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <small class="text-muted">Начните вводить название или описание задачи</small>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($stage->tasks->isEmpty()): ?>
                    <div class="alert alert-info" id="noTasksMessage">
                        У этого этапа пока нет задач
                    </div>
                <?php else: ?>
                    <div class="row" id="tasksContainer" data-project-id="<?php echo e($project->id); ?>" data-stage-id="<?php echo e($stage->id); ?>">
                        <?php $__currentLoopData = $stage->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 task-card-compact position-relative" 
                                     data-task-id="<?php echo e($task->id); ?>"
                                     <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editTasks', $project)): ?>
                                     data-long-press="true"
                                     data-delete-target="#deleteTaskModal<?php echo e($task->id); ?>"
                                     <?php endif; ?>
                                     data-bs-toggle="modal" 
                                     data-bs-target="#taskModal<?php echo e($task->id); ?>" 
                                     style="cursor: pointer;">
                                    <div class="long-press-indicator"></div>
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="mb-0 fs-6"><?php echo e($task->name); ?></h6>
                                            <?php if($task->status === 'Завершена'): ?>
                                                <span class="badge bg-success"><?php echo e($task->status); ?></span>
                                            <?php elseif($task->status === 'В работе'): ?>
                                                <span class="badge bg-primary"><?php echo e($task->status); ?></span>
                                            <?php elseif($task->status === 'На проверке'): ?>
                                                <span class="badge bg-warning"><?php echo e($task->status); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?php echo e($task->status); ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <?php if($task->description): ?>
                                            <p class="text-muted small mb-1" style="font-size: 0.75rem;">
                                                <?php echo e(Str::limit($task->description, 50)); ?></p>
                                        <?php endif; ?>

                                        <div class="mb-1 d-flex gap-2" style="font-size: 0.75rem;">
                                            <small class="d-block" style="font-size: 0.7rem;">
                                                <i class="bi bi-person"></i> <?php echo e($task->creator->name); ?>

                                            </small>
                                            <?php if($task->assignedUser): ?>
                                                <small class="d-block" style="font-size: 0.7rem;">
                                                    <i class="bi bi-check2-circle"></i> <?php echo e($task->assignedUser->name); ?>

                                                </small>
                                            <?php endif; ?>
                                        </div>



                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <div class="d-flex gap-2">
                                                <?php if($task->photos->count() > 0): ?>
                                                    <span class="badge bg-info me-1"
                                                        style="font-size: 0.65rem; padding: 0.15rem 0.35rem;">
                                                        <i class="bi bi-camera"></i> <?php echo e($task->photos->count()); ?>

                                                    </span>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewCosts', $project)): ?>
                                                    <?php if($task->final_cost > 0): ?>
                                                    <div class="mb-1">
                                                        <span class="badge bg-light text-dark border"
                                                            style="font-size: 0.7rem; padding: 0.15rem 0.4rem;">
                                                            <i class="bi bi-currency-exchange"></i>
                                                            <?php echo e(number_format($task->final_cost, 0, ',', ' ')); ?> ₽
                                                        </span>
                                                    </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editTasks', $project)): ?>
                                                <?php if($task->comments->count() > 0): ?>
                                                    <span class="badge bg-secondary"
                                                        style="font-size: 0.65rem; padding: 0.15rem 0.35rem;">
                                                        <i class="bi bi-chat"></i> <?php echo e($task->comments->count()); ?>

                                                    </span>
                                                <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Модальное окно задачи -->
                            <div class="modal fade" id="taskModal<?php echo e($task->id); ?>" tabindex="-1">
                                <div class="modal-dialog modal-fullscreen m-0">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 pb-2">
                                            <div class="w-100">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <h4 class="mb-1"><?php echo e($task->name); ?></h4>
                                                        <?php if($task->description): ?>
                                                            <p class="text-muted mb-0 small"><?php echo e($task->description); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="d-flex align-items-center" style="gap: 0.5rem;">
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                </div>
                                                <?php
                                                    $isOwner = $currentUserRole && $currentUserRole->role === 'owner';
                                                    $isAssignedExecutor = $task->assigned_to === Auth::id();
                                                    $canChangeStatus = $isOwner || $isAssignedExecutor;
                                                ?>
                                                <!-- Вкладки -->
                                                <ul class="nav nav-tabs border-0 mt-2" role="tablist">
                                                    <li class="nav-item">
                                                        <button class="nav-link active" data-bs-toggle="tab"
                                                            data-bs-target="#photos<?php echo e($task->id); ?>">
                                                            <i class="bi bi-images"></i> Фото
                                                            (<?php echo e($task->photos->count()); ?>)
                                                        </button>
                                                    </li>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editTasks', $project)): ?>
                                                        <li class="nav-item">
                                                            <button class="nav-link" data-bs-toggle="tab"
                                                                data-bs-target="#comments<?php echo e($task->id); ?>">
                                                                <i class="bi bi-chat-dots"></i> Комментарии
                                                                (<?php echo e($task->comments->count()); ?>)
                                                            </button>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if($isOwner): ?>
                                                        <li class="nav-item">
                                                            <button class="nav-link" data-bs-toggle="tab"
                                                                data-bs-target="#settings<?php echo e($task->id); ?>">
                                                                <i class="bi bi-gear"></i> Настройки
                                                            </button>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="modal-body p-3">
                                            <div class="tab-content">
                                                <!-- Фото -->
                                                <div class="tab-pane fade show active" id="photos<?php echo e($task->id); ?>">
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('uploadDocuments', $project)): ?>
                                                        <div class="mb-3">
                                                            <form
                                                                action="<?php echo e(route('stages.tasks.photos.add', [$project, $stage, $task])); ?>"
                                                                method="POST" enctype="multipart/form-data"
                                                                class="photo-upload-form"
                                                                id="photoForm<?php echo e($task->id); ?>">
                                                                <?php echo csrf_field(); ?>

                                                                <!-- Drag and Drop зона для фото -->
                                                                <div class="upload-zone-photo mb-3"
                                                                    data-task-id="<?php echo e($task->id); ?>">
                                                                    <input type="file" name="photos[]"
                                                                        class="photo-file-input d-none" accept="image/*"
                                                                        multiple required
                                                                        id="photoFiles<?php echo e($task->id); ?>">
                                                                    <div class="upload-content-photo">
                                                                        <i class="bi bi-cloud-arrow-up upload-icon-photo"></i>
                                                                        <p class="upload-title-photo mb-3">Выберите или перетащите фото</p>
                                                                        <div class="d-flex gap-2 flex-wrap justify-content-center">
                                                                            <button type="button" class="btn btn-primary btn-upload-select"
                                                                                onclick="event.stopPropagation(); document.getElementById('photoFiles<?php echo e($task->id); ?>').click();">
                                                                                <i class="bi bi-images"></i> Выбрать
                                                                            </button>
                                                                            <button type="button" class="btn btn-primary btn-upload-camera d-md-none"
                                                                                onclick="event.stopPropagation(); document.getElementById('cameraFiles<?php echo e($task->id); ?>').click();">
                                                                                <i class="bi bi-camera"></i> Камера
                                                                            </button>
                                                                        </div>
                                                                        <input type="file" class="d-none" accept="image/*" capture="environment" multiple
                                                                            id="cameraFiles<?php echo e($task->id); ?>">
                                                                    </div>
                                                                    <div class="upload-preview-photo d-none">
                                                                        <div class="photos-preview-grid"></div>
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-danger mt-2 clear-photos-btn">
                                                                            <i class="bi bi-x-circle"></i> Очистить
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <button type="submit"
                                                                    class="btn btn-primary w-100 upload-photos-btn"
                                                                    disabled>
                                                                    <i class="bi bi-upload"></i> Загрузить
                                                                </button>
                                                            </form>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="row g-2">
                                                        <?php $__empty_1 = true; $__currentLoopData = $task->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                                                                <div class="position-relative">
                                                                    <img src="<?php echo e($photo->secure_url); ?>"
                                                                        class="img-fluid rounded shadow-sm"
                                                                        style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;"
                                                                        onclick="event.preventDefault(); window.open('<?php echo e($photo->secure_url); ?>', '_blank', 'noopener,noreferrer');">

                                                                    <?php
                                                                        $isOwner = $currentUserRole && $currentUserRole->role === 'owner';
                                                                        $canDelete = $photo->user_id === Auth::id() || $isOwner;
                                                                    ?>
                                                                    <?php if($canDelete): ?>
                                                                        <form
                                                                            action="<?php echo e(route('stages.tasks.photos.destroy', [$project, $stage, $task, $photo])); ?>"
                                                                            method="POST"
                                                                            class="position-absolute top-0 end-0"
                                                                            style="margin: 0.25rem;"
                                                                            onsubmit="return confirm('Удалить?')">
                                                                            <?php echo csrf_field(); ?>
                                                                            <?php echo method_field('DELETE'); ?>
                                                                            <button type="submit"
                                                                                class="btn btn-sm btn-danger rounded-circle p-0"
                                                                                style="width: 28px; height: 28px; line-height: 1;">
                                                                                <i class="bi bi-x"></i>
                                                                            </button>
                                                                        </form>
                                                                    <?php endif; ?>

                                                                    <div class="position-absolute bottom-0 start-0 w-100 p-2 text-white"
                                                                        style="background: linear-gradient(transparent, rgba(0,0,0,0.7)); border-radius: 0 0 0.25rem 0.25rem;">
                                                                        <small class="d-block"
                                                                            style="font-size: 0.7rem;"><?php echo e($photo->user->name); ?></small>
                                                                        <small class="d-block"
                                                                            style="font-size: 0.7rem;"><?php echo e($photo->created_at->format('d.m.Y H:i')); ?></small>
                                                                        <?php if($photo->description): ?>
                                                                            <small class="d-block mt-1"
                                                                                style="font-size: 0.7rem;"><?php echo e($photo->description); ?></small>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <!-- Комментарии -->
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editTasks', $project)): ?>
                                                    <div class="tab-pane fade" id="comments<?php echo e($task->id); ?>">
                                                        <div class="mb-3">
                                                            <form
                                                                action="<?php echo e(route('stages.tasks.comments.add', [$project, $stage, $task])); ?>"
                                                                method="POST"
                                                                class="comment-form"
                                                                data-task-id="<?php echo e($task->id); ?>">
                                                                <?php echo csrf_field(); ?>
                                                                <div class="d-flex gap-2 align-items-end">
                                                                    <div class="flex-grow-1">
                                                                        <textarea class="form-control" name="comment" rows="3" placeholder="Напишите комментарий..." required style="resize: vertical; min-height: 80px;"></textarea>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary" style="height: 46px; min-width: 46px;">
                                                                        <i class="bi bi-send fs-5"></i>
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>

                                                        <div class="row g-0">
                                                            <div class="col-lg-10 col-xl-8" id="comments-list-<?php echo e($task->id); ?>">
                                                                <?php $__empty_1 = true; $__currentLoopData = $task->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                                    <div class="card mb-2 border-0 shadow-sm">
                                                                        <div class="card-body p-2">
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-start">
                                                                                <div class="flex-grow-1">
                                                                                    <div class="d-flex align-items-center mb-1"
                                                                                        style="gap: 0.5rem;">
                                                                                        <strong
                                                                                            style="font-size: 0.9rem;"><?php echo e($comment->user->name); ?></strong>
                                                                                        <small class="text-muted"
                                                                                            style="font-size: 0.75rem;"><?php echo e($comment->created_at->format('d.m.Y H:i')); ?></small>
                                                                                    </div>
                                                                                    <p class="mb-0"
                                                                                        style="font-size: 0.9rem;">
                                                                                        <?php echo e($comment->comment); ?></p>
                                                                                </div>
                                                                                <?php
                                                                                    $isOwner = $currentUserRole && $currentUserRole->role === 'owner';
                                                                                    $canDelete = $comment->user_id === Auth::id() || $isOwner;
                                                                                ?>
                                                                                <?php if($canDelete): ?>
                                                                                    <form
                                                                                        action="<?php echo e(route('stages.tasks.comments.destroy', [$project, $stage, $task, $comment])); ?>"
                                                                                        method="POST" class="ms-2"
                                                                                        onsubmit="return confirm('Удалить?')">
                                                                                        <?php echo csrf_field(); ?>
                                                                                        <?php echo method_field('DELETE'); ?>
                                                                                        <button type="submit"
                                                                                            class="btn btn-sm btn-link text-danger p-0">
                                                                                            <i class="bi bi-x-lg"></i>
                                                                                        </button>
                                                                                    </form>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                                    <div class="text-center text-muted py-5">
                                                                        <i class="bi bi-chat-dots"
                                                                            style="font-size: 3rem;"></i>
                                                                        <p class="mt-2 mb-0">Комментариев пока нет</p>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- Настройки задачи (только для прораба) -->
                                                <?php if($isOwner): ?>
                                                    <div class="tab-pane fade" id="settings<?php echo e($task->id); ?>">
                                                        <form action="<?php echo e(route('stages.tasks.update', [$project, $stage, $task])); ?>" 
                                                              method="POST" 
                                                              class="task-settings-form"
                                                              data-task-id="<?php echo e($task->id); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('PUT'); ?>
                                                            
                                                            <div class="minimal-card mb-3">
                                                                <div class="minimal-card-header">
                                                                    <span><i class="bi bi-gear"></i> Параметры задачи</span>
                                                                </div>
                                                                <div class="minimal-card-body">
                                                                    <!-- Статус -->
                                                                    <div class="form-group-minimal">
                                                                        <label>Статус задачи</label>
                                                                        <select class="minimal-input" name="status" required>
                                                                            <option value="Не начата" <?php echo e($task->status === 'Не начата' ? 'selected' : ''); ?>>Не начата</option>
                                                                            <option value="В работе" <?php echo e($task->status === 'В работе' ? 'selected' : ''); ?>>В работе</option>
                                                                            <option value="На проверке" <?php echo e($task->status === 'На проверке' ? 'selected' : ''); ?>>На проверке</option>
                                                                            <option value="Завершена" <?php echo e($task->status === 'Завершена' ? 'selected' : ''); ?>>Завершена</option>
                                                                        </select>
                                                                    </div>

                                                                    <!-- Цена -->
                                                                    <div class="form-group-minimal">
                                                                        <label>Стоимость работ (₽)</label>
                                                                        <input type="number" 
                                                                               class="minimal-input" 
                                                                               name="cost" 
                                                                               value="<?php echo e($task->cost); ?>" 
                                                                               step="0.01" 
                                                                               min="0"
                                                                               placeholder="0.00">
                                                                        <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">Укажите плановую стоимость выполнения задачи</small>
                                                                    </div>

                                                                    <!-- Ответственный сотрудник -->
                                                                    <div class="form-group-minimal">
                                                                        <label>Ответственный исполнитель</label>
                                                                        <select class="minimal-input" name="assigned_to">
                                                                            <option value="">Не назначен</option>
                                                                            <?php
                                                                                $executors = $project->userRoles()
                                                                                    ->where('role', 'executor')
                                                                                    ->with('user')
                                                                                    ->get();
                                                                            ?>
                                                                            <?php $__currentLoopData = $executors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $executor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <option value="<?php echo e($executor->user_id); ?>" 
                                                                                    <?php echo e($task->assigned_to == $executor->user_id ? 'selected' : ''); ?>>
                                                                                    <?php echo e($executor->user->name); ?>

                                                                                    <?php if($executor->user->phone): ?>
                                                                                        (<?php echo e($executor->user->phone); ?>)
                                                                                    <?php endif; ?>
                                                                                </option>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </select>
                                                                        <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">Выберите сотрудника из команды проекта</small>
                                                                    </div>

                                                                    <button type="submit" class="minimal-btn minimal-btn-primary" style="width: 100%;">
                                                                        <i class="bi bi-check-lg"></i> Сохранить изменения
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>

                                                        <!-- Информация о задаче -->
                                                        <div class="minimal-card">
                                                            <div class="minimal-card-header">
                                                                <span><i class="bi bi-info-circle"></i> Информация о задаче</span>
                                                            </div>
                                                            <div class="minimal-card-body">
                                                                <div class="task-info-grid">
                                                                    <div class="task-info-item">
                                                                        <small class="text-muted">Создал задачу</small>
                                                                        <strong><?php echo e($task->creator->name ?? 'Неизвестно'); ?></strong>
                                                                    </div>
                                                                    <div class="task-info-item">
                                                                        <small class="text-muted">Дата создания</small>
                                                                        <strong><?php echo e($task->created_at->format('d.m.Y H:i')); ?></strong>
                                                                    </div>
                                                                    <?php if($task->assignedUser): ?>
                                                                        <div class="task-info-item" style="grid-column: 1 / -1;">
                                                                            <small class="text-muted">Текущий исполнитель</small>
                                                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                                                <span class="badge bg-primary"><?php echo e($task->assignedUser->name); ?></span>
                                                                                <?php if($task->assignedUser->phone): ?>
                                                                                    <small class="text-muted"><?php echo e($task->assignedUser->phone); ?></small>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <?php if($task->final_cost > 0): ?>
                                                                        <div class="task-info-item" style="grid-column: 1 / -1;">
                                                                            <small class="text-muted">Стоимость работ</small>
                                                                            <strong style="color: #10b981; font-size: 1.1rem;"><?php echo e(number_format($task->final_cost, 2, ',', ' ')); ?> ₽</strong>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <!-- Индикатор загрузки для infinite scroll -->
                    <div class="col-12 text-center py-3 d-none" id="tasksLoader">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Загрузка...</span>
                        </div>
                    </div>
                    
                    <!-- Сообщение об отсутствии результатов поиска -->
                    <div class="col-12 d-none" id="noTasksFound">
                        <div class="alert alert-info">
                            <i class="bi bi-search"></i> Ничего не найдено. Попробуйте изменить запрос.
                        </div>
                    </div>
                <?php endif; ?>

                
                <?php
                    $isOwner = $currentUserRole && $currentUserRole->role === 'owner';
                ?>
                <?php if($isOwner): ?>
                    <?php $__currentLoopData = $stage->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    <button type="button" class="d-none" id="triggerDeleteTask<?php echo e($task->id); ?>" data-bs-toggle="modal" data-bs-target="#deleteTaskModal<?php echo e($task->id); ?>"></button>
                    <div class="modal fade" id="deleteTaskModal<?php echo e($task->id); ?>" tabindex="-1">
                        <div class="modal-dialog modal-fullscreen m-0">
                            <div class="modal-content">
                                <div class="modal-header border-0 pb-2">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="px-4">
                                    <div class="wizard-header text-center">
                                        <h2 class="text-danger"><i class="bi bi-exclamation-triangle"></i> Удалить задачу?</h2>
                                        <p>Это действие нельзя отменить</p>
                                    </div>
                                </div>
                                <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                                    <div class="wizard-container" style="max-width: 600px; width: 100%;">
                                        <div class="alert alert-warning">
                                            <h5 class="alert-heading"><?php echo e($task->name); ?></h5>
                                            <?php if($task->description): ?>
                                                <p class="small mb-2"><?php echo e($task->description); ?></p>
                                            <?php endif; ?>
                                            <hr>
                                            <p class="mb-1"><strong>Будет удалено:</strong></p>
                                            <ul class="mb-0">
                                                <li><?php echo e($task->photos->count()); ?> <?php echo e(Str::plural('фотоотчёт', $task->photos->count())); ?></li>
                                                <li><?php echo e($task->comments->count()); ?> <?php echo e(Str::plural('комментарий', $task->comments->count())); ?></li>
                                                <?php if($task->final_cost > 0): ?>
                                                    <li>Стоимость: <?php echo e(number_format($task->final_cost, 0, ',', ' ')); ?> ₽</li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 justify-content-center">
                                    <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                                    <form action="<?php echo e(route('stages.tasks.destroy', [$project, $stage, $task])); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="minimal-btn minimal-btn-danger">Удалить безвозвратно</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
            <div class="tab-pane fade" id="materialsTab">
                <?php if($stage->materials->isEmpty()): ?>
                    <div class="alert alert-info">
                        Материалов пока нет
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php $__currentLoopData = $stage->materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0"><?php echo e($material->name); ?></h6>
                                            <?php
                                                $isOwner = $currentUserRole && $currentUserRole->role === 'owner';
                                            ?>
                                            <?php if($isOwner): ?>
                                                <form
                                                    action="<?php echo e(route('stages.materials.destroy', [$project, $stage, $material])); ?>"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Удалить материал?')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>

                                        <?php if($material->description): ?>
                                            <p class="text-muted small mb-2"><?php echo e($material->description); ?></p>
                                        <?php endif; ?>

                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Количество</small>
                                                <strong><?php echo e($material->quantity); ?> <?php echo e($material->unit); ?></strong>
                                            </div>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewCosts', $project)): ?>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Цена за ед.</small>
                                                    <strong><?php echo e(number_format($material->price_per_unit, 0, ',', ' ')); ?>

                                                        ₽</strong>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewCosts', $project)): ?>
                                            <div class="alert alert-light border mb-0 p-2 text-center">
                                                <strong>Итого: <?php echo e(number_format($material->final_cost, 0, ',', ' ')); ?>

                                                    ₽</strong>
                                            </div>
                                        <?php endif; ?>

                                        <small class="text-muted d-block mt-2">
                                            <i class="bi bi-person"></i> <?php echo e($material->user->name); ?>

                                            • <?php echo e($material->created_at->format('d.m.Y')); ?>

                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Вкладка Доставки -->
            <div class="tab-pane fade" id="deliveriesTab">
                <?php if($stage->deliveries->isEmpty()): ?>
                    <div class="alert alert-info">
                        Доставок пока нет
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php $__currentLoopData = $stage->deliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0"><?php echo e($delivery->name); ?></h6>
                                            <?php
                                                $isOwner = $currentUserRole && $currentUserRole->role === 'owner';
                                            ?>
                                            <?php if($isOwner): ?>
                                                <form
                                                    action="<?php echo e(route('stages.deliveries.destroy', [$project, $stage, $delivery])); ?>"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Удалить доставку?')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>

                                        <?php if($delivery->description): ?>
                                            <p class="text-muted small mb-2"><?php echo e($delivery->description); ?></p>
                                        <?php endif; ?>

                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Количество</small>
                                                <strong><?php echo e($delivery->quantity); ?> <?php echo e($delivery->unit); ?></strong>
                                            </div>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewCosts', $project)): ?>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Цена за ед.</small>
                                                    <strong><?php echo e(number_format($delivery->price_per_unit, 0, ',', ' ')); ?>

                                                        ₽</strong>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewCosts', $project)): ?>
                                            <div class="alert alert-light border mb-0 p-2 text-center">
                                                <strong>Итого: <?php echo e(number_format($delivery->final_cost, 0, ',', ' ')); ?>

                                                    ₽</strong>
                                            </div>
                                        <?php endif; ?>

                                        <small class="text-muted d-block mt-2">
                                            <i class="bi bi-person"></i> <?php echo e($delivery->user->name); ?>

                                            • <?php echo e($delivery->created_at->format('d.m.Y')); ?>

                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Модальное окно создания задачи -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('createStages', $project)): ?>
        <div class="modal fade" id="createTaskModal" tabindex="-1">
            <div class="modal-dialog modal-fullscreen m-0">
                <div class="modal-content">
                    <form action="<?php echo e(route('stages.tasks.store', [$project, $stage])); ?>" method="POST" class="d-flex flex-column h-100">
                        <?php echo csrf_field(); ?>
                        <div class="modal-header border-0 pb-2">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="px-4">
                            <div class="wizard-header text-center">
                                <h2>Создать задачу</h2>
                                <p>Укажите название и параметры задачи</p>
                            </div>
                        </div>
                        <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                            <div class="wizard-container" style="max-width: 600px; width: 100%;">
                            <div class="form-group-minimal">
                                <label>Название задачи</label>
                                <input type="text" class="minimal-input" id="name" name="name" 
                                       placeholder="Например: Установка розеток"
                                       maxlength="255"
                                       required>
                            </div>

                            <div class="form-group-minimal">
                                <label>Описание</label>
                                <textarea class="minimal-input" id="description" name="description" rows="3"
                                          placeholder="Подробное описание задачи"
                                          maxlength="1000"></textarea>
                            </div>

                            <div class="form-group-minimal">
                                <label>Назначить исполнителю</label>
                                <select class="minimal-input" id="assigned_to" name="assigned_to">
                                    <option value="">Не назначена</option>
                                    <?php
                                        // Получаем всех исполнителей проекта
                                        $executors = $project->userRoles()
                                            ->where('role', 'executor')
                                            ->with('user')
                                            ->get()
                                            ->pluck('user')
                                            ->filter();
                                    ?>
                                    <?php $__currentLoopData = $executors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $executor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($executor->id); ?>"><?php echo e($executor->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="form-group-minimal">
                                <label>Стоимость работы (₽)</label>
                                <input type="number" class="minimal-input" id="cost" name="cost" min="0"
                                    step="0.01" value="0" placeholder="0">
                            </div>

                            <div class="form-group-minimal">
                                <label>Наценка (%)</label>
                                <input type="number" class="minimal-input" id="markup_percent" name="markup_percent" 
                                    min="0" max="999.99" step="0.01" placeholder="<?php echo e($project->markup_percent ? $project->markup_percent : 'Использовать общую наценку'); ?>">
                                <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">
                                    <?php if($project->markup_percent): ?>
                                        Оставьте пустым чтобы использовать общую наценку проекта (<?php echo e($project->markup_percent); ?>%)
                                    <?php else: ?>
                                        Индивидуальная наценка для этой задачи
                                    <?php endif; ?>
                                </small>
                            </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 justify-content-center">
                            <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" class="minimal-btn minimal-btn-primary">Создать</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Модальное окно создания материала -->
        <div class="modal fade" id="createMaterialModal" tabindex="-1">
            <div class="modal-dialog modal-fullscreen m-0">
                <div class="modal-content">
                    <form action="<?php echo e(route('stages.materials.store', [$project, $stage])); ?>" method="POST" class="d-flex flex-column h-100">
                        <?php echo csrf_field(); ?>
                        <div class="modal-header border-0 pb-2">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="px-4">
                            <div class="wizard-header text-center">
                                <h2>Добавить материал</h2>
                                <p>Укажите название, количество и стоимость</p>
                            </div>
                        </div>
                        <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                            <div class="wizard-container" style="max-width: 600px; width: 100%;">
                            <div class="form-group-minimal">
                                <label>Название материала</label>
                                <input type="text" class="minimal-input" id="material_name" name="name" 
                                       placeholder="Например: Керамогранит 60x60"
                                       maxlength="255"
                                       required>
                            </div>

                            <div class="form-group-minimal">
                                <label>Описание</label>
                                <textarea class="minimal-input" id="material_description" name="description" rows="2"
                                          placeholder="Цвет, размер, производитель"
                                          maxlength="500"></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group-minimal">
                                    <label>Количество</label>
                                    <input type="number" class="minimal-input" id="quantity" name="quantity"
                                        min="0.01" step="0.01" placeholder="0" required>
                                </div>
                                <div class="form-group-minimal">
                                    <label>Единица измерения</label>
                                    <select class="minimal-input" id="unit" name="unit" required>
                                        <option value="шт">шт</option>
                                        <option value="кг">кг</option>
                                        <option value="м">м</option>
                                        <option value="м²">м²</option>
                                        <option value="м³">м³</option>
                                        <option value="л">л</option>
                                        <option value="уп">уп</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group-minimal">
                                <label>Цена за единицу (₽)</label>
                                <input type="number" class="minimal-input" id="price_per_unit" name="price_per_unit"
                                    min="0" step="0.01" placeholder="0" required>
                            </div>

                            <div class="form-group-minimal">
                                <label>Наценка (%)</label>
                                <input type="number" class="minimal-input" id="material_markup_percent" name="markup_percent" 
                                    min="0" max="999.99" step="0.01" placeholder="<?php echo e($project->markup_percent ? $project->markup_percent : 'Использовать общую наценку'); ?>">
                                <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">
                                    <?php if($project->markup_percent): ?>
                                        Оставьте пустым чтобы использовать общую наценку проекта (<?php echo e($project->markup_percent); ?>%)
                                    <?php else: ?>
                                        Индивидуальная наценка для этого материала
                                    <?php endif; ?>
                                </small>
                            </div>

                            <div class="alert alert-light border">
                                <small class="text-muted">Общая стоимость будет рассчитана автоматически с учетом наценки</small>
                            </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 justify-content-center">
                            <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" class="minimal-btn minimal-btn-primary">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Модальное окно создания доставки -->
        <div class="modal fade" id="createDeliveryModal" tabindex="-1">
            <div class="modal-dialog modal-fullscreen m-0">
                <div class="modal-content">
                    <form action="<?php echo e(route('stages.deliveries.store', [$project, $stage])); ?>" method="POST" class="d-flex flex-column h-100">
                        <?php echo csrf_field(); ?>
                        <div class="modal-header border-0 pb-2">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="px-4">
                            <div class="wizard-header text-center">
                                <h2>Добавить доставку</h2>
                                <p>Укажите название, количество и стоимость</p>
                            </div>
                        </div>
                        <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                            <div class="wizard-container" style="max-width: 600px; width: 100%;">
                            <div class="form-group-minimal">
                                <label>Название доставки/услуги</label>
                                <input type="text" class="minimal-input" id="delivery_name" name="name" 
                                       placeholder="Например: Доставка щебня или Аренда экскаватора"
                                       maxlength="255"
                                       required>
                            </div>

                            <div class="form-group-minimal">
                                <label>Описание</label>
                                <textarea class="minimal-input" id="delivery_description" name="description" rows="2"
                                          placeholder="Краткое описание доставки или услуги"
                                          maxlength="500"></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group-minimal">
                                    <label>Количество</label>
                                    <input type="number" class="minimal-input" id="delivery_quantity" name="quantity"
                                        min="0.01" step="0.01" placeholder="0" required>
                                </div>
                                <div class="form-group-minimal">
                                    <label>Единица измерения</label>
                                    <select class="minimal-input" id="delivery_unit" name="unit" required>
                                        <option value="рейсы">рейсы</option>
                                        <option value="шт">шт</option>
                                        <option value="час">час</option>
                                        <option value="день">день</option>
                                        <option value="л">л</option>
                                        <option value="м³">м³</option>
                                        <option value="т">т</option>
                                        <option value="км">км</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group-minimal">
                                <label>Цена за единицу (₽)</label>
                                <input type="number" class="minimal-input" id="delivery_price_per_unit" name="price_per_unit"
                                    min="0" step="0.01" placeholder="0" required>
                            </div>

                            <div class="form-group-minimal">
                                <label>Наценка (%)</label>
                                <input type="number" class="minimal-input" id="delivery_markup_percent" name="markup_percent" 
                                    min="0" max="999.99" step="0.01" placeholder="<?php echo e($project->markup_percent ? $project->markup_percent : 'Использовать общую наценку'); ?>">
                                <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">
                                    <?php if($project->markup_percent): ?>
                                        Оставьте пустым чтобы использовать общую наценку проекта (<?php echo e($project->markup_percent); ?>%)
                                    <?php else: ?>
                                        Индивидуальная наценка для этой доставки
                                    <?php endif; ?>
                                </small>
                            </div>

                            <div class="alert alert-light border">
                                <small class="text-muted">Общая стоимость будет рассчитана автоматически с учетом наценки</small>
                            </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 justify-content-center">
                            <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" class="minimal-btn minimal-btn-primary">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <style>
        /* Стили для загрузки фото */
        .upload-zone-photo {
            border: 2px dashed var(--color-gray-light);
            border-radius: var(--border-radius);
            padding: 2rem 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            background: var(--color-white);
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .upload-zone-photo:hover {
            border-color: var(--color-primary);
            background: #f8f9fa;
        }

        .upload-zone-photo.drag-over {
            border-color: var(--color-primary);
            background: linear-gradient(135deg, #e3f2fd 0%, #e8f5e9 100%);
            border-style: solid;
            transform: scale(1.01);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .upload-content-photo {
            width: 100%;
            padding: 1.5rem;
        }

        .upload-icon-photo {
            font-size: 2.5rem;
            color: #a70000;
            margin-bottom: 0.5rem;
            display: block;
        }

        .upload-title-photo {
            font-size: 0.95rem;
            color: #000000;
            font-weight: 600;
            color: var(--color-dark);
            margin-bottom: 0.25rem;
        }

        .upload-text-photo {
            color: var(--color-gray);
            margin: 0.5rem 0;
            font-size: 0.9rem;
        }

        .btn-upload-select,
        .btn-upload-camera {
            font-size: 0.875rem;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
        }

        .btn-upload-camera {
            background-color: #000000;
            border-color: #000000;
        }

        .btn-upload-camera:hover {
            background-color: #a70000;
            border-color: #a70000;
        }

        .upload-preview-photo {
            width: 100%;
        }

        .photos-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .photo-preview-item {
            position: relative;
            padding-top: 100%;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e9ecef;
            transition: all 0.2s ease;
        }

        .photo-preview-item:hover {
            border-color: var(--color-primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .photo-preview-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-preview-remove {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--color-danger);
            color: white;
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.7rem;
            transition: transform 0.2s ease;
            z-index: 10;
        }

        .photo-preview-remove:hover {
            transform: scale(1.15);
        }

        /* Стили для формы настроек задачи */
        .settings-form .minimal-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }

        .task-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .task-info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .task-info-item small {
            font-size: 0.85rem;
            color: #6b7280;
        }

        .task-info-item strong {
            font-size: 0.95rem;
            color: #111827;
        }

        @media (max-width: 768px) {
            .task-info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ========================================
            // ПЕРЕКЛЮЧЕНИЕ КНОПКИ СОЗДАНИЯ
            // ========================================
            
            const dynamicBtn = document.getElementById('dynamicCreateBtn');
            const btnText = document.getElementById('btnText');
            const stageId = <?php echo json_encode($stage->id, 15, 512) ?>;
            const storageKey = `stage_${stageId}_active_tab`;
            
            if (dynamicBtn && btnText) {
                // Функция для обновления кнопки
                function updateCreateButton(tabId) {
                    if (tabId === 'tasksTab') {
                        dynamicBtn.setAttribute('data-bs-target', '#createTaskModal');
                        btnText.textContent = 'Создать задачу';
                    } else if (tabId === 'materialsTab') {
                        dynamicBtn.setAttribute('data-bs-target', '#createMaterialModal');
                        btnText.textContent = 'Добавить материал';
                    } else if (tabId === 'deliveriesTab') {
                        dynamicBtn.setAttribute('data-bs-target', '#createDeliveryModal');
                        btnText.textContent = 'Добавить доставку';
                    }
                }
                
                // Слушаем события переключения вкладок
                const tabButtons = document.querySelectorAll('.nav-tabs > .nav-item > button[data-bs-toggle="tab"]');
                tabButtons.forEach(button => {
                    button.addEventListener('shown.bs.tab', function(e) {
                        const targetTab = e.target.getAttribute('data-bs-target').replace('#', '');
                        updateCreateButton(targetTab);
                        // Сохраняем активную вкладку в localStorage
                        localStorage.setItem(storageKey, targetTab);
                    });
                });
            }

            // ========================================
            // ВОССТАНОВЛЕНИЕ СОСТОЯНИЯ СТРАНИЦЫ
            // ========================================

            // Восстановление активной вкладки
            let activeTabToRestore = null;
            
            // Приоритет 1: вкладка из сессии (после отправки формы)
            <?php if(session('tab')): ?>
                activeTabToRestore = '<?php echo e(session('tab')); ?>';
            <?php endif; ?>
            
            // Приоритет 2: вкладка из localStorage (после перезагрузки страницы)
            if (!activeTabToRestore) {
                const savedTab = localStorage.getItem(storageKey);
                if (savedTab) {
                    activeTabToRestore = savedTab;
                }
            }
            
            // Восстанавливаем вкладку
            if (activeTabToRestore) {
                const tabButton = document.querySelector(`button[data-bs-target="#${activeTabToRestore}"]`);
                if (tabButton) {
                    const tab = new bootstrap.Tab(tabButton);
                    tab.show();
                    // Обновляем кнопку после восстановления вкладки
                    if (dynamicBtn && btnText) {
                        setTimeout(() => updateCreateButton(activeTabToRestore), 100);
                    }
                }
            }

            // Прокрутка к определенному элементу и подсветка
            <?php if(session('task_id')): ?>
                setTimeout(function() {
                    const taskElement = document.getElementById('task-<?php echo e(session('task_id')); ?>');
                    if (taskElement) {
                        taskElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        // Подсветка элемента
                        taskElement.style.transition = 'background-color 0.3s';
                        taskElement.style.backgroundColor = '#fff3cd';
                        setTimeout(function() {
                            taskElement.style.backgroundColor = '';
                        }, 1000);
                    }
                }, 500);
            <?php endif; ?>

            <?php if(session('scroll_to')): ?>
                setTimeout(function() {
                    const element = document.getElementById('<?php echo e(session('scroll_to')); ?>');
                    if (element) {
                        element.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }, 500);
            <?php endif; ?>

            // ========================================
            // СОХРАНЕНИЕ И ВОССТАНОВЛЕНИЕ МОДАЛЬНЫХ ОКОН
            // ========================================

            // Ключ для хранения модалки и вкладки в модалке
            const modalStorageKey = `stage_${stageId}_active_modal`;
            const modalTabStorageKey = `stage_${stageId}_modal_tab`;

            // Сохранение открытой модалки и активной вкладки внутри
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('show.bs.modal', function(e) {
                    const modalId = this.id;
                    localStorage.setItem(modalStorageKey, modalId);
                });

                modal.addEventListener('hidden.bs.modal', function(e) {
                    // Очищаем при закрытии
                    localStorage.removeItem(modalStorageKey);
                    localStorage.removeItem(modalTabStorageKey);
                });

                // Слушаем переключение вкладок внутри модалки
                const modalTabButtons = modal.querySelectorAll('.nav-tabs button[data-bs-toggle="tab"]');
                modalTabButtons.forEach(button => {
                    button.addEventListener('shown.bs.tab', function(e) {
                        const targetTab = e.target.getAttribute('data-bs-target');
                        localStorage.setItem(modalTabStorageKey, targetTab);
                    });
                });
            });

            // Восстановление модалки после перезагрузки (только если есть task_id в сессии)
            <?php if(session('task_id')): ?>
                setTimeout(function() {
                    const savedModalId = localStorage.getItem(modalStorageKey);
                    const savedModalTab = localStorage.getItem(modalTabStorageKey);
                    
                    // Восстанавливаем модалку только если это та же задача
                    const taskId = <?php echo json_encode(session('task_id'), 15, 512) ?>;
                    if (savedModalId && savedModalId.includes(taskId)) {
                        const modalElement = document.getElementById(savedModalId);
                        if (modalElement) {
                            const modal = new bootstrap.Modal(modalElement);
                            modal.show();
                            
                            // Восстанавливаем активную вкладку внутри модалки
                            if (savedModalTab) {
                                setTimeout(() => {
                                    const tabButton = modalElement.querySelector(`button[data-bs-target="${savedModalTab}"]`);
                                    if (tabButton) {
                                        const tab = new bootstrap.Tab(tabButton);
                                        tab.show();
                                    }
                                }, 200);
                            }
                        }
                    }
                }, 600);
            <?php endif; ?>

            // ========================================
            // AJAX ОБРАБОТКА КОММЕНТАРИЕВ
            // ========================================

            document.querySelectorAll('.comment-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const taskId = this.dataset.taskId;
                    const formData = new FormData(this);
                    const textarea = this.querySelector('textarea[name="comment"]');
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const commentsList = document.getElementById(`comments-list-${taskId}`);
                    
                    // Блокируем кнопку отправки
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Очищаем textarea
                            textarea.value = '';
                            
                            // Добавляем новый комментарий в список
                            const commentHtml = `
                                <div class="card mb-2 border-0 shadow-sm">
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-1" style="gap: 0.5rem;">
                                                    <strong style="font-size: 0.9rem;">${data.comment.user_name}</strong>
                                                    <small class="text-muted" style="font-size: 0.75rem;">${data.comment.created_at}</small>
                                                </div>
                                                <p class="mb-0" style="font-size: 0.9rem;">${data.comment.comment}</p>
                                            </div>
                                            ${data.comment.can_delete ? `
                                            <form action="${data.comment.delete_url}" method="POST" class="ms-2 delete-comment-form" onsubmit="return confirm('Удалить?')">
                                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                            ` : ''}
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                            // Убираем сообщение "Комментариев пока нет", если оно есть
                            const emptyMessage = commentsList.querySelector('.text-center.text-muted.py-5');
                            if (emptyMessage) {
                                emptyMessage.remove();
                            }
                            
                            commentsList.insertAdjacentHTML('beforeend', commentHtml);
                            
                            // Обновляем счетчик комментариев в табе
                            const commentsTab = document.querySelector(`button[data-bs-target="#comments${taskId}"]`);
                            if (commentsTab) {
                                const match = commentsTab.innerHTML.match(/\((\d+)\)/);
                                if (match) {
                                    const newCount = parseInt(match[1]) + 1;
                                    commentsTab.innerHTML = commentsTab.innerHTML.replace(/\(\d+\)/, `(${newCount})`);
                                }
                            }
                            
                            // Показываем уведомление
                            showNotification('Комментарий добавлен!', 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Ошибка при добавлении комментария', 'error');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-send"></i>';
                    });
                });
            });

            // ========================================
            // AJAX ОБНОВЛЕНИЕ НАСТРОЕК ЗАДАЧИ
            // ========================================

            document.querySelectorAll('.task-settings-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const taskId = this.dataset.taskId;
                    const formData = new FormData(this);
                    const submitBtn = this.querySelector('button[type="submit"]');
                    
                    // Блокируем кнопку отправки
                    submitBtn.disabled = true;
                    const originalBtnHtml = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Сохранение...';
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Обновляем информацию о задаче в информационном блоке
                            const modal = this.closest('.modal');
                            
                            // Обновляем исполнителя, если он изменился
                            if (data.task.assigned_user) {
                                let executorBlock = modal.querySelector('.task-info-item:has(small:contains("Текущий исполнитель"))');
                                if (!executorBlock) {
                                    // Создаем блок, если его не было
                                    const taskInfoGrid = modal.querySelector('.task-info-grid');
                                    executorBlock = document.createElement('div');
                                    executorBlock.className = 'task-info-item';
                                    executorBlock.style.gridColumn = '1 / -1';
                                    taskInfoGrid.appendChild(executorBlock);
                                }
                                executorBlock.innerHTML = `
                                    <small class="text-muted">Текущий исполнитель</small>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <span class="badge bg-primary">${data.task.assigned_user.name}</span>
                                        ${data.task.assigned_user.phone ? `<small class="text-muted">${data.task.assigned_user.phone}</small>` : ''}
                                    </div>
                                `;
                            }
                            
                            // Обновляем стоимость
                            if (data.task.final_cost > 0) {
                                const costElement = modal.querySelector('.task-info-item strong[style*="color: #10b981"]');
                                if (costElement) {
                                    costElement.textContent = data.task.final_cost_formatted + ' ₽';
                                }
                            }
                            
                            // Обновляем статус в карточке задачи на странице
                            const taskCard = document.querySelector(`[data-task-id="${taskId}"]`);
                            if (taskCard && data.task.status) {
                                const statusBadge = taskCard.querySelector('.badge');
                                if (statusBadge) {
                                    statusBadge.className = 'badge';
                                    statusBadge.classList.add(data.task.status_class);
                                    statusBadge.textContent = data.task.status;
                                }
                            }
                            
                            showNotification('Настройки задачи обновлены!', 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Ошибка при обновлении настроек', 'error');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHtml;
                    });
                });
            });

            // Функция для показа уведомлений
            function showNotification(message, type = 'success') {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
                alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
                alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alertDiv);
                
                setTimeout(() => {
                    alertDiv.classList.remove('show');
                    setTimeout(() => alertDiv.remove(), 150);
                }, 3000);
            }

            // ========================================
            // ЗАГРУЗКА ФОТОГРАФИЙ
            // ========================================

            // Инициализация для каждой формы загрузки фото
            document.querySelectorAll('.upload-zone-photo').forEach(function(uploadZone) {
                const taskId = uploadZone.dataset.taskId;
                const fileInput = document.getElementById('photoFiles' + taskId);
                const uploadContent = uploadZone.querySelector('.upload-content-photo');
                const uploadPreview = uploadZone.querySelector('.upload-preview-photo');
                const previewGrid = uploadZone.querySelector('.photos-preview-grid');
                const form = uploadZone.closest('form');
                const submitBtn = form.querySelector('.upload-photos-btn');
                const clearBtn = uploadZone.querySelector('.clear-photos-btn');

                // Обработка выбора через кнопку камеры
                const cameraInput = document.getElementById('cameraFiles' + taskId);
                if (cameraInput) {
                    cameraInput.addEventListener('change', function() {
                        if (this.files.length > 0) {
                            // Копируем файлы из cameraInput в основной fileInput
                            const dt = new DataTransfer();
                            Array.from(this.files).forEach(file => dt.items.add(file));
                            fileInput.files = dt.files;
                            handleFiles(fileInput.files);
                        }
                    });
                }

                // Предотвращаем стандартное поведение drag and drop
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    uploadZone.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                // Подсветка при наведении
                ['dragenter', 'dragover'].forEach(eventName => {
                    uploadZone.addEventListener(eventName, function() {
                        uploadZone.classList.add('drag-over');
                    }, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    uploadZone.addEventListener(eventName, function() {
                        uploadZone.classList.remove('drag-over');
                    }, false);
                });

                // Обработка drop
                uploadZone.addEventListener('drop', function(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;

                    if (files.length > 0) {
                        // Фильтруем только изображения
                        const imageFiles = Array.from(files).filter(file => file.type.startsWith(
                            'image/'));
                        if (imageFiles.length > 0) {
                            const dt2 = new DataTransfer();
                            imageFiles.forEach(file => dt2.items.add(file));
                            fileInput.files = dt2.files;
                            handleFiles(fileInput.files);
                        }
                    }
                }, false);

                // Обработка выбора файлов через input
                fileInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        handleFiles(this.files);
                    }
                });

                function handleFiles(files) {
                    previewGrid.innerHTML = '';
                    const MAX_FILE_SIZE = 104857600; // 100MB в байтах

                    Array.from(files).forEach((file, index) => {
                        if (!file.type.startsWith('image/')) return;
                        
                        // Проверка размера файла
                        if (file.size > MAX_FILE_SIZE) {
                            alert(`Файл "${file.name}" слишком большой (${(file.size / 1048576).toFixed(2)} МБ). Максимальный размер: 100 МБ.`);
                            return;
                        }

                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const previewItem = document.createElement('div');
                            previewItem.className = 'photo-preview-item';

                            const img = document.createElement('img');
                            img.className = 'photo-preview-img';
                            img.src = e.target.result;

                            const removeBtn = document.createElement('div');
                            removeBtn.className = 'photo-preview-remove';
                            removeBtn.innerHTML = '<i class="bi bi-x"></i>';
                            removeBtn.onclick = function(event) {
                                event.stopPropagation();
                                removeFile(index);
                            };

                            previewItem.appendChild(img);
                            previewItem.appendChild(removeBtn);
                            previewGrid.appendChild(previewItem);
                        };

                        reader.readAsDataURL(file);
                    });

                    if (files.length > 0) {
                        uploadContent.classList.add('d-none');
                        uploadPreview.classList.remove('d-none');
                        submitBtn.disabled = false;
                    }
                }

                function removeFile(index) {
                    const dt = new DataTransfer();
                    const files = Array.from(fileInput.files);

                    files.forEach((file, i) => {
                        if (i !== index) {
                            dt.items.add(file);
                        }
                    });

                    fileInput.files = dt.files;

                    if (fileInput.files.length > 0) {
                        handleFiles(fileInput.files);
                    } else {
                        clearFiles();
                    }
                }

                function clearFiles() {
                    fileInput.value = '';
                    previewGrid.innerHTML = '';
                    uploadContent.classList.remove('d-none');
                    uploadPreview.classList.add('d-none');
                    submitBtn.disabled = true;
                }

                // Кнопка очистки
                if (clearBtn) {
                    clearBtn.addEventListener('click', clearFiles);
                }
            });
        });
    </script>

    
    <style>
    .long-press-indicator {
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 4px;
        background: linear-gradient(90deg, #dc3545, #ff6b6b);
        border-radius: 4px 0 0 0;
        transition: width 2s linear;
        z-index: 10;
        opacity: 0;
    }

    .long-press-indicator.active {
        opacity: 1;
        width: 100%;
    }

    .minimal-btn-danger {
        background-color: #dc3545;
        color: white;
        border: 2px solid #dc3545;
    }

    .minimal-btn-danger:hover {
        background-color: #bb2d3b;
        border-color: #bb2d3b;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let pressTimer;
        let isLongPress = false;

        // Функция для всех элементов с долгим нажатием
        document.querySelectorAll('[data-long-press="true"]').forEach(element => {
            const indicator = element.querySelector('.long-press-indicator');
            const deleteTarget = element.getAttribute('data-delete-target');
            
            // Mouse events
            element.addEventListener('mousedown', function(e) {
                // Игнорируем клик по интерактивным элементам
                if (e.target.closest('form, button, input, select, a, .dropdown')) {
                    return;
                }
                
                isLongPress = false;
                if (indicator) {
                    indicator.classList.add('active');
                }
                
                pressTimer = setTimeout(() => {
                    isLongPress = true;
                    if (indicator) {
                        indicator.classList.remove('active');
                    }
                    // Открываем модальное окно через клик по скрытой кнопке
                    if (deleteTarget) {
                        e.stopPropagation();
                        const modalId = deleteTarget.replace('#deleteStageModal', '').replace('#deleteTaskModal', '');
                        const triggerBtn = document.getElementById('triggerDeleteStage' + modalId) || 
                                          document.getElementById('triggerDeleteTask' + modalId);
                        if (triggerBtn) {
                            triggerBtn.click();
                        }
                    }
                }, 1000);
            });

            element.addEventListener('mouseup', function() {
                clearTimeout(pressTimer);
                if (indicator) {
                    indicator.classList.remove('active');
                }
            });

            element.addEventListener('mouseleave', function() {
                clearTimeout(pressTimer);
                if (indicator) {
                    indicator.classList.remove('active');
                }
            });

            // Touch events для мобильных устройств
            element.addEventListener('touchstart', function(e) {
                if (e.target.closest('form, button, input, select, a, .dropdown')) {
                    return;
                }
                
                isLongPress = false;
                if (indicator) {
                    indicator.classList.add('active');
                }
                
                pressTimer = setTimeout(() => {
                    isLongPress = true;
                    if (indicator) {
                        indicator.classList.remove('active');
                    }
                    if (deleteTarget) {
                        e.preventDefault();
                        e.stopPropagation();
                        const modalId = deleteTarget.replace('#deleteStageModal', '').replace('#deleteTaskModal', '');
                        const triggerBtn = document.getElementById('triggerDeleteStage' + modalId) || 
                                          document.getElementById('triggerDeleteTask' + modalId);
                        if (triggerBtn) {
                            triggerBtn.click();
                        }
                    }
                }, 1000);
            });

            element.addEventListener('touchend', function(e) {
                clearTimeout(pressTimer);
                if (indicator) {
                    indicator.classList.remove('active');
                }
                // Предотвращаем обычный клик, если было долгое нажатие
                if (isLongPress) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });

            element.addEventListener('touchmove', function() {
                clearTimeout(pressTimer);
                if (indicator) {
                    indicator.classList.remove('active');
                }
            });

            // Предотвращаем открытие стандартного модального окна при долгом нажатии
            element.addEventListener('click', function(e) {
                if (isLongPress) {
                    e.preventDefault();
                    e.stopPropagation();
                    isLongPress = false;
                }
            });
        });
    });
    </script>

    <!-- Модальное окно редактирования этапа -->
    <div class="modal fade" id="editStageModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen m-0">
            <div class="modal-content">
                <form action="<?php echo e(route('projects.stages.update', [$project, $stage])); ?>" method="POST" class="d-flex flex-column h-100">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-header border-0 pb-2">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="px-4">
                        <div class="wizard-header text-center">
                            <h2>Редактировать этап</h2>
                            <p>Измените название и период выполнения</p>
                        </div>
                    </div>
                    <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
                        <div class="wizard-container" style="max-width: 600px; width: 100%;">
                            <div class="form-group-minimal">
                                <label>Название этапа</label>
                                <input type="text" class="minimal-input" name="name" 
                                       value="<?php echo e($stage->name); ?>"
                                       placeholder="Например: Черновая отделка"
                                       maxlength="255"
                                       required>
                            </div>

                            <div class="form-group-minimal">
                                <label>Период выполнения</label>
                                <input type="text" class="minimal-input date-range-picker" 
                                       id="edit_stage_dates" 
                                       placeholder="Выберите даты"
                                       value="<?php echo e($stage->start_date->format('d.m.Y')); ?> - <?php echo e($stage->end_date->format('d.m.Y')); ?>"
                                       readonly>
                                <input type="hidden" name="start_date" id="edit_stage_start_date" value="<?php echo e($stage->start_date->format('Y-m-d')); ?>">
                                <input type="hidden" name="end_date" id="edit_stage_end_date" value="<?php echo e($stage->end_date->format('Y-m-d')); ?>">
                            </div>

                            <div class="form-group-minimal">
                                <label>Статус этапа</label>
                                <select class="minimal-input" name="status" required>
                                    <option value="Не начат" <?php echo e($stage->status === 'Не начат' ? 'selected' : ''); ?>>Не начат</option>
                                    <option value="В работе" <?php echo e($stage->status === 'В работе' ? 'selected' : ''); ?>>В работе</option>
                                    <option value="Готово" <?php echo e($stage->status === 'Готово' ? 'selected' : ''); ?>>Готово</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center">
                        <button type="button" class="minimal-btn minimal-btn-ghost" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="minimal-btn minimal-btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Инициализация Flatpickr для редактирования дат этапа
        const editStageDateInput = document.getElementById('edit_stage_dates');
        if (editStageDateInput) {
            flatpickr(editStageDateInput, {
                mode: 'range',
                locale: 'ru',
                dateFormat: 'd.m.Y',
                defaultDate: [
                    '<?php echo e($stage->start_date->format('d.m.Y')); ?>',
                    '<?php echo e($stage->end_date->format('d.m.Y')); ?>'
                ],
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        document.getElementById('edit_stage_start_date').value = flatpickr.formatDate(selectedDates[0], 'Y-m-d');
                        document.getElementById('edit_stage_end_date').value = flatpickr.formatDate(selectedDates[1], 'Y-m-d');
                    }
                }
            });
        }

        // ========================================
        // Поиск и пагинация задач
        // ========================================
        const tasksContainer = document.getElementById('tasksContainer');
        const tasksSearchInput = document.getElementById('tasksSearchInput');
        const clearTasksSearch = document.getElementById('clearTasksSearch');
        const tasksLoader = document.getElementById('tasksLoader');
        const noTasksFound = document.getElementById('noTasksFound');

        if (tasksContainer) {
            let tasksPage = 2; // Начинаем со второй страницы, т.к. первая уже загружена на сервере
            let tasksLoading = false;
            let tasksHasMore = <?php echo e($totalTasks > 20 ? 'true' : 'false'); ?>; // Есть ли еще задачи для загрузки
            let tasksSearchQuery = '';
            let tasksSearchTimeout = null;

            // НЕ загружаем первые задачи - они уже отображены на сервере

            // Поиск с задержкой 800мс
            if (tasksSearchInput) {
                tasksSearchInput.addEventListener('input', function() {
                    clearTimeout(tasksSearchTimeout);
                    
                    if (this.value.trim()) {
                        clearTasksSearch.style.display = 'block';
                    } else {
                        clearTasksSearch.style.display = 'none';
                    }
                    
                    tasksSearchTimeout = setTimeout(() => {
                        tasksSearchQuery = this.value.trim();
                        tasksPage = 1;
                        tasksHasMore = true;
                        tasksContainer.innerHTML = '';
                        loadTasks();
                    }, 800);
                });

                clearTasksSearch.addEventListener('click', function() {
                    tasksSearchInput.value = '';
                    this.style.display = 'none';
                    tasksSearchQuery = '';
                    // Перезагружаем страницу чтобы восстановить исходные задачи
                    window.location.reload();
                });
            }

            // Infinite scroll
            const tasksObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !tasksLoading && tasksHasMore) {
                        loadTasks();
                    }
                });
            }, { threshold: 0.1 });

            if (tasksLoader) {
                tasksObserver.observe(tasksLoader);
            }

            async function loadTasks() {
                if (tasksLoading || !tasksHasMore) return;

                tasksLoading = true;
                tasksLoader.classList.remove('d-none');
                noTasksFound.classList.add('d-none');

                try {
                    const projectId = tasksContainer.dataset.projectId;
                    const stageId = tasksContainer.dataset.stageId;
                    const url = `/projects/${projectId}/stages/${stageId}/tasks/search?page=${tasksPage}${tasksSearchQuery ? '&search=' + encodeURIComponent(tasksSearchQuery) : ''}`;
                    
                    const response = await fetch(url);
                    const data = await response.json();

                    if (data.tasks && data.tasks.length > 0) {
                        data.tasks.forEach(task => {
                            tasksContainer.insertAdjacentHTML('beforeend', renderTaskCard(task));
                        });

                        tasksHasMore = data.has_more;
                        if (tasksHasMore) {
                            tasksPage = data.next_page;
                        }
                    } else if (tasksPage === 1) {
                        noTasksFound.classList.remove('d-none');
                    }

                } catch (error) {
                    console.error('Ошибка загрузки задач:', error);
                } finally {
                    tasksLoading = false;
                    tasksLoader.classList.add('d-none');
                }
            }

            function renderTaskCard(task) {
                const projectId = tasksContainer.dataset.projectId;
                const stageId = tasksContainer.dataset.stageId;
                const photosCount = task.photos_count || 0;
                const commentsCount = task.comments_count || 0;
                
                let statusBadge = '';
                if (task.status === 'Завершена') {
                    statusBadge = '<span class="badge bg-success">Завершена</span>';
                } else if (task.status === 'В работе') {
                    statusBadge = '<span class="badge bg-primary">В работе</span>';
                } else if (task.status === 'На проверке') {
                    statusBadge = '<span class="badge bg-warning">На проверке</span>';
                } else {
                    statusBadge = '<span class="badge bg-secondary">Не начата</span>';
                }
                
                return `
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0">${escapeHtml(task.name)}</h6>
                                    ${statusBadge}
                                </div>
                                
                                ${task.description ? `<p class="text-muted small mb-2">${escapeHtml(task.description)}</p>` : ''}
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        ${photosCount > 0 ? `<i class="bi bi-image"></i> ${photosCount} ` : ''}
                                        ${commentsCount > 0 ? `<i class="bi bi-chat"></i> ${commentsCount}` : ''}
                                    </div>
                                    ${task.cost ? `<span class="badge bg-light text-dark">${formatCurrency(task.cost)} ₽</span>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            function escapeHtml(text) {
                if (!text) return '';
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function formatCurrency(amount) {
                return new Intl.NumberFormat('ru-RU').format(amount);
            }
        }
    });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views/stages/show.blade.php ENDPATH**/ ?>