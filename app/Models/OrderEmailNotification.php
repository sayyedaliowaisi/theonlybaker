<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderEmailNotification extends Model
{
    protected $fillable = [
        'order_id',
        'type',
        'recipient_email',
        'status',
        'attempts',
        'next_attempt_at',
        'processing_started_at',
        'processing_token',
        'send_started_at',
        'sent_at',
        'last_error',
    ];

    protected function casts(): array
    {
        return [
            'attempts' => 'integer',
            'next_attempt_at' => 'datetime',
            'processing_started_at' => 'datetime',
            'send_started_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function recoveryLogs(): HasMany
    {
        return $this->hasMany(
            OrderEmailRecoveryLog::class,
            'notification_id'
        )->latest('id');
    }

    public function isStuck(): bool
    {
        return $this->status === 'processing'
            && $this->sent_at === null
            && $this->processing_started_at !== null
            && $this->processing_started_at->lte(
                now()->subMinutes(5)
            );
    }
}
