<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderStatusService
{
    private const NEXT = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['processing', 'cancelled'],
        'processing' => ['shipped', 'cancelled'],
        'shipped' => ['delivered'],
        'delivered' => [],
        'cancelled' => [],
    ];

    public function change(
        Order $order,
        string $newStatus,
        ?int $actorId = null,
        ?string $note = null
    ): Order {
        if (!array_key_exists($newStatus, self::NEXT)) {
            throw ValidationException::withMessages([
                'order_status' => 'Invalid order status.',
            ]);
        }

        return DB::transaction(function () use (
            $order,
            $newStatus,
            $actorId,
            $note
        ) {
            $locked = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($locked->order_status === $newStatus) {
                return $locked;
            }

            $allowed = self::NEXT[$locked->order_status] ?? [];

            if (!in_array($newStatus, $allowed, true)) {
                throw ValidationException::withMessages([
                    'order_status' => 'This status transition is not allowed.',
                ]);
            }

            if ($newStatus === 'cancelled') {
                throw ValidationException::withMessages([
                    'order_status' =>
                        'Use the inventory-safe cancellation handler.',
                ]);
            }

            $locked->update([
                'order_status' => $newStatus,
            ]);

            $locked->statusHistory()->create([
                'status' => $newStatus,
                'note' => $note,
                'changed_by' => $actorId,
            ]);

            return $locked;
        }, 3);
    }
}
