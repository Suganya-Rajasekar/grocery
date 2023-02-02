@extends('main.app')
@section('content')
<style type="text/css">
	.scroll-loader {
		text-align: center;
  }
 .home-asw-toprate h2 p{
    display: inline;
    text-align: right;
    float: right;
    color: #f65a60;
    cursor: pointer;
    font-weight: 600;
    margin: 13px 0px;
    position: absolute;
    right: 0;
	}
</style>
<input type="hidden" name="celeb_page" id="celebchef" value="{{ 1 }}">
<input type="hidden" name="pop_page" id="popchef" value="{{ 1 }}">
<input type="hidden" name="chef_event" id="chef_event" value="{{ 1 }}">
<input type="hidden" name="food_page" id="foodblog" value="{{ 1 }}">
<div class="showBlocklatlang" style="display: none;">
	{{-- @if( isset($explore) && count($explore) > 0) --}}
	{{-- <section class="container-fluid explore-bg"> 
		<div class="explore-asw ">
			<h2 class="text-center my-2  pop-h2">Let's Explore</h2>
			<div class="owl-carousel explore-owl owl-carousel-explore" id=" owl-carousel-explore">
				@foreach($explore as $k => $v)
				<div class="item lets" style="padding: 3px">
					<div class="img_wrap" @if($v->slug=='nearByChefs')onclick="seeMore('nearByChefs')"@elseif($v->slug=='popular')onclick="seeMore('popularNearYou')"@elseif($v->slug=='cuisines')onclick="seeexplore('cuisines')"@elseif($v->slug!='cuisines') onclick="seeexplore('{{$v->slug}}')"@endif>
						<img src="{{ $v->image }}"
						class="cor-height" @if($v->slug=='nearByChefs')onclick="seeMore('nearByChefs')"@elseif($v->slug=='popular')onclick="seeMore('popularNearYou')"@elseif($v->slug=='cuisines')onclick="seeexplore('cuisines')"@elseif($v->slug!='cuisines') onclick="seeexplore('{{$v->slug}}')"@endif>
						<div class="text_wrap" @if($v->slug=='nearByChefs')onclick="seeMore('nearByChefs')"@elseif($v->slug=='popular')onclick="seeMore('popularNearYou')"@elseif($v->slug=='cuisines')onclick="seeexplore('cuisines')"@elseif($v->slug!='cuisines') onclick="seeexplore('{{$v->slug}}')"@endif>
							<h4 class="text-center mt-2 mt-sm-4 ">{{ strip_tags($v->name) }}</h4>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</section> 
	@endif  --}}
	@if(isset($homePage->explorecuisines) && count($homePage->explorecuisines)>0)
	<section class="container-fluid explore-bg"> 
		<div class="explore-asw pt-md-5">
			<h2 class="text-center my-2  pop-h2">Let's Explore</h2>
			<div class="owl-carousel explore-owl owl-carousel-explore" id=" owl-carousel-explore">
				@foreach($homePage->explorecuisines as $k => $v)
				<div class="item lets" style="padding: 3px">
					<div class="img_wrap" onclick="cuisine({{ $v->id }})">
						<img src="{{ $v->image }}"
						class="cor-height" onclick="cuisine({{ $v->id }})" alt="Explore-image">
						<div class="text_wrap">
							<h4 class="text-center mt-2 mt-sm-4 ">{{ strip_tags($v->name) }}</h4>
						</div>
					</div>
				</div>
				@endforeach 
			</div>
		</div>
	</section> 
	@endif

	@if( isset($topRatedChefs->data) && count($topRatedChefs->data) > 0)
<section class="container-fluid home-asw-toprate">
    <h2 class="text-md-center mt-5 mb-2 pop-h2 ">Order from Top Rated Chefs<p class=" font-montserrat" onclick="seeMore('topRatedChefs')">See More</p></h2>
    <div class="owl-carousel owl-carousel-top_rated owl-theme">
        @foreach( $topRatedChefs->data as $k => $v)
        <div class="item ">
            <div class="topRatedChefsimg">
                <a href="{!!url('/chef/'.$v->id)!!}" >
                    <img src="{{ $v->avatar }}" class="avatar-img" alt="toprated-chef-image">
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
                <div class="pad_set text-center">
                <a href="{!!url('/chef/'.$v->id)!!}" ><h3 class="home-h4 font-opensans">{{ strip_tags($v->name) }}{{-- <span class="offer-chef">20% OFF</span> --}}</h3></a>
                <span class="home-span">
                    <p class="font-montserrat">
                        @foreach( $v->cuisines as $c1 => $c2)
                        {{ strip_tags($c2->name) }}@if(!$loop->last), @endif
                        @endforeach
                    </p>
                </span>
            <div class="sqr-star mt-lg-3">
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
                        <p class="text-black p-top">
                            <span class="star-points font-montserrat text-muted">{{--$v->ratings--}}({{$v->reviewscount}} Reviews)</span></p>
                            <!-- <div class="float-right">(</div> -->
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif
	<input type="hidden" id="hid_lat" name="hid_lat" value="0">
	<input type="hidden" id="hid_lang" name="hid_lang" value="0">
	<div id="near_by_chef_content" style="display:none;">
		<section class="popular container-fluid home-asw-nearby " id="celebrity">
			<h2 class="text-md-center mt-2 mb-2 pop-h2 celeb-popular">Celebrity Chefs
				<p class=" font-montserrat" onclick="seeMore('celebrityChefs')">See More</p>
			</h2>
		</section>
		<section class="popular container-fluid home-asw-nearby" id="popular" style="display: none;">
			<h2 class="text-md-center mt-2 mb-2 pop-h2 celeb-popular">Popular Chefs
				<p class=" font-montserrat" onclick="seeMore('popularChefs')">See More</p>
			</h2>
		</section>
		<section class="popular container-fluid home-asw-nearby" id="chefevent" style="display: none;">
			<h2 class="text-md-center mt-2 mb-2 pop-h2 celeb-popular">Knosh Events
				<p class=" font-montserrat" onclick="seeMore('chefevent')">See More</p>
			</h2>
		</section>
		<div id="foodblogs">
		</div>	
	</div>
</div>
<div class="scroll-loader" style="display:none;"><img src="{{ url('knosh-world-loader.gif') }}"></div>
@php
$recipe_img = [
	'https://hips.hearstapps.com/hmg-prod.s3.amazonaws.com/images/easy-cheap-dinners-weeknight-1604466210.jpg?crop=0.502xw:1.00xh;0.498xw,0&resize=640:*',
	'https://www.culinaryhill.com/wp-content/uploads/2018/10/Cowboy-Caviar-Recipe-Culinary-Hill-LR-04SQ.jpg',
	'https://imagesvc.meredithcorp.io/v3/mm/image?url=https%3A%2F%2Fimages.media-allrecipes.com%2Fuserphotos%2F5631902.jpg',
	'https://imagesvc.meredithcorp.io/v3/mm/image?url=https%3A%2F%2Fstatic.onecms.io%2Fwp-content%2Fuploads%2Fsites%2F43%2F2020%2F09%2F01%2F1015406_original.jpg',
	'https://aubreyskitchen.com/wp-content/uploads/2020/10/lions-mane-mushroom-recipe-crab-cakes-overhead-square.jpg'
];
$title			= ['name1','name2','name3','name4','name5'];
$description	= [''];
$description2	= [''];
@endphp
@endsection