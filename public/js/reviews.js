document.addEventListener(
    "DOMContentLoaded",
    function () {

        const starContainer =
            document.getElementById(
                "tobReviewStars"
            );

        const ratingInput =
            document.getElementById(
                "tobReviewRating"
            );

        const selectedRatingText =
            document.getElementById(
                "tobSelectedRating"
            );

        const reviewText =
            document.getElementById(
                "tobReviewText"
            );

        const characterCount =
            document.getElementById(
                "tobReviewCharacterCount"
            );


        /* =====================================================
           STAR RATING
        ====================================================== */

        if (
            starContainer &&
            ratingInput
        ) {

            const stars =
                Array.from(
                    starContainer.querySelectorAll(
                        ".tob-review-star"
                    )
                );


            const labels = {
                1: "Poor",
                2: "Fair",
                3: "Good",
                4: "Very Good",
                5: "Excellent"
            };


            function getSelectedRating() {

                const value =
                    parseInt(
                        ratingInput.value,
                        10
                    );

                return Number.isNaN(value)
                    ? 0
                    : value;

            }


            function paintStars(
                rating,
                preview
            ) {

                stars.forEach(
                    function (star) {

                        const starRating =
                            Number(
                                star.dataset.rating
                            );


                        star.classList.toggle(
                            preview
                                ? "preview"
                                : "active",
                            starRating <= rating
                        );


                        if (preview) {

                            star.classList.remove(
                                "active"
                            );

                        }

                    }
                );


                if (
                    !preview &&
                    selectedRatingText
                ) {

                    selectedRatingText.textContent =
                        rating > 0
                            ? rating +
                              " / 5 — " +
                              labels[rating]
                            : "Select your rating";

                }

            }


            function restoreSelectedRating() {

                stars.forEach(
                    function (star) {

                        star.classList.remove(
                            "preview"
                        );

                    }
                );


                paintStars(
                    getSelectedRating(),
                    false
                );

            }


            stars.forEach(
                function (star) {

                    star.addEventListener(
                        "mouseenter",
                        function () {

                            const rating =
                                Number(
                                    star.dataset.rating
                                );


                            stars.forEach(
                                function (item) {

                                    item.classList.remove(
                                        "active"
                                    );

                                }
                            );


                            paintStars(
                                rating,
                                true
                            );

                        }
                    );


                    star.addEventListener(
                        "click",
                        function () {

                            const rating =
                                Number(
                                    star.dataset.rating
                                );


                            ratingInput.value =
                                rating;


                            stars.forEach(
                                function (item) {

                                    item.classList.remove(
                                        "preview"
                                    );

                                }
                            );


                            paintStars(
                                rating,
                                false
                            );

                        }
                    );


                    star.addEventListener(
                        "focus",
                        function () {

                            const rating =
                                Number(
                                    star.dataset.rating
                                );

                            paintStars(
                                rating,
                                true
                            );

                        }
                    );


                    star.addEventListener(
                        "blur",
                        restoreSelectedRating
                    );

                }
            );


            starContainer.addEventListener(
                "mouseleave",
                restoreSelectedRating
            );


            restoreSelectedRating();

        }


        /* =====================================================
           REVIEW CHARACTER COUNTER
        ====================================================== */

        if (
            reviewText &&
            characterCount
        ) {

            function updateCharacterCount() {

                characterCount.textContent =
                    reviewText.value.length;

            }


            reviewText.addEventListener(
                "input",
                updateCharacterCount
            );


            updateCharacterCount();

        }


        /* =====================================================
           REVIEW LINK SMOOTH SCROLL
        ====================================================== */

        document
            .querySelectorAll(
                'a[href="#reviews"]'
            )
            .forEach(
                function (link) {

                    link.addEventListener(
                        "click",
                        function (event) {

                            const reviews =
                                document.getElementById(
                                    "reviews"
                                );


                            if (!reviews) {
                                return;
                            }


                            event.preventDefault();


                            reviews.scrollIntoView({
                                behavior: "smooth",
                                block: "start"
                            });

                        }
                    );

                }
            );

    }
);