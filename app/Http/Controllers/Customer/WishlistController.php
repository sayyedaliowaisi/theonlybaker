<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $catalog = config('products', []);

        $wishlistRows = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        $products = $wishlistRows
            ->map(function (Wishlist $wishlist) use ($catalog) {

                if (!isset($catalog[$wishlist->product_slug])) {
                    return null;
                }

                $product = $catalog[$wishlist->product_slug];

                $product['slug'] = $wishlist->product_slug;

                return $product;
            })
            ->filter()
            ->values();

        return view(
            'customer.account.wishlist',
            compact('products')
        );
    }

    public function toggle(
        Request $request,
        string $slug
    ): JsonResponse {
        $catalog = config('products', []);

        if (!isset($catalog[$slug])) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        $existing = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->where('product_slug', $slug)
            ->first();

        if ($existing) {
            $existing->delete();

            $added = false;
            $message = 'Removed from wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $request->user()->id,
                'product_slug' => $slug,
            ]);

            $added = true;
            $message = 'Added to wishlist.';
        }

        $count = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->count();

        return response()->json([
            'success' => true,
            'added' => $added,
            'count' => $count,
            'message' => $message,
        ]);
    }

    public function remove(
        Request $request,
        string $slug
    ): JsonResponse {
        Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->where('product_slug', $slug)
            ->delete();

        $count = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => 'Removed from wishlist.',
        ]);
    }
}