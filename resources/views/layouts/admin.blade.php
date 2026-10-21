
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
    href="{{ asset('css/admin.css') }}?v={{ file_exists(public_path('css/admin.css')) ? filemtime(public_path('css/admin.css')) : '1' }}"
>

    @stack('styles')

</head>

<body class="tob-admin-body">


{{-- MOBILE SIDEBAR BUTTON --}}
<button
    type="button"
    class="tob-admin-mobile-toggle"
    id="tobAdminMenuToggle"
    aria-label="Open admin navigation"
    aria-controls="tobAdminSidebar"
    aria-expanded="false"
>
    ☰
</button>

{{-- MOBILE SIDEBAR OVERLAY --}}
<div
    class="tob-admin-sidebar-overlay"
    id="tobAdminSidebarOverlay"
    aria-hidden="true"
></div>


    <div class="tob-admin-layout">

        {{-- =====================================================
             SIDEBAR
        ====================================================== --}}

        
<aside
    class="tob-admin-sidebar"
    id="tobAdminSidebar"
    aria-label="Admin navigation"
>


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

            
@php
    $unreadContactEnquiries = \App\Models\ContactEnquiry::query()
        ->whereNull('read_at')
        ->count();
@endphp


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

                <a href="{{ route('admin.products.index') }}">
    Products
</a>

<a
    href="{{ route('admin.categories.index') }}"
    class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
>
    <span class="tob-admin-nav-icon">▤</span>
    Categories
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

                

{{-- CONTACT ENQUIRIES --}}
<a
    href="{{ route('admin.contact-enquiries.index') }}"
    class="tob-admin-contact-link {{ request()->routeIs('admin.contact-enquiries.*') ? 'active' : '' }}"
>
    <span class="tob-admin-nav-icon" aria-hidden="true">
        ✉
    </span>

    <span class="tob-admin-contact-label">
        Contact Enquiries
    </span>

    @if($unreadContactEnquiries > 0)
        <span
            class="tob-admin-unread-badge"
            aria-label="{{ $unreadContactEnquiries }} unread contact enquiries"
        >
            {{ $unreadContactEnquiries > 99 ? '99+' : $unreadContactEnquiries }}
        </span>
    @endif
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

    <script src="{{ asset('js/admin.js') }}?v={{ file_exists(public_path('js/admin.js')) ? filemtime(public_path('js/admin.js')) : '1' }}"></script>

    @stack('scripts')

</body>

</html>
