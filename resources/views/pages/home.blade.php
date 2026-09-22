@extends('layouts.app')

@section('title', 'The Only Baker')

@section('content')

{{-- =========================================================
     THE ONLY BAKER - HERO SECTION
========================================================= --}}

<section class="tob-hero">

    {{-- =====================================================
         BACKGROUND IMAGE
    ====================================================== --}}
    <div class="tob-hero-background">

        <img
            src="{{ asset('images/hero/hero-bg.jpg') }}"
            alt="The Only Baker Cookies"
            class="tob-hero-background-image"
        >

    </div>


    {{-- =====================================================
         HERO CONTAINER
    ====================================================== --}}
    <div class="tob-hero-container">


        {{-- =================================================
             LEFT CONTENT
        ================================================== --}}
        <div class="tob-hero-content">


            {{-- PREMIUM COOKIES --}}
            <div class="tob-hero-label">

                <span class="tob-label-line"></span>

                <span>
                    PREMIUM COOKIES
                </span>

            </div>


            {{-- MAIN HEADING --}}
            <h1 class="tob-hero-heading">

                <span>
                    Small Bites.
                </span>

                <span>
                    Big Happiness.
                </span>

            </h1>


            {{-- HANDWRITTEN TEXT --}}
            <div class="tob-hero-love">

                <span>
                    Baked with love for your 
                    <br>sweet moments.</br>
                </span>


                {{-- HAND DRAWN HEART --}}
                <svg
                    class="tob-love-heart"
                    viewBox="0 0 45 40"
                    aria-hidden="true"
                >
                    <path
                        d="
                            M22 35
                            C18 30 6 22 6 13
                            C6 7 13 4 18 9
                            C20 11 21 14 22 16
                            C23 14 24 11 27 9
                            C31 4 39 7 39 13
                            C39 22 27 30 22 35
                        "
                    />
                </svg>

            </div>


            {{-- =================================================
                 SHOP COOKIES BUTTON
            ================================================== --}}
            <a href="#" class="tob-hero-button">

                <span class="tob-hero-button-text">
                    SHOP COOKIES
                </span>


                <span class="tob-hero-button-circle">

                    <svg
                        viewBox="0 0 30 20"
                        aria-hidden="true"
                    >
                        <path d="M2 10H26"></path>
                        <path d="M20 4L26 10L20 16"></path>
                    </svg>

                </span>

            </a>


            {{-- =================================================
                 BOTTOM FEATURES
            ================================================== --}}
            <div class="tob-hero-features">


                {{-- FEATURE 01 --}}
                <div class="tob-hero-feature">

                    <div class="tob-feature-icon">

                        <svg
                            viewBox="0 0 40 40"
                            aria-hidden="true"
                        >
                            <circle
                                cx="20"
                                cy="20"
                                r="14"
                            ></circle>

                            <path
                                d="M14 20L18 24L27 15"
                            ></path>
                        </svg>

                    </div>


                    <span>
                        Premium<br>
                        Ingredients
                    </span>

                </div>


                <span class="tob-feature-divider"></span>


                {{-- FEATURE 02 --}}
                <div class="tob-hero-feature">

                    <div class="tob-feature-icon">

                        <svg
                            viewBox="0 0 40 40"
                            aria-hidden="true"
                        >
                            <path d="M12 29V17"></path>
                            <path d="M20 29V11"></path>
                            <path d="M28 29V15"></path>
                            <path d="M8 29H32"></path>
                        </svg>

                    </div>


                    <span>
                        Baked With<br>
                        Care
                    </span>

                </div>


                <span class="tob-feature-divider"></span>


                {{-- FEATURE 03 --}}
                <div class="tob-hero-feature">

                    <div class="tob-feature-icon">

                        <svg
                            viewBox="0 0 40 40"
                            aria-hidden="true"
                        >
                            <path
                                d="
                                    M20 31
                                    C17 27 9 22 9 15
                                    C9 10 15 8 18 12
                                    L20 15
                                    L22 12
                                    C25 8 31 10 31 15
                                    C31 22 23 27 20 31
                                "
                            ></path>
                        </svg>

                    </div>


                    <span>
                        Happiness In<br>
                        Every Bite
                    </span>

                </div>

            </div>

        </div>


        {{-- =================================================
             RIGHT HANDWRITTEN CONTENT
        ================================================== --}}
        <div class="tob-hero-right-note">

            <span class="tob-right-note-line">
                Good Cookies
            </span>

            <span class="tob-right-note-line tob-right-note-second">
                Brighter Days
            </span>


            {{-- RIGHT HEART --}}
            <svg
                class="tob-right-note-heart"
                viewBox="0 0 70 62"
                aria-hidden="true"
            >
                <path
                    d="
                        M35 55
                        C30 48 10 36 10 20
                        C10 10 22 6 29 14
                        C32 17 34 21 35 24
                        C37 20 39 17 42 14
                        C49 6 61 10 61 20
                        C61 36 41 48 35 55
                    "
                />
            </svg>

        </div>


    </div>

</section>

{{-- =========================================================
     OUR COOKIES
========================================================= --}}

