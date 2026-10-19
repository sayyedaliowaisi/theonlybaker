<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendOrderEmailNotification;
use App\Models\OrderEmailNotification;
use App\Models\OrderEmailRecoveryLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class EmailNotificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SUPPORTED NOTIFICATION TYPES
    |--------------------------------------------------------------------------
    */

    private const NOTIFICATION_TYPES = [
        'customer',
        'admin',
        'order_confirmed',
        'order_shipped',
        'order_delivered',
        'order_cancelled',
        'payment_received',
    ];

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATION LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:150',
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'pending',
                    'processing',
                    'sent',
                    'failed',
                    'delivery_uncertain',
                ]),
            ],

            'type' => [
                'nullable',
                Rule::in(self::NOTIFICATION_TYPES),
            ],
        ]);

        $query = OrderEmailNotification::query()
            ->with('order:id,order_number,name,email')
            ->latest();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['search'])) {
            $search = trim($validated['search']);

            $query->where(function ($q) use ($search) {
                $q->where(
                    'recipient_email',
                    'like',
                    "%{$search}%"
                )->orWhereHas(
                    'order',
                    function ($orderQuery) use ($search) {
                        $orderQuery
                            ->where(
                                'order_number',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'email',
                                'like',
                                "%{$search}%"
                            );
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['status'])) {
            $query->where(
                'status',
                $validated['status']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TYPE FILTER
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['type'])) {
            $query->where(
                'type',
                $validated['type']
            );
        }

        $notifications = $query
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD COUNTS
        |--------------------------------------------------------------------------
        */

        $stats = [];

        foreach ([
            'pending',
            'processing',
            'sent',
            'failed',
            'delivery_uncertain',
        ] as $status) {
            $stats[$status] = OrderEmailNotification::query()
                ->where('status', $status)
                ->count();
        }

        $stats['total'] = OrderEmailNotification::count();

        /*
        |--------------------------------------------------------------------------
        | STUCK PROCESSING COUNT
        |--------------------------------------------------------------------------
        */

        $stuckCount = OrderEmailNotification::query()
            ->where('status', 'processing')
            ->whereNull('sent_at')
            ->whereNotNull('processing_started_at')
            ->where(
                'processing_started_at',
                '<=',
                now()->subMinutes(5)
            )
            ->count();

        return view(
            'admin.email-notifications.index',
            compact(
                'notifications',
                'stats',
                'stuckCount'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATION DETAILS
    |--------------------------------------------------------------------------
    */

    public function show(
        OrderEmailNotification $notification
    ) {
        $notification->load([
            'order.items',
            'recoveryLogs',
        ]);

        return view(
            'admin.email-notifications.show',
            compact('notification')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RETRY FAILED NOTIFICATION
    |--------------------------------------------------------------------------
    */

    public function retry(
        Request $request,
        OrderEmailNotification $notification
    ): RedirectResponse {
        return $this->recover(
            $request,
            $notification,
            'failed'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RECOVER STUCK PROCESSING NOTIFICATION
    |--------------------------------------------------------------------------
    */

    public function recoverProcessing(
        Request $request,
        OrderEmailNotification $notification
    ): RedirectResponse {
        return $this->recover(
            $request,
            $notification,
            'processing'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RECOVER UNCERTAIN DELIVERY
    |--------------------------------------------------------------------------
    */

    public function recoverUncertain(
        Request $request,
        OrderEmailNotification $notification
    ): RedirectResponse {
        return $this->recover(
            $request,
            $notification,
            'delivery_uncertain'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SECURE RECOVERY
    |--------------------------------------------------------------------------
    |
    | Recovery requires:
    |
    | 1. A documented reason.
    | 2. Confirmation of non-delivery.
    | 3. Confirmation that the worker has been stopped.
    | 4. A matching notification status.
    | 5. A notification that has not already been sent.
    |
    | Note: worker_stopped is an administrator attestation,
    | not an automated check of running worker processes.
    |
    */

    private function recover(
        Request $request,
        OrderEmailNotification $notification,
        string $expectedStatus
    ): RedirectResponse {
        $validated = $request->validate([
            'reason' => [
                'required',
                'string',
                'min:10',
                'max:2000',
            ],

            'delivery_verification' => [
                'required',
                Rule::in([
                    'confirmed_not_delivered',
                    'delivery_uncertain',
                ]),
            ],

            'worker_stopped' => [
                'required',
                'accepted',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | BLOCK UNVERIFIED DELIVERY
        |--------------------------------------------------------------------------
        */

        if (
            $validated['delivery_verification']
            !== 'confirmed_not_delivered'
        ) {
            throw ValidationException::withMessages([
                'delivery_verification' =>
                    'Delivery is uncertain. Verify non-delivery before resending.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ATOMIC RECOVERY AND AUDIT LOG
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $notification,
            $expectedStatus,
            $validated
        ) {
            $locked = OrderEmailNotification::query()
                ->whereKey($notification->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | CHECK CURRENT STATE
            |--------------------------------------------------------------------------
            */

            if (
                $locked->status !== $expectedStatus
                || $locked->sent_at !== null
            ) {
                throw ValidationException::withMessages([
                    'notification' =>
                        'Notification status changed. Refresh and investigate again.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | CHECK STUCK PROCESSING ELIGIBILITY
            |--------------------------------------------------------------------------
            */

            if (
                $expectedStatus === 'processing'
                && !$locked->isStuck()
            ) {
                throw ValidationException::withMessages([
                    'notification' =>
                        'This processing notification is not yet eligible for recovery.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | RESET NOTIFICATION FOR A NEW ATTEMPT
            |--------------------------------------------------------------------------
            */

            $locked->update([
                'status' => 'pending',
                'attempts' => 0,
                'next_attempt_at' => null,
                'processing_started_at' => null,
                'processing_token' => null,
                'send_started_at' => null,
                'last_error' => null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | RECORD ADMIN RECOVERY ACTION
            |--------------------------------------------------------------------------
            */

            OrderEmailRecoveryLog::create([
                'notification_id' => $locked->id,

                'admin_id' => auth('admin')->id(),

                'action' => match ($expectedStatus) {
                    'processing' => 'recover_processing',

                    'delivery_uncertain' => 'recover_uncertain',

                    default => 'retry_failed',
                },

                'previous_status' => $expectedStatus,

                'new_status' => 'pending',

                'reason' => $validated['reason'],

                'delivery_verification' =>
                    $validated['delivery_verification'],

                'created_at' => now(),
            ]);
        }, 3);

        /*
        |--------------------------------------------------------------------------
        | DISPATCH AFTER SUCCESSFUL RECOVERY
        |--------------------------------------------------------------------------
        */

        try {
            SendOrderEmailNotification::dispatch(
                (int) $notification->id
            )->afterCommit();

            $message = 'Recovery recorded and email queued.';
        } catch (Throwable $exception) {
            report($exception);

            $message = 'Recovery recorded. Notification is pending and requires dispatch.';
        }

        return redirect()
            ->route(
                'admin.email-notifications.show',
                $notification
            )
            ->with('success', $message);
    }
}
