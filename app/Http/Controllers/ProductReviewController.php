<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductReviewController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STORE OR UPDATE REVIEW
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        string $slug
    ): RedirectResponse {

        // Only active database products can receive reviews.
        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $validated = $request->validate([
            'rating' => [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4, 5]),
            ],

            'review' => [
                'nullable',
                'string',
                'max:1500',
            ],
        ]);

        ProductReview::query()->updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'product_slug' => $product->slug,
            ],
            [
                'rating' => $validated['rating'],
                'review' => trim(
                    $validated['review'] ?? ''
                ) ?: null,
            ]
        );

        return redirect()
            ->route('product.show', $product->slug)
            ->with(
                'review_success',
                'Thank you! Your review has been saved.'
            )
            ->withFragment('reviews');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE OWN REVIEW
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Request $request,
        string $slug
    ): RedirectResponse {

        $review = ProductReview::query()
            ->where('user_id', $request->user()->id)
            ->where('product_slug', $slug)
            ->firstOrFail();

        $review->delete();

        // Product may be inactive or removed from public catalog.
        // Redirect safely without requiring product visibility.
        $productIsActive = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->exists();

        if (!$productIsActive) {
            return redirect()
                ->route('account.dashboard')
                ->with(
                    'review_success',
                    'Your review has been removed.'
                );
        }

        return redirect()
            ->route('product.show', $slug)
            ->with(
                'review_success',
                'Your review has been removed.'
            )
            ->withFragment('reviews');
    }
}
