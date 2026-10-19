
/* THE ONLY BAKER — CART PAGE */
/* B2C + APPROVED RETAILER WHOLESALE SUPPORT */

document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    const system = window.TheOnlyBakerCart;

    const page = document.getElementById("tobCartPage");
    const items = document.getElementById("tobCartItems");
    const filled = document.getElementById("tobFilledCart");
    const empty = document.getElementById("tobEmptyCart");

    const subtotalEl = document.getElementById(
        "tobCartSubtotal"
    );

    const totalEl = document.getElementById(
        "tobCartTotal"
    );

    const shippingEl = document.getElementById(
        "tobCartShipping"
    );

    const shippingMessage = document.getElementById(
        "tobShippingMessage"
    );

    const heading = document.getElementById(
        "tobCartHeadingText"
    );

    const clear = document.getElementById(
        "tobClearCart"
    );

    const checkout = document.getElementById(
        "tobCheckoutButton"
    );

    const alertBox = document.getElementById(
        "tobCartStockAlert"
    );

    if (!page || !items || !system) return;

    const stockUrl =
        page.dataset.stockUrl || "/cart/check-stock";

    const csrf = document.querySelector(
        'meta[name="csrf-token"]'
    )?.content;

    let stockData = {};
    let checked = false;
    let checking = false;
    let requestId = 0;

    const getCart = () => system.getCart();

    /*
    |--------------------------------------------------------------------------
    | FORMAT PRICE
    |--------------------------------------------------------------------------
    */

    const money = value =>
        "₹" +
        Number(value).toLocaleString("en-IN", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {
        const node = document.createElement("div");

        node.textContent = String(value ?? "");

        return node.innerHTML;
    }

    /*
    |--------------------------------------------------------------------------
    | AVAILABLE STOCK
    |--------------------------------------------------------------------------
    */

    function available(slug) {
        const item = stockData[slug];

        return item && item.exists === true
            ? Math.max(
                0,
                Number(item.available) || 0
            )
            : 0;
    }

    /*
    |--------------------------------------------------------------------------
    | WHOLESALE INFORMATION
    |--------------------------------------------------------------------------
    | Wholesale pricing must be verified by the server.
    | Both eligibility and checkout activation are required.
    |--------------------------------------------------------------------------
    */

    function wholesaleInfo(item) {
        if (!checked) return null;

        const latest = stockData[item.slug];

        if (
            !latest ||
            latest.exists !== true ||
            latest.has_wholesale_pricing !== true ||
            latest.wholesale_checkout_enabled !== true
        ) {
            return null;
        }

        const price = Number(latest.wholesale_price);
        const moq = Number(latest.wholesale_moq);

        if (
            latest.wholesale_price == null ||
            latest.wholesale_moq == null ||
            !Number.isFinite(price) ||
            price <= 0 ||
            !Number.isInteger(moq) ||
            moq < 1
        ) {
            return null;
        }

        return {
            price,
            moq
        };
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFIED DATABASE PRICE
    |--------------------------------------------------------------------------
    */

    function verifiedPrice(item) {
        const latest = stockData[item.slug];

        if (
            checked &&
            latest &&
            latest.exists === true
        ) {
            if (
                latest.has_wholesale_pricing === true ||
                latest.wholesale_checkout_enabled === true
            ) {
                const wholesale = wholesaleInfo(item);

                if (wholesale) {
                    return wholesale.price;
                }

                // Invalid wholesale data must not silently
                // become an eligible retail checkout.
                return 0;
            }

            const price = Number(latest.price);

            if (
                latest.price != null &&
                Number.isFinite(price) &&
                price >= 0
            ) {
                return price;
            }
        }

        // Display fallback only.
        // Checkout remains disabled until verification.
        return Math.max(
            0,
            Number(item.price) || 0
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CART VALIDATION
    |--------------------------------------------------------------------------
    */

    function problem(item) {
        if (!checked) {
            return "Product availability has not been verified.";
        }

        const latest = stockData[item.slug];

        if (
            !latest ||
            latest.exists !== true
        ) {
            return "This product is no longer available.";
        }

        const stock = available(item.slug);

        if (stock <= 0) {
            return "Out of Stock";
        }

        const quantity = Number(item.quantity);

        if (
            !Number.isInteger(quantity) ||
            quantity < 1
        ) {
            return "Invalid quantity. Please remove this item.";
        }

        if (quantity > 50) {
            return "Maximum 50 units allowed per product.";
        }

        if (quantity > stock) {
            return (
                "Only " +
                stock +
                " available. Please reduce quantity."
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VERIFY RETAIL PRICE
        |--------------------------------------------------------------------------
        */

        const retailPrice = Number(latest.price);

        if (
            latest.price == null ||
            !Number.isFinite(retailPrice) ||
            retailPrice < 0
        ) {
            return "Unable to verify the latest product price.";
        }

        /*
        |--------------------------------------------------------------------------
        | VERIFY WHOLESALE PRICE AND MOQ
        |--------------------------------------------------------------------------
        */

        if (
            latest.has_wholesale_pricing === true ||
            latest.wholesale_checkout_enabled === true
        ) {
            const wholesale = wholesaleInfo(item);

            if (!wholesale) {
                return (
                    "Unable to verify wholesale pricing. " +
                    "Please refresh and try again."
                );
            }

            if (wholesale.moq > 50) {
                return (
                    "This product's wholesale MOQ exceeds " +
                    "the current 50-unit checkout limit."
                );
            }

            if (quantity < wholesale.moq) {
                return (
                    "Wholesale minimum order is " +
                    wholesale.moq +
                    " units. Please increase quantity."
                );
            }
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | CHECKOUT BUTTON STATE
    |--------------------------------------------------------------------------
    */

    function enableCheckout(enabled) {
        if (!checkout) return;

        checkout.classList.toggle(
            "tob-checkout-disabled",
            !enabled
        );

        checkout.setAttribute(
            "aria-disabled",
            enabled ? "false" : "true"
        );

        checkout.tabIndex = enabled ? 0 : -1;
    }

    /*
    |--------------------------------------------------------------------------
    | STOCK ALERT
    |--------------------------------------------------------------------------
    */

    function updateAlert(cart) {
        if (!alertBox) return;

        let message = "";

        if (checking || !checked) {
            message =
                "Checking latest product availability...";
        } else {
            const issues = cart.filter(
                item => problem(item)
            ).length;

            if (issues) {
                message =
                    issues +
                    (issues === 1
                        ? " product needs attention. "
                        : " products need attention. ") +
                    "Please adjust quantities or remove unavailable products before checkout.";
            }
        }

        alertBox.textContent = message;
        alertBox.hidden = !message;
    }

    /*
    |--------------------------------------------------------------------------
    | RENDER CART
    |--------------------------------------------------------------------------
    */

    function render() {
        const cart = getCart();

        /*
        |--------------------------------------------------------------------------
        | EMPTY CART
        |--------------------------------------------------------------------------
        */

        if (!cart.length) {
            if (filled) filled.hidden = true;
            if (empty) empty.hidden = false;

            if (heading) {
                heading.textContent =
                    "Your cart is waiting for something delicious.";
            }

            if (subtotalEl) {
                subtotalEl.textContent = "₹0";
            }

            if (totalEl) {
                totalEl.textContent = "₹0";
            }

            if (shippingEl) {
                shippingEl.textContent =
                    "Calculated at checkout";

                shippingEl.classList.remove(
                    "tob-free-shipping"
                );
            }

            if (shippingMessage) {
                shippingMessage.textContent =
                    "Add ₹499 more for FREE shipping";

                shippingMessage.classList.remove(
                    "unlocked"
                );
            }

            if (alertBox) {
                alertBox.hidden = true;
            }

            enableCheckout(false);
            system.updateCartCount();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | FILLED CART
        |--------------------------------------------------------------------------
        */

        if (filled) filled.hidden = false;
        if (empty) empty.hidden = true;

        const totalQty = cart.reduce(
            (sum, item) =>
                sum + (Number(item.quantity) || 0),
            0
        );

        if (heading) {
            heading.textContent =
                totalQty +
                (totalQty === 1
                    ? " delicious item waiting for you."
                    : " delicious items waiting for you.");
        }

        items.innerHTML = "";

        /*
        |--------------------------------------------------------------------------
        | PRODUCT ROWS
        |--------------------------------------------------------------------------
        */

        cart.forEach(item => {
            const qty = Number(item.quantity) || 0;

            const price = verifiedPrice(item);

            const wholesale = wholesaleInfo(item);

            const issue = problem(item);

            const latest = checked
                ? stockData[item.slug]
                : null;

            const name =
                latest?.exists === true && latest?.name
                    ? latest.name
                    : item.name;

            const image =
                latest?.exists === true && latest?.image
                    ? latest.image
                    : item.image || "";

            const canPlus =
                checked &&
                !checking &&
                available(item.slug) > qty &&
                qty < 50;

            const canMinus = qty > 1;

            const slugUrl =
                "/product/" +
                encodeURIComponent(item.slug);

            const row = document.createElement("div");

            row.className = "tob-cart-item";
            row.dataset.slug = item.slug;

            row.innerHTML = `
                <div class="tob-cart-product">
                    <a
                        href="${slugUrl}"
                        class="tob-cart-product-image"
                    >
                        <img
                            src="${escapeHtml(image)}"
                            alt="${escapeHtml(name)}"
                        >
                    </a>

                    <div class="tob-cart-product-info">
                        <span class="tob-cart-product-label">
                            THE ONLY BAKER
                        </span>

                        <h3>
                            <a href="${slugUrl}">
                                ${escapeHtml(name)}
                            </a>
                        </h3>

                        ${
                            wholesale
                                ? `
                                    <div style="
                                        margin-top: 7px;
                                        font-size: 12px;
                                        line-height: 1.6;
                                        color: #76513a;
                                    ">
                                        <strong style="
                                            color: #237747;
                                        ">
                                            Wholesale Rate:
                                            ${money(wholesale.price)}
                                        </strong>

                                        <div>
                                            Minimum Order:
                                            ${wholesale.moq} units
                                        </div>

                                        <div style="
                                            margin-top: 3px;
                                            color: #777;
                                            font-size: 11px;
                                        ">
                                            Exclusive approved retailer pricing
                                        </div>
                                    </div>
                                `
                                : ""
                        }

                        <button
                            type="button"
                            class="tob-cart-remove"
                            data-cart-remove
                        >
                            Remove
                        </button>

                        ${
                            issue
                                ? `
                                    <p
                                        class="tob-cart-stock-note"
                                        role="alert"
                                    >
                                        ${escapeHtml(issue)}
                                    </p>
                                `
                                : ""
                        }
                    </div>
                </div>

                <div class="tob-cart-price">
                    ${
                        wholesale
                            ? `
                                <small style="
                                    display: block;
                                    color: #237747;
                                    font-size: 11px;
                                    margin-bottom: 4px;
                                ">
                                    Wholesale Price
                                </small>
                            `
                            : ""
                    }

                    ${money(price)}
                </div>

                <div class="tob-cart-quantity">
                    <button
                        type="button"
                        data-cart-minus
                        aria-label="Decrease quantity"
                        ${canMinus ? "" : "disabled"}
                    >
                        −
                    </button>

                    <span>${qty}</span>

                    <button
                        type="button"
                        data-cart-plus
                        aria-label="Increase quantity"
                        ${canPlus ? "" : "disabled"}
                    >
                        +
                    </button>
                </div>

                <div class="tob-cart-item-total">
                    ${money(price * qty)}
                </div>
            `;

            items.appendChild(row);
        });

        /*
        |--------------------------------------------------------------------------
        | SUBTOTAL FROM VERIFIED PRICES
        |--------------------------------------------------------------------------
        */

        const subtotal = cart.reduce(
            (sum, item) => {
                return (
                    sum +
                    verifiedPrice(item) *
                        (Number(item.quantity) || 0)
                );
            },
            0
        );

        const free = subtotal >= 499;

        if (subtotalEl) {
            subtotalEl.textContent = money(subtotal);
        }

        // Shipping below ₹499 is not finalized here.

        if (totalEl) {
            totalEl.textContent = money(subtotal);
        }

        if (shippingEl) {
            shippingEl.textContent = free
                ? "FREE"
                : "Calculated at checkout";

            shippingEl.classList.toggle(
                "tob-free-shipping",
                free
            );
        }

        if (shippingMessage) {
            shippingMessage.textContent = free
                ? "You unlocked FREE shipping!"
                : "Add " +
                  money(Math.max(0, 499 - subtotal)) +
                  " more for FREE shipping";

            shippingMessage.classList.toggle(
                "unlocked",
                free
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CHECKOUT VALIDATION
        |--------------------------------------------------------------------------
        */

        enableCheckout(
            checked &&
            !checking &&
            cart.every(item => !problem(item))
        );

        updateAlert(cart);

        system.updateCartCount();
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK LATEST STOCK
    |--------------------------------------------------------------------------
    */

    async function checkStock() {
        const cart = getCart();

        const id = ++requestId;

        if (!cart.length) {
            stockData = {};
            checked = true;
            checking = false;

            render();
            return;
        }

        checked = false;
        checking = true;

        render();

        try {
            if (!csrf) {
                throw new Error("CSRF token missing.");
            }

            const response = await fetch(stockUrl, {
                method: "POST",

                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrf
                },

                credentials: "same-origin",
                cache: "no-store",

                body: JSON.stringify({
                    slugs: [
                        ...new Set(
                            cart.map(item => item.slug)
                        )
                    ]
                })
            });

            if (!response.ok) {
                throw new Error("Stock check failed.");
            }

            const result = await response.json();

            if (id !== requestId) return;

            if (
                !result ||
                !result.products ||
                typeof result.products !== "object" ||
                Array.isArray(result.products)
            ) {
                throw new Error(
                    "Invalid stock verification response."
                );
            }

            // Ignore a response if the cart changed
            // while the request was in progress.
            const latestCart = getCart();

            const requestedSlugs = [
                ...new Set(cart.map(item => item.slug))
            ];

            const currentSlugs = [
                ...new Set(latestCart.map(item => item.slug))
            ];

            if (
                JSON.stringify(requestedSlugs) !==
                JSON.stringify(currentSlugs)
            ) {
                checked = false;
                return;
            }

            stockData = result.products;
            checked = true;

        } catch (error) {
            if (id !== requestId) return;

            console.error(error);

            stockData = {};
            checked = false;

        } finally {
            if (id === requestId) {
                checking = false;

                render();

                if (!checked && alertBox) {
                    alertBox.textContent =
                        "Unable to check stock right now. Please refresh the page and try again.";

                    alertBox.hidden = false;
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CART BUTTON ACTIONS
    |--------------------------------------------------------------------------
    */

    items.addEventListener("click", event => {
        const plus = event.target.closest(
            "[data-cart-plus]"
        );

        const minus = event.target.closest(
            "[data-cart-minus]"
        );

        const remove = event.target.closest(
            "[data-cart-remove]"
        );

        const button = plus || minus || remove;

        if (!button || button.disabled) return;

        const slug = button.closest(
            ".tob-cart-item"
        )?.dataset.slug;

        if (!slug) return;

        /*
        |--------------------------------------------------------------------------
        | REMOVE PRODUCT
        |--------------------------------------------------------------------------
        */

        if (remove) {
            system.removeItem(slug);
            checkStock();
            return;
        }

        const item = getCart().find(
            entry => entry.slug === slug
        );

        if (!item) return;

        const qty = Number(item.quantity) || 0;

        /*
        |--------------------------------------------------------------------------
        | INCREASE QUANTITY
        |--------------------------------------------------------------------------
        */

        if (plus) {
            if (
                !checked ||
                checking ||
                qty >= available(slug) ||
                qty >= 50
            ) {
                return;
            }

            system.updateQuantity(
                slug,
                qty + 1
            );

            render();
        }

        /*
        |--------------------------------------------------------------------------
        | DECREASE QUANTITY
        |--------------------------------------------------------------------------
        */

        else if (minus && qty > 1) {
            system.updateQuantity(
                slug,
                qty - 1
            );

            render();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | CLEAR CART
    |--------------------------------------------------------------------------
    */

    if (clear) {
        clear.addEventListener("click", () => {
            system.clearCart();
            checkStock();
        });
    }

    /*
|--------------------------------------------------------------------------
| CHECKOUT
|--------------------------------------------------------------------------
*/

if (checkout) {
    checkout.addEventListener("click", async event => {
        event.preventDefault();

        const cart = getCart();

        if (!cart.length) {
            return;
        }

        const isAuthenticated =
            page.dataset.customerAuthenticated === "1";

        // Guest ko protected checkout route par bhejo.
        // Laravel auth middleware login par redirect karega.
        if (!isAuthenticated) {
            window.location.assign(checkout.href);
            return;
        }

        // Logged-in customer ke liye stock verification.
        if (checking) {
            return;
        }

        await checkStock();

        const latestCart = getCart();

        if (
            latestCart.length > 0 &&
            checked &&
            latestCart.every(item => !problem(item))
        ) {
            window.location.assign(checkout.href);
        }
    });
}

    /*
    |--------------------------------------------------------------------------
    | CART UPDATE EVENT
    |--------------------------------------------------------------------------
    */

    window.addEventListener(
        "tob-cart-updated",
        () => system.updateCartCount()
    );

    /*
    |--------------------------------------------------------------------------
    | RECHECK ON TAB RETURN
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        "visibilitychange",
        () => {
            if (
                !document.hidden &&
                getCart().length
            ) {
                checkStock();
            }
        }
    );

    window.addEventListener(
        "pageshow",
        event => {
            if (event.persisted) {
                checkStock();
            }
        }
    );

    /*
    |--------------------------------------------------------------------------
    | INITIALIZE
    |--------------------------------------------------------------------------
    */

    checkStock();
});
