
 <li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
 	<a class="nav-link sub_link" href="{{ route('home') }}">
 		<i class="material-icons">insert_chart</i>
 		<span class="sidebar-normal">Dashboard</span>
		@if(getUserModuleAccess('usermanages','view') == 1 && DeleteRequestCount() > 0)
		<span class="badge badge-info">New</span>
		@endif
 	</a>
 </li>

<li class="nav-item has-sub-menu {{ Request::is('book*') ? 'active' : '' }}">
    <a class="nav-link" data-toggle="collapse" href="#bookings">
        <i class="material-icons">home</i>
        <p>Chefs<b class="caret"></b></p>
    </a>
    <div class="collapse sub-menu  {{ Request::is('book*') ? 'show' : '' }}" id="bookings">
        <ul class="nav">
           @if(getUserModuleAccess('book','view') == 1)
              <li class="nav-item {{ Request::is('booking/pending') ? 'active' : '' }}">
                <a class="nav-link sub_link" href="{{ URL('/') }}/booking/pending">
                  <span class="sidebar-normal">Chefs Request</span>
                </a>
              </li>
              <li class="nav-item {{ Request::is('booking/ongoing*') ? 'active' : '' }}">
                <a class="nav-link sub_link" href="{{ URL('/') }}/booking/ongoing">
                  
                  <span class="sidebar-normal">All chefs</span>
                </a>
              </li>
            @endif
        </ul>
    </div>
</li>

<li class="nav-item has-sub-menu {{ Request::is('subscriptionPlans*','addon*','service*','category*','testimonials*','translate*','setting*') ? 'active' : '' }}">
    <a class="nav-link" data-toggle="collapse" href="#pvr_dashboard">
        <i class="material-icons">home</i>
        <p>
            Site Settings
            <b class="caret"></b>
        </p>
    </a>
    <div class="collapse sub-menu {{ Request::is('subscriptionPlans*','addon*','service*','category*','testimonials*','translate*','setting*') ? 'show' : '' }}" id="pvr_dashboard">
        <ul class="nav">
           @if(getUserModuleAccess('subscriptionPlans','view') == 1)
              <li class="nav-item {{ Request::is('subscriptionPlans*') ? 'active' : '' }}">
            <a class="nav-link sub_link" href="{{ route('subscriptionPlans.index') }}">
              <i class="material-icons">subscriptions</i>
              <span class="sidebar-normal">Subscription Plans</span>
            </a>
           </li>
           @endif


            @if(getUserModuleAccess('addon','view') == 1)
              <li class="nav-item {{ Request::is('addon*') ? 'active' : '' }}">
            <a class="nav-link sub_link" href="{{ route('addon.index') }}">
              <i class="material-icons">subscriptions</i>
              <span class="sidebar-normal">Addons</span>
            </a>
           </li>
           @endif

             @if(getUserModuleAccess('service','view') == 1)
              <li class="nav-item {{ Request::is('service*') ? 'active' : '' }}">
            <a class="nav-link sub_link" href="{{ route('service.index') }}">
              <i class="material-icons">subscriptions</i>
              <span class="sidebar-normal">Services</span>
            </a>
           </li>
           @endif
           @if(getUserModuleAccess('category','view') == 1)
              <li class="nav-item {{ Request::is('category*') ? 'active' : '' }}">
            <a class="nav-link sub_link" href="{{ route('category.index') }}">
              <i class="material-icons">subscriptions</i>
              <span class="sidebar-normal">Category</span>
            </a>
           </li>
           @endif
           @if(getUserModuleAccess('testimonials','view') == 1)
              <li class="nav-item {{ Request::is('testimonials*') ? 'active' : '' }}">
            <a class="nav-link sub_link" href="{{ route('testimonials.index') }}">
              <i class="material-icons">subscriptions</i>
              <span class="sidebar-normal">Testimonials</span>
            </a>
           </li>
           @endif
           @if(getUserModuleAccess('translate','view') == 1)
              <li class="nav-item {{ Request::is('translate*') ? 'active' : '' }}">
                <a class="nav-link sub_link" href="{{ route('translate.index') }}">
                  <i class="material-icons">subscriptions</i>
                  <span class="sidebar-normal">Translate</span>
                </a>
              </li>
            @endif
            @if(getUserModuleAccess('setting','view') == 1)
              <li class="nav-item {{ Request::is('setting*') ? 'active' : '' }}">
                <a class="nav-link sub_link" href="{{ route('setting.index') }}">
                  <i class="material-icons">subscriptions</i>
                  <span class="sidebar-normal">Settings</span>
                </a>
              </li>
            @endif
        </ul>
    </div>
</li>


<li class="nav-item has-sub-menu {{ Request::is('vendors*','usermanages*','roles*','managers*') ? 'active' : '' }}">
    <a class="nav-link" data-toggle="collapse" href="#employee">
        <i class="material-icons">home</i>
        <p>
            Users
            <b class="caret"></b>
        </p>
    </a>
    <div class="collapse sub-menu {{ Request::is('vendors*','usermanages*','roles*','managers*') ? 'show' : '' }}" id="employee">
        <ul class="nav">
           @if(getUserModuleAccess('vendors','view') == 1)
            <li style="display: none;" class="nav-item {{ Request::is('vendors*') ? 'active' : '' }}">
            <a class="nav-link sub_link" href="{{ route('vendors.index') }}">
              <i class="material-icons">business</i>
              <span class="sidebar-normal">Vendors</span>
            </a>
           </li>
           @endif
           @if(getUserModuleAccess('usermanages','view') == 1)
             <li class="nav-item {{ Request::is('usermanages*') ? 'active' : '' }}">
            <a class="nav-link sub_link" href="{{ route('usermanages.index') }}">
              <i class="material-icons">person</i>
              <span class="sidebar-normal">Customers</span>
            </a>
           </li>
           @endif
            @if(getUserModuleAccess('roles','view') == 1)
            <li class="nav-item {{ Request::is('roles*') ? 'active' : '' }}">
            <a class="nav-link sub_link" href="{{ route('roles.index') }}">
              <i class="material-icons">subscriptions</i>
              <span class="sidebar-normal">Roles</span>
            </a>
           </li>
           @endif
           @if(getUserModuleAccess('managers','view') == 1)
           <li class="nav-item {{ Request::is('managers*') ? 'active' : '' }}">
            <a class="nav-link sub_link" href="{{ route('managers.index') }}">
              <i class="material-icons">subscriptions</i>
              <span class="sidebar-normal">Employees</span>
            </a>
           </li>
           @endif
        </ul>
    </div>
</li>