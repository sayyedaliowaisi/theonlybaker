
(function () {
    "use strict";

    /*
    |--------------------------------------------------------------------------
    | CART STORAGE CONFIGURATION
    |--------------------------------------------------------------------------
    */

    const CART_KEY = "theOnlyBakerCart";

    /*
    |--------------------------------------------------------------------------
    | GET CART
    |--------------------------------------------------------------------------
    */

    function getCart() {
        try {
            const cart = JSON.parse(
                localStorage.getItem(CART_KEY)
            );

            return Array.isArray(cart) ? cart : [];
        } catch (error) {
            return [];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE CART
    |--------------------------------------------------------------------------
    */

    function saveCart(cart) {
        localStorage.setItem(
            CART_KEY,
            JSON.stringify(cart)
        );

        updateCartCount();

        window.dispatchEvent(
            new CustomEvent("tob-cart-updated", {
                detail: cart
            })
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ADD PRODUCT TO CART
    |--------------------------------------------------------------------------
    */

    function addItem(product) {
        const cart = getCart();

        const existingProduct = cart.find(function (item) {
            return item.slug === product.slug;
        });

        if (existingProduct) {
            existingProduct.quantity += 1;
        } else {
            cart.push({
                slug: product.slug,
                name: product.name,
                price: Number(product.price),
                image: product.image,
                quantity: 1
            });
        }

        saveCart(cart);

        return cart;
    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE PRODUCT FROM CART
    |--------------------------------------------------------------------------
    */

    function removeItem(slug) {
        const cart = getCart().filter(function (item) {
            return item.slug !== slug;
        });

        saveCart(cart);

        return cart;
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PRODUCT QUANTITY
    |--------------------------------------------------------------------------
    */

    function updateQuantity(slug, quantity) {
        const cart = getCart();

        const product = cart.find(function (item) {
            return item.slug === slug;
        });

        if (!product) {
            return cart;
        }

        quantity = Number(quantity);

        if (quantity <= 0) {
            return removeItem(slug);
        }

        product.quantity = quantity;

        saveCart(cart);

        return cart;
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAR CART
    |--------------------------------------------------------------------------
    */

    function clearCart() {
        saveCart([]);
    }

    /*
    |--------------------------------------------------------------------------
    | GET TOTAL ITEM COUNT
    |--------------------------------------------------------------------------
    */

    function getItemCount() {
        return getCart().reduce(function (total, item) {
            return total + Number(item.quantity || 0);
        }, 0);
    }

    /*
    |--------------------------------------------------------------------------
    | CACHED RETAIL SUBTOTAL
    |--------------------------------------------------------------------------
    |
    | This is only an estimate from localStorage.
    |
    | Actual retail/wholesale prices must be verified
    | through the backend before checkout.
    |
    | Never store wholesale prices in localStorage.
    |
    */

    function getSubtotal() {
        return getCart().reduce(function (total, item) {
            const price = Number(item.price);
            const quantity = Number(item.quantity);

            if (
                !Number.isFinite(price) ||
                price < 0 ||
                !Number.isInteger(quantity) ||
                quantity < 1
            ) {
                return total;
            }

            return total + price * quantity;
        }, 0);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE CART COUNT
    |--------------------------------------------------------------------------
    */

    function updateCartCount() {
        const count = getItemCount();

        document
            .querySelectorAll(".tob-cart-count")
            .forEach(function (element) {
                element.textContent = count;

                element.classList.toggle(
                    "has-items",
                    count > 0
                );
            });
    }

    /*
    |--------------------------------------------------------------------------
    | SYNC LATEST DATABASE PRODUCT DETAILS
    |--------------------------------------------------------------------------
    |
    | The stock API's "price" field is the retail price.
    |
    | Wholesale prices are intentionally NOT stored here.
    | Approved retailers receive wholesale prices from
    | the authenticated stock verification response.
    |
    | This prevents stale wholesale prices from remaining
    | in localStorage after logout or account switching.
    |
    */

    function syncProducts(products) {
        if (
            !products ||
            typeof products !== "object" ||
            Array.isArray(products)
        ) {
            return getCart();
        }

        const cart = getCart();

        let changed = false;

        cart.forEach(function (item) {
            const latest = products[item.slug];

            if (!latest || latest.exists !== true) {
                return;
            }

            const price = Number(latest.price);

            if (
                latest.price !== null &&
                latest.price !== undefined &&
                Number.isFinite(price) &&
                price >= 0 &&
                Number(item.price) !== price
            ) {
                item.price = price;
                changed = true;
            }

            if (
                typeof latest.name === "string" &&
                latest.name.trim() &&
                item.name !== latest.name
            ) {
                item.name = latest.name;
                changed = true;
            }
        });

        if (changed) {
            saveCart(cart);
        }

        return cart;
    }

    /*
    |--------------------------------------------------------------------------
    | EXPOSE GLOBAL CART SYSTEM
    |--------------------------------------------------------------------------
    */

    window.TheOnlyBakerCart = {
        getCart: getCart,
        saveCart: saveCart,
        addItem: addItem,
        removeItem: removeItem,
        updateQuantity: updateQuantity,
        clearCart: clearCart,
        getItemCount: getItemCount,
        getSubtotal: getSubtotal,
        updateCartCount: updateCartCount,
        syncProducts: syncProducts
    };

    /*
    |--------------------------------------------------------------------------
    | INITIALIZE CART COUNT
    |--------------------------------------------------------------------------
    */

    if (document.readyState === "loading") {
        document.addEventListener(
            "DOMContentLoaded",
            updateCartCount
        );
    } else {
        updateCartCount();
    }
})();
