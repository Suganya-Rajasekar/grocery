<!-- <input type="hidden" id="hid_lat" name="hid_lat" value="0">
<input type="hidden" id="hid_lang" name="hid_lang" value="0"> -->
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
@if( isset($popularNearYou->data) && count($popularNearYou->data) > 0)
{{-- <section class="popular container-fluid">
    <h2 class="text-md-center mt-3 mt-sm-5 mb-sm-5 mb-3 pop-h2">What's popular near you <span onclick="seeMore('popularNearYou')">See More</span></h2>
    @php 
    $counter = 1;
    @endphp
     <div class="mb-md-5 mb-1">
        <div class="popular-new">
            <div class="popular-new-width owl-carousel owl-theme">
                @foreach( $popularNearYou->data as $k => $v)
                <div class="item card">
                    <div class="home-asw-big-chef-new card-img">
                        <div class="">
                            <img src="{{ $v->image }}" class="">
                        </div>
                        <div class="content-popular d-flex justify-content-between align-items-center">
                            <h3 class="home-h3">{{ $v->name }}</h3>

                            <h5 class="pop-h5">&#8377;{{ $v->price }}</h5>
                        </div>
                        <span class="pop-span mb-5">{{ $v->category_name }}</span>
                        <div class="card-img-overlay">
                            <p class="pop-p mt-2">
                                <span class="home-h3">{{ $v->name }}</span><br><br>
                                <span>{{ $v->description }}</span>
                                <br><br>
                                <button class="pop-btn" onclick='self.location="{!!url('/menuaddon/'.$v->id)!!}"'>Order Now</button></p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div> 
        <div class="popular-new">
            <div class="popular-new-width owl-carousel owl-theme">
                @foreach( $popularNearYou->data as $k => $v)
                    <div class="item">
                        <div class="home-asw-big-chef-new">
                            <div class="">
                                <img src="{{ $v->image }}" class="">
                            </div>
                            <div class="content-popular">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="home-h3 font-opensans">{{ $v->name }}</h3>
                                    <h5 class="pop-h5 font-montserrat">&#8377;{{ $v->price }}</h5>
                                </div>
                                <span class="pop-span mb-5 font-montserrat">{{ $v->category_name }}</span>
                                <p class="pop-p mt-2 elipsis-text">{{ $v->description }}</p>
                                <div class="d-flex justify-content-between align-items-center popular_orderbtn">
                                    <button class="pop-btn font-montserrat" onclick='self.location="{!!url('/menuaddon/'.$v->id)!!}"'>Order Now</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section> --}}
@endif

