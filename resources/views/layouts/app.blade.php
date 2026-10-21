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

    {{-- SEO TITLE --}}
    <title>@yield('title', 'The Only Baker')</title>

    {{-- META DESCRIPTION --}}
    <meta
        name="description"
        content="@yield('meta_description', 'The Only Baker - Freshly baked cakes, cookies and desserts.')"
    >

    {{-- GOOGLE INDEXING --}}
    <meta
        name="robots"
        content="@yield('meta_robots', 'index, follow')"
    >

    {{-- CANONICAL --}}
    <link
        rel="canonical"
        href="@yield('canonical_url', url()->current())"
    >

    {{-- OPEN GRAPH --}}
    <meta
        property="og:type"
        content="@yield('og_type', 'website')"
    >

    <meta
        property="og:site_name"
        content="The Only Baker"
    >

    <meta
        property="og:title"
        content="@yield('og_title', 'The Only Baker')"
    >

    <meta
        property="og:description"
        content="@yield('og_description', 'The Only Baker - Freshly baked cakes, cookies and desserts.')"
    >

    <meta
        property="og:url"
        content="@yield('canonical_url', url()->current())"
    >

    @hasSection('og_image')
        <meta
            property="og:image"
            content="@yield('og_image')"
        >
    @endif

    {{-- TWITTER / X --}}
    <meta
        name="twitter:card"
        content="summary_large_image"
    >

    <meta
        name="twitter:title"
        content="@yield('og_title', 'The Only Baker')"
    >

    <meta
        name="twitter:description"
        content="@yield('og_description', 'The Only Baker - Freshly baked cakes, cookies and desserts.')"
    >

    @hasSection('og_image')
        <meta
            name="twitter:image"
            content="@yield('og_image')"
        >
    @endif

    @stack('seo')

    {{-- VERSIONED GLOBAL CSS AND JS --}}
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

    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    {{-- GLOBAL PRODUCT SEARCH --}}
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

    <script
        src="{{ asset('js/cart-storage.js') }}?v={{ $cartJsVersion }}"
    ></script>

    <script
        src="{{ asset('js/main.js') }}?v={{ $mainJsVersion }}"
    ></script>

    @stack('scripts')

</body>
</html>