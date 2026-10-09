
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
        @yield('title', 'Admin | The Only Baker')
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/admin.css') }}"
    >

    @stack('styles')

</head>

<body class="tob-admin-body">

    <div class="tob-admin-layout">

        {{-- =====================================================
             SIDEBAR
        ====================================================== --}}

        <aside class="tob-admin-sidebar">

            <a
                href="{{ route('admin.orders.index') }}"
                class="tob-admin-logo"
            >

                <img
                    src="{{ asset('images/logo/logo.png') }}"
                    alt="The Only Baker"
                >

            </a>

            <div class="tob-admin-sidebar-label">
                ADMIN PANEL
            </div>

            <nav class="tob-admin-nav">

                {{-- ORDERS --}}
                <a
                    href="{{ route('admin.orders.index') }}"
                    class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                >

                    <span class="tob-admin-nav-icon">
                        ◫
                    </span>

                    Orders

                </a>

                {{-- INVENTORY --}}
                <a
                    href="{{ route('admin.inventory.index') }}"
                    class="{{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}"
                >

                    <span class="tob-admin-nav-icon">
                        ▦
                    </span>

                    Inventory

                </a>

                {{-- REVIEWS --}}
                <a
                    href="{{ route('admin.reviews.index') }}"
                    class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
                >

                    <span class="tob-admin-nav-icon">
                        ★
                    </span>

                    Reviews

                </a>

                {{-- EMAIL NOTIFICATIONS --}}
                <a
                    href="{{ route('admin.email-notifications.index') }}"
                    class="{{ request()->routeIs('admin.email-notifications.*') ? 'active' : '' }}"
                >

                    <span class="tob-admin-nav-icon">
                        ✉
                    </span>

                    Email Notifications

                </a>

            </nav>

            <div class="tob-admin-sidebar-bottom">

                <div class="tob-admin-user">

                    <span>
                        Signed in as
                    </span>

                    <strong>
                        {{ auth('admin')->user()->name }}
                    </strong>

                </div>

                <a href="{{ route('home') }}">
                    ← View Website
                </a>

                <form
                    method="POST"
                    action="{{ route('admin.logout') }}"
                >

                    @csrf

                    <button type="submit">
                        Sign Out
                    </button>

                </form>

            </div>

        </aside>

        {{-- =====================================================
             MAIN
        ====================================================== --}}

        <main class="tob-admin-main">

            <header class="tob-admin-header">

                <div>

                    <span>
                        THE ONLY BAKER
                    </span>

                    <strong>
                        Store Administration
                    </strong>

                </div>

                <a href="{{ route('home') }}">
                    View Store →
                </a>

            </header>

            <div class="tob-admin-content">

                @if(session('success'))

                    <div class="tob-admin-alert">
                        {{ session('success') }}
                    </div>

                @endif

                @if(session('error'))

                    <div
                        class="tob-admin-alert"
                        style="
                            background:#fff2f0;
                            color:#8d4036;
                            border-color:#ebcbc6;
                        "
                    >
                        {{ session('error') }}
                    </div>

                @endif

                @yield('content')

            </div>

        </main>

    </div>

    @stack('scripts')

</body>

</html>
