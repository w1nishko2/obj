<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'started_at',
        'expires_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Связь с пользователем
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь с тарифом
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Связь с платежами
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Является ли подписка активной
     */
    public function isActive()
    {
        if ($this->status !== 'active') {
            return false;
        }

        // Если нет даты истечения (бессрочная), значит активна
        if (!$this->expires_at) {
            return true;
        }

        // Проверяем, не истекла ли подписка
        return $this->expires_at->isFuture();
    }

    /**
     * Активировать подписку
     * 
     * @param \Carbon\Carbon|null $startDate Дата начала подписки (по умолчанию - текущее время)
     */
    public function activate($startDate = null)
    {
        $this->status = 'active';
        $this->started_at = $startDate ?? now();
        
        // Если у тарифа есть длительность, устанавливаем дату истечения
        if ($this->plan->duration_days) {
            $this->expires_at = $this->started_at->copy()->addDays($this->plan->duration_days);
        } else {
            $this->expires_at = null; // Бессрочная подписка
        }
        
        $this->save();

        // Обновляем данные пользователя
        $this->user->subscription_type = $this->plan->slug;
        $this->user->subscription_expires_at = $this->expires_at;
        $this->user->save();
    }

    /**
     * Отменить подписку
     */
    public function cancel()
    {
        $this->status = 'cancelled';
        $this->save();
    }

    /**
     * Проверить истечение подписки
     */
    public function checkExpiration()
    {
        if ($this->expires_at && $this->expires_at->isPast() && $this->status === 'active') {
            $this->status = 'expired';
            $this->save();

            // Сброс подписки пользователя - убираем подписку полностью
            $this->user->subscription_type = null;
            $this->user->subscription_expires_at = null;
            
            // ВАЖНО: Снимаем статус прораба, возвращаем в клиенты
            if ($this->user->account_type === 'foreman') {
                $this->user->account_type = 'client';
            }
            
            $this->user->save();
        }
    }
}