<section class="tob-cookies-section" id="our-cookies">

    {{-- Decorative botanical artwork --}}
    <div class="tob-cookies-leaves" aria-hidden="true">

    <svg
        viewBox="0 0 250 175"
        xmlns="http://www.w3.org/2000/svg"
    >

        {{-- MAIN BRANCH --}}
        <path class="leaf-branch"
            d="M-18 2
               C18 18, 47 35, 78 59
               C105 79, 130 96, 164 112"
        />

        {{-- TOP BRANCH --}}
        <path class="leaf-branch"
            d="M30 27
               C44 17, 57 9, 76 -4"
        />

        {{-- MIDDLE BRANCH --}}
        <path class="leaf-branch"
            d="M61 48
               C81 37, 96 27, 112 11"
        />

        {{-- RIGHT BRANCH --}}
        <path class="leaf-branch"
            d="M92 70
               C116 62, 137 52, 160 34"
        />


        {{-- =============================================
             LEAF 1 - TOP LEFT
        ============================================== --}}

        <path class="leaf-shape"
            d="M17 18
               C27 2, 45 -3, 62 1
               C52 17, 35 25, 17 18Z"
        />

        <path class="leaf-vein"
            d="M20 17 C34 12, 46 7, 59 2"
        />

        <path class="leaf-vein"
            d="M30 14 L29 7
               M36 12 L37 5
               M43 9 L46 3
               M49 7 L54 2"
        />


        {{-- =============================================
             LEAF 2
        ============================================== --}}

        <path class="leaf-shape"
            d="M37 32
               C38 13, 49 3, 64 -4
               C68 14, 59 29, 37 32Z"
        />

        <path class="leaf-vein"
            d="M39 29 C48 17, 55 7, 63 -2"
        />

        <path class="leaf-vein"
            d="M44 23 L41 15
               M48 18 L47 10
               M53 13 L52 6
               M57 8 L57 2"
        />


        {{-- =============================================
             LEAF 3 - LEFT
        ============================================== --}}

        <path class="leaf-shape"
            d="M42 39
               C24 34, 10 37, -4 49
               C14 56, 31 52, 42 39Z"
        />

        <path class="leaf-vein"
            d="M39 40 C26 43, 12 46, -2 49"
        />

        <path class="leaf-vein"
            d="M30 42 L23 37
               M23 44 L16 39
               M15 46 L8 42
               M31 43 L25 49
               M21 46 L16 51
               M11 48 L7 52"
        />


        {{-- =============================================
             LEAF 4 - UPPER MIDDLE
        ============================================== --}}

        <path class="leaf-shape"
            d="M64 49
               C66 27, 79 15, 97 8
               C98 28, 86 44, 64 49Z"
        />

        <path class="leaf-vein"
            d="M66 46 C77 31, 86 19, 95 10"
        />

        <path class="leaf-vein"
            d="M72 38 L69 29
               M78 31 L75 22
               M83 25 L82 17
               M89 19 L89 13
               M72 38 L81 38
               M77 31 L87 32
               M83 24 L92 26"
        />


        {{-- =============================================
             LEAF 5 - LEFT LOWER
        ============================================== --}}

        <path class="leaf-shape"
            d="M67 56
               C47 51, 29 57, 18 73
               C39 78, 57 72, 67 56Z"
        />

        <path class="leaf-vein"
            d="M64 57 C48 62, 34 67, 20 72"
        />

        <path class="leaf-vein"
            d="M54 60 L47 55
               M46 63 L39 57
               M38 66 L30 61
               M54 61 L49 68
               M45 64 L40 72
               M36 67 L32 74"
        />


        {{-- =============================================
             LEAF 6 - MIDDLE LARGE
        ============================================== --}}

        <path class="leaf-shape"
            d="M91 70
               C91 44, 105 27, 124 19
               C127 43, 115 62, 91 70Z"
        />

        <path class="leaf-vein"
            d="M94 66 C105 49, 114 34, 122 21"
        />

        <path class="leaf-vein"
            d="M100 57 L97 46
               M106 48 L103 38
               M111 40 L109 31
               M116 32 L115 25

               M100 56 L110 58
               M105 48 L116 51
               M110 40 L121 43
               M115 32 L124 35"
        />


        {{-- =============================================
             LEAF 7 - RIGHT UPPER
        ============================================== --}}

        <path class="leaf-shape"
            d="M116 75
               C132 55, 151 47, 172 49
               C162 67, 141 77, 116 75Z"
        />

        <path class="leaf-vein"
            d="M119 73 C137 65, 153 57, 169 51"
        />

        <path class="leaf-vein"
            d="M130 68 L126 60
               M139 64 L136 56
               M149 60 L146 53
               M158 56 L156 51

               M131 68 L137 73
               M141 64 L147 69
               M151 60 L158 64"
        />


        {{-- =============================================
             LEAF 8 - LOWER LEFT
        ============================================== --}}

        <path class="leaf-shape"
            d="M92 78
               C73 77, 60 88, 55 105
               C75 105, 89 97, 92 78Z"
        />

        <path class="leaf-vein"
            d="M89 80 C78 88, 67 96, 57 103"
        />

        <path class="leaf-vein"
            d="M81 86 L75 80
               M74 91 L68 85
               M67 96 L61 91

               M81 87 L82 95
               M73 92 L74 100
               M65 97 L66 103"
        />


        {{-- =============================================
             LEAF 9 - LOWER MIDDLE
        ============================================== --}}

        <path class="leaf-shape"
            d="M112 91
               C103 109, 105 127, 116 143
               C129 126, 127 106, 112 91Z"
        />

        <path class="leaf-vein"
            d="M113 94 C116 111, 116 126, 116 140"
        />

        <path class="leaf-vein"
            d="M114 105 L107 100
               M115 113 L108 108
               M116 121 L109 117
               M116 129 L110 126

               M115 105 L121 100
               M116 113 L123 108
               M116 121 L123 117
               M116 129 L122 126"
        />


        {{-- =============================================
             LEAF 10 - RIGHT LOWER
        ============================================== --}}

        <path class="leaf-shape"
            d="M132 101
               C151 99, 167 108, 177 124
               C157 128, 141 120, 132 101Z"
        />

        <path class="leaf-vein"
            d="M135 103 C149 110, 162 117, 175 123"
        />

        <path class="leaf-vein"
            d="M145 108 L143 101
               M153 112 L152 105
               M161 116 L161 110
               M146 109 L141 115
               M154 113 L149 119
               M163 117 L158 123"
        />


        {{-- =============================================
             END BRANCH / SMALL LEAF
        ============================================== --}}

        <path class="leaf-shape"
            d="M158 110
               C176 100, 194 102, 210 112
               C194 124, 176 122, 158 110Z"
        />

        <path class="leaf-vein"
            d="M161 110 C177 111, 192 111, 207 112"
        />

        <path class="leaf-vein"
            d="M173 111 L168 105
               M183 111 L180 105
               M194 112 L191 107

               M173 112 L168 118
               M184 112 L181 119
               M194 112 L192 118"
        />

    </svg>

