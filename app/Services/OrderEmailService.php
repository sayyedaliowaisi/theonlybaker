<?php

namespace App\Services;

use App\Jobs\SendOrderEmailNotification;
use App\Models\Order;
use App\Models\OrderEmailNotification;
use InvalidArgumentException;

class OrderEmailService
{
    private const CUSTOMER_UPDATE_TYPES = [
        'order_confirmed',
        'order_shipped',
        'order_delivered',
        'order_cancelled',
        'payment_received',
    ];

    /*
    |--------------------------------------------------------------------------
    | RECORD NEW ORDER EMAILS
    |--------------------------------------------------------------------------
    */

    public function record(
        Order $order
    ): void {
        OrderEmailNotification::firstOrCreate(
            [
                'order_id' => $order->id,
                'type' => 'customer',
            ],
            [
                'recipient_email' => $order->email,
                'status' => 'pending',
                'attempts' => 0,
            ]
        );

        $adminEmail = config(
            'mail.admin_address'
        );

        if ($adminEmail) {
            OrderEmailNotification::firstOrCreate(
                [
                    'order_id' => $order->id,
                    'type' => 'admin',
                ],
                [
                    'recipient_email' => $adminEmail,
                    'status' => 'pending',
                    'attempts' => 0,
                ]
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DISPATCH NEW ORDER EMAILS
    |--------------------------------------------------------------------------
    */

    public function dispatch(
        Order $order
    ): void {
        OrderEmailNotification::query()
            ->where('order_id', $order->id)
            ->whereIn(
                'type',
                ['customer', 'admin']
            )
            ->where('status', 'pending')
            ->whereNull('sent_at')
            ->whereNull('send_started_at')
            ->pluck('id')
            ->each(function ($id) {
                SendOrderEmailNotification::dispatch(
                    (int) $id
                )->afterCommit();
            });
    }

    /*
    |--------------------------------------------------------------------------
    | RECORD CUSTOMER STATUS UPDATE
    |--------------------------------------------------------------------------
    */

    public function recordCustomerUpdate(
        Order $order,
        string $type
    ): void {
        $this->validateCustomerUpdateType(
            $type
        );

        OrderEmailNotification::firstOrCreate(
            [
                'order_id' => $order->id,
                'type' => $type,
            ],
            [
                'recipient_email' => $order->email,
                'status' => 'pending',
                'attempts' => 0,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DISPATCH CUSTOMER STATUS UPDATE
    |--------------------------------------------------------------------------
    */

    public function dispatchCustomerUpdate(
        Order $order,
        string $type
    ): void {
        $this->validateCustomerUpdateType(
            $type
        );

        $notification = OrderEmailNotification::query()
            ->where('order_id', $order->id)
            ->where('type', $type)
            ->where('status', 'pending')
            ->whereNull('sent_at')
            ->whereNull('send_started_at')
            ->first();

        if (!$notification) {
            return;
        }

        SendOrderEmailNotification::dispatch(
            (int) $notification->id
        )->afterCommit();
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATE CUSTOMER UPDATE TYPE
    |--------------------------------------------------------------------------
    */

    private function validateCustomerUpdateType(
        string $type
    ): void {
        if (!in_array(
            $type,
            self::CUSTOMER_UPDATE_TYPES,
            true
        )) {
            throw new InvalidArgumentException(
                'Unsupported order notification type: '
                . $type
            );
        }
    }
}
