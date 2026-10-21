@php
    $editing = isset($product);

    $imageUrl = $editing && $product->image
        ? (
            str_starts_with($product->image, 'products/')
                ? asset('storage/' . $product->image)
                : asset($product->image)
        )
        : null;

    $bannerUrl = $editing && $product->banner_image
        ? (
            str_starts_with($product->banner_image, 'products/')
                ? asset('storage/' . $product->banner_image)
                : asset($product->banner_image)
        )
        : null;
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
        >

        <small class="tob-products-help">
            Lowercase letters, numbers and hyphens.
            Existing product slugs cannot be changed.
        </small>

        @error('slug')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror
    </div>

    {{-- DESCRIPTION --}}
    <div class="tob-products-field tob-products-field-full">
        <label for="product-description">Description</label>

        <textarea
            id="product-description"
            name="description"
            rows="5"
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
            min="0"
            max="99999999.99"
            value="{{ old('price', $product->price ?? '') }}"
            required
        >

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
        >
            <option value="">Select Category</option>

            @foreach ($categories as $category)
                <option
                    value="{{ $category->slug }}"
                    @selected(
                        old('category', $product->category ?? '')
                        === $category->slug
                    )
                >
                    {{ $category->name }}
                    {{ !$category->is_active ? '(Inactive)' : '' }}
                </option>
            @endforeach
        </select>

        <small class="tob-products-help">
            Categories are loaded from Admin Category Management.
        </small>

        @error('category')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror
    </div>

    {{-- WHOLESALE HEADING --}}
    <div class="tob-products-field tob-products-field-full">
        <div style="
            padding:20px;
            background:#fff8ef;
            border:1px solid #ead8c1;
            border-radius:12px;
            margin-top:10px;
        ">
            <h3 style="
                margin:0 0 7px;
                font-size:18px;
                color:#593728;
            ">
                Wholesale / B2B Pricing
            </h3>

            <p style="
                margin:0;
                color:#76665b;
                font-size:13px;
            ">
                Set special prices for approved retailers.
                Leave wholesale price empty to disable wholesale pricing.
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
            min="0"
            max="99999999.99"
            value="{{ old('wholesale_price', $product->wholesale_price ?? '') }}"
            placeholder="e.g. 280.00"
        >

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
            max="50"
            step="1"
            value="{{ old('wholesale_moq', $product->wholesale_moq ?? 1) }}"
            required
        >

        <small class="tob-products-help">
            Maximum 50 units.
        </small>

        @error('wholesale_moq')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror
    </div>

    {{-- INITIAL STOCK --}}
    @unless ($editing)
        <div class="tob-products-field tob-products-field-full">
            <label for="product-initial-stock">
                Initial Stock Quantity
                <span class="tob-products-required">*</span>
            </label>

            <input
                id="product-initial-stock"
                type="number"
                name="initial_stock"
                min="0"
                max="1000000"
                step="1"
                value="{{ old('initial_stock', 0) }}"
                required
            >

            <small class="tob-products-help">
                Use 0 for out of stock.
                Update later from Inventory Management.
            </small>

            @error('initial_stock')
                <small class="tob-products-error">{{ $message }}</small>
            @enderror
        </div>
    @endunless

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
        >

        <small class="tob-products-help">
            Lower numbers appear first in the shop.
        </small>

        @error('sort_order')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror
    </div>

    {{-- PRODUCT GALLERY --}}
    <div class="tob-products-field tob-products-field-full">

        <h3>Product Gallery</h3>

        <p class="tob-products-help">
            The selected primary image is used in shop cards
            and shown first on the product detail page.
        </p>

        <input
            type="hidden"
            id="tob-primary-image"
            name="primary_image"
            value="{{ old('primary_image', 'main') }}"
        >

        {{-- CURRENT PRIMARY --}}
        @if ($imageUrl)
            <div class="tob-admin-gallery-item">
                <img
                    src="{{ $imageUrl }}"
                    alt="Current primary product image"
                >

                <label>
                    <input
                        type="radio"
                        name="primary_image_choice"
                        value="main"
                        @checked(old('primary_image', 'main') === 'main')
                    >
                    Current primary image
                </label>
            </div>
        @endif

        {{-- MAIN IMAGE UPLOAD --}}
        <label for="product-image">
            {{ $editing
                ? 'Replace Main Image (optional)'
                : 'Main Image (optional)' }}
        </label>

        <input
            id="product-image"
            type="file"
            name="image"
            accept=".jpg,.jpeg,.png,.webp"
        >

        <div
            id="tob-main-preview"
            class="tob-admin-gallery-grid"
        ></div>

        @error('image')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror

        @if ($editing && $imageUrl)
            <label>
                <input
                    type="checkbox"
                    name="remove_image"
                    value="1"
                >
                Remove current primary image
            </label>
        @endif

        {{-- MULTIPLE GALLERY UPLOAD --}}
        <label
            for="product-gallery-images"
            style="margin-top:18px"
        >
            Add Multiple Gallery Images
        </label>

        <input
            id="product-gallery-images"
            type="file"
            name="gallery_images[]"
            accept=".jpg,.jpeg,.png,.webp"
            multiple
        >

        <small class="tob-products-help">
            Up to 20 new images per save.
            Maximum 5 MB per image.
        </small>

        <div
            id="tob-new-gallery-preview"
            class="tob-admin-gallery-grid"
        ></div>

        @error('gallery_images')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror

        @error('gallery_images.*')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror

        {{-- SAVED GALLERY --}}
        @if ($editing)
            <h4>Saved Gallery Images</h4>

            <div class="tob-admin-gallery-grid">
                @foreach ($product->galleryImages as $galleryImage)

                    <div class="tob-admin-gallery-item">

                        <img
                            src="{{ str_starts_with($galleryImage->image, 'products/')
                                ? asset('storage/' . $galleryImage->image)
                                : asset($galleryImage->image) }}"
                            alt="Saved gallery image"
                        >

                        <label>
                            <input
                                type="radio"
                                name="primary_image_choice"
                                value="existing:{{ $galleryImage->id }}"
                                @checked(
                                    old('primary_image')
                                    === 'existing:' . $galleryImage->id
                                )
                            >
                            Make primary
                        </label>

                        <label>
                            Order

                            <input
                                type="number"
                                name="gallery_order[{{ $galleryImage->id }}]"
                                value="{{ old(
                                    'gallery_order.' . $galleryImage->id,
                                    $galleryImage->sort_order
                                ) }}"
                                min="0"
                                max="1000000"
                            >
                        </label>

                        <label>
                            <input
                                type="checkbox"
                                name="remove_gallery_images[]"
                                value="{{ $galleryImage->id }}"
                            >
                            Remove from gallery
                        </label>

                    </div>

                @endforeach
            </div>
        @endif

    </div>

    {{-- PRODUCT BANNER --}}
    <div class="tob-products-field tob-products-field-full">

        <h3>Product Detail Banner</h3>

        @if ($bannerUrl)
            <div class="tob-admin-banner-preview">
                <img
                    src="{{ $bannerUrl }}"
                    alt="Current product banner"
                >
            </div>

            <label>
                <input
                    type="checkbox"
                    name="remove_banner_image"
                    value="1"
                >
                Remove current banner
            </label>
        @endif

        <input
            type="file"
            id="product-banner-image"
            name="banner_image"
            accept=".jpg,.jpeg,.png,.webp"
        >

        <small class="tob-products-help">
            Recommended wide image: 1600 × 600 px.
            Maximum 5 MB.
        </small>

        <div
            id="tob-banner-preview"
            class="tob-admin-banner-preview"
        ></div>

        @error('banner_image')
            <small class="tob-products-error">{{ $message }}</small>
        @enderror

    </div>

    {{-- =====================================================
     PRODUCT DETAIL PAGE CONTENT
===================================================== --}}

