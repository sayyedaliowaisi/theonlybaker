<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductReviewController extends Controller
{
    public function index(Request $request): View
    {
        $products = config('products', []);

        /*
        |--------------------------------------------------------------------------
        | Reviews Query
        |--------------------------------------------------------------------------
        */

        $query = ProductReview::query()
            ->with('user:id,name,email,phone');


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                (string) $request->input('search')
            );

            $matchingSlugs = collect($products)
                ->filter(function ($product, $slug) use ($search) {

                    $productName =
                        $product['name'] ?? '';

                    return str_contains(
                        mb_strtolower($productName),
                        mb_strtolower($search)
                    )
                    ||
                    str_contains(
                        mb_strtolower($slug),
                        mb_strtolower($search)
                    );

                })
                ->keys()
                ->all();


            $query->where(function ($builder) use (
                $search,
                $matchingSlugs
            ) {

                $builder->where(
                    'review',
                    'like',
                    '%' . $search . '%'
                );


                $builder->orWhereHas(
                    'user',
                    function ($userQuery) use ($search) {

                        $userQuery
                            ->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'email',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'phone',
                                'like',
                                '%' . $search . '%'
                            );

                    }
                );


                if (!empty($matchingSlugs)) {

                    $builder->orWhereIn(
                        'product_slug',
                        $matchingSlugs
                    );

                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Rating Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('rating')) {

            $rating =
                (int) $request->input('rating');

            if ($rating >= 1 && $rating <= 5) {

                $query->where(
                    'rating',
                    $rating
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Sort
        |--------------------------------------------------------------------------
        */

        $sort = $request->input(
            'sort',
            'newest'
        );


        switch ($sort) {

            case 'oldest':

                $query->oldest();

                break;


            case 'highest':

                $query
                    ->orderByDesc('rating')
                    ->latest();

                break;


            case 'lowest':

                $query
                    ->orderBy('rating')
                    ->latest();

                break;


            default:

                $query->latest();

                break;

        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $reviews = $query
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Product + Verified Purchase Data
        |--------------------------------------------------------------------------
        */

        $reviews->getCollection()
            ->transform(function ($review) use ($products) {

                $review->product_data =
                    $products[
                        $review->product_slug
                    ] ?? null;


                $review->verified_purchase =
                    $review->isVerifiedPurchase();


                return $review;

            });


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalReviews =
            ProductReview::count();


        $averageRating =
            $totalReviews > 0
                ? round(
                    (float) ProductReview::avg('rating'),
                    1
                )
                : 0;


        $verifiedReviews = 0;

        if ($totalReviews > 0) {

            /*
             * Count reviews where the same user has a
             * delivered order containing the reviewed product.
             *
             * This is calculated from real order data rather
             * than trusting a review-side boolean.
             */

            ProductReview::query()
                ->select([
                    'id',
                    'user_id',
                    'product_slug',
                ])
                ->chunkById(
                    200,
                    function ($reviewsChunk) use (
                        &$verifiedReviews
                    ) {

                        foreach ($reviewsChunk as $review) {

                            if (
                                $review->isVerifiedPurchase()
                            ) {

                                $verifiedReviews++;

                            }

                        }

                    }
                );

        }


        $stats = [

            'total' =>
                $totalReviews,

            'average' =>
                $averageRating,

            'five_star' =>
                ProductReview::where(
                    'rating',
                    5
                )->count(),

            'verified' =>
                $verifiedReviews,

        ];


        return view(
            'admin.reviews.index',
            compact(
                'reviews',
                'stats'
            )
        );
    }


    public function show(
        ProductReview $review
    ): View {

        $products =
            config('products', []);


        $review->load(
            'user:id,name,email,phone'
        );


        $product =
            $products[
                $review->product_slug
            ] ?? null;


        /*
        |--------------------------------------------------------------------------
        | Verified Purchase
        |--------------------------------------------------------------------------
        */

        $verifiedPurchase =
            $review->isVerifiedPurchase();


        /*
        |--------------------------------------------------------------------------
        | Matching Delivered Order
        |--------------------------------------------------------------------------
        */

        $verifiedOrder = null;


        if ($verifiedPurchase) {

            $verifiedOrder = Order::query()
                ->where(
                    'user_id',
                    $review->user_id
                )
                ->where(
                    'order_status',
                    'delivered'
                )
                ->whereHas(
                    'items',
                    function ($query) use ($review) {

                        $query->where(
                            'product_slug',
                            $review->product_slug
                        );

                    }
                )
                ->latest()
                ->first();

        }


        return view(
            'admin.reviews.show',
            compact(
                'review',
                'product',
                'verifiedPurchase',
                'verifiedOrder'
            )
        );
    }


    public function destroy(
        ProductReview $review
    ): RedirectResponse {

        $review->delete();


        return redirect()
            ->route(
                'admin.reviews.index'
            )
            ->with(
                'success',
                'Review deleted successfully.'
            );
    }
}