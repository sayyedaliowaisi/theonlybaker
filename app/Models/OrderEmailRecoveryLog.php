<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderEmailRecoveryLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'notification_id',
        'admin_id',
        'action',
        'previous_status',
        'new_status',
        'reason',
        'delivery_verification',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function notification(): BelongsTo
    {
        return $this->belongsTo(
            OrderEmailNotification::class,
            'notification_id'
        );
    }
}