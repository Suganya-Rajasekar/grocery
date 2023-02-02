<?php $chef_id = \Request::segment(2);$chef_type = cheftype($chef_id);?>
<ul class="nav nav-tabs chef-asw-tab">
	<li class="nav-item font-montserrat"><a class="nav-link @if(request()->section=='dishes' || !isset(request()->section)) active @endif" href="{{ url('chef/'.$chef_id.'') }}">@if($chef_type == 'event') All Tickets @elseif($chef_type == 'home_event') All Home Events @else All Dishes @endif</a></li>
	<li class="nav-item font-montserrat" @if($chef_type == 'event' || $chef_type == 'home_event') style="display:none;" @endif><a  class="nav-link @if(request()->section=='review') active @endif" href="{{ url('chef/'.$chef_id.'?section=review') }}">Reviews</a></li>
	@if(isset($chefCategories->categories))
	@foreach($chefCategories->categories as $c1 => $c2)
	{{-- @if(isset($c2->menuitems) && !empty($c2->menuitems)) --}}
	<li class="nav-item font-montserrat"><a class="nav-link @if(request()->section==$c2->id) active @endif" href="{{ url('chef/'.$chef_id.'?section='.$c2->id.'') }}" >{{ $c2->name }}</a></li>
	{{-- @endif --}}
	@endforeach
	@endif
</ul>