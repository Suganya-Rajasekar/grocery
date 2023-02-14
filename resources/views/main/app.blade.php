<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-N2QNG6Q');</script>
	<!-- End Google Tag Manager -->
	<meta charset="UTF-8">
	<title>{!! config('app.name') !!}</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link rel="shortcut icon" type="image/x-icon" href="{!! asset('assets/front/img/favicon.ico') !!}" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- Chrome, Firefox OS, Opera -->
	<meta name="theme-color" content="#f65a60">
	<!-- Windows Phone -->
	<meta name="msapplication-navbutton-color" content="#f65a60">
	<!-- iOS Safari -->
	<meta name="apple-mobile-web-app-status-bar-style" content="#f65a60">
	<meta name="apple-mobile-web-app-capable" content="yes">

	<meta property="og:title" content="{!! CNF_APPNAME !!}" />
	<meta property="og:description" content="{!! CNF_APPDESC !!}" />
	<meta property="og:url" content="{!! \URL::to('') !!}" />
	<meta property="og:image" content="{!! asset('assets/front/img/ogimage.png') !!}" />
	<meta name="facebook-domain-verification" content="1zb174c5m7f3rfyaf027vb7m88j1j2" />
	{{-- <meta name="google-signin-client_id" content="{!! CNF_GOOGLE_Client_ID !!}"> --}}


	{{-- <link rel="manifest" href="{{ \URL::to('public/manifest.json') }}" /> --}}
	<link rel="apple-touch-icon" href="{!! asset('assets/front/img/favicon.ico') !!}">
	<link rel="manifest" href="{{ asset('/manifest.json') }}">
	<link rel="mask-icon" href="{!! asset('assets/front/img/favicon.ico') !!}" color="#008046">
	<meta name="apple-mobile-web-app-title" content="{!! config('app.name') !!}">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.10/clipboard.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
	<!-- <link href="{!! asset('assets/front/css/jquery.multiselect.css') !!}" rel="stylesheet"/> -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css">
	<link href="{!! asset('assets/front/css/style.css') !!}" rel="stylesheet"/>
	<link href="{!! asset('assets/front/css/default.css') !!}" rel="stylesheet"/>
	<link href="{!! asset('assets/front/css/detail.css') !!}" rel="stylesheet"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
	<link href="{!! asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') !!}" rel="stylesheet"/>
	<link href="{!! asset('assets/plugins/toastr/toastr.min.css') !!}" rel="stylesheet"/>
	<link href="{!! asset('assets/plugins/lightbox/css/lightbox.css') !!}" rel="stylesheet"/>
	<script type="text/javascript">
		var base_url    = "<?php echo URL::to('/').'/'; ?>";
		var baseurl     = "<?php echo URL::to('/'); ?>";
	</script>
	@yield('css')
	<style>
		/* Prelaoder */
		#preloader {
			position: fixed;
			left: 0;
			top: 0;
			right: 0;
			bottom: 0;
			z-index: 99999;
			width: 100%;
			height: 100%;
			overflow: visible;
			/*background: #fff url('{{asset('/assets/img/preloader.gif')}}')  no-repeat center center;*/
		}
		.cart_overlay {
			background: #fff url('{{asset('/assets/img/loader.gif')}}') no-repeat center center;
			display: none;
			height: 200px;
			position: fixed;
			width: 100%;
			opacity: 0.5;
			left: 0;
			top: 0;
			bottom: 0px;
			height: 100%;
			z-index: 9999;
		}
		.ajaxLoading {
			background: #fff url('<?= \URL::To('loading.gif'); ?>') no-repeat center center;
			display: none;
			height: 200px;
			position: fixed;
			width: 100%;
			opacity: 0.5;
			left: 0;
			top: 0;
			bottom: 0px;
			height: 100%;
			z-index: 9999;
		}
	</style>
</head>
<body>
	<div class="ajaxLoading" id="ajaxLoading"></div>
	<div class="overlay" id="overlay"></div>
	{{-- <div id="preloader" ></div> --}}
	<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
	<div id="overlayer">
		<div class="preloader" style=" position: fixed;width: 100%;height: 100%;top: 0;left: 0;z-index: 100000;backface-visibility: hidden;background: #ffffff;display: flex;align-items: center;justify-content: center;">
			<div class="lottie-parent" style="top: 31%;">
				<lottie-player src="{{ \URL::to('spec.json') }}"  background="transparent"  speed="1"  style="margin: auto;width: 80%;height: 100%;text-align: center;"  loop autoplay>
				</lottie-player>
			</div>
		</div>
	</div>
	{{-- @include('main.mobileappinfo') --}}
	@if(!isset($source) || ( isset($source) && $source != 'api' ) )
		@include('main.header')
	@endif
	<div class="error-message-area">
		<div class="error-content">
			<h4 class="error-msg"></h4>
		</div>
	</div>
	@yield('content')    
	@if(( isset($source) && $source != 'api' ))
		@include('main.footer')
	@endif
	<script src="{!! asset('assets/plugins/jquery/jquery-3.2.1.min.js') !!}" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg==" crossorigin="anonymous"></script>
	<script defer src="{!! asset('assets/plugins/bootstrap/js/bootstrap.bundle.js') !!}" type="text/javascript"></script>
	<script defer src="{!! asset('assets/plugins/lightbox/js/lightbox.min.js') !!}"></script>
	<script type="text/javascript">
		var csrf_token               = '{{ csrf_token() }}';
		var form_original_data       = $("#vendor_coupon_form").serialize(); 
		var subsc_form_original_data = $("#user_subscription_form").serialize(); 
		var redirect                 = '{{ isset($_REQUEST["redirect"]) ? $_REQUEST["redirect"] : "dashboard" }}';
	</script>
	<!-- <script src="{!! asset('assets/front/js/jquery.multiselect.js') !!}" id="appjs"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
	<script defer src="{!! asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') !!}"></script>
	<script defer src="{!! asset('assets/plugins/toastr/toastr.min.js') !!}"></script>
	<script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
	<script defer src="{!! asset('assets/front/js/plugins.js') !!}" id="appjs"></script>
	<script defer src="{!! asset('assets/front/js/main.js') !!}" id="appjs"></script>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places,geometry&key=AIzaSyAUqxCzqXHg1jeS_RUd4p4ukmVrcXckxYA"></script>
	<script>
	</script>
	@yield('script')
	@include('layouts.modal')
	@include('flash::message')
	@if(Session::has('error'))
	@php
	$x  = Session::get('error');
	@endphp
	<script type="text/javascript">
		$(document).ready(function() {
			// $(".error-message-area").css("display","block");
			// $(".error-content").css("background","#ED4956");
			// $(".error-message-area").find('.error-msg').text("{{ $x }}");
			setTimeout(function(){toast("{{ $x }}", 'Error!', 'error'); /*$(".error-message-area").hide();*/ }, 4000);
		});
	</script>
	{{ Session::forget('error') }} 
	@endif
	<script type="text/javascript">
		var lat = 28.4594965;
		var lang= 77.0266383;
		window.onload = function exampleFunction() {
			$(".preloader").delay(2000).fadeOut("slow");
			$("#overlayer").delay(2000).fadeOut("slow");
		}
		{{-- @if(\Request::is('knosh-world')) --}}
		$(document).ready(function() {
			// setsessionlatlang();

			var logeyecount = 0;
			var logconfeye = 0;
			$('.login-eye i').click(function() {
				logeyecount++;
				if (logeyecount % 2 == 0) {
					$('.login-psw-asw input').attr('type', 'password');
					$('.login-psw-asw input~div i').removeClass("fa-eye-slash")
					$('.login-psw-asw input~div i').addClass("fa-eye")
				} else {
					$('.login-psw-asw input').attr('type', 'text');
					$('.login-psw-asw input~div i').addClass("fa-eye-slash")
					$('.login-psw-asw input~div i').removeClass("fa-eye")
				}
			})
			$('.login-conf-eye i').click(function() {
				logconfeye++;
				if (logconfeye % 2 == 0) {
					$('.login-confpsw-asw input').attr('type', 'password');
					$('.login-confpsw-asw input~div i').removeClass("fa-eye-slash")
					$('.login-confpsw-asw input~div i').addClass("fa-eye")
				} else {
					$('.login-confpsw-asw input').attr('type', 'text');
					$('.login-confpsw-asw input~div i').addClass("fa-eye-slash")
					$('.login-confpsw-asw input~div i').removeClass("fa-eye")
				}
			})
		});
		$(document).ready(function() {
			@if( \Request::segment(1) == 'user')
			$("#user_settings_form").submit(function(e){
				var $form = $(this);
				var url = baseurl+"/user/dashboard/profile/update"; 
				var form = document.getElementById("user_settings_form")[0];
				var data = new FormData(this);
				$("#upd_profile").prop('disabled',true); 
				//$("#user_settings_form").serialize();
				if ($form.valid()) {
				$.ajax({
					url : url,
					data : data,
					dataType : 'json',
					mimeType: 'multipart/form-data',
					type : 'post',
					async: false,
					contentType: false,
					cache: false,
					processData: false,
					success : function(res){

						// $(".error-message-area").css("display","block");
						// $(".error-content").css("background","#9cda9c");
						// $(".error-msg").html("Customer profile updated successfully!");  
						toast('Customer profile updated successfully!','Success!','success');  
						setTimeout(function(){location.reload()}, 2000);
					},
					error : function(err){ 
						$("#upd_profile").prop('disabled',false);
						var msg = err.responseJSON.message; 
						// console.log(msg);
						$(".error-content").css("background","#ED4956");
						$(".error-message-area").find('.error-msg').text(msg);
						$(".error-message-area").show();
						if(resend){
							$(".ndiv").show();
							$(".odiv").hide();
						}
					}
				});
				event.preventDefault();}
			});

			$("#user_pass_form").submit(function(){
				var url = baseurl+"/user/dashboard/password/update"; 
				var form = document.getElementById("user_pass_form")[0];
				var data = new FormData(this);
				$.ajax({
					url : url,
					data : data,
					dataType : 'json',
					mimeType: 'multipart/form-data',
					type : 'post',
					async: false,
					contentType: false,
					cache: false,
					processData: false,
					success : function(res){
						// console.log(res);
						// $(".error-message-area").css("display","block");
						// $(".error-content").css("background","#9cda9c");
						// $(".error-msg").html("Customer profile updated successfully!");
						toast('Customer profile updated successfully!','Success!','success');  
						setTimeout(function(){location.reload()}, 2000);
					},
					error : function(err){ 
						// console.log(err);
						 // console.log(err.message);
						$(".reg-btn").prop('disabled',false);
						var msg = err.responseJSON.message; 
						// $(".error-content").css("background","#ED4956");
						$(".error-message-area").find('.error-msg').text(msg);
						//$(".error-message-area").show();
						toast(msg, 'Error!', 'error');
					}
				});
				event.preventDefault();
			});

			@endif
		});
	</script>
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
	<link defer rel="stylesheet" type="text/css" href="{{asset("assets/front/css/polygon.css")}}">
	<!--Start of Tawk.to Script-->
	<script type="text/javascript">
		var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
		(function(){
			var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
			s1.async=true;
			s1.src='https://embed.tawk.to/629ee5967b967b1179933d6b/1g4ub1lui';
			s1.charset='UTF-8';
			s1.setAttribute('crossorigin','*');
			s0.parentNode.insertBefore(s1,s0);
		})();
	</script>
	<!--End of Tawk.to Script-->
</body>
</html>