</div>


    <div class="tob-cookies-container">

        {{-- =================================================
             SECTION HEADING
        ================================================== --}}

        <div class="tob-cookies-heading tob-cookie-reveal">

            <span class="tob-cookies-eyebrow">
                OUR COOKIES
            </span>

            <h2>
                Everyone Has a Favourite!
            </h2>

            <div class="tob-cookies-heading-decoration">

                <span></span>

                <svg viewBox="0 0 30 26" aria-hidden="true">
                    <path
                        d="
                            M15 23
                            C12 19 4 14 4 8
                            C4 4 9 2 12 5
                            L15 8
                            L18 5
                            C21 2 26 4 26 8
                            C26 14 18 19 15 23
                        "
                    />
                </svg>

                <span></span>

            </div>

        </div>


        {{-- =================================================
             HANDWRITTEN NOTE
        ================================================== --}}

        <div class="tob-cookies-note tob-cookie-reveal">

            <span>Crunchy</span>
            <span>Chewy</span>
            <span>Irresistible</span>

            <svg viewBox="0 0 35 32" aria-hidden="true">
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


        {{-- =================================================
             COOKIE CAROUSEL
        ================================================== --}}

        <div class="tob-cookies-slider-wrap tob-cookie-reveal">

            {{-- LEFT ARROW --}}

            <button
                type="button"
                class="tob-cookie-arrow tob-cookie-arrow-left"
                id="tobCookiePrev"
                aria-label="Previous cookies"
            >
                <svg viewBox="0 0 24 24">
                    <path d="M15 5L8 12L15 19"></path>
                </svg>
            </button>


            {{-- PRODUCTS --}}

            <div
                class="tob-cookies-slider"
                id="tobCookiesSlider"
            >

                {{-- CHOCOLATE CHIP --}}

                <article class="tob-cookie-card">

                    <div class="tob-cookie-image">

                        <img
                            src="{{ asset('images/cookies/chocolate-chip.jpg') }}"
                            alt="Chocolate Chip"
                            loading="lazy"
                        >

                    </div>

                    <div class="tob-cookie-card-content">

                        <h3>
                            Chocolate Chip
                        </h3>

                        <p>
                            Classic &amp; Irresistible
                        </p>

                    </div>

                </article>


                {{-- JEM BADAM PISTA --}}

                <article class="tob-cookie-card">

                    <div class="tob-cookie-image">

                        <img
                            src="{{ asset('images/cookies/jem-badam-pista.jpg') }}"
                            alt="Jem Badam Pista"
                            loading="lazy"
                        >

                    </div>

                    <div class="tob-cookie-card-content">

                        <h3>
                            Jem Badam Pista
                        </h3>

                        <p>
                            With Dry Fruits
                        </p>

                    </div>

                </article>


                {{-- MIX NUTS --}}

                <article class="tob-cookie-card">

                    <div class="tob-cookie-image">

                        <img
                            src="{{ asset('images/cookies/mix-nuts.jpg') }}"
                            alt="Mix Nuts"
                            loading="lazy"
                        >

                    </div>

                    <div class="tob-cookie-card-content">

                        <h3>
                            Mix Nuts
                        </h3>

                        <p>
                            With Cashew, Badam, Pista
                        </p>

                    </div>

                </article>


                {{-- KAJU --}}

                <article class="tob-cookie-card">

                    <div class="tob-cookie-image">

                        <img
                            src="{{ asset('images/cookies/kaju.jpg') }}"
                            alt="Kaju"
                            loading="lazy"
                        >

                    </div>

                    <div class="tob-cookie-card-content">

                        <h3>
                            Kaju
                        </h3>

                        <p>
                            Rich Cashew Bites
                        </p>

                    </div>

                </article>


                {{-- COCONUT --}}

                <article class="tob-cookie-card">

                    <div class="tob-cookie-image">

                        <img
                            src="{{ asset('images/cookies/coconut.jpg') }}"
                            alt="Coconut"
                            loading="lazy"
                        >

                    </div>

                    <div class="tob-cookie-card-content">

                        <h3>
                            Coconut
                        </h3>

                        <p>
                            Tropical Delights
                        </p>

                    </div>

                </article>

            </div>


            {{-- RIGHT ARROW --}}

            <button
                type="button"
                class="tob-cookie-arrow tob-cookie-arrow-right"
                id="tobCookieNext"
                aria-label="Next cookies"
            >
                <svg viewBox="0 0 24 24">
                    <path d="M9 5L16 12L9 19"></path>
                </svg>
            </button>

        </div>

    </div>

