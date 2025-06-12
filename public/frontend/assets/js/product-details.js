$(function() {
	"use strict";

    $(document).ready(function() {
		$(".similar-products").owlCarousel({
			loop: true,
			margin: 24,
			responsiveClass: true,
			nav: true,
			navText: [
				"<i class='bx bx-chevron-left'></i>",
				"<i class='bx bx-chevron-right'></i>"
			],
			dots: false,
			autoplay: true,
			autoplayTimeout: 3000,
			autoplayHoverPause: true,
			rtl: document.documentElement.lang === "ar",  // تحديد الاتجاه بناءً على اللغة
			responsive: {
				0: {
					nav: false,
					margin: 16,
					items: 1.5
				},
				576: {
					nav: false,
					items: 2
				},
				768: {
					nav: false,
					items: 3
				},
				1024: {
					nav: false,
					items: 4
				},
				1366: {
					items: 4
				},
				1400: {
					items: 4
				}
			}
		});
	});
	



    $('.product-gallery').owlCarousel({
        loop:true,
        margin:10,
        responsiveClass:true,
        nav:false,
        dots: false,
        thumbs: true,
        thumbsPrerendered: true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
             }
          }
        })

});