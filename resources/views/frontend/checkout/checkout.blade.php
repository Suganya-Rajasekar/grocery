@extends('main.app')
@section('content')
<style>
    .modal {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050;
    display: none;
    width: 100%;
    height: 100%;
    overflow: hidden;
    outline: 0;
    margin-top: 200px;
}
.btnedit {
    color: white;
    -moz-user-select: none;
    background: #f55a60 none repeat scroll 0 0;
    border: medium none;
    border-radius: 5px;
    cursor: pointer;
    display: inline-block;
    font-size: 15px;
    letter-spacing: 1px;
    line-height: 1;
    margin-bottom: 0;
    text-align: center;
    /* text-transform: uppercase; */
    touch-action: manipulation;
    transition: all 0.3s ease 0s;
    vertical-align: middle;
    white-space: nowrap;
    margin-left: 20px;
}
.account-active {
    border: 5px solid #e13452;
    animation: blink 2s infinite;
}
@keyframes blink {
    50% {
        border: 5px solid white;
    }
}
</style>
<section class="checkouts" style="margin-bottom: 100px;margin-top: 30px;">
    <div class="container-fluid checkout-asw pt-5">
        @if($carts->cart_detail)
        <div class="row">
            {{-- <div class="col-md-7 col-lg-5 d-block d-md-none">
                @include('frontend.checkout.showcart')
            </div> --}}
            <div class="col-md-6 col-lg-7">
                <div class="checkout-details-list">
                    @if(Session::has('errors'))
                    <div class="row">
                        <div class="col-12">
                            <p class="alert alert-danger">{!! Session::get('errors') !!}</p>
                        </div>
                    </div>
                    @endif
                     <?php  
                     $b= isset($carts->userdetails->name) ? $carts->userdetails->name : '';
                     $e= isset($carts->userdetails->email) ? $carts->userdetails->email : '';
                     $m= isset($carts->userdetails->mobile) ? $carts->userdetails->mobile : '';?>
                     <input type="hidden" class="pop" value="<?php echo ($b !='' && $e != '' && $m != 0) ? true : '';?>">
                    <ul>
                        <li class="account">
                            <span class="left-border"></span>
                            @if (\Auth::check())
                            <div class="check-head">
                                <span class="check-icon">
                                    <img src="assets/img/004-account.svg" alt="">
                                </span>
                                <h3 class=" font-opensans">Welcome, {!! \Auth::user()->name !!}!</h3>

                            </div>
                            @else
                            <div>
                            <div class="check-head">
                                <div class="check-icon">
                                    <img src="assets/img/004-account.svg" alt="">
                                </div>
                                <div>
                                    <h3 class="font-opensans">Account</h3>
                                    <p class="font-montserrat"></p>
                                </div>
                            </div>
                            <div class="acc-forms">
                                <div class="">
                                    <div class="regcon" style="display: none;">
                                        @include('frontend.checkout.chekout_register')
                                    </div>
                                    <div class="logincon" style="display: none;">
                                        @include('frontend.checkout.chekout_login')
                                    </div>
                                </div>
                            </div>
                            <div id="logreg" class="acc-btn logreg d-lg-flex d-md-block d-sm-flex">
                                <a href="javascript::void();" class="btn btn-theme font-montserrat font-weight-normal login">Have an account?<span> LOGIN</span></a>
                                <a href="javascript::void();" class="btn btn-theme font-montserrat font-weight-normal register">New user <span>REGISTER NOW</span></a>
                            </div>
                            </div>
                            @endif
                        </li>
                        <li class="delivery-add" @if($carts->cart_detail[0]->vendor_info->type == "event") style="display:none;" @endif>
                            <span class="left-border"></span>
                            <div class="allAddress @if(!empty($carts->selectedAdress)) d-none @endif">
                                @if (\Auth::check())
                                <div class="check-head">
                                    <span class="check-icon">
                                        <img src="assets/img/001-pin.svg">
                                    </span>
                                    <div>
                                        <h3 class="font-opensans">Select Your Delivery Address</h3>
                                    </div>
                                </div>
                                <div class="acc-forms">
                                    <div class="row my-4">
                                        @if(!empty($useraddr->addressDetail))
                                        @foreach($useraddr->addressDetail as $addr_k => $addr_v)
                                        <div class="col-lg-6 col-md-12 col-sm-12 my-3">
                                            <div class="shadow-box h-100">
                                                <h3 class="text-muted font-montserrat my-3"><i class="fas @if($addr_v->address_type == 'home'){!! 'fa-home' !!}@elseif($addr_v->address_type == 'office'){!! 'fa-briefcase' !!}@else{!! 'fa-map-marker-alt' !!}@endif"></i> {!! ucfirst($addr_v->address_type) !!}</h3>
                                                @if($addr_v->landmark)
                                                <h3 class="text-muted font-montserrat"> <b>Landmark : </b> {!! $addr_v->landmark !!} <br>
                                                </h3>
                                                @endif
                                                <p class="text-muted font-montserrat">{!! $addr_v->display_address !!}</p>
                                                <div class="acc-btn my-3">
                                                    <a href="javascript:;" class="btn btn-theme font-weight-normal font-montserrat" onclick="choose_address('{!! $addr_v->id !!}')">Deliver here</a>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach 
                                        @endif
                                        <div class="col-lg-6 col-md-12 col-sm-12 my-3">
                                            <div class="shadow-box h-100">
                                                <h3 class="text-muted my-3 font-montserrat">Add a new address</h3>
                                                <h3 class="text-muted font-montserrat"></h3>
                                                <div class="acc-btn my-3">
                                                    <a class="btn btn-theme address-asw font-weight-normal px-2 font-montserrat fn_map_modal" > Add a new address</a>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="latitude" id="lat" value="">
                                        <input type="hidden" name="longitude" id="lang" value="">
                                        <input type="hidden" name="addr" id="addr" value="">
                                    </div>
                                    <div class="address-asw-backdrop d-none"></div>
                                    @include('frontend.addresspopup')
                                </div>
                                @else
                                <div class="check-head">
                                    <span class="check-icon">
                                        <img src="assets/img/001-pin.svg">
                                    </span>
                                    <div>
                                       <h3 class="text-muted">Delivery Address</h3>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="selectedAddress @if(empty($carts->selectedAdress)) d-none @endif">
                                <div class="check-head">
                                    <span class="check-icon">
                                        <img src="assets/img/001-pin.svg">
                                    </span>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="font-opensans mb-0">Delivery Address</h3>
                                        <a href="javascript::void();" class="font-montserrat change_address">CHANGE</a>
                                    </div>
                                </div>
                                <div class="acc-forms">
                                    <div class="row my-4">
                                        <div class="col-lg-12 col-md-12 col-sm-12 my-3">
                                            <div class="shadow-box">
                                                @if(!empty($carts->selectedAdress))
                                                <h3 class="text-muted my-3 font-montserrat"><i class="fas @if($carts->selectedAdress->address_type == 'home'){!! 'fa-home' !!}@elseif($carts->selectedAdress->address_type == 'office'){!! 'fa-briefcase' !!}@else{!! 'fa-map-marker-alt' !!}@endif"></i> {{strtoupper($carts->selectedAdress->address_type)}}</h3>
                                                <h3 class="text-muted font-montserrat">
                                                    @if($carts->selectedAdress->landmark){!! '<b>Landmark : </b>'.$carts->selectedAdress->landmark.',</br>' !!}@endif @if($carts->selectedAdress->building){!! $carts->selectedAdress->building.',' !!}@endif @if($carts->selectedAdress->area){!! $carts->selectedAdress->area.',' !!}@endif @if($carts->selectedAdress->city){!! $carts->selectedAdress->city.',' !!}@endif @if($carts->selectedAdress->state){!! $carts->selectedAdress->state !!}@endif @if($carts->selectedAdress->pin_code){!! '- '.$carts->selectedAdress->pin_code !!}@endif
                                                </h3>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="payment">
                            <span class="left-border"></span>
                            <div class="check-head">
                                <span class="text-theme check-icon">
                                    <img src="assets/img/Payment.svg">
                                </span>
                            </div>
                            @foreach($carts->cart_detail as $k => $v)
                            @php
                            if($v->vendor_info->type != 'event' && $v->vendor_info->type != 'home_event') { 
                                $now            = ceil(time()/1800)*1800;
                                $chunks         = explode('-', $v->food_items[0]->time_s);
                                $open_time      = strtotime($chunks[0]);
                                $close_time     = strtotime($chunks[1]);
                            }
                            $un_available   = '';
                            @endphp
                            @if($v->vendor_info->type != 'event' && $v->vendor_info->type != 'home_event')
                                @if( ($v->vendor_info->Chef->avalability == 'not_avail') || ($v->food_items[0]->date < date("Y-m-d")) || (($v->food_items[0]->date < date("Y-m-d")) && ((($open_time >= $now) || ($open_time <= $now)) && ($now >= $close_time))))
                                @php
                                $un_available ='un_available';
                                @endphp
                                @endif
                            @else
                                @if($v->vendor_info->Chef->avalability == 'not_avail')
                                @php
                                $un_available ='un_available';
                                @endphp
                                @endif
                            @endif
                            @endforeach
                            <h3 class="text-muted font-opensans">Payment Option</h3>
                            @if(!empty($carts->selectedAdress) || ($carts->cart_detail[0]->vendor_info->type == "event" || $carts->cart_detail[0]->vendor_info->type == "home_event" && $un_available != 'un_available') && \Auth::check())
                            <div class="payment-tab">
                                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active font-montserrat" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Online payment</a>
                                    @if($_SERVER['HTTP_HOST'] == 'localhost' || \Auth::user()->id == 235)
                                        <a class="nav-link font-montserrat" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                                        Cash on delivery</a>
                                    @endif
                                </div>
                                <div class="tab-content mt-4" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                        <div class="online-payment">
                                            {{-- <div class="col-md-8 mb-3" style="margin: auto;">
                                                <div class="cod">
                                                    <span>Razor pay</span>
                                                    <span class="btn btn-theme px-4 py-2">pay &#8377;{!! $carts->price !!}</span>
                                                </div>
                                            </div> --}}
                                            @if($un_available != 'un_available' && \Auth::check())
                                            <div class="pay-btn">
                                                <form  action="{{ route('razorpay.payment.store') }}" method="POST" >
                                                    @csrf
                                                    <script src="{!! asset('assets/front/js/razorpay.js') !!}"
                                                    data-key="{{ \Config::get('razorpay')['RAZORPAY_KEY'] }}"
                                                    data-amount="{!! round($carts->price * 100) !!}"
                                                    data-buttontext="Place order & Pay"
                                                    data-name="Knosh"
                                                    data-description="Pay your order"
                                                    {{-- data-image="{{ url('assets/front/img/logo.svg') }}" --}}
                                                    data-prefill.name="{{ \Auth::user()->name }}"
                                                    data-prefill.email="{{ \Auth::user()->email}}" 
                                                    {{-- data-theme.color="#f55a60" --}}>
                                                    </script>
                                                </form>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if($_SERVER['HTTP_HOST'] == 'localhost' || \Auth::user()->id == 235)
                                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                        <div class="online-payment">
                                            <div class="paument-img my-4">
                                                <img src="{!! asset('assets/img/login-img.svg') !!}">
                                            </div>
                                            @if($un_available != 'un_available')
                                            <div class="pay-btn my-4">
                                                @if($b !='' && $e != '' && $m!=0)
                                                <a href="javascript:;" class="font-montserrat btn btn-theme cod_submit">Place order</a>
                                                @else
                                                <button type="button" class="font-montserrat btn btn-theme" id="oppop" data-toggle="modal" data-target="#myModal">Place order</button>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
            <div class="cart_overlay"></div>
            <div class="col-md-6 col-lg-5 cartData">
                @include('frontend.checkout.showcart')
            </div>
        </div>
        @else
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="p-3 no-cart text-center">
                    <img src="{{ asset('assets/front/img/nocart.png') }}" alt="no-cart"/ style="width: 200px;">
                    <h5 class="py-5 font-montserrat">Good food is always cooking! Go ahead, order some yummy items from the menu.</h5>
                    <a href="{{ url('continueredirect/popscroll') }}" class="font-montserrat btn btn-theme font-weight-normal">See chefs near you</a>
                </div> 
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog" style="margin-top: 0%;">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Please complete your profile</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="error"></div>
       <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" class="form-control name" value="@if (\Auth::check()) {!! \Auth::user()->name !!} @endif">
       </div>
       <div class="form-group">
        <label>Email</label>
        <input type="text" name="email" class="form-control email" value="@if (\Auth::check()) {!! \Auth::user()->email !!} @endif">
       </div>
       <div class="form-group">
        <label>Mobile Number</label>
        <input type="text" name="mobile" class="form-control mobile" value="@if (\Auth::check()) {!! \Auth::user()->mobile !!} @endif">
       </div>

      </div>
      <div class="modal-footer">
        <input type="submit" id="namesub" class="btn btn-primary">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@endsection
