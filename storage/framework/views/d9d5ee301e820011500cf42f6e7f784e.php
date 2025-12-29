

<?php $__env->startSection('content'); ?>
<div class="minimal-container py-3 py-md-4">
    <!-- Заголовок -->
    <div class="d-flex align-items-center justify-content-between mb-3 mb-md-4">
        <div class="d-flex align-items-center">
            <a href="<?php echo e(route('admin.index')); ?>" class="btn btn-light me-2">
                <i class="bi bi-arrow-left"></i>
            </a>
            <i class="bi bi-tag-fill text-success me-2" style="font-size: 1.5rem;"></i>
            <h1 class="mb-0">Промокоды</h1>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createPromocodeModal">
            <i class="bi bi-plus-lg me-1"></i>
            Создать
        </button>
    </div>

    <!-- Список промокодов -->
    <?php if($promocodes->count() > 0): ?>
        <div class="row g-3">
            <?php $__currentLoopData = $promocodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12">
                    <div class="card promocode-card">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h5 class="mb-0 fw-bold"><?php echo e($promo->code); ?></h5>
                                        <?php if($promo->is_active): ?>
                                            <span class="badge bg-success">Активен</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Неактивен</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="promo-discount">
                                        <i class="bi bi-percent me-1"></i>
                                        <?php echo e($promo->discount_percent); ?>% скидка
                                    </div>
                                    <?php if($promo->description): ?>
                                        <div class="text-muted small mt-1"><?php echo e($promo->description); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="<?php echo e(route('admin.promocodes.analytics', $promo->id)); ?>">
                                                <i class="bi bi-graph-up me-2"></i>Аналитика
                                            </a>
                                        </li>
                                        <li>
                                            <button class="dropdown-item" onclick="editPromocode(<?php echo e($promo->id); ?>)">
                                                <i class="bi bi-pencil me-2"></i>Редактировать
                                            </button>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button class="dropdown-item text-danger" onclick="deletePromocode(<?php echo e($promo->id); ?>)">
                                                <i class="bi bi-trash me-2"></i>Удалить
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Статистика -->
                            <div class="row g-2 mt-2">
                                <div class="col-6 col-md-3">
                                    <div class="stat-mini">
                                        <div class="stat-mini-label">Использований</div>
                                        <div class="stat-mini-value"><?php echo e($promo->stats['total_usages']); ?></div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="stat-mini">
                                        <div class="stat-mini-label">Выручка</div>
                                        <div class="stat-mini-value"><?php echo e(number_format($promo->stats['total_revenue'], 0, ',', ' ')); ?> ₽</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="stat-mini">
                                        <div class="stat-mini-label">Скидка</div>
                                        <div class="stat-mini-value text-danger"><?php echo e(number_format($promo->stats['total_discount'], 0, ',', ' ')); ?> ₽</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="stat-mini">
                                        <div class="stat-mini-label">Пользователей</div>
                                        <div class="stat-mini-value"><?php echo e($promo->stats['unique_users']); ?></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Дополнительная информация -->
                            <div class="mt-2 pt-2 border-top">
                                <div class="row g-2 small text-muted">
                                    <?php if($promo->usage_limit): ?>
                                        <div class="col-auto">
                                            <i class="bi bi-speedometer2 me-1"></i>
                                            Лимит: <?php echo e($promo->usage_count); ?>/<?php echo e($promo->usage_limit); ?>

                                        </div>
                                    <?php endif; ?>
                                    <?php if($promo->expires_at): ?>
                                        <div class="col-auto">
                                            <i class="bi bi-calendar-x me-1"></i>
                                            До: <?php echo e($promo->expires_at->format('d.m.Y')); ?>

                                        </div>
                                    <?php endif; ?>
                                    <div class="col-auto">
                                        <i class="bi bi-clock me-1"></i>
                                        <?php echo e($promo->created_at->format('d.m.Y')); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-tag" style="font-size: 3rem; opacity: 0.3;"></i>
            <p class="text-muted mt-3">Нет промокодов</p>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createPromocodeModal">
                Создать первый промокод
            </button>
        </div>
    <?php endif; ?>
</div>

<!-- Модальное окно создания промокода -->
<div class="modal fade" id="createPromocodeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Создать промокод</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createPromocodeForm">
                    <div class="mb-3">
                        <label for="code" class="form-label fw-bold">Код промокода *</label>
                        <input type="text" class="form-control text-uppercase" id="code" name="code" required placeholder="НАПРИМЕР: VASYA20">
                        <small class="text-muted">Латинские буквы и цифры</small>
                    </div>

                    <div class="mb-3">
                        <label for="discount_percent" class="form-label fw-bold">Процент скидки * (%)</label>
                        <input type="number" class="form-control" id="discount_percent" name="discount_percent" min="1" max="100" required>
                    </div>

                    <div class="mb-3">
                        <label for="usage_limit" class="form-label fw-bold">Лимит использований</label>
                        <input type="number" class="form-control" id="usage_limit" name="usage_limit" min="1" placeholder="Без ограничений">
                    </div>

                    <div class="mb-3">
                        <label for="expires_at" class="form-label fw-bold">Дата истечения</label>
                        <input type="date" class="form-control" id="expires_at" name="expires_at">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Описание</label>
                        <textarea class="form-control" id="description" name="description" rows="2" placeholder="Для кого этот промокод?"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-success" onclick="createPromocode()">Создать</button>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно редактирования -->
