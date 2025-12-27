<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'account_type',
        'subscription_type',
        'subscription_expires_at',
        'role_selected',
        // Данные прораба
        'full_name',
        'address',
        'passport_series',
        'passport_number',
        'passport_issued_by',
        'passport_issued_date',
        'inn',
        'organization_name',
    ];

    /**
     * Поле для аутентификации (телефон вместо email)
     */
    public function username()
    {
        return 'phone';
    }

    public function projects()
    {
        return $this->hasMany(\App\Models\Project::class);
    }

    public function participatingProjects()
    {
        return $this->belongsToMany(
            \App\Models\Project::class,
            'project_participants',
            'user_id',
            'project_id'
        )->withTimestamps();
    }

    // Новая связь с ролями в проектах
    public function projectRoles()
    {
        return $this->hasMany(\App\Models\ProjectUserRole::class);
    }

    // Связи с платежами и подписками
    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(\App\Models\Subscription::class)
            ->where('status', 'active')
            ->latest();
    }

    // Получить все проекты через новую таблицу ролей
    public function projectsWithRoles()
    {
        return $this->belongsToMany(
            \App\Models\Project::class,
            'project_user_roles',
            'user_id',
            'project_id'
        )->withPivot(['role', 'can_edit_project', 'can_manage_team', 'can_create_stages', 'can_edit_tasks', 'can_view_costs', 'can_upload_documents', 'can_generate_reports'])
         ->withTimestamps();
    }

    public function allAccessibleProjects()
    {
        // Проекты, где пользователь имеет роль (через project_user_roles)
        return \App\Models\Project::whereHas('userRoles', function($query) {
                $query->where('user_id', $this->id);
            })
            ->with(['stages', 'participants', 'userRoles'])
            ->orderBy('created_at', 'desc');
    }

    // ========================================
    // ТИП АККАУНТА (глобальный)
    // ========================================
    
    /**
     * Является ли пользователь прорабом (может создавать проекты)
     */
    public function isForeman()
    {
        return $this->account_type === 'foreman';
    }

    /**
     * Является ли пользователь клиентом (только просмотр)
     */
    public function isClient()
    {
        return $this->account_type === 'client';
    }

    /**
     * @deprecated Используйте isExecutorInProject($project)
     */
    public function isEmployee()
    {
        // Проверяем, есть ли роль исполнителя хотя бы в одном проекте
        return \App\Models\ProjectUserRole::where('user_id', $this->id)
            ->where('role', 'executor')
            ->exists();
    }
    
    /**
     * Переключить аккаунт на прораба (активировать тариф)
     */
    public function upgradeToForeman()
    {
        $this->account_type = 'foreman';
        $this->save();
    }

    // ========================================
    // НОВАЯ СИСТЕМА РОЛЕЙ В ПРОЕКТАХ
    // ========================================

    // Проверка, является ли пользователь владельцем конкретного проекта
    public function ownsProject($project)
    {
        $projectId = is_object($project) ? $project->id : $project;
        return $this->id === (is_object($project) ? $project->user_id : \App\Models\Project::find($projectId)?->user_id);
    }

    // Проверка, является ли пользователь владельцем проекта И прорабом
    public function isForemanOfProject($project)
    {
        return $this->isForeman() && $this->ownsProject($project);
    }

    // Получить роль пользователя в конкретном проекте (НОВАЯ СИСТЕМА)
    public function getRoleInProject($project)
    {
        $projectId = is_object($project) ? $project->id : $project;
        
        // Ищем в новой таблице project_user_roles
        $roleRecord = \App\Models\ProjectUserRole::where('project_id', $projectId)
            ->where('user_id', $this->id)
            ->first();
        
        if ($roleRecord) {
            return $roleRecord;
        }
        
        // Fallback: проверяем старую систему для совместимости
        if ($this->ownsProject($project)) {
            // Владелец проекта - создаём виртуальную роль
            $virtual = new \App\Models\ProjectUserRole();
            $virtual->role = 'owner';
            $virtual->forceFill(\App\Models\ProjectUserRole::getDefaultPermissions('owner'));
            return $virtual;
        }
        
        return null; // Не участвует в проекте
    }

    // Проверка, является ли пользователь клиентом в конкретном проекте
    public function isClientInProject($project)
    {
        $role = $this->getRoleInProject($project);
        return $role && $role->role === 'client';
    }

    // Проверка, является ли пользователь исполнителем в конкретном проекте
    public function isExecutorInProject($project)
    {
        $role = $this->getRoleInProject($project);
        return $role && $role->role === 'executor';
    }

    // Проверка конкретного права в проекте
    public function hasProjectPermission($project, $permission)
    {
        $role = $this->getRoleInProject($project);
        return $role ? $role->hasPermission($permission) : false;
    }

    // Может ли пользователь редактировать задачи в проекте
    public function canEditTasksInProject($project)
    {
        return $this->hasProjectPermission($project, 'can_edit_tasks');
    }

    // Может ли пользователь видеть стоимость в проекте
    public function canViewCostsInProject($project)
    {
        return $this->hasProjectPermission($project, 'can_view_costs');
    }

    // Может ли пользователь управлять командой в проекте
    public function canManageTeamInProject($project)
    {
        return $this->hasProjectPermission($project, 'can_manage_team');
    }

    // Может ли пользователь создавать этапы в проекте
    public function canCreateStagesInProject($project)
    {
        return $this->hasProjectPermission($project, 'can_create_stages');
    }

    // Может ли пользователь загружать документы в проекте
    public function canUploadDocumentsInProject($project)
    {
        return $this->hasProjectPermission($project, 'can_upload_documents');
    }

    // Может ли пользователь генерировать отчёты для проекта
    public function canGenerateReportsInProject($project)
    {
        return $this->hasProjectPermission($project, 'can_generate_reports');
    }

    // Методы проверки типа подписки
    public function isFreePlan()
    {
        return $this->subscription_type === 'free';
    }

    public function isMonthlyPlan()
    {
        return $this->subscription_type === 'monthly';
    }

    public function isYearlyPlan()
    {
        return $this->subscription_type === 'yearly';
    }

    public function hasPaidPlan()
    {
        // Проверяем все платные тарифы (кроме free)
        return in_array($this->subscription_type, [
            'starter', 'starter_yearly',
            'professional', 'professional_yearly',
            'corporate', 'corporate_yearly',
            // Старые тарифы (для обратной совместимости)
            'monthly', 'yearly'
        ]);
    }

    public function hasAnyPlan()
    {
        return $this->subscription_type !== null;
    }

    /**
     * Проверяет, не истекла ли подписка
     */
    public function isSubscriptionExpired()
    {
        if (!$this->subscription_expires_at) {
            return false; // Бессрочная подписка
        }

        return $this->subscription_expires_at->isPast();
    }

    /**
     * Проверяет, есть ли активная подписка
     */
    public function hasActiveSubscription()
    {
        return $this->hasAnyPlan() && !$this->isSubscriptionExpired();
    }

    public function canCreateProjects()
    {
        // Если нет подписки - не может создавать проекты
        if (!$this->hasAnyPlan()) {
            return false;
        }

        // Проверяем, не истекла ли подписка
        if ($this->isSubscriptionExpired()) {
            return false;
        }

        $plan = \App\Models\Plan::where('slug', $this->subscription_type)->first();
        
        if (!$plan) {
            return false;
        }

        $maxProjects = $plan->features['max_projects'] ?? 0;
        
        // null = безлимит (корпоративный тариф)
        if ($maxProjects === null) {
            return true;
        }
        
        $currentProjectsCount = $this->projects()->count();
        return $currentProjectsCount < $maxProjects;
    }

    public function canGenerateDocuments($project = null)
    {
        // Если проект не передан или пользователь является владельцем
        if ($project === null || $this->ownsProject($project)) {
            // Проверяем активность подписки
            if (!$this->hasActiveSubscription()) {
                return false;
            }

            $plan = \App\Models\Plan::where('slug', $this->subscription_type)->first();
            
            if (!$plan) {
                return false;
            }
            
            return $plan->features['can_generate_documents'] ?? false;
        }
        
        // Если пользователь не владелец, проверяем тариф владельца проекта
        $projectOwner = is_object($project) ? $project->user : \App\Models\Project::find($project)?->user;
        return $projectOwner ? $projectOwner->canGenerateDocuments() : false;
    }

    public function canGenerateEstimates()
    {
        // Проверяем активность подписки
        if (!$this->hasActiveSubscription()) {
            return false;
        }

        $plan = \App\Models\Plan::where('slug', $this->subscription_type)->first();
        
        if (!$plan) {
            return false;
        }
        
        return $plan->features['can_generate_estimates'] ?? false;
    }

    public function canArchiveProjects()
    {
        // Проверяем активность подписки
        if (!$this->hasActiveSubscription()) {
            return false;
        }

        $plan = \App\Models\Plan::where('slug', $this->subscription_type)->first();
        
        if (!$plan) {
            return false;
        }
        
        return $plan->features['can_archive_projects'] ?? false;
    }

    public function getRemainingProjectsCount()
    {
        // Если нет подписки - 0 проектов
        if (!$this->hasAnyPlan()) {
            return 0;
        }

        $plan = \App\Models\Plan::where('slug', $this->subscription_type)->first();
        
        if (!$plan) {
            return 0;
        }

        $maxProjects = $plan->features['max_projects'] ?? 0;
        
        // null = безлимит
        if ($maxProjects === null) {
            return null;
        }
        
        return max(0, $maxProjects - $this->projects()->count());
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'subscription_expires_at' => 'datetime',
        'passport_issued_date' => 'date',
    ];
}
