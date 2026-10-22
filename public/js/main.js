/* =========================================================
   THE ONLY BAKER
   COMPLETE MAIN JAVASCRIPT
========================================================= */

/* ==========================================
   MOBILE NAVIGATION + SCROLL LOCK
========================================== */

document.addEventListener("DOMContentLoaded", function () {

    const toggle = document.getElementById("tobMobileToggle");
    const menu = document.getElementById("tobMobileMenu");

    if (!toggle || !menu) return;

    function setMenuState(isOpen) {

        menu.classList.toggle("show", isOpen);

        toggle.setAttribute(
            "aria-expanded",
            String(isOpen)
        );

        toggle.setAttribute(
            "aria-label",
            isOpen ? "Close menu" : "Open menu"
        );

        document.documentElement.classList.toggle(
            "tob-mobile-menu-locked",
            isOpen
        );

        document.body.classList.toggle(
            "tob-mobile-menu-locked",
            isOpen
        );
    }

    toggle.addEventListener("click", function () {

        const isOpen = !menu.classList.contains("show");

        setMenuState(isOpen);

    });

    // Close with Escape key
    document.addEventListener("keydown", function (event) {

        if (
            event.key === "Escape" &&
            menu.classList.contains("show")
        ) {
            setMenuState(false);
            toggle.focus();
        }

    });

    // Reset when switching to desktop
    window.addEventListener("resize", function () {

        if (window.innerWidth > 900) {
            setMenuState(false);
        }

    });

});


/* =========================================================
   OUR COOKIES
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const cookiesSection =
        document.querySelector(".tob-cookies-section");

    const revealItems =
        document.querySelectorAll(".tob-cookie-reveal");

    const slider =
        document.getElementById("tobCookiesSlider");

    const prevButton =
        document.getElementById("tobCookiePrev");

    const nextButton =
        document.getElementById("tobCookieNext");


    /* =====================================================
       SCROLL REVEAL
    ====================================================== */

    if (cookiesSection) {

        const observer =
            new IntersectionObserver(
                function (entries, observerInstance) {

                    entries.forEach(function (entry) {

                        if (!entry.isIntersecting) {
                            return;
                        }

                        cookiesSection.classList.add(
                            "tob-cookies-visible"
                        );

                        revealItems.forEach(
                            function (item, index) {

                                setTimeout(
                                    function () {

                                        item.classList.add(
                                            "tob-cookie-visible"
                                        );

                                    },
                                    index * 100
                                );

                            }
                        );

                        observerInstance.unobserve(
                            entry.target
                        );

                    });

                },
                {
                    threshold: 0.15
                }
            );

        observer.observe(cookiesSection);

    }


    /* =====================================================
       SLIDER
    ====================================================== */

    if (
        slider &&
        prevButton &&
        nextButton
    ) {

        function getScrollAmount() {

            const card =
                slider.querySelector(
                    ".tob-cookie-card"
                );

            if (!card) {
                return 250;
            }

            const sliderStyle =
                window.getComputedStyle(slider);

            const gap =
                parseFloat(sliderStyle.gap) || 0;

            return (
                card.getBoundingClientRect().width +
                gap
            );

        }


        nextButton.addEventListener(
            "click",
            function () {

                slider.scrollBy({
                    left: getScrollAmount(),
                    behavior: "smooth"
                });

            }
        );


        prevButton.addEventListener(
            "click",
            function () {

                slider.scrollBy({
                    left: -getScrollAmount(),
                    behavior: "smooth"
                });

            }
        );

    }

});


