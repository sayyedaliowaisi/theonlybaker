<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\RetailerProfile;
use App\Models\User;
use App\Services\OrderEmailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

class WholesaleCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(
        int $stock = 100,
        float $retailPrice = 200,
        ?float $wholesalePrice = 120,
        int $moq = 5
    ): Product {
        $product = Product::create([
            'slug' => 'test-wholesale-cookie',
            'number' => 9001,
            'name' => 'Test Wholesale Cookie',
            'description' => 'Test product',
            'price' => $retailPrice,
            'wholesale_price' => $wholesalePrice,
            'wholesale_moq' => $moq,
            'category' => 'Cookies',
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => 1,
        ]);

        ProductInventory::create([
            'product_slug' => $product->slug,
            'stock' => $stock,
            'low_stock_threshold' => 5,
        ]);

        return $product;
    }

    private function createRetailer(string $status): User
    {
        $user = User::factory()->create();

        RetailerProfile::create([
            'user_id' => $user->id,
            'business_name' => 'Test Bakery Retailer',
            'business_phone' => '9876543210',
            'business_address' => 'Test Market, Lucknow',
            'status' => $status,
            'approved_at' => $status === 'approved'
                ? now()
                : null,
        ]);

        return $user;
    }

    private function payload(
        string $token,
        int $quantity = 5
    ): array {
        return [
            'checkout_token' => $token,
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'phone' => '9876543210',
            'address' => 'Test Address',
            'landmark' => 'Test Landmark',
            'city' => 'Lucknow',
            'state' => 'Uttar Pradesh',
            'pincode' => '226001',
            'payment_method' => 'cod',
            'cart' => [
                [
                    'slug' => 'test-wholesale-cookie',
                    'quantity' => $quantity,
                ],
            ],
        ];
    }

    private function submitOrder(
        int $quantity = 5,
        ?string $token = null
    ) {
        $token ??= (string) Str::uuid();

        $this->withSession([
            'tob_checkout_token' => $token,
        ]);

        return $this->postJson(
            route('checkout.store'),
            $this->payload($token, $quantity)
        );
    }

    private function fakeOrderEmails(): void
    {
        $service = Mockery::mock(OrderEmailService::class);

        $service->shouldReceive('record')
            ->zeroOrMoreTimes();

        $service->shouldReceive('dispatch')
            ->zeroOrMoreTimes();

        $this->app->instance(
            OrderEmailService::class,
            $service
        );
    }

    public function test_guest_customer_pays_retail_price(): void
    {
        $this->fakeOrderEmails();
        $this->createProduct();

        $this->submitOrder(5)
            ->assertOk()
            ->assertJsonPath('success', true);

        $order = Order::with('items')->firstOrFail();

        $this->assertEquals(1000, (float) $order->subtotal);
        $this->assertEquals(200, (float) $order->items->first()->price);
    }

    public function test_normal_customer_pays_retail_price(): void
    {
        $this->fakeOrderEmails();
        $this->createProduct();

        $this->actingAs(User::factory()->create());

        $this->submitOrder(5)->assertOk();

        $this->assertEquals(
            1000,
            (float) Order::firstOrFail()->subtotal
        );
    }

    public function test_pending_retailer_pays_retail_price(): void
    {
        $this->fakeOrderEmails();
        $this->createProduct();

        $this->actingAs($this->createRetailer('pending'));

        $this->submitOrder(5)->assertOk();

        $this->assertEquals(
            1000,
            (float) Order::firstOrFail()->subtotal
        );
    }

    public function test_rejected_retailer_pays_retail_price(): void
    {
        $this->fakeOrderEmails();
        $this->createProduct();

        $this->actingAs($this->createRetailer('rejected'));

        $this->submitOrder(5)->assertOk();

        $this->assertEquals(
            1000,
            (float) Order::firstOrFail()->subtotal
        );
    }

    public function test_approved_retailer_pays_wholesale_price(): void
    {
        $this->fakeOrderEmails();
        $product = $this->createProduct();

        $this->actingAs($this->createRetailer('approved'));

        $this->submitOrder(5)
            ->assertOk()
            ->assertJsonPath('success', true);

        $order = Order::with('items')->firstOrFail();

        $this->assertEquals(600, (float) $order->subtotal);
        $this->assertEquals(120, (float) $order->items->first()->price);
        $this->assertEquals(5, $order->items->first()->quantity);

        $this->assertDatabaseHas('product_inventories', [
            'product_slug' => $product->slug,
            'stock' => 95,
        ]);
    }

    public function test_approved_retailer_cannot_order_below_moq(): void
    {
        $this->fakeOrderEmails();
        $product = $this->createProduct();

        $this->actingAs($this->createRetailer('approved'));

        $this->submitOrder(4)
            ->assertStatus(422)
            ->assertJsonValidationErrors('cart');

        $this->assertDatabaseCount('orders', 0);

        $this->assertDatabaseHas('product_inventories', [
            'product_slug' => $product->slug,
            'stock' => 100,
        ]);
    }

    public function test_wholesale_order_fails_when_stock_is_insufficient(): void
    {
        $this->fakeOrderEmails();
        $this->createProduct(stock: 3);

        $this->actingAs($this->createRetailer('approved'));

        $this->submitOrder(5)
            ->assertStatus(422)
            ->assertJsonValidationErrors('cart');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_product_without_wholesale_price_uses_retail_price(): void
    {
        $this->fakeOrderEmails();

        $this->createProduct(
            wholesalePrice: null
        );

        $this->actingAs($this->createRetailer('approved'));

        $this->submitOrder(2)->assertOk();

        $this->assertEquals(
            400,
            (float) Order::firstOrFail()->subtotal
        );
    }

    public function test_duplicate_checkout_does_not_create_second_order(): void
    {
        $this->fakeOrderEmails();
        $this->createProduct();

        $this->actingAs($this->createRetailer('approved'));

        $token = (string) Str::uuid();

        $this->submitOrder(5, $token)->assertOk();

        $this->submitOrder(5, $token)->assertOk();

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);

        $this->assertDatabaseHas('product_inventories', [
            'product_slug' => 'test-wholesale-cookie',
            'stock' => 95,
        ]);
    }
}
