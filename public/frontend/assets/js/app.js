$(function () {
  "use strict";


  new PerfectScrollbar('.cart-list');

  // Prevent closing from click inside dropdown

  /*$(document).on('click', '.dropdown-menu', function (e) {
    e.stopPropagation();
  });*/



  // jquery ready start
  $(document).ready(function () {
    // jQuery code

    $("[data-trigger]").on("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      var offcanvas_id = $(this).attr('data-trigger');
      $(offcanvas_id).toggleClass("show");
      $('body').toggleClass("offcanvas-active");
      $(".screen-overlay").toggleClass("show");
    });

    // Close menu when pressing ESC
    $(document).on('keydown', function (event) {
      if (event.keyCode === 27) {
        $(".mobile-offcanvas").removeClass("show");
        $("body").removeClass("overlay-active");
      }
    });

    $(".btn-close, .screen-overlay").click(function (e) {
      $(".screen-overlay").removeClass("show");
      $(".mobile-offcanvas").removeClass("show");
      $("body").removeClass("offcanvas-active");

    });

  }); // jquery end




  $('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
    if (!$(this).next().hasClass('show')) {
      $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
    }
    var $subMenu = $(this).next(".dropdown-menu");
    $subMenu.toggleClass('show');

    $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
      $('.submenu .show').removeClass("show");
    });

    return false;
  });


  $(document).ready(function () {
    $(window).on("scroll", function () {
      $(this).scrollTop() > 300 ? $(".back-to-top").fadeIn() : $(".back-to-top").fadeOut()
    }), $(".back-to-top").on("click", function () {
      return $("html, body").animate({
        scrollTop: 0
      }, 600), !1
    })
  }),


    $(".btn-mobile-filter").on("click", function () {
      $(".filter-sidebar").removeClass("d-none")
    }),

    $(".btn-mobile-filter-close").on("click", function () {
      $(".filter-sidebar").addClass("d-none")
    }),


    $(".switcher-btn").on("click", function () {
      $(".switcher-wrapper").toggleClass("switcher-toggled")
    }),

    $(".close-switcher").on("click", function () {
      $(".switcher-wrapper").removeClass("switcher-toggled")
    }),


    $('#theme1').click(theme1);
  $('#theme2').click(theme2);
  $('#theme3').click(theme3);
  $('#theme4').click(theme4);
  $('#theme5').click(theme5);
  $('#theme6').click(theme6);
  $('#theme7').click(theme7);
  $('#theme8').click(theme8);
  $('#theme9').click(theme9);
  $('#theme10').click(theme10);
  $('#theme11').click(theme11);
  $('#theme12').click(theme12);
  $('#theme13').click(theme13);
  $('#theme14').click(theme14);
  $('#theme15').click(theme15);

  function theme1() {
    $('body').attr('class', 'bg-theme bg-theme1');
  }

  function theme2() {
    $('body').attr('class', 'bg-theme bg-theme2');
  }

  function theme3() {
    $('body').attr('class', 'bg-theme bg-theme3');
  }

  function theme4() {
    $('body').attr('class', 'bg-theme bg-theme4');
  }

  function theme5() {
    $('body').attr('class', 'bg-theme bg-theme5');
  }

  function theme6() {
    $('body').attr('class', 'bg-theme bg-theme6');
  }

  function theme7() {
    $('body').attr('class', 'bg-theme bg-theme7');
  }

  function theme8() {
    $('body').attr('class', 'bg-theme bg-theme8');
  }

  function theme9() {
    $('body').attr('class', 'bg-theme bg-theme9');
  }

  function theme10() {
    $('body').attr('class', 'bg-theme bg-theme10');
  }

  function theme11() {
    $('body').attr('class', 'bg-theme bg-theme11');
  }

  function theme12() {
    $('body').attr('class', 'bg-theme bg-theme12');
  }

  function theme13() {
    $('body').attr('class', 'bg-theme bg-theme13');
  }

  function theme14() {
    $('body').attr('class', 'bg-theme bg-theme14');
  }

  function theme15() {
    $('body').attr('class', 'bg-theme bg-theme15');
  }

});

