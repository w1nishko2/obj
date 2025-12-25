<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'start_date',
        'end_date',
        'status',
        'order',
        'budget',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(StageTask::class, 'stage_id')
            ->orderByRaw("CASE 
                WHEN status = 'В работе' THEN 1 
                WHEN status = 'Не начат' THEN 2 
                WHEN status = 'Готово' THEN 3 
                ELSE 4 
            END")
            ->orderBy('order');
    }

    public function materials()
    {
        return $this->hasMany(StageMaterial::class, 'stage_id');
    }

    public function deliveries()
    {
        return $this->hasMany(StageDelivery::class, 'stage_id');
    }

    // Сумма затрат на задачи (с учетом наценки)
    public function getTasksCostAttribute()
    {
        return $this->tasks->sum(function ($task) {
            return $task->final_cost;
        });
    }

    // Сумма затрат на материалы (с учетом наценки)
    public function getMaterialsCostAttribute()
    {
        return $this->materials->sum(function ($material) {
            return $material->final_cost;
        });
    }

    // Сумма затрат на доставки (с учетом наценки)
    public function getDeliveriesCostAttribute()
    {
        return $this->deliveries->sum(function ($delivery) {
            return $delivery->final_cost;
        });
    }

    // Общая сумма затрат на этап (с учетом наценки)
    public function getTotalCostAttribute()
    {
        return $this->tasks_cost + $this->materials_cost + $this->deliveries_cost;
    }
}
