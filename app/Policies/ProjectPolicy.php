<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Models\ProjectUserRole;

class ProjectPolicy
{
    /**
     * Просмотр проекта - любой участник
     */
    public function view(User $user, Project $project)
    {
        // Проверяем наличие роли в project_user_roles
        return ProjectUserRole::where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Редактирование настроек проекта
     */
    public function update(User $user, Project $project)
    {
        return $user->hasProjectPermission($project, 'can_edit_project');
    }

    /**
     * Удаление проекта - только владелец
     */
    public function delete(User $user, Project $project)
    {
        $role = $user->getRoleInProject($project);
        return $role && $role->role === 'owner';
    }

    /**
     * Управление командой (добавление/удаление участников)
     */
    public function manageTeam(User $user, Project $project)
    {
        return $user->canManageTeamInProject($project);
    }

    /**
     * Создание этапов
     */
    public function createStages(User $user, Project $project)
    {
        return $user->canCreateStagesInProject($project);
    }

    /**
     * Редактирование задач
     */
    public function editTasks(User $user, Project $project)
    {
        return $user->canEditTasksInProject($project);
    }

    /**
     * Просмотр стоимости/смет
     */
    public function viewCosts(User $user, Project $project)
    {
        return $user->canViewCostsInProject($project);
    }

    /**
     * Загрузка документов
     */
    public function uploadDocuments(User $user, Project $project)
    {
        return $user->canUploadDocumentsInProject($project);
    }

    /**
     * Генерация отчётов/документов
     */
    public function generateReports(User $user, Project $project)
    {
        // Должен быть владельцем проекта
        $role = $user->getRoleInProject($project);
        if (!$role || $role->role !== 'owner') {
            return false;
        }

        // Проверяем активную подписку (не просто наличие тарифа)
        if (!$user->hasActiveSubscription()) {
            return false;
        }

        // Проверяем права на генерацию документов
        return $user->canGenerateDocuments($project) || $user->canGenerateEstimates();
    }

    /**
     * Архивирование проекта - только владелец
     */
    public function archive(User $user, Project $project)
    {
        $role = $user->getRoleInProject($project);
        return $role && $role->role === 'owner';
    }
}