// language change
document.addEventListener("DOMContentLoaded", function () {
  const selectedFlag = document.getElementById("selected-flag");
  const selectedLanguage = document.getElementById("selected-language");
  const languageItems = document.querySelectorAll("#language-menu .dropdown-item");

  languageItems.forEach(item => {
    item.addEventListener("click", function (event) {
      const newLanguage = this.getAttribute("data-lang");
      const newFlag = this.getAttribute("data-flag");

      selectedLanguage.textContent = newLanguage;
      selectedFlag.className = "flag-icon flag-icon-" + newFlag;

      window.location.href = this.getAttribute("href");
    });
  });
});

// language change end


// search start
document.addEventListener("DOMContentLoaded", function () {
  const searchBox = document.getElementById("search-box");
  const searchToggle = document.getElementById("search-toggle");
  const searchToggleMobile = document.getElementById("search-toggle-mobile");
  const closeSearch = document.getElementById("close-search");
  const searchInput = document.getElementById("search-input");
  const searchIcon = document.getElementById("search-icon");
  const searchResults = document.getElementById("search-results");

  function toggleSearch() {
    searchBox.classList.toggle("d-none");
    if (!searchBox.classList.contains("d-none")) {
      searchInput.focus();
    }
  }

  function performSearch() {
    const query = searchInput.value.trim();
    if (query !== "") {

      searchIcon.classList.remove("bx-search");
      searchIcon.classList.add("bx-loader", "bx-spin");

      setTimeout(() => {
        searchIcon.classList.remove("bx-loader", "bx-spin");
        searchIcon.classList.add("bx-search");

        searchResults.classList.remove("d-none");
      }, 2000);
    }
  }

  searchToggle?.addEventListener("click", toggleSearch);
  searchToggleMobile?.addEventListener("click", toggleSearch);
  closeSearch?.addEventListener("click", toggleSearch);

  searchInput.addEventListener("keypress", function (event) {
    if (event.key === "Enter") {
      performSearch();
    }
  });

  searchBox.addEventListener("click", function (event) {
    if (event.target === searchBox) {
      toggleSearch();
    }
  });
});

// search end



// Currencies
document.addEventListener("DOMContentLoaded", function () {
  const currencyDropdown = document.getElementById("currency-dropdown");
  const currencyItems = document.querySelectorAll("#currency-menu .dropdown-item");

  currencyItems.forEach(item => {
    item.addEventListener("click", function (event) {
      event.preventDefault();
      const selectedCurrency = this.getAttribute("data-currency");
      currencyDropdown.textContent = selectedCurrency;
    });
  });
});


// // modal mobile

// document.addEventListener("DOMContentLoaded", function () {
//   const loginButton = document.getElementById("loginButton");
//   const phoneInput = document.getElementById("phoneInput");
//   const selectedCode = document.getElementById("selectedCode");
//   const otpModalElement = document.getElementById("otpModal");
//   const loginModalElement = document.getElementById("loginModal");
//   const otpInputs = document.querySelectorAll(".otp-input");
//   const verifyOtpButton = document.getElementById("verifyOtpButton");
//   const loginLink = document.getElementById("loginLink");
//   const profileIconMobile = document.getElementById("profileIconMobile");
//   const userNameElement = document.getElementById("userName");
//   const userNameMobileElement = document.getElementById("userNameMobile");
//   const welcomeMessage = document.getElementById("welcomeMessage");
//   const resendTimer = document.getElementById("resendTimer");
//   const saveUserInfoButton = document.getElementById("saveUserInfo");
//   const userInfoModalElement = document.getElementById("userInfoModal");
//   const userDropdown = document.getElementById("userDropdown");

//   let otpModal = new bootstrap.Modal(otpModalElement);
//   let loginModal = new bootstrap.Modal(loginModalElement);
//   let userInfoModal = new bootstrap.Modal(userInfoModalElement);
//   let countdown = 30;
//   let timer;


//   let resendCooldown = 60 * 5; // 60 seconds cooldown

//   function startResendTimer() {
//     resendTimer.innerText = `يمكنك إعادة الإرسال بعد ${resendCooldown} ثانية`;
//     let interval = setInterval(() => {
//       resendCooldown--;
//       if (resendCooldown <= 0) {
//         resendTimer.innerText = "";
//         clearInterval(interval);
//         document.getElementById('sendOtpButton').disabled = false;
//       } else {
//         resendTimer.innerText = `يمكنك إعادة الإرسال بعد ${resendCooldown} ثانية`;
//       }
//     }, 1000);
//   }

//   document.getElementById("verifyOtpButton").addEventListener("click", function () {
//     let enteredOtp = "";
//     otpInputs.forEach(input => enteredOtp += input.value);
//     const cartRoute = "{{ route('cart') }}";
//     const fullPhoneNumber = selectedCode.textContent + phoneInput.value.trim();

