<!--modal start food-->
<div class="modal my-mod-1 fade" id="exampleModal{{$v->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			@php
				$first_unit='';
				$unit_price=$v->price;
				if($v->food_type == "home_event_menu") {
					$unit_price= $v->minimum_order_quantity * $v->price;
				}
			@endphp
			@if($v->unit_detail)
				@php
					$first_unit=$v->unit_detail[0]->id;
					$unit_price=$v->unit_detail[0]->price;
				@endphp
			@endif
			<div class="modal-header"> 
				<h5 class="modal-title" id="exampleModalLabel">
					<span class="foodname font-opensans">{{$v->name}}</span>
					<div class="d-flex">
						<div class="itembuttn" id="fnitem_<?php echo $v->id; ?>">
							<span class="add_dec_option remove_item font-montserrat" data-fid="{{$v->id}}" data-type="more_details" data-isad="" data-foodtype="{{ $v->food_type }}" data-min-qty="{{ $v->minimum_order_quantity }}">-</span>
							<h5 class="item-count font-montserrat afid_{{$v->id}}" id="afid_{{$v->id}}" data-id="{{$v->id}}" data-acprice="{{$v->price}}" data-disprice="{{ $v->discount_price }}" data-purchasequantity="{{ $v->purchase_quantity }}" data-pqcount="{{ ($v->purchase_quantity_count == 1) ? 0 : $v->purchase_quantity_count}}" data-staticpqcount="{{ $v->purchase_quantity_count }}">@if($v->food_type == 'home_event_menu') {{ $v->minimum_order_quantity }} @else 1 @endif</h5>
							<span data-fid="{{$v->id}}" class="add_dec_option add_item font-montserrat" data-isad="" data-id="{{$v->id}}" data-type="more_details" data-foodtype="{{ $v->food_type }}" data-min-qty="{{ $v->minimum_order_quantity }}">+</span>
						</div>
						<div class="text-theme price_s">
							@if($v->discount_price != 0) 
							&#8377;<del id="unitprice{{$v->id}}" class="font-montserrat unitprice">{{$unit_price}}</del>
							<span class="ml-3">
								&#8377;
								<span id="discount_price{{$v->id}}" class="font-montserrat ">{{$v->discount_price}}</span>
							</span>
							@else 
							&#8377;<span id="unitprice{{$v->id}}" class="font-montserrat unitprice">{{$unit_price}}</span>
							<input type="hidden" id="checked_theme_amt" {{-- value="@if($v->food_type == 'home_event_menu'){{ $v->theme_detail[0]->amount }} @endif" --}}>
							@endif
						</div>
					</div>
				</h5>

				<button type="button" class="close datatime{{$v->id}}" data-dismiss="modal" aria-label="Close" data-unit="{{$first_unit}}" data-id="{{$v->id}}" data-disprice="{{ $v->discount_price }}" data-myval="" data-pqcount="{{ $v->purchase_quantity_count }}" data-type="{{ $v->food_type }}">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="" data-pric="{{$unit_price}}" class="resrt ids{{$v->id}}">
					@php
					$first_unit='';
					@endphp
					@if($v->unit_detail)

					@php
					$first_unit=$v->unit_detail[0]->id;
					@endphp
					
					<div class="addon-sec over-hid @if(count($v->unit_detail) <= 1) d-none @endif">
						<div class="modalbody-head">
							<h5 class="text-black font-opensans font-weight-bold">Variants</h5>
						</div>
						<div class="cont" style="line-height: 2;"> 
							<ul class="over-hid ord-mod">
								@foreach($v->unit_detail as $ke => $unit_val)
								<li>
									<div class="float-left">
										<div class=" custom-checkbox">
											<label class="container-check" for="">{{ $unit_val->name }}
												<input type="radio" class="custom-control-input unit unit{{$v->id}}" name="unit" value="{{ $unit_val->id }}" data-id="{{$v->id}}" data-val="{{ $unit_val->price }}" @if($ke == 0) checked @endif data-discount="{{ $unit_val->discount_price }}">
												<span class="checkmark"></span>
											</label>
										</div>
									</div>
									<div class="float-right">
										@if($unit_val->discount_price != 0)
										<del class="foodprice">&#8377;{{ $unit_val->price }}</del>
										<span class="foodprice ml-3">&#8377;{{ $unit_val->discount_price }}</span>
										@else
										<div class="foodprice">&#8377;{{ $unit_val->price }}</div>
										@endif
									</div>
								</li>
								@endforeach

							</ul>
						</div>
					</div>

					@endif

					@if($v->food_type == "home_event_menu")
					@if($v->meal)
					<div class="addon-sec over-hid" id="model_{{ $v->id }}">
						<div class="modalbody-head">
							<h5 class="text-black font-opensans font-weight-bold">{{ $v->home_event_sections->meal_section_name }}</h5>
						</div>
						<div class="cont" style="line-height: 2;">
							<ul class="over-hid ord-mod">
								@foreach(explode(',',$v->meal) as $ke => $value)
								<li class="justify-content-between meal_list">
									<div class="float-left w-auto">
										<label>{{ $value }}</label>
									</div>
									<div class="float-right d-flex  w-auto">
										<div class="font-montserrat w-auto meal_count mealcount_{{ $value }}" @if($ke != 0) style="display: none; @endif">
											<input type="number" class="form-control meal-text-box" name="count" id="{{ $value }}_count" value="@if($ke == 0){{ $v->minimum_order_quantity }}@endif" data-id="{{ $v->id }}" data-meal="{{ $value }}" data-min-ord-count="{{ $v->minimum_order_quantity }}" style="width: 100px;border: 2px solid #f55a60;">
										</div>
										<div class="container-check ml-5">
											<input type="checkbox" class="custom-control-input meal meal{{ $v->id }}" data-type="{{ $value }}"  name="meal" value="{{ $value }}" data-menuid="{{ $v->id }}" @if($ke == 0) checked @endif>
											<span class="checkmarks eventcheck" ></span>
										</div>
									</div>
								</li>
								@endforeach
							</ul>
						</div>
					</div>
					@endif

					@if($v->preferences)
					<div class="addon-sec over-hid">
						<div class="modalbody-head">
							<h5 class="text-black font-opensans font-weight-bold">{{ $v->home_event_sections->preference_section_name }}</h5>
						</div>
						<div class="cont" style="line-height: 2;">
							<ul class="over-hid ord-mod">
								@foreach($v->preference_detail as $ke => $value)
								<li class="justify-content-between">
									<div class="float-left w-auto">
											<label>{{ $value->name }}</label>
									</div>
									<div class="float-right d-flex  w-auto">
										<div class="container-check">
											<input type="checkbox" class="custom-control-input preferences preference{{ $v->id }}" name="preferences" value="{{ $value->id }}" data-id="{{$value->id}}">
												<span class="checkmarks eventcheck"></span>
										</div>
									</div>
								</li>
								@endforeach
							</ul>
						</div>
					</div>
					@endif


					@if($v->themes)
					<div class="addon-sec">
						<div class="modalbody-head">
							<h5 class="text-black font-opensans font-weight-bold">{{ $v->home_event_sections->theme_section_name }}</h5>
						</div>
						<div class="cont" style="line-height: 2;"> 
							<ul class="ord-mod">
								@foreach($v->theme_detail as $ke => $value)
								<li class="themefor{{ $v->id }}">
									<div class="float-left">
										<div class="custom-checkbox d-flex align-items-center position-relative">
											<span class="theme_img_cls" data-mid="{{ $v->id }}" data-theme-id="{{ $value->id }}">
												<img src="{{ (isset($value->theme_images) && !empty($value->theme_images)) ? $value->theme_images[0] : asset('/storage/app/public/avatar.jpg') }}"  style="width:40px;height: 40px;cursor: pointer;" >
											</span>
											<label class="ml-3" for="">{{ $value->name }}</label>
										</div> 
										
										<div class="popup_img image{{ $value->id }}" id="image{{ $value->id }}" style="display: none;">	
											<div class="theme_img theme_img_close" data-mid="{{ $v->id }}" data-theme-id="{{ $value->id }}">
												<i class="fa fa-times" aria-hidden="true"></i>
											</div>
											<div class="owl-carousel owl-theme popup_imgcarousel w-100 h-100">
												@if(!empty($value->theme_images))
												@foreach($value->theme_images as $k => $image)
												<div class="item">
													<img src="{{ $image }}">
												</div>
												@endforeach
												@else 
												<div class="item">
													<img src="{{ asset('/storage/app/public/avatar.jpg') }}">
												</div>
												@endif
											</div>
										</div>
										
									</div>
									<div class="float-right d-flex justify-content-between">
										<div class="font-montserrat w-auto">&#8377;{{ $value->amount }}</div>
										<div class="container-check ml-5 w-auto">
											<input type="checkbox" class="custom-control-input themes theme{{ $v->id }}" name="themes" value="{{ $value->id }}"  data-id="{{$v->id}}" data-val="{{ $value->amount }}" data-checked-amount="0">
												<span class="checkmark eventcheck"></span>
										</div>
									</div>
								</li>
								@endforeach

							</ul>
						</div>
					</div>
					@endif

					@if($v->addons)
					<div class="addon-sec over-hid">
						<div class="modalbody-head">
							<h5 class="text-black font-opensans font-weight-bold">{{ $v->home_event_sections->addon_section_name}}</h5>
						</div>
						<div class="cont" style="line-height: 2;">
							<ul class="over-hid ord-mod">

								@foreach($v->addons as $ke => $addon_val)
								<li>
									<div class="float-left">
											<label>{{ $addon_val->name }}</label>
									</div>
									<div class="float-right d-flex justify-content-between">
										<div class="foodprice font-montserrat w-auto">&#8377;{{ $addon_val->price }}</div>
										<div class="container-check  w-auto">
											<input type="checkbox" class="custom-control-input addon addon{{$v->id}}" name="addon" value="{{ $addon_val->id }}" data-id="{{$v->id}}" data-val="{{ $addon_val->price }}">
											<span class="checkmarks eventcheck"></span>
										</div>
									</div>
								</li>
								@endforeach
							</ul>
						</div>
					</div>
					@endif
					@endif

					@if($v->food_type != "home_event_menu")
					@if($v->addons)
					<div class="addon-sec over-hid">
						<div class="modalbody-head">
							<h5 class="text-black font-opensans font-weight-bold">Addons</h5>
						</div>
						<div class="cont" style="line-height: 2;">
							<ul class="over-hid ord-mod">

								@foreach($v->addons as $ke => $addon_val)
								<li>
									<div class="float-left">
										<div class=" custom-checkbox">
											<label class="container-check" for="">{{ $addon_val->name }}
												<input type="checkbox" class="custom-control-input addon addon{{$v->id}}" name="addon" value="{{ $addon_val->id }}" data-id="{{$v->id}}" data-val="{{ $addon_val->price }}">
												<span class="checkmarks"></span>
											</label>
										</div>
									</div>
									<div class="float-right">
										<div class="foodprice font-montserrat">&#8377;{{ $addon_val->price }}</div>
									</div>
								</li>
								@endforeach
							</ul>
						</div>
					</div>
					@endif
					@endif
				</form>
				@php
				$hours	= date(('H'),strtotime($v->preparation_time));
				@endphp
				{{--$hours--}}
			</div>
			<div class="modal-footer justify-content-center">
				<div class="over-hid ord-mod-total">
					<span class="float-left text-theme font-weight-bold font-montserrat f-24">Total</span>
					<span class="float-right text-theme font-montserrat f-24">&#8377;<span id="total_price{{$v->id}}">@if($v->discount_price != 0) {{ $v->discount_price }} @else{{$unit_price}} @endif</span></span>
				</div>
				@if(isset($chefinfo) && $chefinfo->type != 'event' && $chefinfo->type != 'home_event')
				<div class="order_time" @if($cartcount->cart_datetimeslot->is_samedatetime == 'no') style="display:block;width:100%;" @else style="display:none;" @endif>
				<div class="d-flex order-btn w-100">
					@if(($v->preparation_time=='2_to_3hrs' || $v->preparation_time=='1_to_2hrs') && ($v->today_order_disable == false))
						@php
							$today_date=date('Y-m-d');
						@endphp
						<button type="button" data-toggle="modal" data-target="#exampleModaldelivery_timetoday{{$v->id}}"  data-dismiss="modal" class="btn font-montserrat ordertoday" data-id="{{$v->id}}">Order for today</button>
					@else
						@php
							$today_date=date('Y-m-d',strtotime("+1 day"));
						@endphp
						<button type="button" class="btn tooltip-btn-asw cancel-asw font-montserrat" disabled>Order for today</button>
					@endif

					<button type="button" class="btn font-montserrat" data-toggle="modal" data-target="#exampleModaldate{{$v->id}}" data-dismiss="modal">Order for future</button>
					
				</div>
				</div>
				@elseif($chefinfo->type == 'home_event')
					<button type="button" style="width:100%;" class="btn font-montserrat" data-toggle="modal" data-target="#exampleModaldate{{$v->id}}" data-dismiss="modal" @if($cartcount->cart_datetimeslot->is_samedatetime == 'yes') style="display:none;" @endif>Add to cart</button>
				@else 
					<button type="button" style="width:100%;" class="btn font-montserrat" onclick="lastresult('{{$v->id}}','no','event')">Add to cart</button>
				@endif
				<div class="order-btn addtocart" @if(isset($cartcount) && $cartcount->cart_datetimeslot->is_samedatetime == 'yes') style="display:block;" @else style="display:none;" @endif>
						@php
							$today_date=date('Y-m-d');
						@endphp
					<button type="button" style="width:100%;" class="btn font-montserrat" onclick="lastresult({{$v->id}},'yes','samedatetime_all')">Add to cart</button>
				</div>
				<div class="tooltip-asw">
					<p>This dish is available only on Pre-Booking a day prior</p>
				</div>
			</div>
		</div>
	</div>
