<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\Product;
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
use Throwable;

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

        $checkoutToken = $request->session()
            ->get('tob_checkout_token');

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

        $savedAddresses = $user
            ? $user->addresses()
                ->orderByDesc('is_default')
                ->latest()
                ->get()
            : collect();

        return view(
            'pages.checkout',
            compact(
                'user',
                'checkoutToken',
                'savedAddresses'
            )
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
        $user = $request->user();

        $userId = $user?->id;

        $submittedToken = $request->input('checkout_token');

        $sessionToken = (string) $request
            ->session()
            ->get('tob_checkout_token', '');

        /*
        |--------------------------------------------------------------------------
        | SAFE RETRY CHECK
        |--------------------------------------------------------------------------
        */

        if (
            is_string($submittedToken) &&
            Str::isUuid($submittedToken) &&
            $sessionToken !== '' &&
            hash_equals($sessionToken, $submittedToken)
        ) {
            $existingOrder = Order::query()
                ->where('checkout_token', $submittedToken)
                ->first();

            if ($existingOrder) {
                if (
                    $existingOrder->user_id !== null &&
                    $existingOrder->user_id !== $userId
                ) {
                    abort(403);
                }

                return $this->successResponse($existingOrder);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDATE REQUEST
        |--------------------------------------------------------------------------
        */

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

            'saved_address_id' => [
                'nullable',
            ],

            'save_address' => [
                'sometimes',
                'boolean',
            ],

            'address_label' => [
                'nullable',
                'string',
                'max:40',
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
        | VERIFY SESSION TOKEN
        |--------------------------------------------------------------------------
        */

        if (
            $sessionToken === '' ||
            !hash_equals($sessionToken, $checkoutToken)
        ) {
            throw ValidationException::withMessages([
                'checkout_token' => [
                    'Your checkout session has expired. Please refresh the checkout page.',
                ],
            ]);
        }

        $existingOrder = Order::query()
            ->where('checkout_token', $checkoutToken)
            ->first();

        if ($existingOrder) {
            if (
                $existingOrder->user_id !== null &&
                $existingOrder->user_id !== $userId
            ) {
                abort(403);
            }

            return $this->successResponse($existingOrder);
        }

        /*
        |--------------------------------------------------------------------------
        | VERIFY SAVED ADDRESS OWNERSHIP
        |--------------------------------------------------------------------------
        */

        $selectedAddressId = $validated['saved_address_id'] ?? null;

        if (
            $selectedAddressId !== null &&
            $selectedAddressId !== '' &&
            $selectedAddressId !== 'new'
        ) {
            if (
                !$user ||
                !ctype_digit((string) $selectedAddressId)
            ) {
                throw ValidationException::withMessages([
                    'saved_address_id' => [
                        'Invalid delivery address selected.',
                    ],
                ]);
            }

            $savedAddress = $user->addresses()
                ->whereKey($selectedAddressId)
                ->first();

            if (!$savedAddress) {
                throw ValidationException::withMessages([
                    'saved_address_id' => [
                        'The selected delivery address was not found.',
                    ],
                ]);
            }

            // Use the saved address as the authoritative
            // delivery snapshot instead of browser-edited values.
            foreach ([
                'name',
                'phone',
                'address',
                'landmark',
                'city',
                'state',
                'pincode',
            ] as $field) {
                $validated[$field] = $savedAddress->{$field};
            }

            $validated['save_address'] = false;
        }

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

        ksort($normalizedCart);

        /*
        |--------------------------------------------------------------------------
        | DATABASE TRANSACTION
        |--------------------------------------------------------------------------
        */

        try {
            $result = DB::transaction(function () use (
                $validated,
                $checkoutToken,
                $normalizedCart,
                $userId
            ) {
                /*
                |--------------------------------------------------------------
                | PREVENT DUPLICATE ORDERS
                |--------------------------------------------------------------
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

                /*
                |--------------------------------------------------------------
                | RETAILER WHOLESALE ELIGIBILITY
                |--------------------------------------------------------------
                */

                $isApprovedRetailer = false;

                if ($userId !== null) {
                    $isApprovedRetailer = DB::table('retailer_profiles')
                        ->where('user_id', $userId)
                        ->where('status', 'approved')
                        ->exists();
                }

                $subtotal = 0;

                $items = [];

                $inventoryRows = [];

                /*
                |--------------------------------------------------------------
                | PRODUCTS, PRICES AND STOCK
                |--------------------------------------------------------------
                */

                foreach ($normalizedCart as $slug => $quantity) {
                    $productModel = Product::query()
                        ->where('slug', $slug)
                        ->where('is_active', true)
                        ->lockForUpdate()
                        ->first();

                    if (!$productModel) {
                        throw ValidationException::withMessages([
                            'cart' => [
                                'Your cart contains an unavailable product.',
                            ],
                        ]);
                    }

                    $productName = $productModel->name;

                    /*
                    |----------------------------------------------------------
                    | WHOLESALE PRICING
                    |----------------------------------------------------------
                    */

                    $useWholesale =
                        $isApprovedRetailer &&
                        $productModel->hasWholesalePricing();

                    $minimumQuantity = $useWholesale
                        ? $productModel->getWholesaleMinimumQuantity()
                        : 1;

                    if ($minimumQuantity > 50) {
                        throw ValidationException::withMessages([
                            'cart' => [
                                $productName .
                                ' has a wholesale minimum quantity above the 50-unit checkout limit.',
                            ],
                        ]);
                    }

                    if ($quantity < $minimumQuantity) {
                        throw ValidationException::withMessages([
                            'cart' => [
                                $productName .
                                ' requires a minimum quantity of ' .
                                $minimumQuantity . ' units.',
                            ],
                        ]);
                    }

                    /*
                    |----------------------------------------------------------
                    | SERVER-SIDE PRICE
                    |----------------------------------------------------------
                    */

                    $price = round(
                        (float) (
                            $useWholesale
                                ? $productModel->wholesale_price
                                : $productModel->price
                        ),
                        2
                    );

                    if (!is_finite($price) || $price < 0) {
                        throw ValidationException::withMessages([
                            'cart' => [
                                'A product has an invalid price.',
                            ],
                        ]);
                    }

                    /*
                    |----------------------------------------------------------
                    | PRODUCT IMAGE
                    |----------------------------------------------------------
                    */

                    $image = $productModel->image
                        ? (
                            str_starts_with(
                                $productModel->image,
                                'products/'
                            )
                                ? 'storage/' . $productModel->image
                                : $productModel->image
                        )
                        : null;

                    /*
                    |----------------------------------------------------------
                    | LOCK INVENTORY
                    |----------------------------------------------------------
                    */

                    $inventory = ProductInventory::query()
                        ->where('product_slug', $slug)
                        ->lockForUpdate()
                        ->first();

                    if (!$inventory || $inventory->stock <= 0) {
                        throw ValidationException::withMessages([
                            'cart' => [
                                $productName .
                                ' is currently out of stock.',
                            ],
                        ]);
                    }

                    if ($quantity > $inventory->stock) {
                        throw ValidationException::withMessages([
                            'cart' => [
                                'Only ' . $inventory->stock .
                                ' unit(s) of ' . $productName .
                                ' are currently available.',
                            ],
                        ]);
                    }

                    /*
                    |----------------------------------------------------------
                    | ORDER ITEM SNAPSHOT
                    |----------------------------------------------------------
                    */

                    $lineTotal = round($price * $quantity, 2);

                    $subtotal += $lineTotal;

                    $items[] = [
                        'product_slug' => $slug,
                        'product_name' => $productName,
                        'price' => $price,
                        'quantity' => $quantity,
                        'total' => $lineTotal,
                        'image' => $image,
                    ];

                    $inventoryRows[] = [
                        'model' => $inventory,
                        'quantity' => $quantity,
                    ];
                }

                /*
                |--------------------------------------------------------------
                | SHIPPING CALCULATION
                |--------------------------------------------------------------
                */

                $subtotal = round($subtotal, 2);

                $shipping = $subtotal >= 499 ? 0 : null;

                // Shipping below Rs 499 has not been configured.
                // Preserve the existing checkout calculation.

                $total = $subtotal;

                /*
                |--------------------------------------------------------------
                | CREATE ORDER
                |--------------------------------------------------------------
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
                    'landmark' => $validated['landmark'] ?? null,
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
                |--------------------------------------------------------------
                | ORDER STATUS HISTORY
                |--------------------------------------------------------------
                */

                $order->statusHistory()->create([
                    'status' => 'pending',
                    'note' => 'Order placed successfully.',
                    'changed_by' => null,
                ]);

                /*
                |--------------------------------------------------------------
                | CREATE ORDER ITEMS
                |--------------------------------------------------------------
                */

                $order->items()->createMany($items);

                /*
                |--------------------------------------------------------------
                | DEDUCT INVENTORY
                |--------------------------------------------------------------
                */

                foreach ($inventoryRows as $inventoryRow) {
                    $inventoryRow['model']->decrement(
                        'stock',
                        $inventoryRow['quantity']
                    );
                }

                $order->forceFill([
                    'inventory_deducted_at' => now(),
                    'inventory_restored_at' => null,
                ])->save();

                /*
                |--------------------------------------------------------------
                | SAVE NEW DELIVERY ADDRESS
                |--------------------------------------------------------------
                */

                if (
                    $userId !== null &&
                    !empty($validated['save_address'])
                ) {
                    $addressFields = [
                        'name' => $validated['name'],
                        'phone' => $validated['phone'],
                        'address' => $validated['address'],
                        'landmark' => $validated['landmark'] ?? null,
                        'city' => $validated['city'],
                        'state' => $validated['state'],
                        'pincode' => $validated['pincode'],
                    ];

                    $existingAddress = CustomerAddress::query()
                        ->where('user_id', $userId)
                        ->where($addressFields)
                        ->first();

                    if (!$existingAddress) {
                        $hasAddress = CustomerAddress::query()
                            ->where('user_id', $userId)
                            ->exists();

                        CustomerAddress::create([
                            'user_id' => $userId,

                            'label' =>
                                $validated['address_label']
                                ?? 'Home',

                            ...$addressFields,

                            'is_default' => !$hasAddress,
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------
                | RECORD ORDER EMAILS
                |--------------------------------------------------------------
                */

                app(OrderEmailService::class)->record($order);

                return [
                    'order' => $order,
                    'created' => true,
                ];
            }, 3);

            /*
            |------------------------------------------------------------------
            | TRANSACTION RESULT
            |------------------------------------------------------------------
            */

            $order = $result['order'];

            if (!$result['created']) {
                return $this->successResponse($order);
            }

            $order->load('items');

            return $this->completeNewOrder(
                $order,
                $user,
                $validated
            );

        } catch (ValidationException $exception) {
            throw $exception;

        } catch (QueryException $exception) {
            $existingOrder = Order::query()
                ->where('checkout_token', $checkoutToken)
                ->first();

            if ($existingOrder) {
                return $this->successResponse($existingOrder);
            }

            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'We could not place your order. Please try again.',
            ], 500);

        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'We could not place your order. Please try again.',
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | COMPLETE NEW ORDER
    |--------------------------------------------------------------------------
    */

    private function completeNewOrder(
        Order $order,
        $user,
        array $validated
    ): JsonResponse {
        /*
        |----------------------------------------------------------------------
        | DISPATCH ORDER EMAILS
        |----------------------------------------------------------------------
        */

        try {
            app(OrderEmailService::class)->dispatch($order);

        } catch (Throwable $exception) {
            report($exception);
        }

        /*
        |----------------------------------------------------------------------
        | SAVE CUSTOMER PHONE IF MISSING
        |----------------------------------------------------------------------
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

        } catch (Throwable $exception) {
            report($exception);
        }

        return $this->successResponse($order);
    }

    /*
    |--------------------------------------------------------------------------
    | ORDER SUCCESS PAGE
    |--------------------------------------------------------------------------
    */

    public function success(
        Request $request,
        string $orderNumber
    ) {
        abort_unless(
            $request->hasValidSignature(),
            403
        );

        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view(
            'pages.order-success',
            compact('order')
        );
    }
}
