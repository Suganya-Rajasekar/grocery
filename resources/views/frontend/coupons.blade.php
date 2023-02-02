<div class="coupon-asw">
	@if(isset($v) && isset($class))
	@if(isset($offers['promos']) && $carts->couponCode == '')
	<button class="btn apply-coupon-btn {{$class}}">
		<i class="fas fa-gift"></i> Apply Coupon
	</button>
	@elseif($carts->couponCode != '')
	<button class="btn send_coupon {{$class}}" data-id="{!! $carts->couponId !!}" data-action="remove">{!! $carts->couponCode !!} <i class="fas fa-close"></i>
	</button> 
	@endif
	@endif
	<div class="coupon-backdrop d-none"></div>
	<div class="coupon-nav">
		<div class="p-4">
			<div class="coupon-nav-close d-inline-block p-3">
				<i class="fas fa-times"></i>
			</div>
			<div class="py-3">
				<div class="couponcode disable-coupon">
						<div class="form-group position-relative">
							<input type="text" name="coupon_code" placeholder="Enter Coupon code" class="form-control coupon_code">
							<button class="btn coupon-submit apply-coupon send_coupon" disabled>Apply</button>
						</div>
				</div>
				@if(isset($offers['promos']))
				@foreach($offers['promos'] as $off_ke => $off_val)
				<div class="py-3">
					<div class="avail-offer">
						<h3 class="mb-0 py-1">Available coupons</h3>
						<div class="coupon-code my-1">
							<div class="d-flex justify-content-between align-items-center">
								<i class="fab fa-angellist"></i>
								<span>{{$off_val->promo_code}}</span>
							</div>
						</div>
						<h2 class="mb-0 py-1">Get {{$off_val->name}} cashback</h2>
						<h6 class="mb-0 py-1">{{$off_val->offer_text}}</h6>
						<p class="mb-0 py-1">{{$off_val->promo_desc}}</p>
						<div class="more py-1">
							<span>+ MORE</span>
						</div>
						<button class="btn apply-coupon send_coupon disable-coupon" data-id="{{$off_val->id}}">
							APPLY COUPON
						</button>
					</div>
				</div>
				@endforeach
				@endif
			</div>
		</div>
	</div>
</div>
<script>

</script>