//     if (enteredOtp.length === otpInputs.length) {
//       fetch('/verify-otp', {
//         method: 'POST',
//         headers: {
//           'Content-Type': 'application/json',
//           'Accept': 'application/json',
//           'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
//         },
//         body: JSON.stringify({
//           phone_number: userPhone.textContent.trim(),
//           otp: enteredOtp,
//           country_code: selectedCode.textContent,
//           fullPhoneNumber: fullPhoneNumber
//         })
//       })
//         .then(res => res.json())
//         .then(data => {
//           if (data.status) {
//             // show next step
//             if (data.user) {
//               toastr.success('مرحبا بك في فليكس');
//               location.reload();
//             }

//             else if (data.order) {
//               toastr.success('مرحبا بك في فليكس');

//               // window.location.href = 'cart/manage';
//             }
//             else {
//               otpModal.hide();
//               userInfoModal.show();
//             }
//           } else {
//             toastr.error(data.message || "رمز غير صحيح");
//           }
//         });
//     } else {
//       toastr.error("يرجى إدخال 4 أرقام.");
//     }
//   });

//   document.getElementById("sendOtpButton").addEventListener("click", function () {
//     const fullPhoneNumber = selectedCode.textContent + phoneInput.value.trim();

//     fetch('/resend-otp', {
//       method: 'POST',
//       headers: {
//         'Content-Type': 'application/json',
//         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
//       },
//       body: JSON.stringify({
//         phone_number: userPhone.textContent.trim(),
//         fullPhoneNumber: fullPhoneNumber,
//         country_code: selectedCode.textContent

//       })
//     })
//       .then(res => res.json())
//       .then(data => {
//         if (data.status) {
//           toastr.success("تم إرسال الرمز مجددًا");
//           document.getElementById('sendOtpButton').disabled = true;
//           resendCooldown = 60;
//           startResendTimer();
//         } else {
//           toastr.error(data.message || "فشل إرسال الرمز");
//         }
//       });
//   });


//   document.querySelectorAll('.dropdown-menu.country-list .dropdown-item').forEach(item => {
//     item.addEventListener('click', function (e) {
//       e.preventDefault();

//       const selectedFlag = this.getAttribute('data-flag');
//       const selectedCode = this.getAttribute('data-code');

//       document.getElementById('selectedFlag').textContent = selectedFlag;
//       document.getElementById('selectedCode').textContent = selectedCode;
//     });
//   });

//   loginButton.addEventListener("click", function () {
//     const fullPhoneNumber = selectedCode.textContent + phoneInput.value.trim();

//     if (phoneInput.value.trim() === "") {
//       toastr.error("يرجى إدخال رقم الهاتف");
//       return;
//     }

//     document.getElementById("userPhone").innerText = phoneInput.value.trim();

//     fetch("/send-otp", {
//       method: "POST",
//       headers: {
//         "Content-Type": "application/json",
//         'Accept': 'application/json',

//         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//       },
//       body: JSON.stringify({
//         phone: fullPhoneNumber,
//         country_code: selectedCode.textContent,
//         phone_number: phoneInput.value.trim()
//       })
//     })
//       .then(data => {
//         console.log(data);
//         if (data.status) {
//           // console.log("OTP sent successfully");
//           loginModal.hide();
//           otpModal.show();
//           document.getElementById('sendOtpButton').disabled = true;
//           resendCooldown = 60;
//           startResendTimer();
//           toastr.success(data.message || 'تم إرسال رمز التحقق');
//         } else {
//           if (data.errors) {
//             for (const key in data.errors) {
//               toastr.error(data.errors[key][0]);
//             }
//           } else {
//             console.log("Error sending OTP:", data);
//             toastr.error(data.message || 'حدث خطأ.');
//           }
//         }
//       })
//       .catch(error => {
//         console.error("Error sending OTP:", error);
//         toastr.error("فشل الاتصال بالخادم.");
//       });
//   });

//   otpInputs.forEach((input, index) => {
//     input.style.direction = "rtl";
//     input.style.textAlign = "center";
//     input.addEventListener("input", function () {
//       this.value = this.value.replace(/[^0-9]/g, '');
//       if (this.value.length === 1 && index > 0) {
//         otpInputs[index - 1].focus();
//       }
//     });
//     input.addEventListener("keydown", function (e) {
//       if (e.key === "Backspace" && this.value === "" && index < otpInputs.length - 1) {
//         otpInputs[index + 1].focus();
//       }
//     });
//   });