/* =========================================================
   EXPLORE BY CATEGORY ANIMATION
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const categorySection =
        document.querySelector(".tob-category-section");

    if (!categorySection) {
        return;
    }


    const reducedMotion =
        window.matchMedia(
            "(prefers-reduced-motion: reduce)"
        ).matches;


    if (reducedMotion) {

        categorySection.classList.add(
            "tob-category-visible"
        );

        return;
    }


    const categoryObserver =
        new IntersectionObserver(

            function (entries, observer) {

                entries.forEach(function (entry) {

                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add(
                        "tob-category-visible"
                    );

                    observer.unobserve(
                        entry.target
                    );

                });

            },

            {
                threshold: 0.18
            }

        );


    categoryObserver.observe(
        categorySection
    );

});


/* =========================================================
   OUR STORY ANIMATION
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const storySection =
        document.querySelector(".tob-story-section");

    if (!storySection) {
        return;
    }


    const prefersReducedMotion =
        window.matchMedia(
            "(prefers-reduced-motion: reduce)"
        ).matches;


    if (prefersReducedMotion) {

        storySection.classList.add(
            "tob-story-visible"
        );

        return;
    }


    const storyObserver =
        new IntersectionObserver(

            function (entries, observer) {

                entries.forEach(function (entry) {

                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add(
                        "tob-story-visible"
                    );

                    observer.unobserve(
                        entry.target
                    );

                });

            },

            {
                threshold: 0.18
            }

        );


    storyObserver.observe(
        storySection
    );

});


/* =========================================================
   PREMIUM INGREDIENTS ANIMATION
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const ingredientsSection =
        document.querySelector(
            ".tob-ingredients-section"
        );

    if (!ingredientsSection) {
        return;
    }


    const reducedMotion =
        window.matchMedia(
            "(prefers-reduced-motion: reduce)"
        ).matches;


    if (reducedMotion) {

        ingredientsSection.classList.add(
            "tob-ingredients-visible"
        );

        return;
    }


    const ingredientsObserver =
        new IntersectionObserver(

            function (entries, observer) {

                entries.forEach(function (entry) {

                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add(
                        "tob-ingredients-visible"
                    );

                    observer.unobserve(
                        entry.target
                    );

                });

            },

            {
                threshold: 0.18
            }

        );


    ingredientsObserver.observe(
        ingredientsSection
    );

});


/* =========================================================
   GIFTING SECTION ANIMATION
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const giftingSection =
        document.querySelector(
            ".tob-gifting-section"
        );

    if (!giftingSection) {
        return;
    }


    const reducedMotion =
        window.matchMedia(
            "(prefers-reduced-motion: reduce)"
        ).matches;


    if (reducedMotion) {

        giftingSection.classList.add(
            "tob-gifting-visible"
        );

        return;
    }


    const giftingObserver =
        new IntersectionObserver(

            function (entries, observer) {

                entries.forEach(function (entry) {

                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add(
                        "tob-gifting-visible"
                    );

                    observer.unobserve(
                        entry.target
                    );

                });

            },

            {
                threshold: 0.18
            }

        );


    giftingObserver.observe(
        giftingSection
    );

});


/* =========================================================
   TESTIMONIALS ANIMATION
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const testimonialsSection =
        document.querySelector(
            ".tob-testimonials-section"
        );

    if (!testimonialsSection) {
        return;
    }


    const reducedMotion =
        window.matchMedia(
            "(prefers-reduced-motion: reduce)"
        ).matches;


    if (reducedMotion) {

        testimonialsSection.classList.add(
            "tob-testimonials-visible"
        );

        return;
    }


    const testimonialsObserver =
        new IntersectionObserver(

            function (entries, observer) {

                entries.forEach(function (entry) {

                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add(
                        "tob-testimonials-visible"
                    );

                    observer.unobserve(
                        entry.target
                    );

                });

            },

            {
                threshold: 0.18
            }

        );


    testimonialsObserver.observe(
        testimonialsSection
    );

});


/* =========================================================
   FOLLOW OUR JOURNEY ANIMATION
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const journeySection =
        document.querySelector(
            ".tob-journey-section"
        );

    if (!journeySection) {
        return;
    }


    const reducedMotion =
        window.matchMedia(
            "(prefers-reduced-motion: reduce)"
        ).matches;


    if (reducedMotion) {

        journeySection.classList.add(
            "tob-journey-visible"
        );

        return;
    }


    const journeyObserver =
        new IntersectionObserver(

            function (entries, observer) {

                entries.forEach(function (entry) {

                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add(
                        "tob-journey-visible"
                    );

                    observer.unobserve(
                        entry.target
                    );

                });

            },

            {
                threshold: 0.15
            }

        );


    journeyObserver.observe(
        journeySection
    );

});


/* =========================================================
   FOOTER SCROLL ANIMATION
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const footer =
        document.querySelector(".tob-footer");

    if (!footer) {
        return;
    }


    const reducedMotion =
        window.matchMedia(
            "(prefers-reduced-motion: reduce)"
        ).matches;


    if (reducedMotion) {

        footer.classList.add(
            "tob-footer-visible"
        );

        return;
    }


    const footerObserver =
        new IntersectionObserver(

            function (entries, observer) {

                entries.forEach(function (entry) {

                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add(
                        "tob-footer-visible"
                    );

                    observer.unobserve(
                        entry.target
                    );

                });

            },

            {
                threshold: 0.12
            }

        );


    footerObserver.observe(footer);

});


/* =========================================================
   THE ONLY BAKER - HERO BACKGROUND SLIDER
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const slider =
        document.getElementById("tobHeroSlider");

    if (!slider) {
        return;
    }


    const slides = Array.from(
        slider.querySelectorAll(
            ".tob-hero-slide"
        )
    );


    if (slides.length <= 1) {
        return;
    }


    /* =====================================================
       SETTINGS
    ====================================================== */

    let currentSlide = 0;

    let autoSlideTimer = null;

    const autoSlideDelay = 5000;

    const swipeThreshold = 45;


    /* =====================================================
       DRAG / SWIPE VALUES
    ====================================================== */

    let pointerStartX = 0;

    let pointerCurrentX = 0;

    let isDragging = false;


    /* =====================================================
       SHOW SLIDE
    ====================================================== */

    function showSlide(index) {

        if (index < 0) {
            index = slides.length - 1;
        }

        if (index >= slides.length) {
            index = 0;
        }

        slides.forEach(function (slide) {

            slide.classList.remove(
                "active"
            );

        });

        slides[index].classList.add(
            "active"
        );

        currentSlide = index;

    }


    /* =====================================================
       NEXT
    ====================================================== */

    function nextSlide() {

        showSlide(
            currentSlide + 1
        );

    }


    /* =====================================================
       PREVIOUS
    ====================================================== */

    function previousSlide() {

        showSlide(
            currentSlide - 1
        );

    }


    /* =====================================================
       AUTO PLAY
    ====================================================== */

    function startAutoSlide() {

        stopAutoSlide();

        autoSlideTimer =
            setInterval(
                function () {

                    nextSlide();

                },
                autoSlideDelay
            );

    }


    function stopAutoSlide() {

        if (autoSlideTimer) {

            clearInterval(
                autoSlideTimer
            );

            autoSlideTimer = null;

        }

    }


    function restartAutoSlide() {

        stopAutoSlide();

        startAutoSlide();

    }


    /* =====================================================
       POINTER DOWN
    ====================================================== */

    slider.addEventListener(
        "pointerdown",
        function (event) {

            if (
                event.pointerType === "mouse" &&
                event.button !== 0
            ) {
                return;
            }


            isDragging = true;

            pointerStartX =
                event.clientX;

            pointerCurrentX =
                event.clientX;


            slider.classList.add(
                "tob-slider-dragging"
            );


            stopAutoSlide();


            try {

                slider.setPointerCapture(
                    event.pointerId
                );

            } catch (error) {
                // Nothing required.
            }

        }
    );


    /* =====================================================
       POINTER MOVE
    ====================================================== */

    slider.addEventListener(
        "pointermove",
        function (event) {

            if (!isDragging) {
                return;
            }

            pointerCurrentX =
                event.clientX;

        }
    );


    /* =====================================================
       END SWIPE / DRAG
    ====================================================== */

    function finishSwipe(event) {

        if (!isDragging) {
            return;
        }


        isDragging = false;


        slider.classList.remove(
            "tob-slider-dragging"
        );


        const difference =
            pointerCurrentX -
            pointerStartX;


        if (
            difference <
            -swipeThreshold
        ) {

            nextSlide();

        }

        else if (
            difference >
            swipeThreshold
        ) {

            previousSlide();

        }


        try {

            slider.releasePointerCapture(
                event.pointerId
            );

        } catch (error) {
            // Nothing required.
        }


        restartAutoSlide();

    }


    slider.addEventListener(
        "pointerup",
        finishSwipe
    );


    slider.addEventListener(
        "pointercancel",
        finishSwipe
    );


    /* =====================================================
       PAUSE WHEN TAB IS HIDDEN
    ====================================================== */

    document.addEventListener(
        "visibilitychange",
        function () {

            if (document.hidden) {

                stopAutoSlide();

            } else {

                startAutoSlide();

            }

        }
    );


    /* =====================================================
       INITIALIZE
    ====================================================== */

    showSlide(0);

    startAutoSlide();

});



