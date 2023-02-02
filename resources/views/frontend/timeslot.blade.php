@if($timeslots)
@foreach($timeslots as $slot_k => $slot_val)
@if($slot_val->status == 'active')
<div class="addon-sec over-hid">
	<div class="modalbody-head">
		<h5 class="text-black font-weight-bold">{{ $slot_val->name }}</h5>
	</div>
	<div class="cont" style="line-height: 2;">
		<ul class="over-hid">
		@foreach($slot_val->slots as $slot => $slots)
			<li>
				<div class="float-left">
					<div class=" custom-checkbox">
						<label class="container-check" for="">{{ $slots->time_slot }}
							<input type="radio" @if($slots->status == 'inactive')disabled @endif class="@if($slots->status == 'inactive')disable @endif custom-control-input timeslot timeslot{!! $menuid !!}" name="timeslot" value="{{ $slots->id }}" data-id="{!! $menuid !!}">
							<span class="checkmark"></span>
						</label>
					</div>
				</div>
			</li>
		@endforeach
		</ul>
	</div>
</div>
@endif
@endforeach 
@endif