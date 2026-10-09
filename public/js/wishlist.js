/* =========================================================
   THE ONLY BAKER
   SHARED WISHLIST SYSTEM
========================================================= */

(function () {

    "use strict";


    /* =====================================================
       CSRF TOKEN
    ====================================================== */

    function getCsrfToken() {

        const token = document.querySelector(
            'meta[name="csrf-token"]'
        );

        return token
            ? token.getAttribute("content")
            : "";
    }


    /* =====================================================
       UPDATE NAVBAR COUNT
    ====================================================== */

    function updateWishlistCount(count) {

        document
            .querySelectorAll(".tob-wishlist-count")
            .forEach(function (element) {

                element.textContent = count;

                element.classList.toggle(
                    "has-items",
                    Number(count) > 0
                );

            });

    }


    /* =====================================================
       UPDATE BUTTON STATE
    ====================================================== */

    function setButtonState(
        button,
        active
    ) {

        button.classList.toggle(
            "active",
            active
        );

        button.setAttribute(
            "aria-pressed",
            active ? "true" : "false"
        );

        button.dataset.wishlistActive =
            active ? "1" : "0";


        const productName =
            button.dataset.productName ||
            "product";


        button.setAttribute(
            "aria-label",
            active
                ? "Remove " +
                    productName +
                    " from wishlist"
                : "Add " +
                    productName +
                    " to wishlist"
        );

    }


    /* =====================================================
       SYNC SAME PRODUCT BUTTONS
    ====================================================== */

    function syncProductButtons(
        slug,
        active
    ) {

        document
            .querySelectorAll(
                '[data-wishlist-slug="' +
                CSS.escape(slug) +
                '"]'
            )
            .forEach(function (button) {

                setButtonState(
                    button,
                    active
                );

            });

    }


    /* =====================================================
       TOGGLE WISHLIST
    ====================================================== */

    async function toggleWishlist(button) {

        if (
            button.dataset.wishlistBusy === "1"
        ) {
            return;
        }


        const loginUrl =
            button.dataset.loginUrl;


        const toggleUrl =
            button.dataset.toggleUrl;


        const slug =
            button.dataset.wishlistSlug;


        if (!toggleUrl) {

            if (loginUrl) {
                window.location.href = loginUrl;
            }

            return;
        }


        button.dataset.wishlistBusy = "1";

        button.disabled = true;


        try {

            const response = await fetch(
                toggleUrl,
                {
                    method: "POST",

                    headers: {
                        "Accept":
                            "application/json",

                        "X-Requested-With":
                            "XMLHttpRequest",

                        "X-CSRF-TOKEN":
                            getCsrfToken()
                    }
                }
            );


            if (
                response.status === 401 ||
                response.status === 419
            ) {

                if (loginUrl) {
                    window.location.href =
                        loginUrl;
                }

                return;
            }


            const data =
                await response.json();


            if (
                !response.ok ||
                !data.success
            ) {

                throw new Error(
                    data.message ||
                    "Unable to update wishlist."
                );

            }


            syncProductButtons(
                slug,
                Boolean(data.added)
            );


            updateWishlistCount(
                data.count || 0
            );


            window.dispatchEvent(
                new CustomEvent(
                    "tob-wishlist-updated",
                    {
                        detail: {
                            slug: slug,
                            added:
                                Boolean(
                                    data.added
                                ),
                            count:
                                Number(
                                    data.count || 0
                                )
                        }
                    }
                )
            );

        } catch (error) {

            console.error(
                "Wishlist error:",
                error
            );

        } finally {

            button.dataset.wishlistBusy = "0";

            button.disabled = false;

        }

    }


    /* =====================================================
       INITIALIZE
    ====================================================== */

    function initialize() {

        document
            .querySelectorAll(
                "[data-wishlist-toggle]"
            )
            .forEach(function (button) {

                const active =
                    button.dataset
                        .wishlistActive === "1";


                setButtonState(
                    button,
                    active
                );


                button.addEventListener(
                    "click",
                    function () {

                        toggleWishlist(
                            button
                        );

                    }
                );

            });

    }


    if (
        document.readyState === "loading"
    ) {

        document.addEventListener(
            "DOMContentLoaded",
            initialize
        );

    } else {

        initialize();

    }

})();