<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'work_type',
        'status',
        'is_archived',
        'markup_percent',
        // Данные клиента
        'client_full_name',
        'client_phone',
        'client_email',
        'client_address',
        'client_passport_series',
        'client_passport_number',
        'client_passport_issued_by',
        'client_passport_issued_date',
        'client_inn',
        'client_organization_name',
        // Данные прораба
        'foreman_full_name',
        'foreman_phone',
        'foreman_email',
        'foreman_address',
        'foreman_passport_series',
        'foreman_passport_number',
        'foreman_passport_issued_by',
        'foreman_passport_issued_date',
        'foreman_inn',
        'foreman_organization_name',
    ];

    protected $casts = [
        // 'is_archived' => 'boolean', // Убрали cast, чтобы работать с числовыми значениями 0/1
        'client_passport_issued_date' => 'date',
        'foreman_passport_issued_date' => 'date',
        'markup_percent' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stages()
    {
        return $this->hasMany(ProjectStage::class)
            ->orderByRaw("CASE 
                WHEN status = 'В работе' THEN 1 
                WHEN status = 'Не начат' THEN 2 
                WHEN status = 'Готово' THEN 3 
                ELSE 4 
            END")
            ->orderBy('order');
    }

    public function tasks()
    {
        return $this->hasManyThrough(StageTask::class, ProjectStage::class, 'project_id', 'stage_id');
    }

    public function participants()
    {
        return $this->hasMany(ProjectParticipant::class);
    }

    // Новая связь с ролями участников
    public function userRoles()
    {
        return $this->hasMany(ProjectUserRole::class);
    }

    // Получить всех пользователей с ролями через новую таблицу
    public function usersWithRoles()
    {
        return $this->belongsToMany(
            User::class,
            'project_user_roles',
            'project_id',
            'user_id'
        )->withPivot(['role', 'can_edit_project', 'can_manage_team', 'can_create_stages', 'can_edit_tasks', 'can_view_costs', 'can_upload_documents', 'can_generate_reports'])
         ->withTimestamps();
    }

    public function documents()
    {
        return $this->hasMany(ProjectDocument::class)->orderBy('created_at', 'desc');
    }

    public function getProgressAttribute()
    {
        $totalStages = $this->stages()->count();
        if ($totalStages === 0) {
            return 0;
        }
        $completedStages = $this->stages()->where('status', 'Готово')->count();
        return round(($completedStages / $totalStages) * 100);
    }

    public function getNearestDeadlineAttribute()
    {
        return $this->stages()
            ->whereIn('status', ['Не начат', 'В работе'])
            ->orderBy('end_date')
            ->first()?->end_date;
    }

    // Общая сумма затрат на проект
    public function getTotalCostAttribute()
    {
        return $this->stages->sum(function ($stage) {
            return $stage->total_cost;
        });
    }
}
