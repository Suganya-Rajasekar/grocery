@extends('main.app')
@section('content')
<section class="topsec area-asw-carousel-1">
    
  </section>


  <?php //echo "<pre>";print_r($seemore);echo "</pre>";exit;?>
  <div class="container-fluid  ">

    <div class="searchbyfood">
      <div class="">
        {{-- @if(count($cuisine) > 0) --}}

          <div class="">
            <div class=" area-asw-filter">      
              <section class="filterby mb-5 d-flex justify-content-between align-items-center">
                <div class="form-group">
                  <select class="form-control font-montserrat" name="area" id="filter">
                    <option  value="cuisines" data-id="explore">cuisines</option>
                    <option  value="popularNearYou">popular Near You</option>
                    <option  value="topRatedChefs">Top Rated Chefs</option>
                    <option  value="celebrityChefs">Celebrity Chefs</option>
                    <option  value="nearByChefs">Near by Chefs</option>
                    <option  value="7" data-id="explore">Snacks</option>
                    <option  value="8" data-id="explore">Dessert</option>
                    <option  value="9" data-id="explore">Bakery</option>
                  </select>
                </div>
              </section>
            </div>
            <div class="tab-content">
              <div class="tab-pane active" id="cuisinechef1">
                {{-- @if($seemore) --}}
                {{-- @foreach($seemore as $k => $v) --}}
                @for($j=0;$j<5;$j++)
                  <div class="chef-lists mb-5 cuisine-detail-asw">
                    <div class="chefdetails my-3">
                      <div class="d-flex justify-content-between align-items-center">
                        <div class=" ">
                          <div class="chefimg">
                            <a href="" ><img src="{{asset('assets/front/img/nayan.jpg')}}" alt=""></a>
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
                        <div class="ml-1 ml-sm-3 w-100 mw-0 cuisine-detail-asw-chefname">
                          <div class="o-hid justify-content-between d-flex justify-content-between">
                            <div class="w-asw-85 mw-0">
                              <div class="chefname">
                                <a href="" >
                                  <h2 class="text-black font-weight-bold font-opensans">Chef Name{{-- <span class="offer-chef">20% OFF</span> --}}</h2>
                                </a>
                                <h4 class="elipsis-text text-muted">
                                  <span class="font-montserrat">
                                    {{-- @foreach( $v->cuisines as $c1 => $c2)
                                    {{ strip_tags($c2->name) }}@if(!$loop->last), @endif
                                    @endforeach --}}
                                    cuisine categories like south-indian, north-indian
                                  </span>
                                </h4>
                                <p class="elipsis-text d-none font-montserrat d-sm-block">
                                  Description that explain about the dish or chef.
                                </p>
                                <div class="sqr-star">
                                  <div class=" star-rating ">

                                    <div class="overflow-hidden profile-asw-rate elipsis-text">
                                      <div class="">
                                        @for($x=1;$x<=5;$x++)
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        @endfor
                                        {{-- @if (strpos($v->ratings,'.')) --}}
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining remain-last" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        @php            
                                        $x++;
                                        @endphp
                                        {{-- @endif --}}
                                        @while ($x<=5)
                                        <label class="star-rating-star js-star-rating">
                                          <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                        </label>
                                        @php
                                        $x++;
                                        @endphp
                                        @endwhile
                                      </div>
                                      {{-- @if($v->ratings) --}}
                                      <div class="rating-text">
                                        <span class="star-points text-black">5.5</span>
                                        <div class="font-montserrat">(120 Reviews)</div>
                                      </div>
                                      {{-- @endif --}}
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="text-center">
                              <div class="tag-ribbon p-2">
                                <a href="javascript:void(0)"><span class=" fa fa-bookmark-o"></span></a>
                              </div>
                              <div class="pricefornos">
                                <h4 class="text-theme font-montserrat text-nowrap">&#8377;2 for two</h4>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                    <div class="chefsfood cuisine-detail-asw-food">
                      {{-- @if($v->get_vendor_food_details) --}}
                      <div class="owl-carousel see-more owl-theme">
                        {{-- @foreach($v->get_vendor_food_details as $food_k => $food_val) --}}
                        @for($i=0;$i<5;$i++)
                          <div class="item">
                            <div class="chefsfoodlists  text-lg-left">
                              <div class="foodimg">
                                <a href=""><img src="{{asset("assets/front/img/rice.jpg")}}" alt=""></a>
                              </div>
                              <div class="fooddesc">
                                <div>
                                  <div class="d-flex justify-content-between">
                                    <h2 class="food-name text-black">
                                      <a href="" class=" font-opensans">Dish Name</a>
                                    </h2>
                                    <div class="foodprice">
                                      <h2 class="text-black">&#8377;50</h2>
                                    </div>
                                  </div>
                                  <p class="elipsis-text font-montserrat">This is desciption to explain about the dishm due to this explanation customer can identify about dish of the chef, thatwise , this is useful for customers who will want to order from the chefs.</p>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endfor
                        {{-- @endforeach --}}
                      </div>

                      {{-- @endif --}}

                    </div>

                  </div>
                @endfor
                {{-- @endforeach --}}
                {{-- @endif --}}
              </div>
            </div>
          </div>

        {{-- @endif --}}
      </div>

    </div>
  </div>
</div>

@endsection