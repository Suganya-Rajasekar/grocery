@extends('main.app')
<!-- @include('main.mobileappinfo') -->
@section('content')
<style type="text/css">
	.hero-area {
		overflow: hidden;
		background-image: url('{{ $homecontent->banimage }}');
		background-position:100% 40%;
		background-size:cover;
		width:100%;
		height: 700px;
		display:table;
		position: relative;
	}
	.knoshwrld-btn {
		/*width: 100%;*/
		padding: 20px 10px;
		background: unset;
		color: #f55a60;
		border: 4px solid #f55a60;
		font-size: 23px;
		font-weight: 600;
		display: inline-block;
		margin: 20px 0px;
		border-radius: 4px;
	}
	a.knoshwrld-btn:hover {
		border: 4px solid transparent;
		background: #f55a60;
		color: #fff;
		padding: 20px 10px;
	}
	@media (max-width: 575px){
		.knoshwrld-btn {
			padding: 10px;
			font-size: 16px;
		}
	}
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
{{-- <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script> --}}
<section id="hero" class="over-flow home-asw-banner">
	<div class="hero-area">
		<div class="row">
			<div class="col-xl-6 col-lg-8 col-md-10 table">
				<div class="table_cell">
					<div class="hero-main-content" style="padding: 25px">
						<h2 id="hero_title font-opensans" class="text-left mt-5 mb-5 pop-h2 ">@if(isset($homecontent->title))
							@php 
							$imp=explode('.',$homecontent->title);
							@endphp
							@foreach($imp as $title_val)
							<?php echo $title_val.'<br>';?>
							@endforeach
						@endif</h2>
						<p id="hero_des" class="home-slide-con font-montserrat">@if(isset($homecontent->subtitle)){{ $homecontent->subtitle }}@endif</p> <br>
						<div class="food-search-bar" style="box-shadow: none;">
							<!-- <form action="http://localhost/emperica/resturents" id="searchform"> -->
								<!-- <div class="search-input d-flex">
									<div class="location1"><img src="assets/front/img/locate.png"></div>
									<input type="hidden" id="lat" name="lat" required="" value="9.9260717">
									<input type="hidden" id="long" name="long" required="" value="78.1215208">
									<input type="hidden" id="city" name="city" required="" value="Madurai"> -->
									<input type="hidden" placeholder="Enter your delivery location" id="location_input" name="address" required="" class="pac-target-input font-montserrat" >
									<input type="hidden" placeholder="Enter your delivery location" id="ready-location_input" name="address" required="" class="pac-target-input font-montserrat" >

							<!--		<div class="location-icon" id="locationIcon" id="ic-locate-me-round">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"  width="24" height="24">
											<defs>
												<path id="a" d="M11.5 18a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM12 4v2.019A6.501 6.501 0 0 1 17.981 12H20v1h-2.019a6.501 6.501 0 0 1-5.98 5.981L12 21h-1v-2.019a6.501 6.501 0 0 1-5.981-5.98L3 13v-1h2.019A6.501 6.501 0 0 1 11 6.02V4h1zm-.5 11a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zm0 1a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7zm0-2.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"></path>
											</defs>
											<use fill="#ff3252" xlink:href="#a"></use>
										</svg>
										<span class="color-muted d-sm-block d-none font-montserrat" style="white-space: nowrap;">Locate Me</span>
									</div>
									<button type="submit" id="button_title" class="home-btn" onclick="seeMore('nearByChefs')">
										<img src="assets/front/img/next.png" class="home-btn-img">
									</button>
								</div> -->
							<!-- </form> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="how_margin">
	<h2 class="text-center mt-5 mb-5 pop-h2">How knosh works</h2>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4 position_set aniconthkw1 show-on-scroll">
				<div class="animate">
					<lottie-player src="{{ \URL::to('assets/js/chef.json') }}"   speed="1"  class="animate_img"  loop autoplay>
						<style type="text/css">
							lottie-player svg{
								width: 25%!important;
								transform: translate3d(0px, 0px, 0px);
								display: block!important;
							}
						</style>
					</lottie-player>
					<div class="ani_con">
						<h4>Choose your chef</h4>
						<p class="font-montserrat">Multiple Chefs - Single Order</p>
						<p class="font-montserrat">Master Chef | Celebrity Chef | Popular Chef</p>
					</div>
				</div>
			</div>
			<div class="col-md-4 position_set aniconthkw2 show-on-scroll">
				<div class="animate">
					<lottie-player src="{{ \URL::to('assets/js/food.json') }}"   speed="1"  class="animate_img"  loop autoplay>
						<style type="text/css">
							lottie-player svg{
								width: 25%!important;
								transform: translate3d(0px, 0px, 0px);
								display: block!important;
							}
						</style>
					</lottie-player>
					<div class="ani_con">
						<h4>Plan your meal</h4>
						<p class="font-montserrat">Place same day orders or pre book your meals for future</p>
					</div>
				</div>
			</div>
			<div class="col-md-4 position_set aniconthkw3 show-on-scroll">
				<div class="animate">
					<lottie-player src="{{ \URL::to('assets/js/delivery.json') }}"   speed="1"  class="animate_img"  loop autoplay>
						<style type="text/css">
							lottie-player svg{
								width: 25%!important;
								transform: translate3d(0px, 0px, 0px);
								display: block!important;
							}
						</style>
					</lottie-player>
					<div class="ani_con">
						<h4>Celebrate Food</h4>
						<p class="font-montserrat">Get your favourite dishes delivered at your preferred date and your preferred time</p>
					</div>
				</div>
			</div> 
		</div>
	</div>
