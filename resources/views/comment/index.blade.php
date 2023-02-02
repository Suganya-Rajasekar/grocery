@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Comments</span></h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Comments</li>
		</ul>
	</div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
@php 
$pages=[];
$access = getUserModuleAccess(\Request::segment(2));
$cpage	= request()->has('page') ? request()->get('page') : '';
$from	= request()->has('from') ? request()->get('from') : '';
$url	= '?from='.$from.'&page='.$cpage;
$dwnload= ['pdf'=>'PDF','xls'=>'EXCEL','csv'=>'CSV'];
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	<div class="panel-body">
		<div class="pull-left">
			<div class="form-group mb-2">
				<div class="dropdown">	
					<button class="btn bg-teal-400  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="icon-download"></i></b> Download </button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						@foreach($dwnload as $dn => $dv)
						<a class="dropdown-item" href="{!! url(getRoleName().'/commentexport/'.$dn.'?user_id='.\Request::query('user_id').'&food_id='.\Request::query('food_id').'&date='.\Request::query('date').'&search='.\Request::query('search').'&status='.\Request::query('status')) !!}">{!! $dv !!}</a>
						@endforeach
					</div>
				</div>
			</div>
		</div>
		<div class="pull-right">
			<form class="form-inline" method="GET">

				<div class="form-group mb-2">
					<input type="hidden" name="date" value="" id="start_date">
					<input type="text" class="form-control daterange-basic" id="date" value="{!! (\Request::query('date') != '') ? \Request::query('date') : '' !!}">
				</div>
				<div class="form-group mb-10">
					<select name="user_id" class="select-search form-control">
						<option value="" selected >All Users</option>
						@if(count($customerData)>0)
						@foreach($customerData as $cname) 
						<option value="{!! $cname->id !!}" @if(\Request::query('user_id') != '' && ($cname->id == \Request::query('user_id')))  selected @endif>{!! $cname->name !!}</option>
						@endforeach

						@endif
					</select>
				</div>
				<div class="form-group mb-10">
					<select name="food_id" class="select-search form-control">
						<option value="" selected >All Foods</option>
						@if(count($foodData)>0)
						@foreach($foodData as $fname)
						<option value="{!! $fname->id !!}" @if(\Request::query('food_id') != '' && ($fname->id == \Request::query('food_id')))  selected @endif>{!! $fname->name !!}</option>
						@endforeach
						@endif
					</select>
				</div>
				<div class="form-group mb-10">
					<select name="status" class="select-search form-control">
						<option value="">All Status</option>
						<option value="published" @if(\Request::query('status') == 'published') selected @endif >Published</option> 
						<option value="pending" @if(\Request::query('status') == 'pending') selected @endif>Pending</option>
						<option value="rejected" @if(\Request::query('status') == 'rejected') selected @endif>Rejected</option>
					</select>
				</div>
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search comment or reply" value="{!! \Request::query('search') !!}">
				</div>

				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if( \Request::query('date') != '' || \Request::query('user_id') != '' || \Request::query('food_id') != ''|| \Request::query('status') != '' || \Request::query('search') != '')
				<a href="{!! url(getRoleName().'/comment') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@endif
			</form>
		</div>
	</div>
	<div class="table-responsive-xl">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Comment</th>
					<th>Food name </th>
					<th>Customer</th>
					<th>Reply</th>
					<th>Status</th>
					<th>Date</th>
					@if(getRoleName()=='admin' && ( $access->edit || $access->remove ))
					<th>Action</th>
					@endif
				</tr>
			</thead>
			<tbody>
				@if(count($resultData)>0)
				@foreach($resultData as $key=>$value)
				<tr>
					<td>{!!($key+1)+$page!!}</td>
					<td class="comment"><p>{!!($value->c_id==0) ? $value->comment : $value->getReply['comment'] ?? '' !!}</p></td>
					<td>{!!isset($value->getFoodDetails) ? $value->getFoodDetails['name'] : ''!!}</td>
					<td>{!!isset($value->getUserDetails) ? $value->getUserDetails['name'] : ''!!}</td>
					<td class="reply-comment"><p>{!!($value->c_id>0) ? $value->comment : ''!!}</p></td>
					<td>{!!ucfirst($value->status)!!}</td>
					<td class="date-comment"><p>{!!date('M-d, Y h:i A',strtotime($value->created_at))!!}</p></td>
					@if(getRoleName()=='admin' && ( $access->edit || $access->remove ))
					<td>
						<div class="d-flex">
							@if($access->edit)
							<button type="button" class="mr-2 btn btn-success btn-xs edit_popup" data-id="{!!$value->id!!}" data-status="{!!$value->status!!}" data-reason="{!! $value->reason !!}"><b><i class="fa fa-edit"></i></b></button>
							@endif
							@if($access->remove)
							<form action="{!!url(getRoleName().'/comment/'.$value->id)!!}" method="post" enctype="Multipart/form-data">
							<input name="_method" type="hidden" value="DELETE">
							{{ csrf_field() }}
							<button type="submit" class="btn btn-danger btn-xs"  data-id="{!!$value->id!!}" ><b><i class="fa fa-trash"></i></b></button>
							</form>
							@endif
						</div>
					</td>
					@endif
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
	<div class="panel-body">
		@include('footer')	
	</div>
</div>
<!-- /basic responsive configuration -->

<!-- status modal -->
<div id="modal_status" class="modal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Change Status</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="{!!url(getRoleName().'/comment/store')!!}" method="POST" enctype="Multipart/form-data">
				<div class="modal-body">
					{{ csrf_field() }}{{ method_field('PUT') }}
					<div class="form-group">
						<div class="row">
							<div class="col-sm-12">
								<label>Status</label>
								<select name="status" id="status" class="select-search" required="">
									<option value="">select any one</option>
									<option value="published">Published</option>
									<option value="rejected">Rejected</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group" @if((isset($comment->status) && $comment->status != 'rejected') || (!isset($comment->status))) style="display:none;" @endif id="reason">
						<input type="text" name="reason" id="inputreason" value="{!! (isset($comment->status) && ($comment->status == 'rejected')) ? $comment->reason : '' !!}" class="form-control" placeholder="Enter the reason for reject">
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="id" id="id">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- /Cuisine modal -->
@endsection
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
	});*/
	$(document).on('click','.edit_popup',function(){
		$("#id").val($(this).attr('data-id'));
		$("#status").val($(this).attr('data-status')).trigger('change');
		$("#modal_status").modal('show');
		$("#inputreason").val($(this).attr('data-reason'));
	});

	$(document).on('change','#status',function(){
    if ( this.value == 'rejected' ) {
      $('#reason').show();
      $('#inputreason').attr('required',true);
    }
    else {
      $('#reason').hide();
      $('#inputreason').removeAttr('required',true);
    }
  })
	
</script>
@endsection
