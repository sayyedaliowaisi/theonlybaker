(function () {

    const CART_KEY = "theOnlyBakerCart";

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

    function removeItem(slug) {

        const cart = getCart().filter(function (item) {
            return item.slug !== slug;
        });

        saveCart(cart);

        return cart;
    }

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

    function clearCart() {
        saveCart([]);
    }

    function getItemCount() {

        return getCart().reduce(function (total, item) {
            return total + Number(item.quantity || 0);
        }, 0);

    }

    function getSubtotal() {

        return getCart().reduce(function (total, item) {

            return total + (
                Number(item.price) *
                Number(item.quantity)
            );

        }, 0);

    }

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

    window.TheOnlyBakerCart = {
        getCart: getCart,
        saveCart: saveCart,
        addItem: addItem,
        removeItem: removeItem,
        updateQuantity: updateQuantity,
        clearCart: clearCart,
        getItemCount: getItemCount,
        getSubtotal: getSubtotal,
        updateCartCount: updateCartCount
    };

    if (document.readyState === "loading") {

        document.addEventListener(
            "DOMContentLoaded",
            updateCartCount
        );

    } else {
        updateCartCount();
    }

})();