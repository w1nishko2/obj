<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTemplateType extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Получить пользователя, которому принадлежит шаблон
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить этапы типа работы
     */
    public function stages()
    {
        return $this->hasMany(WorkTemplateStage::class)->orderBy('order');
    }

    /**
     * Получить все задачи через этапы
     */
    public function tasks()
    {
        return $this->hasManyThrough(WorkTemplateTask::class, WorkTemplateStage::class);
    }

    /**
     * Scope для получения только активных шаблонов
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope для получения шаблонов текущего пользователя
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
