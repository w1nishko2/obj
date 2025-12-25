<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StageTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id',
        'created_by',
        'assigned_to',
        'name',
        'description',
        'status',
        'order',
        'cost',
        'markup_percent',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'markup_percent' => 'decimal:2',
    ];

    /**
     * Получить стоимость с учетом наценки
     */
    public function getFinalCostAttribute()
    {
        $baseCost = $this->cost ?? 0;
        
        // Приоритет: наценка задачи, затем наценка проекта
        $markup = $this->markup_percent ?? $this->stage->project->markup_percent ?? 0;
        
        return $baseCost * (1 + $markup / 100);
    }

    public function stage()
    {
        return $this->belongsTo(ProjectStage::class, 'stage_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class, 'task_id')->orderBy('created_at', 'desc');
    }

    public function photos()
    {
        return $this->hasMany(TaskPhoto::class, 'task_id')->orderBy('created_at', 'desc');
    }
}
