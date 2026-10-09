<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductReviewController extends Controller
{
    public function store(
        Request $request,
        string $slug
    ): RedirectResponse {
        $products = config('products', []);

        abort_unless(
            isset($products[$slug]),
            404
        );

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

        ProductReview::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'product_slug' => $slug,
            ],
            [
                'rating' => $validated['rating'],
                'review' => trim(
                    $validated['review'] ?? ''
                ) ?: null,
            ]
        );

        return redirect()
            ->route('product.show', $slug)
            ->with(
                'review_success',
                'Thank you! Your review has been saved.'
            )
            ->withFragment('reviews');
    }

    public function destroy(
        Request $request,
        string $slug
    ): RedirectResponse {
        $review = ProductReview::query()
            ->where('user_id', $request->user()->id)
            ->where('product_slug', $slug)
            ->firstOrFail();

        $review->delete();

        return redirect()
            ->route('product.show', $slug)
            ->with(
                'review_success',
                'Your review has been removed.'
            )
            ->withFragment('reviews');
    }
}