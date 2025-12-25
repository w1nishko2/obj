<?php

if (!function_exists('can_manage_project')) {
    /**
     * Проверяет, может ли текущий пользователь управлять проектом
     * (только владелец проекта)
     */
    function can_manage_project($project)
    {
        return auth()->check() && auth()->user()->isForemanOfProject($project);
    }
}

if (!function_exists('can_view_costs')) {
    /**
     * Проверяет, может ли пользователь видеть стоимость в проекте
     * (владелец или клиент)
     */
    function can_view_costs($project)
    {
        if (!auth()->check()) {
            return false;
        }
        
        $user = auth()->user();
        $role = $user->getRoleInProject($project);
        
        return in_array($role, ['owner', 'Клиент']);
    }
}

if (!function_exists('can_edit_tasks')) {
    /**
     * Проверяет, может ли пользователь редактировать задачи в проекте
     * (владелец или исполнитель)
     */
    function can_edit_tasks($project)
    {
        return auth()->check() && auth()->user()->canEditTasksInProject($project);
    }
}
