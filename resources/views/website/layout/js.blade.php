  <!-- Bootstrap JS -->
  <!-- jQuery -->
  <script src="{{ asset('frontend') }}/assets/js/jquery.min.js"></script>
  <script src="{{ asset('frontend') }}/assets/plugins/OwlCarousel/js/owl.carousel.min.js"></script>
  <script src="{{ asset('frontend') }}/assets/plugins/OwlCarousel/js/owl.carousel2.thumbs.min.js"></script>
  <script src="{{ asset('frontend') }}/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
  <script src="{{ asset('frontend') }}/assets/plugins/purecounter/purecounter_vanilla.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("body").on("contextmenu", function(e) {
                return false;
            });

            $(document).on('cut copy paste', function(e) {
                e.preventDefault();
            });

        });

        function copyCode() {
            event.preventDefault();

            $(document).off('cut copy paste');

            const codeElement = document.getElementById('code-to-copy');
            const htmlContent = codeElement.innerHTML;

            let formattedText = htmlContent
                .replace(/<br\s*\/?>/gi, '\n')
                .replace(/<\/p>/gi, '\n')
                .replace(/<p>/gi, '')
                .replace(/<[^>]*>/g, '')
                .replace(/&nbsp;/g, ' ')
                .replace(/&amp;/g, '&')
                .replace(/&lt;/g, '<')
                .replace(/&gt;/g, '>')
                .replace(/\n{3,}/g, '\n\n')
                .trim();

            formattedText = formattedText.replace(/^\[|\]$/g, '');

            const textarea = document.createElement('textarea');
            textarea.value = formattedText;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();

            let notificationShown = false;

            const showNotification = () => {
                if (!notificationShown) {
                    notificationShown = true;
                    if (typeof toastr !== 'undefined') {
                        toastr.success("{{ __('filament.copied_successfully') }}");
                    } else {
                        alert("{{ __('filament.copied_successfully') }}");
                    }
                }
            };

            try {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(formattedText)
                        .then(showNotification)
                        .catch(() => {
                            document.execCommand('copy');
                            showNotification();
                        });
                } else {
                    document.execCommand('copy');
                    showNotification();
                }
            } catch (err) {
                if (typeof toastr !== 'undefined') {
                    toastr.error("{{ __('filament.copy_error') }}");
                } else {
                    alert("{{ __('filament.copy_error') }}");
                }
            } finally {
                document.body.removeChild(textarea);
                setTimeout(() => {
                    $(document).on('cut copy paste', function(e) {
                        e.preventDefault();
                    });
                }, 100);
            }

            return false;
        }
    </script>

  <!-- Bootstrap -->
  <script src="{{ asset('frontend') }}/assets/js/bootstrap.bundle.min.js"></script>

  <!-- js -->
  <script src="{{ asset('frontend') }}/assets/js/app.js"></script>
  <script src="{{ asset('frontend') }}/assets/js/index.js"></script>
  <script>
      // Handle language selection and redirect
      document.querySelectorAll('#language-menu .dropdown-item').forEach(item => {
          item.addEventListener('click', function(e) {
              e.preventDefault();
              const url = item.getAttribute('data-url');
              window.location.href = url; // Redirect to the selected language
          });
      });
  </script>

  <!-- Toastr JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script>
      document.querySelectorAll('#currency-menu .dropdown-item').forEach(item => {
          item.addEventListener('click', function(e) {
              e.preventDefault();
              const currency = this.dataset.currency;

              fetch("{{ route('set.currency') }}", {
                      method: "POST",
                      headers: {
                          'X-CSRF-TOKEN': '{{ csrf_token() }}',
                          'Content-Type': 'application/json'
                      },
                      body: JSON.stringify({
                          currency: currency
                      })
                  })
                  .then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          location.reload(); // or update prices dynamically
                      }
                  });
          });
      });
  </script>
  <script>
      @if (session('success'))
          toastr.success("{{ session('success') }}");
      @endif

      @if (session('error'))
          toastr.error("{{ session('error') }}");
      @endif
  </script>
  <script>
    $('#search-input').on('input', function () {
        let query = $(this).val().trim();

        // Optional: Skip if query is too short
        // if (query.length < 2) {
        //     $('#search-results').addClass('d-none');
        //     return;
        // }

        $.ajax({
            url: '/search/products',
            method: 'GET',
            data: { query: query },
            success: function (data) {
                const resultsDiv = $('#search-results');
                resultsDiv.empty();

                if (data.length === 0) {
                    resultsDiv.text("{{ __('site.no_search_results') }}");
                } else {
                    data.forEach(item => {
                        const a = $('<a></a>')
                            .attr('href', item.url)
                            .addClass('d-block text-start mb-2')
                            .text(item.name);
                        resultsDiv.append(a);
                    });
                }

                resultsDiv.removeClass('d-none');
            },
            error: function (xhr) {
                console.error('Search failed:', xhr.responseText);
            }
        });
    });