</section>
@if(isset($banner) && count($banner) > 0 ) 
<section class="anicontad show-on-scroll">
	<div class="container-fluid mx-auto" style="overflow: hidden;">
		<div class="row d-flex justify-content-center">
			<div class="col-md-12">
				<div class="card card-main border-0 text-center">
					<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
						<ol class="carousel-indicators">
							@foreach($banner as $k => $v)
							<li data-target="#carouselExampleIndicators" data-slide-to="{{$k}}" @if($k == 0) class="active" @endif></li>
							@endforeach
						</ol>
						<div class="carousel-inner">
							@foreach($banner as $k => $v)
							<div class="carousel-item  @if($k == 0) active @endif">
								<div class="card border-{{$k}} card-{{$k}}">
									<img src="{{$v->image_src}}" class="ban-wdt" >
								</div>
							</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endif
<span id="popular_continue_scroll"></span>
{{-- <div class="container">
	<div class="text-center">
		<a href="{!! \URL::to('knosh-world') !!}" class="knoshwrld-btn text-center">Click To Experience Magical Food Experiences</a>
	</div>
</div> --}}
{{-- @include('main.knosh_world') --}}

<input type="hidden" name="celeb_page" id="celebchef" value="{{ 1 }}">
<input type="hidden" name="pop_page" id="popchef" value="{{ 1 }}">
<input type="hidden" name="home_event" id="home_event" value="{{ 1 }}">
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
		<section class="popular container-fluid home-asw-nearby" id="homeevent" style="display: none;">
			<h2 class="text-md-center mt-2 mb-2 pop-h2 celeb-popular">Party@Home
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
		<div id="mediapress" style="display:none;">
			@if(count($mediapress) > 0)
			<section class="container-fluid explore-bg py-3"> 
				<div class="explore-asw media-sec">
					<div class="owl-carousel explore-owl owl-media-sec" id=" owl-media-sec">
						@foreach($mediapress as $key=>$val)
						@if($val->status == 'active')
						<div class="item lets" style="padding: 3px" @if($val->media_type == "external_link") onclick="window.location=('{{ $val->description }}');"@endif>
							<div class="img_wrap" @if($val->media_type == "description") data-toggle="modal" data-target="#mediaSection{{ $val->id }}" @endif>
								<img src="{{ $val->image }}"
								class="cor-height" alt="food-image">
							</div>
						</div>
						@endif
						@endforeach
					</div>
				</div>
			</section>
			@endif

			@if(count($mediapress) > 0)
			@foreach($mediapress as $key=>$val)
			@if($val->media_type == 'description')
			<div class="modal fade mediaSection flow" id="mediaSection{{ $val->id }}">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<button type="button" class="close text-right p-2" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<div class="modal-body pt-5 pb-4 px-3">
							<div class="media-content row align-items-start">
								<div class="col-lg-5 col-md-5">
									<img src="{{ $val->image }}" alt="modal-image">
								</div>
								<div class="col-lg-7 col-md-7">
									<div class="article-content pr-1">
										<h3 class="article-title text-md-left text-center">{{ $val->name }}</h3>
										<span class="article-date text-md-left text-center d-block">{{ $val->created_at->format('d-M-Y') }}</span>
										<p class="mt-3">{{ strip_tags($val->description) }}</p>
										<a href="{{ url('blogs') }}">Show More...</a>
									</div>
								</div>  
							</div>
						</div>
					</div>
				</div>
			</div> 
			@endif
			@endforeach
			@endif
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