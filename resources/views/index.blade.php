@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Contact</span></h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Contact</li>
		</ul>
	</div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
@php 
$pages=[];
$access = getUserModuleAccess(\Request::segment(3));
$cpage	= request()->has('page') ? request()->get('page') : '';
$from	= request()->has('from') ? request()->get('from') : '';
$url	= '?from='.$from.'&page='.$cpage;
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	<!-- <div class="panel-heading">
		<h5 class="panel-title">Log activity</h5>		
		<div class="heading-elements"> -->
			<!-- <ul class="icons-list">
				<li><a href="{!!url(getRoleName().'/customer/create')!!}"><button type="button" class="btn bg-teal-400 btn-labeled pull-right"><b><i class="fa fa-cutlery"></i></b> {{ __('Add New') }}</button></a></li>
			</ul> -->
		<!-- </div>
	</div> -->
	<div class="panel-body">	
		<div class="pull-right">
			<form class="form-inline" method="GET">
				<div class="form-group mb-2">
				<input type="text" class="form-control daterange-basic" id="date" name="date" value="{!! (\Request::query('date') != '') ? \Request::query('date') : '' !!}">
				</div>
				<div class="form-group mb-10">
				<select name="user_id" class="select-search form-control">
					<option value="" selected >All From ID</option>
					@if(count($customerData)>0)
					@foreach($customerData as $cname)
					<option value="{!! $cname->id !!}" @if(\Request::query('user_id') != '' && ($cname->id == \Request::query('user_id')))  selected @endif>{!! $cname->name !!}</option>
					@endforeach
					@endif
				</select>
			</div>
			<div class="form-group mb-10">
				<select name="module" class="select-search form-control">
					<option value="" selected >All Module</option>
					@if(count($module)>0)
					@foreach($module as $module_name)
					<option value="{!! $module_name !!}" @if(\Request::query('module') != '' && ($module_name == \Request::query('module')))  selected @endif>{!! $module_name !!}</option>
					@endforeach
					@endif
				</select>
			</div>
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search field or before or after changes" value="{!! \Request::query('search') !!}">
				</div>
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="filter" placeholder="Search recordid " value="{!! \Request::query('filter') !!}">
				</div>
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if( \Request::query('date') != '' || \Request::query('search') != '' || \Request::query('filter') != ''|| \Request::query('user_id') != '' || \Request::query('module') != '')
				<a href="{!! url('admin/logactivity') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@endif
				
			</form>
		</div>
	</div>

	<table class="table datatable-responsive">
		
		<thead>
			<tr>
				<th>#</th>
				<th>Name</th>
				<th>E-mail</th>
				<th>Mobile</th>
				<th>Subject</th>
				<th>Message</th>
				<th>Date</th>
				<th class="text-center">Actions</th>
			</tr>
		</thead>
		<tbody>

			@if(count($contactus)>0)
			@foreach($contactus as $key=>$value)
			@php
            $user = App\Models\User::where('id', $value->user_id)->first();
            @endphp
			<tr>
				<td>{!! ($key+1)+$page !!}</td>
				<td>{!! $user->name !!}</td>
				<td>{!! $value->email!!}</td>
				<td>{!! $value->mobile!!}</td>
				<td>{!! $value->subject!!}</td>
				<td>{!! $value->mesage!!}</td>
				<td>{!! date('M d Y H:i:s', strtotime($value->created_at)) !!}</td>
				<td class="text-center">				
					<a href="{!! $value->url !!}" class="isread" data-id="{!! $value->id !!}"><button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-eye"></i></b></button></a>
				</td>
			</tr>
			@endforeach
			@endif

		</tbody>

	</table>
</div>
<div class="mt-10">
	<div class="pull-left font-montserrat">
		Displaying {{$logactivity->count()+$page}} of {{ $logactivity->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$logactivity->appends(Request::except('page'))->render()}}
	</div>
</div>
<!-- /basic responsive configuration -->
@endsection
@section('script')
<script type="text/javascript">
	"use strict";	
	 // Success notification
	 $('#pnotify-success').on('click', function () {
	 	new PNotify({
            title: 'Success notice',
            text: 'Check me out! I\'m a notice.',
            addclass: 'bg-success'
        });
	 });

	 $(document).on("click",'.isread',function() {
	 	var id= $(this).attr('data-id');
	 	var token = $("input[name='_token']").val();
        var url = base_url+"/admin/update_notify_isread";
	 	$.ajax({
	 		type : 'POST',
	 		url : url,
	 		data : {id:id,"_token": token},
	 		success:function(data){
	 		
	 		},
	 		error : function(err){ 
	 			
	 		}
	 	});

	 });
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
	var startDate	= "{!! $dt1 !!}";
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
	});
</script>
@endsection
