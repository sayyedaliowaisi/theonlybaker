<?php



use Illuminate\Support\Facades\Route;



use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartStockController;



use App\Http\Controllers\Admin\AuthController;

use App\Http\Controllers\Admin\OrderController;



use App\Http\Controllers\Customer\AuthController as CustomerAuthController;

use App\Http\Controllers\Customer\AccountController;

use App\Http\Controllers\Customer\EmailVerificationController;

use App\Http\Controllers\Customer\WishlistController;

use App\Http\Controllers\ProductReviewController;

use App\Models\ProductReview;

use App\Http\Controllers\Admin\ProductReviewController as AdminProductReviewController;

use App\Http\Controllers\Admin\ProductInventoryController;

use App\Http\Controllers\Admin\EmailNotificationController;





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

| All products config/products.php se load honge.

|

*/



Route::get('/shop', function () {

    $products = config('products', []);

    $inventoryStocks = \App\Models\ProductInventory::query()
        ->pluck('stock', 'product_slug');

    foreach ($products as $slug => &$product) {

        $product['slug'] = $slug;

        $product['stock'] = max(
            0,
            (int) ($inventoryStocks[$slug] ?? 0)
        );
    }

    unset($product);

    return view(
        'pages.shop',
        compact('products')
    );

})->name('shop');



/*

|--------------------------------------------------------------------------

| PRODUCT DETAILS

|--------------------------------------------------------------------------

*/



Route::get('/product/{slug}', function (string $slug) {

    $products = config('products', []);

    abort_unless(
        isset($products[$slug]),
        404
    );

    $product = $products[$slug];
    $product['slug'] = $slug;

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

    return view(
        'pages.product',
        compact(
            'product',
            'reviews',
            'reviewCount',
            'averageRating',
            'ratingBreakdown',
            'userReview',
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

| CHECKOUT

|--------------------------------------------------------------------------

|

| Guest aur logged-in customer dono checkout kar sakte hain.

|

*/



Route::get(

    '/checkout',

    [CheckoutController::class, 'index']

)->name('checkout');





Route::post(

    '/checkout',

    [CheckoutController::class, 'store']

)->name('checkout.store');





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

                )->name('orders.status');



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

                | ADMIN LOGOUT

                |--------------------------------------------------------------------------

                */



                Route::post(

                    '/logout',

                    [AuthController::class, 'logout']

                )->name('logout');



            });



    });