</section>

{{-- =========================================================
     EXPLORE BY CATEGORY
========================================================= --}}

<section class="tob-category-section" id="explore-category">

    <div class="tob-category-container">

        {{-- =================================================
             HEADING
        ================================================== --}}

        <div class="tob-category-heading">

            <span class="tob-category-eyebrow">
                EXPLORE BY CATEGORY
            </span>

            <div class="tob-category-heading-decoration">

                <span class="tob-category-line"></span>

                <svg
                    class="tob-category-heart"
                    viewBox="0 0 30 26"
                    aria-hidden="true"
                >
                    <path
                        d="
                            M15 23
                            C12 19 4 14 4 8
                            C4 4 9 2 12 5
                            L15 8
                            L18 5
                            C21 2 26 4 26 8
                            C26 14 18 19 15 23
                        "
                    />
                </svg>

                <span class="tob-category-line"></span>

            </div>

        </div>


        {{-- =================================================
             CATEGORIES
        ================================================== --}}

        <div class="tob-category-grid">

            {{-- ALL COOKIES --}}

            <a href="#" class="tob-category-item">

                <div class="tob-category-image-wrap">

                    <img
                        src="{{ asset('images/categories/all-cookies.png') }}"
                        alt="All Cookies"
                        class="tob-category-image"
                        loading="lazy"
                    >

                </div>

                <span class="tob-category-name">
                    All Cookies
                </span>

            </a>


            {{-- DRY FRUIT COOKIES --}}

            <a href="#" class="tob-category-item">

                <div class="tob-category-image-wrap">

                    <img
                        src="{{ asset('images/categories/dry-fruit-cookies.png') }}"
                        alt="Dry Fruit Cookies"
                        class="tob-category-image"
                        loading="lazy"
                    >

                </div>

                <span class="tob-category-name">
                    Dry Fruit<br>
                    Cookies
                </span>

            </a>


            {{-- CHOCOLATE COOKIES --}}

            <a href="#" class="tob-category-item">

                <div class="tob-category-image-wrap">

                    <img
                        src="{{ asset('images/categories/chocolate-cookies.png') }}"
                        alt="Chocolate Cookies"
                        class="tob-category-image"
                        loading="lazy"
                    >

                </div>

                <span class="tob-category-name">
                    Chocolate<br>
                    Cookies
                </span>

            </a>


            {{-- TRADITIONAL FLAVOURS --}}

            <a href="#" class="tob-category-item">

                <div class="tob-category-image-wrap">

                    <img
                        src="{{ asset('images/categories/traditional-flavours.png') }}"
                        alt="Traditional Flavours"
                        class="tob-category-image"
                        loading="lazy"
                    >

                </div>

                <span class="tob-category-name">
                    Traditional<br>
                    Flavours
                </span>

            </a>


            {{-- COCONUT COOKIES --}}

            <a href="#" class="tob-category-item">

                <div class="tob-category-image-wrap">

                    <img
                        src="{{ asset('images/categories/coconut-cookies.png') }}"
                        alt="Coconut Cookies"
                        class="tob-category-image"
                        loading="lazy"
                    >

                </div>

                <span class="tob-category-name">
                    Coconut<br>
                    Cookies
                </span>

            </a>


            {{-- SAVOURY COOKIES --}}

            <a href="#" class="tob-category-item">

                <div class="tob-category-image-wrap">

                    <img
                        src="{{ asset('images/categories/savoury-cookies.png') }}"
                        alt="Savoury Cookies"
                        class="tob-category-image"
                        loading="lazy"
                    >

                </div>

                <span class="tob-category-name">
                    Savoury<br>
                    Cookies
                </span>

            </a>


            {{-- GIFTING --}}

            <a href="#" class="tob-category-item">

                <div class="tob-category-image-wrap">

                    <img
                        src="{{ asset('images/categories/gifting.png') }}"
                        alt="Gifting"
                        class="tob-category-image"
                        loading="lazy"
                    >

                </div>

                <span class="tob-category-name">
                    Gifting
                </span>

            </a>

        </div>

    </div>

</section>

{{-- =========================================================
     OUR STORY
========================================================= --}}

