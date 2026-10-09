document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    const cartSystem = window.TheOnlyBakerCart;

    const cartPage = document.getElementById("tobCartPage");
    const cartItems = document.getElementById("tobCartItems");
    const filledCart = document.getElementById("tobFilledCart");
    const emptyCart = document.getElementById("tobEmptyCart");
    const subtotalElement = document.getElementById("tobCartSubtotal");
    const totalElement = document.getElementById("tobCartTotal");
    const shippingElement = document.getElementById("tobCartShipping");
    const shippingMessage = document.getElementById("tobShippingMessage");
    const headingText = document.getElementById("tobCartHeadingText");
    const clearButton = document.getElementById("tobClearCart");
    const checkoutButton = document.getElementById("tobCheckoutButton");
    const stockAlert = document.getElementById("tobCartStockAlert");

    if (!cartPage || !cartItems || !cartSystem) {
        return;
    }

    const stockUrl = cartPage.dataset.stockUrl;
    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]'
    )?.content;

    let stockData = {};
    let stockChecked = false;
    let checkingStock = false;
    let requestNumber = 0;

    function formatPrice(price) {
        return "₹" + Number(price).toLocaleString("en-IN");
    }

    function escapeHtml(value) {
        const element = document.createElement("div");
        element.textContent = String(value ?? "");
        return element.innerHTML;
    }

    function getCart() {
        return cartSystem.getCart();
    }

    function getStock(slug) {
        const product = stockData[slug];

        if (!product || product.exists === false) {
            return 0;
        }

        return Math.max(0, Number(product.available) || 0);
    }

    function getStockProblem(item) {
        if (!stockChecked) {
            return null;
        }

        const product = stockData[item.slug];

        if (!product || product.exists === false) {
            return "This product is no longer available.";
        }

        const stock = getStock(item.slug);

        if (stock <= 0) {
            return "Out of Stock";
        }

        if (Number(item.quantity) > stock) {
            return "Only " + stock + " available. Please reduce quantity.";
        }

        if (Number(item.quantity) > 50) {
            return "Maximum 50 units allowed per product.";
        }

        return null;
    }

    function setCheckoutEnabled(enabled) {
        if (!checkoutButton) {
            return;
        }

        checkoutButton.classList.toggle(
            "tob-checkout-disabled",
            !enabled
        );

        checkoutButton.setAttribute(
            "aria-disabled",
            enabled ? "false" : "true"
        );

        checkoutButton.tabIndex = enabled ? 0 : -1;
    }

    function updateStockAlert(cart) {
        if (!stockAlert) {
            return;
        }

        let message = "";

        if (checkingStock || !stockChecked) {
            message = "Checking latest product availability...";
        } else {
            const problemCount = cart.filter(
                item => getStockProblem(item) !== null
            ).length;

            if (problemCount > 0) {
                message =
                    problemCount +
                    (problemCount === 1
                        ? " product needs attention. "
                        : " products need attention. ") +
                    "Please adjust the quantities or remove unavailable products before checkout.";
            }
        }

        stockAlert.textContent = message;
        stockAlert.hidden = message === "";
    }

    function renderCart() {
        const cart = getCart();

        if (cart.length === 0) {
            filledCart.hidden = true;
            emptyCart.hidden = false;

            if (headingText) {
                headingText.textContent =
                    "Your cart is waiting for something delicious.";
            }

            if (subtotalElement) subtotalElement.textContent = "₹0";
            if (totalElement) totalElement.textContent = "₹0";

            if (shippingElement) {
                shippingElement.textContent =
                    "Calculated at checkout";
                shippingElement.classList.remove("tob-free-shipping");
            }

            if (shippingMessage) {
                shippingMessage.textContent =
                    "Add ₹499 more for FREE shipping";
                shippingMessage.classList.remove("unlocked");
            }

            if (stockAlert) stockAlert.hidden = true;

            setCheckoutEnabled(false);
            cartSystem.updateCartCount();
            return;
        }

        filledCart.hidden = false;
        emptyCart.hidden = true;

        const totalQuantity = cart.reduce(
            (total, item) => total + Number(item.quantity || 0),
            0
        );

        if (headingText) {
            headingText.textContent =
                totalQuantity +
                (totalQuantity === 1
                    ? " delicious item waiting for you."
                    : " delicious items waiting for you.");
        }

        cartItems.innerHTML = "";

        cart.forEach(function (item) {
            const quantity = Number(item.quantity) || 0;
            const itemTotal = Number(item.price) * quantity;

            const problem = getStockProblem(item);

            const available = getStock(item.slug);

            const plusDisabled =
                !stockChecked ||
                checkingStock ||
                available <= quantity ||
                quantity >= 50;

            const minusDisabled = quantity <= 1;

            const stockNote = problem
                ? `<p class="tob-cart-stock-note" role="alert">${escapeHtml(problem)}</p>`
                : "";

            const row = document.createElement("div");

            row.className = "tob-cart-item";
            row.dataset.slug = item.slug;

            row.innerHTML = `
                <div class="tob-cart-product">
                    <a
                        href="/product/${encodeURIComponent(item.slug)}"
                        class="tob-cart-product-image"
                    >
                        <img
                            src="${escapeHtml(item.image)}"
                            alt="${escapeHtml(item.name)}"
                        >
                    </a>

                    <div class="tob-cart-product-info">
                        <span class="tob-cart-product-label">
                            THE ONLY BAKER
                        </span>

                        <h3>
                            <a href="/product/${encodeURIComponent(item.slug)}">
                                ${escapeHtml(item.name)}
                            </a>
                        </h3>

                        <button
                            type="button"
                            class="tob-cart-remove"
                            data-cart-remove
                        >
                            Remove
                        </button>

                        ${stockNote}
                    </div>
                </div>

                <div class="tob-cart-price">
                    ${formatPrice(item.price)}
                </div>

                <div class="tob-cart-quantity">
                    <button
                        type="button"
                        data-cart-minus
                        aria-label="Decrease quantity"
                        ${minusDisabled ? "disabled" : ""}
                    >
                        −
                    </button>

                    <span>${quantity}</span>

                    <button
                        type="button"
                        data-cart-plus
                        aria-label="Increase quantity"
                        ${plusDisabled ? "disabled" : ""}
                    >
                        +
                    </button>
                </div>

                <div class="tob-cart-item-total">
                    ${formatPrice(itemTotal)}
                </div>
            `;

            cartItems.appendChild(row);
        });

        const subtotal = cartSystem.getSubtotal();
        const qualifiesForFreeShipping = subtotal >= 499;
        const amountForFreeShipping = Math.max(0, 499 - subtotal);

        if (subtotalElement) {
            subtotalElement.textContent = formatPrice(subtotal);
        }

        // Shipping below ₹499 is not assumed.
        // The displayed total remains the known product subtotal.
        if (totalElement) {
            totalElement.textContent = formatPrice(subtotal);
        }

        if (shippingElement) {
            shippingElement.textContent = qualifiesForFreeShipping
                ? "FREE"
                : "Calculated at checkout";

            shippingElement.classList.toggle(
                "tob-free-shipping",
                qualifiesForFreeShipping
            );
        }

        if (shippingMessage) {
            shippingMessage.textContent = qualifiesForFreeShipping
                ? "You unlocked FREE shipping!"
                : "Add " +
                  formatPrice(amountForFreeShipping) +
                  " more for FREE shipping";

            shippingMessage.classList.toggle(
                "unlocked",
                qualifiesForFreeShipping
            );
        }

        const hasProblems = cart.some(
            item => getStockProblem(item) !== null
        );

        setCheckoutEnabled(
            stockChecked &&
            !checkingStock &&
            !hasProblems
        );

        updateStockAlert(cart);
        cartSystem.updateCartCount();
    }

    async function checkStock() {
        const cart = getCart();

        const currentRequest = ++requestNumber;

        if (cart.length === 0) {
            stockData = {};
            stockChecked = true;
            checkingStock = false;
            renderCart();
            return;
        }

        stockChecked = false;
        checkingStock = true;
        renderCart();

        const slugs = [...new Set(cart.map(item => item.slug))];

        try {
            const response = await fetch(stockUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken || ""
                },
                credentials: "same-origin",
                cache: "no-store",
                body: JSON.stringify({ slugs })
            });

            if (!response.ok) {
                throw new Error("Stock check failed.");
            }

            const result = await response.json();

            if (currentRequest !== requestNumber) {
                return;
            }

            stockData = result.products || {};
            stockChecked = true;
        } catch (error) {
            if (currentRequest !== requestNumber) {
                return;
            }

            console.error(error);

            stockData = {};
            stockChecked = false;
        } finally {
            if (currentRequest === requestNumber) {
                checkingStock = false;
                renderCart();

                if (!stockChecked && stockAlert) {
                    stockAlert.textContent =
                        "Unable to check stock right now. Please refresh the page and try again.";
                    stockAlert.hidden = false;
                }
            }
        }
    }

    cartItems.addEventListener("click", function (event) {
        const plus = event.target.closest("[data-cart-plus]");
        const minus = event.target.closest("[data-cart-minus]");
        const remove = event.target.closest("[data-cart-remove]");

        const button = plus || minus || remove;

        if (!button) {
            return;
        }

        const row = button.closest(".tob-cart-item");
        const slug = row?.dataset.slug;

        if (!slug) {
            return;
        }

        if (remove) {
            cartSystem.removeItem(slug);
            checkStock();
            return;
        }

        const product = getCart().find(item => item.slug === slug);

        if (!product) {
            return;
        }

        const quantity = Number(product.quantity) || 0;

        if (plus) {
            if (!stockChecked || checkingStock) {
                return;
            }

            if (
                quantity >= getStock(slug) ||
                quantity >= 50
            ) {
                return;
            }

            cartSystem.updateQuantity(slug, quantity + 1);
            renderCart();
            return;
        }

        if (minus && quantity > 1) {
            cartSystem.updateQuantity(slug, quantity - 1);
            renderCart();
        }
    });

    if (clearButton) {
        clearButton.addEventListener("click", function () {
            cartSystem.clearCart();
            checkStock();
        });
    }

    if (checkoutButton) {
        checkoutButton.addEventListener("click", async function (event) {
            event.preventDefault();

            if (checkingStock) {
                return;
            }

            // Recheck immediately before leaving the cart page.
            await checkStock();

            const cart = getCart();

            const valid =
                cart.length > 0 &&
                stockChecked &&
                cart.every(item => getStockProblem(item) === null);

            if (valid) {
                window.location.href = checkoutButton.href;
            }
        });
    }

    window.addEventListener("tob-cart-updated", function () {
        cartSystem.updateCartCount();
    });

    // Recheck when the customer returns to this browser tab.
    document.addEventListener("visibilitychange", function () {
        if (!document.hidden && getCart().length > 0) {
            checkStock();
        }
    });

    window.addEventListener("pageshow", function (event) {
        if (event.persisted) {
            checkStock();
        }
    });

    checkStock();
});