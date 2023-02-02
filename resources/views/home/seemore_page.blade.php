<!-- For chefs start-->
<style type="text/css">
.l-con:before {
    position: absolute;
    bottom: -7px;
    content: "";
    width: 0;
    height: 0;
    border-style: solid;
    border-left-color: transparent;
    border-bottom-color: transparent;
    border-right-color: cornflowerblue;
    left: 0;
    border-width: 9px 0 0 9px;
}

.recommend:before {
    border-top-color: #28a745;
}
.bestsell:before {
    border-top-color: #007bff;

}
.mpopular:before {
    border-top-color: #dc3545;
}
.mpopular,.recommend {
    left:7px;
    padding: 5px;
}
</style>
@if(count($seemore) > 0 && $module != 'popular_near_you')
<input type="hidden" value="{!! \Request::segment('3') !!}" id="latitu">
<input type="hidden" value="{!! \Request::segment('4') !!}" id="longitu">
<input type="hidden" value="1" id="pagecount">
@foreach($seemore as $k => $v)
<div class="container-fluid">
<div class="chef-lists mb-5 seemore-asw">
    <div class="chefdetails my-3">
        <div class="d-flex">
            <div class=" ">
                <div class="chefimg">
                    <a href="{!!url('/chef/'.$v->id)!!}" ><img src="{{$v->avatar}}" alt="">
                        @if($v->certified == 'yes')
                        <div class="ribbon1 up">
                            <div class="content">
                                <img src="{{ asset('assets/front/img/vegan.png') }}">
                            </div>
                        </div>
                        @endif
                        @if($v->promoted == 'yes')
                        <div class="cor-height-top-ad">
                            <span>
                                AD
                            </span>
                        </div>
                        @endif
                        @if($v->celebrity == 'yes')
                        <div class="ribbon down">
                            <div class="content fas fa-star"></div>
                        </div>
                        @endif
                    </a>
                </div> 
            </div>
            <div class="ml-1 ml-sm-3 w-100 seemore-asw-chefname">
                <div class="o-hid justify-content-between d-flex justify-content-md-start">
                    <div class="w-asw-85">
                        <div class="chefname">
                            <a href="{!!url('/chef/'.$v->id)!!}" ><h2 class="text-black d-inline-block font-opensans font-weight-bold">{{$v->name}}{{-- <span class="offer-chef">20% OFF</span> --}}</h2></a>
                            <?php //echo "<pre>";print_r($v->get_vendor_food_details[0]->restaurant->adrs_line_1);exit(); ?>
                            @if(isset($v->type) && $v->type == 'event' && isset($v->get_vendor_food_details[0]))
                            <div class="text-muted">
                                <span><b> Location:</b>{{ $v->get_vendor_food_details[0]->restaurant->adrs_line_1 }}</span>
                            </div>
                            <div class="text-muted mt-3 mb-3">
                                <span><b>Event date & time:</b>{!! date('d-m-Y',strtotime($v->get_vendor_food_details[0]->restaurant->event_time[0])).' <b>/</b> '.$v->get_vendor_food_details[0]->restaurant->event_time[1] !!}</span>

                            </div>
                            @endif
                            <h4 class="elipsis-text font-montserrat text-muted">
                                <span>
                                    @foreach( $v->cuisines as $c1 => $c2)
                                    {{ strip_tags($c2->name) }}@if(!$loop->last), @endif
                                    @endforeach
                                </span>
                            </h4>
                            <p class="d-none d-md-block font-montserrat read-more-cont text-justify">{{ strip_tags($v->description) }}</p>
                            <span class="read-more">Read more</span>
                        </div>
                    </div>
                    <div class="">
                        <div class="tag-ribbon">
                            <a href="javascript:void(0)" onclick="updateBookmark( {{ $v->id }} )"><span class="@if($v->is_bookmarked == 1) fa fa-bookmark @else fa fa-bookmark-o @endif"></span></a>
                        </div>
                    </div>
                </div>
                @if(isset($v->type) && $v->type != 'event')
                <div class="o-hid d-flex justify-content-between">
                    <div class="w-asw-85">
                        <div class="sqr-star">
                            <div class=" star-rating ">

                                <div class="overflow-hidden profile-asw-rate elipsis-text">
                                    <div class="">
                                        @for($x=1;$x<=$v->ratings;$x++)
                                            <label class="star-rating-star js-star-rating">
                                                <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                            </label>
                                            @endfor
                                            @if (strpos($v->ratings,'.'))
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
                                        @if($v->ratings)
                                        <div>
                                            <span class="star-points text-black">{{$v->ratings}}</span>
                                            <div class="font-montserrat">({{$v->reviewscount}} Reviews)</div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mt-2 mt-sm-0">
                            <div class="pricefornos">
                                {{-- <h4 class="text-theme font-montserrat">&#8377;{{$v->budgetName}}</h4> --}}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="chefsfood seemore-asw-food">
            @if($v->get_vendor_food_details)
            <div class="owl-carousel see-more owl-theme">
                @foreach($v->get_vendor_food_details as $food_k => $food_val)
                <div class="item" data-merge="2">
                    <div class="flip-box" data-id="{{ $food_val->id }}" id="flipbox{{ $food_val->id }}">
                        <div class="flip-box-inner">
                            <div class="flip-box-front px-2" id="flipfront{{ $food_val->id }}" data-id="{{ $food_val->id }}">
                                <div class="foodimg" style="overflow:visible;">
                                 <?php $tag = tags_status($food_val->tag_type);?>
                                 <div class="tags{{ $food_val->id }}">
                                 @if($tag['none'] == 0)  
                                 @if($tag['bestsell'] == 1)
                                 <span class="badge badge-primary bestsell position-absolute l-con" id="see_bestsell" style="left:1px;padding: 5px;z-index:999;font-size: 12px;">Bestseller</span>
                                 @endif
                                 @if($tag['special'] == 1)
                                 <span class="badge badge-danger mpopular position-absolute l-con" id="see_mpopular" @if($tag['bestsell'] == 1) style="top:30px;left: 1px;z-index: 999;font-size: 12px;" @endif>Chef's special</span>
                                 @endif
                                 @if($tag['must_try'] == 1)
                                 <span class="badge badge-success recommend position-absolute l-con" id="see_recommend" @if($tag['bestsell'] == 1 || $tag['special'] == 1) style="top:30px;left: 1px;z-index: 999;font-size: 12px;" @endif>Must try</span>
                                 @endif
                                 @endif
                                </div>
                                    <a href="{!!url('/menuaddon/'.$food_val->id)!!}"><img src="{{$food_val->image}}" alt=""></a>
                                </div>
                            </div>
                            <div class="flip-box-back" id="flipback{{ $food_val->id }}" data-id="{{ $food_val->id }}">
                                <div class="fooddesc">
                                    <h2 class="elipsis-text food-name font-opensans text-black">
                                        {{$food_val->name}}
                                    </h2>
                                    <p class="elipsis-text font-montserrat">{{$food_val->description}}</p>
                                </div>
                                <div class="foodprice">
                                    @if($food_val->discount_price != 0)
                                    <h2 class="font-montserrat text-black"><del style="color:red;">&#8377;{{$food_val->price}}</del>
                                    &#8377;{{ $food_val->discount_price }}</h2>
                                    @else
                                    <h2 class="font-montserrat text-black">&#8377;{{$food_val->price}}</h2>
                                    @endif
                                    <a class="font-montserrat" href="{!!url('/menuaddon/'.$food_val->id)!!}">@if(isset($v->type) && $v->type == 'event'){!! "View Ticket" !!}@else{!! "View dish" !!}@endif</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chefsfoodlists  text-lg-left">
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
    @endforeach
    @if($current_page < $last_page )
    <button class="btn btn-default col-md-12 loadModule"  name="{!! $module !!}" type="button">Load More</button>
    @endif
    @endif
<!-- For chefs end -->
<script type="text/javascript">
    function updateBookmark(id){
        $.ajax({
            url : baseurl+"/bookmark/update",
            type : 'post',
            async: true,
            processData: true,          
            data : {'vendor_id': id},

            success : function(res){
                var msg = JSON.parse(JSON.stringify(res)); 
                    // $(".error-message-area").css("display","block");
                    // $(".error-content").css("background","#9cda9c");
                    // $(".error-msg").html("<b style='color:black'>"+msg.message+"</b>");
                    toast(msg.message, 'Success!', 'success'); 
                    setTimeout(function(){location.reload()}, 1000);

                },
                error : function(err){ 
                    var msg = err.responseJSON.message; 
                    if(msg == 'Unauthenticated.')
                        self.location=baseurl+"/login";

                    // console.log(msg);
                    $(".error-content").css("background","#ED4956");
                    $(".error-message-area").find('.error-msg').text(msg);
                    $(".error-message-area").show();
                    if(resend){
                        $(".ndiv").show();
                        $(".odiv").hide();
                    }
                }
            });
    }

</script>