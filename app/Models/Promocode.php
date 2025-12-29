<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promocode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_percent',
        'is_active',
        'usage_limit',
        'usage_count',
        'expires_at',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Связь с использованиями промокода
     */
    public function usages()
    {
        return $this->hasMany(PromocodeUsage::class);
    }

    /**
     * Проверка, действителен ли промокод
     */
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Применить промокод
     */
    public function apply($amount)
    {
        $discountAmount = ($amount * $this->discount_percent) / 100;
        $finalAmount = $amount - $discountAmount;

        return [
            'original_amount' => $amount,
            'discount_amount' => $discountAmount,
            'final_amount' => max(0, $finalAmount),
            'discount_percent' => $this->discount_percent,
        ];
    }

    /**
     * Увеличить счетчик использований
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    /**
     * Статистика по промокоду
     */
    public function getStats()
    {
        return [
            'total_usages' => $this->usages()->count(),
            'total_revenue' => $this->usages()->sum('final_amount'),
            'total_discount' => $this->usages()->sum('discount_amount'),
            'unique_users' => $this->usages()->distinct('user_id')->count('user_id'),
        ];
    }
}
