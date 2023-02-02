<div class="w-100 ml-md-5 ml-2  pt-md-4">
    <div class="o-hid d-flex justify-content-between">
        <div class="">
            <div class="chefname">
                <h2 class="text-black font-weight-bold font-opensans">{!! $chefinfo->name !!}{{-- <span class="offer-chef">20% OFF</span> --}}</h2>
                @if($chefinfo->chef_restaurant->type == "event")
                <div class="text-muted">
                    <span><b> Location:</b>{{ $chefinfo->chef_restaurant->adrs_line_1 }}</span>
                </div>
                <div class="text-muted mt-3 mb-3">
                    <span><b>Event date & time:</b>{!! date('d-m-Y',strtotime($chefinfo->chef_restaurant->event_time[0])).' <b>/</b> '.$chefinfo->chef_restaurant->event_time[1] !!}</span>

                </div>
                @else
                <h4 class="text-muted font-montserrat">
                    @foreach( $chefinfo->cuisines as $c1 => $c2)
                    <span class="home-span">{!! $c2->name !!}@if(!$loop->last), @endif
                    </span>
                    @endforeach
                </h4>
                <h4 class="text-muted font-montserrat">{{ $chefinfo->chef_restaurant->chef_sector }}</h4>
                @endif
                {{-- <h4 class="text-muted font-montserrat">{!! $chefinfo->chef_restaurant->adrs_line_1 !!}</h4> --}}
                @if($chefinfo->chef_restaurant->description != '')
                <p class="font-montserrat read-more-cont text-justify">{!! $chefinfo->chef_restaurant->description !!}</p>
                <span class="read-more">Read more</span>
                @endif
            </div>
        </div>
        <div class="float-right bookmark-asw">
            <div class="tag-ribbon">
                <a href="javascript:void(0)" onclick="updateBookmark({!! $chefinfo->id !!})"><span class="@if($chefinfo->is_bookmarked == 1) fa fa-bookmark @else fa fa-bookmark-o @endif"></span></a>
            </div>
        </div>
    </div>
    <div class="o-hid">
        <div class="">
            <div class="sqr-star">
                <div class=" star-rating ">
                    <div class="overflow-hidden">
                        <div class="">
                            @if($chefinfo->ratings != 0)
                            @for($i=1;$i<=$chefinfo->ratings;$i++)
                                <label class="star-rating-star js-star-rating">
                                    <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                </label>
                            @endfor
                            @if (strpos($chefinfo->ratings,'.'))
                            <label class="star-rating-star js-star-rating">
                                <svg class="svg-inline--fa fa-star fa-w-18 remaining remain-last" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                            </label>
                            @php
                            $i++;
                            @endphp
                            @endif
                            @while ($i<=5)
                            <label class="star-rating-star js-star-rating">
                                <svg class="svg-inline--fa fa-star fa-w-18 remaining" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                            </label>
                            @php
                            $i++;
                            @endphp
                            @endwhile
                            @if($chefinfo->ratings)
                            <span class="star-points font-montserrat text-black">{{$chefinfo->ratings}}</span>
                            <span class="font-montserrat text-muted">&nbsp;({{$chefinfo->reviewscount}} Reviews)</span>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($chefinfo->chef_restaurant->type != "event" && $chefinfo->chef_restaurant->type != 'home_event')
        <div class="pricefornos my-md-3">
            <h4 class="text-theme font-montserrat">@if( isset($chefinfo->chef_restaurant) ) &#8377;{{$chefinfo->chef_restaurant->budget_name}}@endif</h4>
        </div>
        @endif
    </div>
</div>