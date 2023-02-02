<nav>
    <ul class="nav nav-tabs">
        <div class="profile-asw-menu-close d-lg-none">X</div>
        <li class="nsec mb-30" data-val="dashboard">
            <!-- <i class="fas fa-tachometer-alt mr-3"></i> -->
            <a href="{!! url('/user/dashboard/profile') !!}" class="font-opensans" @if(last(request()->segments()) == 'profile') class="active " @endif >

                <div class="icons">
                    <img src="{{url('/assets/img/edit.svg')}}">
                </div>
                Edit Profile
            </a>
        </li>
        <li class="nsec mb-30" data-val="password">
            <!-- <i class="fas fa-lock mr-3"></i> -->
            <a href="{!! url('/user/dashboard/changePassword') !!}" class="font-montserrat"  @if(last(request()->segments()) == 'changePassword') class="active" @endif >
                <div class="icons">
                    <img src="{{url('/assets/img/password.svg')}}">
                </div>
                Password
            </a>
        </li>
        <li class="nsec mb-30" data-val="orders">
            <!-- <i class="fas fa-clone mr-3"></i> -->
            <a href="{!! url('/user/dashboard/myOrders') !!}" class="font-montserrat" @if(last(request()->segments()) == 'myOrders') class="active" @endif>

                <div class="icons">
                    <img src="{{url('/assets/img/myorderrs.svg')}}">
                </div>
                My Orders 
            </a>
        </li>
         <li class="nsec mb-30" data-val="events">
            <!-- <i class="fas fa-clone mr-3"></i> -->
            <a href="{!! url('/user/dashboard/events') !!}" class="font-montserrat" @if(last(request()->segments()) == 'events') class="active" @endif>

                <div class="icons">
                    <b><img src="{{url('/assets/img/knosh-event.png')}}"></b>
                </div>
                My Events 
            </a>
        </li>
         <li class="nsec mb-30" data-val="booked_events">
            <!-- <i class="fas fa-clone mr-3"></i> -->
            <a href="{!! url('/user/dashboard/home_events') !!}" class="font-montserrat" @if(last(request()->segments()) == 'bookedevents') class="active" @endif>

                <div class="icons">
                    <b><img src="{{url('/assets/img/home_event.png')}}"></b>
                </div>
                My Home Events  
            </a>
        </li>
        <li class="nsec mb-30" data-val="bookmark">
            <!-- <i class="fas fa-heart mr-3"></i> -->
            <a href="{!! url('/user/dashboard/bookmark') !!}" class="font-montserrat" @if(last(request()->segments()) == 'bookmark') class="active" @endif>
                <div class="icons">
                    <img src="{{url('/assets/img/bookmark.svg')}}">
                </div>
                Bookmarks 
            </a>
        </li>
        <li class="nsec mb-30" data-val="wishlist">
            <!-- <i class="fas fa-heart mr-3"></i> -->
            <a href="{!! url('/user/dashboard/wishlist') !!}" class="font-montserrat" @if(last(request()->segments()) == 'wishlist') class="active" @endif>
                <div class="icons">
                    <img src="{{url('/assets/img/wishlist.svg')}}">
                </div>
                Wishlist 
            </a>
        </li>
        <li class="nsec mb-30" data-val="favourites">
            <!-- <i class="fas fa-heart mr-3"></i> -->
            <a href="{!! url('/user/dashboard/favourites') !!}" class="font-montserrat" @if(last(request()->segments()) == 'favourites') class="active" @endif>
                <div class="icons">
                    <img src="{{url('/assets/img/favorite.svg')}}">
                </div>
                Favorite
            </a>
        </li>
        <li class="nsec mb-30" data-val="referral">
            <!-- <i class="fas fa-heart mr-3"></i> -->
            <a href="{!! url('/user/dashboard/referral') !!}" class="font-montserrat" @if(last(request()->segments()) == 'referral') class="active" @endif>
                <div class="icons">
                    <i class="fa fa-user-plus"></i>
                </div>
                Referral
            </a>
        </li>
        <li class="nsec mb-30" data-val="wallet">
            <a href="{!! url('/user/dashboard/wallet') !!}" class="font-montserrat" @if(last(request()->segments()) == 'address') class="active" @endif>
                <div class="icons">
                    <img src="{{url('/assets/img/wallet.svg')}}">
                </div>
                Wallet
            </a>
        </li>
        <li class="nsec mb-30" data-val="address">
            <a href="{!! url('/user/dashboard/address') !!}" class="font-montserrat" @if(last(request()->segments()) == 'address') class="active" @endif>
                <div class="icons">
                    {{-- <img src="{{url('/assets/img/favorite.svg')}}"> --}}
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                Address
            </a>
        </li>
        <li class="nsec mb-30">
            <div class="icons">
                <img src="{{url('/assets/img/logout.svg')}}">
            </div>
            <a class="font-montserrat profile-dropdown" href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                Logout
            </a>
            <form id="logout-form-tab" action="http://localhost/emperica/logout" method="GET" class="d-none font-montserrat">
                <input type="hidden" name="_token" value="0HzGBWThqR7l2Ar52HHVhnslecsNyXLQnaBlnj3Q">  
            </form>
        </li>
    </ul>
</nav>