<div class="tob-products-field tob-products-field-full">

    <div style="
        padding:22px;
        margin-top:22px;
        background:#fff8ef;
        border:1px solid #ead8c1;
        border-radius:12px;
    ">
        <h3 style="
            margin:0 0 8px;
            color:#593728;
            font-size:20px;
        ">
            Product Detail Page Content
        </h3>

        <p style="
            margin:0;
            color:#806f65;
            font-size:13px;
            line-height:1.7;
        ">
            Manage the content displayed on this product's
            detail page, including ingredients, nutrition,
            storage instructions, shipping information,
            premium badge and benefits.
        </p>
    </div>

</div>


{{-- PRODUCT SUBTITLE --}}

<div class="tob-products-field tob-products-field-full">

    <label for="product-subtitle">
        Product Subtitle
    </label>

    <input
        type="text"
        id="product-subtitle"
        name="subtitle"
        maxlength="150"
        value="{{ old('subtitle', $product->subtitle ?? '') }}"
        placeholder="e.g. Premium Dry Fruit Cookies"
    >

    <small class="tob-products-help">
        Displayed below the product name.
        Leave blank to show the category name.
    </small>

    @error('subtitle')
        <small class="tob-products-error">
            {{ $message }}
        </small>
    @enderror

</div>


{{-- PREMIUM BADGE --}}

