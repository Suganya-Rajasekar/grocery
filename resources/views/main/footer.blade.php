<div class="foot-wave">
    <img src="{{asset('assets/front/img/waves.svg') }}" alt="wave-image">
</div>
<footer id="footer" class="@if(
                                \Request::segment(1) == 'become-a-chef'  || 
                                \Request::segment(1) == 'partnerterms' 
                            )
                                removed-link 
                            @endif">
    <div class="footer-area">
        <!-- <div class="container-fluid ">
            <div class="logo_area row">
                <div class="col-md-12 mb-5">
                     <div class="float-left footer-logos">
                    <img src="http://localhost/emperica/script/main-content/Themes/main/public/img/drapshot_footer_logo.png">
                </div>
                <div class="float-right footer-logos">
                    <ul class="social_icon text-right">
                        <li><a href=""><i class="fab fa-twitter"></i></a> </li>
                        <li><a href=""><i class="fab fa-facebook-f"></i> </a></li>
                        <li><a href=""><i class="fab fa-linkedin"></i></a> </li>
                        <li><a href=""><i class="fab fa-instagram"></i></a> </li>
                    </ul>
                </div>
                </div>
               
            </div>
        </div> -->
        <div class="footer-main-content">
            <div class="container-fluid">
         
                <div class="footer-menu row">
                    <div class="col-md-3 col-sm-6">
                    <h3 class="title font-opensans">Learn</h3>
                        <ul>
                            <!--                                 <li>
                                    <h4><a href="http://localhost/emperica/restaurant/register">Register as restaurant</a></h4>
                                </li>
                             -->
                            <li><h4><a href="{{ url('become-a-chef') }}" class="become-a-chef font-montserrat">Become a chef</a></h4></li>
                            <li><h4><a href="@if(\Request::segment(1) != 'become-a-chef' && (\Request::segment(1) != 'partnerterms')) # @endif" class="font-montserrat">Help center</a></h4></li>
                            <li><h4><a href="@if(\Request::segment(1) != 'become-a-chef' && (\Request::segment(1) != 'partnerterms')) # @endif" class="font-montserrat">FAQ</a></h4></li>
                            <!-- <li>
                                <h4><a href="http://localhost/emperica">Home</a></h4>
                            </li> -->
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-6">
                    <h3 class="title font-opensans">About</h3>
                        <ul>
                            <li><h4><a href="@if(\Request::segment(1) != 'become-a-chef' && (\Request::segment(1) != 'partnerterms')){{ url('/aboutus') }}@endif" class="font-montserrat">About Us</a> </h4> </li>
                            <li><h4><a href="@if(\Request::segment(1) != 'become-a-chef' && (\Request::segment(1) != 'partnerterms')){{ url('how-it-works') }}@endif" class="font-montserrat">How it works</a> </h4> </li>
                            <li><h4><a href="@if(\Request::segment(1) != 'become-a-chef' && (\Request::segment(1) != 'partnerterms')){{ url('pages/contact-us') }}@endif" class="font-montserrat">Contact Us</a> </h4> </li>
                            <li><h4><a href="@if(\Request::segment(1) != 'become-a-chef' && (\Request::segment(1) != 'partnerterms')){{ url('/blogs') }}@endif" class="font-montserrat">Media & Press</a> </h4> </li>
                        </ul>
                    </div>

                    <div class="col-md-3 col-sm-6">
                    <h3 class="title font-opensans">Need Help</h3>
                        <ul>
                            <li><h4><a href="@if(\Request::segment(1) != 'become-a-chef' && (\Request::segment(1) != 'partnerterms')){{ url('terms-and-conditions') }}@endif" class="font-montserrat">Terms &amp; Conditions</a>  </h4> </li>
                            <li><h4><a href="@if(\Request::segment(1) != 'become-a-chef' && (\Request::segment(1) != 'partnerterms')){{ url('conduct-guidelines') }}@endif" class="font-montserrat">Conduct Guidelines</a>  </h4> </li>
                            <li><h4><a href="@if(\Request::segment(1) != 'become-a-chef' && (\Request::segment(1) != 'partnerterms')){{ url('privacy-policy') }}@endif" class="font-montserrat">Privacy Policy</a>  </h4> </li>
                            <li><h4><a href="@if(\Request::segment(1) != 'become-a-chef' && (\Request::segment(1) != 'partnerterms')){{ url('cancellation-refund') }}@endif" class="font-montserrat">Cancellation &amp; Refunds Policy</a>  </h4> </li>
                        </ul>
                       <div class=" footer-logos">
                            <ul class="social_icon ">
                                <li><a href="@if(\Request::segment(1) != 'become-a-chef' && (\Request::segment(1) != 'partnerterms'))@endif"><i class=""><img src="{!! asset('assets/img/fb.svg') !!}" alt="facebook-image"></i></a> </li>
                                <li><a href="@if(\Request::segment(1) != 'become-a-chef' && (\Request::segment(1) != 'partnerterms'))@endif"><i class=""><img src="{!! asset('assets/img/twitter.svg') !!}" alt="twitter-image"></i> </a></li>
                                <li><a href="@if(\Request::segment(1) != 'become-a-chef' && (\Request::segment(1) != 'partnerterms'))@endif"><i class=""><img src="{!! asset('assets/img/whatsapp.svg') !!}" alt="whatapp-image"></i></a> </li><!--
                                <li><a href=""><i class="fab fa-instagram"></i></a> </li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 ">
                        <div class="app-images float-right">
                            <h3 class="title font-opensans">Get the Apps</h3><h3>
                            <div class="playstore-img mb-2">
                                <a href="https://play.google.com/store/apps/details?id=com.emperica.app">{{-- <img src="{!! asset('assets/img/playstore.png') !!}" alt="playstore-img"> --}}
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
                                <a href="https://apps.apple.com/in/app/knosh-food-order-and-delivery/id1601196646">{{-- <img src="{!! asset('assets/img/appstore.png') !!}" alt="playstore-img"> --}}
                                    <div class="icon-download text-light mr-3">
                                        <i class="fas fa-mobile"></i>
                                    </div>
                                    <div class="text-download">
                                        <span class="text-light">Available on the </span>
                                        <h6 class="text-light font-opensans">App Store</h6>
                                    </div>
                                </a>
                            </div>
                        </h3></div>
                    </div>
                                    </div>
            </div>
            <div class="footer-copyright ">
            <div class="container-fluid">
                <div class="row m-0">
                    <div class="col-md-6 text-left pl-0">
                        <p id="copyright_area" class="font-montserrat"> © Knosh, Inc. 2021.All Rights Reserved.</p>
                    </div>
                    <div class="col-md-6 text-right pr-0">
                        <!-- <p>Powered by Drapshop Corp</p> -->
                    </div>
                </div>  
            </div>
            </div>
        </div>
    </div>
</footer>