</div>
<!--modal end-->

<!-- <a href="#" class="btn btn-theme btn-small bordered-small-button" data-toggle="modal" data-target="#exampleModaldate">datepicker modal</a> -->
<!--modal start choose date-->
<div class="modal my-mod-date fade" id="exampleModaldate{{$v->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title m-auto" id="exampleModalLabel">
					<span class="foodname font-opensans">Order for future</span>
				</h5>
				<button type="button" class="close datatime{{$v->id}}" data-dismiss="modal" aria-label="Close" data-unit="{{$first_unit}}" data-id="{{$v->id}}" data-myval="">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="" data-pric="{{$unit_price}}" class="resrt ids{{$v->id}}">
					<div class="addon-sec over-hid">
						<div class="modalbody-head">
							<!-- <form name="myForm" action="{{ url('myurl') }}" method="POST"> -->
								<div id="datepicker{{$v->id}}" name='datepicker_start' class="datepickerdish" data-id="{{$v->id}}" data-offdates="{{!empty($offtime) ? json_encode($offtime) : ''}}" data-offdays="{{!empty($offdays) ? json_encode($offdays) : ''}}"></div>
								<input type="hidden" name="test" value="10">
								<!-- <input type="submit" name="submit" value="submit">  
							</form> -->
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn cancel-asw font-montserrat" data-toggle="modal" data-target="#exampleModal{{$v->id}}" data-dismiss="modal">Cancel</button>
				@if($v->food_type == "home_event_menu")
					<button type="button" class="btn font-montserrat datebtn{{$v->id}}" onclick="lastresult('{{$v->id}}','no','home_event')" disabled="">Add</button>
				@else 
					<button type="button" class="btn font-montserrat datebtn{{$v->id}}" data-toggle="modal" data-target="#exampleModaldelivery_time{{$v->id}}" data-dismiss="modal" disabled="">Confirm</button>
				@endif
			</div>
		</div>
	</div>
