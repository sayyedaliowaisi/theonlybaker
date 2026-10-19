<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderEmailNotification;
use App\Models\Product;
use App\Models\ProductInventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function makeProduct(int $stock = 10): Product
    {
        $product = new Product();

        $product->slug = 'cherry-fruit';
$product->name = 'Cherry Fruit';
$product->category = 'Cookies';
$product->price = 250;
$product->is_active = true;

        $product->save();

        ProductInventory::create([
            'product_slug' => 'cherry-fruit',
            'stock' => $stock,
            'low_stock_threshold' => 2,
        ]);

        return $product;
    }

    private function checkoutPayload(
        string $token,
        int $quantity = 2
    ): array {
        return [
            'checkout_token' => $token,
            'name' => 'Test Customer',
            'email' => 'customer@example.test',
            'phone' => '9876543210',
            'address' => 'Test Address',
            'city' => 'Lucknow',
            'state' => 'Uttar Pradesh',
            'pincode' => '226001',
            'payment_method' => 'cod',
            'cart' => [
                [
                    'slug' => 'cherry-fruit',
                    'quantity' => $quantity,
                ],
            ],
        ];
    }

    private function stock(): int
    {
        return (int) ProductInventory::where(
            'product_slug',
            'cherry-fruit'
        )->value('stock');
    }

    public function test_successful_checkout_creates_order_and_deducts_stock(): void
    {
        Bus::fake();

        $this->makeProduct();

        $token = (string) Str::uuid();

        $this->withSession([
            'tob_checkout_token' => $token,
        ])->postJson(
            route('checkout.store'),
            $this->checkoutPayload($token)
        )->assertOk()
         ->assertJsonPath('success', true);

        $this->assertSame(1, Order::count());
        $this->assertSame(8, $this->stock());

        $order = Order::firstOrFail();

        $this->assertEquals(500, $order->total);
        $this->assertNotNull($order->inventory_deducted_at);

        $this->assertSame(1, $order->items()->count());

        $this->assertSame(
            2,
            (int) $order->items()->first()->quantity
        );
    }

    public function test_insufficient_stock_rejects_checkout(): void
    {
        Bus::fake();

        $this->makeProduct(1);

        $token = (string) Str::uuid();

        $this->withSession([
            'tob_checkout_token' => $token,
        ])->postJson(
            route('checkout.store'),
            $this->checkoutPayload($token, 2)
        )->assertUnprocessable()
         ->assertJsonValidationErrors('cart');

        $this->assertSame(0, Order::count());
        $this->assertSame(1, $this->stock());
    }

    public function test_duplicate_checkout_token_does_not_create_second_order(): void
    {
        Bus::fake();

        $this->makeProduct();

        $token = (string) Str::uuid();

        $payload = $this->checkoutPayload($token);

        $this->withSession([
            'tob_checkout_token' => $token,
        ]);

        $first = $this->postJson(
            route('checkout.store'),
            $payload
        )->assertOk();

        $second = $this->postJson(
            route('checkout.store'),
            $payload
        )->assertOk();

        $this->assertSame(1, Order::count());
        $this->assertSame(8, $this->stock());

        $this->assertSame(
            $first->json('redirect'),
            $second->json('redirect')
        );

        $this->assertSame(
            1,
            OrderEmailNotification::where(
                'order_id',
                Order::firstOrFail()->id
            )->where('type', 'customer')->count()
        );
    }

    public function test_invalid_checkout_token_is_rejected(): void
    {
        Bus::fake();

        $this->makeProduct();

        $sessionToken = (string) Str::uuid();
        $submittedToken = (string) Str::uuid();

        $this->withSession([
            'tob_checkout_token' => $sessionToken,
        ])->postJson(
            route('checkout.store'),
            $this->checkoutPayload($submittedToken)
        )->assertUnprocessable()
         ->assertJsonValidationErrors('checkout_token');

        $this->assertSame(0, Order::count());
        $this->assertSame(10, $this->stock());
    }

    public function test_checkout_uses_database_price_not_browser_price(): void
    {
        Bus::fake();

        $this->makeProduct();

        $token = (string) Str::uuid();

        $payload = $this->checkoutPayload($token);

        // Malicious browser-supplied price.
        $payload['cart'][0]['price'] = 1;

        $this->withSession([
            'tob_checkout_token' => $token,
        ])->postJson(
            route('checkout.store'),
            $payload
        )->assertOk();

        $order = Order::firstOrFail();

        $this->assertEquals(500, $order->subtotal);
        $this->assertEquals(500, $order->total);
        $this->assertEquals(
            250,
            $order->items()->first()->price
        );

        $this->assertSame(8, $this->stock());
    }

    
public function test_duplicate_cart_items_are_combined(): void
{
    Bus::fake();

    $this->makeProduct(10);

    $token = (string) Str::uuid();

    $payload = $this->checkoutPayload($token);

    $payload['cart'] = [
        [
            'slug' => 'cherry-fruit',
            'quantity' => 2,
        ],
        [
            'slug' => 'cherry-fruit',
            'quantity' => 3,
        ],
    ];

    $this->withSession([
        'tob_checkout_token' => $token,
    ])->postJson(
        route('checkout.store'),
        $payload
    )->assertOk()
     ->assertJsonPath('success', true);

    $order = Order::firstOrFail();

    $this->assertSame(1, $order->items()->count());

    $this->assertSame(
        5,
        (int) $order->items()->first()->quantity
    );

    $this->assertEquals(1250, $order->total);

    $this->assertSame(5, $this->stock());
}

public function test_out_of_stock_product_cannot_be_ordered(): void
{
    Bus::fake();

    $this->makeProduct(0);

    $token = (string) Str::uuid();

    $this->withSession([
        'tob_checkout_token' => $token,
    ])->postJson(
        route('checkout.store'),
        $this->checkoutPayload($token, 1)
    )->assertUnprocessable()
     ->assertJsonValidationErrors('cart');

    $this->assertSame(0, Order::count());
    $this->assertSame(0, $this->stock());
}

public function test_inactive_product_cannot_be_ordered(): void
{
    Bus::fake();

    $product = $this->makeProduct(10);

    $product->is_active = false;
    $product->save();

    $token = (string) Str::uuid();

    $this->withSession([
        'tob_checkout_token' => $token,
    ])->postJson(
        route('checkout.store'),
        $this->checkoutPayload($token, 1)
    )->assertUnprocessable()
     ->assertJsonValidationErrors('cart');

    $this->assertSame(0, Order::count());
    $this->assertSame(10, $this->stock());
}

public function test_duplicate_cart_quantities_cannot_exceed_limit(): void
{
    Bus::fake();

    $this->makeProduct(100);

    $token = (string) Str::uuid();

    $payload = $this->checkoutPayload($token);

    $payload['cart'] = [
        [
            'slug' => 'cherry-fruit',
            'quantity' => 30,
        ],
        [
            'slug' => 'cherry-fruit',
            'quantity' => 25,
        ],
    ];

    $this->withSession([
        'tob_checkout_token' => $token,
    ])->postJson(
        route('checkout.store'),
        $payload
    )->assertUnprocessable()
     ->assertJsonValidationErrors('cart');

    $this->assertSame(0, Order::count());
    $this->assertSame(100, $this->stock());
}

}
