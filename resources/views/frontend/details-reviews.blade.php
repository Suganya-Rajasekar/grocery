<div class="foodstat">
    <div class="details">
        <h4 class="font-opensans">Overall Rating</h4>
        <div>
            @for($x=1;$x<=$chefinfo->ratings;$x++)
            <label class="star-rating-star js-star-rating">
                <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
            </label>
            @endfor
            @if (strpos($chefinfo->ratings,'.'))
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
            <span class="font-montserrat">{{$chefinfo->ratings}}</span>
            <span class="text-muted font-montserrat">({{$chefinfo->reviewscount}} Reviews)</span>
        </div>
    </div>
</div>
{{-- <?php dd($chefinfo->publishedreviews); ?> --}}
@foreach($chefinfo->publishedreviews as $rev_k => $rev_v)
<div class="commend-food">
    <div class="custcomment">
        <div class="customer-image float-left mr-3">
            <a href="{!!url('/chef/'.$rev_v->vendor_id)!!}" ><img @if($rev_v->comment_user != null) src="{{$rev_v->comment_user->avatar}}" @else src="" @endif alt=""></a>
        </div>
        <div class="customer-comment over-hid">
            <div class="custname-likes  ">
                <div class="d-flex">
                    <div class="float-left">
                        <h4 class="font-weight-bold font-opensans">@if($rev_v->comment_user != null) {{$rev_v->comment_user->name}} @endif<span class="text-muted">({{$rev_v->day}})</span></h4>
                        <div>
                            @for($x=1;$x<=$rev_v->rating;$x++)
                            <label class="star-rating-star js-star-rating">
                                <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                            </label>
                            @endfor
                            @if (strpos($rev_v->rating,'.'))
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
                            <span class="font-montserrat">{{$rev_v->rating}}</span>
                        </div>
                        <p class="text-secondary font-montserrat">{{$rev_v->reviews}}</p>
                    </div>
                </div>
                @foreach($rev_v->reply as $k => $v)
                <div class="cust-reply d-flex">
                    <div class="customer-reply-img float-left mr-3">
                        <img src="{{ $v->vendorinfo->avatar }}" alt="">
                    </div>
                    <div class="float-left">
                        <h4 class="font-weight-bold font-opensans">{{ $v->vendorinfo->name }}</h4>
                        <p class="text-secondary font-montserrat">{{ $v->reviews }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endforeach