/* =========================================================
   MOBILE SHOP ACCORDION
========================================================= */

document.addEventListener("DOMContentLoaded", function () {
    const shopToggle = document.getElementById("tobMobileShopToggle");
    const shopCategories = document.getElementById("tobMobileShopCategories");
    const shopRow = document.querySelector(".tob-mobile-shop-row");

    if (!shopToggle || !shopCategories) return;

    function toggleShop() {
        const isOpen = shopCategories.classList.toggle("show");

        shopToggle.classList.toggle("active", isOpen);

        // Keep existing CSS + icon animation working.
        shopRow?.classList.toggle("active", isOpen);

        shopToggle.setAttribute("aria-expanded", String(isOpen));
        shopToggle.setAttribute(
            "aria-label",
            isOpen ? "Close Shop Categories" : "Open Shop Categories"
        );
    }

    shopToggle.addEventListener("click", toggleShop);
});


/* =========================================================
   GLOBAL PRODUCT SEARCH
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    /* =====================================================
       ELEMENTS / DATA
    ====================================================== */

    const searchButtons =
        document.querySelectorAll(
            '.tob-actions > button[aria-label="Search"], [data-tob-search-open]'
        );


    if (!searchButtons.length) {
        return;
    }


    const products =
        Array.isArray(
            window.TheOnlyBakerSearchProducts
        )
            ? window.TheOnlyBakerSearchProducts
            : [];


    const shopUrl =
        window.TheOnlyBakerSearchConfig &&
        window.TheOnlyBakerSearchConfig.shopUrl

            ? window.TheOnlyBakerSearchConfig.shopUrl

            : "/shop";


    /* =====================================================
       HELPERS
    ====================================================== */

    function escapeHtml(value) {

        return String(value ?? "")
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;")
            .replaceAll("'", "&#039;");

    }


    function escapeAttribute(value) {

        return escapeHtml(value);

    }


    function normalize(value) {

        return String(value ?? "")
            .toLowerCase()
            .trim();

    }


    function categoryLabel(category) {

        const labels = {

            "dry-fruit":
                "Dry Fruit Cookies",

            "chocolate":
                "Chocolate Cookies",

            "classic":
                "Classic Cookies"

        };


        return (
            labels[category] ||
            "Cookies"
        );

    }


    /* =====================================================
       CREATE SEARCH OVERLAY
    ====================================================== */

    const overlay =
        document.createElement("div");


    overlay.className =
        "tob-search-overlay";


    overlay.id =
        "tobSearchOverlay";


    overlay.setAttribute(
        "aria-hidden",
        "true"
    );


    overlay.innerHTML = `

        <section
            class="tob-search-panel"
            role="dialog"
            aria-modal="true"
            aria-label="Search products"
        >

            <div class="tob-search-head">

                <svg
                    class="tob-search-head-icon"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <circle
                        cx="11"
                        cy="11"
                        r="7"
                    ></circle>

                    <path
                        d="M20 20l-4.1-4.1"
                    ></path>
                </svg>


                <input
                    type="search"
                    class="tob-search-input"
                    id="tobSearchInput"
                    placeholder="Search cookies..."
                    autocomplete="off"
                    spellcheck="false"
                    aria-label="Search products"
                >


                <button
                    type="button"
                    class="tob-search-close"
                    id="tobSearchClose"
                    aria-label="Close search"
                >

                    <svg
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path
                            d="M5 5l14 14"
                        ></path>

                        <path
                            d="M19 5L5 19"
                        ></path>
                    </svg>

                </button>

            </div>


            <div class="tob-search-body">

                <p
                    class="tob-search-kicker"
                    id="tobSearchKicker"
                >
                    Our Cookies
                </p>


                <div
                    class="tob-search-results"
                    id="tobSearchResults"
                    aria-live="polite"
                ></div>

            </div>


            <div class="tob-search-footer">

                <a
                    class="tob-search-view-all"
                    href="${escapeAttribute(shopUrl)}"
                >
                    View All Products
                </a>

            </div>

        </section>

    `;


    document.body.appendChild(
        overlay
    );


    /* =====================================================
       SEARCH ELEMENTS
    ====================================================== */

    const input =
        document.getElementById(
            "tobSearchInput"
        );


    const closeButton =
        document.getElementById(
            "tobSearchClose"
        );


    const results =
        document.getElementById(
            "tobSearchResults"
        );


    const kicker =
        document.getElementById(
            "tobSearchKicker"
        );


    let lastFocusedElement = null;


    /* =====================================================
       RENDER SEARCH PRODUCTS
    ====================================================== */

    function renderProducts(
        productList,
        query
    ) {

        results.innerHTML = "";


        /* NO PRODUCT DATA */

        if (!products.length) {

            kicker.textContent =
                "Search";


            results.innerHTML = `

                <div class="tob-search-empty">

                    <h3
                        class="tob-search-empty-title"
                    >
                        Products unavailable
                    </h3>

                    <p
                        class="tob-search-empty-text"
                    >
                        Please refresh the page
                        and try again.
                    </p>

                </div>

            `;


            return;

        }


        /* NO SEARCH RESULT */

        if (!productList.length) {

            kicker.textContent =
                "Search Results";


            results.innerHTML = `

                <div class="tob-search-empty">

                    <h3
                        class="tob-search-empty-title"
                    >
                        No cookies found
                    </h3>

                    <p
                        class="tob-search-empty-text"
                    >
                        We couldn't find a product
                        matching
                        “${escapeHtml(query)}”.
                    </p>

                </div>

            `;


            return;

        }


        /* RESULT COUNT */

        kicker.textContent =
            query

                ? `${productList.length} Result${
                    productList.length === 1
                        ? ""
                        : "s"
                }`

                : "Our Cookies";


        /* PRODUCTS */

        productList.forEach(
            function (product) {

                const item =
                    document.createElement(
                        "a"
                    );


                item.className =
                    "tob-search-result";


                item.href =
                    product.url;


                item.innerHTML = `

                    <img
                        class="tob-search-result-image"
                        src="${escapeAttribute(
                            product.image
                        )}"
                        alt="${escapeAttribute(
                            product.name
                        )}"
                        loading="lazy"
                    >


                    <span
                        class="tob-search-result-copy"
                    >

                        <span
                            class="tob-search-result-name"
                        >
                            ${escapeHtml(
                                product.name
                            )}
                        </span>


                        <span
                            class="tob-search-result-category"
                        >
                            ${escapeHtml(
                                categoryLabel(
                                    product.category
                                )
                            )}
                        </span>

                    </span>


                    <span
                        class="tob-search-result-price"
                    >
                        ₹${Number(
                            product.price
                        ).toLocaleString(
                            "en-IN"
                        )}
                    </span>

                `;


                results.appendChild(
                    item
                );

            }
        );

    }


    /* =====================================================
       FILTER PRODUCTS
    ====================================================== */

    function filterProducts(query) {

        const term =
            normalize(query);


        if (!term) {

            return products.slice(
                0,
                6
            );

        }


        return products.filter(
            function (product) {

                const searchableText = [

                    product.name,

                    product.slug,

                    product.category

                ]
                    .map(normalize)
                    .join(" ");


                return searchableText.includes(
                    term
                );

            }
        );

    }


    /* =====================================================
       OPEN SEARCH
    ====================================================== */

    function openSearch() {

        lastFocusedElement =
            document.activeElement;


        overlay.classList.add(
            "show"
        );


        overlay.setAttribute(
            "aria-hidden",
            "false"
        );


        document.body.classList.add(
            "tob-search-lock"
        );


        input.value = "";


        renderProducts(
            products.slice(0, 6),
            ""
        );


        window.setTimeout(
            function () {

                input.focus();

            },
            50
        );

    }


    /* =====================================================
       CLOSE SEARCH
    ====================================================== */

    function closeSearch() {

        overlay.classList.remove(
            "show"
        );


        overlay.setAttribute(
            "aria-hidden",
            "true"
        );


        document.body.classList.remove(
            "tob-search-lock"
        );


        if (
            lastFocusedElement &&
            typeof lastFocusedElement.focus
                === "function"
        ) {

            lastFocusedElement.focus();

        }

    }


    /* =====================================================
       SEARCH ICON CLICK
    ====================================================== */

    searchButtons.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function (event) {

                    event.preventDefault();

                    openSearch();

                }
            );

        }
    );


    /* =====================================================
       LIVE SEARCH
    ====================================================== */

    input.addEventListener(
        "input",
        function () {

            const query =
                input.value;


            renderProducts(
                filterProducts(query),
                query.trim()
            );

        }
    );


    /* =====================================================
       CLOSE BUTTON
    ====================================================== */

    closeButton.addEventListener(
        "click",
        closeSearch
    );


    /* =====================================================
       OUTSIDE CLICK CLOSE
    ====================================================== */

    overlay.addEventListener(
        "mousedown",
        function (event) {

            if (
                event.target === overlay
            ) {

                closeSearch();

            }

        }
    );


    /* =====================================================
       ESCAPE KEY CLOSE
    ====================================================== */

    document.addEventListener(
        "keydown",
        function (event) {

            if (
                event.key === "Escape" &&
                overlay.classList.contains(
                    "show"
                )
            ) {

                closeSearch();

            }

        }
    );

});




/* ==========================================
   DESKTOP SHOP DROPDOWN — HOVER + CLICK
========================================== */

document.addEventListener("DOMContentLoaded", function () {
    const dropdown = document.getElementById("tobDesktopShopDropdown");
    const toggle = document.getElementById("tobDesktopShopToggle");

    if (!dropdown || !toggle) return;

    const desktopQuery = window.matchMedia("(min-width: 901px)");

    function setDropdownState(isOpen) {
        dropdown.classList.toggle("is-open", isOpen);
        toggle.setAttribute("aria-expanded", String(isOpen));
    }

    dropdown.addEventListener("mouseenter", function () {
        if (desktopQuery.matches) {
            setDropdownState(true);
        }
    });

    dropdown.addEventListener("mouseleave", function () {
        if (desktopQuery.matches) {
            setDropdownState(false);
        }
    });

    toggle.addEventListener("click", function () {
        setDropdownState(!dropdown.classList.contains("is-open"));
    });

    document.addEventListener("click", function (event) {
        if (!dropdown.contains(event.target)) {
            setDropdownState(false);
        }
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            setDropdownState(false);
            toggle.focus();
        }
    });
});