@section('script')
<script type="text/javascript"> const surl = "{!! route('login') !!}"; </script>
<script type="text/javascript" src="{{ asset('assets/front/js/main.js') }}"></script>
<script type="text/javascript" src="{!! asset('assets/front/js/checkout.js') !!}"></script>
@if(!(\Auth::check()))
<script type="text/javascript" src="{!! asset('assets/front/js/cloginregister.js') !!}"></script>
@endif
<script>
 $(document).on('click','#cart',function(e){
    e.preventDefault();    
     bootbox.confirm({
        message: "Are you sure? Want to clear your cart?",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if(result) {
             $('.cartform').submit();
        }
    }
    });
});
 $(document).on('click','.razorpay-payment-button',function(e){
    if($('.pop').val()==''){
    e.preventDefault(); 
    $('#myModal').modal('show');
    };
});

//  $(document).on('click','.oppop',function(e){
//        alert('hi');
//     if($('.pop').val()==''){
    
//     $('#myModal').modal('show');
//     };
// });

        $(document).on("click",'#namesub',function() {
            
        
        var name       = $('.name').val();
        var email      = $('.email').val();
        var mobile      = $('.mobile').val();
        
        var data = {};

        if(name != '')
            data['name'] = name;

        if(email != '')
            data['email'] = email;

        if(mobile != '')
            data['mobile'] = mobile;

        data['location_id'] = '91';
         
        if(name!=''){
            
        $.ajax({
            url     : 'checkout/insertname',
            type    : "POST",
            dataType: "json",
            data    : data,
            success:function(data){
            
            setTimeout(function(){location.reload()}, 1000);
        },
        error : function(err){ 
            $('#myModal').modal('show');
            var msg = err.responseJSON.message; 
            toast(msg, 'Oops!', 'error');
            //$('#error').text(msg);
        }
        });
    }
    
    });

    function loadDatepickerdeliveryslot(prep_time,cartid,chef_type = '') {
    var date = new Date();
    if(prep_time == 'preorder' || chef_type == "home_event_menu") {
        date.setDate(date.getDate() + 1);
    } else {
        date.setDate(date.getDate());
    }
    var enddate = new Date();
    enddate.setDate(enddate.getDate() + 10);

    var disabledates = $('#datepicker'+cartid).attr('data-offdates');

    $("#datepicker"+cartid).datepicker({
        format: 'yy-mm-dd',
        startDate: date,
        endDate: enddate,
        autoclose: true,
        todayHighlight: true,
        changeYear: false,
        changeMonth: false,
        maxViewMode: 0,
        datesDisabled: disabledates,
    }).on('changeDate', function(date) {
        var date = $(this).data('datepicker').getFormattedDate('yyyy-mm-dd');
        var id = $(this).attr('data-id');
        // $('#future_date_' + id).val(date);
        $('.datebtn').removeAttr("disabled");
        $('#edited_date'+cartid).val(date);
        timeslot(id, date);
    });
    $('[data-toggle="tooltip"]').tooltip();
    // outside click can't close modal popup
    $(".modal").modal({
        show: false,
        backdrop: 'static'
    });
}

