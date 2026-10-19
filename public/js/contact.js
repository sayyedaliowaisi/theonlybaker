
/* ==========================================================
   THE ONLY BAKER — CONTACT PAGE
   Final UI & Form Experience
========================================================== */

document.addEventListener("DOMContentLoaded", () => {

    const page = document.querySelector(".tob-contact-page");

    if (!page) return;

    const form = document.getElementById("tobContactForm");

    const reduceMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)"
    ).matches;

    /* ======================================================
       SMOOTH ANCHOR SCROLL
    ====================================================== */

    document.querySelectorAll(
        '.tob-contact-page a[href^="#"]'
    ).forEach((link) => {

        link.addEventListener("click", (event) => {

            const targetId = link.getAttribute("href");

            if (!targetId || targetId === "#") return;

            const target = document.querySelector(targetId);

            if (!target) return;

            event.preventDefault();

            target.scrollIntoView({
                behavior: reduceMotion ? "auto" : "smooth",
                block: "start"
            });

            history.replaceState(null, "", targetId);

        });

    });

    /* ======================================================
       FORM SUBMIT LOADING STATE
    ====================================================== */

    if (form) {

        const submitButton = form.querySelector(
            'button[type="submit"]'
        );

        form.addEventListener("submit", (event) => {

            if (!form.checkValidity()) return;

            if (form.dataset.submitting === "true") {
                event.preventDefault();
                return;
            }

            form.dataset.submitting = "true";

            if (submitButton) {

                submitButton.disabled = true;

                submitButton.innerHTML = `
                    <span class="tob-contact-spinner"
                          aria-hidden="true"></span>
                    Sending Message...
                `;

            }

            // Screen reader status message
    const status = document.getElementById(
        "contactSubmitStatus"
    );

    if (status) {
        status.textContent =
            "Your message is being submitted.";
    }

        });

        // Restore button if page returns from browser cache.
        window.addEventListener("pageshow", () => {

            form.dataset.submitting = "false";

            if (submitButton) {

                submitButton.disabled = false;

                submitButton.innerHTML = `
                    Send Your Message
                    <span aria-hidden="true">↗</span>
                `;

            }

        });

    }

    /* ======================================================
       SUCCESS / VALIDATION ALERT SCROLL
    ====================================================== */

    const alertBox = document.querySelector(
        ".tob-contact-alert"
    );

    if (alertBox) {

        alertBox.setAttribute("tabindex", "-1");

        requestAnimationFrame(() => {

            alertBox.scrollIntoView({
                behavior: "auto",
                block: "center"
            });

            alertBox.focus({
                preventScroll: true
            });

        });

    }

});
