@if(count($carts->cart_detail) > 0)
<div class="chefs-checkout">
    {{-- <div class="d-flex justify-content-between py-3 px-2"> --}}
        <?php 
            $baseurl    = url('').'/';
            if($previous!='' && $baseurl==$previous) {
                $prv_url=url('/continueredirect/popscroll');
            } else if($previous!='') {
                $prv_url=$previous;
            } else {
                $prv_url=$previous;
            }
        ?>
    {{-- </div> --}}
    <div class="d-flex flex-wrap justify-content-between py-3 px-2 ">
        <a href="{{ 'chef/'.\Session::get('current_chefid') }}" class="mb-2 mb-sm-0">
            <h5 class="font-opensans btn "> << Back</h5>
        </a>
        <a href="{!!url('/continueredirect/popscroll')!!}">
            <h5 class="font-opensans btn ">Continue ordering</h5>
        </a>
        {{-- <br> --}}
        <form class="d-flex cartform" action="{!! url('deletecart') !!}" method="post" enctype="Multipart/form-data">
            {!! csrf_field() !!}
            <input name="_method" type="hidden" value="DELETE">
            <input name="function" type="hidden" value="clearcart">
            <button type="submit" class="btn font-montserrat" id="cart">Clear cart</button>
        </form>
    </div>
    <div>
        <ul class="checkout-ul">
            <?php 
            $tot_coup_val = 0;
            ?>
            @foreach($carts->cart_detail as $k => $v)
                @php

                if($v->vendor_info->type != "event" && $v->vendor_info->type != "home_event") {  
                    $now = ceil(time()/1800)*1800;
                    $chunks = explode('-', $v->food_items[0]->time_s);
                    $open_time = strtotime($chunks[0]);
                    $close_time = strtotime($chunks[1]);
                }
                $tot_coup_val += $v->food_items[0]->total_coupon_value;
                @endphp
                <!-- $AddMins  = (date('i')>30) ? ((60 * 60) + (60 * 60) + (60 * 60)) : ((60 * 60) + (60 * 60));
                $open_time -= $AddMins;
                $close_time -= $AddMins; -->
                {{--date ("g:i", $now)--}}
                {{--date ("g:i", $open_time)--}}
                {{--date ("g:i", $close_time)--}}
                @php
                    $class = '';
                    $text  = '';
                @endphp

                @if($v->vendor_info->type == 'event')
                    @if($v->vendor_info->Chef->avalability == 'not_avail' || strtotime($v->vendor_info->event_datetime) < strtotime(date('Y-m-d H:i:s')))
                    @php
                     $class = 'un_available position-relative';
                     $text  = "<div class='font-opensans expired'>Un available </div>";
                    @endphp
                    @endif
                @else
                   @if($v->vendor_info->Chef->avalability == 'not_avail' || $v->food_items[0]->ordertimeslotavailable->status == false)
                   @php
                   $class = 'un_available position-relative';
                   $text  = "<div class='font-opensans expired'>Un available</div>";
                   @endphp
                   @if($v->food_items[0]->ordertimeslotavailable->status == false)
                   @php
                   $class = 'un_available position-relative';
                   $text  = "<div class='font-opensans expired'>".$v->food_items[0]->ordertimeslotavailable->message."</div>";
                   @endphp
                   @endif
                   @endif
                 @endif
            <li>
                <div class="chefs-checkout-list {{$class}}">
                    <div class="chefs-checkout-top d-flex">
                        <div class="chef-checkout-img">
                            <a href="{{url('chef/'.$v->vendor_info->vendor_id)}}">
                                <img src="{{$v->vendor_info->Chef->avatar}}" alt="">
                            </a>
                        </div>
                        <div class="ml-1 ml-md-3 chef-check-det w-100">
                            <div class="">
                                <div class="d-flex justify-content-between">
                                    <div class="mw-0 w-md-50" >
                                        <a href="{{url('chef/'.$v->vendor_info->vendor_id)}}">
                                            <h3 class="font-opensans elipsis-text">{!! $v->vendor_info->name !!}
                                            </h3>
                                        </a>
                                        <i title="Remove item" class="delcart {!! $class !!} fas fa-close" data-function="removecart" data-res_id="{!! $v->vendor_info->id !!}" data-date="{!! $v->food_items[0]->date !!}" data-time_slot="{!! $v->food_items[0]->time_slot !!}"
                                        ></i>
                                        
                                        
                                        @if($v->vendor_info->type != 'event')
                                        <button type="button" class="btn btn-primary editdeliveryslot{{ $v->food_items[0]->id }}" name="timeslot_edit" data-toggle="modal" data-target="#exampleModaldate{{ $v->food_items[0]->id }}" data-olddate="{{ $v->food_items[0]->date }}" data-oldtime_slot="{{ $v->food_items[0]->time_slot }}" data-resid="{{ $v->vendor_info->id }}" onclick="time_slot({{ $v->food_items[0]->food_id }},'{{ $v->vendor_info->preparation_time }}',{{ $v->food_items[0]->id }})" data-cartid="{{ $v->food_items[0]->id }}">Edit</button>
                                        <h6 class="text-muted m-0 elipsis-text">
                                            <span class="home-span">
                                                @foreach( $v->vendor_info->Chef->cuisines as $c1 => $c2)
                                                    {!! $c2->name !!}@if(!$loop->last), @endif
                                                @endforeach
                                            </span>
                                        </h6>

                                        @if($v->vendor_info->type == 'home_event')
                                        <span class="order-date font-montserrat"><b> Booking date</b> [ {!! date('d M Y',strtotime($v->food_items[0]->date)) !!} ]
                                        </span>
                                        @else 
                                        <span class="order-date font-montserrat"><b> Delivery date / time:</b>
                                            [ {!! date('d M Y',strtotime($v->food_items[0]->date)).' <b>/</b> '.$v->food_items[0]->time_s!!}]
                                        </span>
                                        @endif

                                        @else 
                                        <div class="text-muted">
                                            <span><b> Location:</b>{{ $v->vendor_info->adrs_line_1 }}</span>
                                        </div>
                                        <div class="text-muted">
                                            <span><b>Event date / time:</b>{!! date('d-m-Y',strtotime($v->vendor_info->event_time[0])).' <b>/</b> '.$v->vendor_info->event_time[1]!!}</span>
                                            
                                        </div>
                                        @endif
                                    </div>
                                    <div class="mw-0">
                                    </div>
                                </div>
                                @if($v->vendor_info->type != 'event')
                                <div class="sqr-star">
                                    <div class=" star-rating ">
                                        <div class="overflow-hidden">
                                            <div class="float-left">
                                                @for($x=1;$x<=$v->vendor_info->Chef->ratings;$x++)
                                                    <label class="star-rating-star js-star-rating">
                                                    <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                                    </label>
                                                @endfor
                                                @if (strpos($v->vendor_info->Chef->ratings,'.'))
                                                    <label class="star-rating-star js-star-rating">
                                                    <svg class="svg-inline--fa fa-star fa-w-18 remaining remain-last" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                                    </label>
                                                    @php
                                                    $x++;
                                                    @endphp
                                                @endif
                                                @while ($x<=5)
                                                    <label class="star-rating-star js-star-rating">
                                                    <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                                    </label>
                                                    @php
                                                    $x++;
                                                    @endphp
                                                @endwhile
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="check-food-details position-relative">
                        <ul class="foodlist">
                            @php
                            $subtot = '0.00';
                            @endphp
                            @if($v->food_items)
                            @foreach($v->food_items as $foo_k => $foo_val)
                            @php
                            $subtot += $foo_val->price;
                            @endphp
                            <li @if($foo_val->food_type == "home_event_menu" && $foo_k != 0) class="mt-5" @endif>
                                <div class="foodandprice" >
                                    <div class="d-flex justify-content-between w-100">
                                        <span class="font-montserrat" style="width:270px;">{!! $foo_val->food_name !!}   
                                        @if(count($v->food_items)>1)
                                            <i title="Remove item" class="fas fa-close delcart" data-function="removedish" data-food_id="{!! $foo_val->food_id !!}" data-date="{!! $foo_val->date !!}" data-time_slot="{!! $foo_val->time_slot !!}" ></i>
                                        @endif
                                        @if($v->vendor_info->type != 'event')
                                        <button type="button" class="btnedit btn-primary editdeliveryslot{{ $foo_val->id }}" name="item_timeslot_edit" data-toggle="modal" data-target="#exampleModaldate{{ $foo_val->id }}" data-olddate="{{ $foo_val->date }}" data-oldtime_slot="{{ $foo_val->time_slot }}" data-resid="{{ $v->vendor_info->id }}" onclick="time_slot({{ $foo_val->food_id }},'{{ $v->vendor_info->preparation_time }}',{{ $foo_val->id }},'menu_edit')" data-cartid="{{ $foo_val->id }}">Edit</button>
                                        @endif
                                        </span>
                                        @if($foo_val->discount_price != 0)
                                         <span class=" font-montserrat">
                                            <del style="color:red;">&#8377; {!! number_format(($foo_val->food_price * $foo_val->quantity),2,'.','') !!}</del>
                                        </span>
                                         <span class=" font-montserrat">&#8377; {!! number_format(($foo_val->discount_price * $foo_val->quantity),2,'.','') !!}</span>
                                        @else 
                                        <span class=" font-montserrat">&#8377; {!! number_format(($foo_val->food_price * $foo_val->quantity),2,'.','') !!}</span>
                                        @endif
                                    </div>
                                    <div class="itembuttn mb-1">
                                        <span class="ucart remove_item font-montserrat" data-uid="{!! $foo_val->id !!}">-</span>
                                        <h5 class="cartQty font-montserrat" data-qty="{!! $foo_val->quantity !!}" data-staticqty="{{ $foo_val->quantity }}" id="qty_{!! $foo_val->id !!}">{!! $foo_val->quantity !!}</h5>
                                        <span class="ucart add_item font-montserrat" data-uid="{!! $foo_val->id !!}">+</span>
                                    </div>
                                </div>
                                @if($foo_val->food_type == "home_event_menu")
                                @if(isset($foo_val->meals_data)) 
                                <div class="d-flex mt-3"> 
                                    <span class="font-montserrat" style="color: #f55a60;">People Count :</span>
                                    <span style="margin-left: 35px;">
                                        <ul>
                                            @foreach($foo_val->meals_data as $mname => $mcount)
                                            <li style="list-style: disc;">{{ $mname }} - {{ $mcount }}</li>
                                            @endforeach
                                        </ul>
                                    </span>
                                </div>
                                @endif

                                @if(isset($foo_val->preferences_data)) 
                                <div class="d-flex mt-3"> 
                                    <span style="color: #f55a60;" class="font-montserrat">@if(!empty($foo_val->preferences_data))preferences : @endif</span>
                                    <span class="ml-5">
                                        <ul>
                                            @foreach($foo_val->preferences_data as $pk => $preference)
                                            <li style="list-style: disc;">{{ $preference->name }}</li>
                                            @endforeach
                                        </ul>
                                    </span>
                                </div>
                                @endif

                                @if(isset($foo_val->theme_data)) 
                                @foreach($foo_val->theme_data as $k => $theme)
                                <div class="d-flex justify-content-between mt-3 mb-3"> 
                                    <span class="font-montserrat"><span style="color: #f55a60;">Theme:</span> {{ $theme->name }}</span>
                                    <span class="font-montserrat">&#8377; {!! number_format($theme->amount,2,'.','') !!}</span>
                                </div>
                                @endforeach
                                @endif

                                @if(isset($foo_val->addon_item))
                                @foreach($foo_val->addon_item as $addon_k => $addon_val)
                                <div class="d-flex justify-content-between w-100 extra addonandprice">
                                    <span class=" text-muted font-montserrat">{!! $addon_val->name !!} 
                                        <i title="Remove addon" class="fas fa-close delcart" data-function="removeaddon" data-addon_id="{!! $addon_val->id !!}"></i>
                                    </span>
                                    <span class="font-montserrat text-muted text-theme">&#8377; {!! number_format($addon_val->price,2,'.','') !!}</span>
                                </div>
                                @endforeach
                                @endif
                                @endif

                                @if($foo_val->food_type != "home_event_menu")
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
                            <div class="chef-asw">
                                <div class="modal my-mod-date fade" id="exampleModaldate{{ $foo_val->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin-top: 0px;">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title m-auto" id="exampleModalLabel">
                                                    <span class="foodname font-opensans">Order for future</span>
                                                </h5>
                                                <button type="button" class="close datatime" data-dismiss="modal" aria-label="Close" data-unit="" data-id="" data-myval="">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" data-pric="" class="resrt ids">
                                                    <div class="addon-sec over-hid">
                                                        <div class="modalbody-head">
                                                            <!-- <form name="myForm" action="{{ url('myurl') }}" method="POST"> -->
                                                                <div id="datepicker{{ $foo_val->id }}" name='datepicker_start' class="datepickerdish" data-id="" data-offdates=""></div>
                                                                <input type="hidden" name="date" id="olddate">
                                                                <input type="hidden" name="test" value="10">
                                <!-- <input type="submit" name="submit" value="submit">  
                                </form> -->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn cancel-asw font-montserrat" data-toggle="modal" data-target="#exampleModal" data-dismiss="modal">Cancel</button>
                    @if($foo_val->food_type == "home_event_menu")
                        <button type="button" class="btn font-montserrat datebtn" onclick="date_time_confirm({{ $foo_val->id }})">Confirm</button>
                        <input type="hidden" id="home_event" value="yes">
                    @else 
                        <button type="button" class="btn font-montserrat datebtn" data-toggle="modal" data-target="#exampleModaldelivery_time{{ $foo_val->id }}" data-dismiss="modal" disabled="">Confirm</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal my-mod-time fade" id="exampleModaldelivery_time{{ $foo_val->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin-top:0px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <span class="foodname">Delivery time slots</span>
                    </h5>
                    <button type="button" class="close datatime" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="height: 500px;">
                    <form action="" data-pric="" class="resrt ids " id="timeappend{{ $foo_val->id }}">

                    </form>
                </div>
                <div class="modal-footer">
                    <div class="d-flex order-btn">
                        <input type="hidden" id="CartId">
                        <input type="hidden" id="ResID">
                        <input type="hidden" id="old_date{{ $foo_val->id }}">
                        <input type="hidden" id="old_timeslot{{ $foo_val->id }}">
                        <input type="hidden" id="edited_date{{ $foo_val->id }}">
                        <input type="hidden" id="action{{ $foo_val->id }}">
                        <input type="hidden" id="chef_type{{ $foo_val->id }}" value="{{ $foo_val->food_type }}">
                        <button type="button" class="btn cancel-asw timeslot-cancel" data-toggle="modal" data-target="#exampleModaldate" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn" onclick="date_time_confirm({{ $foo_val->id }})">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                            @endforeach
                            @endif
                        </ul>
                        <hr>
                        <ul class="price-list">
                            <li>
                                <div class="d-flex w-100 justify-content-between">
                                    <span class="font-montserrat">Item Total</span>
                                    <span class="font-montserrat">&#8377; {!! number_format($subtot,2,'.','') !!}</span>
                                </div>
                                @if($v->food_items[0]->total_coupon_value > 0)
                                <div class="d-flex w-100 justify-content-between" style="color:red;">
                                    <span class="font-montserrat">offer</span>
                                    <span class="font-montserrat">- &#8377; {!! number_format($v->food_items[0]->total_coupon_value,2,'.','') !!}</span>
                                </div>
                                @endif
                                @if($v->food_items[0]->tax > 0)
                                <div class="d-flex w-100 justify-content-between">
                                    <span class="font-montserrat">Tax</span>
                                    <span class="font-montserrat">&#8377; {!! number_format($v->food_items[0]->tax,2,'.','') !!}</span>
                                </div>
                                @endif
                                @if($v->vendor_info->type != 'event' && $v->vendor_info->type != 'home_event')
                                <div class="d-flex w-100 justify-content-between">
                                    <span class="font-montserrat">Delivery Charge</span>
                                    <span class="font-montserrat">&#8377; {!! number_format($carts->DelChargePerOrder,2,'.','') !!}</span>
                                </div>
                                <div class="d-flex w-100 justify-content-between">
                                    <span class="font-montserrat">Packaging Charge</span>
                                    <span class="font-montserrat">&#8377; {!! $v->vendor_info->package_charge !!}</span>
                                </div>
                                @endif
                            </li>
                        </ul>
                    {!! $text !!}
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        {{-- <div class="grand-total bg-light-theme">
            <ul>
                <li>
                    <div class="loyalty-pnt">
                        <span class="float-left text-theme">
                            <label class="container-check text-theme" for="">Loyalty points <br> earned
                                <input type="checkbox" class="custom-control-input">
                                <span class="checkmarks"></span>
                            </label>
                        </span>
                        <span class="float-right text-theme">$38</span>
                    </div>
                </li>
                <li>
                    <span class="float-left text-theme">Knosh wallet</span>
                    <span class="float-right text-theme">-$8</span>
                </li>
            </ul>
        </div> --}}
        @php 
        $class = $class;
        @endphp
        @include('frontend.coupons',[$class])
        <div class="w-container d-flex justify-content-between">
            <div>
                <input class="wallet-form-check ml-3" type="checkbox" name="use_wallet" id="wallet-check">
                <label class="wallet-form-check text-dark" for="wallet-check" id="use-wa-text">@if(isset($carts->used_wallet_amount) && $carts->used_wallet_amount != 0) Edit Wallet @else Use Wallet @endif</label>
            </div>
            <div>
                <input class="form-control" type="text" name="wallet_amount" id="w-checkbox" placeholder="Enter amount" value="{{ (isset($carts->used_wallet_amount) && $carts->used_wallet_amount != 0) ? $carts->used_wallet_amount : ''}}">
            </div>
            <div class="mr-3">
                <button class="btn btn-primary w-apply">Apply</button>
            </div>
        </div>
        <div class="checkout-grand-total-asw">
            <!-- <div class="d-flex justify-content-between tax py-1">
                <h2 class="text-theme mb-0">Loyalty points earned</h2> 
                <div class="">
                  <label class="container-check text-theme" for="loyalty_point">
                    Loyalty points earned
                    <input class="custom-control-input " type="checkbox" value="" id="loyalty_point">
                    <span class="checkmarks"></span>
                  </label>
                </div>
                <h2 class="text-theme mb-0">&#8377;185.50</h2>
            </div>
            <div class="d-flex justify-content-between  py-1 tax">
                <h2 class="text-theme mb-0">Knosh wallet</h2>
                <h2 class="text-theme mb-0">&#8377;185.50</h2>
            </div> -->
            @if(isset($carts->used_wallet_amount) && $carts->used_wallet_amount != 0) 
            <div class="d-flex w-100 justify-content-between" style="color:red;">
                <span class="font-montserrat">Used Wallet Amount</span>
                <span class="font-montserrat">- &#8377; {!! $carts->used_wallet_amount !!}</span>
            </div>
            @endif
            @if(isset($offers['promos']) && $carts->couponCode != '')
            <div class="d-flex w-100 justify-content-between" style="color:red;">
                <span class="font-montserrat">Discount offer</span>
                <span class="font-montserrat">- &#8377; {!! $tot_coup_val !!}</span>
            </div>
            @endif
            @if($carts->cart_detail[0]->vendor_info->type != 'event' && $carts->cart_detail[0]->vendor_info->type != 'home_event')
            <div class="d-flex w-100 justify-content-between">
                <span class="font-montserrat">Total Delivery Charges</span>
                <span class="font-montserrat">&#8377; {!! number_format($carts->DelCharge,2,'.','') !!}</span>
            </div>
            @endif
            @if($carts->tax > 0)
            <div class="d-flex w-100 justify-content-between">
                <span class="font-montserrat">Taxes</span>
                <span class="font-montserrat">&#8377; {!! number_format($carts->tax,2,'.','') !!}</span>
            </div>
            @endif
            @if(isset($carts->package_charge) && $carts->package_charge > 0 && $carts->cart_detail[0]->vendor_info->type != 'event')
            <div class="d-flex w-100 justify-content-between">
                <span class="font-montserrat">Package Charges</span>
                <span class="font-montserrat">&#8377; {!! number_format($carts->package_charge,2,'.','') !!}</span>
            </div>
            @endif 
            <div class="d-flex justify-content-between py-1">
                <h2 class="font-weight-bold mb-0 font-montserrat">Grand Total</h2>
                <h2 class="font-weight-bold mb-0 font-montserrat">&#8377; {!! number_format($carts->price,2,'.','') !!}</h2>
            </div>
        </div>
    </div>
</div>
@else

@endif