<section class="tob-story-section" id="our-story">

    <div class="tob-story-layout">

        {{-- =================================================
             LEFT COOKIE IMAGE
        ================================================== --}}

        <div class="tob-story-visual">

            <img
                src="{{ asset('images/about/story-cookies.jpg') }}"
                alt="Chocolate Chip Cookies"
                class="tob-story-main-image"
                loading="lazy"
            >

        </div>


        {{-- =================================================
             CENTER BADGE
        ================================================== --}}

        <div class="tob-story-badge">

            <div class="tob-story-badge-inner">

                <span>MORE</span>
                <span>THAN</span>
                <span>COOKIES</span>

                <span class="tob-story-badge-small">
                    IT'S A
                </span>

                <span>FEELING</span>

                <svg
                    class="tob-story-badge-heart"
                    viewBox="0 0 30 26"
                    aria-hidden="true"
                >
                    <path
                        d="
                            M15 23
                            C12 19 4 14 4 8
                            C4 4 9 2 12 5
                            L15 8
                            L18 5
                            C21 2 26 4 26 8
                            C26 14 18 19 15 23
                        "
                    />
                </svg>

            </div>

        </div>


        {{-- =================================================
             RIGHT CONTENT
        ================================================== --}}

        <div class="tob-story-content">

            <span class="tob-story-eyebrow">
                OUR STORY
            </span>

            <h2 class="tob-story-title">
                A Cookie Lover’s Dream
            </h2>

            <p class="tob-story-description">
                The Only Baker was born from a simple idea — to create
                cookies that bring joy. With the finest ingredients, timeless
                recipes and a little bit of love, we craft cookies that turn
                everyday moments into something special.
            </p>

            <a href="#" class="tob-story-button">

                <span>
                    READ OUR STORY
                </span>

                <svg
                    viewBox="0 0 30 20"
                    aria-hidden="true"
                >
                    <path d="M2 10H25"></path>
                    <path d="M19 4L25 10L19 16"></path>
                </svg>

            </a>

        </div>


        {{-- =================================================
             RIGHT BOTANICAL LEAVES - SVG CODE
        ================================================== --}}

        <div class="tob-story-leaves" aria-hidden="true">

            <svg
                viewBox="0 0 220 260"
                xmlns="http://www.w3.org/2000/svg"
            >

                {{-- MAIN STEM --}}
                <path
                    class="tob-story-branch"
                    d="
                        M211 253
                        C181 222 164 188 153 154
                        C143 124 137 91 119 56
                        C109 37 97 20 82 4
                    "
                />

                {{-- BRANCHES --}}
                <path
                    class="tob-story-branch"
                    d="M154 158 C176 151 194 138 211 120"
                />

                <path
                    class="tob-story-branch"
                    d="M145 127 C162 115 175 99 184 80"
                />

                <path
                    class="tob-story-branch"
                    d="M134 94 C114 86 98 74 84 59"
                />

                <path
                    class="tob-story-branch"
                    d="M119 57 C137 49 149 35 157 17"
                />

                <path
                    class="tob-story-branch"
                    d="M168 190 C185 185 200 175 216 159"
                />


                {{-- LEAF 1 --}}
                <path
                    class="tob-story-leaf"
                    d="
                        M180 80
                        C180 55 190 38 207 27
                        C211 49 202 70 180 80Z
                    "
                />

                <path
                    class="tob-story-vein"
                    d="M183 76 C192 59 199 44 205 30"
                />

                <path
                    class="tob-story-vein"
                    d="
                        M188 66L184 55
                        M193 57L190 47
                        M198 48L196 40

                        M188 66L198 65
                        M193 57L202 56
                    "
                />


                {{-- LEAF 2 --}}
                <path
                    class="tob-story-leaf"
                    d="
                        M158 116
                        C178 101 198 100 216 108
                        C201 124 180 128 158 116Z
                    "
                />

                <path
                    class="tob-story-vein"
                    d="M162 115 C179 111 197 109 213 109"
                />

                <path
                    class="tob-story-vein"
                    d="
                        M175 112L170 105
                        M185 110L181 103
                        M196 109L193 103

                        M175 114L170 120
                        M186 112L182 119
                        M197 111L194 117
                    "
                />


                {{-- LEAF 3 --}}
                <path
                    class="tob-story-leaf"
                    d="
                        M143 126
                        C124 112 114 94 115 75
                        C135 84 146 102 143 126Z
                    "
                />

                <path
                    class="tob-story-vein"
                    d="M140 121 C132 105 124 90 117 78"
                />

                <path
                    class="tob-story-vein"
                    d="
                        M135 111L137 100
                        M130 101L132 92
                        M125 92L127 85

                        M135 111L126 108
                        M130 101L121 98
                    "
                />


                {{-- LEAF 4 --}}
                <path
                    class="tob-story-leaf"
                    d="
                        M132 91
                        C111 92 94 83 84 67
                        C104 62 123 70 132 91Z
                    "
                />

                <path
                    class="tob-story-vein"
                    d="M128 89 C113 81 99 74 87 68"
                />

                <path
                    class="tob-story-vein"
                    d="
                        M116 82L116 74
                        M106 77L106 70
                        M97 73L98 67

                        M116 83L109 88
                        M106 78L100 83
                    "
                />


                {{-- LEAF 5 --}}
                <path
                    class="tob-story-leaf"
                    d="
                        M119 56
                        C122 34 136 18 155 10
                        C157 32 143 51 119 56Z
                    "
                />

                <path
                    class="tob-story-vein"
                    d="M122 53 C133 38 144 24 153 12"
                />

                <path
                    class="tob-story-vein"
                    d="
                        M130 43L127 34
                        M136 35L134 27
                        M143 27L141 20

                        M130 43L139 44
                        M136 35L145 36
                    "
                />


                {{-- LEAF 6 --}}
                <path
                    class="tob-story-leaf"
                    d="
                        M167 187
                        C186 169 204 166 219 172
                        C207 191 188 197 167 187Z
                    "
                />

                <path
                    class="tob-story-vein"
                    d="M171 186 C187 181 202 176 216 173"
                />

                <path
                    class="tob-story-vein"
                    d="
                        M183 182L178 175
                        M193 179L190 172
                        M203 176L201 170

                        M183 184L180 190
                        M194 181L191 188
                        M204 177L202 184
                    "
                />


                {{-- LEAF 7 --}}
                <path
                    class="tob-story-leaf"
                    d="
                        M166 188
                        C146 176 136 158 137 139
                        C157 148 169 165 166 188Z
                    "
                />

                <path
                    class="tob-story-vein"
                    d="M163 184 C155 168 146 153 139 142"
                />

                <path
                    class="tob-story-vein"
                    d="
                        M158 174L160 164
                        M153 165L155 156
                        M148 156L150 149

                        M158 174L149 171
                        M153 165L144 162
                    "
                />

            </svg>

        </div>

    </div>

