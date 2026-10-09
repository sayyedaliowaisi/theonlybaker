
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

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

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

    function getCart() {
        return cartSystem.getCart();
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
            errorBox.setAttribute("role", "alert");

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

        if (!product || !product.exists) {
            return (
                item.name +
                " is no longer available."
            );
        }

        const available = Math.max(
            0,
            Number(product.available) || 0
        );

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

    function setButtonsDisabled(disabled, loading = false) {
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
            const verifiedProduct = stockChecked
                ? stockData[item.slug]
                : null;

            const verifiedPrice = Number(
                verifiedProduct?.price
            );

            const price = (
                verifiedProduct &&
                verifiedProduct.exists &&
                verifiedProduct.price !== null &&
                Number.isFinite(verifiedPrice)
            )
                ? verifiedPrice
                : Number(item.price) || 0;

            const quantity = Number(item.quantity) || 0;

            totalQuantity += quantity;
            subtotal += price * quantity;

            const productName =
                verifiedProduct?.name || item.name;

            const productImage = item.image;

            const product = document.createElement("div");

            product.className = "tob-checkout-product";

            product.innerHTML = `
                <div class="tob-checkout-product-image">
                    <img
                        src="${escapeHtml(productImage)}"
                        alt="${escapeHtml(productName)}"
                    >

                    <span>
                        ${quantity}
                    </span>
                </div>

                <div class="tob-checkout-product-info">
                    <h3>
                        ${escapeHtml(productName)}
                    </h3>

                    <small>
                        Qty: ${quantity}
                    </small>
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

        // Shipping charges below ₹499 are not configured.
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
                    "Shipping charges, if applicable, are calculated separately.";

                shippingNote.classList.remove(
                    "unlocked"
                );
            }
        }

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
            setStockWarning(getCartProblem());
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

        const currentRequestId = ++stockRequestId;

        if (cart.length === 0) {
            stockData = {};
            stockChecked = false;
            checkingStock = false;

            renderCheckout();
            return false;
        }

        if (!stockUrl || !csrfInput) {
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

            stockData = data.products || {};
            stockChecked = true;

            const currentSlugs = [
                ...new Set(
                    getCart().map(function (item) {
                        return item.slug;
                    })
                )
            ];

            const sameProducts =
                currentSlugs.length === slugs.length &&
                currentSlugs.every(function (slug) {
                    return slugs.includes(slug);
                });

            if (!sameProducts) {
                stockChecked = false;
                return false;
            }

            return getCartProblem() === null;

        } catch (error) {
            if (currentRequestId === stockRequestId) {
                stockData = {};
                stockChecked = false;
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
            const firstError = Object.values(
                data.errors
            )[0];

            if (Array.isArray(firstError)) {
                return (
                    firstError[0] ||
                    "Unable to place order."
                );
            }

            if (firstError) {
                return String(firstError);
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

            if (
                !checkoutTokenInput ||
                !checkoutTokenInput.value
            ) {
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
                    const validStock = await verifyStock();

                    if (!validStock) {
                        throw new Error(
                            getCartProblem() ||
                            "Unable to verify stock. Please try again."
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | PREPARE PAYLOAD
                |--------------------------------------------------------------------------
                */

                const cart = getCart();
                const formData = new FormData(checkoutForm);

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

                    payment_method:
                        formData.get("payment_method"),

                    cart: cart.map(function (item) {
                        return {
                            slug: item.slug,
                            quantity: Number(
                                item.quantity
                            )
                        };
                    })
                };

                /*
                |--------------------------------------------------------------------------
                | SUBMIT ORDER
                |--------------------------------------------------------------------------
                */

                // From here, network failure may leave order status unknown.
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
                    // A clear server error allows fresh validation on retry.
                    retryUncertainOrder = false;

                    throw new Error(
                        extractErrorMessage(data)
                    );
                }

                if (!data.success || !data.redirect) {
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

                // Existing success page clears the cart.
                window.location.href = data.redirect;

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
            if (!submitting && !orderSubmitted) {
                stockChecked = false;
                verifyStock();
            }
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
