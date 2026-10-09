@extends('layouts.admin')


@section('title', 'Inventory | Admin | The Only Baker')


@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/admin-inventory.css') }}"
    >
@endpush


@section('content')

<div class="tob-admin-page-heading">

    <div>

        <span>
            STORE MANAGEMENT
        </span>

        <h1>
            Inventory
        </h1>

        <p>
            Manage product stock and low stock levels.
        </p>

    </div>

</div>


{{-- =========================================================
     STATS
========================================================= --}}

<div class="tob-admin-stats">

    <div class="tob-admin-stat">

        <span>
            Products
        </span>

        <strong>
            {{ $stats['products'] }}
        </strong>

    </div>


    <div class="tob-admin-stat">

        <span>
            In Stock
        </span>

        <strong>
            {{ $stats['in_stock'] }}
        </strong>

    </div>


    <div class="tob-admin-stat">

        <span>
            Low Stock
        </span>

        <strong>
            {{ $stats['low_stock'] }}
        </strong>

    </div>


    <div class="tob-admin-stat">

        <span>
            Out of Stock
        </span>

        <strong>
            {{ $stats['out_of_stock'] }}
        </strong>

    </div>

</div>


{{-- =========================================================
     SEARCH
========================================================= --}}

<div class="tob-admin-card tob-admin-inventory-filter">

    <form
        method="GET"
        action="{{ route('admin.inventory.index') }}"
        class="tob-inventory-search-form"
    >

        <input
            type="search"
            name="search"
            value="{{ $search }}"
            placeholder="Search products..."
        >

        <button type="submit">
            Search
        </button>


        @if($search !== '')

            <a href="{{ route('admin.inventory.index') }}">
                Reset
            </a>

        @endif

    </form>

</div>


{{-- =========================================================
     PRODUCTS
========================================================= --}}

<div class="tob-admin-card">

    <div class="tob-inventory-table-wrap">

        <table class="tob-inventory-table">

            <thead>

                <tr>

                    <th>
                        Product
                    </th>

                    <th>
                        Stock
                    </th>

                    <th>
                        Low Stock At
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Action
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($products as $slug => $product)

                    @php

                        $inventory =
                            $inventories->get($slug);

                        $stock =
                            $inventory?->stock ?? 0;

                        $threshold =
                            $inventory?->low_stock_threshold ?? 5;


                        if ($stock <= 0) {

                            $stockStatus =
                                'out';

                            $stockLabel =
                                'Out of Stock';

                        } elseif ($stock <= $threshold) {

                            $stockStatus =
                                'low';

                            $stockLabel =
                                'Low Stock';

                        } else {

                            $stockStatus =
                                'available';

                            $stockLabel =
                                'In Stock';
                        }

                    @endphp


                    <tr>

                        {{-- PRODUCT --}}
                        <td>

                            <div class="tob-inventory-product">

                                <img
                                    src="{{ asset($product['image']) }}"
                                    alt="{{ $product['name'] }}"
                                >

                                <div>

                                    <strong>
                                        {{ $product['name'] }}
                                    </strong>

                                    <span>
                                        {{ $slug }}
                                    </span>

                                </div>

                            </div>

                        </td>


                        {{-- UPDATE FORM --}}
                        <td colspan="4">

                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.inventory.update',
                                    $slug
                                ) }}"
                                class="tob-inventory-update-form"
                            >

                                @csrf
                                @method('PUT')


                                <div class="tob-inventory-field">

                                    <label
                                        for="stock-{{ $slug }}"
                                        class="tob-mobile-field-label"
                                    >
                                        Stock
                                    </label>

                                    <input
                                        id="stock-{{ $slug }}"
                                        type="number"
                                        name="stock"
                                        min="0"
                                        max="1000000"
                                        value="{{ $stock }}"
                                        required
                                    >

                                </div>


                                <div class="tob-inventory-field">

                                    <label
                                        for="threshold-{{ $slug }}"
                                        class="tob-mobile-field-label"
                                    >
                                        Low Stock At
                                    </label>

                                    <input
                                        id="threshold-{{ $slug }}"
                                        type="number"
                                        name="low_stock_threshold"
                                        min="0"
                                        max="1000000"
                                        value="{{ $threshold }}"
                                        required
                                    >

                                </div>


                                <div>

                                    <span
                                        class="
                                            tob-inventory-status
                                            tob-inventory-status-{{ $stockStatus }}
                                        "
                                    >
                                        {{ $stockLabel }}
                                    </span>

                                </div>


                                <div>

                                    <button type="submit">
                                        Update
                                    </button>

                                </div>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="tob-inventory-empty"
                        >
                            No products found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection