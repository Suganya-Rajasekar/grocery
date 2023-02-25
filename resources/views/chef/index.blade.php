@extends('layouts.backend.app')
@section('page_header')
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Vendors</span> - All Vendor @if($chefreq == true) request @endif</h5>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>@if($chefreq == false){!! 'All Vendors' !!}@else{!! 'Vendor request' !!}@endif</a></li>
		</ul>
	</div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
@php 
	$pages	= [];
	$access	= getUserModuleAccess(\Request::segment(2));
	$cpage	= request()->has('page') ? request()->get('page') : '';
	$from	= request()->has('from') ? request()->get('from') : '';
	$url	= '?from='.$from.'&page='.$cpage;
	$dwnload= ['pdf'=>'PDF','xls'=>'EXCEL','csv'=>'CSV'];
	$dwnicon= ['pdf'=>'pdf','xls'=>'excel','csv'=>'csv'];
	if(\Request::is('admin/chef/request')) { $status = 'pending'; } else { $status = \Request::query('status'); }
@endphp
<!-- Basic responsive configuration -->
<div class="row">
	@if($chefreq==false && $access->edit)
	<div class="col-md-2">
		<a href="{!!route('admin.chef.create')!!}"><button type="button" class="btn bg-teal-400 btn-labeled btn-rounded"><b><i class="icon-user-plus"></i></b> {{ ('Add New') }}</button></a>
	</div>
	<div class="col-md-2">
		<div class="btn-group ml-1 position-static">
			<button type="button" class="btn bg-transparent text-success border-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="icon-download"></i> Download</button>
			<div class="dropdown-menu dropdown-menu-right" style="">
				@php $i =0; @endphp
				@foreach($dwnload as $dn => $dv)
				@if($i != 0)
				<div class="dropdown-divider"></div>
				@endif
				<a href="{!! url(getRoleName().'/chefexport/'.$dn.'?location_id='.\Request::query('location_id').'&date='.\Request::query('date').'&search='.\Request::query('search').'&status='.$status) !!}" class="dropdown-item"><i class="fas fa-file-{!! $dwnicon[$dn] !!}"></i> {!! $dv !!}</a>
				@php $i++; @endphp
				@endforeach
			</div>
		</div>
	</div>
	{{-- <div class="col-md-2">
		<a href="{!!route('admin.chef_ordering')!!}"><button type="button" class="btn bg-blue-400 btn-labeled"><b><i class="fas fa-sort"></i></b> {{ ('Sort Chefs') }}</button></a>
	</div> --}}
	@endif
