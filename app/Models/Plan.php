<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'duration_days',
        'is_active',
        'features',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'features' => 'array',
    ];

    /**
     * Связь с подписками
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Связь с платежами
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Является ли тариф бесплатным
     */
    public function isFree()
    {
        return $this->price == 0 || $this->slug === 'free';
    }

    /**
     * Получить тариф по slug
     */
    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->where('is_active', true)->first();
    }
}
