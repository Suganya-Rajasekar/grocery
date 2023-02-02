@if($search->data)
	@foreach($search->data as $chef_key => $chef_val)
		<div class="search-chef-asw">
			<div class="d-flex justify-content-start">
				<div class="cheff-im">
					<a href="{!!url('/chef/'.$chef_val->id)!!}">
						<img src="{{$chef_val->avatar}}" class="">
						@if($chef_val->celebrity == 'yes')
						<div class="ribbon down">
							<div class="content fas fa-star"></div>
						</div>
						@endif
						@if($chef_val->promoted == 'yes')
						<div class="cor-height-top-ad">
							<span>
								AD
							</span>
						</div>
						@endif
						@if($chef_val->certified == 'yes')
						<div class="ribbon1 up">
							<div class="content">
								<img src="{{ asset('assets/front/img/vegan.png') }}">
							</div>
						</div>
						@endif
					</a>
				</div>
				<div class="ml-1 ml-sm-3 w-100 mw-0">
					<div class=" d-flex justify-content-between">
						<div class="desc-asw ">
							<a href="{!!url('/chef/'.$chef_val->id)!!}"><h3 class="name font-opensans">{{$chef_val->name}}</h3></a>
							<span class="text-muted couisin font-montserrat">
								@foreach( $chef_val->cuisines as $c1 => $c2)
									{{ $c2->name }}@if(!$loop->last), @endif
								@endforeach
							</span>
							<p class="d-none d-md-block font-montserrat read-more-cont text-justify">{!! $chef_val->chef_description !!}</p>
							<span class="read-more">Read more</span>
						</div>
						<div class="tag-ribbon">
							<a href="javascript:void(0)" onclick="updateBookmark({!! $chef_val->id !!})"><span class="@if($chef_val->is_bookmarked == 1) fa fa-bookmark @else fa fa-bookmark-o @endif"></span></a>
					</div>
					</div>
					<div class="d-flex justify-content-between">
						<div class="sqr-star">
							<div class=" star-rating ">
								<div class="overflow-hidden">
									<div class="float-left">
										@for ($i = 1; $i <= $chef_val->ratings; $i++)
										<label for="condition_5" class="star-rating-star js-star-rating">
											<svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
										</label>
										@endfor
										@if (strpos($chef_val->ratings,'.'))
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
										@if($chef_val->ratings)
										<span class="review-count-asw font-montserrat"><span>{!! $chef_val->ratings !!}</span> ({!! $chef_val->reviewscount !!} review)</span>
										@endif
									</div>
								</div>
							</div>
						</div>
						@if($chef_val->singlerestaurant != null)
						<div>
							<span class="font-montserrat">&#8377;{!! $chef_val->singlerestaurant->budget_name ?? '' !!}</span>
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>

		<div class="py-5">
			<div class="">
				@if($search->data)
					<div class="owl-carousel search_food">
						@foreach($chef_val->food_items as $k => $v)
							<div class="item">
								<div>
									<div class="food-lists-img-asw">
										<img src="{{$v->image}}" alt="" onclick='self.location="{!!url('/menuaddon/'.$v->id)!!}"'>
									</div>
									<div class="food-list-det-asw">
										<h2 class="font-weight-bold font-opensans text-black" onclick='self.location="{!!url('/menuaddon/'.$v->id)!!}"'>{{$v->name}}</h2>
										<h3 class="text-muted font-montserrat elipsis-text my-3">{{$v->description}}</h3>
										<h3 class="theme-color font-montserrat">&#8377;{{$v->price}}</h3>
										{{-- <p class="text-muted">@if(count($v->addons) > 0) Customizable @endif</p> --}}
										{{-- <a href="#" class="add-asw font-montserrat btn btn-theme-small btn-small addbutton" onclick='self.location="{!!url('/menuaddon/'.$v->id)!!}"'>Add</a> --}}
									</div>
									@include('frontend.details-modal')
								</div>
							</div>
						@endforeach
					</div>
				@endif
			</div>
		</div>
	@endforeach
	@else
	<script>
		$(document).ready(function(){
			$('.load').hide();
		})
	</script>
@endif   