<div class="modal fade" id="editPromocodeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Редактировать промокод</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_id">
                <form id="editPromocodeForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Код промокода</label>
                        <input type="text" class="form-control" id="edit_code" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="edit_discount_percent" class="form-label fw-bold">Процент скидки (%)</label>
                        <input type="number" class="form-control" id="edit_discount_percent" min="1" max="100">
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="edit_is_active">
                            <label class="form-check-label" for="edit_is_active">Активен</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_usage_limit" class="form-label fw-bold">Лимит использований</label>
                        <input type="number" class="form-control" id="edit_usage_limit" min="1">
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label fw-bold">Описание</label>
                        <textarea class="form-control" id="edit_description" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" onclick="updatePromocode()">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<style>
.promocode-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.promocode-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.promocode-card .card-body {
    padding: 1rem !important;
}

.promo-discount {
    font-size: 1rem;
    color: #198754;
    font-weight: 600;
}

.stat-mini {
    background: #f8f9fa;
    padding: 0.75rem 0.5rem;
    border-radius: 8px;
    text-align: center;
}

.stat-mini-label {
    font-size: 0.7rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
    line-height: 1.2;
}

.stat-mini-value {
    font-size: 0.95rem;
    font-weight: 700;
    color: #212529;
    line-height: 1.2;
}

.promocode-card h5 {
    font-size: 1.25rem;
    word-break: break-all;
}

.promocode-card .badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

.promocode-card .dropdown .btn {
    padding: 0.25rem 0.5rem;
}

/* Мобильная адаптация */
@media (max-width: 767px) {
    .promocode-card .card-body {
        padding: 0.75rem !important;
    }
    
    .promocode-card h5 {
        font-size: 1rem;
    }
    
    .promo-discount {
        font-size: 0.875rem;
    }
    
    .stat-mini {
        padding: 0.5rem 0.25rem;
    }
    
    .stat-mini-label {
        font-size: 0.65rem;
    }
    
    .stat-mini-value {
        font-size: 0.8rem;
    }
    
    .promocode-card .text-muted.small {
        font-size: 0.75rem !important;
    }
    
    .promocode-card .badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.4rem;
    }
    
    .promocode-card .border-top {
        margin-top: 0.5rem !important;
        padding-top: 0.5rem !important;
    }
    
    .promocode-card .border-top .col-auto {
        font-size: 0.7rem !important;
    }
    
    .promocode-card .dropdown-menu {
        font-size: 0.875rem;
    }
}

/* Очень маленькие экраны */
@media (max-width: 360px) {
    .promocode-card h5 {
        font-size: 0.9rem;
    }
    
    .stat-mini-label {
        font-size: 0.6rem;
    }
    
    .stat-mini-value {
        font-size: 0.75rem;
    }
}

/* Защита от выделения */
.promocode-card {
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
    -webkit-touch-callout: none;
}
</style>

<script>
const createModal = new bootstrap.Modal(document.getElementById('createPromocodeModal'));
const editModal = new bootstrap.Modal(document.getElementById('editPromocodeModal'));

function createPromocode() {
    const form = document.getElementById('createPromocodeForm');
    const formData = new FormData(form);
    
    fetch('<?php echo e(route("admin.promocodes.create")); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Ошибка при создании промокода', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Ошибка при создании промокода', 'danger');
    });
}

function editPromocode(id) {
    // Загружаем данные промокода
    const promoCard = event.target.closest('.card');
    // Здесь можно сделать AJAX запрос или взять данные из DOM
    
    editModal.show();
}

function updatePromocode() {
    const id = document.getElementById('edit_id').value;
    const data = {
        discount_percent: document.getElementById('edit_discount_percent').value,
        is_active: document.getElementById('edit_is_active').checked,
        usage_limit: document.getElementById('edit_usage_limit').value || null,
        description: document.getElementById('edit_description').value
    };
    
    fetch(`/admin/promocodes/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Ошибка при обновлении', 'danger');
    });
}

function deletePromocode(id) {
    if (!confirm('Вы уверены, что хотите удалить этот промокод?')) return;
    
    fetch(`/admin/promocodes/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Ошибка при удалении', 'danger');
    });
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed bottom-0 end-0 m-3`;
    toast.style.zIndex = '9999';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Автоматическое преобразование в верхний регистр
document.getElementById('code').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views\admin\promocodes.blade.php ENDPATH**/ ?>