//   document.getElementById("saveUserInfo").addEventListener("click", function () {
//     let firstName = document.getElementById("firstName").value.trim();
//     let lastName = document.getElementById("lastName").value.trim();
//     let email = document.getElementById("email").value.trim();
//     let phone = document.getElementById("userPhone").innerText.trim(); // from OTP screen
//     let selectedCode = document.getElementById("selectedCode");

//     if (firstName && lastName && email) {
//       fetch("/register", {
//         method: "POST",
//         headers: {
//           "Content-Type": "application/json",
//           "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
//         },
//         body: JSON.stringify({
//           first_name: firstName,
//           last_name: lastName,
//           email: email,
//           phone: phone,
//           country_code: selectedCode.textContent,
//         })
//       })
//         .then(response => response.json())
//         .then(data => {
//           console.log(data, data.status);
//           if (data.status) {
//             // close modal

//             // console.log("User registered successfully");           
//             const userInfoModal = bootstrap.Modal.getInstance(document.getElementById('userInfoModal'));
//             userInfoModal.hide();
//             // show user info
//             // document.getElementById("userNameElement").innerText = firstName + ' ' + lastName;
//             // document.getElementById("userNameMobileElement").innerText = firstName + ' ' + lastName;
//             // document.getElementById("userDropdown").classList.remove("d-none");
//             // document.getElementById("loginLink").classList.add("d-none");
//             // document.getElementById("profileIconMobile").classList.remove("d-none");
//             // document.getElementById("welcomeMessage").classList.remove("d-none");

//             toastr.success(data.message || "تم التسجيل بنجاح");
//             location.reload();
//           } else {
//             console.log("Error registering user:", data);
//             toastr.error(data.message || "فشل التسجيل");
//           }
//         })
//         .catch(error => {
//           console.error("Register error:", error);
//           // toastr.error("فشل الاتصال بالخادم.");
//         });
//     } else {
//       toastr.error("يرجى إدخال جميع البيانات المطلوبة.");
//     }
//   });



//   function startResendCountdown() {
//     countdown = 30;
//     resendTimer.style.display = "block";
//     resendTimer.innerHTML = `يمكنك إعادة الإرسال بعد <span id="countdown">${countdown}</span> ثانية`;
//     let countdownElement = document.getElementById("countdown");
//     timer = setInterval(function () {
//       countdown--;
//       if (countdown > 0) {
//         countdownElement.textContent = countdown;
//       } else {
//         clearInterval(timer);
//         resendTimer.textContent = "يمكنك إعادة الإرسال الآن";
//       }
//     }, 1000);
//   }

//   profileIconMobile.addEventListener("click", function () {
//     let mobileUserModal = new bootstrap.Modal(document.getElementById("mobileUserModal"));
//     mobileUserModal.show();
//   });

//   document.getElementById("logoutButton").addEventListener("click", function () {
//     loginLink.classList.remove("d-none");
//     profileIconMobile.classList.add("d-none");
//     welcomeMessage.classList.add("d-none");
//     userDropdown.classList.add("d-none");
//     let mobileUserModal = bootstrap.Modal.getInstance(document.getElementById("mobileUserModal"));
//     if (mobileUserModal) mobileUserModal.hide();
//   });

//   document.getElementById("backToLogin").addEventListener("click", function () {
//     otpModal.hide();
//     loginModal.show();
//   });
// });


// offer
document.getElementById("closeAdBtn").addEventListener("click", function () {
  document.querySelector(".top-menu").style.display = "none";
});


// profile image  start
// document.getElementById("profileImageInput").addEventListener("change", function (event) {
//   const file = event.target.files[0];
//   if (file) {
//     const reader = new FileReader();
//     reader.onload = function (e) {
//       const image = document.getElementById("profileImage");
//       image.src = e.target.result;
//       setTimeout(function () {
//         document.getElementById("removeImage").style.display = "flex";
//       }, 100);
//     };
//     reader.readAsDataURL(file);
//   }
// });

// document.getElementById("removeImage").addEventListener("click", function () {
//   document.getElementById("profileImage").src = "assets/image/avatar_male.webp";
//   document.getElementById("profileImageInput").value = "";
//   this.style.display = "none";
// });

// profile image  end




