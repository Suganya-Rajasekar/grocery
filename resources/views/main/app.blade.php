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
	@php 
	$offers  =  app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@AvailableOffers');
	@endphp
	@include('frontend.coupons',[$offers])     
	@if(\Request::segment(1) != 'checkout' && \Request::segment(1) != 'chef' && (!isset($source) || ( isset($source) && $source != 'api' )))
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
			setsessionlatlang();
			//homePage();
			$('.showBlocklatlang').show();
			$('.owl-carousel-top_rated').owlCarousel({
					loop:true,
					margin:10,
					autoplay:10000,
					autoplaySpeed:2000,
					center: true,   
					dots: false,
					responsive:{    
						0:{
							items:1,
						},
						400:{
							items:1,
						},
						767:{
							items:2,
						},
						1000:{
							items:3,
						},
						1400:{
							items: 3,
						}
					}
			});
			if ('scrollRestoration' in history) {
				history.scrollRestoration = 'manual';
			}
		});
		
		var working = false;
		$(document).on('scroll',function(){
			if(working == false) {
				working = true;
				var scroll = $(this).scrollTop();
				if(scroll >= 1) {
					var celebpage = $('#celebchef').val();
					if(celebpage <= 3) {
						var seemore   		= 'celebrityChefs';
						var page 	  		= celebpage;
						var nextpage  		= Number(page) + Number(1);
						var pagecount_id 	= '#celebchef';
						var data_append_id  = '#celebrity';
					} else if(celebpage >= 3) {
						var poppage = $('#popchef').val();
						if(poppage <= 3) {
							var seemore 		= 'nearByChefs';
							var page 			= poppage;	
							var nextpage  		= Number(page) + Number(1);
							var pagecount_id 	= '#popchef';
							var data_append_id  = '#popular';
						} else if(poppage >= 3) {
							var homeevent = $('#home_event').val();
							if(homeevent <= 3) {
								var seemore 		= 'homeevent';
								var page 			= homeevent;	
								var nextpage  		= Number(page) + Number(1);
								var pagecount_id 	= '#home_event';
								var data_append_id  = '#homeevent';	
							} else if(homeevent >= 3) {
								var eventpage = $('#chef_event').val();
								if(eventpage <= 3) {
									var seemore         = 'chefevent';
									var page 			= eventpage;	
									var nextpage  		= Number(page) + Number(1);
									var pagecount_id 	= '#chef_event';
									var data_append_id  = '#chefevent';
								} else if(eventpage >= 3){
									var blockpage = $('#foodblog').val();
									if(blockpage == 1) {	
										videoblogscript();
										var seemore 		= '';
										var page 			= blockpage;	
										var nextpage  		= Number(4);
										var pagecount_id 	= '#foodblog';
										var data_append_id  = '#foodblogs';
										$('#foodblog').val(nextpage)
									} else if(blockpage == 4) {
										$('#mediapress').show();
									}
								} 
							}
							
						}
					}
					if(page <= 3) {
						dataloader(seemore,page,pagecount_id,data_append_id,nextpage);
					}
				}
				setTimeout(function(){ working = false;},2500); 
			}
		});

		function dataloader(seemore,page,pagecount_id,data_append_id,nextpage) {
			$('.scroll-loader').show();	
			$.ajax({
				url : base_url /*+ 'knosh-world'*/,
				type : 'GET',
				data : {seemore : seemore , call : 'onscroll' , pageNumber : page },
				success:function(res){
						if(page == 1 && res.totaldata != 0) {
							$(data_append_id).show();
						}			
						$(data_append_id).append(res.html);
						$('#near_by_chef_content').show();
						$('.scroll-loader').hide();
						console.log(res.lastpage);
						if(res.lastpage == page || res.totaldata == 0) {
							nextpage  = 4;
							$('.scroll-loader').hide();
						}
						$(pagecount_id).val(nextpage);
						$('.celebrity-chef').owlCarousel({
							loop:false,
							dots: false,
							nav: false,
							margin:10,
							merge:true,
							responsive:{
								0:{
									items:6
								},
								600:{
									items:7
								},
								850:{
									items:9
								},
								1400:{
									items:11
								}
							}
						});
						$('.owl-recipes, .owl-food-blog').owlCarousel({
							loop:true,
							margin:10,
							nav:true,
							dots:false,
							autoplay: true,
							autoHeight: true,
							autoplaySpeed: 3000,
							autoplayTimeout:10000,
							responsive:{
								0:{
									items:1
								},
								500:{
									items:2
								},
								991:{
									items:3
								},
								1300:{
									items:4
								}
							}
						});
						var whatstrend = $('.owl-whats-trending').owlCarousel({
							loop: true,
						// items: 3,
						margin: 0,
						center: true,
						nav: true,
						video:true,
						// startPosition: 1,
						responsive:{
							0:{
								items:1
							},
							501:{
								items:3
							},
							850:{
								items:3
							},
							1400:{
								items:5
							}
						},
						onTranslate: function(event) {

							var currentSlide, player, command, localplayer;

							currentSlide = $('.owl-item.active.center');

							player = currentSlide.find(".youtube-player iframe").get(0);

							command = {
								"event": "command",
								"func": "pauseVideo"
							};


							if (player != undefined) {
								player.contentWindow.postMessage(JSON.stringify(command), "*");

							}

							localplayer = currentSlide.find("video");
							$(localplayer).trigger('pause');
						}
					  });
					}			
			})
		}
		{{-- @endif --}}
		LocationInput();
		function LocationInput() {
			var to_places = new google.maps.places.Autocomplete(document.getElementById('location_input'));
			var ready_to_places = new google.maps.places.Autocomplete(document.getElementById('ready-location_input'));
			var place = to_places.getPlace();

			google.maps.event.addListener(to_places, 'place_changed', function () {
				var to_place = to_places.getPlace(); 
				var to_address = to_place.formatted_address;
				$('#location_input').val(to_address);
				$('#ready-location_input').val(to_address);
				$('#district').val(to_places.getPlace().address_components[2]['long_name'])
				localStorage.setItem('location',to_address);
				localStorage.setItem('district',to_places.getPlace().address_components[2]['long_name']);
				var place = to_places.getPlace();

				localStorage.setItem('lat',place.geometry.location.lat());
				localStorage.setItem('lang',place.geometry.location.lng());

				$('#a_lat,#lat').val(place.geometry.location.lat());
				$('#a_lang,#long').val(place.geometry.location.lng());
				$('#a_addr').val(place.formatted_address);
				 /*initialize();
				 address_check();*/

				var geocoder = new google.maps.Geocoder();
				var latlng = {lat: parseFloat(place.geometry.location.lat()), lng:
				parseFloat(place.geometry.location.lng())};
				homePage();
				// console.log(localStorage.getItem("lat"));
				setsessionlatlang();
				$('.searchpath').attr('href',baseurl+"/search/?lat="+latlng.lat+"&lang="+latlng.lng);
				// setcity(latlng);
			});

			google.maps.event.addListener(ready_to_places, 'place_changed', function () {
				var to_place = ready_to_places.getPlace(); 
				var to_address = to_place.formatted_address;
				$('#location_input').val(to_address);
				$('#ready-location_input').val(to_address);
				$('#district').val(ready_to_places.getPlace().address_components[2]['long_name'])
				localStorage.setItem('location',to_address);
				localStorage.setItem('district',ready_to_places.getPlace().address_components[2]['long_name']);
				var place = ready_to_places.getPlace();

				localStorage.setItem('lat',place.geometry.location.lat());
				localStorage.setItem('long',place.geometry.location.lng());

				$('#lat').val(place.geometry.location.lat());
				$('#long').val(place.geometry.location.lng());
			   

				var geocoder = new google.maps.Geocoder();
				var latlng = {lat: parseFloat(place.geometry.location.lat()), lng: 
				parseFloat(place.geometry.location.lng())};
				// setcity(latlng);
			});
		}

		$("#location_input,#button_title").on('change',function(){
			//homePage();
			//setsessionlatlang();
		});

		@if( Request::is('/') ||  Request::is('/knosh-world') ||\Request::segment(1) == 'checkout' || \Request::segment(1) == 'seeMore' || \Request::segment(1) == 'explore' || \Request::segment(1) == 'chefoffer' || \Request::segment(1) == 'search') 
		// get lat, lang to show celebrity chefs
		$(document).ready(function() {
			if (navigator.geolocation) {
				if(localStorage.getItem("lat")==null && localStorage.getItem("lang")==null) { /*getCurrentLocation();*/ }
			}
		})
		@endif
		function getCurrentLocation(){
			navigator.geolocation.getCurrentPosition(function(position) {
				if(position.coords.latitude != null){
					$("#hid_lat").val(position.coords.latitude); 
					$("#hid_lang").val(position.coords.longitude);
					localStorage.setItem("lat", position.coords.latitude);
					localStorage.setItem("lang", position.coords.longitude);
					setCurrentPosition(position);
					//setsessionlatlang();
				}
				data = homePage();
			});
		}

		$(document).on("click","#locationIcon, #readyLocationIcon",function(){
			if (navigator.geolocation) {
				getCurrentLocation();
			}
		})

		//It prints current location full address in searchbar.
		function setCurrentPosition(pos) {
			var geocoder = new google.maps.Geocoder();
			var latlng = {lat: parseFloat(pos.coords.latitude), lng: parseFloat(pos.coords.longitude)};
			localStorage.setItem('lat',latlng.lat);
			localStorage.setItem('long',latlng.lng);
			geocoder.geocode({ 'location' :latlng  }, function (responses) {
				if (responses && responses.length > 0) {
					$('#location_input').val(responses[0].formatted_address);
					$('#ready-location_input').val(responses[0].formatted_address);
					$('#district').val(responses[1].address_components[3]['long_name']);
					localStorage.setItem('location',responses[0].formatted_address);
					localStorage.setItem('district',responses[1].address_components[3]['long_name']);
					$('#lat').val(latlng.lat);
					$('#long').val(latlng.lng);
					setsessionlatlang();
					$('.searchpath').attr('href',baseurl+"/search/?lat="+latlng.lat+"&lang="+latlng.lng);
					// setcity(latlng);
					// data    = homePage();
				} else {
					// alert("Cannot determine address at this location.")
				}
			});
		}

		function homePage() {
			if(localStorage.getItem("lat") != null)
				lat = localStorage.getItem("lat");
			if(localStorage.getItem("lang") != null)
				lang = localStorage.getItem("lang");

			var result = '';
			return $.ajax({
				url : baseurl+"/?latitude="+lat+"&longitude="+lang+"&mode=ajax",
				mimeType: 'multipart/form-data',
				type : 'get',
				async: true,
				processData: true,
				contentType: 'application/x-www-form-urlencoded',
			}).done(function(data, textStatus, jqXHR){
				// if(lat != 0 && lang != 0) {
				$('.showBlocklatlang').css("display","block");
				// $("#near_by_chef_content").html(jqXHR.responseText);
				$('.owl-carousel-sponsored').owlCarousel({
					loop:true,
					margin:10,
					autoplay:true,
					autoplaySpeed:2000,
					dots: false,
					responsive:{
						0:{
							items:3,
						},
						575:{
							items:3,
						},
						800:{
							items:5,
						},
						991:{
							items:7,
						},
						1400:{
							items:7,
						}
					}
				});
				$('.owl-carousel-top_rated').owlCarousel({
					loop:true,
					margin:10,
					autoplay:10000,
					autoplaySpeed:2000,
					center: true,   
					dots: false,
					responsive:{    
						0:{
							items:1,
						},
						400:{
							items:1,
						},
						767:{
							items:2,
						},
						1000:{
							items:3,
						},
						1400:{
							items: 3,
						}
					}
				});
				$('.owl-nearbychef').owlCarousel({
					loop:true,
					margin:10,
					nav:true,
					dots:false,
					// mouseDrag: false,
					// touchDrag: false,
					// animateOut: 'fadeOut',
					// animateIn: 'fadeIn',
					autoplay: true,
					// autoHeight: true,
					autoplaySpeed: 3000,
					autoplayTimeout:10000,
					responsive:{
						0:{
							items:1
						},
						600:{
							items:1
						},
						850:{
							items:1
						},
						1400:{
							items:1
						}
					}
				})
				$('.owl-celebrity').owlCarousel({
					loop:true,
					margin:10,
					nav:true,
					dots:false,
					// mouseDrag: false,
					// touchDrag: false,
					// animateOut: 'fadeOut',
					// animateIn: 'fadeIn',
					autoplay: true,
					// autoHeight: true,
					autoplaySpeed: 3500,
					autoplayTimeout:10000,
					responsive:{
						0:{
							items:1
						},
						600:{
							items:1
						},
						850:{
							items:1
						},
						1400:{
							items:1
						}
					}
				})
				$('.celebrity-chef').owlCarousel({
					loop:false,
					dots: false,
					nav: false,
					margin:10,
					merge:true,
					responsive:{
						0:{
							items:6
						},
						600:{
							items:7
						},
						850:{
							items:9
						},
						1400:{
							items:11
						}
					}
				})
				$('.see-more').owlCarousel({
					loop:false,
					margin:10,
					nav:false,
					dots:false,
					responsive:{
						0:{
							items:1
						},
						600:{
							items:2
						},
						850:{
							items:3
						},
						1400:{
							items:5
						}
					}
				});
				$('.popular-new-width').owlCarousel({
					loop:true,
					margin:10,
					nav:false,
					dots:false,
					autoplay:true,
					responsive:{
						0:{
							items:1
						},
						600:{
							items:2
						},
						850:{
							items:3
						},
						1400:{
							items:4
						}
					}
				})
				// }
				// else {
				//     $("#near_by_chef_content").html(''); 
				// }
			});
		}

		$('.owl-bookmark,  .owl-fav').owlCarousel({
			loop:false,
			margin:10,
			nav:false,
			dots:false,
			responsive:{
				0:{
					items:1
				},
				600:{
					items:2
				},
				850:{
					items:3
				},
				1400:{
					items:4
				}
			}
		});
		$('.popup_imgcarousel').owlCarousel({
			loop:true,
			margin:10,
			nav:true,
			dots:false,
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
		});
		$('.owl-cuisine-old').owlCarousel({
			loop:false,
			margin:10,
			nav:true,
			dots:false,
			responsive:{
				0:{
					items:3
				},
				600:{
					items:4
				},
				850:{
					items:6
				},
				1400:{
					items:8
				}
			}
		})
		$('.owl-chefreg1').owlCarousel({
			loop:true,
			margin:10,
			nav:false,
			dots:false,
			responsive:{
				0:{
					items:1
				},
				575:{
					items:2
				},
				850:{
					items:3
				},
				1100:{
					items:4
				},
				1450:{
					items:5
				}
			}
		})
		$('.owl-chefreg').owlCarousel({
			loop:true,
			margin:10,
			nav:false,
			autoplay: true,
			autoplayTimeout: 8000,
			animateOut: "fadeOut",
			animateIn: "fadeIn",
			dots:true,
			// touchDrag:false,
			// mouseDrag:false,
			responsive:{
				0:{
					items:1
				},
				600:{
					items:1
				},
				850:{
					items:1
				},
				1400:{
					items:1,
				}
			}
		})
		/*$('.owl-recipes, .owl-food-blog').owlCarousel({
			loop:true,
			margin:10,
			nav:true,
			dots:false,
			autoplay: true,
			autoHeight: true,
			autoplaySpeed: 3000,
			autoplayTimeout:10000,
			responsive:{
				0:{
					items:1
				},
				500:{
					items:2
				},
				991:{
					items:3
				},
				1300:{
					items:4
				}
			}
		});
		var whatstrend = $('.owl-whats-trending').owlCarousel({
			loop: true,
			// items: 3,
			margin: 0,
			center: true,
			nav: true,
			video:true,
			// startPosition: 1,
			responsive:{
				0:{
					items:1
				},
				501:{
					items:3
				},
				850:{
					items:3
				},
				1400:{
					items:5
				}
			},
			onTranslate: function(event) {

				var currentSlide, player, command, localplayer;

				currentSlide = $('.owl-item.active.center');

				player = currentSlide.find(".youtube-player iframe").get(0);

				command = {
					"event": "command",
					"func": "pauseVideo"
				};


				if (player != undefined) {
					player.contentWindow.postMessage(JSON.stringify(command), "*");

				}

				localplayer = currentSlide.find("video");
				$(localplayer).trigger('pause');
			}
		});*/
		/*$('.see-more').owlCarousel({
			loop:false,
			margin:10,
			nav:false,
			dots:false,
			responsive:{
				0:{
					items:1
				},
				600:{
					items:2
				},
				850:{
					items:3
				},
				1400:{
					items:5
				}
			}
		});*/
		$(".see-more").each(function() {
			var gallery = $(this);
			// console.log($(window).width());
			// debugger;
			if($(window).width() < 1200){
				if($(window).width() < 600){
					var onMultiple = gallery.children(".item").length >= 2 ? true : false;
				}
				else{
					var onMultiple = gallery.children(".item").length > 4 ? true : false;
				}
			}
			else{ 
				var onMultiple = gallery.children(".item").length > 5 ? true : false;
			}
			// console.log(gallery.children(".item").length);
			gallery.owlCarousel({
				margin:10,
				nav:false,
				dots:false,
				rewind: true,
				merge:true,
				responsive:{
					0:{
						loop: onMultiple,
						items:3
					},
					600:{
						loop: onMultiple,
						items:5
					},
					850:{
						loop: onMultiple,
						items:7
					},
					1200:{
						loop: onMultiple,
						items:9
					},
					1400:{
						loop: onMultiple,
						items:9
					}
				}
			})
			$(window).resize(function(){
			});
		});

		function setsessionlatlang(){
			if(localStorage.getItem("lat") != null)
				lat = localStorage.getItem("lat");
			if(localStorage.getItem("lang") != null)
				lang = localStorage.getItem("lang");
			// console.log("lat"+lat+"--lang--"+lang);
			$.ajax({
				url : baseurl+"/setsessionlatlang",
				type : 'post',
				data : {'lat': lat,"lang" : lang},
				async: true,
				processData: true,
				success : function(res){

				},
				error : function(err){ 

				}
			});
		}

		function showPosition(position) {
			if(position.coords.latitude != null){
				$("#hid_lat").val(position.coords.latitude); 
				$("#hid_lang").val(position.coords.longitude);
				localStorage.setItem("lat", position.coords.latitude);
				localStorage.setItem("lang", position.coords.longitude);
			}
		}



		@if( \Request::segment(1) == 'user' || \Request::segment(1) == 'knosh-world')
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

		function removeWhish(id){

			$.ajax({
				url : baseurl+"/removeUserWishlist",
				type : 'post',
				data : {'id': id},
				async: true,
				processData: true,
				success : function(res){
					// $(".error-message-area").css("display","block");
					// $(".error-content").css("background","#9cda9c");
					// $(".error-msg").html("The wishlist is removed successfully."); 
					toast('The wishlist is removed successfully.', 'Success!', 'success'); 

					setTimeout(function(){location.reload()}, 2000);

				},
				error : function(err){ 
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
		}

		function updateBookmark(id){

			$.ajax({
				url : baseurl+"/bookmark/update",
				type : 'post',
				async: true,
				processData: true,          
				data : {'vendor_id': id},

				success : function(res){
					var msg = JSON.parse(JSON.stringify(res)); 
					// $(".error-message-area").css("display","block");
					// $(".error-content").css("background","#9cda9c");
					// $(".error-msg").html("<b style='color:black'>"+msg.message+"</b>");
				   toast(msg.message, 'Success!', 'success'); 
					setTimeout(function(){location.reload()}, 1000);

				},
				error : function(err){ 
					var msg = err.responseJSON.message; 
					if(msg == 'Unauthenticated.')
						self.location=baseurl+"/login";

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
		}

		function updateFavorites(id){

			$.ajax({
				url : baseurl+"/favourite/update",
				type : 'post',
				async: true,
				processData: true,          
				data : {'menu_id': id},

				success : function(res){
					var msg = JSON.parse(JSON.stringify(res)); 
					//$(".error-message-area").css("display","block");
					// $(".error-content").css("background","#9cda9c");
					// $(".error-msg").html("<b style='color:black'>"+msg.message+"</b>"); 
					toast(msg.message, 'Success!', 'success'); 
					setTimeout(function(){location.reload()}, 1000);

				},
				error : function(err){ 
					var msg = err.responseJSON.message; 
					if(msg == 'Unauthenticated.')
						self.location=baseurl+"/login";

					// console.log(msg);
					// $(".error-content").css("background","#ED4956");
					// $(".error-message-area").find('.error-msg').text(msg);
					// $(".error-message-area").show();
					if(resend){
						$(".ndiv").show();
						$(".odiv").hide();
					}
				}
			});
		}

		$("#wishlistForm").submit(function(){

			var url = baseurl+"/wishlist/update"; 
			var data = $(this).serialize();

			$.ajax({
				url : url,
				data : data,
				dataType : 'json',
				type : 'post',
				success : function(res){
					var msg = JSON.parse(JSON.stringify(res));
					 //$(".error-message-area").css("display","block");
					// $(".error-content").css("background","#f55a60");
					 $(".error-msg").html(msg.message); 
					 toast(msg.message, 'Success!', 'success');  
					setTimeout(function(){location.reload()}, 2000);
				},
				error : function(err){ 
					var msg = err.responseJSON.message; 
					// console.log(msg);
					$(".error-content").css("background","#ED4956");
					$(".error-message-area").find('.error-msg').text(msg);
					$(".error-message-area").show();
				}
			});
			event.preventDefault();
		});
		@endif

		//home page popular recipe detail ajax
		function popularinfo(id){
			$.ajax({
				type : 'POST',
				url : base_url+'showpopular',
				data : {id:id},
				success:function(data){
					$('#commentbox').html(data.html);
				},
				error : function(err){ 
			
					$("#profilemodal").modal('hide');
					var msg = err.responseJSON.message; 
					$(".error-content").css("background","#d4d4d4");
					$(".error-message-area").find('.error-msg').text(msg);
					$(".error-message-area").show();
				}
			});
		}
		//home page blog detail ajax
		function bloginfo(id){
			$.ajax({
				type : 'POST',
				url : base_url+'showblog',
				data : {id:id},
				success:function(data){
					$('#commentbox').html(data.html);
				},
				error : function(err){ 
			
					$("#profilemodal").modal('hide');
					var msg = err.responseJSON.message; 
					$(".error-content").css("background","#d4d4d4");
					$(".error-message-area").find('.error-msg').text(msg);
					$(".error-message-area").show();
				}
			});
		} 

		function cuisine(id){
			if(localStorage.getItem("lat") != null)
				lat = localStorage.getItem("lat");
			if(localStorage.getItem("lang") != null)
				lang = localStorage.getItem("lang");
			self.location= baseurl+"/explore/cuisines/"+id/*+"/"+lat+"/"+lang*/;
		}
		
		function seeMore(module) {
			if(localStorage.getItem("lat") != null)
				lat = localStorage.getItem("lat");
			if(localStorage.getItem("lang") != null)
				lang = localStorage.getItem("lang");
			//self.location = baseurl+"/seeMore/"+module+"/"+$("#hid_lat").val()+"/"+$("#hid_lang").val();
			self.location = baseurl+"/seeMore/"+module/*+"/"+lat+"/"+lang*/;
		}

		function seeexplore(keyword){
			if(localStorage.getItem("lat") != null)
				lat = localStorage.getItem("lat");
			if(localStorage.getItem("lang") != null)
				lang = localStorage.getItem("lang");
			//self.location = baseurl+"/explore/"+keyword+"/"+$("#hid_lat").val()+"/"+$("#hid_lang").val();
			self.location = baseurl+"/explore/"+keyword+"/"+lat+"/"+lang;
		}

		function seecuisinechef(keyword,cuisine_id){
			if(localStorage.getItem("lat") != null)
				lat = localStorage.getItem("lat");
			if(localStorage.getItem("lang") != null)
				lang = localStorage.getItem("lang");
			//self.location = baseurl+"/explore/"+keyword+"/"+$("#hid_lat").val()+"/"+$("#hid_lang").val();
			self.location = baseurl+"/explore/"+keyword+"/"+cuisine_id+"/"+lat+"/"+lang;
		}

		$("select[name='area']").change(function(){
			$see_more = $(this).val();
			$explore = $(this).find(':selected').attr('data-id');
		   /* if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(showPosition);
			} */
			 if(localStorage.getItem("lat") != null)
			   lat = localStorage.getItem("lat");
			 if(localStorage.getItem("lang") != null)
			  lang = localStorage.getItem("lang");
			//self.location = baseurl+"/seeMore/"+$see_more+"/"+$("#hid_lat").val()+"/"+$("#hid_lang").val();
			if($explore=='explore'){
				var modu="/explore/";
			} else {
				var modu="/seeMore/";
			}
			//self.location = baseurl+modu+$see_more+"/"+$("#hid_lat").val()+"/"+$("#hid_lang").val();
			self.location = baseurl+modu+$see_more/*+"/"+lat+"/"+lang*/;
			//localStorage.clear();
		});

		$(document).ready(function(){
			@if(Request::is('/') || Request::is('search*'))
				homePage();
			@endif
			$('.SideBar').fadeIn();
			@if(Request::is('setting*'))
			const actualBtn = document.getElementById('actual-btn');
			const fileChosen = document.getElementById('file-chosen');
			actualBtn.addEventListener('change', function(){
				fileChosen.textContent = this.files[0].name
				var ofile=document.getElementById('actual-btn').files[0];
				var formdata = new FormData();
				formdata.append("image",ofile);
				$.ajax({
					type : 'POST',
					dataType : 'json',
					url:base_url+"profileImageChange",
					data : formdata,
					contentType: false,
					processData: false,
					success: function (data) {
						toast(data.result,'Success!','success');
						document.getElementById("card-img-top").src = data.path;
						$('#imagelink').attr('href',data.path);
						$('.prof-img img').attr('src',data.path);

					},
					error: function (data,response) {
						toast(data,'Oops!','error');
					}   
				});
			})
			@endif
			@if (Session::has('register'))
			$('#registration_success').modal('show');
			@endif

			$('.profile-asw .profile-asw-menu').click(function(){
				if($('.profile-asw .settings-main-menu').hasClass('profile-menu-active')){
					$('.profile-asw .settings-main-menu').removeClass('profile-menu-active');
					$('.profile-asw .profile-backdrop').addClass('d-none');
				}
				else{
					$('.profile-asw .settings-main-menu').addClass('profile-menu-active');
					$('.profile-asw .profile-backdrop').removeClass('d-none');
				}
			})
			$('.profile-asw .profile-backdrop').click(function(){
				$('.profile-asw .settings-main-menu').removeClass('profile-menu-active');
				$('.profile-asw .profile-backdrop').addClass('d-none');
			})
			$('.profile-asw .profile-asw-menu-close').click(function(){
				$('.profile-asw .settings-main-menu').removeClass('profile-menu-active');
				$('.profile-asw .profile-backdrop').addClass('d-none');
			})
			$('.tooltip-btn-asw').mouseover(function(){
				$(".tooltip-asw").css("opacity","1");
			})
			$('.tooltip-btn-asw').click(function(){
				$(".tooltip-asw").css("opacity","1");
			})    
			$('.tooltip-btn-asw').mouseout(function(){
				$(".tooltip-asw").css("opacity","0");
			})

			var logeyecount=0;
			var logconfeye=0;
			$('.login-eye i').click(function(){
				logeyecount++;
				if(logeyecount%2==0){
					$('.login-psw-asw input').attr('type', 'password');
					$('.login-psw-asw input~div i').removeClass("fa-eye-slash")
					$('.login-psw-asw input~div i').addClass("fa-eye")
				}
				else{
					$('.login-psw-asw input').attr('type', 'text');
					$('.login-psw-asw input~div i').addClass("fa-eye-slash")
					$('.login-psw-asw input~div i').removeClass("fa-eye")
				}
			})
			$('.login-conf-eye i').click(function(){
				logconfeye++;
				if(logconfeye%2==0){
					$('.login-confpsw-asw input').attr('type', 'password');
					$('.login-confpsw-asw input~div i').removeClass("fa-eye-slash")
					$('.login-confpsw-asw input~div i').addClass("fa-eye")
				}
				else{
					$('.login-confpsw-asw input').attr('type', 'text');
					$('.login-confpsw-asw input~div i').addClass("fa-eye-slash")
					$('.login-confpsw-asw input~div i').removeClass("fa-eye")
				}
			})

			$('.share-option-top .share-mod-btn-asw').click(function(){
				if($(this).parent().parent().parent().parent().parent().siblings().hasClass('share-option-active-asw')){
					$(this).parent().parent().parent().parent().parent().siblings().removeClass('share-option-active-asw');
				} else {
					$(this).parent().parent().parent().parent().parent().siblings().addClass('share-option-active-asw');
				}
			}) 

			$('.cui-active-menu').click(function(){
				if($('.area-asw .searchbyfood .nav-tabs').hasClass('cui-active')){
					$('.area-asw .searchbyfood .nav-tabs').removeClass('cui-active');
					$('.cuisine-backdrop').addClass('d-none');
				} else {
					$('.area-asw .searchbyfood .nav-tabs').addClass('cui-active');
					$('.cuisine-backdrop').removeClass('d-none');
				}
			})
			$('.cuisine-backdrop').click(function(){
				$('.area-asw .searchbyfood .nav-tabs').removeClass('cui-active');
				$('.cuisine-backdrop').addClass('d-none');
			})
			$(".chef-asw .my-mod-time ul.over-hid .custom-checkbox label input.disable").parent().css("color","#b7b7b7");

			$(document).on('click',".cart-popup-btn",function(){
				if(!$('.cart-popup-btn').hasClass('active-cart-btn')){
					$(this).addClass("active-cart-btn")
					$(".chef-asw .carts .cart-cont .foodlist.chefcart").removeClass("active-cart")
					$(".chef-asw .carts .cart-cont .foodlist.chefcart").addClass("active-cart")
					$(".cart-backdrop").removeClass("d-none")
				}
				else{
					$(this).removeClass("active-cart-btn")
					$(".cart-backdrop").addClass("d-none")
					$(".chef-asw .carts .cart-cont .foodlist.chefcart").addClass("active-cart")
					$(".chef-asw .carts .cart-cont .foodlist.chefcart").removeClass("active-cart")
				}
			})
			$(document).on('click',".cart-backdrop",function(){
				$('.cart-popup-btn').removeClass("active-cart-btn")
				$(".cart-backdrop").addClass("d-none")
				$(".chef-asw .carts .cart-cont .foodlist.chefcart").addClass("active-cart")
				$(".chef-asw .carts .cart-cont .foodlist.chefcart").removeClass("active-cart")
			})

			$(document).on("click",".apply-coupon-btn,.disable-apply-coupon-btn",function(){
				$(this).addClass("active")
				$(".coupon-asw .coupon-nav").addClass("active");
				$(".coupon-backdrop").removeClass("d-none");
				$(".disable-coupon").show();
				if($(this).hasClass('disable-apply-coupon-btn')){
					$(".disable-coupon").hide();
				}
				$('body').css("overflow","hidden");
			});
			$(document).on("click",".coupon-backdrop, .coupon-nav-close",function(){
				$(".apply-coupon-btn").removeClass("active")
				$(".coupon-asw .coupon-nav").removeClass("active");
				$(".coupon-backdrop").addClass("d-none");
				$('body').css("overflow","unset");
			}); 
			
			if(navigator.userAgent.indexOf("Firefix")){
				$(".chefreg-asw .chefregbenefits .benefit-box2").addClass("firefox");
			}

			$(".box-video").click(function(){
				if ($(window).width() > 500) {
					// $('source',this)[0].src += "";
					if ($(this).parents('.active.center').length) { 
						$(this).addClass('open');
						$(this).css("height", $(this).width());
					}
				}
			});
			// var i = $('.owl-whats-trending').find(".box-video");
			// $(window).resize(function(){
			//     if($(window).width() > 500){
			//         for(var j=0;j< i.length;j++){
			//             $(i[j]).css("height", 0.75 * $(i[j]).width());
			//         }
			//     }
			// })
			// if ($(window).width() > 500) {
			//     for(var j=0;j< i.length;j++){
			//         $(i[j]).css("height", 0.75 * $(i[j]).width());
			//         // console.log(i[j], $(i[j]).width())
			//     }
			// }


			// $('.bg-video').click(function(){
			//     $('.owl-whats-trending').trigger('stop.owl.autoplay');
			// })
			// var vid = document.getElementById("recipevideoorg").addEventListener("ended",pausevideo);
			// function pausevideo() {
			//     console.log("success")
			//     $('.owl-whats-trending').trigger('play.owl.autoplay');
			// };

			///readmore script

			$(document).on('click','.read-more',function(){
				if(!$(this).hasClass("more")){
					$(this).siblings(".read-more-cont").addClass('more');
					$(this).addClass('more');
					$(this).text('Make less');
				}
				else{
					$(this).siblings(".read-more-cont").removeClass('more');
					$(this).removeClass('more');
					$(this).text('Read more');
				}
			})

			function readmore(){
				var no_of_class= $('.read-more-cont').length;
				for (var i = 0; i < no_of_class; i++) {
					var words=($('.read-more-cont')[i].innerHTML.split(' '));
					// console.log(words.length);
					if(words.length < 70){
						$(".read-more-cont").eq(i).addClass("more");
						$(".read-more-cont").eq(i).siblings(".read-more").addClass("d-none");
					}
				}
			}
			readmore();
			setInterval(readmore, 800);
			///end of readmore script
		});
	</script> 
	@if (\Session::has('add_continue_redirect'))
	<script type="text/javascript">
		$(document).ready(function(){
			var add_continue_redirect="{{Session::get('add_continue_redirect')}}";
			//$("#exampleModal"+add_food_id).modal();
			var target = $('#popular_continue_scroll');
			if (target.length) {
			$('html,body').animate({
			scrollTop: target.offset().top-140
			}, 1000);
			return false;
			}
		});
	</script>
	{{ Session::forget('add_continue_redirect') }}
	@endif
	<script>
		var pagecount = 1;
		$('.owl-carousel-explore').owlCarousel({
			loop:true,
			margin:10,
			autoplay:10000,
			autoplaySpeed:3000,
			dots: false,
			responsiveClass:true,
			autoplay: true,
			
			responsive:{
				0:{
					items:3,
					nav:true
				},
				600:{
					items:3,
					nav:true
				},
				767:{
					items:4,
					nav:true
				},
				1000:{
					items:6,
					nav:true,
				}
			}
		})
		var owlMedia = $('.owl-media-sec');
		owlMedia.owlCarousel({
			loop:true,
			margin:10,
			autoplay:10000,
			autoplaySpeed:3000,
			dots: false,
			responsiveClass:true,
			autoplay: true,
			
			responsive:{
				0:{
					center: true,
					items:3,
					nav:true
				},
				600:{
					center: true,
					items:3,
					nav:true
				},
				767:{
					items:4,
					nav:true
				},
				1000:{
					center: true,
					items:5,
					nav:true,
				}
			}
		})
		$('.modal.mediaSection').on('shown.bs.modal', function (e) {
			if($('.mediaSection').hasClass('show')){
				owlMedia.trigger('stop.owl.autoplay');
			}
		});
		$('.modal.mediaSection').on('hidden.bs.modal', function (e) {
			owlMedia.trigger('play.owl.autoplay');
		});

		$('.top-area-car').owlCarousel({
		  loop:true,
		  margin:10,
		  nav:true,
		  dots:false,
		  responsive:{
			  0:{
				  items:1
			  },
			  600:{
				  items:3
			  },
			  1000:{
				  items:4
			  }
		  }
		})
		$('.popular-new-width').owlCarousel({
			loop:true,
			margin:10,
			nav:false,
			dots:false,
			autoplay:true,
			responsive:{
				0:{
					items:1
				},
				600:{
					items:2
				},
				850:{
					items:3
				},
				1400:{
					items:4
				}
			}
		})
		//-------------------show on scroll Effect-------------------
		var scroll = window.requestAnimationFrame || function(callback){ window.setTimeout(callback, 1000/60)};
		var elementsToShow = document.querySelectorAll('.show-on-scroll'); 

		function loop() {
			Array.prototype.forEach.call(elementsToShow, function(element){
			  if (isElementInViewport(element)) {
				element.classList.add('is-visible');
			  } else {
				element.classList.remove('is-visible');
			  }
			});
			scroll(loop);
		}
		loop();
		function isElementInViewport(el) {
		  if (typeof jQuery === "function" && el instanceof jQuery) {
			el = el[0];
		  }
		  var rect = el.getBoundingClientRect();
		  return (
			(rect.top <= 0
			  && rect.bottom >= 0)
			||
			(rect.bottom >= (window.innerHeight || document.documentElement.clientHeight) &&
			  rect.top <= (window.innerHeight || document.documentElement.clientHeight))
			||
			(rect.top >= 0 &&
			  rect.bottom <= (window.innerHeight || document.documentElement.clientHeight))
		  );
		}

		/* flipbox tags show hide */
		$('.flip-box-front').mouseenter(function(){
			var id = $(this).attr('data-id');
			$('.tags'+id).hide();
		});
		$('.flip-box-back').mouseleave(function(){
			var id = $(this).attr('data-id');
				$('.tags'+id).show();
		});
		/* flipbox tags show hide end */

		$(document).ready( function() {
			$('#myCarousel').carousel({
				interval:   4000
			});
			
			var clickEvent = false;
			$('#myCarousel').on('click', '.nav a', function() {
					clickEvent = true;
					$('.nav li').removeClass('active');
					$(this).parent().addClass('active');        
			}).on('slid.bs.carousel', function(e) {
				if(!clickEvent) {
					var count = $('.nav').children().length -1;
					var current = $('.nav li.active');
					current.removeClass('active').next().addClass('active');
					var id = parseInt(current.data('slide-to'));
					if(count == id) {
						$('.nav li').first().addClass('active');    
					}
				}
				clickEvent = false;
			});
		});
		//-------------------End of the show on scroll Effect-------------------

		$(document).on('click', '.loadModule', function(){
			var curURL      = window.location.href;
			var segments    = curURL.split( '/' );
			var module      = $(this).attr('name');
			var offsetValue = pagecount;
			offsetValue     = parseInt(offsetValue )+1;
			pagecount       = offsetValue;
			lat = localStorage.getItem("lat");
			lang = localStorage.getItem("lang");
			$.ajax({
				url     : ((module == 7) || (module == 8) || (module == 9)) ? curURL : base_url + 'seeMore/'+module+'/'+lat+'/'+lang,
				type    : "get",
				dataType: "json",
				async   : true,
				data    : {page:  offsetValue},
				success : function(data) {
					$(".loadModule").css("display","none");
					$(".paginate").append(data.html); 
					 $('.see-more').owlCarousel({
						loop:false,
						margin:10,
						nav:false,
						dots:false,
						responsive:{
							0:{
								items:1
							},
							600:{
								items:2
							},
							850:{
								items:3
							},
							1400:{
								items:5
							}
						}
					})

				}
			});
		});
		function videoblogscript(){
			//-------------------Video play-------------------
				(function(){
					// var path = $(location).attr('pathname').split("/");
					// var url = path[path.length-1];
					var v = document.getElementsByClassName("youtube-player");
					for (var n = 0; n < v.length; n++) {
						v[n].onclick = function () {
							whatstrend.trigger('refresh.owl.carousel');
							let url = this.dataset.id;
							//let id = url.split('=')[1];
							var id = '';
							url = url.replace(/(>|<)/gi,'').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
							if(url[2] !== undefined) {
							id = url[2].split(/[^0-9a-z_\-]/i);
							id = id[0];
							}
							else {
							id = url;
							}
							//console.log(id);
							var iframe = document.createElement("iframe");
							iframe.setAttribute("src", "//www.youtube.com/embed/" + 
								id +
								"?autoplay=1&autohide=2&border=0&wmode=opaque&enablejsapi=1&rel="+
								this.dataset.related +"&controls="+
								this.dataset.control+
								"&showinfo=" + this.dataset.info); 
							iframe.setAttribute("frameborder", "0"); 
							iframe.setAttribute("id", "youtube-iframe"); 
							iframe.setAttribute("style", "width: 100%; height: 100%; position: absolute; top: 0; left: 0;"); 
							if (this.dataset.fullscreen == 1){ iframe.setAttribute("allowfullscreen", ""); 
							} 
							while (this.firstChild) 
							{ 
								this.removeChild(this.firstChild); 
							} 
							this.appendChild(iframe);
						}; 
					} 
				}
				)
				();
				(function(){
					var v = document.getElementsByClassName("local-player");
					var count = 0;
					var thumbnail = [];

					for (var n = 0; n < v.length; n++) {
						thumbnail[n] = v[n].innerHTML;
						v[n].onclick = function (e) {
							console.log('success')
							whatstrend.trigger('refresh.owl.carousel');
							let a = $(this).attr('data-count');
							let b = $(this);
							var video = $('<video />', {
								id: 'recipevideoorg',
								src: $(this).attr('data-src'),
								type: 'video/mp4',
								controls: true
							});

							if($(this).children().first().is("video")){
							}
							else{
								video.appendTo($(this));
								thumbnail = $(this).children().first();
								$(this).children().first().remove();
							}

							// video after ended
							var local = document.getElementById("recipevideoorg");
							local.onended = function(){
								// alert();
								b[0].appendChild(thumbnail[a]);
								local.remove();
							};
							// video after ended

						}; 
					}
				}
				)
				();
			//-------------------End Video play-------------------
		}
		$('.food-name').click(function(){
			if($(this).hasClass('elipsis-text')) {
				$(this).removeClass('elipsis-text');
			} else {
				$(this).addClass('elipsis-text');
			}
		});
	</script>
		<?php //dd('hhh');?>

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