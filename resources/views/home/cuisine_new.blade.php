@extends('main.app')
@section('content')
  <section class="topsec area-asw-carousel-1">
    <div class="carousel d-none">
      <div class="container-fluid">
        <div class="row pt-sm-50 rest_grocery m-0 owl-carousel owl-carousel-cui owl-loaded owl-drag" id="owlcarousel-rest">
          <div class="car-asw">
            <div class="owl-carousel top-area-car owl-theme">
              <div class="item">
                <div>
                  <img src="https://static.vecteezy.com/system/resources/thumbnails/001/254/896/small_2x/creative-minimal-offer-special-food-banner-for-social-media.jpg" alt=""/>
                </div>
              </div>
              <div class="item">
                <div>
                  <img src="https://image.freepik.com/free-psd/special-food-menu-offer-social-media-post-banner-design_268949-21.jpg" alt=""/>
                </div>
              </div>
              <div class="item">
                <div>
                  <img src="https://d1csarkz8obe9u.cloudfront.net/posterpreviews/food-deliver-%26-offer-ads-design-template-664c0400c6b3d38642ed37f0f1e2134c_screen.jpg?ts=1591836204"/>
                </div>
              </div>
              <div class="item">
                <div>
                  <img src="https://image.freepik.com/free-vector/restaurant-food-offer-special-social-menu-media-post-colorful-abstract-premium-template_171965-408.jpg"/>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <?php //echo "<pre>";print_r($seemore);echo "</pre>";exit;?>
  <div class="cuisine-bg1 py-5">
    <div class="area-asw">
      <div class="container-lg">
        <div class="searchbyfood">
          <div class="">
            {{-- @if(count($cuisine) > 0) --}}

              <div class="">
                <div class="cuisine-backdrop d-none"> 
                  <div class="cusine-active-close">
                    <i class="fas fa-close"></i>
                  </div>
                </div>
                <div class="nav nav-tabs mb-5">
                  <div class="owl-cuisine row m-0">
                    @for($i=0; $i<20; $i++)
                    <div class="item col-md-6 p-0">
                      <div class="nav-item text-center">
                        <div class="cuisine d-md-flex d-none"  data-toggle="tab" href="#cuisinechef1" onclick="" id="{{-- cui{{$val->id}} --}}">
                          <div class="cuisine-img w-100 @if(($i%4)>=2) d-none @endif">
                            <img src="{{url('storage/app/public/food.jpg')}}" alt="">
                          </div>
                          <div class="w-100 content">
                            <h2 class="elipsis-text font-montserrat">Name</h2>
                            <p></p>
                            <p class="readmore">Read more</p>
                          </div>
                          <div class="cuisine-img w-100 @if(($i%4)<2) d-none @endif">
                            <img src="{{url('storage/app/public/food.jpg')}}" alt="">
                          </div>
                        </div>
                        <div class="cuisine d-flex d-md-none"  data-toggle="tab" href="">
                          <div class="cuisine-img w-100 @if($i%2==1) d-none @endif">
                            <img src="{{url('storage/app/public/food.jpg')}}" alt="">
                          </div>
                          <div class="w-100 content">
                            <h2 class="elipsis-text font-montserrat">Name</h2>
                            <p> </p>
                            <p class="readmore">Read more</p>
                          </div>
                          <div class="cuisine-img w-100 @if($i%2==0) d-none @endif">
                            <img src="{{url('storage/app/public/food.jpg')}}" alt="">
                          </div>
                        </div>
                      </div>
                    </div>
                    @endfor
                  </div>
                </div>
              </div>

              {{--<div class="">
                <div class=" area-asw-filter">      
                  <section class="filterby mb-5 d-flex justify-content-between align-items-center">
                    <div class="p-3 d-block d-lg-none cui-active-menu">
                      <i class="fas fa-bars"></i>
                    </div>
                    <div class="form-group">
                      <select class="form-control font-montserrat" name="area" id="filter">
                        <option  @if($module == 'cuisines') selected="true" @endif value="cuisines" data-id="explore">cuisines</option>
                        <option  @if($module == 'popularNearYou') selected="true" @endif value="popularNearYou">popular Near You</option>
                        <option  @if($module == 'topRatedChefs') selected="true" @endif value="topRatedChefs">Top Rated Chefs</option>
                        <option  @if($module == 'celebrityChefs') selected="true" @endif value="celebrityChefs">Celebrity Chefs</option>
                        <option  @if($module == 'nearByChefs') selected="true" @endif value="nearByChefs">Near by Chefs</option>
                        <option  @if($module == 7) selected="true" @endif value="7" data-id="explore">Snacks</option>
                        <option  @if($module == 8) selected="true" @endif value="8" data-id="explore">Dessert</option>
                        <option  @if($module == 9) selected="true" @endif value="9" data-id="explore">Bakery</option>
                      </select>
                    </div>
                  </section>
                </div>
                <div class="tab-content">
                  <div class="tab-pane active" id="cuisinechef1">
                    @if($seemore)
                    @foreach($seemore as $k => $v)
                    <div class="chef-lists mb-5 seemore-asw">
                      <div class="chefdetails my-3">
                        <div class="d-flex">
                          <div class=" ">
                            <div class="chefimg">
                              <a href="{!!url('/chef/'.$v->id)!!}" ><img src="{{$v->avatar}}" alt=""></a>
                                <div class="ribbon1 up">
                                    <div class="content">
                                      <img src="{{ asset('assets/front/img/vegan.png') }}">
                                    </div>
                                </div>
                                <div class="cor-height-top-ad">
                                    <span>
                                        AD
                                    </span>
                                </div>
                                <div class="ribbon down">
                                    <div class="content fas fa-star"></div>
                                </div>
                            </div> 
                          </div>
                          <div class="ml-1 ml-sm-3 w-100 seemore-asw-chefname">
                            <div class="o-hid justify-content-between d-flex justify-content-md-start">
                              <div class="w-asw-85">
                                <div class="chefname">
                                  <a href="{!!url('/chef/'.$v->id)!!}" ><h2 class="text-black font-weight-bold font-opensans">{{$v->name}}{{-- <span class="offer-chef">20% OFF</span> --}}</h2></a>
                                  <h4 class="elipsis-text text-muted">
                                    <span class="font-montserrat">
                                      @foreach( $v->cuisines as $c1 => $c2)
                                      {{ strip_tags($c2->name) }}@if(!$loop->last), @endif
                                      @endforeach
                                    </span>
                                  </h4>
                                  <p class="elipsis-text d-none font-montserrat d-sm-block">{{ strip_tags($v->description) }}</p>
                                </div>
                              </div>
                              <div class="">
                                <div class="tag-ribbon">
                                  <a href="javascript:void(0)" onclick="updateBookmark( {{ $v->id }} )"><span class=" fa fa-bookmark-o"></span></a>
                                </div>
                              </div>
                            </div>
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
                                  <h4 class="text-theme font-montserrat">&#8377;{{$v->budgetName}}</h4>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                      </div>
                      <div class="chefsfood seemore-asw-food">
                        @if($v->get_vendor_food_details)
                        <div class="owl-carousel see-more owl-theme">
                          @foreach($v->get_vendor_food_details as $food_k => $food_val)
                          <div class="item">
                            <div class="chefsfoodlists  text-lg-left">
                              <div class="foodimg">
                                <a href="{!!url('/menuaddon/'.$food_val->id)!!}"><img src="{{$food_val->image}}" alt=""></a>
                              </div>
                              <div class="fooddesc">
                                <h2 class="food-name text-black">
                                  <a href="{!!url('/menuaddon/'.$food_val->id)!!}" class=" font-opensans">{{$food_val->name}}</a>
                                </h2>
                                <p class="elipsis-text font-montserrat">{{$food_val->description}}</p>
                              </div>
                              <div class="foodprice">
                                <h2 class="text-black">&#8377;{{$food_val->price}}</h2>
                              </div>
                            </div>
                          </div>
                          @endforeach
                        </div>

                        @endif

                      </div>

                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>--}}

            {{-- @endif --}}
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection