<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Services\ProductCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(
        Request $request,
        ProductCatalog $catalog
    ): View {
        $wishlistRows = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        $catalogProducts = $catalog->findMany(
            $wishlistRows->pluck('product_slug')->all()
        );

        $products = $wishlistRows
            ->map(
                fn (Wishlist $wishlist) =>
                    $catalogProducts[$wishlist->product_slug] ?? null
            )
            ->filter()
            ->values();

        return view(
            'customer.account.wishlist',
            compact('products')
        );
    }

    public function toggle(
        Request $request,
        string $slug,
        ProductCatalog $catalog
    ): JsonResponse {
        $userId = $request->user()->id;

        $existing = Wishlist::query()
            ->where('user_id', $userId)
            ->where('product_slug', $slug)
            ->first();

        if ($existing) {
            $existing->delete();

            $added = false;
            $message = 'Removed from wishlist.';
        } else {
            if ($catalog->find($slug) === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.',
                ], 404);
            }

            Wishlist::query()->firstOrCreate([
                'user_id' => $userId,
                'product_slug' => $slug,
            ]);

            $added = true;
            $message = 'Added to wishlist.';
        }

        $count = Wishlist::query()
            ->where('user_id', $userId)
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
