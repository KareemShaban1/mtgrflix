$(function() {
    "use strict";


	$(document).ready(function() {
		$(".banner-slider").owlCarousel({
			loop: true,
			margin: 10,
			responsiveClass: true,
			nav: true,
			navText: [
				"<i class='bx bx-chevron-left'></i>",
				"<i class='bx bx-chevron-right'></i>"
			],
			dots: false,
			autoplay: true,
			autoplayTimeout: 6000,
			rtl: document.documentElement.lang === "ar",  
			responsive: {
				0: { nav: false, items: 1 },
				576: { nav: false, items: 1 },
				768: { nav: false, items: 1 },
				1024: { nav: false, items: 1 },
				1366: { items: 1 },
				1400: { items: 1 }
			}
		});
	});
	

	
	$('.new-arrivals').owlCarousel({
		loop:false,
		margin:24,
		responsiveClass:true,
		nav:true,
		navText: [
			"<i class='bx bx-chevron-left'></i>",
		    "<i class='bx bx-chevron-right' ></i>"
		 ],
		dots: false,
		responsive:{
			0:{
				nav:false,
				margin:16,
				items:2
			},
			576:{
				nav:false,
				items:2
			},
			768:{
				nav:false,
				items:3
			},
			1024:{
				nav:false,
				items:4
			},
			1366:{
				items:4
			},
			1400:{
				items:5
			}
	     },
    	})




		$('.browse-category').owlCarousel({
			loop:true,
			margin:24,
			responsiveClass:true,
			nav:true,
			navText: [
				"<i class='bx bx-chevron-left'></i>",
				"<i class='bx bx-chevron-right' ></i>"
			],
			dots: false,
			responsive:{
				0:{
					nav:false,
					margin:16,
					items:2
				},
				576:{
					nav:false,
					items:2
				},
				768:{
					nav:false,
					items:3
				},
				1024:{
					nav:false,
					items:4
				},
				1366:{
					items:5
				},
				1400:{
					items:5
				}
			 },
			})


			


			$('.latest-news').owlCarousel({
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
				rtl: document.documentElement.lang === "ar", 
				responsive: {
					0: {
						nav: false,
						margin: 16,
						items: 1
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
						items: 3
					}
				},
			});
			




				$('.brands-shops').owlCarousel({
					loop:true,
					margin:0,
					responsiveClass:true,
					nav:true,
					navText: [
						"<i class='bx bx-chevron-left'></i>",
						"<i class='bx bx-chevron-right' ></i>"
					],
					autoplay:true,
					autoplayTimeout:5000,
					dots: false,
					responsive:{
						0:{
							nav:false,
							items:2
						},
						576:{
							nav:false,
							items:3
						},
						768:{
							nav:false,
							items:4
						},
						1024:{
							nav:false,
							items:5
						},
						1366:{
							items:5
						},
						1400:{
							items:6
						}
					 },
					})

		
   });	 
   



// counter start

document.addEventListener("DOMContentLoaded", function () {
    let startValue = 10;
    const endValue = parseInt(counter.getAttribute('data-end-value')) || 100000;
    let duration = 5; 
    let steps = 60 * duration; 
    let increment = (endValue - startValue) / steps;
    let counterElement = document.getElementById("counter");
    let countingStarted = false; 

    function updateCounter(value) {
        counterElement.innerText = Math.floor(value).toLocaleString();
    }

    function startCounting() {
        let currentValue = startValue;
        let interval = setInterval(() => {
            currentValue += increment;
            updateCounter(currentValue);

            if (currentValue >= endValue) {
                clearInterval(interval);
                updateCounter(endValue); 
            }
        }, (duration * 1000) / steps);
    }

    let observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting && !countingStarted) {
                    countingStarted = true; 
                    startCounting();
                }
            });
        },
        { threshold: 0.5 } 
    );

    observer.observe(counterElement);
});

   // counter end


// sharing start

   document.getElementById("shareButton").addEventListener("click", function () {
    let menu = document.getElementById("shareMenu");
    menu.style.display = menu.style.display === "flex" ? "none" : "flex";
});
document.getElementById("copyLink").addEventListener("click", function () {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        toastr.success("تم نسخ الرابط بنجاح!");
    }).catch(err => {
        console.error("فشل في النسخ", err);
    });
});
// sharing end


