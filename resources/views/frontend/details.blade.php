@extends('main.app')
@section('content')
<input type="hidden" id="Page" value='1'>
<div class="cart-backdrop d-none"></div>
<link href="{!! asset('assets/front/css/detail.css') !!}" rel="stylesheet"/>
<div class="chef-container-fluid">
    <div class="background chef-asw" style="margin-top: 111px;">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="chefdetails my-
                3" style="background: white;">
                    <div class="d-flex">
                        <div class=" ">
                            <div class="chefimg">
                                <img src="{!! $chefinfo->avatar !!}" alt="">
                                @if($chefinfo->certified == 'yes')
                                <div class="ribbon1 up">
                                    <div class="content">
                                        <img src="{{ asset('assets/front/img/vegan.png') }}">
                                    </div>
                                </div>
                                @endif
                                @if($chefinfo->promoted == 'yes')
                                <div class="cor-height-top-ad">
                                    <span>
                                        AD
                                    </span>
                                </div>
                                @endif
                                @if($chefinfo->celebrity == 'yes')
                                <div class="ribbon down">
                                    <div class="content fas fa-star"></div>
                                </div>
                                @endif
                            </div> 
                        </div>
                        @if( isset($chefinfo) )
                            @include('frontend.details-chef')
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if($chefinfo->avalability != 'avail' && $chefinfo->type != 'event' && $chefinfo->type != 'home_event')
        <div class="d-flex content-wrap alert alert-danger pb-2">
            <div class="col-8 my-auto pl-0">
                <p class="text-end mb-0" style="color: #721c24;">Currently not accepting orders</p>
            </div>
            <?php ;?>
            @if(\Auth::check())    
            <div class="form-check ml-2 col-4 pr-0 my-auto">
                <div class="d-flex justify-content-end">
                    <input class="form-check-input" type="checkbox" name="notify" id="notifyme" value="notifyme" data-userid="{{ \Auth::user()->id }}" data-chefid="{{ $chefinfo->id }}" @if(!empty($notify) && $notify->status == 1 && $notify->id != 0) checked data-notifyid="{{ $notify->id }}" data-action="DELETE" @endif> 
                <label class="form-check-label" for="notifyme" style="color: black;">Notify Me</label>
                </div>

            </div> 
            @endif   
        </div>    
        @endif 
    </div>
    <div class="nav-tab-div">
        @include('frontend.details-tabs')
    </div>

    <div class="tab-content chef-asw-tab-content">
        @if(!request()->section)
        @include('frontend.all_dishes')
        @endif
        @include('frontend.reviews')
        <div class="dish_and_review_div"></div>
        @foreach($chefinfo->chef_restaurant->categories as $c1 => $c2)
        <div id="{{$c2->id}}" class="tab-pane @if(request()->section==$c2->id) active @endif">
            <section class="chef-asw">
                <div class="container-fluid">
                    <div class="">
                        @if(request()->section==$c2->id)
                        <div class="">
                            @if(count($c2->menuitems) > 0)
                            @php
                            $chefdish = $c2->menuitems;
                            $type = 'category';
                            $cat_id=$c2->id;
                            @endphp
                            @include('frontend.details-dish',[$chefdish,$type])
                            @endif

                        </div>
                        @endif
                        <div class="">
                            <div class="carts chef-container-fluid detailData">
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
        @endforeach
        <div class="paginate"></div>
        <div class="container-fluid"> 
            @if(!(\Request::has('Page')) && $chefinfo->chef_restaurant->categories  != '' ) 
            <button class="btn btn-default col-md-12 loadModuleDetails"  name="chef/{{ $chefinfo->id }}" id="@if(app('request')->input('section') == 'review') review @else dishes @endif  " type="button">Load More</button>
        </div>
        @endif
    </div>
    @if($chefinfo->chef_restaurant->fssai != '' && $chefinfo->chef_restaurant->fssai != null)
    <div class="container-fluid mt-5">
        <div class="fssai_img pt-4">
            <img src="{{ asset('assets/front/img/fssai.png') }}">
            <p class="text-muted mt-3">Lic No. {!! $chefinfo->chef_restaurant->fssai !!}</p>
            <p class="mt-5">Prices of all items on this menu are directly controlled by the chef and not by {!! env('APP_NAME') !!}.</p>
        </div>
    </div>
    @endif
