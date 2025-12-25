<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'subscription_id',
        'yookassa_payment_id',
        'status',
        'amount',
        'currency',
        'description',
        'metadata',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'paid_at' => 'datetime',
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
     * Связь с подпиской
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Успешен ли платеж
     */
    public function isSucceeded()
    {
        return $this->status === 'succeeded';
    }

    /**
     * Ожидает ли платеж оплаты
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Отметить платеж как успешный
     */
    public function markAsSucceeded()
    {
        $this->status = 'succeeded';
        $this->paid_at = now();
        $this->save();
    }

    /**
     * Отметить платеж как отмененный
     */
    public function markAsCancelled()
    {
        $this->status = 'cancelled';
        $this->save();
    }

    /**
     * Отметить платеж как неудачный
     */
    public function markAsFailed()
    {
        $this->status = 'failed';
        $this->save();
    }
}
