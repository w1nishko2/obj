<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTemplateTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_template_stage_id',
        'name',
        'description',
        'price',
        'order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Получить этап, к которому относится задача
     */
    public function stage()
    {
        return $this->belongsTo(WorkTemplateStage::class, 'work_template_stage_id');
    }
}
