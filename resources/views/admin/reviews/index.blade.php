@extends('layouts.admin')

@section('title', 'Reviews | The Only Baker Admin')


@section('content')

<div class="tob-admin-page-heading">

    <div>

        <span>
            STORE MANAGEMENT
        </span>

        <h1>
            Reviews
        </h1>

        <p>
            Manage customer product reviews and ratings.
        </p>

    </div>

</div>


{{-- =====================================================
     STATS
===================================================== --}}

<div class="tob-admin-stats">

    <div class="tob-admin-stat">

        <span>
            Total Reviews
        </span>

        <strong>
            {{ $stats['total'] }}
        </strong>

    </div>


    <div class="tob-admin-stat">

        <span>
            Average Rating
        </span>

        <strong>
            {{ number_format($stats['average'], 1) }}
        </strong>

    </div>


    <div class="tob-admin-stat">

        <span>
            5 Star Reviews
        </span>

        <strong>
            {{ $stats['five_star'] }}
        </strong>

    </div>


    <div class="tob-admin-stat">

        <span>
            Verified Purchases
        </span>

        <strong>
            {{ $stats['verified'] }}
        </strong>

    </div>

</div>


{{-- =====================================================
     FILTERS
===================================================== --}}

<div class="tob-admin-card tob-admin-filter-card">

    <form
        method="GET"
        action="{{ route('admin.reviews.index') }}"
        class="tob-admin-filters"
    >

        <div class="tob-admin-search">

            <input
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search product, customer, email or review..."
            >

        </div>


        <select name="rating">

            <option value="">
                All Ratings
            </option>

            @for($rating = 5; $rating >= 1; $rating--)

                <option
                    value="{{ $rating }}"
                    @selected(
                        (string) request('rating') ===
                        (string) $rating
                    )
                >
                    {{ $rating }} Star
                </option>

            @endfor

        </select>


        <select name="sort">

            <option
                value="newest"
                @selected(
                    request('sort', 'newest') ===
                    'newest'
                )
            >
                Newest First
            </option>

            <option
                value="oldest"
                @selected(
                    request('sort') ===
                    'oldest'
                )
            >
                Oldest First
            </option>

            <option
                value="highest"
                @selected(
                    request('sort') ===
                    'highest'
                )
            >
                Highest Rating
            </option>

            <option
                value="lowest"
                @selected(
                    request('sort') ===
                    'lowest'
                )
            >
                Lowest Rating
            </option>

        </select>


        <button type="submit">
            Filter
        </button>


        @if(
            request('search') ||
            request('rating') ||
            (
                request('sort') &&
                request('sort') !== 'newest'
            )
        )

            <a
                href="{{ route('admin.reviews.index') }}"
                class="tob-admin-reset"
            >
                Reset
            </a>

        @endif

    </form>

</div>


{{-- =====================================================
     REVIEWS
===================================================== --}}

<div class="tob-admin-card">

    <div class="tob-admin-table-wrap">

        <table class="tob-admin-table">

            <thead>

                <tr>

                    <th>Product</th>

                    <th>Customer</th>

                    <th>Rating</th>

                    <th>Purchase</th>

                    <th>Review</th>

                    <th>Date</th>

                    <th></th>

                </tr>

            </thead>


            <tbody>

                @forelse($reviews as $review)

                    <tr>

                        {{-- PRODUCT --}}
                        <td>

                            <strong class="tob-customer-name">

                                {{ $review->product_data['name']
                                    ?? $review->product_slug
                                }}

                            </strong>

                            <small>
                                {{ $review->product_slug }}
                            </small>

                        </td>


                        {{-- CUSTOMER --}}
                        <td>

                            <strong class="tob-customer-name">

                                {{ $review->user->name
                                    ?? 'Customer'
                                }}

                            </strong>

                            <small>

                                {{ $review->user->email
                                    ?? '—'
                                }}

                            </small>

                        </td>


                        {{-- RATING --}}
                        <td>

                            <span class="tob-admin-review-stars">

                                @for($star = 1; $star <= 5; $star++)

                                    <span
                                        class="{{ $star <= $review->rating ? 'active' : '' }}"
                                    >
                                        ★
                                    </span>

                                @endfor

                            </span>

                            <small>
                                {{ $review->rating }}/5
                            </small>

                        </td>


                        {{-- VERIFIED PURCHASE --}}
                        <td>

                            @if($review->verified_purchase)

                                <span class="tob-admin-verified-badge">
                                    ✓ Verified
                                </span>

                            @else

                                <span class="tob-admin-unverified-badge">
                                    Not Verified
                                </span>

                            @endif

                        </td>


                        {{-- REVIEW --}}
                        <td>

                            <span class="tob-admin-review-preview">

                                @if($review->review)

                                    {{ \Illuminate\Support\Str::limit(
                                        $review->review,
                                        80
                                    ) }}

                                @else

                                    Rating only

                                @endif

                            </span>

                        </td>


                        {{-- DATE --}}
                        <td>

                            <span class="tob-order-date">

                                {{ $review->created_at
                                    ->format('d M Y')
                                }}

                            </span>

                            <small>

                                {{ $review->created_at
                                    ->format('h:i A')
                                }}

                            </small>

                        </td>


                        {{-- ACTION --}}
                        <td>

                            <a
                                href="{{ route(
                                    'admin.reviews.show',
                                    $review
                                ) }}"
                                class="tob-view-order"
                            >
                                View →
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="tob-admin-empty"
                        >

                            <strong>
                                No reviews found
                            </strong>

                            <span>
                                Customer product reviews
                                will appear here.
                            </span>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    @if($reviews->hasPages())

        <div class="tob-admin-pagination">

            @if($reviews->onFirstPage())

                <span class="disabled">
                    ← Previous
                </span>

            @else

                <a href="{{ $reviews->previousPageUrl() }}">
                    ← Previous
                </a>

            @endif


            <span>

                Page {{ $reviews->currentPage() }}

                of {{ $reviews->lastPage() }}

            </span>


            @if($reviews->hasMorePages())

                <a href="{{ $reviews->nextPageUrl() }}">
                    Next →
                </a>

            @else

                <span class="disabled">
                    Next →
                </span>

            @endif

        </div>

    @endif

</div>

@endsection


@push('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/admin-reviews.css') }}"
>

@endpush