{{-- =========================================================
     THE ONLY BAKER - FOOTER
========================================================= --}}

<footer class="tob-footer">

    <div class="tob-footer-container">

        <div class="tob-footer-main">

            {{-- =================================================
                 BRAND
            ================================================== --}}

            <div class="tob-footer-brand tob-footer-animate">

                <a href="{{ route('home') }}" class="tob-footer-logo">
                    <img
                        src="{{ asset('images/logo/logo.png') }}"
                        alt="The Only Baker"
                    >
                </a>

                <p class="tob-footer-tagline">
                    Small Bites. Big Happiness.
                </p>


                {{-- SOCIAL ICONS --}}

                <div class="tob-footer-socials">

                    {{-- Instagram --}}
                    <a href="#"
                       aria-label="Instagram">

                        <svg viewBox="0 0 24 24">
                            <rect
                                x="3"
                                y="3"
                                width="18"
                                height="18"
                                rx="5"
                            ></rect>

                            <circle
                                cx="12"
                                cy="12"
                                r="4"
                            ></circle>

                            <circle
                                cx="17.5"
                                cy="6.5"
                                r="1"
                            ></circle>
                        </svg>

                    </a>


                    {{-- Facebook --}}
                    <a href="#"
                       aria-label="Facebook">

                        <svg viewBox="0 0 24 24">
                            <path
                                d="
                                    M14.5 8H17V4.5
                                    C16.2 4.4 14.9 4.3 13.5 4.3
                                    C10.7 4.3 8.8 6 8.8 9.1
                                    V12H6V15.9H8.8V22
                                    H12.8V15.9H16.1
                                    L16.6 12H12.8V9.5
                                    C12.8 8.4 13.1 8 14.5 8Z
                                "
                            ></path>
                        </svg>

                    </a>


                    {{-- Pinterest --}}
                    <a href="#"
                       aria-label="Pinterest">

                        <svg viewBox="0 0 24 24">

                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                            ></circle>

                            <path
                                d="
                                    M10.3 18
                                    C11 16 11.3 15 11.7 13.3
                                    C11.2 12.4 11.5 10.5 12.5 10.5
                                    C13.3 10.5 13.6 11.1 13.6 11.8
                                    C13.6 12.7 13 14 12.7 15
                                    C12.4 15.8 13.1 16.5 14 16.5
                                    C15.7 16.5 16.8 14.4 16.8 12
                                    C16.8 9.9 15 8.3 12.5 8.3
                                    C9.6 8.3 7.9 10.3 7.9 12.5
                                    C7.9 13.5 8.3 14.4 9 14.9
                                "
                            ></path>

                        </svg>

                    </a>


                    {{-- YouTube --}}
                    <a href="#"
                       aria-label="YouTube">

                        <svg viewBox="0 0 24 24">

                            <path
                                d="
                                    M21 8
                                    C20.8 6.8 20 6 18.8 5.8
                                    C17.2 5.5 14.4 5.4 12 5.4
                                    C9.6 5.4 6.8 5.5 5.2 5.8
                                    C4 6 3.2 6.8 3 8
                                    C2.8 9.2 2.7 10.6 2.7 12
                                    C2.7 13.4 2.8 14.8 3 16
                                    C3.2 17.2 4 18 5.2 18.2
                                    C6.8 18.5 9.6 18.6 12 18.6
                                    C14.4 18.6 17.2 18.5 18.8 18.2
                                    C20 18 20.8 17.2 21 16
                                    C21.2 14.8 21.3 13.4 21.3 12
                                    C21.3 10.6 21.2 9.2 21 8Z
                                "
                            ></path>

                            <path d="M10 9L15 12L10 15Z"></path>

                        </svg>

                    </a>

                </div>

            </div>


            {{-- =================================================
                 QUICK LINKS
            ================================================== --}}

            <div class="tob-footer-column tob-footer-animate">

                <h3>
                    Quick Links
                </h3>

                <ul>
                    <li>
                        <a href="{{ route('home') }}">Home</a>
                    </li>

                    <li>
                        <a href="#">Shop</a>
                    </li>

                    <li>
                        <a href="#">Collections</a>
                    </li>

                    <li>
                        <a href="#">Gifting</a>
                    </li>

                    <li>
                        <a href="#our-story">Our Story</a>
                    </li>

                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>

            </div>


            {{-- =================================================
                 HELP
            ================================================== --}}

            <div class="tob-footer-column tob-footer-animate">

                <h3>
                    Help
                </h3>

                <ul>
                    <li>
                        <a href="#">FAQ</a>
                    </li>

                    <li>
                        <a href="#">Shipping Policy</a>
                    </li>

                    <li>
                        <a href="#">Return &amp; Refund</a>
                    </li>

                    <li>
                        <a href="#">Terms &amp; Conditions</a>
                    </li>

                    <li>
                        <a href="#">Privacy Policy</a>
                    </li>
                </ul>

            </div>


            {{-- =================================================
                 GET IN TOUCH
            ================================================== --}}

            <div class="tob-footer-contact tob-footer-animate">

                <h3>
                    Get in Touch
                </h3>


                {{-- PHONE --}}

                <a href="tel:+916206490079"
                   class="tob-footer-contact-item">

                    <svg viewBox="0 0 24 24">
                        <path
                            d="
                                M7 3H4.5
                                C3.7 3 3 3.7 3 4.5
                                C3 13.6 10.4 21 19.5 21
                                C20.3 21 21 20.3 21 19.5
                                V17
                                L16.7 16
                                L15.4 18
                                C11.3 16.3 7.7 12.7 6 8.6
                                L8 7.3
                                L7 3Z
                            "
                        ></path>
                    </svg>

                    <span>
                        +91 6206490079
                    </span>

                </a>


                {{-- EMAIL --}}

                <a href="mailto:theonlybaker@gmail.com"
                   class="tob-footer-contact-item">

                    <svg viewBox="0 0 24 24">

                        <rect
                            x="3"
                            y="5"
                            width="18"
                            height="14"
                            rx="2"
                        ></rect>

                        <path d="M4 7L12 13L20 7"></path>

                    </svg>

                    <span>
                        theonlybaker@gmail.com
                    </span>

                </a>


                {{-- LOCATION --}}

                <div class="tob-footer-contact-item">

                    <svg viewBox="0 0 24 24">

                        <path
                            d="
                                M12 21
                                C12 21 18 15.5 18 10
                                C18 6.7 15.3 4 12 4
                                C8.7 4 6 6.7 6 10
                                C6 15.5 12 21 12 21Z
                            "
                        ></path>

                        <circle
                            cx="12"
                            cy="10"
                            r="2"
                        ></circle>

                    </svg>

                    <span>
                        India
                    </span>

                </div>


                {{-- SOCIAL ROW --}}

                <div class="tob-footer-contact-social">

                    <a href="#"
                       aria-label="Facebook">
                        f
                    </a>

                    <a href="#"
                       aria-label="Twitter">
                        ♥
                    </a>

                    <a href="#"
                       aria-label="Instagram">

                        <svg viewBox="0 0 24 24">
                            <rect
                                x="3"
                                y="3"
                                width="18"
                                height="18"
                                rx="5"
                            ></rect>

                            <circle
                                cx="12"
                                cy="12"
                                r="4"
                            ></circle>
                        </svg>

                    </a>

                </div>

            </div>


            {{-- =================================================
                 NEWSLETTER
            ================================================== --}}

            <div class="tob-footer-newsletter tob-footer-animate">

                <h3>
                    Subscribe to Our Newsletter
                </h3>

                <p>
                    Get updates on new flavours, offers, and more.
                </p>

                <form
                    class="tob-footer-newsletter-form"
                    action="#"
                    method="post"
                    onsubmit="return false;"
                >

                    <input
                        type="email"
                        placeholder="Enter your email"
                        aria-label="Enter your email"
                    >

                    <button
                        type="submit"
                        aria-label="Subscribe"
                    >

                        <svg viewBox="0 0 30 20">
                            <path d="M2 10H25"></path>
                            <path d="M19 4L25 10L19 16"></path>
                        </svg>

                    </button>

                </form>

            </div>

        </div>


        {{-- =================================================
             BOTTOM FOOTER
        ================================================== --}}

        <div class="tob-footer-bottom">

            <p class="tob-footer-copyright">
                © 2025 The Only Baker. All rights reserved.
            </p>


            <div class="tob-footer-handwritten">

                <span>
                    Baked to make you smile
                </span>

                <svg
                    viewBox="0 0 35 32"
                    aria-hidden="true"
                >
                    <path
                        d="
                            M17 28
                            C14 24 5 18 5 11
                            C5 6 11 4 15 8
                            L17 11
                            L20 8
                            C24 4 30 6 30 11
                            C30 18 21 24 17 28
                        "
                    />
                </svg>

            </div>

        </div>

    </div>

</footer>