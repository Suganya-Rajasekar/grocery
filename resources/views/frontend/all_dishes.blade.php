<div id="dishes" class="tab-pane @if(request()->section=='dishes' || !isset(request()->section)) active @endif">
    <section class="chef-asw">
        <div class="container-fluid">
            <div class="">
                {{-- <div class="col-md-12"></div> --}}
                {{-- if(request()->section == 'dishes' || !isset(request()->section)) --}}
                <div class="" id="post-data">
                    @if(count($chefinfo->chef_restaurant->approved_dishes) > 0)
                    @php
                    $chefdish   = $chefinfo->chef_restaurant->approved_dishes;
                    $type       = 'all_dishes';
                    @endphp
                    @include('frontend.details-dish',[$chefdish,$type,$offtime])
                    @endif
                </div>
                {{-- endif --}}
                <div class="">
                    <div class="carts detailData">
                        @include('frontend.details-cart')
                    </div>
                    <div class="cart-mob">
                        <a href="{{ url('checkout') }}"><i class="fas fa-arrow-circle-right"></i>View Cart</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>