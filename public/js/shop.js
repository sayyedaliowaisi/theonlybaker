/* =========================================================
   THE ONLY BAKER
   SHOP PAGE
========================================================= */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        "use strict";


        /* =====================================================
           ELEMENTS
        ====================================================== */

        const productGrid =
            document.getElementById(
                "tobProductsGrid"
            );


        const productCards =
            Array.from(
                document.querySelectorAll(
                    "[data-product-card]"
                )
            );


        const categoryButtons =
            document.querySelectorAll(
                ".tob-category-filter"
            );


        const priceFilters =
            document.querySelectorAll(
                "[data-price-filter]"
            );


        const dietaryFilters =
            document.querySelectorAll(
                "[data-dietary-filter]"
            );


        const sortSelect =
            document.getElementById(
                "tobProductSort"
            );


        const productCount =
            document.getElementById(
                "tobProductCount"
            );


        const noProducts =
            document.getElementById(
                "tobNoProducts"
            );


        const addCartButtons =
            document.querySelectorAll(
                "[data-add-cart]"
            );


        const sidebarToggles =
            document.querySelectorAll(
                "[data-sidebar-toggle]"
            );


        if (!productGrid) {
            return;
        }


        /* =====================================================
           CURRENT FILTER STATE
        ====================================================== */

        let activeCategory = "all";


        /* =====================================================
           CATEGORY FILTER
        ====================================================== */

        categoryButtons.forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    function () {

                        activeCategory =
                            button.dataset.category ||
                            "all";


                        categoryButtons.forEach(
                            function (item) {

                                item.classList.remove(
                                    "active"
                                );

                            }
                        );


                        button.classList.add(
                            "active"
                        );


                        applyFilters();
                    }
                );
            }
        );


        /* =====================================================
           PRICE FILTER
        ====================================================== */

        priceFilters.forEach(
            function (checkbox) {

                checkbox.addEventListener(
                    "change",
                    applyFilters
                );

            }
        );


        /* =====================================================
           DIETARY FILTER
        ====================================================== */

        dietaryFilters.forEach(
            function (checkbox) {

                checkbox.addEventListener(
                    "change",
                    applyFilters
                );

            }
        );


        /* =====================================================
           CHECK PRICE
        ====================================================== */

        function matchesPrice(
            productPrice
        ) {

            const selectedPrices =
                Array.from(
                    priceFilters
                )
                    .filter(
                        function (checkbox) {

                            return checkbox.checked;

                        }
                    )
                    .map(
                        function (checkbox) {

                            return checkbox.value;

                        }
                    );


            if (
                selectedPrices.length === 0
            ) {
                return true;
            }


            return selectedPrices.some(
                function (range) {

                    switch (range) {

                        case "under-200":

                            return (
                                productPrice < 200
                            );


                        case "200-300":

                            return (
                                productPrice >= 200 &&
                                productPrice <= 300
                            );


                        case "300-400":

                            return (
                                productPrice > 300 &&
                                productPrice <= 400
                            );


                        case "above-400":

                            return (
                                productPrice > 400
                            );


                        default:

                            return true;
                    }
                }
            );
        }


        /* =====================================================
           CHECK DIETARY
        ====================================================== */

        function matchesDietary() {

            const selectedDietary =
                Array.from(
                    dietaryFilters
                )
                    .filter(
                        function (checkbox) {

                            return checkbox.checked;

                        }
                    )
                    .map(
                        function (checkbox) {

                            return checkbox.value;

                        }
                    );


            if (
                selectedDietary.length === 0
            ) {
                return true;
            }


            const productDietary = [
                "eggless",
                "no-preservatives"
            ];


            return selectedDietary.every(
                function (dietary) {

                    return productDietary.includes(
                        dietary
                    );

                }
            );
        }


        /* =====================================================
           APPLY FILTERS
        ====================================================== */

        function applyFilters() {

            let visibleProducts = 0;


            productCards.forEach(
                function (card) {

                    const category =
                        card.dataset.category;


                    const price =
                        Number(
                            card.dataset.price
                        );


                    const categoryMatch =
                        activeCategory === "all" ||
                        category === activeCategory;


                    const priceMatch =
                        matchesPrice(
                            price
                        );


                    const dietaryMatch =
                        matchesDietary();


                    const shouldShow =
                        categoryMatch &&
                        priceMatch &&
                        dietaryMatch;


                    card.classList.toggle(
                        "tob-product-hidden",
                        !shouldShow
                    );


                    if (shouldShow) {
                        visibleProducts++;
                    }

                }
            );


            updateProductCount(
                visibleProducts
            );


            if (noProducts) {

                noProducts.hidden =
                    visibleProducts !== 0;

            }
        }


        /* =====================================================
           PRODUCT COUNT
        ====================================================== */

        function updateProductCount(
            count
        ) {

            if (!productCount) {
                return;
            }


            if (
                activeCategory === "all" &&
                count === 16
            ) {

                productCount.textContent =
                    "16 Delicious Varieties";

                return;
            }


            productCount.textContent =
                count +
                (
                    count === 1
                        ? " Delicious Variety"
                        : " Delicious Varieties"
                );
        }


        /* =====================================================
           SORT
        ====================================================== */

        if (sortSelect) {

            sortSelect.addEventListener(
                "change",
                function () {

                    sortProducts(
                        sortSelect.value
                    );

                }
            );
        }


        function sortProducts(
            sortType
        ) {

            const sortedProducts = [
                ...productCards
            ];


            switch (sortType) {

                case "price-low":

                    sortedProducts.sort(
                        function (a, b) {

                            return (
                                Number(
                                    a.dataset.price
                                ) -
                                Number(
                                    b.dataset.price
                                )
                            );

                        }
                    );

                    break;


                case "price-high":

                    sortedProducts.sort(
                        function (a, b) {

                            return (
                                Number(
                                    b.dataset.price
                                ) -
                                Number(
                                    a.dataset.price
                                )
                            );

                        }
                    );

                    break;


                case "name":

                    sortedProducts.sort(
                        function (a, b) {

                            return a.dataset.name
                                .localeCompare(
                                    b.dataset.name
                                );

                        }
                    );

                    break;


                case "featured":
                default:

                    sortedProducts.sort(
                        function (a, b) {

                            return (
                                Number(
                                    a.dataset.number
                                ) -
                                Number(
                                    b.dataset.number
                                )
                            );

                        }
                    );

                    break;
            }


            sortedProducts.forEach(
                function (card) {

                    productGrid.appendChild(
                        card
                    );

                }
            );
        }


        /* =====================================================
           STOCK HELPERS
        ====================================================== */

        function getCardStock(
            card
        ) {

            const stock =
                Number(
                    card.dataset.stock
                );


            if (
                Number.isNaN(stock) ||
                stock < 0
            ) {
                return 0;
            }


            return stock;
        }


        function getCartQuantity(
            slug
        ) {

            if (
                !window.TheOnlyBakerCart
            ) {
                return 0;
            }


            const cart =
                window.TheOnlyBakerCart
                    .getCart();


            const existing =
                cart.find(
                    function (item) {

                        return (
                            item.slug === slug
                        );

                    }
                );


            if (!existing) {
                return 0;
            }


            return Math.max(
                0,
                Number(
                    existing.quantity
                ) || 0
            );
        }


        /* =====================================================
           INITIAL STOCK BUTTON STATE
        ====================================================== */

        function initializeStockButtons() {

            addCartButtons.forEach(
                function (button) {

                    const card =
                        button.closest(
                            "[data-product-card]"
                        );


                    if (!card) {
                        return;
                    }


                    const stock =
                        getCardStock(
                            card
                        );


                    if (stock <= 0) {

                        button.disabled = true;

                        button.setAttribute(
                            "aria-disabled",
                            "true"
                        );

                        const text =
                            button.querySelector(
                                "span"
                            );


                        if (text) {

                            text.textContent =
                                "Out of Stock";

                        }

                    } else {

                        button.disabled = false;

                        button.setAttribute(
                            "aria-disabled",
                            "false"
                        );
                    }

                }
            );
        }


        /* =====================================================
           BUTTON MESSAGE
        ====================================================== */

        function showButtonMessage(
            button,
            message,
            className,
            duration
        ) {

            const text =
                button.querySelector(
                    "span"
                );


            if (!text) {
                return;
            }


            const originalText =
                text.textContent;


            if (className) {

                button.classList.add(
                    className
                );

            }


            text.textContent =
                message;


            window.setTimeout(
                function () {

                    if (className) {

                        button.classList.remove(
                            className
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | If product became disabled, keep Out of Stock text.
                    |--------------------------------------------------------------------------
                    */

                    if (button.disabled) {

                        text.textContent =
                            "Out of Stock";

                        return;
                    }


                    text.textContent =
                        originalText;

                },
                duration || 1400
            );
        }


        /* =====================================================
           ADD TO CART
        ====================================================== */

        addCartButtons.forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    function () {

                        const card =
                            button.closest(
                                "[data-product-card]"
                            );


                        if (!card) {
                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | INVENTORY
                        |--------------------------------------------------------------------------
                        */

                        const stock =
                            getCardStock(
                                card
                            );


                        if (
                            stock <= 0 ||
                            button.disabled
                        ) {

                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | CART SYSTEM
                        |--------------------------------------------------------------------------
                        */

                        if (
                            !window.TheOnlyBakerCart
                        ) {

                            console.error(
                                "Cart system is not loaded."
                            );

                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | PRODUCT
                        |--------------------------------------------------------------------------
                        */

                        const product = {

                            slug:
                                card.dataset.slug,

                            name:
                                card.dataset
                                    .productName,

                            price:
                                Number(
                                    card.dataset.price
                                ),

                            image:
                                card.dataset.image

                        };


                        if (
                            !product.slug
                        ) {
                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | EXISTING CART QUANTITY
                        |--------------------------------------------------------------------------
                        */

                        const existingQuantity =
                            getCartQuantity(
                                product.slug
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | STOCK LIMIT
                        |--------------------------------------------------------------------------
                        */

                        if (
                            existingQuantity >=
                            stock
                        ) {

                            showButtonMessage(
                                button,
                                "Stock Limit Reached",
                                "stock-limit",
                                1600
                            );

                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | ADD / UPDATE CART
                        |--------------------------------------------------------------------------
                        */

                        if (
                            existingQuantity > 0
                        ) {

                            window.TheOnlyBakerCart
                                .updateQuantity(
                                    product.slug,
                                    existingQuantity + 1
                                );

                        } else {

                            window.TheOnlyBakerCart
                                .addItem(
                                    product
                                );
                        }


                        window.TheOnlyBakerCart
                            .updateCartCount();


                        /*
                        |--------------------------------------------------------------------------
                        | SUCCESS FEEDBACK
                        |--------------------------------------------------------------------------
                        */

                        showButtonMessage(
                            button,
                            "Added to Cart",
                            "added",
                            1400
                        );

                    }
                );

            }
        );


        /* =====================================================
           SIDEBAR ACCORDION
        ====================================================== */

        sidebarToggles.forEach(
            function (toggle) {

                toggle.addEventListener(
                    "click",
                    function () {

                        const box =
                            toggle.closest(
                                ".tob-shop-sidebar-box"
                            );


                        if (!box) {
                            return;
                        }


                        box.classList.toggle(
                            "collapsed"
                        );

                    }
                );

            }
        );


        /* =====================================================
           URL CATEGORY
        ====================================================== */

        function handleUrlCategory() {

            const params =
                new URLSearchParams(
                    window.location.search
                );


            const categorySlug =
                params.get(
                    "category"
                );


            if (!categorySlug) {
                return;
            }


            const categoryGroups = {

                "cherry-fruit":
                    "dry-fruit",

                "badam-pista":
                    "dry-fruit",

                "american-chocolate-buddy":
                    "chocolate",

                "gem-badam-pista":
                    "dry-fruit",

                "badam":
                    "dry-fruit",

                "kaju-cashew":
                    "dry-fruit",

                "pista":
                    "dry-fruit",

                "coconut":
                    "classic",

                "ajwain-long":
                    "classic",

                "jeera":
                    "classic",

                "kaju-jeera":
                    "dry-fruit",

                "ajwain":
                    "classic",

                "giri-coconut":
                    "classic",

                "kala-jeera":
                    "classic",

                "ajwain-short":
                    "classic",

                "choco-chips":
                    "chocolate"

            };


            const group =
                categoryGroups[
                    categorySlug
                ];


            if (!group) {
                return;
            }


            const targetButton =
                document.querySelector(
                    '.tob-category-filter[data-category="' +
                    group +
                    '"]'
                );


            if (!targetButton) {
                return;
            }


            activeCategory =
                group;


            categoryButtons.forEach(
                function (button) {

                    button.classList.remove(
                        "active"
                    );

                }
            );


            targetButton.classList.add(
                "active"
            );


            applyFilters();
        }


        /* =====================================================
           CART UPDATE LISTENER
        ====================================================== */

        window.addEventListener(
            "tob-cart-updated",
            function () {

                /*
                 * Cart update ke baad stock limit
                 * next click par latest cart quantity
                 * ke against check hogi.
                 */

                initializeStockButtons();

            }
        );


        /* =====================================================
           INITIALIZE
        ====================================================== */

        initializeStockButtons();

        handleUrlCategory();

        sortProducts(
            "featured"
        );

    }
);