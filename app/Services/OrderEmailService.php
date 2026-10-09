<?php

namespace App\Services;

use App\Jobs\SendOrderEmailNotification;
use App\Models\Order;
use App\Models\OrderEmailNotification;

class OrderEmailService
{
    public function record(Order $order): void
    {
        OrderEmailNotification::firstOrCreate(
            [
                'order_id' => $order->id,
                'type' => 'customer',
            ],
            [
                'recipient_email' => $order->email,
                'status' => 'pending',
            ]
        );

        $adminEmail = config('mail.admin_address');

        if ($adminEmail) {
            OrderEmailNotification::firstOrCreate(
                [
                    'order_id' => $order->id,
                    'type' => 'admin',
                ],
                [
                    'recipient_email' => $adminEmail,
                    'status' => 'pending',
                ]
            );
        }
    }

    public function dispatch(Order $order): void
    {
        OrderEmailNotification::query()
            ->where('order_id', $order->id)
            ->where('status', 'pending')
            ->pluck('id')
            ->each(function ($id) {
                SendOrderEmailNotification::dispatch(
                    (int) $id
                )->afterCommit();
            });
    }
}