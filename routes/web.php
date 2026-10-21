<?php

use Illuminate\Support\Facades\Route;

use App\Services\ProductCatalog;

use App\Http\Controllers\ContactController;

use App\Http\Controllers\CheckoutController;

use App\Http\Controllers\CartStockController;

use App\Http\Controllers\Admin\AuthController;

use App\Http\Controllers\Admin\OrderController;

use App\Http\Controllers\Customer\AuthController as CustomerAuthController;

use App\Http\Controllers\Customer\AccountController;
use App\Http\Controllers\Customer\AddressController;

use App\Http\Controllers\Customer\EmailVerificationController;

use App\Http\Controllers\Customer\WishlistController;

use App\Http\Controllers\ProductReviewController;

use App\Models\ProductReview;

use App\Http\Controllers\Admin\ProductReviewController as AdminProductReviewController;

use App\Http\Controllers\Admin\ProductController as AdminProductController;

use App\Http\Controllers\Admin\ProductInventoryController;

use App\Http\Controllers\Admin\EmailNotificationController;

use App\Http\Controllers\RetailerRegistrationController;

use App\Http\Controllers\Admin\RetailerController as AdminRetailerController;

use App\Http\Controllers\Admin\ContactEnquiryController as AdminContactEnquiryController;

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;

/*

|--------------------------------------------------------------------------

| HOME

|--------------------------------------------------------------------------

*/

Route::get('/', function () {

    return view('pages.home');

})->name('home');

/*

|--------------------------------------------------------------------------

| SHOP

|--------------------------------------------------------------------------

|

| Active products database se load honge.

|

*/

Route::get('/shop', function (ProductCatalog $catalog) {

    $products = $catalog->all();

    $inventoryStocks = \App\Models\ProductInventory::query()

        ->whereIn('product_slug', array_keys($products))

        ->pluck('stock', 'product_slug');

    foreach ($products as $slug => &$product) {

        $product['stock'] = max(

            0,

            (int) ($inventoryStocks[$slug] ?? 0)

        );

    }

    unset($product);

    return view('pages.shop', compact('products'));

})->name('shop');


/*
|--------------------------------------------------------------------------
| COLLECTIONS
|--------------------------------------------------------------------------
|
| Public collections page.
| Existing cookie categories and curated collections.
|
*/



Route::get('/collections', function (ProductCatalog $catalog) {

    $featuredProducts = \App\Models\Product::query()
        ->where('is_active', true)
        ->where('is_featured', true)
        ->orderBy('sort_order')
        ->orderBy('id')
        ->limit(4)
        ->get();

    // Fetch inventory for all featured products in one query.
    $inventoryStocks = \App\Models\ProductInventory::query()
        ->whereIn(
            'product_slug',
            $featuredProducts->pluck('slug')->all()
        )
        ->pluck('stock', 'product_slug');

    // Preserve existing B2C/B2B pricing logic.
    $bestSellingCookies = $featuredProducts
        ->map(function ($product) use ($catalog, $inventoryStocks) {

            $data = $catalog->toArray($product);

            $data['stock'] = max(
                0,
                (int) ($inventoryStocks[$product->slug] ?? 0)
            );

            return $data;

        })
        ->all();

    return view(
        'pages.collections',
        compact('bestSellingCookies')
    );

})->name('collections');


Route::view('/gifting', 'pages.gifting')
    ->name('gifting');


Route::view('/faq', 'pages.faq')->name('faq');

Route::view('/shipping-delivery', 'pages.shipping-delivery')
    ->name('shipping.delivery');


Route::view(
    '/return-refund-cancellation',
    'pages.return-refund-cancellation'
)->name('refund.policy');


Route::view('/privacy-policy', 'pages.privacy-policy')
    ->name('privacy.policy');

 
Route::view('/terms-and-conditions', 'pages.terms-and-conditions')
    ->name('terms.conditions');  

Route::view('/custom-orders', 'pages.custom-orders')
    ->name('custom.orders');


use App\Http\Controllers\OrderTrackingController;

Route::middleware(['auth'])->group(function () {

    Route::get('/track-order', [OrderTrackingController::class, 'index'])
        ->name('orders.track');

    Route::post('/track-order', [OrderTrackingController::class, 'search'])
        ->middleware('throttle:10,1')
        ->name('orders.track.search');

});

