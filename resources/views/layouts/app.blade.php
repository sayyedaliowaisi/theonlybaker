<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'The Only Baker')</title>

    <meta name="description"
          content="The Only Baker - Freshly baked cakes, cookies and desserts.">

    {{-- MAIN CSS --}}
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    @stack('styles')
</head>

<body>

    {{-- NAVBAR --}}
    @include('components.navbar')


    {{-- PAGE CONTENT --}}
    <main>
        @yield('content')
    </main>


    {{-- FOOTER --}}
    @include('components.footer')


    {{-- MAIN JS --}}
    <script src="{{ asset('js/main.js') }}"></script>

    @stack('scripts')

</body>

</html>