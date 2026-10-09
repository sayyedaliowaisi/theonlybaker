(function () {

    "use strict";


    function initPasswordToggles() {

        const buttons =
            document.querySelectorAll(
                "[data-password-target]"
            );


        buttons.forEach(function (button) {

            button.addEventListener(
                "click",
                function () {

                    const targetId =
                        button.dataset.passwordTarget;


                    const input =
                        document.getElementById(
                            targetId
                        );


                    if (!input) {
                        return;
                    }


                    const showing =
                        input.type === "text";


                    input.type =
                        showing
                            ? "password"
                            : "text";


                    button.textContent =
                        showing
                            ? "SHOW"
                            : "HIDE";

                }
            );

        });

    }


    if (document.readyState === "loading") {

        document.addEventListener(
            "DOMContentLoaded",
            initPasswordToggles
        );

    } else {

        initPasswordToggles();

    }

})();