<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
<input type="hidden" name="baseurl" value="{!! url('/') !!}">
<header id="header">
    <style>
        .navbar-brand {
            display: block;
            width: 110px;
        }
        .navbar-brand img {
            width: 100%;
        }
    </style>
    <nav class="navbar navbar-expand-lg  home-header header-area navbar-light " id="main-nav ">
        <a class="navbar-brand pjax" href="{!!url('/')!!}"><img src="{!!asset('assets/front/img/logo.svg')!!}" style="width: 100%" alt="knosh-logo"></a>
        <div class="disp991 ml-auto">
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse main-menu" id="navbarSupportedContent">
            {{-- <ul class="navbar-nav m-auto">
                <li class=" nav-item"></li>
            </ul> --}}
            <ul class="navbar-nav align-items-center  ml-auto  @if(Auth::check())head-log @endif">
                @if (!Auth::check())
                 <!-- <li class="nav-item ">
                    <a class="nav-link emp-nav  font-montserrat link {{ Request::segment(1)=='become-a-chef' ? 'active-menu' : '' }}" href="{{ URL::to('become-a-chef') }}">
                        Become a Chef
                    </a>
                </li>  -->
                @endif
                <li class="nav-item ">
                    <a class="nav-link emp-nav  font-montserrat link {{ Request::segment(2)=='chefevent' ? 'active-menu' : '' }}" href="{{ URL::to('seeMore/chefevent') }}">
                        Events
                    </a>
                </li>
                <li class="nav-item">
                    @php
                        $sea_lat=0;
                        $sea_lang=0;
                    @endphp
                    @if (\Session::has('latitude'))
                        @php $sea_lat=\Session::get('latitude'); @endphp
                    @endif
                    @if (\Session::has('longitude'))
                        @php $sea_lang=\Session::get('longitude'); @endphp
                    @endif
                    <a class="nav-link emp-nav font-montserrat searchpath link {{ Request::segment(1)=='search' ? 'active-menu' : '' }}" href="{{ url('/search/'.'?lat='.$sea_lat.'&lang='.$sea_lang) }}">
                        Search
                    </a>
                </li>
                @if(Auth::check())
                <li class="nav-item ">
                    <a href="#" class="float-lg-right nav-link emp-nav" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@if(\Auth::User()->name!=''){{ \Auth::User()->name }}@else{{ 'User '.Auth::user()->user_code }}@endif</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        @if(Auth::user()->role==3)
                        <a href="{{ route('vendor.dashboard') }}" class="dropdown-item">{{ __('Dashboard') }}</a>
                        @elseif(Auth::user()->role==1)
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item">{{ __('Dashboard') }}</a>
                        @elseif(Auth::user()->role==2)
                        <a href="{{ url('/user/dashboard/profile') }}" class="dropdown-item">{{ __('Profile') }}</a>
                        @endif
                        <a  href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item font-montserrat">
                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>Logout
                        </a>
                    </div>
                </li>
                @else
                <li class=" nav-item">
                    <a class=" nav-link font-montserrat   logintext  text-white  link {{ (Request::segment(2)== 'login' || Request::segment(1)== 'login') ? 'active-menu' : '' }} " href="@if(\Request::segment(1) != 'become-a-chef' && (\Request::segment(2) != 'register')  && (\Request::segment(1) != 'partnerterms')) {!!url('login')!!} @endif" target="_top">Login</a>
                    <i class="empty"></i>
                </li>
                <li class=" nav-item font-montserrat  sighuptest  divider  ">
                    <a class="nav-link signuptest divider link {{ (Request::segment(2)== 'register' || Request::segment(1)== 'register') ? 'active-menu' : '' }} " href="@if(\Request::segment(1) != 'become-a-chef' && (\Request::segment(2) != 'register') && (\Request::segment(1) != 'partnerterms')){!!url('register')!!}@endif" target="_top">Sign Up</a>
                    <i class="empty"></i>
                </li>
                @endif
                <li>
                    <a class="nav-link emp-nav font-montserrat disable-apply-coupon-btn" href="javascript:void(0);">Offers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link cart-icons" href="#">
                    </a>
                    @php
                       $count =  app()->call('App\Http\Controllers\Api\Emperica\CartController@cartCount')->getData();
                    @endphp
                    <a href="@if(\Request::segment(1) != 'become-a-chef' && (\Request::segment(2) != 'register') && (\Request::segment(1) != 'partnerterms')){{ url('checkout') }}@endif" id="toggle-sidebar-left1" class=" nav-link">
                        <div class="shopping-cart">
                            <span class="countt">{!! $count->count !!}</span>
                            <span class="ti-shopping-cart fa fa-shopping-cart"></span>
                            <div class="count_load"></div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="header-area " >
        <div class="header-main-area" style="display: none;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-2 bord-right">
                        <div class="header-logo">
                            <a href="{!! \URL::to('') !!}" class="pjax">
                                <img id="logo" src="{!!asset('assets/front/img/logo.png')!!}" alt="Emperica">
                            </a>
                        </div>
                    </div>
                    <div class=" col-lg-9 ">
                        <div class="header-main-right-area">
                            <div class="shopping-cart f-right">
                                <span class="ti-shopping-cart fa fa-shopping-cart"></span>
                                <div class="count_load"></div>
                            </div>
                            <div class="main-menu ">
                                <div class="mobile-menu">
                                    <a class="toggle f-right hc-nav-trigger hc-nav-1" href="#" role="button" aria-controls="hc-nav-1"><i class="ti-menu"></i></a>
                                </div>
                                <nav id="main-nav" class="hc-nav-original hc-nav-1">
                                    <ul>
                                        <li class=" nav-item  ">
                                            <a class=" nav-link    logintext  text-white  " href="http://localhost/emperica/user/login " target="_top">Login</a>
                                            <i class="empty"></i>
                                        </li>
                                        <li class=" nav-item  sighuptest  divider  ">
                                            <a class=" nav-link  signuptest  divider   " href="http://localhost/emperica/user/register " target="_top">Sign Up</a>
                                            <i class="empty"></i>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sidebar d-none" id="sidebar-left" style="position: fixed; top: 0px; bottom: 0px; width: 300px; z-index: 3000; right: -300px;" data-simplersidebar="closed">
        <div class="sidebar-wrapper">
            <div class="main_cart_ok">
                <div class="delivery-main-content sidebar text-center">
                    <h5 class="mt-20 mb-15">No Item in your Cart</h5>
                    <p class="mb-15">You haven't added anything in your cart yet! Start adding the products you like.</p>
                    <div class="cart-product-another-information">
                        <div class="single-information d-flex">
                            <span>Subtotal</span>
                            <div class="main-amount">
                                <span>USD 0.00</span>
                            </div>
                        </div>
                        <div class="checkout-btn disabled">
                            <a href="#" class="disabled">Checkout</a>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
    <input type="hidden" id="cart_update" value="http://localhost/emperica/cart/update">
    <input type="hidden" id="cart_delete" value="http://localhost/emperica/cart/delete">
</header>