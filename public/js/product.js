document.addEventListener("DOMContentLoaded", function () {

    const productPage =
        document.getElementById("tobProductPage");

    if (!productPage) {
        return;
    }


    /* =====================================================
       PRODUCT DATA
    ====================================================== */

    const product = {
        slug: productPage.dataset.productSlug,
        name: productPage.dataset.productName,
        price: Number(productPage.dataset.productPrice),
        image: productPage.dataset.productImage,
        stock: Math.max(
            0,
            Number(productPage.dataset.productStock) || 0
        )
    };


    const checkoutUrl =
        productPage.dataset.checkoutUrl || "/checkout";


    const productInStock =
        product.stock > 0;


    const maximumQuantity =
        productInStock
            ? Math.min(20, product.stock)
            : 0;



    /* =====================================================
       QUANTITY
    ====================================================== */

    const quantityInput =
        document.getElementById(
            "tobProductQuantity"
        );


    const minusButton =
        document.getElementById(
            "tobProductMinus"
        );


    const plusButton =
        document.getElementById(
            "tobProductPlus"
        );


    function getQuantity() {

        if (!productInStock) {
            return 0;
        }


        if (!quantityInput) {
            return 1;
        }


        let quantity =
            parseInt(
                quantityInput.value,
                10
            );


        if (
            Number.isNaN(quantity) ||
            quantity < 1
        ) {
            quantity = 1;
        }


        return Math.min(
            quantity,
            maximumQuantity
        );
    }


    function updateQuantityButtons(quantity) {

        if (!productInStock) {

            if (minusButton) {
                minusButton.disabled = true;
            }

            if (plusButton) {
                plusButton.disabled = true;
            }

            return;
        }


        if (minusButton) {

            minusButton.disabled =
                quantity <= 1;
        }


        if (plusButton) {

            plusButton.disabled =
                quantity >= maximumQuantity;
        }
    }


    function setQuantity(quantity) {

        if (
            !quantityInput ||
            !productInStock
        ) {
            return;
        }


        quantity =
            Math.max(
                1,
                Math.min(
                    maximumQuantity,
                    Number(quantity) || 1
                )
            );


        quantityInput.value =
            quantity;


        updateQuantityButtons(
            quantity
        );
    }


    /*
    =====================================================
    INITIAL QUANTITY STATE
    =====================================================
    */

    if (!productInStock) {

        if (quantityInput) {
            quantityInput.disabled = true;
        }


        if (minusButton) {
            minusButton.disabled = true;
        }


        if (plusButton) {
            plusButton.disabled = true;
        }

    } else {

        setQuantity(
            getQuantity()
        );
    }


    /*
    =====================================================
    MINUS
    =====================================================
    */

    if (minusButton) {

        minusButton.addEventListener(
            "click",
            function () {

                if (!productInStock) {
                    return;
                }


                setQuantity(
                    getQuantity() - 1
                );
            }
        );
    }


    /*
    =====================================================
    PLUS
    =====================================================
    */

    if (plusButton) {

        plusButton.addEventListener(
            "click",
            function () {

                if (!productInStock) {
                    return;
                }


                setQuantity(
                    getQuantity() + 1
                );
            }
        );
    }



    /* =====================================================
       CART
    ====================================================== */

    function addProductToCart(quantity) {

        /*
        =====================================================
        STOCK PROTECTION
        =====================================================
        */

        if (!productInStock) {

            console.warn(
                product.name +
                " is currently out of stock."
            );

            return false;
        }


        quantity =
            Number(quantity) || 1;


        if (
            quantity < 1 ||
            quantity > maximumQuantity
        ) {

            console.warn(
                "Selected quantity is not available."
            );

            return false;
        }


        /*
        =====================================================
        CART SYSTEM
        =====================================================
        */

        if (!window.TheOnlyBakerCart) {

            console.error(
                "Cart system is not loaded."
            );

            return false;
        }


        const cart =
            window.TheOnlyBakerCart
                .getCart();


        const existing =
            cart.find(
                function (item) {

                    return (
                        item.slug ===
                        product.slug
                    );
                }
            );


        /*
        =====================================================
        EXISTING PRODUCT
        =====================================================
        */

        if (existing) {

            const existingQuantity =
                Number(
                    existing.quantity
                ) || 0;


            const newQuantity =
                existingQuantity +
                quantity;


            /*
            =================================================
            DO NOT EXCEED AVAILABLE STOCK
            =================================================
            */

            if (
                newQuantity >
                product.stock
            ) {

                console.warn(
                    "Only " +
                    product.stock +
                    " unit(s) of " +
                    product.name +
                    " are currently available."
                );

                return false;
            }


            window.TheOnlyBakerCart
                .updateQuantity(
                    product.slug,
                    newQuantity
                );

        } else {

            /*
            =================================================
            NEW PRODUCT
            =================================================
            */

            window.TheOnlyBakerCart
                .addItem({
                    slug: product.slug,
                    name: product.name,
                    price: product.price,
                    image: product.image
                });


            if (quantity > 1) {

                window.TheOnlyBakerCart
                    .updateQuantity(
                        product.slug,
                        quantity
                    );
            }
        }


        window.TheOnlyBakerCart
            .updateCartCount();


        return true;
    }



    /* =====================================================
       ADD TO CART
    ====================================================== */

    const addCartButton =
        document.getElementById(
            "tobProductAddCart"
        );


    /*
    =====================================================
    FORCE BUTTON STATE FROM STOCK
    =====================================================
    */

    if (addCartButton) {

        addCartButton.disabled =
            !productInStock;


        addCartButton.setAttribute(
            "aria-disabled",
            productInStock
                ? "false"
                : "true"
        );
    }


    if (
        addCartButton &&
        productInStock
    ) {

        addCartButton.addEventListener(
            "click",
            function () {

                /*
                =============================================
                SECOND STOCK GUARD
                =============================================
                */

                if (!productInStock) {
                    return;
                }


                const success =
                    addProductToCart(
                        getQuantity()
                    );


                if (!success) {
                    return;
                }


                const text =
                    addCartButton
                        .querySelector("span");


                const originalText =
                    text
                        ? text.textContent
                        : "ADD TO CART";


                addCartButton.classList.add(
                    "added"
                );


                if (text) {

                    text.textContent =
                        "ADDED TO CART";
                }


                window.setTimeout(
                    function () {

                        addCartButton
                            .classList.remove(
                                "added"
                            );


                        if (text) {

                            text.textContent =
                                originalText;
                        }

                    },
                    1400
                );
            }
        );
    }



    /* =====================================================
       BUY NOW
    ====================================================== */

    const buyNowButton =
        document.getElementById(
            "tobProductBuyNow"
        );


    /*
    =====================================================
    FORCE BUTTON STATE FROM STOCK
    =====================================================
    */

    if (buyNowButton) {

        buyNowButton.disabled =
            !productInStock;


        buyNowButton.setAttribute(
            "aria-disabled",
            productInStock
                ? "false"
                : "true"
        );
    }


    if (
        buyNowButton &&
        productInStock
    ) {

        buyNowButton.addEventListener(
            "click",
            function () {

                /*
                =============================================
                SECOND STOCK GUARD
                =============================================
                */

                if (!productInStock) {
                    return;
                }


                const success =
                    addProductToCart(
                        getQuantity()
                    );


                if (!success) {
                    return;
                }


                window.location.href =
                    checkoutUrl;
            }
        );
    }



    /* =====================================================
       PRODUCT GALLERY
    ====================================================== */

    const mainImage =
        document.querySelector(
            "[data-product-main-image]"
        );


    const thumbnails =
        document.querySelectorAll(
            "[data-product-thumbnail]"
        );


    thumbnails.forEach(
        function (thumbnail) {

            thumbnail.addEventListener(
                "click",
                function () {

                    if (!mainImage) {
                        return;
                    }


                    const imageUrl =
                        thumbnail.dataset.image;


                    if (!imageUrl) {
                        return;
                    }


                    mainImage.style.opacity =
                        "0";


                    window.setTimeout(
                        function () {

                            mainImage.src =
                                imageUrl;


                            mainImage.style.opacity =
                                "1";
                        },
                        130
                    );


                    thumbnails.forEach(
                        function (item) {

                            item.classList.remove(
                                "active"
                            );
                        }
                    );


                    thumbnail.classList.add(
                        "active"
                    );
                }
            );
        }
    );



    /* =====================================================
       ZOOM
    ====================================================== */

    const zoomButton =
        document.querySelector(
            ".tob-product-zoom"
        );


    if (
        zoomButton &&
        mainImage
    ) {

        const modal =
            document.createElement(
                "div"
            );


        modal.className =
            "tob-product-zoom-modal";


        modal.innerHTML = `
            <div
                class="tob-product-zoom-backdrop"
            ></div>

            <div
                class="tob-product-zoom-content"
            >
                <button
                    type="button"
                    class="tob-product-zoom-close"
                    aria-label="Close image"
                >
                    ×
                </button>

                <img
                    src=""
                    alt="${product.name}"
                >
            </div>
        `;


        document.body.appendChild(
            modal
        );


        const modalImage =
            modal.querySelector("img");


        const closeButton =
            modal.querySelector(
                ".tob-product-zoom-close"
            );


        const backdrop =
            modal.querySelector(
                ".tob-product-zoom-backdrop"
            );


        function openZoom() {

            if (!modalImage) {
                return;
            }


            modalImage.src =
                mainImage.src;


            modal.classList.add(
                "active"
            );


            document.body.style.overflow =
                "hidden";
        }


        function closeZoom() {

            modal.classList.remove(
                "active"
            );


            document.body.style.overflow =
                "";
        }


        zoomButton.addEventListener(
            "click",
            openZoom
        );


        if (closeButton) {

            closeButton.addEventListener(
                "click",
                closeZoom
            );
        }


        if (backdrop) {

            backdrop.addEventListener(
                "click",
                closeZoom
            );
        }


        document.addEventListener(
            "keydown",
            function (event) {

                if (
                    event.key === "Escape" &&
                    modal.classList.contains(
                        "active"
                    )
                ) {

                    closeZoom();
                }
            }
        );
    }



    /* =====================================================
       ACCORDIONS
    ====================================================== */

    const accordions =
        document.querySelectorAll(
            ".tob-product-accordion"
        );


    accordions.forEach(
        function (accordion) {

            const trigger =
                accordion.querySelector(
                    ".tob-accordion-trigger"
                );


            const icon =
                accordion.querySelector(
                    ".tob-accordion-icon"
                );


            if (!trigger) {
                return;
            }


            trigger.addEventListener(
                "click",
                function () {

                    const wasActive =
                        accordion.classList
                            .contains(
                                "active"
                            );


                    accordions.forEach(
                        function (item) {

                            item.classList.remove(
                                "active"
                            );


                            const itemTrigger =
                                item.querySelector(
                                    ".tob-accordion-trigger"
                                );


                            const itemIcon =
                                item.querySelector(
                                    ".tob-accordion-icon"
                                );


                            if (itemTrigger) {

                                itemTrigger.setAttribute(
                                    "aria-expanded",
                                    "false"
                                );
                            }


                            if (itemIcon) {

                                itemIcon.textContent =
                                    "+";
                            }
                        }
                    );


                    if (!wasActive) {

                        accordion.classList.add(
                            "active"
                        );


                        trigger.setAttribute(
                            "aria-expanded",
                            "true"
                        );


                        if (icon) {

                            icon.textContent =
                                "−";
                        }
                    }
                }
            );
        }
    );



    /* =====================================================
       RELATED PRODUCTS SLIDER
    ====================================================== */

    const relatedTrack =
        document.getElementById(
            "tob-related-track"
        );


    const relatedArrows =
        document.querySelectorAll(
            ".tob-related-arrow"
        );


    if (
        relatedTrack &&
        relatedArrows.length
    ) {

        relatedArrows.forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    function () {

                        const direction =
                            button.dataset.direction;


                        const card =
                            relatedTrack.querySelector(
                                ".tob-related-card"
                            );


                        const amount =
                            card
                                ? card.getBoundingClientRect()
                                    .width + 20
                                : 280;


                        relatedTrack.scrollBy({

                            left:
                                direction === "left"
                                    ? -amount
                                    : amount,

                            behavior:
                                "smooth"
                        });
                    }
                );
            }
        );
    }

});