</div>
<div class="panel panel-flat">
	<div class="panel-body">
		<div class="pull-left">
			@if($chefreq==false && $access->edit)
			<div class="panel-heading ml-3">
				<label class="checkbox-inline">
					Select All
					<input type="checkbox" name="delete[]" value="0" form="DeleteMultiple" class="styled selectAll">
				</label>
				<input form="DeleteMultiple" type="submit" class="btn bg-danger ml-2" value="Delete selected">
			</div>
			@endif
		</div>
		<div class="pull-right">
			<form class="form-inline" method="GET">
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search name or email or mobile or ID" value="{!! \Request::query('search') !!}">
				</div>
				<div class="form-group mb-2">
					<input type="hidden" name="date" value="" id="start_date">
					<input type="text" class="form-control daterange-basic" id="date" value="{!! (\Request::query('date') != '') ? \Request::query('date') : '' !!}">
				</div>
				<div class="form-group mb-10">
					<select name="location_id" class="select-search form-control">
						<option value="" selected >All Locations</option>
						@if(count($city)>0)
						@foreach($city as $l_value)
						<option value="{!! $l_value->id !!}" @if(\Request::query('location_id') != '' && ($l_value->id == \Request::query('location_id')))  selected @endif>{!! $l_value->name !!}</option>
						@endforeach
						@endif
					</select>
				</div>
				@if (Request::is('admin/chef'))
				<div class="form-group mb-2">
					<select name="status" class="select-search form-control">
						<option value="">All Status</option>
						<option value="pending" @if(\Request::query('status') == 'pending') selected @endif>Pending</option>
						<option value="approved" @if(\Request::query('status') == 'approved') selected @endif>Approved</option>
						<!-- <option value="suspended" @if(\Request::query('status') == 'suspended') selected @endif>Suspended</option> -->
						<option value="cancelled" @if(\Request::query('status') == 'cancelled') selected @endif>Rejected</option>
					</select>
				</div>
				<div class="form-group mb-10">
					<select name="ordering" class="select-search form-control">
						<option value="" disabled hidden selected >Sort By</option>
						<option value="asc" @if(\Request::query('ordering') == 'asc') selected @endif>ASC of vendor sorting</option>
						<option value="desc" @if(\Request::query('ordering') == 'desc') selected @endif>DESC of vendor sorting</option>
					</select>
				</div>
				<div class="form-group mb-10">
					<select name="mode" class="select-search form-control">
						<option value="" selected >All mode</option>
						<option value="open" @if(\Request::query('mode') == 'open') selected @endif>Online</option>
						<option value="close" @if(\Request::query('mode') == 'close') selected @endif>Offline</option>
					</select>
				</div>
				{{-- <div class="form-group mb-10">
					<select name="cheftype" class="select-search form-control">
						<option value="" selected >All type</option>
						<option value="Celebrity" @if(\Request::query('cheftype') == 'Celebrity') selected @endif>Celebrity</option>
						<option value="Promoted" @if(\Request::query('cheftype') == 'Promoted') selected @endif>Promoted</option>
						<option value="Certified" @if(\Request::query('cheftype') == 'Certified') selected @endif>Certified</option>
					</select>
				</div> --}}
				@endif
				<div class="form-group">
					<select name="cuisines" class="select-search form-control">
						<option value="" selected>All cuisines</option>
						@if(isset($cuisines) && $cuisines != '')
						@foreach($cuisines as $k => $value)
						<option value="{{ $value->id }}" @if(\Request::query('cuisines') != '' && $value->id == \Request::query('cuisines')) selected @endif>{{ $value->name }}</option>
						@endforeach
						@endif
					</select>	
				</div>
				{{-- <div class="form-group">
					<select name="type" class="select-search form-control">
						<option value="">Chef type</option>
						<option value="chef" @if(\Request::query('type') == 'chef') selected @endif>Chef</option>
						<option value="event" @if(\Request::query('type') == 'event') selected @endif>Event</option>
						<option value="home_event" @if(\Request::query('type') == 'home_event') selected @endif>Home Event</option>
					</select>
				</div> --}}
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if(\Request::query('location_id') != '' || \Request::query('date') != '' || \Request::query('search') != ''|| \Request::query('status') != '' || \Request::query('cuisines') != '' || \Request::query('cheftype') || \Request::query('type') || \Request::query('mode') || \Request::query('ordering'))
				<a href="@if($chefreq == false){!! url('admin/chef') !!}@else{!! url('admin/chef/request') !!}@endif" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@endif
			</form>
		</div>
	</div>
	<form id="DeleteMultiple" action="{!! route('admin.multidelete') !!}" method="POST">
		{{ csrf_field() }}
	</form>
	<div class="table-responsive">
		<table class="table datatable-responsive table-bordered">
			<thead>
				<tr>
					<th>S.No</th>
					{{-- <th>Chef ID</th> --}}
					{{-- <th>Order</th> --}}
					@if($chefreq == false)
					<th>Avatar</th>
					@endif
					<th>Location</th>
					<th>Name</th>
					<th>Email</th>
					<th>Mobile</th>
					<th>Status</th>
					{{-- <th>Type</th>
					<th>Email verified</th>
					<th>Registered date</th> --}}
					<th class="text-center">Actions</th>
				</tr>
			</thead>
			<tbody>
				  <?php //echo "<pre>";print_r($chefs);echo "<pre>";echo exit();?>
				@if(count($chefs)>0)
				@foreach($chefs as $key => $value)
				<tr>
					<td>
						<label class="checkbox-inline">
							{!! ($key+1)+$page !!}
							<input type="checkbox" form="DeleteMultiple" name="delete[]" value="{!!$value->id!!}"  class="styled chefscheck"  >
						</label>
					</td>
					{{-- <td><a href="{!! url(getRoleName().'/chef/'.$value->id.'/edit'.$url)!!}" data-placement="left" data-toggle="tooltip" title="Click to edit profile">{!!$value->user_code!!}</a></td>
					<td contenteditable="true">
						{!! $value->ordering !!}
					</td> --}}
					@if($chefreq == false)
					<td>
						<a href="{!! url(getRoleName().'/chef/'.$value->id.'/edit') !!}"><img src="{!! $value->avatar !!}" class="img-circle" alt="{!! $value->avatar !!}">
						</a>
					</td>
					@endif
					@if($chefreq == false)
					<td>
						@if($value->getChefRestaurant && $value->getChefRestaurant->location_info){!! $value->getChefRestaurant->location_info->name !!}@endif
					</td>
					@else
					<td>
						@if($value->singlerestaurant && $value->singlerestaurant->location_info){!! $value->singlerestaurant->location_info->name !!}@endif
					</td>
					@endif
					<td>{!! $value->name !!}</td>
					<td>{!! $value->email !!}</td>
					<td>{!! $value->mobile !!}</td>
					<td>
						<span class="label @if($value->status == 'cancelled' || $value->status == 'suspended'){!! 'label-danger' !!}@else{!! 'label-success' !!}@endif">@if($value->status == 'cancelled' || $value->status == 'suspended'){!! 'Rejected' !!}@else{!! $value->status !!}@endif</span>
					</td>
					{{-- <td>
						@if($value->email_verified_at!='')
						<span class="label label-success">Verified</span>
						@else
						<span class="label label-danger">Not-Verified</span>
						@endif
					</td>
					<td>{{ $value->type }}</td>
					<td>{!! date('M d, Y', strtotime($value->created_at)) !!}</td> --}}
					<td class="text-center">
						<ul class="icons-list">
							<li class="dropdown">
								<a href="javascript::void()" data-toggle="dropdown">
									 <i class="icon-menu9"></i>
								</a>
								<ul class="dropdown-menu dropdown-menu-right">
									@if($chefreq == false && $access->edit)
									<li><a href="{!!url(getRoleName().'/chef/'.$value->id.'/edit_business'.$url)!!}" data-placement="left" data-toggle="tooltip" title="Click to update business profile"><button type="button" class="btn bg-success-400 btn-sm" >Business Info</button></a></li>
									<li><a href="{!!url(getRoleName().'/chef/'.$value->id.'/addon'.$url)!!}" data-placement="left" data-toggle="tooltip" title="Click to add/edit addons"><button type="button" class="btn bg-primary-400 btn-sm" >Addons</button></a></li>
									<li><a href="{!!url(getRoleName().'/chef/'.$value->id.'/store'.$url)!!}" data-placement="left" data-toggle="tooltip" title="Click to add/edit stores"><button type="button" class="btn bg-warning-400 btn-sm" >Stores</button></a></li>
									<li><a href="{!!url(getRoleName().'/chef/'.$value->id.'/menu_item'.$url)!!}" data-placement="left" data-toggle="tooltip" title="Click to add/edit Menu Item's"><button type="button" class="btn bg-info-400 btn-sm" >Menu items</button></a></li>
									@endif
									<li>
										@if($access->remove)
										<form action="{!!url('admin/chef/'.$value->id)!!}" id="delete_{!! $value->id !!}" method="post" >
											<input name="_method" form="delete_{!! $value->id !!}" type="hidden" value="DELETE">
											{{ csrf_field() }}
											<button data-placement="left" data-toggle="tooltip" title="Click to delete chef" type="submit" class="ml-3 btn btn-danger btn-sm"  data-id="{!!$value->id!!}" ><b>Delete</b></button>
										</form>
										@endif
									</li>
								</ul>
							</li>
						</ul>
					</td>
				</tr>
				<div class="modal fade" id="preparation_time_edit{{ $value->id }}" style="margin-top:150px;">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Preparation time Edit</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								 <span aria-hidden="true">&times;</span>
        						</button>	
							</div>
							<div class="modal-body">
									<form action="{{ url::to('admin/chef/preparationtime') }}" method="POST">
										{{ csrf_field() }}
										<input type="hidden" name="v_id" value="{{ $value->id }}">
										<label for="preparation_time" class="text-semibold">preparation_time</label>
										<select name="preparation_time" class="form-control" id="preparationselect">
											<option value="select any" selected>select option</option>
											<option value="1_to_2hrs">1_to_2hrs</option>
											<option value="2_to_3hrs">2_to_3hrs</option>
											<option value="tomorrow">Tomorrow</option>
										</select>
										<button class="btn btn-primary mt-20" type="submit" style="margin-left:300px;">Submit</button>
										<button type="button" class="btn btn-secondary mt-20 float-right" data-dismiss="modal">Close</button>
									</form>
							</div>
						</div>
					</div> 
				</div>	
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
<div class="mt-10">
	<div class="pull-left font-montserrat">
		Displaying {{$chefs->count()+$page}} of {{ $chefs->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$chefs->appends(Request::except('page'))->render()}}
	</div>
