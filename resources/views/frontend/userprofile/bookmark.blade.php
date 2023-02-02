<div class="setting-main-area bookmark-asw tab-pane fade  verification_area @if(last(request()->segments()) == 'bookmark') active show @endif "   id="bookmark">

    @if(!(\Request::has('bPage')))
    <div class="py-3 d-flex justify-content-between align-items-center">
        <h4 class="d-inline font-weight-bold font-opensans">Bookmarks</h4>
        <div class="d-lg-none profile-asw-menu d-inline"><i class="fa fa-bars"></i></div>
    </div>
    @endif 
    @if( isset($bookmark) && count($bookmark->get_bookmarks) >   0)
    @foreach( $bookmark->get_bookmarks as $k => $v)
    @php
    $get_chef=$v->get_vendor_details;
    @endphp
    <div class="settings-content-area">
        <div class="searchbychef-das">
            <div class="chef-lists ">
                <div class="chefdetails my-3">
                    <div class="d-flex">
                        <div class=" ">
                            <div class="chefimg">
                                <a href="{!!url('/chef/'.$v->vendor_id)!!}" > <img src="{{ $get_chef->avatar }}"> <!--class="cor-height-top"-->
                                    @if($get_chef->promoted == 'yes')
                                    <div class="cor-height-top-ad">
                                        <span>
                                            AD
                                        </span>
                                    </div>
                                    @endif
                                    @if($get_chef->celebrity == 'yes')
                                    <div class="ribbon down">
                                        <div class="content fas fa-star"></div>
                                    </div>
                                    @endif
                                    @if($get_chef->certified == 'yes')
                                    <div class="ribbon1 up">
                                        <div class="content">
                                            <img src="{{ asset('assets/front/img/vegan.png') }}">
                                        </div>
                                    </div>
                                    @endif
                                </a>
                            </div>
                        </div>
                        <div class="w-100 ml-1 ml-sm-3">
                            <div class="o-hid d-flex justify-content-between justify-content-md-start">
                                <div class="w-asw-85">
                                    <div class="chefname">
                                        <a href="{!!url('/chef/'.$v->vendor_id)!!}" ><h2 class="text-black mb-0 mb-md-3 elipsis-text font-weight-bold font-opensans">{{ strip_tags($get_chef->name) }}{{-- <span class="offer-chef">20% OFF</span> --}}</h2></a>
                                        <h4 class="text-muted elipsis-text mb-0 mb-md-3">
                                            <span class="home-span font-montserrat">
                                                @foreach( $get_chef->cuisines as $c1 => $c2){{ strip_tags($c2->name) }}@if(!$loop->last), @endif
                                                @endforeach
                                            </span>
                                        </h4>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="tag-ribbon">
                                        <a href="javascript:void(0)" onclick="updateBookmark( {{ $get_chef->id }} )"><span class="@if($get_chef->is_bookmarked == 1) fa fa-bookmark @else fa fa-bookmark-o @endif"></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="o-hid d-flex align-items-center justify-content-between">
                                <div class="">
                                    <div class="sqr-star">
                                        <div class=" star-rating ">
                                            <div class="overflow-hidden profile-asw-rate">
                                                <div class="">

                                                    @for($x=1;$x<=$get_chef->ratings;$x++)
                                                    <label class="star-rating-star js-star-rating">
                                                        <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                                    </label>
                                                    @endfor
                                                    @if (strpos($get_chef->ratings,'.'))
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
                                                <div>
                                                    <span class="star-points text-black">{{$get_chef->ratings}}</span>
                                                    <div class="font-montserrat">({{$get_chef->reviewscount}} Reviews)</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="pricefornos">
                                        <h4 class="mb-0 font-montserrat">&#8377;{!! $get_chef->get_first_restaurant->budget_name !!}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                $food_items=$v->get_vendor_food_details;
                @endphp

                @if( isset($food_items) && count($food_items) > 0)

                <div class="chefsfood">
                    <div class="owl-carousel owl-bookmark ">
                        @foreach($food_items as $ke => $val)
                        <div class="item">
                            <div class="chefsfoodlists">
                                <div class="foodimg">
                                    <a href="{!!url('/menuaddon/'.$val->id)!!}"><img src="{{ $val->image }}" alt=""></a>
                                </div>
                                <div class="fooddesc">
                                    <h3 class="food-name font-montserrat text-black">
                                        {{ strip_tags($val->name) }}
                                    </h3>
                                    <p class="elipsis-text text-muted font-montserrat">{{ strip_tags($val->description) }}</p>
                                </div>
                                <div class="foodprice">
                                    <h3 class="text-black font-montserrat">&#8377;{{ $val->price }}</h3>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        <br>
    </div>
    @endforeach
    <div class="paginate"></div>
    @if(!(\Request::has('bPage')) && $bookmark->get_bookmarks != '' )
    <button class="btn btn-default col-md-12 profileModule"  name="bookmark" type="button">Load More</button>
    @endif
    @else
        @if(!(\Request::has('bPage')))
        <div class="text-center">
            <img src="https://sustain.round.glass/wp-content/themes/sustain/assets/images/no-results.png" alt="" width="200px">
            <p>No bookmarks marked yet.</p>
        </div>
        @endif
    @endif
    <!-- bookmark -->
</div>