<div class="tob-products-field tob-products-field-full">

    <h4>Premium Badge Settings</h4>

    <input
        type="hidden"
        name="show_premium_badge"
        value="0"
    >

    <label class="tob-products-check">

        <input
            type="checkbox"
            name="show_premium_badge"
            value="1"
            @checked(
                (bool) old(
                    'show_premium_badge',
                    $product->show_premium_badge ?? true
                )
            )
        >

        <span>
            <strong>Show Premium Badge</strong>
            <small>
                Display a badge on the product image.
            </small>
        </span>

    </label>

</div>


{{-- PREMIUM BADGE TEXT --}}

<div class="tob-products-field tob-products-field-full">

    <label for="product-premium-badge-text">
        Premium Badge Text
    </label>

    <input
        type="text"
        id="product-premium-badge-text"
        name="premium_badge_text"
        maxlength="150"
        value="{{ old(
            'premium_badge_text',
            $product->premium_badge_text ?? ''
        ) }}"
        placeholder="e.g. PREMIUM"
    >

    <small class="tob-products-help">
        Default: PREMIUM
    </small>

    @error('premium_badge_text')
        <small class="tob-products-error">
            {{ $message }}
        </small>
    @enderror

</div>


{{-- INGREDIENTS --}}

<div class="tob-products-field tob-products-field-full">

    <label for="product-ingredients">
        Ingredients
    </label>

    <textarea
        id="product-ingredients"
        name="ingredients"
        rows="5"
        maxlength="5000"
        placeholder="Enter product ingredients..."
    >{{ old(
        'ingredients',
        $product->ingredients ?? ''
    ) }}</textarea>

    <small class="tob-products-help">
        Appears under the Ingredients accordion.
    </small>

    @error('ingredients')
        <small class="tob-products-error">
            {{ $message }}
        </small>
    @enderror

</div>


{{-- NUTRITION INFORMATION --}}

<div class="tob-products-field tob-products-field-full">

    <label for="product-nutrition">
        Nutritional Information
    </label>

    <textarea
        id="product-nutrition"
        name="nutrition_information"
        rows="5"
        maxlength="5000"
        placeholder="Enter nutritional information..."
    >{{ old(
        'nutrition_information',
        $product->nutrition_information ?? ''
    ) }}</textarea>

    @error('nutrition_information')
        <small class="tob-products-error">
            {{ $message }}
        </small>
    @enderror

</div>


{{-- STORAGE INSTRUCTIONS --}}

<div class="tob-products-field tob-products-field-full">

    <label for="product-storage">
        Storage Instructions
    </label>

    <textarea
        id="product-storage"
        name="storage_instructions"
        rows="5"
        maxlength="5000"
        placeholder="Enter storage instructions..."
    >{{ old(
        'storage_instructions',
        $product->storage_instructions ?? ''
    ) }}</textarea>

    @error('storage_instructions')
        <small class="tob-products-error">
            {{ $message }}
        </small>
    @enderror

</div>


{{-- SHIPPING AND DELIVERY --}}

<div class="tob-products-field tob-products-field-full">

    <label for="product-shipping">
        Shipping & Delivery
    </label>

    <textarea
        id="product-shipping"
        name="shipping_delivery"
        rows="5"
        maxlength="5000"
        placeholder="Enter shipping and delivery information..."
    >{{ old(
        'shipping_delivery',
        $product->shipping_delivery ?? ''
    ) }}</textarea>

    @error('shipping_delivery')
        <small class="tob-products-error">
            {{ $message }}
        </small>
    @enderror

</div>


{{-- =====================================================
     PRODUCT BENEFITS
===================================================== --}}

<div class="tob-products-field tob-products-field-full">

    <div style="
        padding:18px;
        margin-top:10px;
        background:#fbf7f1;
        border:1px solid #e9ded3;
        border-radius:10px;
    ">

        <h3 style="
            margin:0 0 7px;
            color:#4a2818;
        ">
            Product Benefits
        </h3>

        <p style="
            margin:0;
            color:#806f65;
            font-size:13px;
        ">
            Edit the four benefit items displayed
            below the product purchase buttons.
        </p>

    </div>

</div>


