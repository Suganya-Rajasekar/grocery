@if((request()->ajax() && (isset($call) && $call == 'addcart') && $chefinfo['count'] > 0) || (request()->ajax() && (isset($call) && $call == 'addcart') && $chefinfo['count'] == 0))
<div class="cart-cont">
        <div class="d-md-flex d-none justify-content-between align-items-center "> 
            <div class="cart-popup-btn">
                <span class="font-montserrat"><i class="fas fa-arrow-up"></i>Your order</span>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <span class="font-montserrat mr-2">Sub total: &#8377;
                    <span class="subTotal">{!! $chefinfo['price'] !!}</span>
                </span>
                <div class="d-flex">
                    <a href="{!!url('/continueredirect/popscroll')!!}" class="mr-2 checkoutbtn-asw">
                        <h3 class="font-montserrat">Continue ordering</h3>
                    </a>
                    <a href="{{ url('checkout') }}" class="checkoutbtn-asw mr-2 d-flex justify-content-center align-items-center">
                        <h3 class="font-montserrat">Checkout</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class=" d-md-none justify-content-between align-items-center "> 
            <div class="cart-popup-btn d-flex justify-content-between mb-2">
                <span class="font-montserrat"><i class="fas fa-arrow-up"></i>Your order</span>
                <span class="font-montserrat">Sub total: &#8377;
                    <span class="subTotal">{!! $chefinfo['price'] !!}</span>
                </span>
            </div>
            <div class="">
                <div class="d-flex justify-content-between">
                    <a href="{!!url('/continueredirect/popscroll')!!}" class="mr-2 checkoutbtn-asw">
                        <h3 class="font-montserrat">Continue ordering</h3>
                    </a>
                    <a href="{{ url('checkout') }}" class="checkoutbtn-asw d-flex justify-content-center align-items-center">
                        <h3 class="font-montserrat">Checkout</h3>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="cart-cont p-0">
        <ul class="foodlist chefcart">
            <div class="chefs-checkout">
                <div>
                    <ul class="checkout-ul">
                        @foreach($chefinfo as $ccky => $ccval)
                        @isset ($ccval['vendor_info'])
                        <li>
                            <div class="chefs-checkout-list">
                                <div class="chefs-checkout-top d-flex">
                                    <div class="chef-checkout-img">
                                        <a href="">
                                            <img src="{!! $ccval['vendor_info']->Chef->avatar !!}" alt="">
                                        </a>
                                    </div>
                                    <div class="ml-1 ml-md-3 chef-check-det w-100">
                                        <div class="">
                                            <div class="d-flex justify-content-between">
                                                <div class="mw-0 w-md-50" >
                                                    <a href="">
                                                        <h4 class="font-opensans d-inline elipsis-text">{!! $ccval['vendor_info']->name !!}
                                                        </h4>
                                                    </a>
                                                    <i title="Remove item" class="fas fa-close delcart" data-function="removecart" data-res_id="{!! $ccval['vendor_info']->id !!}" data-date="{!! $ccval['food_items'][0]->date !!}" data-time_slot="{!! $ccval['food_items'][0]->time_slot !!}"
                                                        ></i>
                                                    @if($ccval['vendor_info']->type != 'event')
                                                    <h6 class="text-muted m-0 elipsis-text">
                                                        <span class="home-span ">
                                                            @foreach( $ccval['vendor_info']->Chef->cuisines as $c1 => $c2)
                                                            {!! $c2->name !!}@if(!$loop->last), @endif
                                                            @endforeach
                                                        </span>
                                                    </h6>
                                                        @if($ccval['vendor_info']->type == 'home_event')
                                                            <span class="order-date font-montserrat"><b> Booking date</b> [ {!! date('d M Y',strtotime($ccval['food_items'][0]->date)) !!} ]
                                                            </span>
                                                        @else 
                                                            <span class="order-date font-montserrat"><b> Delivery date / time:</b> [ {!! date('d M Y',strtotime($ccval['food_items'][0]->date)). ' <b>/</b> ' .$ccval['food_items'][0]->time_s !!} ]
                                                            </span>
                                                        @endif
                                                    @else
                                                    <div class="text-muted">
                                                        <span><b> Location:</b>{{ $ccval['vendor_info']->adrs_line_1 }}</span>
                                                    </div>
                                                    <div class="text-muted">
                                                        <span><b>Event date / time:</b>{!! date('d-m-Y',strtotime($ccval['vendor_info']->event_time[0])).' <b>/</b> '.$ccval['vendor_info']->event_time[1]!!}</span>

                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="mw-0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="check-food-details">
                                    <ul class="foodlist">
                                        @php
                                            $subtot = '0.00';
                                        @endphp                                        
                                        @isset ($ccval['food_items'])
                                        @foreach($ccval['food_items'] as $foo_k => $foo_val)
                                        @php
                                            $subtot += $foo_val->price;
                                        @endphp
                                        <li>
                                            <div class="foodandprice" >
                                                <div class="d-flex justify-content-between w-100">
                                                    <span class="font-montserrat">{!! $foo_val->food_name !!}
                                                      @if(count($ccval['food_items'])>1)
                                                        <i title="Remove item" class="fas fa-close delcart" data-function="removedish" data-food_id="{!! $foo_val->food_id !!}" data-date="{!! $foo_val->date !!}" data-time_slot="{!! $foo_val->time_slot !!}" ></i>
                                                       @endif
                                                    </span>
                                                    @if($foo_val->discount_price != 0 || $foo_val->discount_price != '')
                                                    <div class="d-flex justify-content-between" style="width: 220px">
                                                        <span class=" font-montserrat mr-2">
                                                            <del style="color:red;">&#8377; {!! number_format(($foo_val->food_price * $foo_val->quantity),2,'.','') !!} </del></span>
                                                    `   <span class=" font-montserrat">&#8377; {!! number_format(($foo_val->discount_price * $foo_val->quantity),2,'.','') !!} </span>
                                                    </div>
                                                    @else 
                                                    <span class=" font-montserrat">&#8377; {!! number_format(($foo_val->food_price * $foo_val->quantity),2,'.','') !!} </span>
                                                    @endif 
                                                </div>
                                                <div class="itembuttn mb-1">
                                                    <span class="ucart remove_item font-montserrat" data-uid="{!! $foo_val->id !!}">-</span>
                                                    <h5 class="cartQty font-montserrat" data-qty="{!! $foo_val->quantity !!}" data-staticqty="{{ $foo_val->quantity }}" id="qty_{!! $foo_val->id !!}">{!! $foo_val->quantity !!}</h5>
                                                    <span class="ucart add_item font-montserrat" data-uid="{!! $foo_val->id !!}">+</span>
                                                </div>
                                            </div>
                                            @if($ccval['vendor_info']->type == "home_event")
                                            <div class="d-flex w-100">
                                                <span class="font-montserrat mt-3 font-weight-bold" style="color: #f55a60;">People Count :</span>
                                                <span style="margin-left: 35px;">
                                                    <ul>
                                                        @foreach($foo_val->meals_data as $mname => $mcount)
                                                        <li style="list-style: disc;">{{ $mname }} - {{ $mcount }}</li>
                                                        @endforeach
                                                    </ul>
                                                </span>
                                            </div>

                                            <div class="d-flex mt-3"> 
                                                <span style="color: #f55a60;" class="font-montserrat mt-3 font-weight-bold">@if(!empty($foo_val->preferences_data)) preferences : @endif</span>
                                                <span class="ml-5">
                                                    <ul>
                                                        @foreach($foo_val->preferences_data as $pk => $preference)
                                                        <li style="list-style: disc;">{{ $preference['name'] }}</li>
                                                        @endforeach
                                                    </ul>
                                                </span>
                                            </div>

                                            @foreach($foo_val->theme_data as $k => $theme)
                                            <div class="d-flex justify-content-between mt-3 mb-3"> 
                                                <div class="d-flex">
                                                    <span style="color: #f55a60; width: 91px;" class="font-monserrat mt-3 font-weight-bold"> theme: </span>
                                                    <span class="ml-5"><ul><li style="list-style: none">{{ $theme['name'] }}</li></ul></span>
                                                </div>
                                                    <p class="font-montserrat" style="float:right">&#8377; {!! number_format($theme['amount'],2,'.','') !!}</p>
                                            </div>
                                            @endforeach

                                            @foreach($foo_val->addon_item as $addon_k => $addon_val)
                                            <div class="d-flex justify-content-between w-100 extra addonandprice">
                                                <span class=" text-muted font-montserrat">{!! $addon_val->name !!}
                                                    <i title="Remove addon" class="fas fa-close delcart" data-function="removeaddon" data-addon_id="{!! $addon_val->id !!}"></i>
                                                </span>
                                                <span class="font-montserrat text-muted text-theme">&#8377; {!! number_format($addon_val->price,2,'.','') !!}</span>
                                            </div>
                                            @endforeach

                                            @else 
                                            @foreach($foo_val->addon_item as $addon_k => $addon_val)
                                            <div class="d-flex justify-content-between w-100 extra addonandprice">
                                                <span class=" text-muted font-montserrat">{!! $addon_val->name !!}
                                                    <i title="Remove addon" class="fas fa-close delcart" data-function="removeaddon" data-addon_id="{!! $addon_val->id !!}"></i>
                                                </span>
                                                <span class="font-montserrat text-muted text-theme">&#8377; {!! number_format($addon_val->price,2,'.','') !!}</span>
                                            </div>
                                            @endforeach
                                            @endif
                                        </li>
                                        @endforeach
                                        @endisset
                                    </ul>
                                    {{-- <ul class="price-list">
                                        <li>
                                            <div class="d-flex w-100 justify-content-between">
                                                <span class="font-montserrat">Sub Total</span>
                                                <span class="font-montserrat">&#8377; {!! number_format($subtot,2,'.','') !!} </span>
                                            </div>
                                        </li>
                                    </ul> --}}
                                </div>
                            </div>
                        </li>
                        @endisset
                        @endforeach
                    </ul>
                </div>
            </div>
        </ul>
    </div>
@elseif(request()->ajax() && $chefinfo->cart_detail->count == 0)
@else
<div class=" ">
    {{-- <div class="text-center p-3 detail_cart-title-bg">
        <h3 class="font-weight-bold font-opensans">
            Cart
        </h3>
    </div> --}}
    @if($chefinfo->cart_detail->count > 0)
    <div class="cart-cont">
        <div class="d-md-flex d-none justify-content-between align-items-center "> 
            <div class="cart-popup-btn">
                <span class="font-montserrat"><i class="fas fa-arrow-up"></i>Your order</span>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <span class="font-montserrat mr-2">Sub total: &#8377;
                    <span class="subTotal">{!! $chefinfo->cart_detail->price !!}</span>
                </span>
                <div class="d-flex">
                    <a href="{!!url('/continueredirect/popscroll')!!}" class="mr-2 checkoutbtn-asw">
                        <h3 class="font-montserrat">Continue ordering</h3>
                    </a>
                    <a href="{{ url('checkout') }}" class="checkoutbtn-asw mr-2 d-flex justify-content-center align-items-center">
                        <h3 class="font-montserrat">Checkout</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class=" d-md-none justify-content-between align-items-center "> 
            <div class="cart-popup-btn d-flex justify-content-between mb-2">
                <span class="font-montserrat"><i class="fas fa-arrow-up"></i>Your order</span>
                <span class="font-montserrat">Sub total: &#8377;
                    <span class="subTotal">{!! $chefinfo->cart_detail->price !!}</span>
                </span>
            </div>
            <div class="">
                <div class="d-flex justify-content-between">
                    <a href="{!!url('/continueredirect/popscroll')!!}" class="mr-2 checkoutbtn-asw">
                        <h3 class="font-montserrat">Continue ordering</h3>
                    </a>
                    <a href="{{ url('checkout') }}" class="checkoutbtn-asw d-flex justify-content-center align-items-center">
                        <h3 class="font-montserrat">Checkout</h3>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="cart-cont p-0">
        <ul class="foodlist chefcart @if(request()->ajax()) active-cart @endif">
            <div class="chefs-checkout">
                <div>
                    <ul class="checkout-ul">
                        @foreach($chefinfo->cart_detail as $ccky => $ccval)
                        @isset ($ccval->vendor_info)
                        <li>
                            <div class="chefs-checkout-list">
                                <div class="chefs-checkout-top d-flex">
                                    <div class="chef-checkout-img">
                                        <a href="">
                                            <img src="{!! $ccval->vendor_info->Chef->avatar !!}" alt="">
                                        </a>
                                    </div>
                                    <div class="ml-1 ml-md-3 chef-check-det w-100">
                                        <div class="">
                                            <div class="d-flex justify-content-between">
                                                <div class="mw-0 w-md-50" >
                                                    <a href="">
                                                        <h4 class="font-opensans d-inline elipsis-text">{!! $ccval->vendor_info->name !!}
                                                        </h4>
                                                    </a>
                                                    <i title="Remove item" class="fas fa-close delcart" data-function="removecart" data-res_id="{!! $ccval->vendor_info->id !!}" data-date="{!! $ccval->food_items[0]->date !!}" data-time_slot="{!! $ccval->food_items[0]->time_slot !!}"
                                                        ></i>
                                                    @if($ccval->vendor_info->type != 'event')
                                                    <h6 class="text-muted m-0 elipsis-text">
                                                        <span class="home-span ">
                                                            @foreach( $ccval->vendor_info->Chef->cuisines as $c1 => $c2)
                                                            {!! $c2->name !!}@if(!$loop->last), @endif
                                                            @endforeach
                                                        </span>
                                                    </h6>
                                                        @if($ccval->vendor_info->type == 'home_event')
                                                            <span class="order-date font-montserrat"><b> Booking date</b> [ {!! date('d M Y',strtotime($ccval->food_items[0]->date)) !!} ]
                                                            </span>
                                                        @else 
                                                            <span class="order-date font-montserrat"><b> Delivery date / time:</b> [ {!! date('d M Y',strtotime($ccval->food_items[0]->date)). ' <b>/</b> ' .$ccval->food_items[0]->time_s !!} ]
                                                            </span>
                                                        @endif
                                                    @else
                                                      <div class="text-muted">
                                                        <span><b> Location:</b>{{ $ccval->vendor_info->adrs_line_1 }}</span>
                                                    </div>
                                                    <div class="text-muted">
                                                        <span><b>Event date / time:</b>{!! date('d-m-Y',strtotime($ccval->vendor_info->event_time[0])).' <b>/</b> '.$ccval->vendor_info->event_time[1]!!}</span>

                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="mw-0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="check-food-details">
                                    <ul class="foodlist">
                                        @php
                                            $subtot = '0.00';
                                        @endphp
                                        @isset ($ccval->food_items)
                                        @foreach($ccval->food_items as $foo_k => $foo_val)
                                        @php
                                            $subtot += $foo_val->price;
                                        @endphp
                                        <li>
                                            <div class="foodandprice" >
                                                <div class="d-flex justify-content-between w-100">
                                                    <span class="font-montserrat">{!! $foo_val->food_name !!}
                                                      @if(count($ccval->food_items)>1)
                                                        <i title="Remove item" class="fas fa-close delcart" data-function="removedish" data-food_id="{!! $foo_val->food_id !!}" data-date="{!! $foo_val->date !!}" data-time_slot="{!! $foo_val->time_slot !!}" ></i>
                                                       @endif
                                                    </span>
                                                    @if($foo_val->discount_price != 0)
                                                    <div class="d-flex justify-content-between" style="width: 220px">
                                                        <span class=" font-montserrat mr-3">
                                                        <del style="color:red;">&#8377; {!! number_format(($foo_val->food_price * $foo_val->quantity),2,'.','') !!}</del>
                                                        </span>
                                                        <span class=" font-montserrat">&#8377; {!! number_format(($foo_val->discount_price * $foo_val->quantity),2,'.','') !!} </span>
                                                    </div>
                                                    @else 
                                                     <span class=" font-montserrat">&#8377; {!! number_format(($foo_val->food_price * $foo_val->quantity),2,'.','') !!} </span>
                                                    @endif                                                
                                                </div>
                                                <div class="itembuttn mb-1">
                                                    <span class="ucart remove_item font-montserrat" data-uid="{!! $foo_val->id !!}">-</span>
                                                    <h5 class="cartQty font-montserrat"  data-qty="{!! $foo_val->quantity !!}" data-staticqty="{{ $foo_val->quantity }}" id="qty_{!! $foo_val->id !!}">{!! $foo_val->quantity !!}</h5>
                                                    <span class="ucart add_item font-montserrat" data-uid="{!! $foo_val->id !!}">+</span>
                                                </div>
                                            </div>
                                            @if($ccval->vendor_info->type == "home_event")
                                            <div class="d-flex w-100">
                                                <span class="font-montserrat mt-3 font-weight-bold" style="color: #f55a60;">People Count :</span>
                                                <span style="margin-left: 35px;">
                                                    <ul>
                                                        @foreach($foo_val->meals_data as $mname => $mcount)
                                                        <li style="list-style: disc;">{{ $mname }} - {{ $mcount }}</li>
                                                        @endforeach
                                                    </ul>
                                                </span>
                                            </div>

                                             <div class="d-flex mt-3"> 
                                                <span style="color: #f55a60;" class="font-montserrat mt-3 font-weight-bold">@if(!empty($foo_val->preferences_data)) preferences : @endif</span>
                                                <span class="ml-5">
                                                    <ul>
                                                        @foreach($foo_val->preferences_data as $pk => $preference)
                                                        <li style="list-style: disc;">{{ $preference->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                </span>
                                            </div>

                                            @foreach($foo_val->theme_data as $k => $theme)
                                            <div class="d-flex justify-content-between mt-3 mb-3"> 
                                                <div class="d-flex">
                                                    <span style="color: #f55a60; width: 91px;" class="font-monserrat mt-3 font-weight-bold"> theme: </span>
                                                    <span class="ml-5"><ul><li style="list-style: none">{{ $theme->name }}</li></ul></span>
                                                </div>
                                                    <p class="font-montserrat" style="float:right">&#8377; {!! number_format($theme->amount,2,'.','') !!}</p>
                                            </div>
                                            @endforeach

                                            @foreach($foo_val->addon_item as $addon_k => $addon_val)
                                            <div class="d-flex justify-content-between w-100 extra addonandprice">
                                                <span class=" text-muted font-montserrat">{!! $addon_val->name !!}
                                                    <i title="Remove addon ss" class="fas fa-close delcart" data-function="removeaddon" data-addon_id="{!! $addon_val->id !!}"></i>
                                                </span>
                                                <span class="font-montserrat text-muted text-theme">&#8377; {!! number_format($addon_val->price,2,'.','') !!}</span>
                                            </div>
                                            @endforeach
                                            
                                            @else 
                                            @foreach($foo_val->addon_item as $addon_k => $addon_val)
                                            <div class="d-flex justify-content-between w-100 extra addonandprice">
                                                <span class=" text-muted font-montserrat">{!! $addon_val->name !!}
                                                    <i title="Remove addon" class="fas fa-close delcart" data-function="removeaddon" data-addon_id="{!! $addon_val->id !!}"></i>
                                                </span>
                                                <span class="font-montserrat text-muted text-theme">&#8377; {!! number_format($addon_val->price,2,'.','') !!}</span>
                                            </div>
                                            @endforeach
                                            
                                            @endif
                                        </li>
                                        @endforeach
                                        @endisset
                                    </ul>
                                    {{-- <ul class="price-list">
                                        <li>
                                            <div class="d-flex w-100 justify-content-between">
                                                <span class="font-montserrat">Sub Total</span>
                                                <span class="font-montserrat">&#8377; {!! number_format($subtot,2,'.','') !!} </span>
                                            </div>
                                        </li>
                                    </ul> --}}
                                </div>
                            </div>
                        </li>
                        @endisset
                        @endforeach
                    </ul>
                </div>
            </div>
        </ul>
    </div>
    @else
    <div class="p-3 no-cart text-center d-none">
        <img src="{{ asset('assets/front/img/nocart.png') }}" alt="no-cart"/ style="height: 200px;">
        <h5>Good food is always cooking! Go ahead, order some yummy items from the menu.</h5>
    </div>
    @endif
</div>
@endif
