
/* ============================================================
   THE ONLY BAKER — SHOP PAGE
   Updated: Product Detail Navigation
   ============================================================ */

document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    /* ============================================================
       ELEMENTS
    ============================================================ */

    const grid = document.getElementById("tobProductsGrid");

    if (!grid) return;

    const cards = Array.from(
        grid.querySelectorAll("[data-product-card]")
    );

    const categories = Array.from(
        document.querySelectorAll(".tob-category-filter")
    );

    const prices = Array.from(
        document.querySelectorAll("[data-price-filter]")
    );

    const dietary = Array.from(
        document.querySelectorAll("[data-dietary-filter]")
    );

    const sort = document.getElementById("tobProductSort");

    const count = document.getElementById("tobProductCount");

    const empty = document.getElementById("tobNoProducts");

    let activeCategory = "all";

    /* ============================================================
       PRICE FILTER
    ============================================================ */

    function matchesPrice(price) {
        const selected = prices
            .filter(input => input.checked)
            .map(input => input.value);

        return (
            selected.length === 0 ||
            selected.some(range => {
                switch (range) {
                    case "under-200":
                        return price < 200;

                    case "200-300":
                        return price >= 200 && price <= 300;

                    case "300-400":
                        return price > 300 && price <= 400;

                    case "above-400":
                        return price > 400;

                    default:
                        return true;
                }
            })
        );
    }

    /* ============================================================
       DIETARY FILTERS

       Database mein dietary attributes abhi available
       nahi hain. Incorrect filtering avoid karne ke liye
       controls disabled rakhe gaye hain.
    ============================================================ */

    dietary.forEach(input => {
        input.checked = false;
        input.disabled = true;

        input.title =
            "Dietary filtering will be available soon";
    });

    /* ============================================================
       APPLY FILTERS
    ============================================================ */

    function applyFilters() {
        let visible = 0;

        cards.forEach(card => {
            const categoryMatch =
                activeCategory === "all" ||
                card.dataset.category === activeCategory;

            const price = Number(card.dataset.price);

            const priceMatch = matchesPrice(price);

            const matches =
                categoryMatch && priceMatch;

            card.classList.toggle(
                "tob-product-hidden",
                !matches
            );

            if (matches) {
                visible++;
            }
        });

        if (count) {
            count.textContent =
                visible +
                (
                    visible === 1
                        ? " Delicious Variety"
                        : " Delicious Varieties"
                );
        }

        if (empty) {
            empty.hidden = visible !== 0;
        }
    }

    /* ============================================================
       CATEGORY FILTER
    ============================================================ */

    categories.forEach(button => {
        button.addEventListener("click", () => {
            activeCategory =
                button.dataset.category || "all";

            categories.forEach(item => {
                item.classList.toggle(
                    "active",
                    item === button
                );
            });

            applyFilters();
        });
    });

    /* ============================================================
       PRICE FILTER EVENTS
    ============================================================ */

    prices.forEach(input => {
        input.addEventListener(
            "change",
            applyFilters
        );
    });

    /* ============================================================
       PRODUCT SORTING
    ============================================================ */

    function sortProducts(type) {
        const sorted = [...cards];

        sorted.sort((a, b) => {
            if (type === "price-low") {
                return (
                    Number(a.dataset.price) -
                    Number(b.dataset.price)
                );
            }

            if (type === "price-high") {
                return (
                    Number(b.dataset.price) -
                    Number(a.dataset.price)
                );
            }

            if (type === "name") {
                const nameA =
                    a.dataset.name ||
                    a.dataset.productName ||
                    "";

                const nameB =
                    b.dataset.name ||
                    b.dataset.productName ||
                    "";

                return nameA.localeCompare(nameB);
            }

            // Preserve original database-rendered order.
            return (
                cards.indexOf(a) -
                cards.indexOf(b)
            );
        });

        sorted.forEach(card => {
            grid.appendChild(card);
        });
    }

    if (sort) {
        sort.addEventListener("change", () => {
            sortProducts(sort.value);
        });
    }

    /* ============================================================
       PRODUCT DETAIL NAVIGATION

       Shop page par direct Add to Cart nahi hoga.

       Blade file mein har product ka View Details link
       route('product.show', $product['slug']) use karega.

       Navigation native anchor link se handle hogi.
       Isliye yahan extra click handler ki zaroorat nahi.
    ============================================================ */

    /* ============================================================
       SIDEBAR ACCORDION
    ============================================================ */

    document
        .querySelectorAll("[data-sidebar-toggle]")
        .forEach(toggle => {
            toggle.addEventListener("click", () => {
                const box = toggle.closest(
                    ".tob-shop-sidebar-box"
                );

                if (box) {
                    box.classList.toggle("collapsed");
                }
            });
        });

    /* ============================================================
       URL CATEGORY FILTER

       Example:
       /cookies?category=chocolate

       Old product-slug category links bhi supported hain.
    ============================================================ */

    function handleUrlCategory() {
        const requested = new URLSearchParams(
            window.location.search
        ).get("category");

        if (!requested) return;

        const allowed = categories
            .map(button => button.dataset.category)
            .filter(Boolean);

        let category = requested;

        // Support old product-slug category links.
        if (!allowed.includes(category)) {
            const card = cards.find(
                item => item.dataset.slug === requested
            );

            category = card?.dataset.category || "";
        }

        const target = categories.find(
            button => button.dataset.category === category
        );

        if (!target) return;

        activeCategory = category;

        categories.forEach(button => {
            button.classList.toggle(
                "active",
                button === target
            );
        });
    }

    /* ============================================================
       INITIALIZE SHOP
    ============================================================ */

    handleUrlCategory();

    sortProducts(
        sort?.value || "featured"
    );

    applyFilters();
});
