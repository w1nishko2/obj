<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StageMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id',
        'user_id',
        'name',
        'description',
        'unit',
        'quantity',
        'price_per_unit',
        'total_cost',
        'markup_percent',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'markup_percent' => 'decimal:2',
    ];

    public function stage(): BelongsTo
    {
        return $this->belongsTo(ProjectStage::class, 'stage_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить стоимость с учетом наценки
     */
    public function getFinalCostAttribute()
    {
        $baseCost = $this->total_cost ?? 0;
        
        // Приоритет: наценка материала, затем наценка проекта
        $markup = $this->markup_percent ?? $this->stage->project->markup_percent ?? 0;
        
        return $baseCost * (1 + $markup / 100);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($material) {
            $material->total_cost = $material->quantity * $material->price_per_unit;
        });
    }
}
