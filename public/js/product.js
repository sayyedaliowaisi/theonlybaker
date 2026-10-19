
/* THE ONLY BAKER — PRODUCT DETAILS */

document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    const page = document.getElementById("tobProductPage");

    if (!page) return;

    /* PRODUCT DATA */

    const product = {
        slug: page.dataset.productSlug,
        name: page.dataset.productName,
        price: Number(page.dataset.productPrice),
        image: page.dataset.productImage,
        stock: Math.max(
            0,
            Number(page.dataset.productStock) || 0
        )
    };

    const checkoutUrl =
        page.dataset.checkoutUrl || "/checkout";

    const stockUrl =
        page.dataset.stockUrl || "/cart/check-stock";

    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]'
    )?.content;

    
    const wholesaleEnabled =
        page.dataset.wholesaleEnabled === "1";

    const rawMoq = Number(page.dataset.wholesaleMoq);

    const wholesaleMoq =
        wholesaleEnabled &&
        Number.isInteger(rawMoq) &&
        rawMoq >= 1 &&
        rawMoq <= 50
            ? rawMoq
            : 1;

    const minQuantity = wholesaleEnabled
        ? wholesaleMoq
        : 1;

    const maxQuantity = Math.min(
        50,
        Math.max(0, Math.floor(product.stock))
    );

    const inStock =
        maxQuantity >= minQuantity &&
        (!wholesaleEnabled ||
            (Number.isInteger(rawMoq) &&
                rawMoq >= 1 &&
                rawMoq <= 50));


    const input = document.getElementById(
        "tobProductQuantity"
    );

    const minus = document.getElementById(
        "tobProductMinus"
    );

    const plus = document.getElementById(
        "tobProductPlus"
    );

    const addButton = document.getElementById(
        "tobProductAddCart"
    );

    const buyButton = document.getElementById(
        "tobProductBuyNow"
    );

    let submitting = false;

    
    /* QUANTITY */

    function getQuantity() {
        if (!inStock) return 0;

        const value = Number.parseInt(
            input?.value || String(minQuantity),
            10
        );

        return Math.min(
            maxQuantity,
            Math.max(
                minQuantity,
                Number.isFinite(value)
                    ? value
                    : minQuantity
            )
        );
    }

    function setQuantity(value) {
        if (!inStock) return;

        const quantity = Math.min(
            maxQuantity,
            Math.max(
                minQuantity,
                Math.floor(Number(value) || minQuantity)
            )
        );

        if (input) {
            input.value = String(quantity);
            input.min = String(minQuantity);
            input.max = String(maxQuantity);
        }

        if (minus) {
            minus.disabled = quantity <= minQuantity;
        }

        if (plus) {
            plus.disabled = quantity >= maxQuantity;
        }
    }



    /* INITIAL QUANTITY STATE */

    if (!inStock) {
        if (input) input.disabled = true;
        if (minus) minus.disabled = true;
        if (plus) plus.disabled = true;
    } else {
        setQuantity(minQuantity);
    }

    if (minus) {
        minus.addEventListener("click", () => {
            setQuantity(getQuantity() - 1);
        });
    }

    if (plus) {
        plus.addEventListener("click", () => {
            setQuantity(getQuantity() + 1);
        });
    }

    if (input) {
        input.addEventListener("change", () => {
            setQuantity(getQuantity());
        });

        input.addEventListener("blur", () => {
            setQuantity(getQuantity());
        });
    }

    /* BUTTON STATES */

    for (const button of [addButton, buyButton]) {
        if (!button) continue;

        button.disabled = !inStock;

        button.setAttribute(
            "aria-disabled",
            inStock ? "false" : "true"
        );
    }

    /* VERIFY LATEST STOCK FROM LARAVEL */

    async function fetchLatest() {
        if (!csrfToken) {
            throw new Error("CSRF token missing.");
        }

        const response = await fetch(stockUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            credentials: "same-origin",
            cache: "no-store",
            body: JSON.stringify({
                slugs: [product.slug]
            })
        });

        if (!response.ok) {
            throw new Error("Stock check failed.");
        }

        const result = await response.json();

        return result.products?.[product.slug] || {
            exists: false,
            available: 0
        };
    }

    /* ADD PRODUCT TO CART */

    async function addProductToCart(quantity) {
        if (!inStock || !product.slug) {
            return false;
        }

        const system = window.TheOnlyBakerCart;

        if (!system) {
            console.error(
                "Cart system is not loaded."
            );
            return false;
        }

        let latest;

        try {
            latest = await fetchLatest();
        } catch (error) {
            console.error(error);

            alert(
                "Unable to verify availability. Please try again."
            );

            return false;
        }

        const available = Math.max(
            0,
            Math.floor(Number(latest.available) || 0)
        );

        if (
            latest.exists === false ||
            available <= 0
        ) {
            alert(
                "This product is currently unavailable."
            );

            return false;
        }

        quantity = Number(quantity);

        
        const serverWholesaleEnabled =
            latest.wholesale_checkout_enabled === true &&
            latest.has_wholesale_pricing === true;

        const serverMoq = Number(latest.wholesale_moq);

        const latestMinimum =
            serverWholesaleEnabled &&
            Number.isInteger(serverMoq) &&
            serverMoq >= 1 &&
            serverMoq <= 50
                ? serverMoq
                : 1;

        if (
            wholesaleEnabled !== serverWholesaleEnabled ||
            (
                serverWholesaleEnabled &&
                (
                    !Number.isInteger(serverMoq) ||
                    serverMoq < 1 ||
                    serverMoq > 50 ||
                    serverMoq !== wholesaleMoq
                )
            )
        ) {
            alert(
                "Wholesale availability has changed. Please refresh the page."
            );
            return false;
        }

        if (
            !Number.isInteger(quantity) ||
            quantity < latestMinimum ||
            quantity > Math.min(50, available)
        ) {
            alert(
                "Please select an available quantity" +
                (serverWholesaleEnabled
                    ? " of at least " + latestMinimum + " units."
                    : ".")
            );

            return false;
        }


        const existing = system
            .getCart()
            .find(item => item.slug === product.slug);

        const oldQuantity = Math.max(
            0,
            Number(existing?.quantity) || 0
        );

        if (
    oldQuantity + quantity > Math.min(available, 50)
) {
            alert(
                "Only " +
                Math.min(available, 50) +
                " unit(s) are available for this product in your cart."
            );

            return false;
        }

        const latestPrice = Number(latest.price);

        const item = {
            slug: product.slug,
            name: latest.name || product.name,
            price:
                Number.isFinite(latestPrice) &&
                latestPrice >= 0
                    ? latestPrice
                    : product.price,
            image: product.image
        };

        if (existing) {
            system.updateQuantity(
                product.slug,
                oldQuantity + quantity
            );
        } else {
            system.addItem(item);

            if (quantity > 1) {
                system.updateQuantity(
                    product.slug,
                    quantity
                );
            }
        }

        system.updateCartCount();

        return true;
    }

    /* LOADING STATE */

    function setBusy(busy) {
        submitting = busy;

        for (const button of [addButton, buyButton]) {
            if (!button) continue;

            button.disabled = busy || !inStock;

            button.setAttribute(
                "aria-disabled",
                button.disabled ? "true" : "false"
            );
        }
    }

    /* ADD TO CART BUTTON */

    if (addButton && inStock) {
        addButton.addEventListener(
            "click",
            async () => {
                if (submitting) return;

                setBusy(true);

                try {
                    const success =
                        await addProductToCart(
                            getQuantity()
                        );

                    if (!success) return;

                    const label =
                        addButton.querySelector("span");

                    const original =
                        label?.textContent || "ADD TO CART";

                    addButton.classList.add("added");

                    if (label) {
                        label.textContent =
                            "ADDED TO CART";
                    }

                    window.setTimeout(() => {
                        addButton.classList.remove(
                            "added"
                        );

                        if (label) {
                            label.textContent = original;
                        }
                    }, 1400);
                } finally {
                    setBusy(false);
                }
            }
        );
    }

    /* BUY NOW BUTTON */

    if (buyButton && inStock) {
        buyButton.addEventListener(
            "click",
            async () => {
                if (submitting) return;

                setBusy(true);

                try {
                    const success =
                        await addProductToCart(
                            getQuantity()
                        );

                    if (success) {
                        window.location.href =
                            checkoutUrl;
                    }
                } finally {
                    setBusy(false);
                }
            }
        );
    }

    /* PRODUCT GALLERY */

    const mainImage = document.querySelector(
        "[data-product-main-image]"
    );

    const thumbnails = document.querySelectorAll(
        "[data-product-thumbnail]"
    );

    thumbnails.forEach(thumb => {
        thumb.addEventListener("click", () => {
            if (
                !mainImage ||
                !thumb.dataset.image
            ) {
                return;
            }

            mainImage.style.opacity = "0";

            window.setTimeout(() => {
                mainImage.src = thumb.dataset.image;
                mainImage.style.opacity = "1";
            }, 130);

            thumbnails.forEach(item => {
                item.classList.remove("active");
            });

            thumb.classList.add("active");
        });
    });

    /* PRODUCT IMAGE ZOOM */

    const zoomButton = document.querySelector(
        ".tob-product-zoom"
    );

    if (zoomButton && mainImage) {
        const modal = document.createElement("div");

        modal.className =
            "tob-product-zoom-modal";

        modal.innerHTML = `
            <div class="tob-product-zoom-backdrop"></div>

            <div class="tob-product-zoom-content">
                <button
                    type="button"
                    class="tob-product-zoom-close"
                    aria-label="Close image"
                >×</button>

                <img src="" alt="">
            </div>
        `;

        document.body.appendChild(modal);

        const image = modal.querySelector("img");

        const close = () => {
            modal.classList.remove("active");
            document.body.style.overflow = "";
        };

        zoomButton.addEventListener("click", () => {
            image.src = mainImage.src;

            image.alt =
                product.name || "Product image";

            modal.classList.add("active");

            document.body.style.overflow = "hidden";
        });

        modal
            .querySelector(".tob-product-zoom-close")
            .addEventListener("click", close);

        modal
            .querySelector(".tob-product-zoom-backdrop")
            .addEventListener("click", close);

        document.addEventListener(
            "keydown",
            event => {
                if (
                    event.key === "Escape" &&
                    modal.classList.contains("active")
                ) {
                    close();
                }
            }
        );
    }

    /* ACCORDIONS */

    const accordions = document.querySelectorAll(
        ".tob-product-accordion"
    );

    accordions.forEach(accordion => {
        const trigger = accordion.querySelector(
            ".tob-accordion-trigger"
        );

        if (!trigger) return;

        trigger.addEventListener("click", () => {
            const open =
                accordion.classList.contains("active");

            accordions.forEach(item => {
                item.classList.remove("active");

                const itemTrigger = item.querySelector(
                    ".tob-accordion-trigger"
                );

                const icon = item.querySelector(
                    ".tob-accordion-icon"
                );

                if (itemTrigger) {
                    itemTrigger.setAttribute(
                        "aria-expanded",
                        "false"
                    );
                }

                if (icon) {
                    icon.textContent = "+";
                }
            });

            if (!open) {
                accordion.classList.add("active");

                trigger.setAttribute(
                    "aria-expanded",
                    "true"
                );

                const icon = accordion.querySelector(
                    ".tob-accordion-icon"
                );

                if (icon) {
                    icon.textContent = "−";
                }
            }
        });
    });

    /* RELATED PRODUCTS SLIDER */

    const track = document.getElementById(
        "tob-related-track"
    );

    if (track) {
        document
            .querySelectorAll(".tob-related-arrow")
            .forEach(button => {
                button.addEventListener("click", () => {
                    const card = track.querySelector(
                        ".tob-related-card"
                    );

                    const amount = card
                        ? card.getBoundingClientRect().width + 20
                        : 280;

                    track.scrollBy({
                        left:
                            button.dataset.direction === "left"
                                ? -amount
                                : amount,
                        behavior: "smooth"
                    });
                });
            });
    }
});