@php
    $defaultBenefits = [
        1 => [
            'icon' => '♡',
            'title' => 'Freshly Baked',
            'description' => 'Made with care',
        ],
        2 => [
            'icon' => '✦',
            'title' => 'Premium Quality',
            'description' => 'Carefully selected ingredients',
        ],
        3 => [
            'icon' => '♧',
            'title' => 'Quality Packaging',
            'description' => 'Freshness protected',
        ],
        4 => [
            'icon' => '♢',
            'title' => 'Made with Love',
            'description' => 'The Only Baker',
        ],
    ];
@endphp


@for ($i = 1; $i <= 4; $i++)

    @php
        $iconField = "benefit_{$i}_icon";
        $titleField = "benefit_{$i}_title";
        $descriptionField = "benefit_{$i}_description";
    @endphp

    <div class="tob-products-field tob-products-field-full">

        <h4 style="
            margin:15px 0 5px;
            color:#593728;
        ">
            Benefit {{ $i }}
        </h4>

    </div>

    {{-- BENEFIT ICON --}}

    <div class="tob-products-field">

        <label for="benefit-{{ $i }}-icon">
            Benefit {{ $i }} Icon
        </label>

        <input
            type="text"
            id="benefit-{{ $i }}-icon"
            name="{{ $iconField }}"
            maxlength="150"
            value="{{ old(
                $iconField,
                $product?->{$iconField}
                    ?? $defaultBenefits[$i]['icon']
            ) }}"
            placeholder="e.g. ✦"
        >

        <small class="tob-products-help">
            Enter a symbol or emoji.
        </small>

        @error($iconField)
            <small class="tob-products-error">
                {{ $message }}
            </small>
        @enderror

    </div>

    {{-- BENEFIT TITLE --}}

    <div class="tob-products-field">

        <label for="benefit-{{ $i }}-title">
            Benefit {{ $i }} Title
        </label>

        <input
            type="text"
            id="benefit-{{ $i }}-title"
            name="{{ $titleField }}"
            maxlength="150"
            value="{{ old(
                $titleField,
                $product?->{$titleField}
                    ?? $defaultBenefits[$i]['title']
            ) }}"
        >

        @error($titleField)
            <small class="tob-products-error">
                {{ $message }}
            </small>
        @enderror

    </div>

    {{-- BENEFIT DESCRIPTION --}}

    <div class="tob-products-field tob-products-field-full">

        <label for="benefit-{{ $i }}-description">
            Benefit {{ $i }} Description
        </label>

        <textarea
            id="benefit-{{ $i }}-description"
            name="{{ $descriptionField }}"
            rows="3"
            maxlength="500"
        >{{ old(
            $descriptionField,
            $product?->{$descriptionField}
                ?? $defaultBenefits[$i]['description']
        ) }}</textarea>

        @error($descriptionField)
            <small class="tob-products-error">
                {{ $message }}
            </small>
        @enderror

    </div>

@endfor

{{-- =====================================================
     PRODUCT SEO MANAGEMENT
===================================================== --}}

<div
    class="tob-products-field tob-products-field-full"
    style="
        padding:20px;
        border:1px solid #ead8c1;
        border-radius:12px;
        background:#fff8ef;
        margin-top:20px;
    "
>
    <h3 style="margin:0 0 8px;color:#593728;">
        Product SEO Management
    </h3>

    <p class="tob-products-help">
        Manage how this product appears in search engines
        and when shared on social media.
    </p>
</div>

{{-- SEO TITLE --}}
<div class="tob-products-field tob-products-field-full">
    <label for="seo-title">SEO Title</label>

    <input
        id="seo-title"
        type="text"
        name="seo_title"
        maxlength="255"
        value="{{ old('seo_title', $product->seo_title ?? '') }}"
        placeholder="Product Name | The Only Baker"
    >

    @error('seo_title')
        <small class="tob-products-error">{{ $message }}</small>
    @enderror
</div>

{{-- META DESCRIPTION --}}
<div class="tob-products-field tob-products-field-full">
    <label for="seo-description">Meta Description</label>

    <textarea
        id="seo-description"
        name="seo_description"
        rows="3"
        maxlength="500"
        placeholder="Describe this product for search engines"
    >{{ old('seo_description', $product->seo_description ?? '') }}</textarea>

    @error('seo_description')
        <small class="tob-products-error">{{ $message }}</small>
    @enderror
</div>

