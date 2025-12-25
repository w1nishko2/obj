<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PushSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'endpoint',
        'public_key',
        'auth_token',
        'content_encoding',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Связь с пользователем
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить подписку в формате для Web Push
     */
    public function getSubscriptionArray(): array
    {
        return [
            'endpoint' => $this->endpoint,
            'keys' => [
                'p256dh' => $this->public_key,
                'auth' => $this->auth_token,
            ],
            'contentEncoding' => $this->content_encoding ?? 'aesgcm',
        ];
    }

    /**
     * Найти или создать подписку
     */
    public static function updateOrCreateSubscription(?int $userId, array $subscription): self
    {
        return self::updateOrCreate(
            ['endpoint' => $subscription['endpoint']],
            [
                'user_id' => $userId,
                'public_key' => $subscription['keys']['p256dh'] ?? null,
                'auth_token' => $subscription['keys']['auth'] ?? null,
                'content_encoding' => $subscription['contentEncoding'] ?? 'aesgcm',
            ]
        );
    }
}
