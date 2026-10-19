
/* ==========================================================
   THE ONLY BAKER — COLLECTIONS PAGE ANIMATIONS
========================================================== */

document.addEventListener("DOMContentLoaded", () => {
    const page = document.querySelector(".tob-collections-page");

    if (!page) return;

    const revealElements = page.querySelectorAll([
        ".tob-collections-section-heading",
        ".tob-collection-category-card",
        ".tob-featured-card",
        ".tob-featured-footer",
        ".tob-gifting-banner-inner",
        ".tob-cookie-special-card",
        ".tob-curated-card",
        ".tob-shop-mood-card",
        ".tob-collections-final-cta .tob-collections-container"
    ].join(","));

    if (!revealElements.length) return;

    const reducedMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)"
    ).matches;

    if (reducedMotion || !("IntersectionObserver" in window)) {
        return;
    }

    // Add animation classes only after JS is ready.
    revealElements.forEach((element, index) => {
        element.classList.add("tob-reveal");

        // Small stagger effect for cards.
        if (
            element.matches(
                ".tob-collection-category-card, " +
                ".tob-featured-card, " +
                ".tob-cookie-special-card, " +
                ".tob-curated-card, " +
                ".tob-shop-mood-card"
            )
        ) {
            element.style.setProperty(
                "--tob-reveal-delay",
                `${(index % 4) * 75}ms`
            );
        }
    });

    const observer = new IntersectionObserver(
        (entries, currentObserver) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;

                entry.target.classList.add("tob-reveal-visible");

                currentObserver.unobserve(entry.target);
            });
        },
        {
            threshold: 0.08,
            rootMargin: "0px 0px -25px 0px"
        }
    );

    revealElements.forEach((element) => {
        observer.observe(element);
    });
});


/* ==========================================================
   COLLECTIONS QUICK NAVIGATION
========================================================== */

document.addEventListener("DOMContentLoaded", () => {
    const page = document.querySelector(".tob-collections-page");
    if (!page) return;

    const navLinks = Array.from(
        page.querySelectorAll(".tob-collections-nav-link")
    );

    if (!navLinks.length) return;

    const sections = navLinks
        .map((link) => document.querySelector(link.getAttribute("href")))
        .filter(Boolean);

    const prefersReducedMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)"
    ).matches;

    function setActive(id) {
        navLinks.forEach((link) => {
            const active = link.getAttribute("href") === `#${id}`;

            link.classList.toggle("is-active", active);

            if (active) {
                link.setAttribute("aria-current", "location");
            } else {
                link.removeAttribute("aria-current");
            }
        });
    }

    navLinks.forEach((link) => {
        link.addEventListener("click", (event) => {
            const target = document.querySelector(
                link.getAttribute("href")
            );

            if (!target) return;

            event.preventDefault();

            setActive(target.id);

            target.querySelectorAll(".tob-reveal").forEach((element) => {
    element.classList.add("tob-reveal-visible");
});

if (target.classList.contains("tob-reveal")) {
    target.classList.add("tob-reveal-visible");
}

            history.pushState(null, "", `#${target.id}`);

            target.scrollIntoView({
                behavior: prefersReducedMotion ? "auto" : "smooth",
                block: "start"
            });
        });
    });

    if ("IntersectionObserver" in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        setActive(entry.target.id);
                    }
                });
            },
            {
                rootMargin: "-15% 0px -65% 0px",
                threshold: 0
            }
        );

        sections.forEach((section) => observer.observe(section));
    }

    if (window.location.hash) {
        const initialId = window.location.hash.slice(1);

        if (sections.some((section) => section.id === initialId)) {
            setActive(initialId);
        }
    }
});