/*
|--------------------------------------------------------------------------
| ABOUT US
|--------------------------------------------------------------------------
| Public About Us page for The Only Baker.
|--------------------------------------------------------------------------
*/

Route::get('/about-us', function () {
    return view('pages.about');
})->name('about');




/*
|--------------------------------------------------------------------------
| CONTACT US
|--------------------------------------------------------------------------
*/

Route::get('/contact-us', [ContactController::class, 'index'])
    ->name('contact');

Route::post('/contact-us', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');





/*
|--------------------------------------------------------------------------
| PRODUCT DETAILS
|--------------------------------------------------------------------------
*/

Route::get('/product/{slug}', function (string $slug) {

    $product = app(ProductCatalog::class)->find($slug);

    abort_if($product === null, 404);

    $stock = max(

        0,

        (int) (\App\Models\ProductInventory::query()

            ->where('product_slug', $slug)

            ->value('stock') ?? 0)

    );

    $reviews = ProductReview::query()

        ->with('user:id,name')

        ->where('product_slug', $slug)

        ->latest()

        ->get();

    $reviewCount = $reviews->count();

    $averageRating = $reviewCount > 0

        ? round((float) $reviews->avg('rating'), 1)

        : 0;

    $ratingBreakdown = [];

    for ($rating = 5; $rating >= 1; $rating--) {

        $ratingBreakdown[$rating] = $reviews

            ->where('rating', $rating)

            ->count();

    }

    $userReview = auth()->check()

        ? $reviews->firstWhere(

            'user_id',

            auth()->id()

        )

        : null;

    // Active related products from the same category.
    $relatedProducts = \App\Models\Product::query()
        ->where('is_active', true)
        ->where('slug', '!=', $slug)
        ->where('category', $product['category'])
        ->orderBy('sort_order')
        ->limit(4)
        ->get()
        ->map(fn ($related) => app(ProductCatalog::class)->toArray($related))
        ->all();

    return view(

        'pages.product',

        compact(

            'product',

            'reviews',

            'reviewCount',

            'averageRating',

            'ratingBreakdown',

            'userReview',

            'relatedProducts',
            'stock'

        )

    );

})->name('product.show');

Route::middleware('auth')->group(function () {

    Route::post(

        '/product/{slug}/review',

        [ProductReviewController::class, 'store']

    )->name('product.review.store');

    Route::delete(

        '/product/{slug}/review',

        [ProductReviewController::class, 'destroy']

    )->name('product.review.destroy');

});

/*

|--------------------------------------------------------------------------

| CART

|--------------------------------------------------------------------------

*/

Route::get('/cart', function () {

    return view('pages.cart');

})->name('cart');

Route::post('/cart/check-stock', [

    CartStockController::class,

    'check',

])

    ->middleware('throttle:60,1')

    ->name('cart.check-stock');


/*
|--------------------------------------------------------------------------
| CHECKOUT - CUSTOMER LOGIN REQUIRED
|--------------------------------------------------------------------------
|
| Guest customers can add products to cart.
| Login is compulsory to access checkout and place orders.
|
*/

Route::middleware(['web', 'auth'])->group(function () {

    Route::get(
        '/checkout',
        [CheckoutController::class, 'index']
    )->name('checkout');

    Route::post(
        '/checkout',
        [CheckoutController::class, 'store']
    )->name('checkout.store');

});


/*

|--------------------------------------------------------------------------

| ORDER SUCCESS

|--------------------------------------------------------------------------

|

| CheckoutController temporary signed URL generate karega.

| Signed middleware ke bina order success page access nahi hoga.

|

*/

Route::get(

    '/order-success/{orderNumber}',

    [CheckoutController::class, 'success']

)

    ->middleware('signed')

    ->name('order.success');

/*

|--------------------------------------------------------------------------

| CUSTOMER AUTHENTICATION

|--------------------------------------------------------------------------

|

| Ye routes sirf logged-out customers ke liye hain.

|

*/

Route::middleware('guest')

    ->group(function () {

        /*

        |--------------------------------------------------------------------------

        | LOGIN

        |--------------------------------------------------------------------------

        */

        Route::get(

            '/login',

            [CustomerAuthController::class, 'showLogin']

        )->name('login');

        Route::post(

            '/login',

            [CustomerAuthController::class, 'login']

        )->name('login.store');

        /*

        |--------------------------------------------------------------------------

        | REGISTER

        |--------------------------------------------------------------------------

        */

        Route::get(

            '/register',

            [CustomerAuthController::class, 'showRegister']

        )->name('register');

        Route::post(

            '/register',

            [CustomerAuthController::class, 'register']

        )->name('register.store');

        /*

        |--------------------------------------------------------------------------

        | FORGOT PASSWORD

        |--------------------------------------------------------------------------

        */

        Route::get(

            '/forgot-password',

            [CustomerAuthController::class, 'showForgotPassword']

        )->name('password.request');

        Route::post(

            '/forgot-password',

            [CustomerAuthController::class, 'sendResetLink']

        )

            ->middleware('throttle:5,1')

            ->name('password.email');

        /*

        |--------------------------------------------------------------------------

        | RESET PASSWORD

        |--------------------------------------------------------------------------

        */

        Route::get(

            '/reset-password/{token}',

            [CustomerAuthController::class, 'showResetPassword']

        )->name('password.reset');

        Route::post(

            '/reset-password',

            [CustomerAuthController::class, 'resetPassword']

        )->name('password.update');

    });

/*

|--------------------------------------------------------------------------

| CUSTOMER EMAIL VERIFICATION

|--------------------------------------------------------------------------

|

| Customer login hone ke baad email verification page access kar sakta hai.

| Shop, Cart aur Checkout verification ke bina bhi work karenge.

|

*/

Route::middleware('auth')

    ->group(function () {

        /*

        |--------------------------------------------------------------------------

        | VERIFICATION NOTICE

        |--------------------------------------------------------------------------

        */

        Route::get(

            '/email/verify',

            [EmailVerificationController::class, 'notice']

        )->name('verification.notice');

        /*

        |--------------------------------------------------------------------------

        | VERIFY EMAIL

        |--------------------------------------------------------------------------

        |

        | Verification URL signed hoga.

        |

        */

        Route::get(

            '/email/verify/{id}/{hash}',

            [EmailVerificationController::class, 'verify']

        )

            ->middleware('signed')

            ->name('verification.verify');

        /*

        |--------------------------------------------------------------------------

        | RESEND VERIFICATION EMAIL

        |--------------------------------------------------------------------------

        */

        Route::post(

            '/email/verification-notification',

            [EmailVerificationController::class, 'resend']

        )

            ->middleware('throttle:6,1')

            ->name('verification.send');

            Route::get(

    '/account/wishlist',

    [WishlistController::class, 'index']

)->name('wishlist.index');

Route::post(

    '/account/wishlist/{slug}/toggle',

    [WishlistController::class, 'toggle']

)->name('wishlist.toggle');

Route::delete(

    '/account/wishlist/{slug}',

    [WishlistController::class, 'remove']

)->name('wishlist.remove');

    });

/*

|--------------------------------------------------------------------------

| CUSTOMER ACCOUNT

|--------------------------------------------------------------------------

|

| Sirf authenticated customer apna account aur apne orders

| access kar sakta hai.

|

*/

Route::middleware('auth')

    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | SAVED CUSTOMER ADDRESSES
        |--------------------------------------------------------------------------
        */

        Route::prefix('account/addresses')
            ->name('account.addresses.')
            ->group(function () {
                Route::get('/', [AddressController::class, 'index'])
                    ->name('index');

                Route::post('/', [AddressController::class, 'store'])
                    ->name('store');

                Route::put('/{address}', [AddressController::class, 'update'])
                    ->name('update');

                Route::delete('/{address}', [AddressController::class, 'destroy'])
                    ->name('destroy');

                Route::patch('/{address}/default', [AddressController::class, 'makeDefault'])
                    ->name('default');
            });

        /*

        |--------------------------------------------------------------------------

        | ACCOUNT DASHBOARD

        |--------------------------------------------------------------------------

        */

        Route::get(

            '/account',

            [AccountController::class, 'dashboard']

        )->name('account.dashboard');

        /*

        |--------------------------------------------------------------------------

        | MY ORDERS

        |--------------------------------------------------------------------------

        */

        Route::get(

            '/account/orders',

            [AccountController::class, 'orders']

        )->name('account.orders');

        /*

        |--------------------------------------------------------------------------

        | ORDER DETAILS

        |--------------------------------------------------------------------------

        |

        | Controller me user_id ke through ownership verify hogi.

        |

        */

        Route::get(

            '/account/orders/{orderNumber}',

            [AccountController::class, 'orderShow']

        )->name('account.orders.show');

        /*

        |--------------------------------------------------------------------------

        | ACCOUNT DETAILS / PROFILE

        |--------------------------------------------------------------------------

        */

        Route::get(

            '/account/profile',

            [AccountController::class, 'profile']

        )->name('account.profile');

        Route::put(

            '/account/profile',

            [AccountController::class, 'updateProfile']

        )->name('account.profile.update');

        /*

        |--------------------------------------------------------------------------

        | CHANGE PASSWORD

        |--------------------------------------------------------------------------

        */

        Route::put(

            '/account/password',

            [AccountController::class, 'updatePassword']

        )->name('account.password.update');

        /*

        |--------------------------------------------------------------------------

        | CUSTOMER LOGOUT

        |--------------------------------------------------------------------------

        */

        Route::post(

            '/logout',

            [CustomerAuthController::class, 'logout']

        )->name('logout');

    });

