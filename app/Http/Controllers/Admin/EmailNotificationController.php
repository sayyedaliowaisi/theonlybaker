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

class EmailNotificationController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
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
                Rule::in(['customer', 'admin']),
            ],
        ]);

        $query = OrderEmailNotification::query()
            ->with('order:id,order_number,name,email')
            ->latest();

        if (!empty($validated['search'])) {
            $search = trim($validated['search']);

            $query->where(function ($q) use ($search) {
                $q->where(
                    'recipient_email',
                    'like',
                    "%{$search}%"
                )->orWhereHas('order', function ($orderQuery) use ($search) {
                    $orderQuery
                        ->where('order_number', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['type'])) {
            $query->where('type', $validated['type']);
        }

        $notifications = $query
            ->paginate(15)
            ->withQueryString();

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

        $stuckCount = OrderEmailNotification::query()
            ->where('status', 'processing')
            ->whereNull('sent_at')
            ->where(
                'processing_started_at',
                '<=',
                now()->subMinutes(5)
            )
            ->count();

        return view(
            'admin.email-notifications.index',
            compact('notifications', 'stats', 'stuckCount')
        );
    }

    public function show(OrderEmailNotification $notification)
    {
        $notification->load([
            'order.items',
            'recoveryLogs',
        ]);

        return view(
            'admin.email-notifications.show',
            compact('notification')
        );
    }

    public function retry(
        Request $request,
        OrderEmailNotification $notification
    ): RedirectResponse {
        return $this->recover($request, $notification, 'failed');
    }

    public function recoverProcessing(
        Request $request,
        OrderEmailNotification $notification
    ): RedirectResponse {
        return $this->recover($request, $notification, 'processing');
    }

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

        if (
            $validated['delivery_verification']
            !== 'confirmed_not_delivered'
        ) {
            throw ValidationException::withMessages([
                'delivery_verification' =>
                    'Delivery is uncertain. Verify non-delivery before resending.',
            ]);
        }

        DB::transaction(function () use (
            $notification,
            $expectedStatus,
            $validated
        ) {
            $locked = OrderEmailNotification::query()
                ->whereKey($notification->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $locked->status !== $expectedStatus ||
                $locked->sent_at !== null
            ) {
                throw ValidationException::withMessages([
                    'notification' =>
                        'Notification status changed. Refresh and investigate again.',
                ]);
            }

            if (
                $expectedStatus === 'processing'
                && !$locked->isStuck()
            ) {
                throw ValidationException::withMessages([
                    'notification' =>
                        'This processing notification is not yet eligible for recovery.',
                ]);
            }

            $locked->update([
                'status' => 'pending',
                'attempts' => 0,
                'next_attempt_at' => null,
                'processing_started_at' => null,
                'processing_token' => null,
                'send_started_at' => null,
                'last_error' => null,
            ]);

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

        try {
            SendOrderEmailNotification::dispatch(
                $notification->id
            )->afterCommit();

            $message = 'Recovery recorded and email queued.';
        } catch (\Throwable $exception) {
            report($exception);

            $message = 'Recovery recorded. Scheduler will queue the pending email.';
        }

        return redirect()
            ->route(
                'admin.email-notifications.show',
                $notification
            )
            ->with('success', $message);
    }
}