</div>
<!--modal end choose date-->

<!--modal start delivery time-->
<div class="modal my-mod-time fade" id="exampleModaldelivery_timetoday{{$v->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">
					<span class="foodname font-opensans">Delivery time slots</span>
				</h5>
				<button type="button" class="close datatime{{$v->id}}" data-dismiss="modal" aria-label="Close" data-unit="{{$first_unit}}" data-id="{{$v->id}}" data-myval="">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="height: 500px;">
				<?php  
					/*$now = time();
					$now = strtotime('12:30pm');
					$rounded = date('H:i', ceil(time()/1800)*1800);
					echo $rounded;*/
				?>
				<form action="" data-pric="{{$unit_price}}" class="resrt ids{{$v->id}} appendid{{$v->id}}">
{{-- 					@if($timeslots)
					@foreach($timeslots as $slot_k => $slot_val)
					<div class="addon-sec over-hid">
						<div class="modalbody-head">
							<h5 class="text-black font-weight-bold font-opensans">{{ $slot_val->name }}</h5>
						</div>
						<div class="cont" style="line-height: 2;">
							<ul class="over-hid">
								@if($slot_val->slots)
								@foreach($slot_val->slots as $time_k => $time_val)
								@php
								$now = ceil(time()/1800)*1800;
								$chunks = explode('-', $time_val->time_slot);

								$open_time = strtotime($chunks[0]);
								$close_time = strtotime($chunks[1]);
								$hours = DB::table('menu_items')->select('preparation_time')->where('id',$v->id)->first();
								$AddMins  = (date('i')>30) ? ((60 * 60) + (60 * 60) + (60 * 60)) : ((60 * 60) + (60 * 60));
								if($hours->preparation_time == '1_to_2hrs'){
									$AddMins = (date('i')>30) ? ((60 * 60) + (60 * 60)) : (60 * 60); 
								}
								$open_time -= $AddMins;
								$close_time -= $AddMins;

								@endphp --}}

								{{--date ("G:i", $open_time)--}}
								{{--date ("G:i", $close_time)--}}
								{{--date ("G:i", $now)--}}
								{{-- <li>
									<div class="float-left">
										@if((($open_time <= $now) || ($open_time >= $now)) && ($now <= $close_time) ))
										@php $i = 0; @endphp
										@if($i == 0 || $i != 2)
										@php
										$i = 1;
										$first_timeslot = $time_val->id;
										@endphp
										@endif
										<div class=" custom-checkbox">
											<label class="container-check" for="">{{ $time_val->time_slot }}
												<input type="radio" class="custom-control-input timeslot timeslot{{$v->id}}" name="timeslot" value="{{ $time_val->id }}" data-id="{{$v->id}}">
												<span class="checkmark"></span>
											</label>
										</div>
										@php
										$i = 2;
										@endphp
										@else
										<div class=" custom-checkbox">
											<label class="container-check"  for="">{{ $time_val->time_slot }}
												<input type="radio" disabled class="disable custom-control-input timeslot timeslot{{$v->id}}" name="timeslot" value="{{ $time_val->id }}" data-id="{{$v->id}}">
												<span class="checkmark"></span>
											</label>
										</div>
										@endif
									</div>

								</li>

								@endforeach
								@endif 
							</ul>
						</div>
					</div>
					@endforeach 
					@endif --}}
				</form>

			</div>
			<div class="modal-footer">
				<div class="d-flex order-btn">
					<input type="hidden" id="addon_item_{{$v->id}}" name="addon_item" value="">
					<input type="hidden" id="future_date_{{$v->id}}" name="future_date" value="{{$today_date}}">
					<input type="hidden" id="time_slot_{{$v->id}}" name="deliveytime_slot" value="">
					<input type="hidden" id="unit_item_{{$v->id}}" name="unit_item" value="{{$first_unit}}">
					<input type="hidden" id="quantity_{{$v->id}}" name="quantity" value="@if($v->food_type == "home_event_menu") {{ $v->minimum_order_quantity }} @else 1 @endif">

					<button type="button" class="btn cancel-asw" data-toggle="modal" data-target="#exampleModaldate{{$v->id}}" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn lastconfirm" data-id="{{$v->id}}" onclick="lastresult('{{$v->id}}','no','exampleModaldelivery_timetoday{{$v->id}}')">Confirm</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end order for future time slot -->

