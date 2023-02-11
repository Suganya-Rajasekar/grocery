<div class="foot-wave">
	<img src="{{asset('assets/front/img/waves.svg') }}" alt="wave-image">
</div>
<footer id="footer" class="@if(\Request::segment(1) == 'become-a-chef'  || \Request::segment(1) == 'partnerterms' ) removed-link  @endif">
	<div class="footer-area">
		<div class="footer-main-content">
			<div class="container-fluid">
		 
				<div class="footer-menu row">
					<div class="col-md-3 col-sm-6">
					<h3 class="title font-opensans">Learn</h3>
						<ul>
							<li><h4><a href="javascript::void(0);" class="font-montserrat">Help center</a></h4></li>
							<li><h4><a href="javascript::void(0);" class="font-montserrat">FAQ</a></h4></li>
						</ul>
					</div>
					<div class="col-md-3 col-sm-6">
					<h3 class="title font-opensans">About</h3>
						<ul>
							<li><h4><a href="{!! url('/aboutus') !!}" class="font-montserrat">About Us</a> </h4> </li>
							<li><h4><a href="{!! url('pages/contact-us') !!}" class="font-montserrat">Contact Us</a> </h4> </li>
						</ul>
					</div>

					<div class="col-md-3 col-sm-6">
					<h3 class="title font-opensans">Need Help</h3>
						<ul>
							<li><h4><a href="{!! url('terms-and-conditions') !!}" class="font-montserrat">Terms &amp; Conditions</a>  </h4> </li>
							<li><h4><a href="{!! url('privacy-policy') !!}" class="font-montserrat">Privacy Policy</a>  </h4> </li>
						</ul>
					   <div class=" footer-logos">
							<ul class="social_icon ">
								<li><a href="javascript::void(0);"><i class=""><img src="{!! asset('assets/img/fb.svg') !!}" alt="facebook-image"></i></a> </li>
								<li><a href="javascript::void(0);"><i class=""><img src="{!! asset('assets/img/twitter.svg') !!}" alt="twitter-image"></i> </a></li>
								<li><a href="javascript::void(0);"><i class=""><img src="{!! asset('assets/img/whatsapp.svg') !!}" alt="whatapp-image"></i></a> </li><!--
								<li><a href=""><i class="fab fa-instagram"></i></a> </li> -->
							</ul>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 ">
						<div class="app-images float-right">
							<h3 class="title font-opensans">Get the Apps</h3>
							<h3>
								<div class="playstore-img mb-2">
									<a href="javascript::void(0);">
										<div class="icon-download text-light mr-3">
											<i class="fab fa-google-play"></i>
										</div>
										<div class="text-download">
											<span class="text-light">Get it on</span>
											<h6 class="text-light font-opensans">Google Play</h6>
										</div>
									</a>
								</div>
								<div class="appstore-img  mb-2">
									<a href="javascript::void(0);">
										<div class="icon-download text-light mr-3">
											<i class="fas fa-mobile"></i>
										</div>
										<div class="text-download">
											<span class="text-light">Available on the </span>
											<h6 class="text-light font-opensans">App Store</h6>
										</div>
									</a>
								</div>
							</h3>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-copyright ">
			<div class="container-fluid">
				<div class="row m-0">
					<div class="col-md-6 text-left pl-0">
						<p id="copyright_area" class="font-montserrat"> © {!! config('app.name') !!}, Inc. 2021.All Rights Reserved.</p>
					</div>
					<div class="col-md-6 text-right pr-0">
						<p>Powered by {!! config('app.name') !!} Corp</p>
					</div>
				</div>  
			</div>
			</div>
		</div>
	</div>
</footer>
