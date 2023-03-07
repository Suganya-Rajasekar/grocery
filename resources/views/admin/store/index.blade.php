@extends('layouts.backend.app')
@section('page_header')
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">{!! $chef->name !!}</span> - All Stores</h5>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li><a href="{!! url(getRoleName().'/vendor') !!}"><i class="icon-user-tie position-left"></i>All Vendors</a></li>
			<li>All Stores</a></li>
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
	if(\Request::is('admin/vendor/request')) { $status = 'pending'; } else { $status = \Request::query('status'); }
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	<div class="panel-body">
		<div class="pull-left row ml-2">
			<div class="form-group">
				<a href="{!! \URL::to(getRoleName().'/vendor/'.$chef->id.'/store/create') !!}"><button type="button" class="btn bg-teal-400 btn-labeled btn-rounded"><b><i class="fa fa-building"></i></b> {{ ('Add New') }}</button></a>
			</div>
			<div class="form-group">
				<div class="btn-group ml-1 position-static">
					<button type="button" class="btn bg-info-400 border-info btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="icon-download"></i> Download</button>
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
			<div class="form-group">
				<button form="DeleteMultiple" type="submit" class="btn bg-danger btn-labeled btn-rounded ml-2"><b><i class="icon-bin"></i></b>Delete Selected</button>
			</div>
		</div>
		<div class="pull-right row">
			<form class="form-inline" method="GET">
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search name" value="{!! \Request::query('search') !!}">
				</div>
				<div class="form-group mb-2">
					<select name="location_id" class="select-search form-control">
						<option value="" selected >All Locations</option>
						@if(count($city)>0)
						@foreach($city as $l_value)
						<option value="{!! $l_value->id !!}" @if(\Request::query('location_id') != '' && ($l_value->id == \Request::query('location_id')))  selected @endif>{!! $l_value->name !!}</option>
						@endforeach
						@endif
					</select>
				</div>
				@if (Request::is('admin/vendor/'.$chef->id.'/store'))
				<div class="form-group mb-10">
					<select name="mode" class="select-search form-control">
						<option value="" selected >All mode</option>
						<option value="open" @if(\Request::query('mode') == 'open') selected @endif>Online</option>
						<option value="close" @if(\Request::query('mode') == 'close') selected @endif>Offline</option>
					</select>
				</div>
				<div class="form-group mb-10">
					<select name="status" class="select-search form-control">
						<option value="" selected >All status</option>
						<option value="approved" @if(\Request::query('status') == 'approved') selected @endif>Approved</option>
						<option value="pending" @if(\Request::query('status') == 'pending') selected @endif>Pending</option>
						<option value="declined" @if(\Request::query('status') == 'declined') selected @endif>Declined</option>
						<option value="suspended" @if(\Request::query('status') == 'suspended') selected @endif>Suspended</option>
					</select>
				</div>
				@endif
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if(\Request::query('search') != '')
				<a href="{!! url(getRoleName().'/vendor/'.$chef->id.'/store') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@endif
			</form>
		</div>
	</div>
	<form id="DeleteMultiple" action="{!! route('admin.storemultidelete') !!}" method="POST">
		{{ csrf_field() }}
	</form>
	<div class="table-responsive">
		<table class="table datatable-responsive table-bordered" id="vendorTable">
			<thead>
				<tr>
					<th>No</th>
					<th><input type="checkbox" name="delete[]" value="0" form="DeleteMultiple" class="styled selectAll"></th>
					<th>Logo</th>
					<th>Name</th>
					<th>Location</th>
					<th>Mobile</th>
					<th>Mode</th>
					<th>Status</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>
			<tbody>
				@if(count($restaurant)>0)
				@foreach($restaurant as $key => $value)
				<tr>
					<td>
						{!! ($key+1)+$page !!}
					</td>
					<td>
						<label class="checkbox-inline">
							<input type="checkbox" form="DeleteMultiple" name="delete[]" value="{!! $value->id !!}"  class="styled chefscheck">
						</label>
					</td>
					<td>
						<a href="{!! url(getRoleName().'/vendor/'.$chef->id.'/store/'.$value->id.'/edit') !!}"><img src="{!! $value->logo !!}" class="img-circle" alt="{!! $value->logo !!}">
						</a>
					</td>
					<td>{!! $value->name !!}</td>
					<td>{!! $value->location_name !!}</td>
					<td>{!! $value->phone !!}</td>
					<td>
						<span class="label @if($value->status == 'close'){!! 'label-danger' !!}@else{!! 'label-success' !!}@endif">@if($value->status == 'close'){!! 'Closed' !!}@else{!! 'Opened' !!}@endif</span>
					</td>
					<td>
						<span class="label @if($value->status == 'declined' || $value->status == 'suspended'){!! 'label-danger' !!}@elseif($value->status == 'pending'){!! 'label-warning' !!}@else{!! 'label-success' !!}@endif">@if($value->status == 'declined' || $value->status == 'suspended'){!! 'Rejected' !!}@else{!! $value->status !!}@endif</span>
					</td>
					{{-- <td>{{ $value->type }}</td>
					<td>{!! date('M d, Y', strtotime($value->created_at)) !!}</td> --}}
					<td class="text-center">
						<ul class="icons-list">
							<li class="dropdown">
								<a href="javascript::void()" data-toggle="dropdown">
									 <i class="icon-menu9"></i>
								</a>
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="{!! url(getRoleName().'/vendor/'.$value->id.'/edit_business'.$url) !!}" data-placement="left" data-toggle="tooltip" title="Click to update business profile"><button type="button" class="btn bg-success-400 btn-sm" >Business Info</button></a></li>
									<li><a href="{!! url(getRoleName().'/vendor/'.$value->id.'/addon'.$url) !!}" data-placement="left" data-toggle="tooltip" title="Click to add/edit addons"><button type="button" class="btn bg-primary-400 btn-sm" >Addons</button></a></li>
									<li><a href="{!! url(getRoleName().'/vendor/'.$value->id.'/store'.$url) !!}" data-placement="left" data-toggle="tooltip" title="Click to add/edit stores"><button type="button" class="btn bg-warning-400 btn-sm" >Stores</button></a></li>
									<li><a href="{!! url(getRoleName().'/vendor/'.$value->id.'/menu_item'.$url)!!}" data-placement="left" data-toggle="tooltip" title="Click to add/edit Menu Item's"><button type="button" class="btn bg-info-400 btn-sm" >Menu items</button></a></li>
									<li>
										@if($access->remove)
										<form action="{!! url('admin/vendor/'.$value->id) !!}" id="delete_{!! $value->id !!}" method="post" >
											<input name="_method" form="delete_{!! $value->id !!}" type="hidden" value="DELETE">
											{!! csrf_field() !!}
											<button data-placement="left" data-toggle="tooltip" title="Click to delete vendor" type="submit" class="ml-3 btn btn-danger btn-sm"  data-id="{!! $value->id !!}" ><b>Delete</b></button>
										</form>
										@endif
									</li>
								</ul>
							</li>
						</ul>
					</td>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
<div class="mt-10">
	<div class="pull-left font-montserrat">
		Displaying {{$restaurant->count()+$page}} of {{ $restaurant->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$restaurant->appends(Request::except('page'))->render()}}
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
	if (\Request::query('date') != "") {
		$dt  = explode(" - ",\Request::query('date'));
		$dt1 = $dt[0];
		$dt2 = $dt[1];
	} else {
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
		if ($(this).is(':checked')) {
			$(this).closest('tr').addClass('selectedTr');
		} else {
			$(this).closest('tr').removeClass('selectedTr');
			$('.selectAll').prop("checked",false);
			$('.selectAll').parent().removeClass("checked");
		}
		if ($('input:checkbox:checked.styled').length == 0) {
			$('#vendorTable').find('tr').removeClass('selectedTr');
		}
	})
	$('.selectAll').click(function(){
		if($(this).is(':checked')){
			$('.chefscheck').trigger('click');
		}else{
			$('.chefscheck').trigger('click');
		}
	});
</script>
@endsection