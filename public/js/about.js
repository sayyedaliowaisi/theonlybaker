
/* ==========================================================
   THE ONLY BAKER — ABOUT US PAGE ANIMATIONS
========================================================== */

document.addEventListener("DOMContentLoaded", () => {
    const page = document.querySelector(".tob-about-page");

    if (!page) return;

    const reducedMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)"
    ).matches;

    const revealSelectors = [
        ".tob-about-hero .tob-about-eyebrow",
        ".tob-about-hero h1",
        ".tob-about-hero p",
        ".tob-about-hero .tob-about-btn",

        ".tob-about-story-visual",
        ".tob-about-story-content",

        ".tob-about-craft-heading",
        ".tob-about-craft-visual",
        ".tob-about-craft-card",

        ".tob-about-values-heading",
        ".tob-about-value-card",
        ".tob-about-values-footer",

        ".tob-about-discover-heading",
        ".tob-about-discover-card",
        ".tob-about-discover-footer",

        ".tob-about-final-content"
    ];

    const elements = page.querySelectorAll(
        revealSelectors.join(", ")
    );

    // Do not hide content when animations are unsupported.
    if (
        reducedMotion ||
        !("IntersectionObserver" in window)
    ) {
        return;
    }

    const observer = new IntersectionObserver(
        (entries, currentObserver) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;

                entry.target.classList.add(
                    "tob-about-reveal-visible"
                );

                currentObserver.unobserve(entry.target);
            });
        },
        {
            threshold: 0.08,
            rootMargin: "0px 0px -25px 0px"
        }
    );

    elements.forEach((element) => {
    const rect = element.getBoundingClientRect();

    if (rect.bottom <= 0) {
        return;
    }

    element.classList.add("tob-about-reveal");
    observer.observe(element);
});
});
