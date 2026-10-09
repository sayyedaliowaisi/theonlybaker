<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductInventory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()
            ->withCount('items')
            ->latest();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where(
                'order_status',
                $request->status
            );
        }

        $orders = $query
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD COUNTS
        |--------------------------------------------------------------------------
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

            'shipped' => Order::where(
                'order_status',
                'shipped'
            )->count(),

            'delivered' => Order::where(
                'order_status',
                'delivered'
            )->count(),
        ];

        return view(
            'admin.orders.index',
            compact('orders', 'stats')
        );
    }

    public function show(Order $order)
    {
        $order->load('items');

        return view(
            'admin.orders.show',
            compact('order')
        );
    }

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
                    'shipped',
                    'delivered',
                    'cancelled',
                ]),
            ],
        ]);

        $newStatus = $validated['order_status'];

        $message = DB::transaction(function () use (
            $order,
            $newStatus
        ) {
            /*
            |--------------------------------------------------------------------------
            | LOCK ORDER TO PREVENT CONCURRENT STATUS CHANGES
            |--------------------------------------------------------------------------
            */

            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            $oldStatus = $lockedOrder->order_status;

            /*
            |--------------------------------------------------------------------------
            | NO CHANGE
            |--------------------------------------------------------------------------
            */

            if ($oldStatus === $newStatus) {
                return 'Order already has this status.';
            }

            /*
            |--------------------------------------------------------------------------
            | CANCELLED ORDERS ARE FINAL
            |--------------------------------------------------------------------------
            */

            if ($oldStatus === 'cancelled') {
                throw ValidationException::withMessages([
                    'order_status' =>
                        'A cancelled order cannot be reactivated.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | CANCEL ORDER AND RESTORE STOCK
            |--------------------------------------------------------------------------
            */

            if ($newStatus === 'cancelled') {
                /*
                 * Only restore inventory if this order was
                 * actually deducted from stock previously.
                 *
                 * This prevents old orders from incorrectly
                 * increasing inventory.
                 */

                $shouldRestoreInventory =
                    $lockedOrder->inventory_deducted_at !== null
                    && $lockedOrder->inventory_restored_at === null;

                if ($shouldRestoreInventory) {
                    $lockedOrder->load('items');

                    /*
                     * Aggregate quantities by slug so each
                     * inventory row is updated only once.
                     */

                    $quantities = $lockedOrder->items
                        ->groupBy('product_slug')
                        ->map(function ($items) {
                            return $items->sum('quantity');
                        })
                        ->sortKeys();

                    foreach ($quantities as $slug => $quantity) {
                        $quantity = (int) $quantity;

                        if ($quantity <= 0) {
                            continue;
                        }

                        /*
                         * Create a missing inventory record
                         * if necessary, then lock it.
                         */

                        ProductInventory::query()->firstOrCreate(
                            ['product_slug' => $slug],
                            [
                                'stock' => 0,
                                'low_stock_threshold' => 5,
                            ]
                        );

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

                $lockedOrder->order_status = 'cancelled';
                $lockedOrder->save();

                return $shouldRestoreInventory
                    ? 'Order cancelled and inventory restored successfully.'
                    : 'Order cancelled successfully. No inventory restoration was required.';
            }

            /*
            |--------------------------------------------------------------------------
            | NORMAL STATUS UPDATE
            |--------------------------------------------------------------------------
            */

            $lockedOrder->order_status = $newStatus;
            $lockedOrder->save();

            return 'Order status updated successfully.';
        }, 3);

        return back()->with('success', $message);
    }
}