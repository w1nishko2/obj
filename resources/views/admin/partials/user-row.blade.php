<div class="user-row d-flex align-items-center" onclick="openEditModal({{ $user->id }})">
    <div class="user-info">
        <div class="user-name">
            {{ $user->name }}
            @if($user->account_type)
                @if($user->account_type == 'foreman')
                    <span class="role-badge role-foreman">Прораб</span>
                @elseif($user->account_type == 'executor')
                    <span class="role-badge role-executor">Исполнитель</span>
                @elseif($user->account_type == 'client')
                    <span class="role-badge role-client">Клиент</span>
                @endif
            @endif
        </div>
        <div class="user-phone">
            <i class="bi bi-phone"></i>
            {{ $user->phone ?? 'Не указан' }}
        </div>
    </div>
    <div class="user-status">
        <div class="status-badge">
            @if($user->activeSubscription)
                <span class="badge bg-success">{{ $user->activeSubscription->plan->name }}</span>
            @else
                <span class="text-muted small">Нет подписки</span>
            @endif
        </div>
    </div>
</div>
