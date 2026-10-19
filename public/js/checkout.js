
document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const cartSystem = window.TheOnlyBakerCart;

    const checkoutPage = document.querySelector(
        ".tob-checkout-page"
    );

    const checkoutLayout = document.getElementById(
        "tobCheckoutLayout"
    );

    const emptyState = document.getElementById(
        "tobCheckoutEmpty"
    );

    const productsContainer = document.getElementById(
        "tobCheckoutProducts"
    );

    const subtotalElement = document.getElementById(
        "tobCheckoutSubtotal"
    );

    const shippingElement = document.getElementById(
        "tobCheckoutShipping"
    );

    const totalElement = document.getElementById(
        "tobCheckoutTotal"
    );

    const shippingNote = document.getElementById(
        "tobCheckoutShippingNote"
    );

    const itemCount = document.getElementById(
        "tobCheckoutItemCount"
    );

    const checkoutForm = document.getElementById(
        "tobCheckoutForm"
    );

    const stockWarning = document.getElementById(
        "tobCheckoutStockWarning"
    );

    const placeOrderButtons = document.querySelectorAll(
        ".tob-place-order"
    );

    if (
        !cartSystem ||
        !checkoutPage ||
        !checkoutForm
    ) {
        return;
    }

    const stockUrl = checkoutPage.dataset.stockUrl;

    const csrfInput = checkoutForm.querySelector(
        'input[name="_token"]'
    );

    const checkoutTokenInput = checkoutForm.querySelector(
        'input[name="checkout_token"]'
    );

    /*
    |--------------------------------------------------------------------------
    | STATE
    |--------------------------------------------------------------------------
    */

    let stockData = {};
    let stockChecked = false;
    let checkingStock = false;
    let submitting = false;
    let orderSubmitted = false;
    let retryUncertainOrder = false;
    let stockRequestId = 0;
    let lastVerifiedCartSignature = null;

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    function getCart() {
        return cartSystem.getCart();
    }

    function signature(cart) {
        return JSON.stringify(
            cart.map(function (item) {
                return {
                    slug: item.slug,
                    quantity: Number(item.quantity)
                };
            })
        );
    }

    function formatPrice(price) {
        return (
            "₹" +
            Number(price).toLocaleString("en-IN", {
                maximumFractionDigits: 2
            })
        );
    }

    function escapeHtml(value) {
        const element = document.createElement("div");

        element.textContent = String(value ?? "");

        return element.innerHTML;
    }

    
    /*
    |--------------------------------------------------------------------------
    | VERIFIED WHOLESALE PRICING
    |--------------------------------------------------------------------------
    */

    function getWholesaleInfo(item) {
        if (!stockChecked) {
            return null;
        }

        const product = stockData[item.slug];

        
        if (
            !product ||
            product.exists !== true ||
            product.has_wholesale_pricing !== true ||
            product.wholesale_checkout_enabled !== true
        ) {
            return null;
        }


        const price = Number(product.wholesale_price);
        const moq = Number(product.wholesale_moq);

        if (
            product.wholesale_price == null ||
            !Number.isFinite(price) ||
            price <= 0 ||
            !Number.isInteger(moq) ||
            moq < 1 ||
            moq > 50
        ) {
            return null;
        }

        return { price, moq };
    }


    /*
    |--------------------------------------------------------------------------
    | ERROR HANDLING
    |--------------------------------------------------------------------------
    */

    function showError(message) {
        let errorBox = document.getElementById(
            "tobCheckoutError"
        );

        if (!errorBox) {
            errorBox = document.createElement("div");

            errorBox.id = "tobCheckoutError";
            errorBox.className = "tob-checkout-error";

            errorBox.setAttribute(
                "role",
                "alert"
            );

            checkoutForm.prepend(errorBox);
        }

        errorBox.textContent = message;
        errorBox.hidden = false;

        errorBox.scrollIntoView({
            behavior: "smooth",
            block: "center"
        });
    }

    function clearError() {
        const errorBox = document.getElementById(
            "tobCheckoutError"
        );

        if (errorBox) {
            errorBox.remove();
        }
    }

    function setStockWarning(message) {
        if (!stockWarning) {
            return;
        }

        stockWarning.textContent = message || "";
        stockWarning.hidden = !message;
    }

    /*
    |--------------------------------------------------------------------------
    | STOCK VALIDATION
    |--------------------------------------------------------------------------
    */

    function getStockProblem(item) {
        if (!stockChecked) {
            return "Product availability has not been verified.";
        }

        const product = stockData[item.slug];

        if (!product || product.exists !== true) {
            return (
                item.name +
                " is no longer available."
            );
        }

        const latestPrice = Number(product.price);

        if (
            product.price == null ||
            !Number.isFinite(latestPrice) ||
            latestPrice < 0
        ) {
            return (
                "Unable to verify the latest price for " +
                item.name +
                "."
            );
        }

        const quantity = Number(item.quantity);

        if (
            !Number.isInteger(quantity) ||
            quantity < 1 ||
            quantity > 50
        ) {
            return (
                "Invalid quantity for " +
                item.name +
                "."
            );
        }

        const available = Number(product.available);

        if (
            !Number.isInteger(available) ||
            available < 0
        ) {
            return (
                "Unable to verify stock for " +
                item.name +
                "."
            );
        }

        if (available === 0) {
            return (
                item.name +
                " is currently out of stock."
            );
        }

        if (quantity > available) {
            return (
                "Only " +
                available +
                " unit(s) of " +
                item.name +
                " are currently available."
            );
        }

        
        
        const productUsesWholesale =
            product.has_wholesale_pricing === true ||
            product.wholesale_checkout_enabled === true;


        if (productUsesWholesale) {
            const wholesale = getWholesaleInfo(item);

            if (!wholesale) {
                return (
                    "Unable to verify wholesale pricing for " +
                    item.name +
                    ". Please refresh and try again."
                );
            }

            if (quantity < wholesale.moq) {
                return (
                    item.name +
                    " requires a minimum wholesale order of " +
                    wholesale.moq +
                    " units."
                );
            }
        }


        return null;
    }

    function getCartProblem() {
        const cart = getCart();

        if (cart.length === 0) {
            return "Your cart is empty.";
        }

        for (const item of cart) {
            const problem = getStockProblem(item);

            if (problem) {
                return problem;
            }
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | PLACE ORDER BUTTONS
    |--------------------------------------------------------------------------
    */

    function setButtonsDisabled(
        disabled,
        loading = false
    ) {
        placeOrderButtons.forEach(function (button) {
            button.disabled = disabled;

            const text = button.querySelector("span");

            if (!text) {
                return;
            }

            if (!button.dataset.originalText) {
                button.dataset.originalText =
                    text.textContent.trim();
            }

            text.textContent = loading
                ? "Placing Order..."
                : button.dataset.originalText;
        });
    }

    function updateButtons() {
        if (orderSubmitted) {
            setButtonsDisabled(true, true);
            return;
        }

        const disabled =
            submitting ||
            checkingStock ||
            (
                !retryUncertainOrder &&
                (
                    !stockChecked ||
                    getCart().length === 0 ||
                    Boolean(getCartProblem())
                )
            );

        setButtonsDisabled(
            disabled,
            submitting
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RENDER CHECKOUT
    |--------------------------------------------------------------------------
    */

    function renderCheckout() {
        const cart = getCart();

        if (cart.length === 0) {
            if (checkoutLayout) {
                checkoutLayout.hidden = true;
            }

            if (emptyState) {
                emptyState.hidden = false;
            }

            setStockWarning("");
            updateButtons();

            return;
        }

        if (checkoutLayout) {
            checkoutLayout.hidden = false;
        }

        if (emptyState) {
            emptyState.hidden = true;
        }

        if (!productsContainer) {
            return;
        }

        productsContainer.innerHTML = "";

        let totalQuantity = 0;
        let subtotal = 0;

        cart.forEach(function (item) {
            const verified = stockChecked
                ? stockData[item.slug]
                : null;

            const verifiedPrice = Number(
                verified?.price
            );

            
            const retailPrice = (
                verified &&
                verified.exists === true &&
                verified.price != null &&
                Number.isFinite(verifiedPrice) &&
                verifiedPrice >= 0
            )
                ? verifiedPrice
                : Number(item.price) || 0;

            const wholesale = getWholesaleInfo(item);

            const price = wholesale
                ? wholesale.price
                : retailPrice;


            const quantity =
                Number(item.quantity) || 0;

            const name =
                verified?.name || item.name;

            const image =
                verified?.image || item.image || "";

            totalQuantity += quantity;
            subtotal += price * quantity;

            const product = document.createElement("div");

            product.className =
                "tob-checkout-product";

            product.innerHTML = `
                <div class="tob-checkout-product-image">
                    <img
                        src="${escapeHtml(image)}"
                        alt="${escapeHtml(name)}"
                    >

                    <span>
                        ${quantity}
                    </span>
                </div>

                <div class="tob-checkout-product-info">
                    <h3>
                        ${escapeHtml(name)}
                    </h3>

                    <small>
                        Qty: ${quantity}
                    </small>


                    
                    ${
                        wholesale
                            ? `
                                <small style="
                                    display: block;
                                    margin-top: 5px;
                                    color: #237747;
                                    font-weight: 600;
                                ">
                                    Wholesale: ${formatPrice(wholesale.price)} / unit
                                    · MOQ ${wholesale.moq}
                                </small>
                            `
                            : ""
                    }


                </div>

                <strong>
                    ${formatPrice(price * quantity)}
                </strong>
            `;

            productsContainer.appendChild(product);
        });

        if (itemCount) {
            itemCount.textContent =
                totalQuantity +
                (
                    totalQuantity === 1
                        ? " item"
                        : " items"
                );
        }

        /*
        |--------------------------------------------------------------------------
        | SHIPPING
        |--------------------------------------------------------------------------
        */

        const freeShipping = subtotal >= 499;

        const remaining = Math.max(
            0,
            499 - subtotal
        );

        if (subtotalElement) {
            subtotalElement.textContent =
                formatPrice(subtotal);
        }

        if (shippingElement) {
            shippingElement.textContent = freeShipping
                ? "FREE"
                : "Calculated at checkout";

            shippingElement.classList.toggle(
                "free",
                freeShipping
            );
        }

        // Shipping below Rs 499 is not configured
        // in the backend yet.
        if (totalElement) {
            totalElement.textContent =
                formatPrice(subtotal);
        }

        if (shippingNote) {
            if (freeShipping) {
                shippingNote.textContent =
                    "You unlocked FREE shipping!";

                shippingNote.classList.add(
                    "unlocked"
                );
            } else {
                shippingNote.textContent =
                    "Add " +
                    formatPrice(remaining) +
                    " more for FREE shipping. " +
                    "Shipping charges, if applicable, " +
                    "are calculated separately.";

                shippingNote.classList.remove(
                    "unlocked"
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | WARNINGS
        |--------------------------------------------------------------------------
        */

        if (checkingStock) {
            setStockWarning(
                "Checking latest product availability..."
            );

        } else if (!stockChecked) {
            setStockWarning(
                retryUncertainOrder
                    ? "Your previous order request may have been received. You can retry safely."
                    : "Unable to verify product availability. Please try again."
            );

        } else {
            setStockWarning(
                getCartProblem()
            );
        }

        updateButtons();
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFY LIVE STOCK
    |--------------------------------------------------------------------------
    */

    async function verifyStock() {
        const cart = getCart();

        const startingSignature = signature(cart);

        const currentRequestId = ++stockRequestId;

        lastVerifiedCartSignature = null;

        if (
            cart.length === 0 ||
            !stockUrl ||
            !csrfInput
        ) {
            stockData = {};
            stockChecked = false;
            checkingStock = false;

            renderCheckout();

            return false;
        }

        checkingStock = true;
        stockChecked = false;

        renderCheckout();

        try {
            const slugs = [
                ...new Set(
                    cart.map(function (item) {
                        return item.slug;
                    })
                )
            ];

            const response = await fetch(stockUrl, {
                method: "POST",
                credentials: "same-origin",
                cache: "no-store",

                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfInput.value
                },

                body: JSON.stringify({
                    slugs: slugs
                })
            });

            if (!response.ok) {
                throw new Error(
                    "Stock verification failed."
                );
            }

            const data = await response.json();

            if (currentRequestId !== stockRequestId) {
                return false;
            }

            if (
                !data ||
                !data.products ||
                typeof data.products !== "object" ||
                Array.isArray(data.products)
            ) {
                throw new Error(
                    "Invalid stock verification response."
                );
            }

            /*
            |--------------------------------------------------------------------------
            | REJECT STALE CART
            |--------------------------------------------------------------------------
            */

            if (
                signature(getCart()) !==
                startingSignature
            ) {
                stockChecked = false;
                return false;
            }

            stockData = data.products;
            stockChecked = true;

            if (getCartProblem()) {
                return false;
            }

            lastVerifiedCartSignature =
                startingSignature;

            /*
            |--------------------------------------------------------------------------
            | SYNCHRONIZE LOCAL CART PRICES
            |--------------------------------------------------------------------------
            */

            if (
                !submitting &&
                typeof cartSystem.syncProducts === "function"
            ) {
                cartSystem.syncProducts(stockData);
            }

            return true;

        } catch (error) {
            if (currentRequestId === stockRequestId) {
                stockData = {};
                stockChecked = false;
                lastVerifiedCartSignature = null;
            }

            console.error(
                "Checkout stock verification:",
                error
            );

            return false;

        } finally {
            if (currentRequestId === stockRequestId) {
                checkingStock = false;
                renderCheckout();
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SERVER ERROR MESSAGE
    |--------------------------------------------------------------------------
    */

    function extractErrorMessage(data) {
        if (data && data.errors) {
            const first = Object.values(
                data.errors
            )[0];

            if (Array.isArray(first)) {
                return (
                    first[0] ||
                    "Unable to place order."
                );
            }

            if (first) {
                return String(first);
            }
        }

        return (
            data?.message ||
            "Unable to place order."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PLACE ORDER
    |--------------------------------------------------------------------------
    */

    checkoutForm.addEventListener(
        "submit",
        async function (event) {
            event.preventDefault();

            if (submitting || orderSubmitted) {
                return;
            }

            clearError();

            if (!checkoutForm.checkValidity()) {
                checkoutForm.reportValidity();
                return;
            }

            if (getCart().length === 0) {
                renderCheckout();
                return;
            }

            if (!csrfInput) {
                showError(
                    "Security token missing. Please refresh the page."
                );
                return;
            }

            if (!checkoutTokenInput?.value) {
                showError(
                    "Checkout token missing. Please refresh the checkout page."
                );
                return;
            }

            submitting = true;
            updateButtons();

            try {
                /*
                |--------------------------------------------------------------------------
                | VERIFY STOCK BEFORE ORDER
                |--------------------------------------------------------------------------
                */

                if (!retryUncertainOrder) {
                    const valid = await verifyStock();

                    if (!valid) {
                        throw new Error(
                            getCartProblem() ||
                            "Unable to verify stock. Please try again."
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | PREPARE CART
                |--------------------------------------------------------------------------
                */

                const cart = getCart().map(
                    function (item) {
                        return {
                            slug: item.slug,
                            quantity: Number(
                                item.quantity
                            )
                        };
                    }
                );

                const invalidCart =
                    cart.length === 0 ||
                    cart.length > 50 ||
                    cart.some(function (item) {
                        return (
                            typeof item.slug !== "string" ||
                            !item.slug.trim() ||
                            !Number.isInteger(item.quantity) ||
                            item.quantity < 1 ||
                            item.quantity > 50
                        );
                    });

                if (invalidCart) {
                    throw new Error(
                        "Your cart contains invalid items. Please review your cart."
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | PREPARE PAYLOAD
                |--------------------------------------------------------------------------
                */

                const formData = new FormData(
                    checkoutForm
                );

                const payload = {
                    checkout_token:
                        checkoutTokenInput.value,

                    name: formData.get("name"),
                    email: formData.get("email"),
                    phone: formData.get("phone"),
                    address: formData.get("address"),
                    landmark: formData.get("landmark"),
                    city: formData.get("city"),
                    state: formData.get("state"),
                    pincode: formData.get("pincode"),
                    save_address: formData.get("save_address") === "1",
address_label: formData.get("address_label") || "Home",
saved_address_id: formData.get("saved_address_choice") === "new"
    ? null
    : (formData.get("saved_address_choice") || null),

                    payment_method:
                        formData.get("payment_method"),

                    cart: cart
                };

                /*
                |--------------------------------------------------------------------------
                | SUBMIT ORDER
                |--------------------------------------------------------------------------
                */

                // Once sent, a network failure may mean
                // the order was successfully created.
                retryUncertainOrder = true;

                const response = await fetch(
                    checkoutForm.action,
                    {
                        method: "POST",
                        credentials: "same-origin",

                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN":
                                csrfInput.value
                        },

                        body: JSON.stringify(payload)
                    }
                );

                let data;

                try {
                    data = await response.json();

                } catch (error) {
                    throw new Error(
                        "Invalid server response. Please check your order status before trying again."
                    );
                }

                if (!response.ok) {
                    // 4xx means a definite client error.
                    // A 5xx may happen after order creation.
                    if (
                        response.status >= 400 &&
                        response.status < 500
                    ) {
                        retryUncertainOrder = false;
                    }

                    throw new Error(
                        extractErrorMessage(data)
                    );
                }

                if (
                    !data.success ||
                    !data.redirect
                ) {
                    throw new Error(
                        "Unable to confirm your order. Please retry using the same checkout page."
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | SUCCESS
                |--------------------------------------------------------------------------
                */

                retryUncertainOrder = false;
                orderSubmitted = true;

                window.location.href =
                    data.redirect;

            } catch (error) {
                showError(
                    error.message ||
                    "Something went wrong. Please try again."
                );

                if (!retryUncertainOrder) {
                    stockChecked = false;
                }

            } finally {
                submitting = false;
                renderCheckout();
            }
        }
    );

    /*
    |--------------------------------------------------------------------------
    | CART UPDATED
    |--------------------------------------------------------------------------
    */

    window.addEventListener(
        "tob-cart-updated",
        function () {
            if (
                submitting ||
                orderSubmitted
            ) {
                return;
            }

            const currentSignature = signature(
                getCart()
            );

            if (
                stockChecked &&
                currentSignature ===
                lastVerifiedCartSignature
            ) {
                renderCheckout();
                return;
            }

            stockChecked = false;

            verifyStock();
        }
    );

    /*
    |--------------------------------------------------------------------------
    | PAGE VISIBILITY
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        "visibilitychange",
        function () {
            if (
                !document.hidden &&
                !submitting &&
                !orderSubmitted &&
                !retryUncertainOrder &&
                getCart().length > 0
            ) {
                verifyStock();
            }
        }
    );

    window.addEventListener(
        "pageshow",
        function (event) {
            if (
                event.persisted &&
                !orderSubmitted &&
                !retryUncertainOrder
            ) {
                verifyStock();
            }
        }
    );

    /*
    |--------------------------------------------------------------------------
    | INITIALIZE CHECKOUT
    |--------------------------------------------------------------------------
    */

    renderCheckout();
    verifyStock();
});
