@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Master</span> - Time slot management</h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Time slot management</li>
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
$dwnload= ['pdf'=>'PDF','xls'=>'EXCEL','csv'=>'CSV'];
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	
	<div class="panel-body">
		<div class="pull-left">
			@if($access->edit)
	<div class="panel-heading">
		<button type="button" class="btn bg-teal-400 btn-labeled btn-rounded" data-toggle="modal" data-target="#modal_cuisine"><b><i class="icon-sort-time-asc"></i></b> {{ ('Add New') }}</button>
	</div>
	@endif
		</div>
		<div class="pull-right">
			<form class="form-inline" method="GET">
				<div class="form-group mb-2">
					<div class="dropdown">	
					<button class="btn bg-teal-400  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="icon-download"></i></b> Download </button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						@foreach($dwnload as $dn => $dv)
						<a class="dropdown-item" href="{!! url(getRoleName().'/timeslotmanexport/'.$dn.'?status='.\Request::query('status')) !!}">{!! $dv !!}</a>
						@endforeach
						</div>
					</div>
				<div class="form-group mb-10">
					<select name="status" class="select-search form-control">
						<option value=""  >All Status</option>
						 <option value="active" @if(\Request::query('status') == 'active')  selected @endif >Active</option> 
						<option value="inactive" @if(\Request::query('status') == 'inactive') selected @endif>Inactive</option> 
						
						
					</select>
				</div>
				
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if(\Request::query('status') != '' || \Request::query('search') != '' )
				<a href="{!! url('admin/common/timeslotmanagement') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@endif
			</form>
		
		</div>
		<br><br>
	</div>

	<div class="table-responsive-xl">
		<table class="table datatable-responsive table-bordered">
			
			<thead>
				<tr>
					<th>#</th>
					<!-- <th>Category ID</th> -->
					<th>start</th>
					<th>End</th>
					<th>Status</th>
					@if($access->edit || $access->remove)
					<th class="text-center">Actions</th>
					@endif
				</tr>
			</thead>
			<tbody>

				@if(count($timeslotmanagement)>0)
				@foreach($timeslotmanagement as $key=>$value)
				<tr>
					<td>{!! ($key+1)+$page !!}</td>
					<!-- <td>{!!$value->cat_id!!}</td> -->
					<td>{!!$value->start!!}</td>
					<td>{!!$value->end!!}</td>
					<td>
						@if($value->status=='active')
						<span class="label label-success ws-nowrap">Active</span>
						@elseif($value->status=='inactive')
						<span class="label label-warning ws-nowrap">In-Active</span>
						@elseif($value->status=='declined')
						<span class="label label-danger ws-nowrap">In-Active</span>
						@else
						<span class="label label-info ws-nowrap">In-Active by Partner</span>
						@endif
					</td>
					@if($access->edit || $access->remove)
					<td>
						<div class="text-center d-flex">
							@if($access->edit)
							<button type="button" class="mr-2 btn btn-success btn-xs edit_popup" data-name="{!!$value->name!!}" data-id="{!!$value->id!!}" data-status="{!!$value->status!!}" data-cat_id="{!!$value->cat_id!!}" data-start="{!!$value->start!!}" data-end="{!!$value->end!!}" ><b><i class="fa fa-edit"></i></b></button>
							@endif
							@if($access->remove)
							<form action="{!!url(getRoleName().'/timeslotmanagement/'.$value->id.'/delete/')!!}" method="post" enctype="Multipart/form-data">
							<input name="_method" type="hidden" value="PUT">
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
</div>
<div class="mt-10">
	<div class="pull-left font-montserrat">
		Displaying {{$timeslotmanagement->count()+$page}} of {{ $timeslotmanagement->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$timeslotmanagement->appends(Request::except('page'))->render()}}
	</div>
</div>
<!-- /basic responsive configuration -->
<!-- Cuisine modal -->
<div id="modal_cuisine" class="modal">
	<div class="modal-dialog modal-bg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add/Edit Time Slot Management</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<form action="{!!url(getRoleName().'/timeslotmanagement/store')!!}" method="POST" enctype="Multipart/form-data">
			<div class="modal-body">
				
				{{ csrf_field() }}{{ method_field('PUT') }}
					<div class="form-group">
						<div class="row">
							<div class="col-sm-3">
								<label>Select Category</label>
								<select name='cat_id' rows='5' id='cat_id' class='form-control' required="" >
								<option value="">Select Category</option>
				                @if(count($category)>0)
				                @foreach($category as $key=>$value)
				                  <option value="{!!$value->id!!}">{!!$value->name!!}</option>
				                 
				                @endforeach
				                @endif
								</select>
							</div>

							<div class="col-sm-3">
								<label>Start Time</label>
								<select id="start" name="start" class="startTimeChange form-control" required="">
										<option value="">Start time</option>
										 @if(count($time_check)>0)
										@foreach($time_check as $key=>$value)
										<option value="{!!$value->id!!}" data-val="{!!$value->time!!}">{!!$value->name!!}</option>
										@endforeach
										@endif
									</select>
							</div>
							<div class="col-sm-3">
								<label>End Time</label>
								<select id="end" name="end" class="endTimeChange form-control" required="">
										<option value="">End time</option>
										  @if(count($time_check)>0)
										@foreach($time_check as $key=>$value)
										<option value="{!!$value->id!!}" data-val="{!!$value->time!!}">{!!$value->name!!}</option>
										@endforeach
										@endif
									</select>
							</div>

							<div class="col-sm-3">
								<label>Status</label>
								<select name="status" id="status" class="form-control" required="">
									<option value="">select any one</option>
									<option value="active">Active</option>
									<option value="inactive">In-Active</option>
									<!-- <option value="declined">Declined</option> -->
                					<!-- <option value="p_inactive">In-Active by partner</option> -->
								</select>
							</div>
						</div>
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
	//response will assign this function
	$(document).on('click','.edit_popup',function(){
		$("#id").val($(this).attr('data-id'));
		$("#cat_id").val($(this).attr('data-cat_id')).trigger('change');
		$("#start").val($(this).attr('data-start')).trigger('change');
		$("#end").val($(this).attr('data-end')).trigger('change');
		$("#status").val($(this).attr('data-status')).trigger('change');
		$("#modal_cuisine").modal('show');
	})
</script>
@endsection
