
@extends('layouts.admin')

@section('title', 'Product Management | The Only Baker')

@push('styles')
    <link
    rel="stylesheet"
    href="{{ asset('css/admin-products.css') }}?v={{ file_exists(public_path('css/admin-products.css')) ? filemtime(public_path('css/admin-products.css')) : '1' }}"
>
@endpush

@section('content')

<div class="tob-products-admin">

    {{-- PAGE HEADER --}}
    <div class="tob-products-heading">
        <div>
            <h1>Product Management</h1>

            <p>
                Manage products, retail prices, wholesale pricing,
                stock availability and archived products.
            </p>
        </div>

        <a
            class="tob-products-btn tob-products-btn-primary"
            href="{{ route('admin.products.create') }}"
        >
            + Add Product
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
        <div
            role="status"
            style="
                padding: 14px 18px;
                margin-bottom: 20px;
                background: #eaf7ee;
                border: 1px solid #b8e0c3;
                border-radius: 10px;
                color: #237747;
            "
        >
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if (session('error'))
        <div
            role="alert"
            style="
                padding: 14px 18px;
                margin-bottom: 20px;
                background: #fff0ed;
                border: 1px solid #f0c1b9;
                border-radius: 10px;
                color: #c0392b;
            "
        >
            {{ session('error') }}
        </div>
    @endif

    {{-- PRODUCT TABS --}}
    <div style="
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    ">
        <a
            href="{{ route('admin.products.index') }}"
            class="tob-products-btn {{
                $view === 'current'
                    ? 'tob-products-btn-primary'
                    : 'tob-products-btn-outline'
            }}"
        >
            All Products ({{ $currentCount }})
        </a>

        <a
            href="{{ route('admin.products.index', ['view' => 'archived']) }}"
            class="tob-products-btn {{
                $view === 'archived'
                    ? 'tob-products-btn-primary'
                    : 'tob-products-btn-outline'
            }}"
        >
            Archived Products ({{ $archivedCount }})
        </a>
    </div>

    {{-- FILTERS --}}
    <form
        method="GET"
        action="{{ route('admin.products.index') }}"
        class="tob-products-panel tob-products-filters"
    >
        <input type="hidden" name="view" value="{{ $view }}">

        <div class="tob-products-filter-grid">

            {{-- SEARCH --}}
            <div class="tob-products-field">
                <label for="product-search">
                    Search products
                </label>

                <input
                    id="product-search"
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Product name or slug"
                >
            </div>

            {{-- CATEGORY FILTER --}}
            <div class="tob-products-field">
                <label for="product-category">
                    Category
                </label>

                <select
                    id="product-category"
                    name="category"
                >
                    <option value="">All Categories</option>

                    @foreach ($categories as $category)
                        <option
                            value="{{ $category }}"
                            @selected(request('category') === $category)
                        >
                            {{ ucwords(str_replace('-', ' ', $category)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- STATUS FILTER --}}
            <div class="tob-products-field">
                <label for="product-status">
                    Status
                </label>

                <select
                    id="product-status"
                    name="status"
                >
                    <option value="">All Status</option>

                    <option
                        value="active"
                        @selected(request('status') === 'active')
                    >
                        Active
                    </option>

                    <option
                        value="inactive"
                        @selected(request('status') === 'inactive')
                    >
                        Inactive
                    </option>
                </select>
            </div>

            {{-- FILTER ACTIONS --}}
            <div class="tob-products-filter-actions">
                <button
                    type="submit"
                    class="tob-products-btn tob-products-btn-primary"
                >
                    Apply Filters
                </button>

                <a
                    href="{{ route(
                        'admin.products.index',
                        $view === 'archived'
                            ? ['view' => 'archived']
                            : []
                    ) }}"
                    class="tob-products-btn tob-products-btn-outline"
                >
                    Reset
                </a>
            </div>

        </div>
    </form>

    {{-- PRODUCTS TABLE --}}
    <div class="tob-products-panel tob-products-table-panel">

        <div class="tob-products-table-scroll">

            <table class="tob-products-table">

                <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Category</th>
                        <th scope="col">Retail Price</th>
                        <th scope="col">Wholesale / MOQ</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($products as $product)

                        @php
                            $imageUrl = $product->image
                                ? (
                                    str_starts_with(
                                        $product->image,
                                        'products/'
                                    )
                                        ? asset('storage/' . $product->image)
                                        : asset($product->image)
                                )
                                : null;

                            $stock = (int) (
                                $product->inventory?->stock ?? 0
                            );

                            $lowStockThreshold = (int) (
                                $product->inventory?->low_stock_threshold ?? 5
                            );
                        @endphp

                        <tr>

                            {{-- PRODUCT --}}
                            <td>
                                <div class="tob-products-product-cell">

                                    @if ($imageUrl)
                                        <img
                                            class="tob-products-thumb"
                                            src="{{ $imageUrl }}"
                                            alt="{{ $product->name }}"
                                            loading="lazy"
                                        >
                                    @else
                                        <div
                                            class="tob-products-thumb tob-products-thumb-empty"
                                            aria-hidden="true"
                                        >
                                            ◈
                                        </div>
                                    @endif

                                    <div class="tob-products-product-text">
                                        <strong>
                                            {{ $product->name }}
                                        </strong>

                                        <small>
                                            {{ $product->slug }}
                                        </small>

                                        @if ($product->trashed())
                                            <small style="color: #c0392b;">
                                                Archived:
                                                {{ $product->deleted_at?->format('d M Y, h:i A') }}
                                            </small>
                                        @endif
                                    </div>

                                </div>
                            </td>

                            {{-- CATEGORY --}}
                            <td>
                                {{ ucwords(str_replace('-', ' ', $product->category)) }}
                            </td>

                            {{-- RETAIL PRICE --}}
                            <td class="tob-products-price">
                                ₹{{ number_format((float) $product->price, 2) }}
                            </td>

                            {{-- WHOLESALE PRICE & MOQ --}}
                            <td>
                                @if ($product->hasWholesalePricing())

                                    <div style="
                                        display: flex;
                                        flex-direction: column;
                                        gap: 5px;
                                    ">
                                        <strong style="
                                            color: #237747;
                                            font-size: 14px;
                                        ">
                                            ₹{{ number_format((float) $product->wholesale_price, 2) }}
                                        </strong>

                                        <small style="color: #76665b;">
                                            MOQ:
                                            {{ $product->getWholesaleMinimumQuantity() }}
                                            units
                                        </small>
                                    </div>

                                @else

                                    <span style="
                                        color: #999;
                                        font-size: 13px;
                                    ">
                                        Not configured
                                    </span>

                                @endif
                            </td>

                            {{-- STOCK --}}
                            <td>
                                <span
                                    class="tob-products-stock {{
                                        $stock <= 0
                                            ? 'is-out'
                                            : (
                                                $stock <= $lowStockThreshold
                                                    ? 'is-low'
                                                    : 'is-available'
                                            )
                                    }}"
                                >
                                    {{ $stock }}
                                    {{ $stock <= 0 ? '(Out of stock)' : '' }}
                                </span>
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if ($product->trashed())

                                    <span
                                        class="tob-products-status is-inactive"
                                        style="
                                            background: #fff0ed;
                                            color: #c0392b;
                                        "
                                    >
                                        Archived
                                    </span>

                                @else

                                    <span
                                        class="tob-products-status {{
                                            $product->is_active
                                                ? 'is-active'
                                                : 'is-inactive'
                                        }}"
                                    >
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>

                                @endif
                            </td>

                            {{-- ACTIONS --}}
                            <td>
                                <div class="tob-products-actions">

                                    @if ($product->trashed())

                                        {{-- RESTORE PRODUCT --}}
                                        <form
                                            method="POST"
                                            action="{{ route('admin.products.restore', $product->id) }}"
                                            onsubmit="return confirm('Restore this product?')"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="tob-products-action-link"
                                                style="color: #237747;"
                                            >
                                                Restore
                                            </button>
                                        </form>

                                    @else

                                        {{-- EDIT PRODUCT --}}
                                        <a
                                            href="{{ route('admin.products.edit', $product) }}"
                                            class="tob-products-action-link"
                                        >
                                            Edit
                                        </a>

                                        {{-- ACTIVATE / DEACTIVATE --}}
                                        <form
                                            method="POST"
                                            action="{{ route('admin.products.toggle-status', $product) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="tob-products-action-link tob-products-toggle"
                                                onclick="return confirm(@js($product->is_active ? 'Deactivate this product?' : 'Activate this product?'))"
                                            >
                                                {{ $product->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>

                                        {{-- ARCHIVE PRODUCT --}}
                                        <form
                                            method="POST"
                                            action="{{ route('admin.products.archive', $product) }}"
                                            onsubmit="return confirm('Archive this product? You can restore it later.');"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="tob-products-action-link"
                                                style="color: #c0392b;"
                                            >
                                                Archive
                                            </button>
                                        </form>

                                    @endif

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="7"
                                class="tob-products-empty"
                            >
                                {{ $view === 'archived'
                                    ? 'No archived products found.'
                                    : 'No products found.'
                                }}
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

    {{-- PAGINATION --}}
    @if ($products->hasPages())

        <div class="tob-products-pagination">
            {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>

    @endif

</div>

@endsection
