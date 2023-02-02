<button type="button" class="close" data-dismiss="modal">×</button>
<div class="chef-det mt-5">
    <div class="d-flex">
        @if(isset($review_data->reviews))
        <div class=" ">
           <div class="chefimg">
            <img src="{{$review_data->reviews->avatar}}" alt="">
            </div>
        </div>
        <div class="w-100 ml-3"> 
            <div class="o-hid">
                <div class="float-left">
                    <div class="chefname">
                        <h3 class="text-black font-weight-bold">{{ $review_data->reviews->name }}</h3>
                         @foreach( $review_data->reviews->cuisines as $c1 => $c2)
                          <h5 class="text-muted">{{ $c2->name }}@if(!$loop->last), @endif</h5>
                        @endforeach
                       
                        <h5 class="text-muted">ID #{{ $review_data->reviews->get_orders[0]->s_id }}</h5>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="o-hid">
    <form action="" method="post" class="reviewform">
        {!! csrf_field() !!}
        @if(empty($review_data->reviews->chefrating_order->id))
        <input name="_method" type="hidden" value="POST">
        <input type="hidden" name="user_id" value="{{ \Auth::user()->id}}">
        @else
        <input name="_method" type="hidden" value="PUT">
        <input type="hidden" name="review_id" value="{{ $review_data->reviews->chefrating_order->id }}">
        @endif
        <div class="ft">
            <div class="sqr-star my-3">
                <h5 class="font-weight-bold text-black">Rate your chef</h5>
                <div class=" star-rating ">
                    <div class="overflow-hidden">
                        <div class="">
                            <input type="hidden" name="order_id" value="{{ $review_data->reviews->order_id }}">
                            <input type="hidden" name="vendor_id" value="{{ $review_data->reviews->id }}">
                            <input name="rating" type="radio" value="5" id="condition_5" class="star-rating-input inverted-rating" @if(isset($review_data->reviews->chefrating_order) && !empty($review_data->reviews->chefrating_order) && $review_data->reviews->chefrating_order->rating=='5') checked @endif>
                            <label for="condition_5" class="star-rating-star js-star-rating">
                                <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                </svg>
                            </label>
                            <input name="rating" type="radio" value="4" id="condition_4" class="star-rating-input inverted-rating" @if(isset($review_data->reviews->chefrating_order) && !empty($review_data->reviews->chefrating_order) && $review_data->reviews->chefrating_order->rating=='4') checked @endif>
                            <label for="condition_4" class="star-rating-star js-star-rating">
                                <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                </svg>
                            </label>
                            <input name="rating" type="radio" value="3" id="condition_3" class="star-rating-input inverted-rating" @if(isset($review_data->reviews->chefrating_order) && !empty($review_data->reviews->chefrating_order) && $review_data->reviews->chefrating_order->rating=='3') checked @endif>
                            <label for="condition_3" class="star-rating-star js-star-rating">
                                <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                </svg>
                            </label>
                            <input name="rating" type="radio" value="2" id="condition_2" class="star-rating-input inverted-rating" @if(isset($review_data->reviews->chefrating_order) && !empty($review_data->reviews->chefrating_order) && $review_data->reviews->chefrating_order->rating=='2') checked @endif>
                            <label for="condition_2" class="star-rating-star js-star-rating">
                                <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                </svg>
                            </label>
                            <input name="rating" type="radio" value="1" id="condition_1" class="star-rating-input inverted-rating" @if(isset($review_data->reviews->chefrating_order) && !empty($review_data->reviews->chefrating_order) && $review_data->reviews->chefrating_order->rating=='1') checked @endif>
                            <label for="condition_1" class="star-rating-star js-star-rating">
                                <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                </svg>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="feedback-form">
                <h5 class="font-weight-bold text-black">Share your feedback</h5>
                <?php 
                $review = '';
                if(isset($review_data->reviews->chefrating_order) && !empty($review_data->reviews->chefrating_order)) {
                    $review = $review_data->reviews->chefrating_order->reviews;
                }
                ?>
                    <textarea name="reviews" id="reviews" rows="5" width="100%" class="form-control">{{ $review }}</textarea>
                    <input type="submit" class="btn btn-theme float-right mt-4 mb-2" value="submit">
            </div>
        </div>
    </form>    
    </div>
</div>