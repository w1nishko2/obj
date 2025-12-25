<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Collection;

class ProjectNotificationService
{
    protected WebPushService $webPushService;

    public function __construct(WebPushService $webPushService)
    {
        $this->webPushService = $webPushService;
    }

    /**
     * Получить ID всех участников проекта (кроме текущего пользователя)
     */
    protected function getProjectParticipantIds(Project $project, ?int $excludeUserId = null): array
    {
        $participantIds = $project->participants()
            ->pluck('user_id')
            ->toArray();

        // Добавляем создателя проекта
        if (!in_array($project->user_id, $participantIds)) {
            $participantIds[] = $project->user_id;
        }

        // Исключаем текущего пользователя (того, кто совершил действие)
        if ($excludeUserId) {
            $participantIds = array_filter($participantIds, fn($id) => $id !== $excludeUserId);
        }

        return array_values($participantIds);
    }

    /**
     * Уведомить участников о создании нового этапа
     */
    public function notifyStageCreated(Project $project, $stage, User $creator): void
    {
        $participantIds = $this->getProjectParticipantIds($project, $creator->id);

        if (empty($participantIds)) {
            return;
        }

        $this->webPushService->sendToUsers(
            userIds: $participantIds,
            payload: [
                'title' => '🏗️ Новый этап в проекте',
                'body' => "«{$stage->name}» добавлен в проект «{$project->name}»",
                'icon' => '/images/icons/icon.svg',
                'badge' => '/images/icons/badge.png',
                'tag' => 'stage-' . $stage->id,
                'data' => [
                    'url' => route('projects.show', $project->id),
                    'project_id' => $project->id,
                    'stage_id' => $stage->id,
                    'type' => 'stage_created'
                ]
            ]
        );
    }

    /**
     * Уведомить участников о создании новой задачи
     */
    public function notifyTaskCreated(Project $project, $stage, $task, User $creator): void
    {
        $participantIds = $this->getProjectParticipantIds($project, $creator->id);

        if (empty($participantIds)) {
            return;
        }

        $this->webPushService->sendToUsers(
            userIds: $participantIds,
            payload: [
                'title' => '✅ Новая задача',
                'body' => "«{$task->name}» в этапе «{$stage->name}»",
                'icon' => '/images/icons/icon.svg',
                'badge' => '/images/icons/badge.png',
                'tag' => 'task-' . $task->id,
                'data' => [
                    'url' => route('stages.show', [$project->id, $stage->id]),
                    'project_id' => $project->id,
                    'stage_id' => $stage->id,
                    'task_id' => $task->id,
                    'type' => 'task_created'
                ],
                'actions' => [
                    [
                        'action' => 'view',
                        'title' => '👁️ Посмотреть'
                    ]
                ]
            ]
        );
    }

    /**
     * Уведомить участников о добавлении материала
     */
    public function notifyMaterialAdded(Project $project, $stage, $material, User $creator): void
    {
        $participantIds = $this->getProjectParticipantIds($project, $creator->id);

        if (empty($participantIds)) {
            return;
        }

        $this->webPushService->sendToUsers(
            userIds: $participantIds,
            payload: [
                'title' => '📦 Добавлен материал',
                'body' => "«{$material->name}» ({$material->quantity} {$material->unit}) в «{$stage->name}»",
                'icon' => '/images/icons/icon.svg',
                'badge' => '/images/icons/badge.png',
                'tag' => 'material-' . $material->id,
                'data' => [
                    'url' => route('stages.show', [$project->id, $stage->id]),
                    'project_id' => $project->id,
                    'stage_id' => $stage->id,
                    'material_id' => $material->id,
                    'type' => 'material_added'
                ]
            ]
        );
    }

    /**
     * Уведомить участников о добавлении доставки
     */
    public function notifyDeliveryAdded(Project $project, $stage, $delivery, User $creator): void
    {
        $participantIds = $this->getProjectParticipantIds($project, $creator->id);

        if (empty($participantIds)) {
            return;
        }

        $this->webPushService->sendToUsers(
            userIds: $participantIds,
            payload: [
                'title' => '🚚 Новая доставка',
                'body' => "Доставка запланирована на {$delivery->delivery_date->format('d.m.Y')} в «{$stage->name}»",
                'icon' => '/images/icons/icon.svg',
                'badge' => '/images/icons/badge.png',
                'tag' => 'delivery-' . $delivery->id,
                'data' => [
                    'url' => route('stages.show', [$project->id, $stage->id]),
                    'project_id' => $project->id,
                    'stage_id' => $stage->id,
                    'delivery_id' => $delivery->id,
                    'type' => 'delivery_added'
                ]
            ]
        );
    }

