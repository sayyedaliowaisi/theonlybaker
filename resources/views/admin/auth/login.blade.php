<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Admin Login | The Only Baker
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/admin-login.css') }}"
    >

</head>


<body class="tob-admin-login-page">

    <main class="tob-admin-login-main">

        <section class="tob-admin-login-card">


            {{-- LOGO --}}
            <a
                href="{{ route('home') }}"
                class="tob-admin-login-logo"
            >

                <img
                    src="{{ asset('images/logo/logo.png') }}"
                    alt="The Only Baker"
                >

            </a>


            {{-- HEADING --}}
            <div class="tob-admin-login-heading">

                <span>
                    THE ONLY BAKER
                </span>

                <h1>
                    Admin Login
                </h1>

                <p>
                    Sign in to manage your store orders.
                </p>

            </div>


            {{-- ERRORS --}}
            @if($errors->any())

                <div class="tob-admin-login-error">

                    {{ $errors->first() }}

                </div>

            @endif


            {{-- FORM --}}
            <form
                method="POST"
                action="{{ route('admin.login.submit') }}"
                class="tob-admin-login-form"
            >

                @csrf


                <div class="tob-admin-login-field">

                    <label for="adminEmail">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="adminEmail"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@example.com"
                        autocomplete="email"
                        required
                        autofocus
                    >

                </div>


                <div class="tob-admin-login-field">

                    <label for="adminPassword">
                        Password
                    </label>


                    <div class="tob-admin-password-wrap">

                        <input
                            type="password"
                            id="adminPassword"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >


                        <button
                            type="button"
                            id="tobToggleAdminPassword"
                            aria-label="Show password"
                        >
                            Show
                        </button>

                    </div>

                </div>


                <label class="tob-admin-remember">

                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                    >

                    <span>
                        Remember me
                    </span>

                </label>


                <button
                    type="submit"
                    class="tob-admin-login-button"
                >

                    <span>
                        Sign In
                    </span>

                    <span>
                        →
                    </span>

                </button>

            </form>


            <div class="tob-admin-login-footer">

                <a href="{{ route('home') }}">
                    ← Back to Website
                </a>

            </div>

        </section>

    </main>


    <script>

        document.addEventListener(
            "DOMContentLoaded",
            function () {

                const input =
                    document.getElementById(
                        "adminPassword"
                    );

                const button =
                    document.getElementById(
                        "tobToggleAdminPassword"
                    );


                if (!input || !button) {
                    return;
                }


                button.addEventListener(
                    "click",
                    function () {

                        const hidden =
                            input.type === "password";


                        input.type =
                            hidden
                                ? "text"
                                : "password";


                        button.textContent =
                            hidden
                                ? "Hide"
                                : "Show";


                        button.setAttribute(
                            "aria-label",
                            hidden
                                ? "Hide password"
                                : "Show password"
                        );

                    }
                );

            }
        );

    </script>

</body>

</html>