<!--modal start order for today delivery time-->
<div class="modal my-mod-time fade" id="exampleModaldelivery_time{{$v->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">
					<span class="foodname">Delivery time slots</span>
				</h5>
				<button type="button" class="close datatime{{$v->id}}" data-dismiss="modal" aria-label="Close" data-unit="{{$first_unit}}" data-id="{{$v->id}}" data-myval="">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="height: 500px;">
				<?php  
					/*$now = time();
					$now = strtotime('12:30pm');
					$rounded = date('H:i', ceil(time()/1800)*1800);
					echo $rounded;*/
				?>
				<form action="" data-pric="{{$unit_price}}" class="resrt ids{{$v->id}} appendid{{$v->id}}">
					
				</form>
			</div>
			<div class="modal-footer">
				<div class="d-flex order-btn">
					<button type="button" class="btn cancel-asw timeslot-cancel" data-toggle="modal" data-target="#exampleModaldate{{$v->id}}" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn lastconfirm" data-id="{{$v->id}}" onclick="@if($v->food_type == "home_event_menu") lastresult('{{$v->id}}','no','home_event') @else lastresult('{{$v->id}}','no','exampleModaldelivery_time{{$v->id}}') @endif" id="today_lastconfirm" data-modal-name="exampleModaldelivery_time{{$v->id}}">Confirm</button>
				</div>
			</div>
		</div>
	</div>