</script>


  <script>
      // modal mobile

      document.addEventListener("DOMContentLoaded", function() {
          const loginButton = document.getElementById("loginButton");
          const phoneInput = document.getElementById("phoneInput");
          const selectedCode = document.getElementById("selectedCode");
          const otpModalElement = document.getElementById("otpModal");
          const loginModalElement = document.getElementById("loginModal");
          const otpInputs = document.querySelectorAll(".otp-input");
          const verifyOtpButton = document.getElementById("verifyOtpButton");
          const loginLink = document.getElementById("loginLink");
          const profileIconMobile = document.getElementById("profileIconMobile");
          const userNameElement = document.getElementById("userName");
          const userNameMobileElement = document.getElementById("userNameMobile");
          const welcomeMessage = document.getElementById("welcomeMessage");
          const resendTimer = document.getElementById("resendTimer");
          const saveUserInfoButton = document.getElementById("saveUserInfo");
          const userInfoModalElement = document.getElementById("userInfoModal");
          const userDropdown = document.getElementById("userDropdown");

          let otpModal = new bootstrap.Modal(otpModalElement);
          let loginModal = new bootstrap.Modal(loginModalElement);
          let userInfoModal = new bootstrap.Modal(userInfoModalElement);
          let countdown = 30;
          let timer;


          let resendCooldown = 60 * 5; // 60 seconds cooldown

          function startResendTimer() {
              resendTimer.innerText = `يمكنك إعادة الإرسال بعد ${resendCooldown} ثانية`;
              let interval = setInterval(() => {
                  resendCooldown--;
                  if (resendCooldown <= 0) {
                      resendTimer.innerText = "";
                      clearInterval(interval);
                      document.getElementById('sendOtpButton').disabled = false;
                  } else {
                      resendTimer.innerText = `يمكنك إعادة الإرسال بعد ${resendCooldown} ثانية`;
                  }
              }, 1000);
          }

          document.getElementById("verifyOtpButton").addEventListener("click", function() {
              let enteredOtp = "";
              otpInputs.forEach(input => enteredOtp += input.value);
              const cartRoute = "{{ route('cart') }}";
              const fullPhoneNumber = selectedCode.textContent + phoneInput.value.trim();

              if (enteredOtp.length === otpInputs.length) {
                  fetch('/api/verify-otp', {
                          method: 'POST',
                          headers: {
                              'Content-Type': 'application/json',
                              'Accept': 'application/json',
                              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                  .content
                          },
                          body: JSON.stringify({
                              phone_number: userPhone.textContent.trim(),
                              otp: enteredOtp,
                              country_code: selectedCode.textContent,
                              fullPhoneNumber: fullPhoneNumber
                          })
                      })
                      .then(res => res.json())
                      .then(data => {
                          if (data.status) {
                              // show next step
                              if (data.user) {
                                  toastr.success('مرحبا بك في فليكس');
                                  otpModal.hide();
                                  window.isAuthenticated = true;
                                  document.dispatchEvent(new CustomEvent('userLoggedIn'));

                                  document.querySelector(".before_login").classList.add("d-none");
                                  document.querySelector(".after_login").classList.remove("d-none");
                              } else {
                                  otpModal.hide();
                                  userInfoModal.show();
                              }
                          } else {
                              toastr.error(data.message || "رمز غير صحيح");
                          }
                      });
              } else {
                  toastr.error("يرجى إدخال 4 أرقام.");
              }
          });

          document.getElementById("sendOtpButton").addEventListener("click", function() {
              const fullPhoneNumber = selectedCode.textContent + phoneInput.value.trim();

              fetch('/api/resend-otp', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                      },
                      body: JSON.stringify({
                          phone_number: userPhone.textContent.trim(),
                          fullPhoneNumber: fullPhoneNumber,
                          country_code: selectedCode.textContent

                      })
                  })
                  .then(res => res.json())
                  .then(data => {
                      if (data.status) {
                          toastr.success("تم إرسال الرمز مجددًا");
                          document.getElementById('sendOtpButton').disabled = true;
                          resendCooldown = 60;
                          startResendTimer();
                      } else {
                          toastr.error(data.message || "فشل إرسال الرمز");
                      }
                  });
          });


          document.querySelectorAll('.dropdown-menu.country-list .dropdown-item').forEach(item => {
              item.addEventListener('click', function(e) {
                  e.preventDefault();

                  const selectedFlag = this.getAttribute('data-flag');
                  const selectedCode = this.getAttribute('data-code');

                  document.getElementById('selectedFlag').textContent = selectedFlag;
                  document.getElementById('selectedCode').textContent = selectedCode;
              });
          });

          loginButton.addEventListener("click", function() {
              const fullPhoneNumber = selectedCode.textContent + phoneInput.value.trim();

              if (phoneInput.value.trim() === "") {
                  toastr.error("يرجى إدخال رقم الهاتف");
                  return;
              }

              document.getElementById("userPhone").innerText = phoneInput.value.trim();

              fetch("/api/send-otp", {
                      method: "POST",
                      headers: {
                          "Content-Type": "application/json",
                          'Accept': 'application/json',

                          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                              .getAttribute('content')
                      },
                      body: JSON.stringify({
                          phone: fullPhoneNumber,
                          country_code: selectedCode.textContent,
                          phone_number: phoneInput.value.trim()
                      })
                  })
                  .then(data => {
                      console.log(data);
                      if (data.status) {
                          // console.log("OTP sent successfully");
                          loginModal.hide();
                          otpModal.show();
                          document.getElementById('sendOtpButton').disabled = true;
                          resendCooldown = 60;
                          startResendTimer();
                          toastr.success(data.message || 'تم إرسال رمز التحقق');
                      } else {
                          if (data.errors) {
                              for (const key in data.errors) {
                                  toastr.error(data.errors[key][0]);
                              }
                          } else {
                              console.log("Error sending OTP:", data);
                              toastr.error(data.message || 'حدث خطأ.');
                          }
                      }
                  })
                  .catch(error => {
                      console.error("Error sending OTP:", error);
                      toastr.error("فشل الاتصال بالخادم.");
                  });
          });

          otpInputs.forEach((input, index) => {
              input.style.direction = "rtl";
              input.style.textAlign = "center";
              input.addEventListener("input", function() {
                  this.value = this.value.replace(/[^0-9]/g, '');
                  if (this.value.length === 1 && index > 0) {
                      otpInputs[index - 1].focus();
                  }
              });
              input.addEventListener("keydown", function(e) {
                  if (e.key === "Backspace" && this.value === "" && index < otpInputs.length -
                      1) {
                      otpInputs[index + 1].focus();
                  }
              });
          });



          document.getElementById("saveUserInfo").addEventListener("click", function() {
              let firstName = document.getElementById("firstName").value.trim();
              let lastName = document.getElementById("lastName").value.trim();
              let email = document.getElementById("email").value.trim();
              let phone = document.getElementById("userPhone").innerText.trim(); // from OTP screen
              let selectedCode = document.getElementById("selectedCode");

              if (firstName && lastName && email) {
                  fetch("/api/register", {
                          method: "POST",
                          headers: {
                              "Content-Type": "application/json",
                              "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                  .content
                          },
                          body: JSON.stringify({
                              first_name: firstName,
                              last_name: lastName,
                              email: email,
                              phone: phone,
                              country_code: selectedCode.textContent,
                          })
                      })
                      .then(response => response.json())
                      .then(data => {
                          console.log(data, data.status);
                          if (data.status) {

                              const userInfoModal = bootstrap.Modal.getInstance(document
                                  .getElementById('userInfoModal'));
                              userInfoModal.hide();


                              toastr.success(data.message || "تم التسجيل بنجاح");
                              window.isAuthenticated = true;
                              document.dispatchEvent(new CustomEvent('userLoggedIn'));

                              document.querySelector(".before_login").classList.add("d-none");
                              document.querySelector(".after_login").classList.remove("d-none");

                          } else {
                              console.log("Error registering user:", data);
                              toastr.error(data.message || "فشل التسجيل");
                          }
                      })
                      .catch(error => {
                          console.error("Register error:", error);
                          // toastr.error("فشل الاتصال بالخادم.");
                      });
              } else {
                  toastr.error("يرجى إدخال جميع البيانات المطلوبة.");
              }
          });



          function startResendCountdown() {
              countdown = 30;
              resendTimer.style.display = "block";
              resendTimer.innerHTML = `يمكنك إعادة الإرسال بعد <span id="countdown">${countdown}</span> ثانية`;
              let countdownElement = document.getElementById("countdown");
              timer = setInterval(function() {
                  countdown--;
                  if (countdown > 0) {
                      countdownElement.textContent = countdown;
                  } else {
                      clearInterval(timer);
                      resendTimer.textContent = "يمكنك إعادة الإرسال الآن";
                  }
              }, 1000);
          }

          profileIconMobile.addEventListener("click", function() {
              let mobileUserModal = new bootstrap.Modal(document.getElementById("mobileUserModal"));
              mobileUserModal.show();
          });

          document.getElementById("logoutButton").addEventListener("click", function() {
              loginLink.classList.remove("d-none");
              profileIconMobile.classList.add("d-none");
              welcomeMessage.classList.add("d-none");
              userDropdown.classList.add("d-none");
              let mobileUserModal = bootstrap.Modal.getInstance(document.getElementById(
                  "mobileUserModal"));
              if (mobileUserModal) mobileUserModal.hide();
          });

          document.getElementById("backToLogin").addEventListener("click", function() {
              otpModal.hide();
              loginModal.show();
          });
      });
  </script>




  @yield('script')