/*

|--------------------------------------------------------------------------

| ADMIN

|--------------------------------------------------------------------------

|

| Customer authentication aur Admin authentication completely

| separate rahenge.

|

*/

Route::prefix('admin')

    ->name('admin.')

    ->group(function () {

        /*

        |--------------------------------------------------------------------------

        | ADMIN GUEST ROUTES

        |--------------------------------------------------------------------------

        |

        | Sirf logged-out admins login page access karenge.

        |

        */

        Route::middleware('guest:admin')

            ->group(function () {

                Route::get(

                    '/login',

                    [AuthController::class, 'showLogin']

                )->name('login');

                Route::post(

                    '/login',

                    [AuthController::class, 'login']

                )->name('login.submit');

            });

        /*

        |--------------------------------------------------------------------------

        | PROTECTED ADMIN ROUTES

        |--------------------------------------------------------------------------

        |

        | Sirf authenticated admin access kar sakta hai.

        |

        */

        Route::middleware('auth:admin')

            ->group(function () {

                /*

                |--------------------------------------------------------------------------

                | ADMIN DASHBOARD

                |--------------------------------------------------------------------------

                */

                Route::get('/', function () {

                    return redirect()

                        ->route('admin.orders.index');

                })->name('dashboard');

                /*

                |--------------------------------------------------------------------------

                | ADMIN ORDERS LIST

                |--------------------------------------------------------------------------

                */

                Route::get(

                    '/orders',

                    [OrderController::class, 'index']

                )->name('orders.index');

                /*

                |--------------------------------------------------------------------------

                | ADMIN ORDER DETAILS

                |--------------------------------------------------------------------------

                */

                Route::get(

                    '/orders/{order}',

                    [OrderController::class, 'show']

                )->name('orders.show');

                /*

                |--------------------------------------------------------------------------

                | ADMIN UPDATE ORDER STATUS

                |--------------------------------------------------------------------------

                */

                Route::patch(
    '/orders/{order}/status',
    [OrderController::class, 'updateStatus']
)->middleware('throttle:10,1')
  ->name('orders.status');

                /*
|--------------------------------------------------------------------------
| ADMIN MARK COD PAYMENT AS PAID
|--------------------------------------------------------------------------
*/

Route::patch(
    '/orders/{order}/payment',
    [OrderController::class, 'markAsPaid']
)
    ->middleware('throttle:10,1')
    ->name('orders.payment');

                Route::get(

    '/reviews',

    [AdminProductReviewController::class, 'index']

)->name('reviews.index');

Route::get(

    '/reviews/{review}',

    [AdminProductReviewController::class, 'show']

)->name('reviews.show');

Route::delete(

    '/reviews/{review}',

    [AdminProductReviewController::class, 'destroy']

)->name('reviews.destroy');

Route::get(

    '/inventory',

    [ProductInventoryController::class, 'index']

)->name('inventory.index');

Route::put(

    '/inventory/{slug}',

    [ProductInventoryController::class, 'update']

)->name('inventory.update');

/*

|--------------------------------------------------------------------------

| ADMIN EMAIL NOTIFICATIONS

|--------------------------------------------------------------------------

*/

Route::get(

    '/email-notifications',

    [EmailNotificationController::class, 'index']

)->name('email-notifications.index');

Route::get(

    '/email-notifications/{notification}',

    [EmailNotificationController::class, 'show']

)->name('email-notifications.show');

Route::post(

    '/email-notifications/{notification}/retry',

    [EmailNotificationController::class, 'retry']

)

    ->middleware('throttle:10,1')

    ->name('email-notifications.retry');

Route::post(

    '/email-notifications/{notification}/recover',

    [EmailNotificationController::class, 'recoverProcessing']

)

    ->middleware('throttle:5,1')

    ->name('email-notifications.recover');

    Route::post(

    '/email-notifications/{notification}/recover-uncertain',

    [EmailNotificationController::class, 'recoverUncertain']

)

    ->middleware('throttle:5,1')

    ->name('email-notifications.recover-uncertain');

    /*

|--------------------------------------------------------------------------

| ADMIN PRODUCT MANAGEMENT

|--------------------------------------------------------------------------

*/

Route::get(

    '/products',

    [AdminProductController::class, 'index']

)->name('products.index');

Route::get(

    '/products/create',

    [AdminProductController::class, 'create']

)->name('products.create');

Route::post(

    '/products',

    [AdminProductController::class, 'store']

)->name('products.store');

Route::get(

    '/products/{product}/edit',

    [AdminProductController::class, 'edit']

)->name('products.edit');

Route::put(

    '/products/{product}',

    [AdminProductController::class, 'update']

)->name('products.update');

Route::patch(

    '/products/{product}/toggle-status',

    [AdminProductController::class, 'toggleStatus']

)->name('products.toggle-status');



/*
|--------------------------------------------------------------------------
| ADMIN RETAILER MANAGEMENT
|--------------------------------------------------------------------------
|
| Only authenticated admins can review retailer applications.
|
*/

Route::get(
    '/retailers',
    [AdminRetailerController::class, 'index']
)->name('retailers.index');

Route::get(
    '/retailers/{retailerProfile}',
    [AdminRetailerController::class, 'show']
)->name('retailers.show');

Route::patch(
    '/retailers/{retailerProfile}/approve',
    [AdminRetailerController::class, 'approve']
)
    ->middleware('throttle:10,1')
    ->name('retailers.approve');

Route::patch(
    '/retailers/{retailerProfile}/reject',
    [AdminRetailerController::class, 'reject']
)
    ->middleware('throttle:10,1')
    ->name('retailers.reject');

    
/*
|--------------------------------------------------------------------------
| ADMIN CONTACT ENQUIRIES
|--------------------------------------------------------------------------
*/

Route::get(
    '/contact-enquiries',
    [AdminContactEnquiryController::class, 'index']
)->name('contact-enquiries.index');

Route::get(
    '/contact-enquiries/{enquiry}',
    [AdminContactEnquiryController::class, 'show']
)->name('contact-enquiries.show');

Route::patch(
    '/contact-enquiries/{enquiry}/read',
    [AdminContactEnquiryController::class, 'markRead']
)->name('contact-enquiries.read');

Route::patch(
    '/contact-enquiries/{enquiry}/unread',
    [AdminContactEnquiryController::class, 'markUnread']
)->name('contact-enquiries.unread');



/*
|--------------------------------------------------------------------------
| ADMIN CATEGORY MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::get('/categories', [AdminCategoryController::class, 'index'])
    ->name('categories.index');

Route::get('/categories/create', [AdminCategoryController::class, 'create'])
    ->name('categories.create');

Route::post('/categories', [AdminCategoryController::class, 'store'])
    ->name('categories.store');

Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])
    ->name('categories.edit');

Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])
    ->name('categories.update');


Route::patch(
    '/products/{product}/archive',
    [AdminProductController::class, 'archive']
)->name('products.archive');

Route::patch(
    '/products/{id}/restore',
    [AdminProductController::class, 'restore']
)->name('products.restore');


                /*

                |--------------------------------------------------------------------------

                | ADMIN LOGOUT

                |--------------------------------------------------------------------------

                */

                Route::post(

                    '/logout',

                    [AuthController::class, 'logout']

                )->name('logout');

            });

    });



    /*
|--------------------------------------------------------------------------
| B2B Retailer Registration
|--------------------------------------------------------------------------
|
| Existing customers can apply for a retailer account.
| Wholesale access requires admin approval.
|
*/

Route::middleware(['auth', 'verified'])
    ->prefix('retailer')
    ->name('retailer.')
    ->group(function () {

        Route::get('/register', [
            RetailerRegistrationController::class,
            'create'
        ])->name('register');

        Route::post('/register', [
            RetailerRegistrationController::class,
            'store'
        ])->name('store');

    });
