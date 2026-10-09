@extends('layouts.admin')

@section(
    'title',
    'Review #' . $review->id . ' | The Only Baker Admin'
)


@section('content')

<div class="tob-admin-order-heading">

    <div>

        <a
            href="{{ route('admin.reviews.index') }}"
            class="tob-admin-back"
        >
            ← Back to Reviews
        </a>


        <span>
            REVIEW DETAILS
        </span>


        <h1>
            Review #{{ $review->id }}
        </h1>


        <p>

            Submitted on

            {{ $review->created_at
                ->format('d M Y, h:i A')
            }}

        </p>

    </div>


    <div class="tob-admin-review-large-rating">

        <span>
            {{ $review->rating }}
        </span>

        <small>
            / 5
        </small>

    </div>

</div>


<div class="tob-admin-review-layout">

    {{-- =================================================
         LEFT
    ================================================== --}}

    <div class="tob-admin-review-main">

        {{-- REVIEW --}}
        <section class="tob-admin-card">

            <div class="tob-admin-card-heading">

                <h2>
                    Customer Review
                </h2>


                <div class="tob-admin-review-stars">

                    @for($star = 1; $star <= 5; $star++)

                        <span
                            class="{{ $star <= $review->rating ? 'active' : '' }}"
                        >
                            ★
                        </span>

                    @endfor

                </div>

            </div>


            <div class="tob-admin-review-status-row">

                @if($verifiedPurchase)

                    <span class="tob-admin-verified-badge">
                        ✓ Verified Purchase
                    </span>

                @else

                    <span class="tob-admin-unverified-badge">
                        Not a Verified Purchase
                    </span>

                @endif

            </div>


            <div class="tob-admin-review-full">

                @if($review->review)

                    <p>
                        {{ $review->review }}
                    </p>

                @else

                    <p class="tob-admin-review-empty-text">

                        This customer submitted a rating
                        without a written review.

                    </p>

                @endif

            </div>

        </section>


        {{-- PRODUCT --}}
        <section class="tob-admin-card">

            <div class="tob-admin-card-heading">

                <h2>
                    Product
                </h2>

            </div>


            <div class="tob-admin-review-product">

                @if(
                    $product &&
                    !empty($product['image'])
                )

                    <div class="tob-admin-review-product-image">

                        <img
                            src="{{ asset($product['image']) }}"
                            alt="{{ $product['name'] }}"
                        >

                    </div>

                @endif


                <div>

                    <span>
                        PRODUCT
                    </span>


                    <strong>

                        {{ $product['name']
                            ?? $review->product_slug
                        }}

                    </strong>


                    @if($product)

                        <small>

                            ₹{{ number_format(
                                $product['price'],
                                0
                            ) }}

                        </small>

                    @endif


                    @if(
                        array_key_exists(
                            $review->product_slug,
                            config('products', [])
                        )
                    )

                        <a
                            href="{{ route(
                                'product.show',
                                $review->product_slug
                            ) }}"
                            target="_blank"
                            rel="noopener"
                        >
                            View Product →
                        </a>

                    @endif

                </div>

            </div>

        </section>


        {{-- VERIFIED ORDER --}}
        @if(
            $verifiedPurchase &&
            $verifiedOrder
        )

            <section class="tob-admin-card">

                <div class="tob-admin-card-heading">

                    <h2>
                        Verified Order
                    </h2>

                </div>


                <div class="tob-admin-info-grid">

                    <div>

                        <span>
                            Order Number
                        </span>

                        <strong>
                            {{ $verifiedOrder->order_number }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Order Status
                        </span>

                        <strong>
                            Delivered
                        </strong>

                    </div>


                    <div>

                        <span>
                            Order Date
                        </span>

                        <strong>

                            {{ $verifiedOrder->created_at
                                ->format('d M Y')
                            }}

                        </strong>

                    </div>


                    <div>

                        <span>
                            Order Total
                        </span>

                        <strong>

                            ₹{{ number_format(
                                $verifiedOrder->total,
                                0
                            ) }}

                        </strong>

                    </div>

                </div>


                <div
                    style="
                        margin-top:18px;
                    "
                >

                    <a
                        href="{{ route(
                            'admin.orders.show',
                            $verifiedOrder
                        ) }}"
                        class="tob-view-order"
                    >
                        View Verified Order →
                    </a>

                </div>

            </section>

        @endif

    </div>


    {{-- =================================================
         RIGHT
    ================================================== --}}

    <aside class="tob-admin-review-side">

        {{-- CUSTOMER --}}
        <section class="tob-admin-card">

            <div class="tob-admin-card-heading">

                <h2>
                    Customer
                </h2>

            </div>


            <div class="tob-admin-payment-info">

                <span>
                    Name
                </span>

                <strong>

                    {{ $review->user->name
                        ?? 'Customer'
                    }}

                </strong>


                <span>
                    Email
                </span>

                <strong>

                    {{ $review->user->email
                        ?? '—'
                    }}

                </strong>


                <span>
                    Phone
                </span>

                <strong>

                    {{ $review->user->phone
                        ?? '—'
                    }}

                </strong>


                <span>
                    Rating
                </span>

                <strong>
                    {{ $review->rating }} / 5
                </strong>

            </div>

        </section>


        {{-- INFORMATION --}}
        <section class="tob-admin-card">

            <div class="tob-admin-card-heading">

                <h2>
                    Review Information
                </h2>

            </div>


            <div class="tob-admin-payment-info">

                <span>
                    Review ID
                </span>

                <strong>
                    #{{ $review->id }}
                </strong>


                <span>
                    Product Slug
                </span>

                <strong>
                    {{ $review->product_slug }}
                </strong>


                <span>
                    Purchase Status
                </span>

                <strong>

                    {{ $verifiedPurchase
                        ? 'Verified Purchase'
                        : 'Not Verified'
                    }}

                </strong>


                <span>
                    Submitted
                </span>

                <strong>

                    {{ $review->created_at
                        ->format('d M Y')
                    }}

                </strong>


                @if(
                    !$review->created_at->equalTo(
                        $review->updated_at
                    )
                )

                    <span>
                        Last Updated
                    </span>

                    <strong>

                        {{ $review->updated_at
                            ->format('d M Y, h:i A')
                        }}

                    </strong>

                @endif

            </div>

        </section>


        {{-- DELETE --}}
        <section class="tob-admin-card">

            <div class="tob-admin-card-heading">

                <h2>
                    Review Actions
                </h2>

            </div>


            <p class="tob-admin-review-delete-text">

                Deleting this review will permanently
                remove it from the product page.

            </p>


            <form
                method="POST"
                action="{{ route(
                    'admin.reviews.destroy',
                    $review
                ) }}"
                onsubmit="
                    return confirm(
                        'Are you sure you want to permanently delete this review?'
                    );
                "
            >

                @csrf

                @method('DELETE')


                <button
                    type="submit"
                    class="tob-admin-delete-review"
                >
                    Delete Review
                </button>

            </form>

        </section>

    </aside>

</div>

@endsection


@push('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/admin-reviews.css') }}"
>

@endpush