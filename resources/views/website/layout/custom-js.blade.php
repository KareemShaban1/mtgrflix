<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Function to setup star ratings for any container
        function setupStarRating(container, ratingTextElement) {
            const stars = container.querySelectorAll('.star');

            stars.forEach((star, index) => {
                star.addEventListener("click", () => {
                    // Remove active class from all stars in this container
                    stars.forEach(s => s.classList.remove("active"));

                    // Add active class to clicked star and previous ones
                    for (let i = 0; i <= index; i++) {
                        stars[i].classList.add("active");
                    }

                    let ratingMessage = "";
                    switch (index + 1) {
                        case 1:
                            ratingMessage = "غير مرضي";
                            break;
                        case 2:
                            ratingMessage = "مقبول";
                            break;
                        case 3:
                            ratingMessage = "جيد ";
                            break;
                        case 4:
                            ratingMessage = "جيد جدا";
                            break;
                        case 5:
                            ratingMessage = "رائع";
                            break;
                    }
                    if (ratingTextElement) {
                        ratingTextElement.textContent = ratingMessage;
                    }
                });

                star.addEventListener("mouseover", function() {
                    let value = this.getAttribute("data-value");
                    stars.forEach(s => s.style.color = s.getAttribute("data-value") <= value ?
                        "gold" : "gray");
                });

                star.addEventListener("mouseleave", function() {
                    stars.forEach(s => {
                        if (!s.classList.contains("active")) {
                            s.style.color = "gray";
                        }
                    });
                });
            });
        }

        // Initialize all product rating modals (desktop)
        document.querySelectorAll('[id^="modal-rate-product-"]').forEach(modal => {
            initializeProductModal(modal, '.submit-rating');
        });

        // Initialize all product rating modals (mobile)
        // document.querySelectorAll('[id^="modal2-rate-product-"]').forEach(modal => {
        //     initializeProductModal(modal, '.submit-rating-2');
        // });

        // Function to initialize a product modal
        function initializeProductModal(modal, submitButtonSelector) {
            const products = modal.querySelectorAll('.product-slide');
            let currentIndex = 0;

            function showProduct(index) {
                products.forEach((product, i) => {
                    product.style.display = (i === index) ? 'block' : 'none';

                    // Initialize stars for this product when shown
                    if (i === index) {
                        const starsContainer = product.querySelector('.stars-container') ||
                            product.querySelector('.product-stars');
                        const ratingText = product.querySelector('.rating-text');
                        if (starsContainer) {
                            // Reset stars appearance
                            starsContainer.querySelectorAll('.star').forEach(star => {
                                star.classList.remove('active');
                                star.style.color = 'gray';
                            });
                            // Setup star rating for this product
                            setupStarRating(starsContainer, ratingText);
                        }
                    }
                });
            }

            // Initialize first product
            showProduct(currentIndex);

            // Handle submit button click for this modal
            const submitBtn = modal.querySelector(submitButtonSelector);
            if (submitBtn) {
                submitBtn.addEventListener('click', function(event) {
                    event.preventDefault();

                    const currentProduct = products[currentIndex];
                    const orderId = currentProduct.querySelector('.order_id').value;
                    const productId = currentProduct.querySelector('.product_id').value;
                    const activeStars = currentProduct.querySelectorAll('.star.active');
                    const rating = activeStars.length > 0 ? activeStars[activeStars.length - 1]
                        .getAttribute('data-value') : null;
                    const reviewText = currentProduct.querySelector('textarea').value;

                    // Send the product review to the server
                    fetch(`/api/submit-review/${orderId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            },
                            body: JSON.stringify({
                                rating: rating,
                                review: reviewText,
                                product: productId,
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                toastr.success('تم تقييم المنتج بنجاح');

                                // Get the thank you modal
                                const thankYouModal = new bootstrap.Modal(document
                                    .querySelector('[id^="modal-thank-you"]'));

                                // Move to next product or show store rating modal
                                if (currentIndex < products.length - 1) {
                                    currentIndex++;
                                    showProduct(currentIndex);
                                    // Clear previous rating and feedback
                                    currentProduct.querySelector('textarea').value = '';
                                } else {
                                    // After last product, show the store rating modal
                                    // const storeModalElement = document.querySelector(
                                    //     '[id^="modal-rate-store"]');
                                    // const storeModal = bootstrap.Modal.getOrCreateInstance(
                                    //     storeModalElement);
                                    // storeModal.show();

                                    // Hide the current product modal
                                    const productModal = bootstrap.Modal.getInstance(modal);
                                    productModal.hide();

                                    thankYouModal.show();

                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error submitting review:', error);
                        });
                });
            }
        }

        // Store rating modal setup - handle both versions
        document.querySelectorAll('[id^="modal-rate-store"]').forEach(storeModal => {
            const storeStarsContainer = storeModal.querySelector('.stars-container');
            const storeRatingText = storeModal.querySelector('.rating-text');
            if (storeStarsContainer) {
                setupStarRating(storeStarsContainer, storeRatingText);
            }

            const submitStoreRatingBtn = storeModal.querySelector('.submit-store-rating');
            if (submitStoreRatingBtn) {
                submitStoreRatingBtn.addEventListener('click', function(event) {
                    event.preventDefault();

                    const activeStars = storeModal.querySelectorAll('.star.active');
                    const rating = activeStars.length > 0 ? activeStars[activeStars.length - 1]
                        .getAttribute('data-value') : null;
                    const reviewText = storeModal.querySelector('textarea').value;

                    fetch(`/api/submit-testimonial`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: JSON.stringify({
                                rate: rating,
                                comment: reviewText
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log(data);
                                const modalInstance = bootstrap.Modal.getInstance(
                                    storeModal);
                                if (modalInstance) modalInstance.hide();

                                // Get the thank you modal
                                const thankYouModal = new bootstrap.Modal(document
                                    .querySelector('[id^="modal-thank-you"]'));

                                // Update modal content with coupon if available
                                if (data.coupon) {
                                    const couponElement = document.getElementById(
                                        'coupon-display');
                                    if (couponElement) {
                                        couponElement.textContent = data
                                            .coupon; // or format as needed
                                        couponElement.style.display =
                                            'block'; // Make it visible

                                        // Optional: Show discount percentage/amount
                                        const discountElement = document.getElementById(
                                            'coupon-discount');
                                        if (discountElement && data.coupon.discount) {
                                            discountElement.textContent =
                                                `(${data.coupon.discount}% OFF)`;
                                        }
                                    }
                                }

                                thankYouModal.show();
                            }
                        })
                        .catch(error => {
                            console.error('Error submitting store rating:', error);
                        });
                });
            }
        });

        // Countdown timer for thank you modal - handle both versions
        let countdownTime = 30;

        function updateCountdown() {
            const seconds = countdownTime;
            const timeString = `00:${String(seconds).padStart(2, '0')}`;

            document.querySelectorAll('[id^="time-left"]').forEach(timeLeft => {
                timeLeft.textContent = timeString;
            });

            if (countdownTime > 0) {
                countdownTime--;
            } else {
                clearInterval(countdownInterval);
            }
        }

        let countdownInterval;
        document.querySelectorAll('[id^="modal-thank-you"]').forEach(thankYouModal => {
            thankYouModal.addEventListener('show.bs.modal', function() {
                countdownTime = 30;
                countdownInterval = setInterval(updateCountdown, 1000);
            });

            thankYouModal.addEventListener('hidden.bs.modal', function() {
                clearInterval(countdownInterval);
            });
        });

        // Reload the page when clicking on the final thank you button - handle both versions
        document.querySelectorAll('[id^="final-thankyou"]').forEach(finalThankYouBtn => {
            finalThankYouBtn.addEventListener("click", function() {
                location.reload();
            });
        });
    });
</script>