</section>

{{-- =========================================================
     PREMIUM INGREDIENTS
========================================================= --}}

<section class="tob-ingredients-section" id="premium-ingredients">

    <div class="tob-ingredients-container">

        {{-- =================================================
             HEADING
        ================================================== --}}

        <div class="tob-ingredients-heading">

            <span class="tob-ingredients-eyebrow">
                ONLY THE GOOD STUFF
            </span>

            <h2>
                Premium Ingredients
            </h2>

        </div>


        {{-- =================================================
             HANDWRITTEN NOTE
        ================================================== --}}

        <div class="tob-ingredients-note" aria-hidden="true">

            <span>Real Ingredients</span>
            <span>Real Taste</span>
            <span>Our Promise</span>

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


        {{-- =================================================
             INGREDIENTS
        ================================================== --}}

        <div class="tob-ingredients-grid">

            {{-- REAL BUTTER --}}
            <div class="tob-ingredient-item">

                <div class="tob-ingredient-image-wrap">
                    <img
                        src="{{ asset('images/ingredients/real-butter.png') }}"
                        alt="Real Butter"
                        class="tob-ingredient-image"
                        loading="lazy"
                    >
                </div>

                <span class="tob-ingredient-name">
                    Real Butter
                </span>

            </div>


            {{-- ALMONDS --}}
            <div class="tob-ingredient-item">

                <div class="tob-ingredient-image-wrap">
                    <img
                        src="{{ asset('images/ingredients/almonds.png') }}"
                        alt="Almonds"
                        class="tob-ingredient-image"
                        loading="lazy"
                    >
                </div>

                <span class="tob-ingredient-name">
                    Almonds
                </span>

            </div>


            {{-- PISTACHIOS --}}
            <div class="tob-ingredient-item">

                <div class="tob-ingredient-image-wrap">
                    <img
                        src="{{ asset('images/ingredients/pistachios.png') }}"
                        alt="Pistachios"
                        class="tob-ingredient-image"
                        loading="lazy"
                    >
                </div>

                <span class="tob-ingredient-name">
                    Pistachios
                </span>

            </div>


            {{-- CASHEWS --}}
            <div class="tob-ingredient-item">

                <div class="tob-ingredient-image-wrap">
                    <img
                        src="{{ asset('images/ingredients/cashews.png') }}"
                        alt="Cashews"
                        class="tob-ingredient-image"
                        loading="lazy"
                    >
                </div>

                <span class="tob-ingredient-name">
                    Cashews
                </span>

            </div>


            {{-- BELGIAN CHOCOLATE --}}
            <div class="tob-ingredient-item">

                <div class="tob-ingredient-image-wrap">
                    <img
                        src="{{ asset('images/ingredients/belgian-chocolate.png') }}"
                        alt="Belgian Chocolate"
                        class="tob-ingredient-image"
                        loading="lazy"
                    >
                </div>

                <span class="tob-ingredient-name">
                    Belgian<br>
                    Chocolate
                </span>

            </div>


            {{-- COCONUT --}}
            <div class="tob-ingredient-item">

                <div class="tob-ingredient-image-wrap">
                    <img
                        src="{{ asset('images/ingredients/coconut.png') }}"
                        alt="Coconut"
                        class="tob-ingredient-image"
                        loading="lazy"
                    >
                </div>

                <span class="tob-ingredient-name">
                    Coconut
                </span>

            </div>


            {{-- WHOLE WHEAT FLOUR --}}
            <div class="tob-ingredient-item">

                <div class="tob-ingredient-image-wrap">
                    <img
                        src="{{ asset('images/ingredients/whole-wheat-flour.png') }}"
                        alt="Whole Wheat Flour"
                        class="tob-ingredient-image"
                        loading="lazy"
                    >
                </div>

                <span class="tob-ingredient-name">
                    Whole Wheat<br>
                    Flour
                </span>

            </div>

        </div>

    </div>

