<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductInventory;
use App\Services\OrderEmailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ORDERS LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Order::query()
            ->withCount('items')
            ->latest();

        /*
        | SEARCH
        */

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        /*
        | STATUS FILTER
        */

        if ($request->filled('status')) {
            $allowedStatuses = [
                'pending',
                'confirmed',
                'processing',
                'shipped',
                'delivered',
                'cancelled',
            ];

            $status = $request->input('status');

            if (in_array($status, $allowedStatuses, true)) {
                $query->where('order_status', $status);
            }
        }

        $orders = $query
            ->paginate(15)
            ->withQueryString();

        /*
        | DASHBOARD COUNTS
        */

        $stats = [
            'total' => Order::count(),

            'pending' => Order::where(
                'order_status',
                'pending'
            )->count(),

            'confirmed' => Order::where(
                'order_status',
                'confirmed'
            )->count(),

            'processing' => Order::where(
                'order_status',
                'processing'
            )->count(),

            'shipped' => Order::where(
                'order_status',
                'shipped'
            )->count(),

            'delivered' => Order::where(
                'order_status',
                'delivered'
            )->count(),

            'cancelled' => Order::where(
                'order_status',
                'cancelled'
            )->count(),
        ];

        return view(
            'admin.orders.index',
            compact('orders', 'stats')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ORDER DETAILS
    |--------------------------------------------------------------------------
    */

    public function show(Order $order)
    {
        $order->load([
            'items',
            'statusHistory',
        ]);

        $allowedTransitions = $this->allowedTransitions(
            $order->order_status
        );

        return view(
            'admin.orders.show',
            compact('order', 'allowedTransitions')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ALLOWED STATUS TRANSITIONS
    |--------------------------------------------------------------------------
    */

    private function allowedTransitions(string $status): array
    {
        $transitions = [
            'pending' => [
                'confirmed',
                'cancelled',
            ],

            'confirmed' => [
                'processing',
                'cancelled',
            ],

            'processing' => [
                'shipped',
                'cancelled',
            ],

            'shipped' => [
                'delivered',
            ],

            'delivered' => [],

            'cancelled' => [],
        ];

        return $transitions[$status] ?? [];
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE ORDER STATUS
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        Request $request,
        Order $order
    ): RedirectResponse {
        $validated = $request->validate([
            'order_status' => [
                'required',
                Rule::in([
                    'pending',
                    'confirmed',
                    'processing',
                    'shipped',
                    'delivered',
                    'cancelled',
                ]),
            ],

            'note' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $newStatus = $validated['order_status'];

        $note = $validated['note'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | DATABASE TRANSACTION
        |--------------------------------------------------------------------------
        */

        $result = DB::transaction(function () use (
            $order,
            $newStatus,
            $note
        ) {
            /*
            | LOCK ORDER
            */

            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            $oldStatus = $lockedOrder->order_status;

            /*
            | NO STATUS CHANGE
            */

            if ($oldStatus === $newStatus) {
                return [
                    'message' => 'Order already has this status.',
                    'notification_type' => null,
                ];
            }

            /*
            | VALIDATE STATUS TRANSITION
            */

            $allowedNextStatuses = $this->allowedTransitions(
                $oldStatus
            );

            if (!in_array(
                $newStatus,
                $allowedNextStatuses,
                true
            )) {
                throw ValidationException::withMessages([
                    'order_status' =>
                        'This order status change is not allowed.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | CANCEL ORDER
            |--------------------------------------------------------------------------
            */

            if ($newStatus === 'cancelled') {
                $shouldRestoreInventory =
                    $lockedOrder->inventory_deducted_at !== null
                    && $lockedOrder->inventory_restored_at === null;

                if ($shouldRestoreInventory) {
                    $lockedOrder->load('items');

                    /*
                    | Aggregate quantities by product slug.
                    */

                    $quantities = $lockedOrder->items
                        ->filter(function ($item) {
                            return !empty($item->product_slug);
                        })
                        ->groupBy('product_slug')
                        ->map(function ($items) {
                            return (int) $items->sum('quantity');
                        })
                        ->sortKeys();

                    /*
                    | Restore inventory inside same transaction.
                    */

                    foreach ($quantities as $slug => $quantity) {
                        if ($quantity <= 0) {
                            continue;
                        }

                        /*
                        | Ensure inventory record exists.
                        | A missing record starts at zero.
                        */

                        ProductInventory::query()->firstOrCreate(
                            [
                                'product_slug' => $slug,
                            ],
                            [
                                'stock' => 0,
                                'low_stock_threshold' => 5,
                            ]
                        );

                        /*
                        | Lock inventory before increment.
                        */

                        $inventory = ProductInventory::query()
                            ->where('product_slug', $slug)
                            ->lockForUpdate()
                            ->firstOrFail();

                        $inventory->increment(
                            'stock',
                            $quantity
                        );
                    }

                    $lockedOrder->inventory_restored_at = now();
                }

                /*
                | Update order status.
                */

                $lockedOrder->order_status = 'cancelled';

                $lockedOrder->save();

                /*
                | Record cancellation history.
                |
                | Admin uses a separate auth guard/table,
                | while changed_by references users.
                | Therefore changed_by stays null.
                */

                $lockedOrder->statusHistory()->create([
                    'status' => 'cancelled',

                    'note' => $note
                        ?: 'Order cancelled by admin.',

                    'changed_by' => null,
                ]);

                /*
                | Record customer cancellation email.
                */

                app(OrderEmailService::class)
                    ->recordCustomerUpdate(
                        $lockedOrder,
                        'order_cancelled'
                    );

                return [
                    'message' => $shouldRestoreInventory
                        ? 'Order cancelled and inventory restored successfully.'
                        : 'Order cancelled successfully. No inventory restoration was required.',

                    'notification_type' => 'order_cancelled',
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | NORMAL STATUS UPDATE
            |--------------------------------------------------------------------------
            */

            $lockedOrder->order_status = $newStatus;

            $lockedOrder->save();

            /*
            | Record status history.
            */

            $defaultNotes = [
                'confirmed' =>
                    'Your order has been confirmed.',

                'processing' =>
                    'Your order is being prepared.',

                'shipped' =>
                    'Your order has been shipped.',

                'delivered' =>
                    'Your order has been delivered.',
            ];

            $lockedOrder->statusHistory()->create([
                'status' => $newStatus,

                'note' => $note
                    ?: ($defaultNotes[$newStatus]
                        ?? 'Order status updated.'),

                'changed_by' => null,
            ]);

            /*
            | Preserve existing customer email types.
            */

            $notificationTypes = [
                'confirmed' => 'order_confirmed',
                'shipped' => 'order_shipped',
                'delivered' => 'order_delivered',
            ];

            $notificationType =
                $notificationTypes[$newStatus] ?? null;

            if ($notificationType !== null) {
                app(OrderEmailService::class)
                    ->recordCustomerUpdate(
                        $lockedOrder,
                        $notificationType
                    );
            }

            /*
            | Processing history is recorded, but no
            | new email type is introduced until the
            | email service supports it.
            */

            return [
                'message' =>
                    'Order status updated to ' .
                    ucfirst($newStatus) .
                    ' successfully.',

                'notification_type' => $notificationType,
            ];

        }, 3);

        /*
        |--------------------------------------------------------------------------
        | DISPATCH EMAIL AFTER COMMIT
        |--------------------------------------------------------------------------
        */

        if ($result['notification_type'] !== null) {
            $this->dispatchCustomerNotification(
                $order,
                $result['notification_type']
            );
        }

        return back()->with(
            'success',
            $result['message']
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MARK COD PAYMENT AS PAID
    |--------------------------------------------------------------------------
    */

    public function markAsPaid(
        Order $order
    ): RedirectResponse {
        $result = DB::transaction(function () use ($order) {
            /*
            | LOCK ORDER
            */

            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            | VALIDATE PAYMENT METHOD
            */

            if ($lockedOrder->payment_method !== 'cod') {
                throw ValidationException::withMessages([
                    'payment_status' =>
                        'Only Cash on Delivery payments can be updated here.',
                ]);
            }

            /*
            | BLOCK CANCELLED ORDERS
            */

            if ($lockedOrder->order_status === 'cancelled') {
                throw ValidationException::withMessages([
                    'payment_status' =>
                        'A cancelled order cannot be marked as paid.',
                ]);
            }

            /*
            | PAYMENT ONLY AFTER DELIVERY
            */

            if ($lockedOrder->order_status !== 'delivered') {
                throw ValidationException::withMessages([
                    'payment_status' =>
                        'Mark the order as delivered before confirming COD payment.',
                ]);
            }

            /*
            | ALREADY PAID
            */

            if ($lockedOrder->payment_status === 'paid') {
                return [
                    'message' =>
                        'This order is already marked as paid.',

                    'notification_type' => null,
                ];
            }

            /*
            | VALIDATE CURRENT PAYMENT STATUS
            */

            if ($lockedOrder->payment_status !== 'pending') {
                throw ValidationException::withMessages([
                    'payment_status' =>
                        'This payment status cannot be updated automatically.',
                ]);
            }

            /*
            | UPDATE PAYMENT STATUS
            */

            $lockedOrder->payment_status = 'paid';

            $lockedOrder->save();

            /*
            | RECORD PAYMENT RECEIVED EMAIL
            */

            app(OrderEmailService::class)
                ->recordCustomerUpdate(
                    $lockedOrder,
                    'payment_received'
                );

            return [
                'message' =>
                    'COD payment marked as paid successfully.',

                'notification_type' => 'payment_received',
            ];

        }, 3);

        /*
        | DISPATCH EMAIL AFTER COMMIT
        */

        if ($result['notification_type'] !== null) {
            $this->dispatchCustomerNotification(
                $order,
                $result['notification_type']
            );
        }

        return back()->with(
            'success',
            $result['message']
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DISPATCH CUSTOMER NOTIFICATION
    |--------------------------------------------------------------------------
    */

    private function dispatchCustomerNotification(
        Order $order,
        string $type
    ): void {
        try {
            app(OrderEmailService::class)
                ->dispatchCustomerUpdate(
                    $order,
                    $type
                );

        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