</div>

{{-- <?php
$userid    = \Auth::check() ? \Auth::user()->id : '';
$cookie    = \Session::has('cookie') ? \Session::get('cookie') : \Cookie::get('mycart');
$cart = uCartQuery($userid,$cookie)->orderby('id','DESC')->first();
?> --}}
<!--- sametimeslot date confirmation modal ---->
{{-- <div class="modal my-mod-date fade" id="sametimeslotModal{{$v->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" style="margin-top: 200px;">
			<div class="modal-header">
				<h5 class="modal-title m-auto" id="exampleModalLabel">
					<span class="foodname font-opensans">Order for same timeslot</span>
				</h5>
				<button type="button" class="close datatime{{$v->id}}" data-dismiss="modal" aria-label="Close" data-unit="{{$first_unit}}" data-id="{{$v->id}}" data-myval="">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
					<input type="hidden" class="addon_item_{{$v->id}} com_addon" name="addon_item" value="">
					<input type="hidden" class="cart_date_{{$v->id}} com_date" name="future_date" value="{{!empty($cartcount->cart_datetimeslot) ? $cartcount->cart_datetimeslot->date : ''}}">
					<input type="hidden" class="cart_time_slot_{{$v->id}} com_timeslot" name="deliveytime_slot" value="{{!empty($cartcount->cart_datetimeslot) ? $cartcount->cart_datetimeslot->time_slot : '' }}">
					<input type="hidden" class="unit_item_{{$v->id}}" name="unit_item" value="{{$first_unit}}">
					<input type="hidden" class="quantity_{{$v->id}}" name="quantity" value="1">
					<p>Do you want to continue your current order for same date and time ?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn cancel-asw font-montserrat" data-toggle="modal" data-target="#exampleModal{{$v->id}}" data-dismiss="modal">No</button>
				<button type="button" class="btn font-montserrat samedatetime_addcart" open-modal="#exampleModal{{$v->id}}" onclick="lastresult({{$v->id}},'samedate_time')">Yes</button>
			</div>
		</div>
	</div>
</div> --}}

