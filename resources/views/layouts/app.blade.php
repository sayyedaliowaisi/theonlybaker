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

    <title>
        @yield('title', 'The Only Baker')
    </title>

    <meta
    name="description"
    content="@yield('meta_description', 'The Only Baker - Freshly baked cakes, cookies and desserts.')"
>

    <link
        rel="stylesheet"
        href="{{ asset('css/main.css') }}"
    >

    @stack('styles')
</head>

<body>

    @include('components.navbar')


    <main>
        @yield('content')
    </main>


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

                    'url' => route(
                        'product.show',
                        ['slug' => $slug]
                    ),
                ];
            })
            ->values();
    @endphp


    <script>
        window.TheOnlyBakerSearchProducts =
            @json($searchProducts);

        window.TheOnlyBakerSearchConfig = {
            shopUrl: @json(route('shop'))
        };
    </script>


    {{-- =====================================================
         GLOBAL JAVASCRIPT
    ====================================================== --}}

    <script src="{{ asset('js/cart-storage.js') }}"></script>

    <script src="{{ asset('js/main.js') }}"></script>


    @stack('scripts')

</body>
</html>