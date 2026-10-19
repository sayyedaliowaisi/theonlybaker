<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderEmailNotification;
use App\Models\ProductInventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class OrderCancellationTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): Admin
    {
        return Admin::create([
            'name' => 'Test Administrator',
            'email' => 'admin@example.test',
            'password' => 'TestPassword123!',
        ]);
    }

    private function makeOrder(
        string $status = 'pending'
    ): Order {
        $order = Order::create([
            'order_number' => 'TOB-TEST-' . uniqid(),
            'name' => 'Test Customer',
            'email' => 'customer@example.test',
            'phone' => '9876543210',
            'address' => 'Test Address',
            'city' => 'Lucknow',
            'state' => 'Uttar Pradesh',
            'pincode' => '226001',
            'country' => 'India',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'order_status' => $status,
            'subtotal' => 1000,
            'shipping' => 0,
            'total' => 1000,
            'inventory_deducted_at' => now(),
        ]);

        OrderItem::create([
    'order_id' => $order->id,
    'product_slug' => 'cherry-fruit',
    'product_name' => 'Cherry Fruit',
    'price' => 500,
    'quantity' => 2,
    'total' => 1000,
]);

        ProductInventory::create([
            'product_slug' => 'cherry-fruit',
            'stock' => 5,
            'low_stock_threshold' => 2,
        ]);

        return $order;
    }

    public function test_cancellation_restores_inventory(): void
    {
        Bus::fake();

        $admin = $this->makeAdmin();
        $order = $this->makeOrder();

        $this->actingAs($admin, 'admin')
            ->patch(
                route('admin.orders.status', $order),
                ['order_status' => 'cancelled']
            )
            ->assertRedirect();

        $order->refresh();

        $this->assertSame('cancelled', $order->order_status);
        $this->assertNotNull($order->inventory_restored_at);

        $this->assertSame(
            7,
            ProductInventory::where(
                'product_slug',
                'cherry-fruit'
            )->value('stock')
        );

        $this->assertDatabaseHas(
            'order_email_notifications',
            [
                'order_id' => $order->id,
                'type' => 'order_cancelled',
            ]
        );
    }

    public function test_duplicate_cancellation_does_not_restore_twice(): void
    {
        Bus::fake();

        $admin = $this->makeAdmin();
        $order = $this->makeOrder();

        $this->actingAs($admin, 'admin');

        $this->patch(
            route('admin.orders.status', $order),
            ['order_status' => 'cancelled']
        )->assertRedirect();

        $firstRestoredAt = $order->fresh()->inventory_restored_at;

        $this->patch(
            route('admin.orders.status', $order),
            ['order_status' => 'cancelled']
        )->assertRedirect();

        $this->assertSame(
            7,
            ProductInventory::where(
                'product_slug',
                'cherry-fruit'
            )->value('stock')
        );

        $this->assertEquals(
            $firstRestoredAt,
            $order->fresh()->inventory_restored_at
        );

        $this->assertSame(
            1,
            OrderEmailNotification::where(
                'order_id',
                $order->id
            )->where(
                'type',
                'order_cancelled'
            )->count()
        );
    }

    public function test_cancelled_order_cannot_be_reactivated(): void
    {
        Bus::fake();

        $admin = $this->makeAdmin();
        $order = $this->makeOrder('cancelled');

        $this->actingAs($admin, 'admin')
            ->patch(
                route('admin.orders.status', $order),
                ['order_status' => 'confirmed']
            )
            ->assertSessionHasErrors('order_status');

        $this->assertSame(
            'cancelled',
            $order->fresh()->order_status
        );
    }

    public function test_invalid_status_transition_is_rejected(): void
    {
        Bus::fake();

        $admin = $this->makeAdmin();
        $order = $this->makeOrder();

        $this->actingAs($admin, 'admin')
            ->patch(
                route('admin.orders.status', $order),
                ['order_status' => 'delivered']
            )
            ->assertSessionHasErrors('order_status');

        $this->assertSame(
            'pending',
            $order->fresh()->order_status
        );

        $this->assertSame(
            5,
            ProductInventory::where(
                'product_slug',
                'cherry-fruit'
            )->value('stock')
        );
    }
}
