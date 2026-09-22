document.addEventListener("DOMContentLoaded", function () {

    const toggle = document.getElementById("tobMobileToggle");
    const menu = document.getElementById("tobMobileMenu");

    if (!toggle || !menu) {
        return;
    }

    toggle.addEventListener("click", function () {

        const isOpen = menu.classList.toggle("show");

        toggle.setAttribute(
            "aria-expanded",
            isOpen ? "true" : "false"
        );

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

            return card.getBoundingClientRect().width + gap;

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


    /* =====================================================
       REDUCED MOTION
    ====================================================== */

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


    /* =====================================================
       SCROLL REVEAL
    ====================================================== */

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


    /* =====================================================
       REDUCED MOTION
    ====================================================== */

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


    /* =====================================================
       SCROLL REVEAL
    ====================================================== */

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


    /* =====================================================
       REDUCED MOTION
    ====================================================== */

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


    /* =====================================================
       SCROLL REVEAL
    ====================================================== */

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


    /* =====================================================
       REDUCED MOTION
    ====================================================== */

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


    /* =====================================================
       SCROLL REVEAL
    ====================================================== */

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


    /* =====================================================
       REDUCED MOTION
    ====================================================== */

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


    /* =====================================================
       SCROLL REVEAL
    ====================================================== */

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