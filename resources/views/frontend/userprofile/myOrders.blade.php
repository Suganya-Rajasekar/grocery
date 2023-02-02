<div class="setting-main-area tab-pane fade  verification_area @if(last(request()->segments()) == 'myOrders') active show @endif "  id="orders">
    <div class="settings-content-area">
        <h4 class="d-inline font-opensans">My Orders</h4>
        <div class="d-lg-none float-right profile-asw-menu d-inline"><i class="fa fa-bars"></i></div>
        <div class="shadow-table">
            <div class=" mt-3">
                <div class="profile-asw-myorder-tabs">
                    <ul class="nav nav-tabs text-center d-flex justify-content-between">
                        <li class="nav-item col-6 p-0">
                            {{-- <a class="nav-link font-montserrat @if(request()->section=='in_progress') active @elseif(request()->section!='past_orders') active @endif" href="{{ url('user/dashboard/myOrders?section=in_progress') }}">In Progess</a> --}}
                            <a class="nav-link font-montserrat @if(request()->section=='in_progress') active @elseif(request()->section!='past_orders') active @endif" href="#inprogress" data-toggle="tab">In Progess</a>
                        </li>
                        <li class="nav-item col-6 p-0">
                            <a class="nav-link font-montserrat @if(request()->section=='past_orders') active @endif" href="#pastorder" data-toggle="tab">Past Orders</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content profile-asw-myorder-tabs-content">
                    <div id="inprogress" class=" tab-pane @if(request()->section=='in_progress') active @elseif(request()->section!='past_orders') active @endif">
                        @include('frontend.userprofile.orders.progress')
                    </div>
                    <div id="pastorder" class=" tab-pane @if(request()->section=='past_orders') active @endif">
                        @include('frontend.userprofile.orders.pastorder')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>