</section>

{{-- =========================================================
     THE ART OF GIFTING
========================================================= --}}

<section class="tob-gifting-section" id="gifting">

    <div class="tob-gifting-layout">

        {{-- =================================================
             LEFT GIFT IMAGE
        ================================================== --}}

        <div class="tob-gifting-visual">

            <img
                src="{{ asset('images/gifting/gifting-box.jpg') }}"
                alt="The Only Baker Gift Box"
                class="tob-gifting-main-image"
                loading="lazy"
            >

        </div>


        {{-- =================================================
             MAIN CONTENT
        ================================================== --}}

        <div class="tob-gifting-content">

            <span class="tob-gifting-eyebrow">
                THE ART OF GIFTING
            </span>

            <h2 class="tob-gifting-title">
                Make Every Occasion Sweeter
            </h2>

            <p class="tob-gifting-description">
                Thoughtful cookie gift boxes, beautifully packed
                to make your loved ones smile. Perfect for festivals,
                birthdays, anniversaries or just because.
            </p>

            <a href="#" class="tob-gifting-button">

                <span>
                    EXPLORE GIFTING
                </span>

                <svg
                    viewBox="0 0 30 20"
                    aria-hidden="true"
                >
                    <path d="M2 10H25"></path>
                    <path d="M19 4L25 10L19 16"></path>
                </svg>

            </a>

        </div>


        {{-- =================================================
             HANDWRITTEN NOTE
        ================================================== --}}

        <div class="tob-gifting-note" aria-hidden="true">

            <span>Birthdays</span>
            <span>Festivals</span>
            <span>Corporate Gifts</span>
            <span>&amp; More</span>

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

</section>

{{-- =========================================================
     SWEET WORDS / TESTIMONIALS
========================================================= --}}

<section class="tob-testimonials-section" id="testimonials">

    <div class="tob-testimonials-container">

        {{-- =================================================
             HEADING
        ================================================== --}}

        <div class="tob-testimonials-heading">

            <span class="tob-testimonials-eyebrow">
                SWEET WORDS
            </span>

            <h2>
                What Our Customers Say
            </h2>

            <div class="tob-testimonials-decoration">

                <span></span>

                <svg viewBox="0 0 30 26" aria-hidden="true">
                    <path
                        d="
                            M15 23
                            C12 19 4 14 4 8
                            C4 4 9 2 12 5
                            L15 8
                            L18 5
                            C21 2 26 4 26 8
                            C26 14 18 19 15 23
                        "
                    />
                </svg>

                <span></span>

            </div>

        </div>


        {{-- =================================================
             TESTIMONIAL CARDS
        ================================================== --}}

        <div class="tob-testimonials-grid">

            {{-- =================================================
                 PRIYA S.
            ================================================== --}}

            <article class="tob-testimonial-card">

                <div class="tob-testimonial-quote">
                    “
                </div>

                <div class="tob-testimonial-stars"
                     aria-label="5 out of 5 stars">

                    <span>★</span>
                    <span>★</span>
                    <span>★</span>
                    <span>★</span>
                    <span>★</span>

                </div>

                <p class="tob-testimonial-text">
                    Absolutely delicious! The cookies
                    are fresh, crunchy and full of flavour.
                    Truly premium quality.
                </p>

                <div class="tob-testimonial-customer">

                    <div class="tob-testimonial-avatar">

                        <img
                            src="{{ asset('images/testimonials/priya-s.jpg') }}"
                            alt="Priya S."
                            loading="lazy"
                        >

                    </div>

                    <strong>
                        Priya S.
                    </strong>

                </div>

            </article>


            {{-- =================================================
                 ROHIT K.
            ================================================== --}}

            <article class="tob-testimonial-card">

                <div class="tob-testimonial-quote">
                    “
                </div>

                <div class="tob-testimonial-stars"
                     aria-label="5 out of 5 stars">

                    <span>★</span>
                    <span>★</span>
                    <span>★</span>
                    <span>★</span>
                    <span>★</span>

                </div>

                <p class="tob-testimonial-text">
                    Loved the Jem Badam Pista cookies!
                    Perfect balance of nuts and texture.
                    Will definitely order again.
                </p>

                <div class="tob-testimonial-customer">

                    <div class="tob-testimonial-avatar">

                        <img
                            src="{{ asset('images/testimonials/rohit-k.jpg') }}"
                            alt="Rohit K."
                            loading="lazy"
                        >

                    </div>

                    <strong>
                        Rohit K.
                    </strong>

                </div>

            </article>


            {{-- =================================================
                 SNEHA M.
            ================================================== --}}

            <article class="tob-testimonial-card">

                <div class="tob-testimonial-quote">
                    “
                </div>

                <div class="tob-testimonial-stars"
                     aria-label="5 out of 5 stars">

                    <span>★</span>
                    <span>★</span>
                    <span>★</span>
                    <span>★</span>
                    <span>★</span>

                </div>

                <p class="tob-testimonial-text">
                    Beautiful packaging and amazing taste.
                    I ordered a gift box and everyone
                    loved it!
                </p>

                <div class="tob-testimonial-customer">

                    <div class="tob-testimonial-avatar">

                        <img
                            src="{{ asset('images/testimonials/sneha-m.jpg') }}"
                            alt="Sneha M."
                            loading="lazy"
                        >

                    </div>

                    <strong>
                        Sneha M.
                    </strong>

                </div>

            </article>

        </div>

    </div>

