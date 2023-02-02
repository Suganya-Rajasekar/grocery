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
@foreach($chefdish as $k => $v)
<div class="food-detailed-lists mb-2 post-id" id="<?php echo $v->id; ?>" data-id="<?php echo $v->vendor_id; ?>" data-type="@if($type) {{$type}} @endif">
	<div class="row">
		<div class="col-xl-3 col-md-4 col-sm-4">
			<div class="foodsdetails">
				<div class="foodimgss">
					<?php $tag = tags_status($v->tag_type);?>
					@if($tag['none'] == 0)	
						@if($tag['bestsell'] == 1)
						<span class="badge badge-primary bestsell position-absolute l-con" style="left:7px;padding: 5px;">Bestseller</span>
						@endif
						@if($tag['special'] == 1)
						<span class="badge badge-danger mpopular position-absolute l-con" @if($tag['bestsell'] == 1) style="top:30px;" @endif>Chef's special</span>
						@endif
						@if($tag['must_try'] == 1)
						<span class="badge badge-success recommend position-absolute l-con" @if($tag['bestsell'] == 1 || $tag['special'] == 1) style="top:30px;" @endif>Must try</span>
						@endif
					@endif
					<a href="#" data-toggle="modal" data-target="#profilemodal{{$v->id}}" onclick="menuinfo('{{$v->id}}')">
						<img src="{{$v->image}}" alt="">
					</a>
				</div>
			</div>
		</div>
		<div class="col-xl-9 col-md-8 col-sm-8 ">
			<div class="share-option-top">
				<div class="foodstat">
					<ul class="prep-time">
						<li class="font-weight-bold">
							<span class="float-left font-opensans">{{$v->name}}</span>
							@if($v->discount_price != 0) 
								<del class="float-right" style="color: red;">&#8377;{{$v->price}}</del>
							@else 
								<span class="float-right">&#8377;{{$v->price}}</span>
							@endif
						</li>
						@if($v->discount_price != 0) 
						<li class="font-weight-bold">
							<span class="float-right">&#8377;{{ $v->discount_price }}</span>
						</li>
						@endif
						{{-- <li class="prep-asw">
							<span class="float-left font-montserrat">Delivers {{strtolower($v->preparation_time_text)}}</span>
							 <span class="float-right font-montserrat">{{$v->preparation_time_text}}</span>
						</li> --}}
					</ul>
					<div class="details">
						{{-- <h5 class="font-montserrat">Details</h5> --}}
						<p class="text-muted text-justify read-more-cont font-montserrat muted-font">{{ strip_tags($v->description) }}</p>
						<span class="read-more">Read more</span>
					</div>
					<div class="likesandcomment">
						<p class="likes font-montserrat">{{$v->likes_count_text}}</p>
						<p class="text-muted muted-font font-montserrat">view all {{$v->comments_count}} comment</p>
					</div>
				</div>
				<div class="chefdetails overflow-hidden">
					<div class="float-left">
						<ul class="like-share">
							<span>
								<a href="javascript:void(0)" onclick="updateFavorites( {{ $v->id }} )"><i class="@if($v->is_favourites == 1) fa fa-heart @else fa fa-heart-o @endif"></i></a>
							</span>
							<span>
								<a href="#" class="comment-dish" data-toggle="modal" data-target="#profilemodal{{$v->id}}" onclick="menuinfo('{{$v->id}}')">
									<img src="{{ asset('assets/front/img/comment.svg') }}" alt=""/>
								</a>
							</span>
							<span>
								<a href="javascript:;" class="share-mod-btn-asw">
									<img src="{{ asset('assets/front/img/share.svg') }}" alt=""/>
								</a>
							</span>
						</ul>
					</div>
					{{-- <?php
					$userid    = \Auth::check() ? \Auth::user()->id : '';
					$cookie    = \Session::has('cookie') ? \Session::get('cookie') : \Cookie::get('mycart');
					$cart_sametime = '';
					$cart = uCartQuery($userid,$cookie)->orderbyDesc('id')->first();
					if($cart) {
						$cart_sametime  = $cart->is_samedatetime; 
					}
					?> --}}
					@if($chefinfo->avalability == 'avail')
					<div class="float-right text-center">
						@if($chefinfo->type == 'event' || $chefinfo->type == 'home_event')
						<a href="#" class="add-asw btn font-montserrat btn-theme btn-small bordered-small-button @if($chefinfo->type == 'home_event') homeevent_add @endif" data-id="{{ $v->id }}" data-toggle="modal" data-target="#exampleModal{{$v->id}}">Add</a>
						@elseif($chefinfo->type != 'event')
							<div class="itemadd_{{ $v->id }}">
							@if($v->discount_price > 0 && $v->purchase_quantity_count == 0)
								<!--- for purchase quantity restriction----> 
								<a href="javascript:void(0)" class="add-asw btn font-montserrat btn-theme btn-small bordered-small-button firstitem" onclick="purchase_quantity()">Add</a>
							@else
								<a href="#" class="add-asw btn font-montserrat btn-theme btn-small bordered-small-button firstitem" data-toggle="modal" data-target="#exampleModal{{$v->id}}" @if(($cartcount->count == 0 && $cartcount->cart_datetimeslot->is_samedatetime == 'no') || ($cartcount->count > 0 && $cartcount->cart_datetimeslot->is_samedatetime == 'no')) style="display:block;" @else style="display:none";  @endif onclick="timeupdate({{$v->id}})">Add</a>

								<a href="#" class="add-asw btn font-montserrat btn-theme btn-small bordered-small-button after_confirm_samedatetime" data-toggle="modal" @if((isset($cartcount->cart_datetimeslot->cart_unavailable) && $cartcount->cart_datetimeslot->cart_unavailable == true)) data-target="#unavailable_cart" @else data-target="#exampleModal{{$v->id}}" @endif @if($cartcount->count > 0 && $cartcount->cart_datetimeslot->is_samedatetime == 'yes') style="display:block;" @else style="display:none;" @endif>Add</a>
							@endif
							</div>
							<div class="limitexceed_{{ $v->id }}" style="display: none;">
								<input type="hidden" class="discount_{{ $v->id }}" value="{{ $v->discount_price }}">
								<input type="hidden" class="pquantitycount_{{ $v->id }}" value="{{ ($v->purchase_quantity_count == 1) ? 0 : $v->purchase_quantity_count }}">
							</div>
						@endif
						<br>
						<span class="text-theme cuspan-asw font-montserrat">@if(count($v->addons) > 0) Customizable @endif</span>
					</div>
					@else
					<div class="float-right text-center">
						<a href="javascript:void(0)" class="add-asw btn font-montserrat btn-theme btn-small bordered-small-button" style="background-color: grey;color: white;">Add</a>
					</div>
					@endif
				</div>
			</div>
			@include('frontend.details-modal')
			<!-- modal start profile popup-->
			<div class="modal my-mod-2 fade" id="profilemodal{{$v->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-xl">
					<div class="modal-content">
						<div class="modal-body" id="commentbox{{$v->id}}">
						</div>
					</div>
				</div>
			</div>
			<!-- modal end profile popup-->
			<div class="share-option-asw">
				<div class="share-option"> 
					<div class="">
						<div class="options d-inline">
							<div class="option-btn d-inline">
								<a href="https://www.facebook.com/sharer.php?caption=Knosh&description=This is the best food I ever had.Please try out my recommendation of this amazing dish {{ $v->name }}.&u={!!url('/chefmenu/'.$v->id)!!}&picture={{$v->image}}" target="_blank" class="fb-share">
									<i class="fa fa-facebook"></i>
								</a>
							</div>
						</div>
						<div class="options d-inline">
							<div class="option-btn d-inline">
								<a href="mailto:?subject=Knosh&amp;body=This is the best food I ever had.Please try out my recommendation of this amazing dish {{ $v->name }}. {!!url('/chefmenu/'.$v->id)!!}" class="email-share">
									<i class="fa fa-envelope"></i>
								</a>
							</div>
						</div>
						<div class="options d-inline">
							<div class="option-btn d-inline">
								{{-- <a href="https://web.whatsapp.com://send?text={!!url('/menuaddon/'.$v->id)!!} Knosh food from the home of india" class="whatsapp-share">
									<i class="fa fa-whatsapp"></i>
								</a> --}}
								<a href="whatsapp://send?text=This is the best food I ever had.Please try out my recommendation of this amazing dish {{ $v->name }}. {!!url('/chefmenu/'.$v->id)!!}" data-action="share/whatsapp/share" target="_blank"> <i class="fa fa-whatsapp"></i> </a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endforeach
{{-- <div class="ajax-load text-center" style="display:none">
	<p><img src="<?= \URL::To('loading.gif'); ?>">Loading More post</p>
</div> --}}