</div>
<!-- /basic responsive configuration -->
@endsection
<style type="text/css">
	.img-circle {
		width: 40px;height: 40px;max-width: none;border-radius: 50%;
	}
</style>
@section('script')
<script type="text/javascript">
	"use strict";
	<?php
	if(\Request::query('date') != ""){
		$dt  = explode(" - ",\Request::query('date'));
		$dt1 = $dt[0];
		$dt2 = $dt[1];
	}else{
		$dt1 = date('Y-m-d', strtotime('-1 month'));
		$dt2 = date('Y-m-d');
	} 	
	?>
	/*var startDate	= "{!! $dt1 !!}";
	var endDate		= "{!! $dt2 !!}";
	$('.daterange-basic').daterangepicker({
		applyClass	: 'bg-slate-600',
		cancelClass	: 'btn-default',
		startDate	: startDate,
		endDate		: endDate,
		locale		: {
			format	: 'YYYY-MM-DD'
		},
		"maxDate"	: new Date(),
	}, function (start_date,end_date) {
    $('#start_date').val(start_date.format('YYYY-MM-DD')+' - '+end_date.format('YYYY-MM-DD'));
	});*/
	/*$('input[name="date"]').on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('YYYY-MM-DD'));
	});2021-08-14 - 2021-09-14 */
	//response will assign this function
	$(document).on('click','.edit_popup',function(){
		$("#l_id").val($(this).attr('data-id'));
		$("#l_name").val($(this).attr('data-name'));
		$('#l_status option[value="'+$(this).attr('data-name')+'"]').attr("selected", "selected");
		$("#modal_location").modal('show');
	})
	// Success notification
	$('#pnotify-success').on('click', function () {
		new PNotify({
			title: 'Success notice',
			text: 'Check me out! I\'m a notice.',
			addclass: 'bg-success'
		});
	});
	$('.styled').click(function(){
		if($(this).is(':checked')){
			$(this).closest('tr').addClass('selectedTr');
		}else{
			$(this).closest('tr').removeClass('selectedTr');
			$('.selectAll').prop("checked",false);
			$('.selectAll').parent().removeClass("checked");
		}
		if($('input:checkbox:checked.styled').length > 0){

		}
	})
	$('.selectAll').click(function(){
		if($(this).is(':checked')){
			$('.chefscheck').trigger('click');
		}else{
			$('.chefscheck').trigger('click');
		}
	})
	$(document).on('click','.preparation_time',function(){
		var chef_id  = $(this).attr('data-vid');
		$('#preparationselect').val('select any').attr('selected','selected');
		$.ajax({
			url:window.location.pathname+'/preparationtime',
			type:'POST',
			data:{chef_id : chef_id,action : 'get'},
			success:function(response){
				if(response == '1_to_2hrs'){
					$('#preparation_time_edit'+chef_id).find('#preparationselect').val('1_to_2hrs').attr('selected','selected');
				} else if(response == '2_to_3hrs') {
					$('#preparation_time_edit'+chef_id).find('#preparationselect').val('2_to_3hrs').attr('selected','selected');
				} else if(response == 'tomorrow') {
					$('#preparation_time_edit'+chef_id).find('#preparationselect').val('tomorrow').attr('selected','selected');
				}
				$('#preparation_time_edit'+chef_id).modal('show');
			}
		})
	});
</script>
@endsection