    /**
     * Уведомить участников о добавлении нового участника
     */
    public function notifyParticipantAdded(Project $project, User $newParticipant, User $addedBy): void
    {
        // Уведомляем всех участников кроме того, кто добавил и самого нового участника
        $participantIds = $this->getProjectParticipantIds($project, $addedBy->id);
        $participantIds = array_filter($participantIds, fn($id) => $id !== $newParticipant->id);

        if (!empty($participantIds)) {
            $this->webPushService->sendToUsers(
                userIds: $participantIds,
                payload: [
                    'title' => '👤 Новый участник',
                    'body' => "{$newParticipant->name} добавлен в проект «{$project->name}»",
                    'icon' => '/images/icons/icon.svg',
                    'badge' => '/images/icons/badge.png',
                    'tag' => 'participant-' . $newParticipant->id . '-' . $project->id,
                    'data' => [
                        'url' => route('projects.show', $project->id),
                        'project_id' => $project->id,
                        'user_id' => $newParticipant->id,
                        'type' => 'participant_added'
                    ]
                ]
            );
        }

        // Уведомляем самого нового участника отдельно
        $this->webPushService->sendToUser(
            userId: $newParticipant->id,
            payload: [
                'title' => '🎉 Вас добавили в проект',
                'body' => "Вы стали участником проекта «{$project->name}»",
                'icon' => '/images/icons/icon.svg',
                'badge' => '/images/icons/badge.png',
                'requireInteraction' => true,
                'data' => [
                    'url' => route('projects.show', $project->id),
                    'project_id' => $project->id,
                    'type' => 'added_to_project'
                ],
                'actions' => [
                    [
                        'action' => 'view',
                        'title' => '👁️ Открыть проект'
                    ]
                ]
            ]
        );
    }

    /**
     * Уведомить участников о добавлении фото к задаче
     */
    public function notifyPhotoAdded(Project $project, $stage, $task, $photo, User $uploader): void
    {
        $participantIds = $this->getProjectParticipantIds($project, $uploader->id);

        if (empty($participantIds)) {
            return;
        }

        $this->webPushService->sendToUsers(
            userIds: $participantIds,
            payload: [
                'title' => '📸 Добавлено фото',
                'body' => "Новое фото в задаче «{$task->name}»",
                'icon' => '/images/icons/icon.svg',
                'badge' => '/images/icons/badge.png',
                'image' => $photo->url ?? null,
                'tag' => 'photo-' . $task->id,
                'data' => [
                    'url' => route('stages.show', [$project->id, $stage->id]),
                    'project_id' => $project->id,
                    'stage_id' => $stage->id,
                    'task_id' => $task->id,
                    'photo_id' => $photo->id,
                    'type' => 'photo_added'
                ],
                'actions' => [
                    [
                        'action' => 'view',
                        'title' => '👁️ Посмотреть'
                    ]
                ]
            ]
        );
    }

    /**
     * Уведомить участников о новом комментарии к задаче
     */
    public function notifyCommentAdded(Project $project, $stage, $task, $comment, User $author): void
    {
        $participantIds = $this->getProjectParticipantIds($project, $author->id);

        if (empty($participantIds)) {
            return;
        }

        $commentPreview = mb_substr($comment->comment, 0, 100);
        if (mb_strlen($comment->comment) > 100) {
            $commentPreview .= '...';
        }

        $this->webPushService->sendToUsers(
            userIds: $participantIds,
            payload: [
                'title' => "💬 Комментарий от {$author->name}",
                'body' => $commentPreview,
                'icon' => '/images/icons/icon.svg',
                'badge' => '/images/icons/badge.png',
                'tag' => 'comment-' . $task->id,
                'renotify' => true,
                'data' => [
                    'url' => route('stages.show', [$project->id, $stage->id]),
                    'project_id' => $project->id,
                    'stage_id' => $stage->id,
                    'task_id' => $task->id,
                    'comment_id' => $comment->id,
                    'type' => 'comment_added'
                ],
                'actions' => [
                    [
                        'action' => 'view',
                        'title' => '👁️ Посмотреть'
                    ],
                    [
                        'action' => 'reply',
                        'title' => '💬 Ответить'
                    ]
                ]
            ]
        );
    }

    /**
     * Уведомить участников об изменении статуса задачи
     */
    public function notifyTaskStatusChanged(Project $project, $stage, $task, string $newStatus, User $changedBy): void
    {
        $participantIds = $this->getProjectParticipantIds($project, $changedBy->id);

        if (empty($participantIds)) {
            return;
        }

        $statusNames = [
            'pending' => 'Ожидает',
            'in_progress' => 'В работе',
            'completed' => 'Завершена'
        ];

        $statusIcons = [
            'pending' => '⏳',
            'in_progress' => '🔧',
            'completed' => '✅'
        ];

        $statusName = $statusNames[$newStatus] ?? $newStatus;
        $icon = $statusIcons[$newStatus] ?? '📋';

        $this->webPushService->sendToUsers(
            userIds: $participantIds,
            payload: [
                'title' => "{$icon} Задача: {$statusName}",
                'body' => "«{$task->name}» изменила статус",
                'icon' => '/images/icons/icon.svg',
                'badge' => '/images/icons/badge.png',
                'tag' => 'task-status-' . $task->id,
                'data' => [
                    'url' => route('stages.show', [$project->id, $stage->id]),
                    'project_id' => $project->id,
                    'stage_id' => $stage->id,
                    'task_id' => $task->id,
                    'type' => 'task_status_changed',
                    'status' => $newStatus
                ]
            ]
        );
    }
}
