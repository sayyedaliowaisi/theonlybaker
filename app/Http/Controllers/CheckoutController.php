<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductInventory;
use App\Services\OrderEmailService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CHECKOUT PAGE
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = $request->user();

        $checkoutToken = $request->session()->get(
            'tob_checkout_token'
        );

        if (
            !$checkoutToken ||
            Order::where('checkout_token', $checkoutToken)->exists()
        ) {
            $checkoutToken = (string) Str::uuid();

            $request->session()->put(
                'tob_checkout_token',
                $checkoutToken
            );
        }

        return view(
            'pages.checkout',
            compact('user', 'checkoutToken')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SUCCESS RESPONSE
    |--------------------------------------------------------------------------
    */

    private function successResponse(Order $order): JsonResponse
    {
        $successUrl = URL::temporarySignedRoute(
            'order.success',
            now()->addHours(24),
            [
                'orderNumber' => $order->order_number,
            ]
        );

        return response()->json([
            'success' => true,
            'redirect' => $successUrl,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PLACE ORDER
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'checkout_token' => [
                'required',
                'uuid',
            ],

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
            ],

            'address' => [
                'required',
                'string',
                'max:500',
            ],

            'landmark' => [
                'nullable',
                'string',
                'max:150',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'state' => [
                'required',
                'string',
                'max:100',
            ],

            'pincode' => [
                'required',
                'digits:6',
            ],

            'payment_method' => [
                'required',
                Rule::in(['cod']),
            ],

            'cart' => [
                'required',
                'array',
                'min:1',
                'max:50',
            ],

            'cart.*.slug' => [
                'required',
                'string',
                'max:150',
            ],

            'cart.*.quantity' => [
                'required',
                'integer',
                'min:1',
                'max:50',
            ],
        ]);

        $checkoutToken = $validated['checkout_token'];

        /*
        |--------------------------------------------------------------------------
        | VERIFY CHECKOUT SESSION
        |--------------------------------------------------------------------------
        */

        $sessionToken = (string) $request->session()->get(
            'tob_checkout_token',
            ''
        );

        if (
            !$sessionToken ||
            !hash_equals($sessionToken, $checkoutToken)
        ) {
            throw ValidationException::withMessages([
                'checkout_token' => [
                    'Your checkout session has expired. Please refresh the checkout page.',
                ],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | IDEMPOTENCY - ALREADY PLACED ORDER
        |--------------------------------------------------------------------------
        */

        $existingOrder = Order::query()
            ->where('checkout_token', $checkoutToken)
            ->first();

        if ($existingOrder) {
            return $this->successResponse($existingOrder);
        }

        $user = $request->user();
        $userId = $user?->id;

        $products = config('products', []);

        /*
        |--------------------------------------------------------------------------
        | NORMALIZE CART
        |--------------------------------------------------------------------------
        */

        $normalizedCart = [];

        foreach ($validated['cart'] as $cartItem) {
            $slug = $cartItem['slug'];
            $quantity = (int) $cartItem['quantity'];

            $normalizedCart[$slug] =
                ($normalizedCart[$slug] ?? 0) + $quantity;

            if ($normalizedCart[$slug] > 50) {
                throw ValidationException::withMessages([
                    'cart' => [
                        'Maximum quantity allowed per product is 50.',
                    ],
                ]);
            }
        }

        // Consistent locking order helps reduce deadlocks.
        ksort($normalizedCart);

        /*
        |--------------------------------------------------------------------------
        | DATABASE TRANSACTION
        |--------------------------------------------------------------------------
        */

        try {
            $result = DB::transaction(
                function () use (
                    $validated,
                    $checkoutToken,
                    $normalizedCart,
                    $products,
                    $userId
                ) {
                    /*
                    |--------------------------------------------------------------------------
                    | RECHECK EXISTING ORDER
                    |--------------------------------------------------------------------------
                    */

                    $existingOrder = Order::query()
                        ->where('checkout_token', $checkoutToken)
                        ->first();

                    if ($existingOrder) {
                        return [
                            'order' => $existingOrder,
                            'created' => false,
                        ];
                    }

                    $subtotal = 0;
                    $items = [];
                    $inventoryRows = [];

                    /*
                    |--------------------------------------------------------------------------
                    | VERIFY PRODUCTS AND LOCK INVENTORY
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $normalizedCart as $slug => $quantity
                    ) {
                        if (!isset($products[$slug])) {
                            throw ValidationException::withMessages([
                                'cart' => [
                                    'Your cart contains an invalid product.',
                                ],
                            ]);
                        }

                        $product = $products[$slug];

                        $inventory = ProductInventory::query()
                            ->where('product_slug', $slug)
                            ->lockForUpdate()
                            ->first();

                        if (
                            !$inventory ||
                            $inventory->stock <= 0
                        ) {
                            throw ValidationException::withMessages([
                                'cart' => [
                                    $product['name'] .
                                    ' is currently out of stock.',
                                ],
                            ]);
                        }

                        if ($quantity > $inventory->stock) {
                            throw ValidationException::withMessages([
                                'cart' => [
                                    'Only ' .
                                    $inventory->stock .
                                    ' unit(s) of ' .
                                    $product['name'] .
                                    ' are currently available.',
                                ],
                            ]);
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | TRUST SERVER-SIDE PRICE ONLY
                        |--------------------------------------------------------------------------
                        */

                        $price = (float) $product['price'];

                        $lineTotal = round(
                            $price * $quantity,
                            2
                        );

                        $subtotal += $lineTotal;

                        $items[] = [
                            'product_slug' => $slug,
                            'product_name' => $product['name'],
                            'price' => $price,
                            'quantity' => $quantity,
                            'total' => $lineTotal,
                            'image' => $product['image'],
                        ];

                        $inventoryRows[] = [
                            'model' => $inventory,
                            'quantity' => $quantity,
                        ];
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | SHIPPING AND TOTAL
                    |--------------------------------------------------------------------------
                    */

                    $subtotal = round($subtotal, 2);

                    $shipping = $subtotal >= 499
                        ? 0
                        : null;

                    // Shipping below ₹499 is not configured yet.
                    $total = $subtotal;

                    /*
                    |--------------------------------------------------------------------------
                    | CREATE ORDER
                    |--------------------------------------------------------------------------
                    */

                    $order = Order::create([
                        'user_id' => $userId,

                        'checkout_token' => $checkoutToken,

                        'order_number' =>
                            'TOB-' .
                            now()->format('Ymd') .
                            '-' .
                            strtoupper(Str::random(6)),

                        'name' => $validated['name'],
                        'email' => $validated['email'],
                        'phone' => $validated['phone'],
                        'address' => $validated['address'],

                        'landmark' =>
                            $validated['landmark'] ?? null,

                        'city' => $validated['city'],
                        'state' => $validated['state'],
                        'pincode' => $validated['pincode'],

                        'country' => 'India',

                        'payment_method' => 'cod',
                        'payment_status' => 'pending',
                        'order_status' => 'pending',

                        'subtotal' => $subtotal,
                        'shipping' => $shipping,
                        'total' => $total,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | CREATE ORDER ITEMS
                    |--------------------------------------------------------------------------
                    */

                    $order->items()->createMany($items);

                    /*
                    |--------------------------------------------------------------------------
                    | DEDUCT INVENTORY
                    |--------------------------------------------------------------------------
                    */

                    foreach ($inventoryRows as $inventoryRow) {
                        $inventoryRow['model']->decrement(
                            'stock',
                            $inventoryRow['quantity']
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | MARK INVENTORY DEDUCTED
                    |--------------------------------------------------------------------------
                    */

                    $order->forceFill([
                        'inventory_deducted_at' => now(),
                        'inventory_restored_at' => null,
                    ])->save();

                    /*
                    |--------------------------------------------------------------------------
                    | RECORD EMAIL NOTIFICATIONS IN SAME TRANSACTION
                    |--------------------------------------------------------------------------
                    */

                    app(OrderEmailService::class)->record($order);

                    return [
                        'order' => $order,
                        'created' => true,
                    ];
                },
                3
            );

            $order = $result['order'];

            /*
            |--------------------------------------------------------------------------
            | EXISTING ORDER - DO NOT DISPATCH EMAIL AGAIN
            |--------------------------------------------------------------------------
            */

            if (!$result['created']) {
                return $this->successResponse($order);
            }

            $order->load('items');

            /*
            |--------------------------------------------------------------------------
            | COMPLETE NEW ORDER
            |--------------------------------------------------------------------------
            */

            return $this->completeNewOrder(
                $order,
                $user,
                $validated
            );

        } catch (ValidationException $exception) {
            throw $exception;

        } catch (QueryException $exception) {
            /*
            |--------------------------------------------------------------------------
            | HANDLE CONCURRENT DUPLICATE TOKEN
            |--------------------------------------------------------------------------
            */

            $existingOrder = Order::query()
                ->where('checkout_token', $checkoutToken)
                ->first();

            if ($existingOrder) {
                return $this->successResponse($existingOrder);
            }

            report($exception);

            return response()->json([
                'success' => false,
                'message' =>
                    'We could not place your order. Please try again.',
            ], 500);

        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' =>
                    'We could not place your order. Please try again.',
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | COMPLETE NEW ORDER - QUEUE EMAILS
    |--------------------------------------------------------------------------
    */

    private function completeNewOrder(
        Order $order,
        $user,
        array $validated
    ): JsonResponse {
        /*
        |--------------------------------------------------------------------------
        | DISPATCH CUSTOMER AND ADMIN EMAIL JOBS
        |--------------------------------------------------------------------------
        */

        try {
            app(OrderEmailService::class)->dispatch($order);

        } catch (\Throwable $exception) {
            report($exception);

            // Order is already saved.
            // Scheduler can dispatch pending notifications later.
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE USER PHONE
        |--------------------------------------------------------------------------
        */

        try {
            if (
                $user &&
                empty($user->phone) &&
                !empty($validated['phone'])
            ) {
                $user->update([
                    'phone' => $validated['phone'],
                ]);
            }

        } catch (\Throwable $exception) {
            report($exception);
        }

        return $this->successResponse($order);
    }

    /*
    |--------------------------------------------------------------------------
    | ORDER SUCCESS PAGE
    |--------------------------------------------------------------------------
    */

    public function success(string $orderNumber)
    {
        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view(
            'pages.order-success',
            compact('order')
        );
    }
}