<!--- sametimeslot date confirmation modal ---->
<input type="hidden" id="com_date" value="{{ isset($cartcount) ? $cartcount->cart_datetimeslot->date : ''}}">
<input type="hidden" id="com_timeslot" value="{{ isset($cartcount) ? $cartcount->cart_datetimeslot->time_slot : ''}}">

<div class="modal my-mod-date fade" id="sametimeslotModal{{ $v->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" style="margin-top: 200px;">
			<div class="modal-header">
				<h5 class="modal-title m-auto" id="exampleModalLabel">
					<span class="foodname font-opensans">Order for same timeslot</span>
				</h5>
				<button type="button" class="close datatime{{$v->id}}" data-dismiss="modal" aria-label="Close" data-unit="{{$first_unit}}" data-id="{{$v->id}}" data-myval="">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
					{{-- <input type="hidden" name="id" value="" class="food_id"> --}}
					<input type="hidden" class="addon_item_{{ $v->id }} com_addon" name="addon_item" value="">
					<input type="hidden" class="cart_date_{{ $v->id }} com_date" name="future_date" value="{{!empty($cartcount->cart_datetimeslot) ? $cartcount->cart_datetimeslot->date : ''}}">
					<input type="hidden" class="cart_time_slot_{{ $v->id }} com_timeslot" name="deliveytime_slot" value="{{!empty($cartcount->cart_datetimeslot) ? $cartcount->cart_datetimeslot->time_slot : '' }}">
					<input type="hidden" class="unit_item_{{ $v->id }}" name="unit_item" value="{{$first_unit}}">
					<input type="hidden" class="quantity_{{ $v->id }}" name="quantity" value="1">
					<p>Do you want to continue your current order for same date and time ?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn cancel-asw font-montserrat" onclick="lastresult({{$v->id}},'no','no_samedatetime')">No</button>
				<button type="button" class="btn font-montserrat samedatetime_addcart" data-from="samedatetime_confirm" onclick="lastresult({{$v->id}},'yes','samedatetime')">Yes</button>
			</div>
		</div>
	</div>
</div>
<!--- sametimeslot date confirmation modal End---->

<!--- unavailable cart data message popup---->
<div class="modal my-mod fade" id="unavailable_cart" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" style="margin-top: 200px;">
			<div class="modal-header">
				<h5 class="modal-title m-auto" id="exampleModalLabel">
					<span class="foodname font-opensans" style="margin-left:90px;">Cart information</span>
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
					<p id="unavailable_message">Empty or change your cart as you cart have invalid date and timeslot for delivery</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn cancel-asw font-montserrat" data-dismiss="modal">Ok</button>
			</div>
		</div>
	</div>
</div>


