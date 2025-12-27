<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTemplateStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_template_type_id',
        'name',
        'description',
        'duration_days',
        'order',
    ];

    protected $casts = [
        'duration_days' => 'integer',
    ];

    /**
     * Получить тип работы, к которому относится этап
     */
    public function templateType()
    {
        return $this->belongsTo(WorkTemplateType::class, 'work_template_type_id');
    }

    /**
     * Получить задачи этапа
     */
    public function tasks()
    {
        return $this->hasMany(WorkTemplateTask::class)->orderBy('order');
    }

    /**
     * Получить общую стоимость этапа
     */
    public function getTotalPriceAttribute()
    {
        return $this->tasks()->sum('price');
    }
}