{{-- @if( isset($celebrityChefs->data) && count($celebrityChefs->data) > 0)
<section class="container-fluid home-asw-cel py-4">
    <h2 class="text-md-center mt-2 mb-2 celebrity-title pop-h2">Celebrity Chefs <p class=" font-montserrat" onclick="seeMore('celebrityChefs')">See More</p>
    </h2>
    <div class="owl-carousel owl-carousel-sponsored">   
        @foreach( $celebrityChefs->data as $k => $v)
        <div class="item" data-merge="2">
            <div class="flip-box" id="cel-dir">
                <div class="flip-box-inner back1">
                    <div class="flip-box-front">
                        <div class="chef_img position-relative">
                            <a href="{!!url('/chef/'.$v->id)!!}" class="" >  
                                <img src="{{ $v->avatar }}" class="avatar-img">
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
                                @if($v->certified == 'yes')
                                <div class="ribbon1 up">
                                    <div class="content">
                                        <img src="{{ asset('assets/front/img/vegan.png') }}">
                                    </div>
                                </div>
                                @endif
                            </a>
                        </div>
                        <div class="chef_pad">
                            <a href="{!!url('/chef/'.$v->id)!!}" >
                                <h3 class="home-h4 font-opensans">{{ strip_tags($v->name) }}{{-- <span class="offer-chef">20% OFF</span> --}}{{-- </h3>
                            </a>
                            <span class="home-span">
                                <p class="font-montserrat elipsis-text">
                                @foreach( $v->cuisines as $c1 => $c2)
                                    {{ strip_tags($c2->name) }}@if(!$loop->last), @endif
                                @endforeach
                                </p>
                            </span>
                            <div class="sqr-star mt-3">
                                <div class=" star-rating ">
                                    <div class="overflow-hidden">
                                        <div class="home-asw-cel-chef-star">
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
                                        <p class="text-black p-top"> --}}{{--$v->ratings--}}
                                    {{--         <span class="star-points font-montserrat text-muted">({{$v->reviewscount}} Reviews)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>          
                    <div class="flip-box-back back1">
                        <p class="font-montserrat text-justify">{{ strip_tags($v->description) }}</p>
                        <a href="{!!url('/chef/'.$v->id)!!}" class="font-montserrat">View Chef</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div> 
</section>
@endif --}} 

{{-- @if( isset($celebrityChefs->data) && count($celebrityChefs->data) > 0)
<section class="popular container-fluid home-asw-nearby ">
    <h2 class="text-md-center mt-2 mb-2 pop-h2">Celebrity Chefs
        <p class=" font-montserrat" onclick="seeMore('celebrityChefs')">See More</p>
    </h2>
    @php 
    $count = 1;
    @endphp
    <div class="owl-carousel owl-theme owl-celebrity">
        @foreach( $celebrityChefs->data as $nbk => $nbv)
            <div class="item">
                <div class="">
                    <div class="settings-content-area">
                        <div class="searchbychef-das">
                            <div class="chef-lists row">
                                <div class="col-md-6">
                                    <div class="chefdetails my-3">
                                        <div class="chefimg">
                                            <a href="{{ \URL::to('chef/'.$nbv->id) }}" > <img src="{{ \URL::to($nbv->avatar) }}" alt="chef-image">
                                                @if($nbv->celebrity == 'yes')
                                                <div class="ribbon down">
                                                    <div class="content fas fa-star"></div>
                                                </div>
                                                @endif
                                                @if($nbv->promoted == 'yes')
                                                <div class="cor-height-top-ad">
                                                    <span>
                                                        AD
                                                    </span>
                                                </div>
                                                @endif
                                                @if($nbv->certified == 'yes')
                                                <div class="ribbon1 up">
                                                    <div class="content">
                                                        <img src="{{ asset('assets/front/img/vegan.png') }}">
                                                    </div>
                                                </div>
                                                @endif
                                            </a>
                                        </div>
                                        <div class="chefname text-center py-4">
                                            <a href="{{ \URL::to('chef/'.$nbv->id) }}" >
                                                <h2 class="text-black mb-0 mb-md-3 elipsis-text font-opensans font-weight-bold">{{ $nbv->name }} --}}
                                                    {{-- <img src="{{ asset('assets/front/img/badge.png') }}" class="cor-height-top-cer ml-2"> --}}{{-- <span class="offer-chef">20% OFF</span> --}}
                                            {{--     </h2>
                                            </a>
                                            <h4 class="text-muted elipsis-text mb-0 mb-md-3">
                                                <span class="home-span font-montserrat">
                                                    @foreach( $nbv->cuisines as $c1 => $c2)
                                                        {{ strip_tags($c2->name) }}@if(!$loop->last), @endif
                                                    @endforeach
                                                </span>
                                            </h4>
                                            <p class="text-justify d-none d-sm-block mb-0 mb-md-3 font-montserrat">{{ $nbv->description }}</p> --}}

                                            {{-- <div class="col-md-12" style="width: 100%;text-align: center;">
                                                <button class="see-btn font-montserrat" onclick="seeMore('nearByChefs')">See More</button>
                                            </div> --}}
{{--                                         </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="chefsfood">
                                        @if(!empty($nbv->get_vendor_food_details))
                                            <div class="row">
                                                @foreach($nbv->get_vendor_food_details as $nky => $nbvf)
                                                    <div class="item col-xl-5 col-md-6 col-sm-4 col-6">
                                                        <div class="chefsfoodlists">
                                                            <div class="foodimg">
                                                                <a href="{!!url('/menuaddon/'.$nbvf->id)!!}"><img src="{{ \URL::to($nbvf->image) }}" alt="food-image"></a>
                                                            </div>
                                                            <div class="fooddesc text-center p-xl-5 p-3">
                                                                <a>
                                                                <h3 class="food-name font-opensans">{{ $nbvf->name }}
                                                                </h3></a> --}}
                                                                {{-- <p class="font-montserrat">{{ $nbvf->description }}</p> --}}

 {{--                                                                <div class="foodprice d-none d-sm-block">
                                                                    <h3 class=" font-montserrat">₹{{ $nbvf->price }}</h3>
                                                                </div>
                                                                <div>
                                                                    <a href="{!!url('/menuaddon/'.$nbvf->id)!!}" class="orbn font-montserrat">Order Now</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php
                                                        if($nky == 3) { break; }
                                                    @endphp
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 d-none d-md-block">
                                    <div class="chefdetails my-3">
                                        <div class="chefimg">
                                            <a href="{{ \URL::to('chef/'.$nbv->id) }}" > <img src="{{ \URL::to($nbv->avatar) }}"></a>
                                        </div>
                                        <div class="chefname text-center py-4">
                                            <a href="{{ \URL::to('chef/'.$nbv->id) }}" ><h2 class="text-black mb-0 mb-md-3 elipsis-text font-weight-bold">{{ $nbv->name }}</h2></a>
                                            <h4 class="text-muted elipsis-text mb-0 mb-md-3">
                                                <span class="home-span">
                                                    @foreach( $nbv->cuisines as $c1 => $c2)
                                                        {{ strip_tags($c2->name) }}@if(!$loop->last), @endif
                                                    @endforeach
                                                </span>
                                            </h4>
                                            <p class="d-none d-sm-block mb-0 mb-md-3">{{ $nbv->description }}</p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        @endforeach
    </div> --}}
    @if( isset($celebrityChefs->data) && count($celebrityChefs->data) > 0)
    @foreach( $celebrityChefs->data as $nbk => $nbv)
    <div class="emp_works chef-lists mb-5 cuisine-detail-asw">
        <div class="chefdetails my-3">
            <div class="d-flex justify-content-between align-items-sm-center">
                <div class="">
                    <div class="chefimg">
                        <a href="{{ \URL::to('chef/'.$nbv->id) }}">
                            <img src="{{ asset($nbv->avatar) }}">
                        </a>
                        @if($nbv->promoted == 'yes')
                        <div class="cor-height-top-ad">
                            <span>
                                AD
                            </span>
                        </div>
                        @endif
                        @if($nbv->celebrity == 'yes')
                        <div class="ribbon down">
                          <div class="content fas fa-star"></div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="ml-1 ml-sm-3 w-100 mw-0 cuisine-detail-asw-chefname">
                  <div class="o-hid justify-content-between d-flex justify-content-between">
                    <div class="w-asw-85 mw-0">
                      <div class="chefname">
                        <a href="{{ URL::to('chef/'.$nbv->id) }}">
                          <h2 class="text-black font-weight-bold font-opensans">{{ $nbv->name }}</h2>
                        </a>
                        <h4 class="elipsis-text text-muted">
                          @foreach( $nbv->cuisines as $c1 => $c2)

                          <span class="font-montserrat">
                            {{ strip_tags($c2->name) }}
                            <!--cuisine categories like south-indian, north-indian-->

                          </span>
                          @endforeach
                        </h4>
                        <p class="elipsis-text d-none font-montserrat d-sm-block">{{ $nbv->description }}
                        </p>
                        <div class="sqr-star">
                            <div class=" star-rating">
                                <div class="overflow-hidden profile-asw-rate elipsis-text">
                                    <div class="">
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="text-center">
                      <div class="tag-ribbon p-2 tag-ribbon-2">
                        <a href="javascript:void(0)" onclick="updateBookmark({{ $nbv->id }})"><span class="@if($nbv->is_bookmarked == 1) fa fa-bookmark @else fa fa-bookmark-o @endif"></span></a>
                      </div>
                      <div class="pricefornos">
                        {{-- <h4 class="text-theme font-montserrat text-nowrap">₹1000 for two</h4> --}}
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
        @if(!empty($nbv->get_vendor_food_details))

        <div class="chefsfood cuisine-detail-asw-food">
            <div class="owl-carousel celebrity-chef owl-theme">
                @foreach($nbv->get_vendor_food_details as $nky => $nbvf)
                <div class="item position-relative" data-merge="2">
                    <div class="chefsfoodlists  text-lg-left" style="overflow: visible;">
                        <a href="{!!url('/menuaddon/'.$nbvf->id)!!}">
                            <?php $tag = tags_status($nbvf->tag_type);?>
                            @if($tag['none'] == 0)  
                            @if($tag['bestsell'] == 1)
                            <span class="badge badge-primary bestsell position-absolute l-con" style="left:-9px;padding: 5px;z-index:999;font-size: 12px;">Bestseller</span>
                            @endif
                            @if($tag['special'] == 1)
                            <span class="badge badge-danger mpopular position-absolute l-con" @if($tag['bestsell'] == 1) style="top:30px;left: -9px;z-index: 999;font-size: 12px;" @endif >Chef's special</span>
                            @endif
                            @if($tag['must_try'] == 1)
                            <span class="badge badge-success recommend position-absolute l-con" @if($tag['bestsell'] == 1 || $tag['special'] == 1) style="top:30px;left: -9px;z-index: 999;font-size: 12px;" @elseif($tag['bestsell'] == 0) style="top:0px;left: -9px;z-index: 999;font-size: 12px;" @endif>Must try</span>
                            @endif
                            @endif
                      <div class="foodimg">
                            <img src="{{asset($nbvf->image)}}" alt="" style="z-index:-99">
                      </div>
                      </a>
                      <div class="foodimg-title">
                            <h5 class="font-montserrat">{{ $nbvf->name }}</h5>
                            @if($nbvf->discount_price != 0)
                            <span><del style="color:red;">₹{{ $nbvf->price }}</del></span>
                            <span class="ml-2">₹{{ $nbvf->discount_price }}</span>
                            @else
                            <span>₹{{ $nbvf->price }}</span>
                            @endif
                      </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endforeach
    @endif


   {{--  <div class="col-md-12" style="width: 100%;text-align: center;">
        <button class="see-btn" onclick="seeMore('nearByChefs')">See More</button>
    </div> --}}
{{-- </section>
@endif --}}

@if( isset($popularChefs->data) && count($popularChefs->data) > 0)
<section class="container-fluid home-asw-cel" style="display:none;">
    <h2 class="text-md-center mt-2 mb-2 pop-h2">Popular Chefs <p class=" font-montserrat" onclick="seeMore('popularChefs')">See More</p>
    </h2>
    <div class="owl-carousel owl-carousel-sponsored">   
        @foreach( $popularChefs->data as $k => $v)
        <div class="item">
            <div class="flip-box" id="cel-dir">
                <div class="flip-box-inner back1">
                    <div class="flip-box-front">
                        <div class="chef_img position-relative">
                            <a href="{!!url('/chef/'.$v->id)!!}" class="" >  
                                <img src="{{ $v->avatar }}" class="avatar-img" alt="chef-image">
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
                                @if($v->certified == 'yes')
                                <div class="ribbon1 up">
                                    <div class="content">
                                        <img src="{{ asset('assets/front/img/vegan.png') }}">
                                    </div>
                                </div>
                                @endif
                            </a>
                        </div>
                        <div class="chef_pad">
                            <a href="{!!url('/chef/'.$v->id)!!}" >
                                <h3 class="home-h4 font-opensans">{{ strip_tags($v->name) }}{{-- <span class="offer-chef">20% OFF</span> --}}</h3>
                            </a>
                            <span class="home-span">
                                <p class="font-montserrat elipsis-text">
                                @foreach( $v->cuisines as $c1 => $c2)
                                    {{ strip_tags($c2->name) }}@if(!$loop->last), @endif
                                @endforeach
                                </p>
                            </span>
                            <div class="sqr-star mt-3">
                                <div class=" star-rating ">
                                    <div class="overflow-hidden">
                                        <div class="home-asw-cel-chef-star">
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
                                        <p class="text-black p-top">{{--$v->ratings--}}
                                            <span class="star-points font-montserrat text-muted">({{$v->reviewscount}} Reviews)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>          
                    <div class="flip-box-back back1">
                        <p class="font-montserrat text-justify">{{ strip_tags($v->description) }}</p>
                        <a href="{!!url('/chef/'.$v->id)!!}" class="font-montserrat">View Chef</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div> 
</section>
@endif

    @if(isset($nearByChefs->data) && count($nearByChefs->data) > 0)
    @foreach( $nearByChefs->data as $nbk => $nbv)
    <div class="emp_works chef-lists mb-5 cuisine-detail-asw">
        <div class="chefdetails my-3">
            <div class="d-flex justify-content-between align-items-sm-center">
                <div class="">
                    <div class="chefimg">
                        <a href="{{ \URL::to('chef/'.$nbv->id) }}">
                            <img src="{{ asset($nbv->avatar) }}">
                        </a>
                        @if($nbv->promoted == 'yes')
                        <div class="cor-height-top-ad">
                            <span>
                                AD
                            </span>
                        </div>
                        @endif
                        @if($nbv->celebrity == 'yes')
                        <div class="ribbon down">
                          <div class="content fas fa-star"></div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="ml-1 ml-sm-3 w-100 mw-0 cuisine-detail-asw-chefname">
                  <div class="o-hid justify-content-between d-flex justify-content-between">
                    <div class="w-asw-85 mw-0">
                      <div class="chefname">
                        <a href="{{ URL::to('chef/'.$nbv->id) }}">
                          <h2 class="text-black font-weight-bold font-opensans">{{ $nbv->name }}</h2>
                        </a>
                        <h4 class="elipsis-text text-muted">
                          @foreach( $nbv->cuisines as $c1 => $c2)

                          <span class="font-montserrat">
                            {{ strip_tags($c2->name) }}
                            <!--cuisine categories like south-indian, north-indian-->

                          </span>
                          @endforeach
                        </h4>
                        <p class="elipsis-text d-none font-montserrat d-sm-block">{{ $nbv->description }}
                        </p>
                        <div class="sqr-star">
                            <div class=" star-rating">
                                <div class="overflow-hidden profile-asw-rate elipsis-text">
                                    <div class="">
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="text-center">
                      <div class="tag-ribbon p-2 tag-ribbon-2">
                        <a href="javascript:void(0)" onclick="updateBookmark({{ $nbv->id }})"><span class="@if($nbv->is_bookmarked == 1) fa fa-bookmark @else fa fa-bookmark-o @endif"></span></a>
                      </div>
                      <div class="pricefornos">
                        {{-- <h4 class="text-theme font-montserrat text-nowrap">₹1000 for two</h4> --}}
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
        @if(!empty($nbv->get_vendor_food_details))

        <div class="chefsfood cuisine-detail-asw-food">
            <div class="owl-carousel celebrity-chef owl-theme">
                @foreach($nbv->get_vendor_food_details as $nky => $nbvf)
                <div class="item" data-merge="2">
                    <div class="chefsfoodlists  text-lg-left" style="overflow:visible;">
                        <?php $tag = tags_status($nbvf->tag_type);?>
                        @if($tag['none'] == 0)  
                        @if($tag['bestsell'] == 1)
                        <span class="badge badge-primary bestsell position-absolute l-con" style="left:-9px;padding: 5px;z-index:999;font-size: 12px;">Bestseller</span>
                        @endif
                        @if($tag['special'] == 1)
                        <span class="badge badge-danger mpopular position-absolute l-con" @if($tag['bestsell'] == 1) style="top:30px;left: -9px;z-index: 999;font-size: 12px;" @endif>Chef's special</span>
                        @endif
                        @if($tag['must_try'] == 1)
                        <span class="badge badge-success recommend position-absolute l-con" @if($tag['bestsell'] == 1 || $tag['special'] == 1) style="top:30px;left: -9px;z-index: 999;font-size: 12px;" @endif>Must try</span>
                        @endif
                        @endif
                      <div class="foodimg">
                        <a href="{!!url('/menuaddon/'.$nbvf->id)!!}">
                            <img src="{{asset($nbvf->image)}}" alt="">
                        </a>
                      </div>
                      <div class="foodimg-title">
                            <h5 class="font-montserrat">{{ $nbvf->name }}</h5>
                            <span>₹{{ $nbvf->price }}</span>
                      </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endforeach
    @endif

    @if(isset($home_event->data) && count($home_event->data) > 0)
    @foreach( $home_event->data as $nbk => $nbv)
    <div class="emp_works chef-lists mb-5 cuisine-detail-asw">
        <div class="chefdetails my-3">
            <div class="d-flex justify-content-between align-items-sm-center">
                <div class="">
                    <div class="chefimg">
                        <a href="{{ \URL::to('chef/'.$nbv->id) }}">
                            <img src="{{ asset($nbv->avatar) }}">
                        </a>
                        @if($nbv->promoted == 'yes')
                        <div class="cor-height-top-ad">
                            <span>
                                AD
                            </span>
                        </div>
                        @endif
                        @if($nbv->celebrity == 'yes')
                        <div class="ribbon down">
                          <div class="content fas fa-star"></div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="ml-1 ml-sm-3 w-100 mw-0 cuisine-detail-asw-chefname">
                  <div class="o-hid justify-content-between d-flex justify-content-between">
                    <div class="w-asw-85 mw-0">
                      <div class="chefname">
                        <a href="{{ URL::to('chef/'.$nbv->id) }}">
                          <h2 class="text-black font-weight-bold font-opensans">{{ $nbv->name }}</h2>
                        </a>
                        <h4 class="elipsis-text text-muted">
                          @foreach( $nbv->cuisines as $c1 => $c2)

                          <span class="font-montserrat">
                            {{ strip_tags($c2->name) }}
                            <!--cuisine categories like south-indian, north-indian-->

                          </span>
                          @endforeach
                        </h4>
                        <p class="elipsis-text d-none font-montserrat d-sm-block">{{ $nbv->description }}
                        </p>
                        <div class="sqr-star">
                            <div class=" star-rating">
                                <div class="overflow-hidden profile-asw-rate elipsis-text">
                                    <div class="">
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="text-center">
                      <div class="tag-ribbon p-2 tag-ribbon-2">
                        <a href="javascript:void(0)" onclick="updateBookmark({{ $nbv->id }})"><span class="@if($nbv->is_bookmarked == 1) fa fa-bookmark @else fa fa-bookmark-o @endif"></span></a>
                      </div>
                      <div class="pricefornos">
                        {{-- <h4 class="text-theme font-montserrat text-nowrap">₹1000 for two</h4> --}}
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
        @if(!empty($nbv->get_vendor_food_details))

        <div class="chefsfood cuisine-detail-asw-food">
            <div class="owl-carousel celebrity-chef owl-theme">
                @foreach($nbv->get_vendor_food_details as $nky => $nbvf)
                <div class="item" data-merge="2">
                    <div class="chefsfoodlists  text-lg-left" style="overflow:visible;">
                        <?php $tag = tags_status($nbvf->tag_type);?>
                        @if($tag['none'] == 0)  
                        @if($tag['bestsell'] == 1)
                        <span class="badge badge-primary bestsell position-absolute l-con" style="left:-9px;padding: 5px;z-index:999;font-size: 12px;">Bestseller</span>
                        @endif
                        @if($tag['special'] == 1)
                        <span class="badge badge-danger mpopular position-absolute l-con" @if($tag['bestsell'] == 1) style="top:30px;left: -9px;z-index: 999;font-size: 12px;" @endif>Chef's special</span>
                        @endif
                        @if($tag['must_try'] == 1)
                        <span class="badge badge-success recommend position-absolute l-con" @if($tag['bestsell'] == 1 || $tag['special'] == 1) style="top:30px;left: -9px;z-index: 999;font-size: 12px;" @endif>Must try</span>
                        @endif
                        @endif
                      <div class="foodimg">
                        <a href="{!!url('/menuaddon/'.$nbvf->id)!!}">
                            <img src="{{asset($nbvf->image)}}" alt="">
                        </a>
                      </div>
                      <div class="foodimg-title">
                            <h5 class="font-montserrat">{{ $nbvf->name }}</h5>
                            <span>₹{{ $nbvf->price }}</span>
                      </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endforeach
    @endif

    @if(isset($chefevent->data) && count($chefevent->data) > 0)
    @foreach( $chefevent->data as $nbk => $nbv)
    <div class="emp_works chef-lists mb-5 cuisine-detail-asw" id="jjyy">
        <div class="chefdetails my-3">
            <div class="d-flex justify-content-between align-items-sm-center">
                <div class="">
                    <div class="chefimg">
                        <a href="{{ \URL::to('chef/'.$nbv->id) }}">
                            <img src="{{ asset($nbv->avatar) }}">
                        </a>
                        @if($nbv->promoted == 'yes')
                        <div class="cor-height-top-ad">
                            <span>
                                AD
                            </span>
                        </div>
                        @endif
                        @if($nbv->celebrity == 'yes')
                        <div class="ribbon down">
                          <div class="content fas fa-star"></div>
                      </div>
                      @endif
                  </div>
                </div>
                <div class="ml-1 ml-sm-3 w-100 mw-0 cuisine-detail-asw-chefname">
                  <div class="o-hid justify-content-between d-flex justify-content-between">
                    <div class="w-asw-85 mw-0">
                      <div class="chefname">
                        <a href="{{ URL::to('chef/'.$nbv->id) }}">
                          <h2 class="text-black font-weight-bold font-opensans">{{ $nbv->name }}</h2>
                        </a>
                        <div class="text-muted">
                            <span><b> Location:</b>{{ $nbv->event_location }}</span>
                        </div>
                        <div class="text-muted mt-3 mb-3">
                            <?php  $event_date_time = explode(' ',$nbv->event_time,2); ?>
                            <span><b>Event date & time:</b>{!! $event_date_time[0].' / '.$event_date_time[1] !!}</span>
                        </div>
                        <p class="elipsis-text d-none font-montserrat d-sm-block">{{ $nbv->chef_description }}
                        </p>
                        {{-- <div class="sqr-star">
                            <div class=" star-rating">
                                <div class="overflow-hidden profile-asw-rate elipsis-text">
                                    <div class="">
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                      </div>
                    </div>
                    <div class="text-center">
                      <div class="tag-ribbon p-2 tag-ribbon-2">
                        <a href="javascript:void(0)" onclick="updateBookmark({{ $nbv->id }})"><span class="@if($nbv->is_bookmarked == 1) fa fa-bookmark @else fa fa-bookmark-o @endif"></span></a>
                      </div>
                      <div class="pricefornos">
                        {{-- <h4 class="text-theme font-montserrat text-nowrap">₹1000 for two</h4> --}}
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
        @if(!empty($nbv->get_vendor_food_details))

        <div class="chefsfood cuisine-detail-asw-food">
            <div class="owl-carousel celebrity-chef owl-theme">
                @foreach($nbv->get_vendor_food_details as $nky => $nbvf)
                <div class="item" data-merge="2">
                    <div class="chefsfoodlists  text-lg-left">
                      <div class="foodimg">
                        <a href="{!!url('/menuaddon/'.$nbvf->id)!!}">
                            <img src="{{asset($nbvf->image)}}" alt="">
                        </a>
                      </div>
                      <div class="foodimg-title">
                            <h5 class="font-montserrat">{{ $nbvf->name }}</h5>
                            <span>₹{{ $nbvf->price }}</span>
                      </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endforeach
    @endif

    @if( isset($popularRecipe->data) && count($popularRecipe->data) > 0)
    <div class="container-fluid popular-recipe pb-5" id="popular_receipe">
        <div>
            <h1 class="py-3">{{ $homePage->sectionTitles->section1 }}</h1>
        </div>
        <div>
            <div class="owl-recipes owl-carousel owl-theme">
                @foreach($popularRecipe->data as $pop_k => $pop_v)
                <div class="item">
                    <div class="recipe-img">
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#popularmodal" onclick="popularinfo('{{$pop_v->id}}')">
                            <img src="{{$pop_v->image}}" alt="recipe-image">

                            <div class="recipe-content">
                                <h2>{{$pop_v->name}}</h2>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if( isset($blogs->data) && count($blogs->data) > 0)
    <div class="container-fluid food-blog pb-5">
        <div>
            <h1 class="py-3">{{ $homePage->sectionTitles->section2 }}</h1>
        </div>
        <div>
            <div class="owl-food-blog owl-carousel owl-theme">
                @foreach($blogs->data as $blog_k => $blog_v)
                <div class="item">
                    <div class="recipe-img">
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#popularmodal" onclick="bloginfo('{{$blog_v->id}}')">
                            <img src="{{$blog_v->image}}" alt="recipe1-image">
                            <div class="recipe-content-1">
                                <h2>{{$blog_v->name}}</h2>
                                <!-- <p></p> -->
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    <!-- modal start popular popup-->
    <div class="modal fade" id="popularmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body" id="commentbox">
                </div>
            </div>
        </div>
    </div>
    <!-- modal end popular popup-->
    @if( isset($whatsTrending->data) && count($whatsTrending->data) > 0)
    <div class="container-fluid whats-trending pb-5">
        @php
        $recipe_video = [
            asset("assets/front/img/recipe-video.mp4"), 
            'demo.mp4'
        ];
        @endphp
        <div>
            <h1 class="py-3">{{ $homePage->sectionTitles->section3 }}</h1>
        </div>
        <div>
            <div class="owl-whats-trending owl-carousel owl-theme">
                @foreach($whatsTrending->data as $tred_k => $tred_v)
                {{-- Youtube video --}}
                @if($tred_v->type == 'url')
                <div class="item">
                    <div class="box-video">
                        <div class="bg-video">
                            <div class="play-bttn"></div>
                        </div>
                        <div class="video-container h-100">
                            <div class="youtube-player h-100" data-id="{{$tred_v->video}}" data-related="0" data-control="1" data-info="0" data-fullscreen="1">
                                <div class="play-btn h-100">
                                    <img src="{!! url($tred_v->image) !!}" class="thumbnail lazyload kno-thumbnail h-100" alt="video thumbnail">
                                    {{-- <img src="https://img.youtube.com/vi/-c8vJtnDPyQ/0.jpg" class="thumbnail lazyload kno-thumbnail h-100" alt="video thumbnail"> --}}
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
                @else
                {{-- Local video --}}
                <div class="item">
                    <div class="box-video">
                        <div class="bg-video">
                            <div class="play-bttn"></div>
                        </div>
                        <div class="video-container h-100"> 
                            <div 
                            class="local-player h-100" 
                            data-id="recipe-video" 
                            data-src="{{$tred_v->video}}"
                            data-count = {{$tred_k}}
                            >
                            <div class="play-btn h-100">
                                <img 
                                src="{!! url($tred_v->image) !!}" 
                                class="thumbnail lazyload kno-thumbnail h-100" 
                                alt="video thumbnail"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
@endif
{{-- @if( isset($nearByChefs->data) && count($nearByChefs->data) > 0)
<section class="popular container-fluid home-asw-nearby">
    <h2 class="text-md-center mt-2 mb-2 pop-h2">Popular Chefs
        <p class=" font-montserrat" onclick="seeMore('popularChefs')">See More</p>
    </h2>
    @php 
    $count = 1;
    @endphp
    <div class="owl-carousel owl-theme owl-nearbychef">
        @foreach( $nearByChefs->data as $nbk => $nbv)
            <div class="item">
                <div class="">
                    <div class="settings-content-area">
                        <div class="searchbychef-das">
                            <div class="chef-lists row">
                                <div class="col-md-6">
                                    <div class="chefdetails my-3">
                                        <div class="chefimg">
                                            <a href="{{ \URL::to('chef/'.$nbv->id) }}" > <img src="{{ \URL::to($nbv->avatar) }}" alt="chef-image">
                                                @if($nbv->celebrity == 'yes')
                                                <div class="ribbon down">
                                                    <div class="content fas fa-star"></div>
                                                </div>
                                                @endif
                                                @if($nbv->promoted == 'yes')
                                                <div class="cor-height-top-ad">
                                                    <span>
                                                        AD
                                                    </span>
                                                </div>
                                                @endif
                                                @if($nbv->certified == 'yes')
                                                <div class="ribbon1 up">
                                                    <div class="content">
                                                        <img src="{{ asset('assets/front/img/vegan.png') }}">
                                                    </div>
                                                </div>
                                                @endif
                                            </a>
                                        </div>
                                        <div class="chefname text-center py-4">
                                            <a href="{{ \URL::to('chef/'.$nbv->id) }}" >
                                                <h2 class="text-black mb-0 mb-md-3 elipsis-text font-opensans font-weight-bold">{{ $nbv->name }} --}}
                                                    {{-- <img src="{{ asset('assets/front/img/badge.png') }}" class="cor-height-top-cer ml-2"> --}}{{-- <span class="offer-chef">20% OFF</span> --}}
                                              {{--   </h2>
                                            </a>
                                            <h4 class="text-muted elipsis-text mb-0 mb-md-3">
                                                <span class="home-span font-montserrat">
                                                    @foreach( $nbv->cuisines as $c1 => $c2)
                                                        {{ strip_tags($c2->name) }}@if(!$loop->last), @endif
                                                    @endforeach
                                                </span>
                                            </h4>
                                            <p class="text-justify d-none d-sm-block mb-0 mb-md-3 font-montserrat">{{ $nbv->description }}</p> --}}

                                            {{-- <div class="col-md-12" style="width: 100%;text-align: center;">
                                                <button class="see-btn font-montserrat" onclick="seeMore('nearByChefs')">See More</button>
                                            </div> --}}
                                       {{--  </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="chefsfood">
                                        @if(!empty($nbv->get_vendor_food_details))
                                            <div class="row">
                                                @foreach($nbv->get_vendor_food_details as $nky => $nbvf)
                                                    <div class="item col-xl-5 col-md-6 col-sm-4 col-6">
                                                        <div class="chefsfoodlists">
                                                            <div class="foodimg">
                                                                <a href="{!!url('/menuaddon/'.$nbvf->id)!!}"><img src="{{ \URL::to($nbvf->image) }}" alt="food-image"></a>
                                                            </div>
                                                            <div class="fooddesc text-center p-xl-5 p-3">
                                                                <a>
                                                                <h3 class="food-name font-opensans">{{ $nbvf->name }}
                                                                </h3></a> --}}
                                                                {{-- <p class="font-montserrat">{{ $nbvf->description }}</p> --}}

                                                            {{--     <div class="foodprice d-none d-sm-block">
                                                                    <h3 class=" font-montserrat">₹{{ $nbvf->price }}</h3>
                                                                </div>
                                                                <div>
                                                                    <a href="{!!url('/menuaddon/'.$nbvf->id)!!}" class="orbn font-montserrat">Order Now</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php
                                                        if($nky == 3) { break; }
                                                    @endphp
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-6 d-none d-md-block">
                                    <div class="chefdetails my-3">
                                        <div class="chefimg">
                                            <a href="{{ \URL::to('chef/'.$nbv->id) }}" > <img src="{{ \URL::to($nbv->avatar) }}"></a>
                                        </div>
                                        <div class="chefname text-center py-4">
                                            <a href="{{ \URL::to('chef/'.$nbv->id) }}" ><h2 class="text-black mb-0 mb-md-3 elipsis-text font-weight-bold">{{ $nbv->name }}</h2></a>
                                            <h4 class="text-muted elipsis-text mb-0 mb-md-3">
                                                <span class="home-span">
                                                    @foreach( $nbv->cuisines as $c1 => $c2)
                                                        {{ strip_tags($c2->name) }}@if(!$loop->last), @endif
                                                    @endforeach
                                                </span>
                                            </h4>
                                            <p class="d-none d-sm-block mb-0 mb-md-3">{{ $nbv->description }}</p>

                                        </div>
                                    </div>
                                </div> --}}
{{--                             </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        @endforeach
    </div> --}}
   {{--  <div class="col-md-12" style="width: 100%;text-align: center;">
        <button class="see-btn" onclick="seeMore('nearByChefs')">See More</button>
    </div> --}}
{{-- </section> --}}