</section>

{{-- =========================================================
     FOLLOW OUR JOURNEY
========================================================= --}}

<section class="tob-journey-section" id="our-journey">

    <div class="tob-journey-container">

        {{-- =================================================
             HEADING
        ================================================== --}}

        <div class="tob-journey-heading">

            <span class="tob-journey-eyebrow">
                FOLLOW OUR JOURNEY
            </span>

            <h2>
                @theonlybaker_
            </h2>

            <div class="tob-journey-decoration">

                <span class="tob-journey-line"></span>

                <svg
                    class="tob-journey-heart"
                    viewBox="0 0 30 26"
                    aria-hidden="true"
                >
                    <path
                        d="
                            M15 23
                            C12 19 4 14 4 8
                            C4 4 9 2 12 5
                            L15 8
                            L18 5
                            C21 2 26 4 26 8
                            C26 14 18 19 15 23
                        "
                    />
                </svg>

                <span class="tob-journey-line"></span>

            </div>

        </div>


        {{-- =================================================
             INSTAGRAM GALLERY
        ================================================== --}}

        <div class="tob-journey-gallery">

            {{-- IMAGE 1 --}}
            <a href="#" class="tob-journey-card">

                <img
                    src="{{ asset('images/gallery/journey-1.jpg') }}"
                    alt="The Only Baker Cookies"
                    loading="lazy"
                >

                <span class="tob-journey-overlay">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="5"></rect>
                        <circle cx="12" cy="12" r="4"></circle>
                        <circle cx="17.5" cy="6.5" r="1"></circle>
                    </svg>
                </span>

            </a>


            {{-- IMAGE 2 --}}
            <a href="#" class="tob-journey-card">

                <img
                    src="{{ asset('images/gallery/journey-2.jpg') }}"
                    alt="The Only Baker Chocolate Cookies"
                    loading="lazy"
                >

                <span class="tob-journey-overlay">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="5"></rect>
                        <circle cx="12" cy="12" r="4"></circle>
                        <circle cx="17.5" cy="6.5" r="1"></circle>
                    </svg>
                </span>

            </a>


            {{-- IMAGE 3 --}}
            <a href="#" class="tob-journey-card">

                <img
                    src="{{ asset('images/gallery/journey-3.jpg') }}"
                    alt="The Only Baker Cookies"
                    loading="lazy"
                >

                <span class="tob-journey-overlay">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="5"></rect>
                        <circle cx="12" cy="12" r="4"></circle>
                        <circle cx="17.5" cy="6.5" r="1"></circle>
                    </svg>
                </span>

            </a>


            {{-- IMAGE 4 --}}
            <a href="#" class="tob-journey-card">

                <img
                    src="{{ asset('images/gallery/journey-4.jpg') }}"
                    alt="The Only Baker Traditional Cookies"
                    loading="lazy"
                >

                <span class="tob-journey-overlay">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="5"></rect>
                        <circle cx="12" cy="12" r="4"></circle>
                        <circle cx="17.5" cy="6.5" r="1"></circle>
                    </svg>
                </span>

            </a>


            {{-- IMAGE 5 --}}
            <a href="#" class="tob-journey-card">

                <img
                    src="{{ asset('images/gallery/journey-5.jpg') }}"
                    alt="The Only Baker Gift Box"
                    loading="lazy"
                >

                <span class="tob-journey-overlay">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="5"></rect>
                        <circle cx="12" cy="12" r="4"></circle>
                        <circle cx="17.5" cy="6.5" r="1"></circle>
                    </svg>
                </span>

            </a>


            {{-- IMAGE 6 --}}
            <a href="#" class="tob-journey-card">

                <img
                    src="{{ asset('images/gallery/journey-6.jpg') }}"
                    alt="The Only Baker Cookie"
                    loading="lazy"
                >

                <span class="tob-journey-overlay">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="5"></rect>
                        <circle cx="12" cy="12" r="4"></circle>
                        <circle cx="17.5" cy="6.5" r="1"></circle>
                    </svg>
                </span>

            </a>


            {{-- =================================================
                 FOLLOW US ON INSTAGRAM
            ================================================== --}}

            <a href="#"
               class="tob-journey-instagram-card">

                <svg
                    class="tob-instagram-icon"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
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

                <span class="tob-instagram-text">
                    Follow Us<br>
                    on Instagram
                </span>

                <svg
                    class="tob-instagram-arrow"
                    viewBox="0 0 30 20"
                    aria-hidden="true"
                >
                    <path d="M2 10H25"></path>
                    <path d="M19 4L25 10L19 16"></path>
                </svg>

            </a>

        </div>

    </div>

</section>

@endsection