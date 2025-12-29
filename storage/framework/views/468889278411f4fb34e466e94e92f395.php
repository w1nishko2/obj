

<?php $__env->startSection('content'); ?>
<div class="minimal-container py-3 py-md-4">
    <!-- Заголовок с кнопкой назад -->
    <div class="d-flex align-items-center justify-content-between mb-3 mb-md-4">
        <div class="d-flex align-items-center">
            <a href="<?php echo e(route('admin.index')); ?>" class="btn btn-light me-2">
                <i class="bi bi-arrow-left"></i>
            </a>
            <i class="bi bi-people-fill text-primary me-2" style="font-size: 1.5rem;"></i>
            <h1 class="mb-0">Клиенты и подписки</h1>
        </div>
    </div>

    <!-- Поиск -->
    <div class="card mb-3">
        <div class="card-body p-2 p-md-3">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       id="searchInput" 
                       placeholder="Поиск по имени, телефону или email..."
                       value="<?php echo e($search); ?>">
            </div>
        </div>
    </div>

    <!-- Список пользователей -->
    <div class="card">
        <div class="card-body p-0">
            <div id="usersList">
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('admin.partials.user-row', ['user' => $user, 'plans' => $plans], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Индикатор загрузки -->
            <div id="loadingIndicator" class="text-center py-3 d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Загрузка...</span>
                </div>
            </div>

            <!-- Сообщение о конце списка -->
            <div id="endMessage" class="text-center text-muted py-3 d-none">
                <i class="bi bi-check-circle"></i>
                Все пользователи загружены
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для редактирования -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Редактирование пользователя</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editUserId">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Имя пользователя</label>
                    <div id="editUserName" class="text-muted"></div>
                </div>

                <div class="mb-3">
                    <label for="editUserRole" class="form-label fw-bold">Роль (Тип аккаунта)</label>
                    <select class="form-select" id="editUserRole">
                        <option value="">Не выбрана</option>
                        <option value="foreman">Прораб</option>
                        <option value="executor">Исполнитель</option>
                        <option value="client">Клиент</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Подписка</label>
                    <div id="currentSubscription" class="alert alert-info mb-2">
                        Нет активной подписки
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-danger btn-sm" id="removeSubscriptionBtn">
                            <i class="bi bi-x-circle me-1"></i>
                            Отменить подписку
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="editUserPlan" class="form-label fw-bold">Назначить тариф</label>
                    <select class="form-select" id="editUserPlan">
                        <option value="">Выберите тариф...</option>
                        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($plan->id); ?>">
                                <?php echo e($plan->name); ?> - <?php echo e(number_format($plan->price, 0, ',', ' ')); ?> ₽
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="saveChangesBtn">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Стили для таблицы пользователей */
.user-row {
    border-bottom: 1px solid #dee2e6;
    padding: 0.75rem;
    transition: background-color 0.2s;
    cursor: pointer;
}

.user-row:hover {
    background-color: #f8f9fa;
}

.user-row:active {
    background-color: #e9ecef;
}

.user-info {
    flex: 1;
    min-width: 0;
}

.user-name {
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.user-phone {
    font-size: 0.85rem;
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.user-status {
    text-align: right;
    flex-shrink: 0;
}

.status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-weight: 500;
    white-space: nowrap;
}

.role-badge {
    display: inline-block;
    padding: 0.15rem 0.4rem;
    font-size: 0.7rem;
    border-radius: 4px;
    font-weight: 500;
}

.role-foreman {
    background-color: #d1f4e0;
    color: #198754;
}

.role-executor {
    background-color: #cfe2ff;
    color: #0d6efd;
}

.role-client {
    background-color: #fff3cd;
    color: #ffc107;
}

@media (max-width: 767px) {
    .user-row {
        padding: 0.5rem;
    }
    
    .user-name {
        font-size: 0.875rem;
    }
    
    .user-phone {
        font-size: 0.75rem;
    }
    
    .status-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
}

/* Защита от выделения */
.user-row {
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
    -webkit-touch-callout: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = <?php echo e($users->currentPage()); ?>;
    let hasMorePages = <?php echo e($users->hasMorePages() ? 'true' : 'false'); ?>;
    let isLoading = false;
    let searchTimeout = null;

    const usersList = document.getElementById('usersList');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const endMessage = document.getElementById('endMessage');
    const searchInput = document.getElementById('searchInput');
    const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));

    // Поиск с задержкой
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            performSearch();
        }, 500);
    });

    function performSearch() {
        const searchValue = searchInput.value;
        window.location.href = '<?php echo e(route("admin.clients")); ?>?search=' + encodeURIComponent(searchValue);
    }

    // Infinite scroll
    window.addEventListener('scroll', function() {
        if (isLoading || !hasMorePages) return;

        const scrollPosition = window.innerHeight + window.scrollY;
        const pageHeight = document.documentElement.scrollHeight;

        if (scrollPosition >= pageHeight - 300) {
            loadMoreUsers();
        }
    });

    function loadMoreUsers() {
        if (isLoading || !hasMorePages) return;

        isLoading = true;
        loadingIndicator.classList.remove('d-none');

        const searchValue = searchInput.value;
        const url = '<?php echo e(route("admin.clients")); ?>?page=' + (currentPage + 1) + '&search=' + encodeURIComponent(searchValue);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.users && data.users.length > 0) {
                data.users.forEach(user => {
                    const userRow = createUserRow(user);
                    usersList.insertAdjacentHTML('beforeend', userRow);
                });

                currentPage = data.nextPage;
                hasMorePages = data.hasMore;

                if (!hasMorePages) {
                    endMessage.classList.remove('d-none');
                }
            } else {
                hasMorePages = false;
                endMessage.classList.remove('d-none');
            }
        })
        .catch(error => {
            console.error('Ошибка загрузки:', error);
            alert('Ошибка при загрузке пользователей');
        })
        .finally(() => {
            isLoading = false;
            loadingIndicator.classList.add('d-none');
        });
    }

    function createUserRow(user) {
        let roleBadge = '';
        if (user.account_type) {
            const roleClasses = {
                'foreman': 'role-foreman',
                'executor': 'role-executor',
                'client': 'role-client'
            };
            const roleNames = {
                'foreman': 'Прораб',
                'executor': 'Исполнитель',
                'client': 'Клиент'
            };
            roleBadge = `<span class="role-badge ${roleClasses[user.account_type]}">${roleNames[user.account_type]}</span>`;
        }

        let subscriptionBadge = 'Нет подписки';
        if (user.active_subscription) {
            subscriptionBadge = `<span class="badge bg-success">${user.active_subscription.plan.name}</span>`;
        }

        return `
            <div class="user-row d-flex align-items-center" onclick="openEditModal(${user.id})">
                <div class="user-info">
                    <div class="user-name">
                        ${user.name}
                        ${roleBadge}
                    </div>
                    <div class="user-phone">
                        <i class="bi bi-phone"></i>
                        ${user.phone || 'Не указан'}
                    </div>
                </div>
                <div class="user-status">
                    <div class="status-badge">
                        ${subscriptionBadge}
                    </div>
                </div>
            </div>
        `;
    }

    // Открытие модального окна редактирования
    window.openEditModal = function(userId) {
        fetch(`/admin/clients?search=${encodeURIComponent(searchInput.value)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const user = data.users.find(u => u.id === userId);
            if (user) {
                document.getElementById('editUserId').value = user.id;
                document.getElementById('editUserName').textContent = user.name;
                document.getElementById('editUserRole').value = user.account_type || '';
                
                const subDiv = document.getElementById('currentSubscription');
                const removeBtn = document.getElementById('removeSubscriptionBtn');
                
                if (user.active_subscription) {
                    subDiv.className = 'alert alert-success mb-2';
                    subDiv.innerHTML = `<strong>${user.active_subscription.plan.name}</strong><br>
                        <small>До: ${new Date(user.active_subscription.expires_at).toLocaleDateString('ru-RU')}</small>`;
                    removeBtn.disabled = false;
                } else {
                    subDiv.className = 'alert alert-info mb-2';
                    subDiv.textContent = 'Нет активной подписки';
                    removeBtn.disabled = true;
                }

                editModal.show();
            }
        });
    };

    // Сохранение изменений
    document.getElementById('saveChangesBtn').addEventListener('click', function() {
        const userId = document.getElementById('editUserId').value;
        const role = document.getElementById('editUserRole').value;
        const planId = document.getElementById('editUserPlan').value;

        // Обновление роли
        if (role !== null) {
            updateRole(userId, role);
        }

        // Назначение тарифа
        if (planId) {
            updateSubscription(userId, planId, 'add');
        }
    });

    // Удаление подписки
    document.getElementById('removeSubscriptionBtn').addEventListener('click', function() {
        const userId = document.getElementById('editUserId').value;
        if (confirm('Вы уверены, что хотите отменить подписку?')) {
            updateSubscription(userId, null, 'remove');
        }
    });

    function updateRole(userId, role) {
        fetch(`/admin/users/${userId}/role`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ account_type: role })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Роль обновлена успешно', 'success');
                setTimeout(() => location.reload(), 1000);
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            showToast('Ошибка при обновлении роли', 'danger');
        });
    }

    function updateSubscription(userId, planId, action) {
        fetch(`/admin/users/${userId}/subscription`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ plan_id: planId, action: action })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Ошибка', 'danger');
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            showToast('Ошибка при обновлении подписки', 'danger');
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
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\work\resources\views\admin\clients.blade.php ENDPATH**/ ?>