{{-- SEO KEYWORDS --}}
<div class="tob-products-field tob-products-field-full">
    <label for="seo-keywords">SEO Keywords (optional)</label>

    <input
        id="seo-keywords"
        type="text"
        name="seo_keywords"
        maxlength="500"
        value="{{ old('seo_keywords', $product->seo_keywords ?? '') }}"
        placeholder="cookies, dry fruit cookies, bakery"
    >

    <small class="tob-products-help">
        Saved for internal use. Google does not use
        meta keywords for ranking.
    </small>

    @error('seo_keywords')
        <small class="tob-products-error">{{ $message }}</small>
    @enderror
</div>

{{-- SOCIAL SHARING IMAGE --}}
<div class="tob-products-field tob-products-field-full">
    <label for="seo-image">Social Sharing Image</label>

    @if ($editing && $product->seo_image)
        <img
            src="{{ str_starts_with($product->seo_image, 'products/')
                ? asset('storage/' . $product->seo_image)
                : asset($product->seo_image) }}"
            alt="Current social sharing image"
            style="
                display:block;
                max-width:280px;
                max-height:180px;
                object-fit:contain;
                margin:10px 0;
            "
        >

        <label>
            <input
                type="checkbox"
                name="remove_seo_image"
                value="1"
            >
            Remove social sharing image
        </label>
    @endif

    <input
        id="seo-image"
        type="file"
        name="seo_image"
        accept=".jpg,.jpeg,.png,.webp"
    >

    <small class="tob-products-help">
        Recommended: 1200 × 630 px. Maximum 5 MB.
    </small>

    @error('seo_image')
        <small class="tob-products-error">{{ $message }}</small>
    @enderror
</div>

{{-- GOOGLE INDEXING --}}
<div class="tob-products-field tob-products-field-full">
    <input
        type="hidden"
        name="seo_noindex"
        value="0"
    >

    <label class="tob-products-check">
        <input
            type="checkbox"
            name="seo_noindex"
            value="1"
            @checked(
                (bool) old(
                    'seo_noindex',
                    $product->seo_noindex ?? false
                )
            )
        >

        <span>
            <strong>Hide from Search Engines (noindex)</strong>

            <small>
                Enable only when you do not want this
                product indexed by search engines.
            </small>
        </span>
    </label>
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
                    @checked(
                        (bool) old(
                            'is_active',
                            $product->is_active ?? true
                        )
                    )
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
                    @checked(
                        (bool) old(
                            'is_featured',
                            $product->is_featured ?? false
                        )
                    )
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

{{-- IMAGE PREVIEW SCRIPT --}}
<script>
(function () {
    const gallery = document.getElementById(
        'product-gallery-images'
    );

    const main = document.getElementById(
        'product-image'
    );

    const banner = document.getElementById(
        'product-banner-image'
    );

    const primary = document.getElementById(
        'tob-primary-image'
    );

    const objectUrls = [];

    const allowed = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    const maxSize = 5 * 1024 * 1024;

    function renderPreview(
        input,
        targetId,
        allowPrimary = false
    ) {
        const target = document.getElementById(targetId);

        if (!target) return;

        target.replaceChildren();

        const files = Array.from(input.files || []);

        if (
            files.length > 20 ||
            files.some(file =>
                !allowed.includes(file.type) ||
                file.size > maxSize
            )
        ) {
            alert(
                'Choose up to 20 JPG, PNG or WebP images, ' +
                'maximum 5 MB each.'
            );

            input.value = '';
            return;
        }

        files.forEach((file, index) => {
            const wrap = document.createElement('div');

            wrap.className = 'tob-admin-gallery-item';

            const img = document.createElement('img');
            const url = URL.createObjectURL(file);

            objectUrls.push(url);

            img.src = url;
            img.alt = file.name;

            wrap.appendChild(img);

            if (allowPrimary) {
                const label = document.createElement('label');
                const radio = document.createElement('input');

                radio.type = 'radio';
                radio.name = 'primary_image_choice';
                radio.value = 'new:' + index;

                label.append(
                    radio,
                    document.createTextNode(' Make primary')
                );

                wrap.appendChild(label);
            }

            target.appendChild(wrap);
        });
    }

    main?.addEventListener('change', () => {
        renderPreview(main, 'tob-main-preview');
    });

    gallery?.addEventListener('change', () => {
        renderPreview(
            gallery,
            'tob-new-gallery-preview',
            true
        );
    });

    banner?.addEventListener('change', () => {
        renderPreview(
            banner,
            'tob-banner-preview'
        );
    });

    document.addEventListener('change', event => {
        if (event.target.name === 'primary_image_choice') {
            primary.value = event.target.value;
        }
    });

    window.addEventListener('pagehide', () => {
        objectUrls.forEach(url => {
            URL.revokeObjectURL(url);
        });
    });
})();
</script>