function time_slot(id,preparationtime,cartid,action="cart_edit")
{
 var chef_type = $('#chef_type'+cartid).val();   
 loadDatepickerdeliveryslot(preparationtime,cartid,chef_type);
 $('#datepicker'+cartid).attr('data-id',id);
 $('#timeappend'+cartid).addClass('appendid' + id); 
 $('#action'+cartid).val(action);
 $('#old_date'+cartid).val($('.editdeliveryslot'+cartid).attr('data-olddate'));
 $('#old_timeslot'+cartid).val($('.editdeliveryslot'+cartid).attr('data-oldtime_slot'));
 $('#ResID').val($('.editdeliveryslot'+cartid).attr('data-resid'));
 $('#CartId').val($('.editdeliveryslot'+cartid).attr('data-cartid'));  
}

function timeslot(id, date) {
    $.ajax({
        type: 'POST',
        url: base_url + 'timeslot',
        data: { id: id, date: date },
        success: function(data) {
            $('.appendid' + id).html(data);
        },
        error: function(err) {}
    });
}

function date_time_confirm(cart_id) {
    $('#exampleModaldelivery_time'+cart_id).modal('hide');
    var new_timeslot = $("input[name='timeslot']:checked").val();
    var old_date     = $('#old_date'+cart_id).val();
    var old_timeslot = $('#old_timeslot'+cart_id).val();
    var res_id       = $('#ResID').val();
    var new_date     = $('#edited_date'+cart_id).val();
    var action       = $('#action'+cart_id).val();
    var home_event   = $('#home_event').val();
    if(home_event == 'yes') {
        $('#exampleModaldate'+cart_id).modal('hide');
    }
    $.ajax({
        url: base_url + 'deliveryslot_change',
        type: 'PATCH',
        data: {cart_id : cart_id,res_id: res_id, old_date : old_date, old_timeslot : old_timeslot, new_date : new_date, new_timeslot : new_timeslot,action : action},
        success:function(res){
            if (res != '') {
                $('.cartData').html(res);
            }
       },
       error: function(err) {
        $('.modal').modal('hide');
        var msg = err.responseJSON.message;
        toast(msg, 'Oops!..Something went wrong!', 'error');
    }

    });
}
</script>   
@endsection