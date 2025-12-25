<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectUserRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'role',
        'can_edit_project',
        'can_manage_team',
        'can_create_stages',
        'can_edit_tasks',
        'can_view_costs',
        'can_upload_documents',
        'can_generate_reports',
    ];

    protected $casts = [
        'can_edit_project' => 'boolean',
        'can_manage_team' => 'boolean',
        'can_create_stages' => 'boolean',
        'can_edit_tasks' => 'boolean',
        'can_view_costs' => 'boolean',
        'can_upload_documents' => 'boolean',
        'can_generate_reports' => 'boolean',
    ];

    // Связи
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Предустановленные роли с правами
    public static function getDefaultPermissions($role)
    {
        return match($role) {
            'owner' => [
                'can_edit_project' => true,
                'can_manage_team' => true,
                'can_create_stages' => true,
                'can_edit_tasks' => true,
                'can_view_costs' => true,
                'can_upload_documents' => true,
                'can_generate_reports' => true,
            ],
            'client' => [
                'can_edit_project' => false,
                'can_manage_team' => false,
                'can_create_stages' => false,
                'can_edit_tasks' => false,
                'can_view_costs' => true,
                'can_upload_documents' => false,
                'can_generate_reports' => false,
            ],
            'executor' => [
                'can_edit_project' => false,
                'can_manage_team' => false,
                'can_create_stages' => false,
                'can_edit_tasks' => true,
                'can_view_costs' => false,
                'can_upload_documents' => true,
                'can_generate_reports' => false,
            ],
            default => []
        };
    }

    // Проверка конкретного права
    public function hasPermission($permission)
    {
        return $this->$permission ?? false;
    }
}