</div>
<style>
    .foodimgss img {
        width: 100%;
        border-radius: 25px;
        max-height: 750px;
        object-fit: cover;
    }
    .chefdetails .float-left ul li {
        display: inline-block;
        padding-right: 10px;
    }
    .chefdetails .float-left ul li a {
        font-size: 30px;
    }
    .bordered-small-button{
        border-radius:30px !important;
    }
    .prep-time li,.over-hid li{
        overflow:hidden;
    }
    .prep-time li span {
        font-size: 16px;
    }
    .muted-font{
        font-size: 18px;
    }
    .prep-time {
        line-height: 1.9;
    }
    .details {
        padding: 16px 0;
    }
    .details h4,.likesandcomment p.likes,.f-24 {
        font-size: 16px;
    }
    .pinkbox{
        background:#fff2f4;
        padding: 20px 10px 20px 20px;
    }
    .cart-cont{
        padding: 20px 0px; 
    }
    .comment-cont h4{
        font-size: 16px;
    }
    .comment-cont .chefdetails p{
        font-size: 14px;
    }
    .tooltip {
        z-index: 100000000; 
    }
</style>
@endsection

@section('script')
<script src="{{ asset('assets/front/js/main.js') }}"></script>
<script src="{{ asset('assets/front/js/details.js') }}"></script>
@if (\Session::has('food_id'))
<script type="text/javascript">
    $(document).ready(function(){
        var food_id="{{Session::get('food_id')}}";
        $("#profilemodal"+food_id).modal();
        menuinfo(food_id);
    });
</script>
{{ Session::forget('food_id') }}
{{ Session::forget('vendor_id') }}
@endif

@if (\Session::has('add_food_id'))
<script type="text/javascript">
    $(document).ready(function(){
        var add_food_id="{{Session::get('add_food_id')}}";
        //$("#exampleModal"+add_food_id).modal();
        var target = $('#'+add_food_id);
        if (target.length) {
        $('html,body').animate({
        scrollTop: target.offset().top-140
        }, 1000);
        return false;
        }
    });
</script>
{{ Session::forget('add_food_id') }}
{{ Session::forget('add_vendor_id') }}
@endif
<script>
    function timeupdate(id){
        var tim = $('#time_slot_'+id).val();
        $('.datatime'+id).attr("data-myval",tim);
    }
    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() >= $(document).height()) {
            var last_id = $(".post-id:last").attr("id");
            var chef_id = $(".post-id:last").attr("data-id");
            var type = $(".post-id:last").attr("data-type");
            // alert($(".tab-pane").attr("id"));
            // loadMoreData(last_id,chef_id,type);
        }
    });

    function loadMoreData(last_id,chef_id,type){
        $.ajax({
            url: base_url+'loadMoreDataurl',
            type: 'POST',
            data : {last_id:last_id,chef_id:chef_id,type:type},
            beforeSend: function(){
                $('.ajax-load').show();
            }
        }).done(function(data){
            $('.ajax-load').hide();
            $("#post-data").append(data);
        }).fail(function(jqXHR, ajaxOptions, thrownError){
            alert('server not responding...');
        });
    }
    /*$(function() {
        var target = $('#1');
        if (target.length) {
            $('html,body').animate({
                scrollTop: target.offset().top-140
            }, 1000);
            return false;
        }

    });*/
    var base_url = "<?php echo URL::to('/').'/'; ?>";
    $(document).on('click', '.loadModuleDetails', function(){
        var module = $(this).attr('name');
        var id = $.trim($(this).attr('id'));
        var offsetValue = $("#Page").val();
        offsetValue = parseInt(offsetValue )+1;
        $("#Page").val(offsetValue);
        var page = {menupage:  offsetValue};
        module = module+'?section='+id;
        $.ajax({
            url: base_url + module,
            type: "get",
            dataType: "json",
            async: true,
            data:  page,
            success: function(data) { 
                if (data.recordCount == 0){
                    $('.loadModuleDetails').hide();
                }
                else {
                $(".paginate").append(data.app); 
                $('.owl-bookmark,  .owl-fav').owlCarousel({
                    loop:false,
                    margin:10,
                    nav:false,
                    dots:false,
                    responsive:{
                        0:{
                            items:1
                        },
                        600:{
                            items:2
                        },
                        850:{
                            items:3
                        },
                        1400:{
                            items:4
                        }
                    }
                });
                loadDatepickerDish();
            }
        }
        });
    });

    $(document).on('click','#notifyme',function(){
        var userid      = $(this).attr('data-userid');
        var chefid      = $(this).attr('data-chefid');
        var method      = $(this).attr('data-action');
        var notifyid    = $(this).attr('data-notifyid');
        var data   = (method == 'DELETE') ? {id : notifyid} : {vendor_id : chefid , user_id : userid};
        if(method == undefined){
            method = 'POST';
        }
        $.ajax({
            url: base_url+'notifyme',
            type: method,
            data: data,
            success:function(res){
                if(res){
                    $(".error-message-area").css("display","block");
                    $(".error-content").css("background","#d4d4d4");
                    $(".error-msg").html("<p style='color:red' class='mb-0'>"+res.message+"</p>");
                    setTimeout(function(){location.reload()},2000);
                }
            }
        });
    });
</script>
@endsection