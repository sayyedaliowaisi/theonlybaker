
@php
    $editing = isset($product);
    $imageUrl = null;

    if ($editing && $product->image) {
        $imageUrl = str_starts_with($product->image, 'products/')
            ? asset('storage/' . $product->image)
            : asset($product->image);
    }
@endphp

@if ($errors->any())
    <div class="tob-products-form-errors" role="alert">
        <strong>Please check the highlighted fields.</strong>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="tob-products-form-grid">

    {{-- PRODUCT NAME --}}
    <div class="tob-products-field tob-products-field-full">
        <label for="product-name">
            Product Name
            <span class="tob-products-required">*</span>
        </label>

        <input
            id="product-name"
            type="text"
            name="name"
            value="{{ old('name', $product->name ?? '') }}"
            maxlength="255"
            required
            @class([
                'tob-products-input-error' => $errors->has('name')
            ])
        >

        @error('name')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror
    </div>

    {{-- PRODUCT SLUG --}}
    <div class="tob-products-field tob-products-field-full">
        <label for="product-slug">
            Product Slug
            <span class="tob-products-required">*</span>
        </label>

        <input
            id="product-slug"
            type="text"
            name="slug"
            value="{{ old('slug', $product->slug ?? '') }}"
            maxlength="150"
            pattern="[a-z0-9]+(?:-[a-z0-9]+)*"
            {{ $editing ? 'readonly' : 'required' }}
            @class([
                'tob-products-input-error' => $errors->has('slug')
            ])
        >

        <small class="tob-products-help">
            Use lowercase letters, numbers and hyphens
            (example: almond-cookies).

            @if($editing)
                Existing product slugs cannot be changed.
            @endif
        </small>

        @error('slug')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror
    </div>

    {{-- DESCRIPTION --}}
    <div class="tob-products-field tob-products-field-full">
        <label for="product-description">
            Description
        </label>

        <textarea
            id="product-description"
            name="description"
            rows="5"
            @class([
                'tob-products-input-error' => $errors->has('description')
            ])
        >{{ old('description', $product->description ?? '') }}</textarea>

        @error('description')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror
    </div>

    {{-- RETAIL PRICE --}}
    <div class="tob-products-field">
        <label for="product-price">
            Retail Price (₹)
            <span class="tob-products-required">*</span>
        </label>

        <input
            id="product-price"
            type="number"
            name="price"
            step="0.01"
            min="0.01"
            max="99999999.99"
            value="{{ old('price', $product->price ?? '') }}"
            required
            @class([
                'tob-products-input-error' => $errors->has('price')
            ])
        >

        <small class="tob-products-help">
            Standard selling price for regular customers.
        </small>

        @error('price')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror
    </div>

    {{-- CATEGORY --}}
    <div class="tob-products-field">
        <label for="product-category">
            Category
            <span class="tob-products-required">*</span>
        </label>

        <select
            id="product-category"
            name="category"
            required
            @class([
                'tob-products-input-error' => $errors->has('category')
            ])
        >
            <option value="">Select Category</option>

            @foreach ([
                'dry-fruit' => 'Dry Fruit',
                'classic' => 'Classic',
                'chocolate' => 'Chocolate'
            ] as $value => $label)
                <option
                    value="{{ $value }}"
                    @selected(old('category', $product->category ?? '') === $value)
                >
                    {{ $label }}
                </option>
            @endforeach
        </select>

        @error('category')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror
    </div>

    {{-- WHOLESALE SECTION --}}
    <div class="tob-products-field tob-products-field-full">
        <div style="
            padding: 20px;
            background: #fff8ef;
            border: 1px solid #ead8c1;
            border-radius: 12px;
            margin-top: 10px;
        ">
            <h3 style="
                margin: 0 0 7px;
                font-size: 18px;
                font-weight: 700;
                color: #593728;
            ">
                Wholesale / B2B Pricing
            </h3>

            <p style="
                margin: 0;
                color: #76665b;
                font-size: 13px;
                line-height: 1.7;
            ">
                Set special prices for approved retailers.
                Leave wholesale price empty if this product
                is not available for wholesale orders.
            </p>
        </div>
    </div>

    {{-- WHOLESALE PRICE --}}
    <div class="tob-products-field">
        <label for="product-wholesale-price">
            Wholesale Price (₹)
        </label>

        <input
            id="product-wholesale-price"
            type="number"
            name="wholesale_price"
            step="0.01"
            min="0.01"
            max="99999999.99"
            value="{{ old('wholesale_price', $product->wholesale_price ?? '') }}"
            placeholder="e.g. 280.00"
            @class([
                'tob-products-input-error' => $errors->has('wholesale_price')
            ])
        >

        <small class="tob-products-help">
            Price per unit for approved retailers.
            Leave blank to disable wholesale pricing.
        </small>

        @error('wholesale_price')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror
    </div>

    {{-- WHOLESALE MOQ --}}
    <div class="tob-products-field">
        <label for="product-wholesale-moq">
            Minimum Wholesale Quantity (MOQ)
        </label>

        <input
            id="product-wholesale-moq"
            type="number"
            name="wholesale_moq"
            min="1"
            max="100000"
            step="1"
            value="{{ old('wholesale_moq', $product->wholesale_moq ?? 1) }}"
            required
            @class([
                'tob-products-input-error' => $errors->has('wholesale_moq')
            ])
        >

        <small class="tob-products-help">
            Minimum number of units required to qualify
            for wholesale pricing.
        </small>

        @error('wholesale_moq')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror
    </div>

    {{-- DISPLAY ORDER --}}
    <div class="tob-products-field tob-products-field-full">
        <label for="product-sort-order">
            Display Order
            <span class="tob-products-required">*</span>
        </label>

        <input
            id="product-sort-order"
            type="number"
            name="sort_order"
            min="0"
            value="{{ old('sort_order', $product->sort_order ?? 0) }}"
            required
            @class([
                'tob-products-input-error' => $errors->has('sort_order')
            ])
        >

        <small class="tob-products-help">
            Lower numbers appear first in the shop.
        </small>

        @error('sort_order')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror
    </div>

    {{-- PRODUCT IMAGE --}}
    <div class="tob-products-field tob-products-field-full">
        <label for="product-image">
            Product Image
        </label>

        @if ($imageUrl)
            <div class="tob-products-current-image">
                <img
                    src="{{ $imageUrl }}"
                    alt="{{ $product->name }}"
                >

                <span>Current product image</span>
            </div>
        @endif

        <input
            id="product-image"
            type="file"
            name="image"
            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
            @class([
                'tob-products-input-error' => $errors->has('image')
            ])
        >

        <small class="tob-products-help">
            JPG, PNG or WebP, up to 2 MB.

            @if($editing)
                Leave empty to keep the current image.
            @endif
        </small>

        @error('image')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror
    </div>

    {{-- PRODUCT SETTINGS --}}
    <div class="tob-products-field tob-products-field-full">
        <div class="tob-products-checks">

            <label class="tob-products-check">
                <input
                    type="hidden"
                    name="is_active"
                    value="0"
                >

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    @checked((bool) old('is_active', $product->is_active ?? true))
                >

                <span>
                    <strong>Active Product</strong>
                    <small>Show this product in the shop.</small>
                </span>
            </label>

            <label class="tob-products-check">
                <input
                    type="hidden"
                    name="is_featured"
                    value="0"
                >

                <input
                    type="checkbox"
                    name="is_featured"
                    value="1"
                    @checked((bool) old('is_featured', $product->is_featured ?? false))
                >

                <span>
                    <strong>Featured Product</strong>
                    <small>Mark this product as featured.</small>
                </span>
            </label>

        </div>
    </div>

</div>

{{-- FORM ACTIONS --}}
<div class="tob-products-form-actions">
    <button
        type="submit"
        class="tob-products-btn tob-products-btn-primary"
    >
        {{ $editing ? 'Update Product' : 'Create Product' }}
    </button>

    <a
        href="{{ route('admin.products.index') }}"
        class="tob-products-btn tob-products-btn-outline"
    >
        Cancel
    </a>
</div>
