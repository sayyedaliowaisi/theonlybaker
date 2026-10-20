
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>@yield('title', 'The Only Baker')</title>

    <meta
        name="description"
        content="@yield('meta_description', 'The Only Baker - Freshly baked cakes, cookies and desserts.')"
    >

    {{-- =====================================================
         VERSIONED GLOBAL CSS
         Automatically refreshes when file changes
    ====================================================== --}}

    @php
        $mainCssVersion = file_exists(public_path('css/main.css'))
            ? filemtime(public_path('css/main.css'))
            :
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>@yield('title', 'The Only Baker')</title>

    <meta
        name="description"
        content="@yield('meta_description', 'The Only Baker - Freshly baked cakes, cookies and desserts.')"
    >

    {{-- =====================================================
         VERSIONED GLOBAL CSS
         Automatically refreshes when file changes
    ====================================================== --}}

    @php
        $mainCssVersion = file_exists(public_path('css/main.css'))
            ? filemtime(public_path('css/main.css'))
            : '1';

        $mainJsVersion = file_exists(public_path('js/main.js'))
            ? filemtime(public_path('js/main.js'))
            : '1';

        $cartJsVersion = file_exists(public_path('js/cart-storage.js'))
            ? filemtime(public_path('js/cart-storage.js'))
            : '1';
    @endphp

    <link
        rel="stylesheet"
        href="{{ asset('css/main.css') }}?v={{ $mainCssVersion }}"
    >

    @stack('styles')
</head>

<body>

    {{-- =====================================================
         GLOBAL NAVBAR
    ====================================================== --}}

    @include('components.navbar')

    {{-- =====================================================
         PAGE CONTENT
    ====================================================== --}}

    <main>
        @yield('content')
    </main>

    {{-- =====================================================
         GLOBAL FOOTER
    ====================================================== --}}

    @include('components.footer')

    {{-- =====================================================
         GLOBAL PRODUCT SEARCH DATA
         Source: config/products.php
    ====================================================== --}}

    @php
        $searchProducts = collect(config('products', []))
            ->map(function ($product, $slug) {
                return [
                    'slug' => $slug,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'category' => $product['category'] ?? 'classic',
                    'image' => asset($product['image']),
                    'url' => route('product.show', ['slug' => $slug]),
                ];
            })
            ->values();
    @endphp

    <script>
        window.TheOnlyBakerSearchProducts = @json($searchProducts);

        window.TheOnlyBakerSearchConfig = {
            shopUrl: @json(route('shop'))
        };
    </script>

    {{-- =====================================================
         VERSIONED GLOBAL JAVASCRIPT
    ====================================================== --}}

    <script
        src="{{ asset('js/cart-storage.js') }}?v={{ $cartJsVersion }}"
    ></script>

    <script
        src="{{ asset('js/main.js') }}?v={{ $mainJsVersion }}"
    ></script>

    @stack('scripts')

</body>
</html>
 '1';

        $mainJsVersion = file_exists(public_path('js/main.js'))
            ? filemtime(public_path('js/main.js'))
            : '1';

        $cartJsVersion = file_exists(public_path('js/cart-storage.js'))
            ? filemtime(public_path('js/cart-storage.js'))
            : '1';
    @endphp

    <link
        rel="stylesheet"
        href="{{ asset('css/main.css') }}?v={{ $mainCssVersion }}"
    >

    @stack('styles')
</head>

<body>

    {{-- =====================================================
         GLOBAL NAVBAR
    ====================================================== --}}

    @include('components.navbar')

    {{-- =====================================================
         PAGE CONTENT
    ====================================================== --}}

    <main>
        @yield('content')
    </main>

    {{-- =====================================================
         GLOBAL FOOTER
    ====================================================== --}}

    @include('components.footer')

    {{-- =====================================================
         GLOBAL PRODUCT SEARCH DATA
         Source: config/products.php
    ====================================================== --}}

    @php
        $searchProducts = collect(config('products', []))
            ->map(function ($product, $slug) {
                return [
                    'slug' => $slug,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'category' => $product['category'] ?? 'classic',
                    'image' => asset($product['image']),
                    'url' => route('product.show', ['slug' => $slug]),
                ];
            })
            ->values();
    @endphp

    <script>
        window.TheOnlyBakerSearchProducts = @json($searchProducts);

        window.TheOnlyBakerSearchConfig = {
            shopUrl: @json(route('shop'))
        };
    </script>

    {{-- =====================================================
         VERSIONED GLOBAL JAVASCRIPT
    ====================================================== --}}

    <script
        src="{{ asset('js/cart-storage.js') }}?v={{ $cartJsVersion }}"
    ></script>

    <script
        src="{{ asset('js/main.js') }}?v={{ $mainJsVersion }}"
    ></script>

    @stack('scripts')

</body>
</html>
