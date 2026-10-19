
/* ==========================================================
   THE ONLY BAKER — ADMIN MOBILE NAVIGATION
========================================================== */

document.addEventListener("DOMContentLoaded", () => {

    const toggle = document.getElementById(
        "tobAdminMenuToggle"
    );

    const sidebar = document.getElementById(
        "tobAdminSidebar"
    );

    const overlay = document.getElementById(
        "tobAdminSidebarOverlay"
    );

    if (!toggle || !sidebar || !overlay) return;

    const mobileQuery = window.matchMedia(
        "(max-width: 900px)"
    );

    let previousFocus = null;

    function setMenuOpen(open) {

        const shouldOpen = open && mobileQuery.matches;

        sidebar.classList.toggle(
            "is-open",
            shouldOpen
        );

        overlay.classList.toggle(
            "is-visible",
            shouldOpen
        );

        toggle.setAttribute(
            "aria-expanded",
            String(shouldOpen)
        );

        toggle.setAttribute(
            "aria-label",
            shouldOpen
                ? "Close admin navigation"
                : "Open admin navigation"
        );

        toggle.textContent = shouldOpen ? "✕" : "☰";

        document.body.classList.toggle(
            "tob-admin-menu-open",
            shouldOpen
        );

        if (shouldOpen) {
            previousFocus = document.activeElement;
            const firstLink = sidebar.querySelector("a");
            firstLink?.focus();
        } else if (previousFocus && mobileQuery.matches) {
            toggle.focus();
            previousFocus = null;
        }
    }

    toggle.addEventListener("click", () => {
        setMenuOpen(
            !sidebar.classList.contains("is-open")
        );
    });

    overlay.addEventListener("click", () => {
        setMenuOpen(false);
    });

    document.addEventListener("keydown", (event) => {

        if (!sidebar.classList.contains("is-open")) {
            return;
        }

        if (event.key === "Escape") {
            setMenuOpen(false);
        }

        if (event.key === "Tab") {
            const focusable = [
                ...sidebar.querySelectorAll(
                    'a[href], button:not([disabled])'
                )
            ];

            if (!focusable.length) return;

            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    });

    mobileQuery.addEventListener("change", () => {
        setMenuOpen(false);
    });

});
