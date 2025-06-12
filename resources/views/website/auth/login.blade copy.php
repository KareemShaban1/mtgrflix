<!doctype html>
<html lang="ar" >

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="في متجر فليكس نوفرلك البطاقات والاشتراكات والخدمات الرقمية بأعلي جودة ممكنه وبأقل سعر ممكن لان رضاك يهمنا 💙	">
	<meta name="keywords" content=" زيادة لايكات .زيادة مشاهدات .كرانشي رول .watch it .amazon prime video ,osn plus .شاهد vip .FLIX .زيادة متابعين ">
	<meta property="og:image" content="https://mtgrflix.com/{{ asset('frontend') }}/assets/image/logo.avif">

	<!--favicon-->
	<link rel="icon" href="{{ asset('frontend') }}/assets/image/logo.avif" type="image/png" />
	<!--plugins-->
	<link href="{{ asset('frontend') }}/assets/plugins/OwlCarousel/css/owl.carousel.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

	<!-- font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">

	<!-- loader-->
	<link href="{{ asset('frontend') }}/assets/css/pace.min.css" rel="stylesheet" />
	<script src="{{ asset('frontend') }}/assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('frontend') }}/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="{{ asset('frontend') }}/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css2?family=Albert+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
	<link href="{{ asset('frontend') }}/assets/css/app.css" rel="stylesheet">
	<link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
	<link href="{{ asset('frontend') }}/assets/css/icons.css" rel="stylesheet">
	<title>متجر فليكس</title>   
    <style>
      
        .login-card {
          background: #fff;
          border-radius: 15px;
          box-shadow: 0 4px 20px rgba(0,0,0,0.1);
          padding: 40px 30px;
          width: 100%;
         
          max-width: 400px;
          text-align: center;
          animation: fadeIn 0.8s ease;
        }
    
        .login-card .form-control,
        .login-card .btn,
        .login-card .dropdown-toggle {
          border-radius: 10px;
        }
    
        .country-list {
          max-height: 200px;
          overflow-y: auto;
        }
    
        @keyframes fadeIn {
          0% { opacity: 0; transform: translateY(30px);}
          100% { opacity: 1; transform: translateY(0);}
        }
      </style>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">

		<!--start top header wrapper-->
		<div class="header-wrapper">
			<!-- top-menu -->
			<div class="top-menu" id="topAdBar">
				<div class="container-fluid d-flex align-items-center justify-content-center position-relative">
					<button class="ad-close-btn" id="closeAdBtn">×</button>
				  <div class="marquee-wrapper">
					<div class="marquee-text">
					  🚀 عرض خاص! خصومات تصل إلى 50% على جميع المنتجات - لا تفوت الفرصة! 🎉
					</div>
				  </div>
				</div>
			  </div>
			  

			<!-- header-content -->
			<div class="header-content bg-header">
				<div class="container">
					<div class="row align-items-center justify-content-between ">
						
						<!--  My Account + Cart + Search (Aligned to the far right on large screens and mobile) -->
						<div class="col-auto d-flex align-items-center gap-3 text-white my-account" >


							
						<!-- Login Modal Start-->
						<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content text-center">
									<div class="modal-header border-0">
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<i class="bx bx-user-circle text-primary" style="font-size: 80px;"></i>
										<h5 class="mb-3 mt-2">تسجيل الدخول</h5>
										<p class="text-dark">أدخل رقم هاتفك للمتابعة</p>
										
										<div class="mb-3 d-flex">
											<input type="tel" class="form-control text-center flex-grow-1" id="phoneInput" placeholder="أدخل رقم هاتفك" required>
											<div class="dropdown me-2">
												<button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="countryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
													<span id="selectedFlag">🇪🇬</span> 
													<span id="selectedCode">+20</span>
												</button>
												<ul class="dropdown-menu country-list" aria-labelledby="countryDropdown" style="max-height: 200px; overflow-y: auto;">
													<li><a class="dropdown-item" href="#" data-code="+966" data-flag="🇸🇦">🇸🇦 السعودية (+966)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+971" data-flag="🇦🇪">🇦🇪 الإمارات (+971)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+965" data-flag="🇰🇼">🇰🇼 الكويت (+965)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+973" data-flag="🇧🇭">🇧🇭 البحرين (+973)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+968" data-flag="🇴🇲">🇴🇲 عمان (+968)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+974" data-flag="🇶🇦">🇶🇦 قطر (+974)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+1" data-flag="🇺🇸">🇺🇸 أمريكا (+1)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+20" data-flag="🇪🇬">🇪🇬 مصر (+20)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+44" data-flag="🇬🇧">🇬🇧 المملكة المتحدة (+44)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+33" data-flag="🇫🇷">🇫🇷 فرنسا (+33)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+91" data-flag="🇮🇳">🇮🇳 الهند (+91)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+49" data-flag="🇩🇪">🇩🇪 ألمانيا (+49)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+61" data-flag="🇦🇺">🇦🇺 أستراليا (+61)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+34" data-flag="🇪🇸">🇪🇸 إسبانيا (+34)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+39" data-flag="🇮🇹">🇮🇹 إيطاليا (+39)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+81" data-flag="🇯🇵">🇯🇵 اليابان (+81)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+55" data-flag="🇧🇷">🇧🇷 البرازيل (+55)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+7" data-flag="🇷🇺">🇷🇺 روسيا (+7)</a></li>
													<li><a class="dropdown-item" href="#" data-code="+82" data-flag="🇰🇷">🇰🇷 كوريا الجنوبية (+82)</a></li>
												</ul>
												
											</div>
										</div>
										<button class="btn btn-primary w-100" id="loginButton">متابعة</button>
									</div>
								</div>
							</div>
						</div>
						<!-- Login Modal End -->

						<!-- Modal OTP Start -->
						<div class="modal fade " id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content text-center ">
									<div class="modal-header border-0 position-relative">
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										<button type="button" class="btn position-absolute top-0 end-0 m-3 bg-light" id="backToLogin">
											<i class="bx bx-arrow-to-right" style="font-size: 24px;"></i>
										</button>
									</div>
									<div class="modal-body">
										<i class="bx bx-lock text-primary" style="font-size: 80px;"></i>
										<h5 class="mb-3 mt-2">تسجيل الدخول</h5>
										<p class="mb-1 mt-2 text-dark"> ادخل رمز التحقق المرسل على الواتساب</p>
										<i class="bx bxl-whatsapp text-success fa-3x " ></i>
										<p class="text-dark">رقم التحقق مطلوب لإكمال العملية <br> لقد تم إرسال رمز التحقق إلى:</p>
										<strong id="userPhone" class="text-dark"></strong>

										<div class="d-flex justify-content-center otp-container pt-4">
											<input type="text" class="otp-input form-control text-center mx-1 rounded-3" maxlength="1">
											<input type="text" class="otp-input form-control text-center mx-1 rounded-3" maxlength="1">
											<input type="text" class="otp-input form-control text-center mx-1 rounded-3" maxlength="1">
											<input type="text" class="otp-input form-control text-center mx-1 rounded-3" maxlength="1">
										</div>

										<button class="btn btn-primary w-100 mt-3" id="verifyOtpButton">تحقق</button>
										<button class="btn btn-secondary w-100 mt-2" id="sendOtpButton">إعادة إرسال </button>

										<p class="mt-2 text-muted"><span id="resendTimer"></span></p>
									</div>
								</div>
							</div>
						</div> 
						<!-- Modal OTP End -->

						<!-- User Info Modal Start -->
						<div class="modal fade" id="userInfoModal" tabindex="-1" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header border-0">
										<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
									</div>
									<div class="modal-body">
										<h5><i class="fas fa-user-plus pb-4"></i> تسجيل الدخول</h5>

										<div class="mb-3">
											<label for="firstName" class="form-label text-dark">اسمك الكريم</label>
											<input type="text" class="form-control mt-1" id="firstName" placeholder="الاسم الأول" required dir="rtl">
										</div>

										<div class="mb-3">
											<label for="lastName" class="form-label text-dark">الاسم الأخير</label>
											<input type="text" class="form-control mt-1" id="lastName" placeholder="الاسم الأخير" required dir="rtl">
										</div>

										<div class="mb-3">
											<label for="email" class="form-label text-dark">البريد الإلكتروني</label>
											<input type="email" class="form-control mt-1" id="email" placeholder="البريد الإلكتروني" required dir="rtl">
										</div>

										<button class="btn btn-primary w-100 mt-3" id="saveUserInfo">تسجيل</button>
									</div>
								</div>
							</div>
						</div>
						<!-- User Info Modal End -->

                            
							<!--  user menu hidden-->
							<div class="dropdown d-none" id="userDropdown">
								<a class="btn dropdown-toggle d-flex align-items-center text-white" href="#" role="button" data-bs-toggle="dropdown">
									<img src="{{ asset('frontend') }}/assets/image/avatar_male.webp" alt="User" class="profile-img">
									<div class="d-none d-xl-block fs-6">
										<div>مرحبًا بك</div>
										<small id="userName" >اسم المستخدم</small>
									</div>
								</a>

								<ul class="dropdown-menu border-0 shadow-lg">
									<li><a class="dropdown-item d-flex align-items-center gap-3" href="notifications.html">
										<i class="bx bx-bell fs-5"></i> الاشعارات
									</a></li>
									<li><a class="dropdown-item d-flex align-items-center gap-3" href="orders.html">
										<i class="bx bx-package fs-5"></i> الطلبات
									</a></li>
									
									<li><a class="dropdown-item d-flex align-items-center gap-3" href="account-user-details.html">
										<i class="bx bx-user fs-5"></i> حسابي
									</a></li>
									<li><hr class="dropdown-divider"></li>
									<li><a class="dropdown-item text-danger d-flex align-items-center gap-3" href="#" id="logoutButton">
										<i class="bx bx-log-out fs-5"></i> تسجيل الخروج
									</a></li>
								</ul>
							</div>



							<!-- cart shop -->
							<div class="d-flex align-items-center gap-3 cart-shop">
								<button class="btn d-md-none search-mobile" id="search-toggle-mobile">
									<i class='bx bx-search text-white'></i>
								</button>
								<a href="shop-cart.html" class="d-flex align-items-center text-white text-decoration-none position-relative cart-mobile">
									<i class="fa-solid fa-bag-shopping fs-3 m-2"></i>
									<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
										8
									</span>
									<span class="d-none d-md-block fs-6">
										<div>السلة</div>
										<small class="text-white">45 <img src="{{ asset('frontend') }}/assets/image/ryal-wh.png" class="logo-price" alt="">	
											<!-- <i class='bx bx-search text-white'></i> -->
										</small>
									</span>
								</a>
							
								<!-- Before Login -->
								<a href="#" class="d-flex align-items-center text-white text-decoration-none account-mobile m-2"
								data-bs-toggle="modal" data-bs-target="#loginModal" id="loginLink">
								<i class='bx bx-user fs-3 m-2'></i>
								<span class="d-none d-md-block fs-6">
									<div>حسابي</div>
									<small class="text-white">تسجيل الدخول</small>
								</span>
								</a>

								<!-- After Login -->
								<a href="#" class="d-none align-items-center text-white text-decoration-none account-mobile" id="profileIconMobile">
								<img src="{{ asset('frontend') }}/assets/image/avatar_male.webp" alt="User" class="profile-img" style="width: 40px; height: 40px; border-radius: 50%;">
									<span id="welcomeMessage" class="d-none d-md-block fs-6">
										<div>مرحبًا</div>
										<small id="userNameMobile">اسم المستخدم</small>
									</span>
								</a>
							</div>
						</div>

						<!-- Account Image Button (On Mobile After Login) -->
						<a href="#" id="profileIconDesktop" class="d-none align-items-center text-white text-decoration-none account-desktop">
						<img src="{{ asset('frontend') }}/assets/image/avatar_male.webp" alt="User" class="profile-img">
						</a>

						<!--  Account Image (Always Visible After Login) -->
						<a href="#" id="profileIconMobile" class="d-none align-items-center text-white text-decoration-none account-mobile">
						<img src="{{ asset('frontend') }}/assets/image/avatar_male.webp" alt="User" class="profile-img">
						</a>

						<!--  User modal on mobile (hidden by default) -->
						<div class="modal user-modal fade" id="mobileUserModal" tabindex="-1" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">				 
									<!-- The modal (upon login) -->
									<div class="modal-header border-0 d-flex w-100">
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										<div class="d-flex align-items-center ms-3">
											<img src="{{ asset('frontend') }}/assets/image/avatar_male.webp" alt="User" class="profile-img-modal">
											<div class="welcome-text ms-2">
												<div>مرحبًا</div>
												<small id="userNameMobile">اسم المستخدم</small>
											</div>
										</div>
									</div>
									
									<div class="modal-body text-center">
										<hr class="my-3">
										<ul class="list-unstyled text-start">
											<li><a class="dropdown-item d-flex align-items-center gap-3" href="notifications.html">
												<i class="bx bx-bell fs-5"></i> الاشعارات
											</a></li>
											<li><a class="dropdown-item d-flex align-items-center gap-3" href="orders.html">
												<i class="bx bx-package fs-5"></i> الطلبات
											</a></li>
									
											<li><a class="dropdown-item d-flex align-items-center gap-3" href="account-user-details.html">
												<i class="bx bx-user fs-5"></i> حسابي
											</a></li>
											<li><hr class="dropdown-divider"></li>
											<li><a class="dropdown-item text-danger d-flex align-items-center gap-3" href="#" id="logoutButton">
												<i class="bx bx-log-out fs-5"></i> تسجيل الخروج
											</a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>


						<!--  Logo + Menu (Visible only on mobile, in correct RTL order) -->
						<div class="col-auto d-xl-none d-flex align-items-center gap-3">
							<!-- Menu -->
							<div class="mobile-toggle-menu order-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
								<i class="bx bx-menu fs-2 text-white"></i>
							</div>
							<!-- Logo -->
							<a href="index.html" class="order-0">
								<img src="{{ asset('frontend') }}/assets/image/logo.avif" class="logo-icon" alt="Logo" />
							</a>
						</div>
			
						<!--  Logo in the center (Visible only on large screens) -->
						<div class="col text-center d-none d-xl-block">
							<a href="index.html">
								<img src="{{ asset('frontend') }}/assets/image/logo.avif" class="logo-icon" alt="Logo" />
							</a>
						</div>
			
						<!--  Language + Currency + Search (Far left on large screens) -->
						<div class="col-auto d-none d-xl-flex align-items-center gap-3">

						<!-- Search (Appears on large screens) -->
						<button class="btn btn-dark" id="search-toggle"><i class='bx bx-search'></i></button>

						<!-- Language Selection -->
						<div class="dropdown">
								<a class="btn btn-dark text-white dropdown-toggle d-flex align-items-center" href="#" role="button" id="language-dropdown" data-bs-toggle="dropdown">
									<i class="flag-icon flag-icon-us me-2" id="selected-flag"></i> <span id="selected-language">English</span>
								</a>
								<ul class="dropdown-menu" id="language-menu">
									<li><a class="dropdown-item d-flex align-items-center" href="#" data-lang="English" data-flag="us">
										<i class="flag-icon flag-icon-us me-2"></i> English
									</a></li>
									<li><a class="dropdown-item d-flex align-items-center" href="#" data-lang="العربية" data-flag="sa">
										<i class="flag-icon flag-icon-sa me-2"></i> العربية
									</a></li>
								</ul>
						</div>
			
						<!-- Currency Selection -->
						<div class="dropdown">
								<a class="btn btn-dark text-white dropdown-toggle" href="#" role="button" id="currency-dropdown" data-bs-toggle="dropdown">USD</a>
								<ul class="dropdown-menu" id="currency-menu" style="max-height: 300px; overflow-y: auto;">
									<li><a class="dropdown-item" href="#" data-currency="SAR">الريال السعودي (SAR)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="AED">الدرهم الإماراتي (AED)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="KWD">الدينار الكويتي (KWD)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="BHD">الدينار البحريني (BHD)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="OMR">الريال العماني (OMR)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="QAR">الريال القطري (QAR)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="USD">الدولار الأمريكي (USD)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="EUR">اليورو (EUR)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="GBP">الجنيه الإسترليني (GBP)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="JPY">الين الياباني (JPY)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="EGP">الجنيه المصري (EGP)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="TRY">الليرة التركية (TRY)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="CNY">اليوان الصيني (CNY)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="INR">الروبية الهندية (INR)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="RUB">الروبل الروسي (RUB)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="ZAR">الراند الجنوب أفريقي (ZAR)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="BRL">الريال البرازيلي (BRL)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="CAD">الدولار الكندي (CAD)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="AUD">الدولار الأسترالي (AUD)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="SGD">الدولار السنغافوري (SGD)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="HKD">الدولار الهونغ كونغي (HKD)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="MXN">البيزو المكسيكي (MXN)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="ARS">البيزو الأرجنتيني (ARS)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="COP">البيزو الكولومبي (COP)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="CLP">البيزو التشيلي (CLP)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="SEK">الكرونا السويدية (SEK)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="NOK">الكرونا النرويجية (NOK)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="DKK">الكرونا الدنماركية (DKK)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="CHF">الفرنك السويسري (CHF)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="PLN">الزلوتي البولندي (PLN)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="THB">البات التايلاندي (THB)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="IDR">الروبية الإندونيسية (IDR)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="KRW">الوون الكوري الجنوبي (KRW)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="MYR">الرينغيت الماليزي (MYR)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="VND">الدونغ الفيتنامي (VND)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="PHP">البيزو الفلبيني (PHP)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="PKR">الروبية الباكستانية (PKR)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="NGN">النيرة النيجيرية (NGN)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="GHS">السيدي الغاني (GHS)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="TZS">الشيلينغ التنزاني (TZS)</a></li>
									<li><a class="dropdown-item" href="#" data-currency="KES">الشيلينغ الكيني (KES)</a></li>
								</ul>
						</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- search-box -->
			<div id="search-box" class="d-none position-fixed w-100 h-100 d-flex align-items-start pt-5 justify-content-center" 
				style="background: rgba(0, 0, 0, 0.5); top: 0; left: 0; z-index: 9999;">
				<div class="bg-white p-3 rounded shadow-lg" style="width: 90%; max-width: 400px; position: relative;">
					<div class="input-group ">
						<span class="input-group-text"><i class="bx bx-search" id="search-icon"></i></span>
						<input type="text" class="form-control text-end" id="search-input" placeholder="ادخل كلمة البحث هنا">
					</div>
					<div id="search-results" class="mt-2 text-center text-dark d-none">لا توجد نتائج</div>
				</div>
			</div>

			<!--primary-menu  -->
			<div class="primary-menu ">
				<nav class="navbar navbar-expand-xl w-100 navbar-dark container mb-0 p-0">
					<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"style="width: 85%;">
						<!-- offcanvas-header -->
					  <div class="offcanvas-header">
						<button type="button" class="btn-close bg-danger " data-bs-dismiss="offcanvas" aria-label="Close"></button>

						<!-- currency-dropdown -->
						<div class="dropdown dropdown-currency">
							<a class="btn btn-dark text-white dropdown-toggle" href="#" role="button" id="currency-dropdown" data-bs-toggle="dropdown">USD</a>
							<ul class="dropdown-menu" id="currency-menu" style="max-height: 300px; overflow-y: auto;">
								<li><a class="dropdown-item" href="#" data-currency="SAR">الريال السعودي (SAR)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="AED">الدرهم الإماراتي (AED)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="KWD">الدينار الكويتي (KWD)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="BHD">الدينار البحريني (BHD)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="OMR">الريال العماني (OMR)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="QAR">الريال القطري (QAR)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="USD">الدولار الأمريكي (USD)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="EUR">اليورو (EUR)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="GBP">الجنيه الإسترليني (GBP)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="JPY">الين الياباني (JPY)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="EGP">الجنيه المصري (EGP)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="TRY">الليرة التركية (TRY)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="CNY">اليوان الصيني (CNY)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="INR">الروبية الهندية (INR)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="RUB">الروبل الروسي (RUB)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="ZAR">الراند الجنوب أفريقي (ZAR)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="BRL">الريال البرازيلي (BRL)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="CAD">الدولار الكندي (CAD)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="AUD">الدولار الأسترالي (AUD)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="SGD">الدولار السنغافوري (SGD)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="HKD">الدولار الهونغ كونغي (HKD)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="MXN">البيزو المكسيكي (MXN)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="ARS">البيزو الأرجنتيني (ARS)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="COP">البيزو الكولومبي (COP)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="CLP">البيزو التشيلي (CLP)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="SEK">الكرونا السويدية (SEK)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="NOK">الكرونا النرويجية (NOK)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="DKK">الكرونا الدنماركية (DKK)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="CHF">الفرنك السويسري (CHF)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="PLN">الزلوتي البولندي (PLN)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="THB">البات التايلاندي (THB)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="IDR">الروبية الإندونيسية (IDR)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="KRW">الوون الكوري الجنوبي (KRW)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="MYR">الرينغيت الماليزي (MYR)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="VND">الدونغ الفيتنامي (VND)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="PHP">البيزو الفلبيني (PHP)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="PKR">الروبية الباكستانية (PKR)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="NGN">النيرة النيجيرية (NGN)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="GHS">السيدي الغاني (GHS)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="TZS">الشيلينغ التنزاني (TZS)</a></li>
								<li><a class="dropdown-item" href="#" data-currency="KES">الشيلينغ الكيني (KES)</a></li>
							</ul>
						</div>
						<!--  language-dropdown-->
						<div class="dropdown">
								<a class="btn btn-dark text-white dropdown-toggle d-flex align-items-center" href="#" role="button" id="language-dropdown" data-bs-toggle="dropdown">
									<i class="flag-icon flag-icon-us me-2" id="selected-flag"></i> <span id="selected-language">English</span>
								</a>
								<ul class="dropdown-menu" id="language-menu">
									<li><a class="dropdown-item d-flex align-items-center" href="#" data-lang="English" data-flag="us">
										<i class="flag-icon flag-icon-us me-2"></i> English
									</a></li>
									<li><a class="dropdown-item d-flex align-items-center" href="#" data-lang="العربية" data-flag="sa">
										<i class="flag-icon flag-icon-sa me-2"></i> العربية
									</a></li>
								</ul>
						</div>
						<!-- <div class="offcanvas-logo"><img src="{{ asset('frontend') }}/assets/image/logo.avif" width="50" alt="">
						</div> -->
					  </div>

					  <!--  offcanvas-body-->
					  <div class="offcanvas-body primary-menu  ">
						<ul class="navbar-nav justify-content-center flex-grow-1 gap-1 my-2 ">
							<li class="nav-item d-flex align-items-center flex-row-reverse ">
								<img src="{{ asset('frontend') }}/assets/image/3.webp" alt="زيادة لايكات" class="d-md-none rounded-circle me-2" width="30" height="30">
								<a class="nav-link  " href="increase-likes.html">زيادة لايكات</a>
							</li>
							<li class="nav-item d-flex align-items-center flex-row-reverse">
								<img src="{{ asset('frontend') }}/assets/image/3.webp" alt="زيادة مشاهدات" class="d-md-none rounded-circle me-2" width="30" height="30">
								<a class="nav-link" href="increase-views.html">زيادة مشاهدات</a>
							</li>
							<li class="nav-item d-flex align-items-center flex-row-reverse">
								<img src="{{ asset('frontend') }}/assets/image/3.webp" alt="كرانشي رول" class="d-md-none rounded-circle me-2" width="30" height="30">
								<a class="nav-link" href="crunchy-roll.html">كرانشي رول</a>
							</li>
							<li class="nav-item d-flex align-items-center flex-row-reverse">
								<img src="{{ asset('frontend') }}/assets/image/3.webp" alt="watch it" class="d-md-none rounded-circle me-2" width="30" height="30">
								<a class="nav-link" href="watch-it.html">watch it</a>
							</li>
							<li class="nav-item d-flex align-items-center flex-row-reverse">
								<img src="{{ asset('frontend') }}/assets/image/3.webp" alt="amazon prime video" class="d-md-none rounded-circle me-2" width="30" height="30">
								<a class="nav-link" href="amazon-prime-video.html">amazon prime video</a>
							</li>
							<li class="nav-item d-flex align-items-center flex-row-reverse">
								<img src="{{ asset('frontend') }}/assets/image/3.webp" alt="osn plus" class="d-md-none rounded-circle me-2" width="30" height="30">
								<a class="nav-link" href="osn-plus.html">osn plus</a>
							</li>
							<li class="nav-item d-flex align-items-center flex-row-reverse">
								<img src="{{ asset('frontend') }}/assets/image/3.webp" alt="شاهد vip" class="d-md-none rounded-circle me-2" width="30" height="30">
								<a class="nav-link" href="watch-vip.html">شاهد vip</a>
							</li>
							<li class="nav-item d-flex align-items-center flex-row-reverse">
								<img src="{{ asset('frontend') }}/assets/image/3.webp" alt="FLIX" class="d-md-none rounded-circle me-2" width="30" height="30">
								<a class="nav-link" href="flix.html">FLIX</a>
							</li>
							<li class="nav-item d-flex align-items-center flex-row-reverse">
								<img src="{{ asset('frontend') }}/assets/image/3.webp" alt="زيادة متابعين" class="d-md-none rounded-circle me-2" width="30" height="30">
								<a class="nav-link" href="increase-followers.html">زيادة متابعين</a>
							</li>
						</ul>
					</div>
					</div>	
				  </nav>
			</div>
		</div>
		<!--end top header wrapper-->


        <!-- Login  Start  -->
        
        <div class="login-card m-auto my-5">
            <i class="bx bx-user-circle text-primary" style="font-size: 80px;"></i>
            <h5 class="mb-3 mt-2">تسجيل الدخول</h5>
            <p class="text-dark mb-4">أدخل رقم هاتفك للمتابعة</p>

            <div class="mb-3 d-flex">
                <input type="tel" class="form-control text-center flex-grow-1" id="phoneInput" placeholder="أدخل رقم هاتفك" required>
                <div class="dropdown me-2">
                    <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="countryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="selectedFlag">🇪🇬</span>
                        <span id="selectedCode">+20</span>
                    </button>
                    <ul class="dropdown-menu country-list" aria-labelledby="countryDropdown">
                        <!-- Country List -->
                        <li><a class="dropdown-item" href="#" data-code="+966" data-flag="🇸🇦">🇸🇦 السعودية (+966)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+971" data-flag="🇦🇪">🇦🇪 الإمارات (+971)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+965" data-flag="🇰🇼">🇰🇼 الكويت (+965)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+973" data-flag="🇧🇭">🇧🇭 البحرين (+973)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+968" data-flag="🇴🇲">🇴🇲 عمان (+968)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+974" data-flag="🇶🇦">🇶🇦 قطر (+974)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+1" data-flag="🇺🇸">🇺🇸 أمريكا (+1)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+20" data-flag="🇪🇬">🇪🇬 مصر (+20)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+44" data-flag="🇬🇧">🇬🇧 المملكة المتحدة (+44)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+33" data-flag="🇫🇷">🇫🇷 فرنسا (+33)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+91" data-flag="🇮🇳">🇮🇳 الهند (+91)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+49" data-flag="🇩🇪">🇩🇪 ألمانيا (+49)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+61" data-flag="🇦🇺">🇦🇺 أستراليا (+61)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+34" data-flag="🇪🇸">🇪🇸 إسبانيا (+34)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+39" data-flag="🇮🇹">🇮🇹 إيطاليا (+39)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+81" data-flag="🇯🇵">🇯🇵 اليابان (+81)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+55" data-flag="🇧🇷">🇧🇷 البرازيل (+55)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+7" data-flag="🇷🇺">🇷🇺 روسيا (+7)</a></li>
                        <li><a class="dropdown-item" href="#" data-code="+82" data-flag="🇰🇷">🇰🇷 كوريا الجنوبية (+82)</a></li>
                    </ul>
                </div>
            </div>

            <button class="btn btn-primary w-100" id="loginButton">متابعة</button>
        </div>
        <!--  Login Moal End  -->


		<!--start about section-->
		<section class="p-4 bg-footer about">
			<div class="container m-auto text-center">
					<div class=" text-white">
						<img src="{{ asset('frontend') }}/assets/image/logo.avif" class="logo-icon mb-3" alt="Logo" />
					<h2 class=" text-white pb-3">من نحن</h2>
					<p class="text-right">  في متجر فليكس نوفرلك البطاقات والاشتراكات والخدمات الرقمية بأعلي جودة ممكنه وبأقل سعر ممكن لان رضاك يهمنا 💙	</p>
					<h2 class=" text-white mt-4"> تابعنا على</h2>
					<div class="icons-about d-flex justify-content-center align-items-center">
						<div class="icon-tiktok m-3">
							<a href="https://www.tiktok.com/@mtgrflix"><i class="fab fa-tiktok"></i> </a>
						</div>
						<div class="icon-tiktok">
							<a href=" https://www.instagram.com/mtgrflixx/#">
								<i class="fab fa-instagram"></i>
							</a>
						</div>
					</div>
					</div>
			</div>
		</section>
		<!--end about section-->

		<!--start footer section-->
		<footer class="foter-ar  bg-footer">
			<img src="{{ asset('frontend') }}/assets/image/line.png" class="w-100 bg-dark" alt="">

			<!-- Top Footer -->
			<section class="py-5  ">
				<div class="container text-white">
					<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 ">
						<div class="col">
							<div class="footer-section3">
								<h5 class="mb-2  fw-bold text-white"> خدمة العملاء </h5>
								<div class="tags-box d-flex flex-wrap gap-1 ">
									<div class="icons-about d-flex justify-content-end align-items-center">
										<div class="m-2">
											<a href="https://www.tiktok.com/@mtgrflix"><i class="fab fa-whatsapp fa-2x "></i> </a>
										</div>
										<div class="pt-3 ">
											<p class="text-white"><a href="https://wa.me/966551200896" class="text-white">966551200896+</a></p>
										</div>
									</div>
								</div>
							
								<div class="tags-box d-flex flex-wrap gap-1 mt-3">
									
									<div class="icons-about d-flex justify-content-center align-items-center">
										<div class=" m-2">
											<a href="https://www.tiktok.com/@mtgrflix"><i class="fas fa-envelope fa-2x"></i> </a>
										</div>
										<div class="pt-3">
											<p class="text-white"><a href="mailto:mtgrflix@gmail.com" class="text-white">mtgrflix@gmail.com</a></p>
										</div>
									</div>
								</div>
							</div>
						</div>
				
						<div class="col">
							<div class="footer-section2">
								<h5 class="mb-4  fw-bold text-white">روابط مهمة</h5>
								<p><a href="replacement-policy.html"class="text-white"><i class='bx bx-chevrons-left text-primary'></i>سياسة الاستبدال والإسترجاع</a></p>
								<p><a href="privacy-policy.html"class="text-white"><i class='bx bx-chevrons-left  text-primary'></i>سياسة الخصوصية </a></p>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Bootom Footer -->
			<section class="footer-strip  bg-footer text-center py-3   top-footer positon-absolute bottom-0">
				<div class="container">
					<div class="d-flex flex-row align-items-center justify-content-center gap-2 mb-4">
						<a href="https://e.business.sa/new-launch-msg">
							<img src="{{ asset('frontend') }}/assets/image/busnines.png" class=" rounded" alt="موثوق في منصة الأعمال" style="width: 40px;">
						</a>
						<hp class="text-white mt-">موثوق في منصة الأعمال</hp>
					</div>
					
					<div class="d-flex flex-column flex-lg-row align-items-center gap-3 justify-content-between">
						<p class="mb-0">الحقوق محفوظة | 2025 متجر فليكس.</p>
						<div class="payment-icon">
							<div class="row row-cols-auto g-2 justify-content-end">
								<div class="col">
									<img src="{{ asset('frontend') }}/assets/image/New folder/1.png" class="img-fluid" alt="" />
								</div>
								<div class="col">
									<img src="{{ asset('frontend') }}/assets/image/New folder/2.png" class="img-fluid" alt="" />
								</div>
								<div class="col">
									<img src="{{ asset('frontend') }}/assets/image/New folder/3.png" class="img-fluid" alt="" />
								</div>
								<div class="col">
									<img src="{{ asset('frontend') }}/assets/image/New folder/4.png" class="img-fluid" alt="" />
								</div>
								<div class="col">
									<img src="{{ asset('frontend') }}/assets/image/New folder/6.png" class="img-fluid" alt="" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</footer>
		<!--end footer section-->

		<!--Start Back To Top Button--> 
		<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
	</div>
	<!--end wrapper-->
	
	<!-- Bootstrap JS -->
	<!-- jQuery -->
	<script src="{{ asset('frontend') }}/assets/js/jquery.min.js"></script>
	<script src="{{ asset('frontend') }}/assets/plugins/OwlCarousel/js/owl.carousel.min.js"></script>
	<script src="{{ asset('frontend') }}/assets/plugins/OwlCarousel/js/owl.carousel2.thumbs.min.js"></script>
	<script src="{{ asset('frontend') }}/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="{{ asset('frontend') }}/assets/plugins/purecounter/purecounter_vanilla.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>

	<!-- Bootstrap -->
	<script src="{{ asset('frontend') }}/assets/js/bootstrap.bundle.min.js"></script>

	<!-- js -->
	<script src="{{ asset('frontend') }}/assets/js/app.js"></script>
	<script src="{{ asset('frontend') }}/assets/js/index.js"></script>

</body>
</html>