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
		<a class="navbar-brand pjax" href="{!!url('/')!!}"><img src="{!!asset('assets/front/img/logo.png')!!}" style="width: 100%" alt="logo"></a>
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
							<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">@csrf</form> Logout
						</a>
					</div>
				</li>
				@else
				<li class=" nav-item">
					<a class=" nav-link font-montserrat   logintext  text-white  link active-menu1" href="{!! url('login') !!}" target="_top">Login</a>
					<i class="empty"></i>
				</li>
				<li class=" nav-item font-montserrat  sighuptest  divider  ">
					<a class="nav-link signuptest divider link active-menu1" href="{!! url('register') !!}" target="_top">Sign Up</a>
					<i class="empty"></i>
				</li>
				@endif
			</ul>
		</div>
	</nav>
</header>