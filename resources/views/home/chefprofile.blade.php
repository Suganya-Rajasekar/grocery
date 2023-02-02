@extends('main.app')
@section('content')
    <div class="background" style="margin-top: 111px;">
        <div class="row">
            <div class="col-md-6 m-auto">
                <div class="chefdetails my-3" style="background: white;">
                    <div class="d-flex">
                        <div class=" ">
                            <div class="chefimg">
                                <img src="{{$chefinfo->avatar}}" alt="">
                            </div> 
                        </div>
                        @if(  isset($chefinfo) )
                        <div class="w-100 ml-3">
                            <div class="o-hid">
                                <div class="float-left">
                                    <div class="chefname">
                                        <h2 class="text-black font-weight-bold">{{$chefinfo->name}}</h2>
                                        <h4 class="text-muted">
                                            @foreach( $chefinfo->cuisines as $c1 => $c2)
                                            <span class="home-span">{{ $c2->name }}@if(!$loop->last), @endif
                                            </span>
                                            @endforeach
                                        </h4>
                                    </div>
                                </div>
                                <div class="float-right">
                                    <div class="tag-ribbon">
                                        <a href="javascript:void(0)" onclick="updateBookmark({{ $chefinfo->id }})"><span class="@if($chefinfo->is_bookmarked == 1) fa fa-bookmark @else fa fa-bookmark-o @endif"></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="o-hid">
                                <div class="float-left">
                                    <div class="sqr-star mt-3">
                                        <div class=" star-rating ">

                                            <div class="overflow-hidden">
                                                <div class="float-left">
                                                    @for ($i = 0; $i < $chefinfo->ratings; $i++)        
                                                    <label for="condition_5" class="star-rating-star js-star-rating">
                                                        <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                                    </label>
                                                    @endfor

                                                </div>
                                                <span class="star-points text-black">4.5 </span>
                                                <div class="float-right">&nbsp;({{$chefinfo->reviewscount}} Reviews)</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pricefornos">
                                    <h4>@if( isset($chefinfo->restaurantDetails->data[0]) ) ${{$chefinfo->restaurantDetails->data[0]->budget}}0 for  two @endif</h4>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

            </div>
            <div class="col-md-6 col-lg-7">
                <?php //echo "<pre>"; print_r($chefinfo->restaurantDetails->data[0]->get_approved_food_items);echo "</pre>";?>
                <?php //echo "<pre>"; print_r($timeslots);echo "</pre>";?>
                @if(count($chefinfo->restaurantDetails->data) > 0)
                @foreach($chefinfo->restaurantDetails->data[0]->get_approved_food_items as $k => $v)
                <div class="food-detailed-lists mb-5">
                    <div class="foodsdetails">
                        <div class="foodimgss">
                            <a href="#" data-toggle="modal" data-target="#profilemodal{{$v->id}}" onclick="menuinfo('{{$v->id}}')"><img src="{{$v->image}}" alt=""></a>
                        </div>
                    </div>
                    <div class="chefdetails overflow-hidden mt-3">
                        <div class="float-left">
                            <ul>
                                <li>
                                    <a href="javascript:void(0)" onclick="updateFavorites( {{ $v->id }} )"><span class="@if($v->is_favourites == 1) fa fa-heart @else fa fa-heart-o @endif"></span></a>
                                </li>
                                <li><a href="#" data-toggle="modal" data-target="#profilemodal{{$v->id}}" onclick="menuinfo('{{$v->id}}')"><span class="fa fa-comment"></span></a></li>
                                <li><a href="#"><span class="fa fa-share-alt"></span></a></li>
                            </ul>
                        </div>
                        <div class="float-right text-right">
                            <a href="#" class="btn btn-theme btn-small bordered-small-button" data-toggle="modal" data-target="#exampleModal{{$v->id}}">Add</a>
                            <br>
                            <span>@if(count($v->addons) > 0) Customizable @endif</span>
                        </div>
                        <!--modal start food-->
                        <div class="modal fade" id="exampleModal{{$v->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header pinkbox">
                                        <h5 class="modal-title" id="exampleModalLabel">
                                            <span class="foodname">{{$v->name}}</span>
                                            <div class="itembuttn" id="fnitem_<?php echo $v->id; ?>">
                                                <span class="add_dec_option remove_item" data-fid="{{$v->id}}" data-type="more_details" data-isad="">-</span>
                                                <h5 class="item-count afid_{{$v->id}}" id="afid_{{$v->id}}" data-id="{{$v->id}}">1</h5>
                                                <span data-fid="{{$v->id}}" class="add_dec_option add_item" data-isad="" data-id="{{$v->id}}" data-type="more_details">+</span>
                                            </div>
                                        </h5>
                                        @php
                                        $first_unit='';
                                        @endphp
                                        @if($v->unit_detail)

                                        @php
                                        $first_unit=$v->unit_detail[0]->id;
                                        @endphp
                                        @endif  
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-unit="{{$first_unit}}" data-id="{{$v->id}}">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" id="form1">
                                            @php
                                            $first_unit='';
                                            @endphp
                                            @if($v->unit_detail)

                                            @php
                                            $first_unit=$v->unit_detail[0]->id;
                                            @endphp
                                            <div class="addon-sec over-hid">
                                                <div class="modalbody-head">
                                                    <h5 class="text-black font-weight-bold">Unit</h5>
                                                </div>
                                                <div class="cont" style="line-height: 2;">
                                                    <ul class="over-hid">

                                                        @foreach($v->unit_detail as $ke => $unit_val)
                                                        <li>
                                                            <div class="float-left">
                                                                <div class=" custom-checkbox">
                                                                    <label class="container-check" for="">{{ $unit_val->name }}
                                                                        <input type="radio" class="custom-control-input unit" name="unit" value="{{ $unit_val->id }}" id="unit{{$v->id}}" data-id="{{$v->id}}" @if($ke==0)checked @endif>
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="float-right">
                                                                <div class="foodprice">${{ $unit_val->price }}</div>
                                                            </div>
                                                        </li>
                                                        @endforeach

                                                    </ul>
                                                </div>
                                            </div>
                                            @endif
                                            @if($v->addons)
                                            <div class="addon-sec over-hid">
                                                <div class="modalbody-head">
                                                    <h5 class="text-black font-weight-bold">Addons</h5>
                                                </div>
                                                <div class="cont" style="line-height: 2;">
                                                    <ul class="over-hid">

                                                        @foreach($v->addons as $ke => $addon_val)
                                                        <li>
                                                            <div class="float-left">
                                                                <div class=" custom-checkbox">
                                                                    <label class="container-check" for="">{{ $addon_val->name }}
                                                                        <input type="checkbox" class="custom-control-input addon" name="addon" value="{{ $addon_val->id }}" id="addon{{$v->id}}" data-id="{{$v->id}}">
                                                                        <span class="checkmarks"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="float-right">
                                                                <div class="foodprice">${{ $addon_val->price }}</div>
                                                            </div>
                                                        </li>
                                                        @endforeach

                                                    </ul>
                                                </div>
                                            </div>
                                            @endif
                                        </form>

                                        @php
                                        $hours=date(('H'),strtotime($v->preparation_time));
                                        @endphp
                                        {{--$hours--}}
                                    </div>
                                    <div class="modal-footer">
                                        @if($hours<=20)
                                        @php
                                        $today_date=date('Y-m-d');
                                        @endphp
                                        <span data-toggle="modal" data-target="#exampleModaldelivery_time{{$v->id}}" data-dismiss="modal">
                                            <button type="button" class="btn btn-secondary">Order for today</button>
                                        </span>
                                        @else
                                        @php
                                        $today_date='';
                                        @endphp

                                        <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="This food preferences not order for today">Order for today</button>

                                        @endif
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModaldate{{$v->id}}" data-dismiss="modal">Order for future</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--modal end-->
                        <!-- <a href="#" class="btn btn-theme btn-small bordered-small-button" data-toggle="modal" data-target="#exampleModaldate">datepicker modal</a> -->
                        <!--modal start choose date-->
                        <div class="modal fade" id="exampleModaldate{{$v->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header pinkbox">
                                        <h5 class="modal-title" id="exampleModalLabel">
                                            <span class="foodname">Order for future</span>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-unit="{{$first_unit}}" data-id="{{$v->id}}">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="addon-sec over-hid">
                                            <div class="modalbody-head">
                                                <div id="datepicker" name='datepicker_start' class="datepicker" data-id="{{$v->id}}"></div>
                                                <input type="hidden" name="test" value="10">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary">cancel</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModaldelivery_time{{$v->id}}" data-dismiss="modal">confirm</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--modal end choose date-->
                        <!--modal start delivery time-->
                        <div class="modal fade" id="exampleModaldelivery_time{{$v->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header pinkbox">
                                        <h5 class="modal-title" id="exampleModalLabel">
                                            <span class="foodname">Delivery time slots</span>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-unit="{{$first_unit}}" data-id="{{$v->id}}">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="height: 500px;">
                                        <form action="" id="form3">
                                            @if($timeslots)
                                            @foreach($timeslots as $slot_k => $slot_val)
                                            <div class="addon-sec over-hid">
                                                <div class="modalbody-head">
                                                    <h5 class="text-black font-weight-bold">{{ $slot_val->name }}</h5>
                                                </div>
                                                <div class="cont" style="line-height: 2;">
                                                    <ul class="over-hid">
                                                        @if($slot_val->slots)
                                                        @foreach($slot_val->slots as $time_k => $time_val)
                                                        @php
                                                        $now = time();
                                                        $chunks = explode('-', $time_val->time_slot);
                                                        $open_time = strtotime($chunks[0]);
                                                        $close_time = strtotime($chunks[1]);

                                                        @endphp
                                                        {{--$open_time--}}
                                                        {{--$close_time--}}
                                                        {{--$now--}}
                                                        <li>
                                                            <div class="float-left">
                                                                @if((($open_time <= $now) || ($open_time >= $now)) && ($now <= $close_time))
                                                                <div class=" custom-checkbox">
                                                                    <label class="container-check" for="">{{ $time_val->time_slot }}
                                                                        <input type="radio" class="custom-control-input timeslot" name="timeslot" value="{{ $time_val->id }}" id="timeslot{{$v->id}}" data-id="{{$v->id}}">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </div>
                                                                @else
                                                                <div class=" custom-checkbox">
                                                                    <label class="container-check" for="">{{ $time_val->time_slot }}
                                                                    </label>
                                                                </div>
                                                                @endif
                                                            </div>

                                                        </li>
                                                        @endforeach
                                                        @endif 
                                                    </ul>
                                                </div>
                                            </div>
                                            @endforeach 
                                            @endif
                                        </form>

                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" id="addon_item_{{$v->id}}" name="addon_item" value="">
                                        <input type="hidden" id="future_date_{{$v->id}}" name="future_date" value="{{$today_date}}">
                                        <input type="hidden" id="time_slot_{{$v->id}}" name="deliveytime_slot" value="">
                                        <input type="hidden" id="unit_item_{{$v->id}}" name="unit_item" value="{{$first_unit}}">
                                        <button type="button" class="btn btn-secondary">cancel</button>
                                        <button type="button" class="btn btn-primary lastconfirm" data-id="{{$v->id}}" onclick="lastresult('{{$v->id}}')">Confirm</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--modal end delivery time-->
                        <!-- modal start profile popup-->
                        <div class="modal fade" id="profilemodal{{$v->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">

                                    <div class="modal-body" id="commentbox{{$v->id}}">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- modal end profile popup-->
                    </div>
                    <div class="foodstat">
                        <ul class="prep-time">
                            <li class="font-weight-bold">
                                <span class="float-left">{{$v->name}}</span>
                                <span class="float-right">${{$v->price}}</span>
                            </li>
                            <li>
                                <span class="float-left">Preperation time</span>
                                <span class="float-right">{{$v->preparation_time_text}}</span>
                            </li>
                        </ul>
                        <div class="details">
                            <h4>Details</h4>
                            <p class="text-muted muted-font">{{ strip_tags($v->description) }}</p>
                        </div>
                        <div class="likesandcomment">
                            <p class="likes">{{$v->is_favourites}} Likes</p>
                            <p class="text-muted muted-font">view all 25 comment</p>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
            <div class="col-md-6 col-lg-5">
                <div class="carts">
                    <div class="shadow-box p-0">
                        <div class="pinkbox text-center">
                            <h3 class="font-weight-bold">
                                Cart
                            </h3>
                        </div>
                        <div class="cart-cont">
                            <ul class="foodlist">
                                <li>
                                    <div class="foodandprice" style="overflow: hidden;">
                                        <span class="float-left">Biriyani</span>
                                        <span class="float-right text-theme">$10</span>
                                    </div>
                                    <div class="quantityinput"></div>
                                    <div class="extra">
                                        <span class="float-left text-muted">Pepsi</span>
                                        <span class="float-right text-muted text-theme">$1</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="foodandprice" style="overflow: hidden;">
                                        <span class="float-left">Fried rice</span>
                                        <span class="float-right text-theme">$10</span>
                                    </div>
                                    <div class="quantityinput"></div>

                                </li>
                            </ul>
                            <ul class="price-list">
                                <li><span class="float-left text-theme font-weight-bold">Sub Total</span>
                                    <span class="float-right text-theme font-weight-bold">$10</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script>
    $(function() {
        var date = new Date();
        date.setDate(date.getDate()+1);
        $( ".datepicker" ).datepicker({
            format: 'yy-mm-dd',
            startDate: date,
            // todayHighlight: true,
            autoclose: true,
        }).on('changeDate', function(date){
            var date=$(this).data('datepicker').getFormattedDate('yyyy-mm-dd');
            var id = $(this).attr('data-id');
            $('#future_date_'+id).val(date);
        });
        //get addon value
        $('[data-toggle="tooltip"]').tooltip();
        $(".addon").click(function(){
            var id = $(this).attr('data-id');
            var ids="#addon"+id;
            var re=$(ids+":checked").map(function () {
                return this.value;
            }).get().join(",");
            $('#addon_item_'+id).val(re);
        });
        // get unit value
        $(".unit").click(function(){
            var id = $(this).attr('data-id');
            var ids="#unit"+id;
            var radioValue = $(ids+":checked").val();
            if(radioValue){
                $('#unit_item_'+id).val(radioValue);
            }
        });
        //get timeslot value
        $(".timeslot").click(function(){
            var id = $(this).attr('data-id');
            var ids="#timeslot"+id;
            var radioValue = $(ids+":checked").val();
            if(radioValue){
                $('#time_slot_'+id).val(radioValue);
            }
        });
        $(".modal").modal({
            show: false,
            backdrop: 'static'
        });
        // unit default value set modal close 
        $(".close").click(function(){
            var unit = $(this).attr('data-unit');
            var id = $(this).attr('data-id');
            $('#unit_item_'+id).val(unit);

        });
    });
    //increate quantity value
    $(document).on("click",'.add_item',function(){
        var adon_type=$(this).attr('data-type');
        var isad = $(this).attr('data-isad');
        var count = $(this).closest("div").find('.item-count').text();
        var item = $(this).closest("div").attr("id");
        var item_array = item.split("_"); 
        var new_count = (parseInt(count) + 1);
        var iItemId = item_array[1];
        $('#afid_'+iItemId).text(parseInt($('#afid_'+iItemId).text())+1);
        $(this).closest("div").find('.item-count').text(parseInt(count) + 1);
    });

    //increate decrease value
    $(document).on("click",'.remove_item',function(){
        var adon_type = $(this).attr("data-type");
        var is_ad = $(this).attr("data-isad");

        var count = $(this).closest("div").find('.item-count').text();
        if(count > 0){

            var item = $(this).closest("div").attr("id");

            var item_array = item.split("_");
            var new_count = (parseInt(count) - 1);

            $('#afid_'+item_array[1]).text(parseInt($('#afid_'+item_array[1]).text())-1);
            if(new_count == 0){
                $(this).closest("div").find('.item-count').text(1);
            } else {
                $(this).closest("div").find('.item-count').text(new_count);
            }


        }
    });

    //pass items ajax function 
    function lastresult(id) {
        var addon   = $('#addon_item_'+id).val();
        var date    = $('#future_date_'+id).val();
        var timeslot= $('#time_slot_'+id).val();
        var quantity= $('#afid_'+id).text();
        var unit    = $('#unit_item_'+id).val();
        $.ajax({
            type    : 'POST',
            url     : base_url+'sendfooditems',
            data    : { id:id, addon:addon, date:date, timeslot:timeslot, quantity:quantity, unit:unit },
            success : function(data) {
                $('.modal').modal('hide');
                var msg = JSON.parse(JSON.stringify(data));
                // $(".error-message-area") .css("display","block");
                // $(".error-content").css("background","#9cda9c");
                // $(".error-msg").html("<b style='color:black'>"+msg.message+"</b>");
                setTimeout(function(){location.reload()}, 1000);
            },
            error   : function(err) {
                $('.modal').modal('hide');
                var msg = err.responseJSON.message;
                // $(".error-content").css("background","#ED4956");
                // $(".error-message-area").find('.error-msg').text(msg);
                // $(".error-message-area").show();
            }
        });
    }

    $('.modal').on('hidden.bs.modal', function () {
        /*$('.modal form')[0].reset();
        $('.modal form')[1].reset();
        $('.modal form')[2].reset();*/
        $(this).find('form').trigger('reset');
        $(this).find('form #datepicker').datepicker('update','');
        $('.item-count').text(1);
         //$('input[name=unit_item]').val('');
        //resetEverything();
    });

    //menu info ajax
    function menuinfo(id){
        $.ajax({
            type : 'POST',
            url : base_url+'menufood',
            data : {id:id},
            success:function(data){
                console.log(data.html);
                $('#commentbox'+id).html(data.html);
            },
            error : function(err){ 
            }
        });
    }
</script>
@endsection