   <div class="sidebar" data-color="purple" data-image="http://via.placeholder.com/1080x1920">
        <div class="sidebar-wrapper">
            <!--Begins Logo start-->
            <div class="logo">
                <a href="javascript:void(0)" class="simple-text logo-mini" style="width: auto;">
                    
                </a>
                <a href="javascript:void(0)" class="simple-text logo-normal">
                    {!! CNF_APPNAME !!} Admin
                </a>
            </div>
            <!--End Logo start-->

            <!--Begins User Section-->
            <div class="user">
                <div class="photo">
                    <i class="fa fa-user" style="padding: 8px;"></i>
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#pvr_user_nav" class="collapsed">
                            <span>@if (Auth::guest())
                                Admin
                                @else
                                    {{ Auth::user()->name}}
                                @endif
                                <b class="caret"></b>
                            </span>
                    </a>
                    <div class="collapse m-t-10" id="pvr_user_nav">
                        <ul class="nav">
                            <li>
                                <a class="profile-dropdown" href="javascript:void(0)">
                                    <span class="sidebar-mini"><i class="icon-user"></i></span>
                                    <span class="sidebar-normal">My Profile</span>
                                </a>
                            </li>
                            <li>
                                <a class="profile-dropdown" href="javascript:void(0)">
                                    <span class="sidebar-mini"><i class="icon-settings"></i></span>
                                    <span class="sidebar-normal">Settings</span>
                                </a>
                            </li>
                            <li>
                                <a class="profile-dropdown" href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <span class="sidebar-mini"><i class="icon-logout"></i></span>
                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    <span class="sidebar-normal">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--End User Section-->

            <ul class="nav">
                 @include('layouts.menu')
            </ul>
        </div>
    </div>