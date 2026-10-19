<?php

namespace App\Jobs;

use App\Mail\AdminNewOrderNotification;
use App\Mail\CustomerOrderConfirmation;
use App\Models\OrderEmailNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\CustomerOrderUpdate;

use Throwable;

class SendOrderEmailNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;
    public int $timeout = 60;

    public function __construct(
        public int $notificationId
    ) {
        $this->onQueue('emails');
    }

    public function handle(): void
    {
        $token = (string) Str::uuid();

        /*
        |--------------------------------------------------------------------------
        | CLAIM ONE DELIVERY ATTEMPT
        |--------------------------------------------------------------------------
        */

        $claimed = DB::transaction(function () use ($token) {
            $notification = OrderEmailNotification::query()
                ->whereKey($this->notificationId)
                ->lockForUpdate()
                ->first();

            if (!$notification) {
                return false;
            }

            if ($notification->sent_at !== null) {
                return false;
            }

            if ($notification->send_started_at !== null) {
    return false;
}

            if (!in_array(
                $notification->status,
                ['pending', 'failed'],
                true
            )) {
                return false;
            }

            if ($notification->attempts >= 5) {
                return false;
            }

            if (
                $notification->next_attempt_at &&
                $notification->next_attempt_at->isFuture()
            ) {
                return false;
            }

            $notification->update([
                'status' => 'processing',
                'attempts' => $notification->attempts + 1,
                'processing_token' => $token,
                'processing_started_at' => now(),
                'send_started_at' => null,
                'next_attempt_at' => null,
                'last_error' => null,
            ]);

            return true;
        }, 3);

        if (!$claimed) {
            return;
        }

        $sendStarted = false;

        try {
            /*
            |--------------------------------------------------------------------------
            | PREPARE EMAIL
            |--------------------------------------------------------------------------
            */

            $notification = OrderEmailNotification::query()
                ->with('order.items')
                ->findOrFail($this->notificationId);

            if (
                $notification->status !== 'processing' ||
                $notification->processing_token !== $token
            ) {
                return;
            }

            $order = $notification->order;

            if (!$order) {
                throw new \RuntimeException('Order not found.');
            }

            if ($notification->type === 'customer') {

    $mailable = new CustomerOrderConfirmation($order);

} elseif ($notification->type === 'admin') {

    $mailable = new AdminNewOrderNotification($order);

} elseif (in_array($notification->type, [
    'order_confirmed',
    'order_shipped',
    'order_delivered',
    'order_cancelled',
    'payment_received',
], true)) {

    $mailable = new CustomerOrderUpdate(
        $order,
        $notification->type
    );

} else {

    throw new \RuntimeException(
        'Unknown notification type: ' . $notification->type
    );

}

            /*
            |--------------------------------------------------------------------------
            | AUTHORIZE SEND ATTEMPT
            |--------------------------------------------------------------------------
            |
            | Only the current attempt may mark the send as started.
            | This check cannot prevent a recovery occurring after
            | authorization but before the external send completes.
            |
            */

            $authorized = OrderEmailNotification::query()
                ->whereKey($this->notificationId)
                ->where('status', 'processing')
                ->where('processing_token', $token)
                ->whereNull('sent_at')
                ->whereNull('send_started_at')
                ->update([
                    'send_started_at' => now(),
                ]);

            if ($authorized !== 1) {
                return;
            }

            $sendStarted = true;

            /*
            |--------------------------------------------------------------------------
            | EXTERNAL MAIL TRANSPORT
            |--------------------------------------------------------------------------
            */

            Mail::to($notification->recipient_email)
                ->send($mailable);

            /*
            |--------------------------------------------------------------------------
            | MARK SENT ONLY IF THIS ATTEMPT STILL OWNS RECORD
            |--------------------------------------------------------------------------
            */

            $updated = OrderEmailNotification::query()
                ->whereKey($this->notificationId)
                ->where('status', 'processing')
                ->where('processing_token', $token)
                ->whereNull('sent_at')
                ->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    'processing_started_at' => null,
                    'processing_token' => null,
                    'next_attempt_at' => null,
                    'last_error' => null,
                ]);

            if ($updated !== 1) {
                report(new \RuntimeException(
                    'Email transport returned successfully, but attempt ownership changed for notification '
                    . $this->notificationId
                ));
            }

        } catch (Throwable $exception) {
            report($exception);

            /*
            |--------------------------------------------------------------------------
            | CLASSIFY FAILURE
            |--------------------------------------------------------------------------
            |
            | Before send started: retryable preparation failure.
            | After send started: delivery outcome is uncertain.
            |
            */

            $notification = OrderEmailNotification::query()
                ->whereKey($this->notificationId)
                ->where('status', 'processing')
                ->where('processing_token', $token)
                ->first();

            if (!$notification) {
                return;
            }

            $attempts = $notification->attempts;

            $uncertain = $sendStarted
                || $notification->send_started_at !== null;

            $notification->update([
                'status' => $uncertain
                    ? 'delivery_uncertain'
                    : 'failed',

                'processing_started_at' => null,
                'processing_token' => null,

                'next_attempt_at' => !$uncertain && $attempts < 5
                    ? now()->addMinutes(
                        min(60, 2 ** $attempts)
                    )
                    : null,

                'last_error' => mb_substr(
                    $exception->getMessage(),
                    0,
                    2000
                ),
            ]);
        }
    }
}
