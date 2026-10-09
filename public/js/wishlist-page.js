/* =========================================================
   THE ONLY BAKER
   WISHLIST PAGE
========================================================= */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        "use strict";


        const grid =
            document.getElementById(
                "tobWishlistGrid"
            );


        const emptyState =
            document.getElementById(
                "tobWishlistEmpty"
            );


        const csrfMeta =
            document.querySelector(
                'meta[name="csrf-token"]'
            );


        const csrfToken =
            csrfMeta
                ? csrfMeta.content
                : "";


        /* =====================================================
           UPDATE NAVBAR COUNT
        ====================================================== */

        function updateCount(
            count
        ) {

            document
                .querySelectorAll(
                    ".tob-wishlist-count"
                )
                .forEach(
                    function (element) {

                        element.textContent =
                            count;


                        element.classList.toggle(
                            "has-items",
                            Number(count) > 0
                        );

                    }
                );

        }


        /* =====================================================
           CHECK EMPTY
        ====================================================== */

        function updateEmptyState() {

            const remaining =
                document.querySelectorAll(
                    "[data-wishlist-card]"
                ).length;


            if (
                remaining === 0 &&
                emptyState
            ) {

                emptyState.hidden = false;

            }


            if (
                remaining === 0 &&
                grid
            ) {

                grid.hidden = true;

            }

        }


        /* =====================================================
           REMOVE
        ====================================================== */

        document
            .querySelectorAll(
                "[data-remove-wishlist]"
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        "click",
                        async function () {

                            if (
                                button.disabled
                            ) {
                                return;
                            }


                            const url =
                                button.dataset
                                    .removeUrl;


                            const card =
                                button.closest(
                                    "[data-wishlist-card]"
                                );


                            if (
                                !url ||
                                !card
                            ) {
                                return;
                            }


                            button.disabled =
                                true;


                            try {

                                const response =
                                    await fetch(
                                        url,
                                        {
                                            method:
                                                "DELETE",

                                            headers:
                                            {
                                                "Accept":
                                                    "application/json",

                                                "X-Requested-With":
                                                    "XMLHttpRequest",

                                                "X-CSRF-TOKEN":
                                                    csrfToken
                                            }
                                        }
                                    );


                                const data =
                                    await response
                                        .json();


                                if (
                                    !response.ok ||
                                    !data.success
                                ) {

                                    throw new Error(
                                        data.message ||
                                        "Unable to remove product."
                                    );

                                }


                                card.remove();


                                updateCount(
                                    data.count ||
                                    0
                                );


                                updateEmptyState();

                            } catch (
                                error
                            ) {

                                console.error(
                                    "Wishlist remove error:",
                                    error
                                );


                                button.disabled =
                                    false;

                            }

                        }
                    );

                }
            );


        /* =====================================================
           ADD TO CART
        ====================================================== */

        document
            .querySelectorAll(
                "[data-wishlist-add-cart]"
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        "click",
                        function () {

                            const card =
                                button.closest(
                                    "[data-wishlist-card]"
                                );


                            if (!card) {
                                return;
                            }


                            if (
                                !window
                                    .TheOnlyBakerCart
                            ) {

                                console.error(
                                    "Cart system is not loaded."
                                );

                                return;
                            }


                            const product = {

                                slug:
                                    card.dataset
                                        .slug,

                                name:
                                    card.dataset
                                        .name,

                                price:
                                    Number(
                                        card.dataset
                                            .price
                                    ),

                                image:
                                    card.dataset
                                        .image
                            };


                            window
                                .TheOnlyBakerCart
                                .addItem(
                                    product
                                );


                            const text =
                                button.querySelector(
                                    "span"
                                );


                            if (!text) {
                                return;
                            }


                            const oldText =
                                text.textContent;


                            button.classList.add(
                                "added"
                            );


                            text.textContent =
                                "Added to Cart";


                            setTimeout(
                                function () {

                                    button.classList
                                        .remove(
                                            "added"
                                        );


                                    text.textContent =
                                        oldText;

                                },
                                1400
                            );

                        }
                    );

                }
            );


        updateEmptyState();

    }
);