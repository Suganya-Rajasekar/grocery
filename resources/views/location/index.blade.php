@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Location</span></h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Location</li>
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
			@if($access->edit)
	<div class="panel-heading">
		<button type="button" class="btn bg-teal-400 btn-labeled btn-rounded" data-toggle="modal" data-target="#modal_location"><b><i class="icon-city"></i></b> {{ ('Add New') }}</button>
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
						<a class="dropdown-item" href="{!! url(getRoleName().'/locationexport/'.$dn.'?search='.\Request::query('search').'&status='.\Request::query('status')) !!}">{!! $dv !!}</a>
						@endforeach
						</div>
					</div>
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search name or code" value="{!! \Request::query('search') !!}">
				</div>
				<div class="form-group mb-10">
					<select name="status" class="select-search form-control">
						<option value="">All Status</option>
						<option value="active" @if(\Request::query('status') == 'active') selected @endif >Active</option> 
						<option value="inactive" @if(\Request::query('status') == 'inactive') selected @endif>In-Active</option>
						
					</select>
				</div>
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if( \Request::query('search') != '' || \Request::query('status') != '' )
				<a href="{!! url('admin/location') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
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
					<th>Location Name</th>
					<th>Code</th>
					<th>Status</th>
					@if($access->edit || $access->remove)
					<th class="text-center">Actions</th>
					@endif
				</tr>
			</thead>
			<tbody>

				@if(count($location)>0)
				@foreach($location as $key=>$value)
				<tr>
					<td>{!!$key+1!!}</td>
					<td>{!!$value->name!!}</td>
					<td>{!!$value->code!!}</td>
					<td>
						@if($value->status=='active')
						<span class="label label-success">Active</span>
						@else
						<span class="label label-danger">In-Active</span>
						@endif
					</td>
					@if($access->edit || $access->remove)
					<td >				
						<div class="text-center d-flex">
							@if($access->edit)
							<button type="button" class="mr-2 btn btn-success btn-xs edit_popup" data-name="{!!$value->name!!}" data-code="{!!$value->code!!}" data-id="{!!$value->id!!}" data-status="{!!$value->status!!}" data-lat="{!!$value->latitude!!}" data-long="{!!$value->longitude!!}" ><b><i class="fa fa-edit"></i></b></button>
							@endif
							<form action="{!!url(getRoleName().'/location/'.$value->id)!!}" method="post" enctype="Multipart/form-data">
							<input name="_method" type="hidden" value="DELETE">
							{{ csrf_field() }}
							@if($access->remove)
							<button type="submit" class="btn btn-danger btn-xs"  data-id="{!!$value->id!!}" ><b><i class="fa fa-trash"></i></b></button>
							@endif
							</form>
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
		Displaying {{$location->count()+$page}} of {{ $location->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$location->appends(Request::except('page'))->render()}}
	</div>
</div>
<!-- /basic responsive configuration -->

<!-- Location modal -->
<div id="modal_location" class="modal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add/Edit Location</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

		<form action="{!!url(getRoleName().'/location/store')!!}" method="POST" enctype="Multipart/form-data">
					{{ csrf_field() }}{{ method_field('PUT') }}
			<div class="modal-body">
				
					<div class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<label>Location name</label>
								<input type="text" name="l_name" id="l_name" placeholder="Location" class="form-control" required="">
							</div>
							<div class="col-sm-6">
								<label>Code</label>
								<input type="text" name="code" id="code" placeholder="code" class="form-control" required="">
							</div>
							<div class="col-sm-6">
								<label>Latitude</label>
								<input type="text" name="latitude" id="latitude" placeholder="latitude" class="form-control" required="">
							</div>
							<div class="col-sm-6">
								<label>Longitude</label>
								<input type="text" name="longitude" id="longitude" placeholder="longitude" class="form-control" required="">
							</div>

							<div class="col-sm-6">
								<label>Status</label>
								<select name="l_status" id="l_status" class="select-search" required="">
									<option value="">select any one</option>
									<option value="active">Active</option>
									<option value="inactive">In-Active</option>
								</select>
							</div>
						</div>
					</div>
			</div>

			<div class="modal-footer">
				<input type="hidden" name="l_id" id="l_id">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>
		</form>
		</div>
	</div>
</div>
<!-- /Location modal -->
@endsection
@section('script')
<script type="text/javascript">
	"use strict";	
	//response will assign this function
	$(document).on('click','.edit_popup',function(){
		$("#l_id").val($(this).attr('data-id'));
		$("#l_name").val($(this).attr('data-name'));
		$("#code").val($(this).attr('data-code'));
		$("#latitude").val($(this).attr('data-lat'));
		$("#longitude").val($(this).attr('data-long'));
		$("#l_status").val($(this).attr('data-status')).trigger('change');
		$("#modal_location").modal('show');
	})
	
</script>
@endsection
