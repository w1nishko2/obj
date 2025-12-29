<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromocodeUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'promocode_id',
        'user_id',
        'payment_id',
        'original_amount',
        'discount_amount',
        'final_amount',
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    /**
     * Связь с промокодом
     */
    public function promocode()
    {
        return $this->belongsTo(Promocode::class);
    }

    /**
     * Связь с пользователем
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь с платежом
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
