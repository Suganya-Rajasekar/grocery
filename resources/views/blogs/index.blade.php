@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">	</span> - Blogs</h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Blogs</li>
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
				<a href="{!!url(getRoleName().'/blogs/create')!!}"><button type="button" class="btn bg-teal-400 btn-labeled btn-rounded"><b><i class="icon-pie-chart3"></i></b> {{ ('Add New') }}</button></a>
			</div>
			@endif

		</div>
		<div class="pull-right">
		{{-- <form class="form-inline" method="GET" >
				<div class="form-group mb-2">
				
				<div class="form-group mb-10">
					<select name="status" class="select-search form-control">
						<option value="">All Status</option>
						<option value="active" @if(\Request::query('status') == 'active') selected @endif >Active</option> 
						<option value="inactive" @if(\Request::query('status') == 'inactive') selected @endif>Inactive</option>
					</select>
				</div>
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search name or all description" value="{!! \Request::query('search') !!}">
				</div>
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if(\Request::query('status') != '' || \Request::query('search') != '' )
				<a href="{!! url('admin/popular_recipe') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@endif
			</form> --}}

		</div>
		<br><br>
	</div>

	<div class="table-responsive-xl">
		<table class="table datatable-responsive table-bordered">
			<thead>
				<tr>
					<th>S.No</th>
					<th>Image</th>
					<th>Name</th>
					<th>Description</th>
					<th>Status</th>
					@if($access->edit || $access->remove )
					<th class="text-center">Actions</th>
					@endif
				</tr>
			</thead>
			<tbody>
				@if(count($blogs)>0)
				@foreach($blogs as $key=>$value)
				<tr>
					<td>{!! ($key+1)+$page !!}</td>
					<td><img src="@if($value->image != ''){!! $value->image !!}@endif" style="width: 40px;height: 40px;max-width: none;" class="img-circle" alt=""></td>
					<td>{!!$value->name!!}</td>
					<td>{!!$value->description!!}</td>
					<td>
						@if($value->status=='active')
						<span class="label label-success">Active</span>
						@else
						<span class="label label-danger ws-nowrap">In-Active</span>
						@endif
					</td>
					@if($access->edit || $access->remove )
					<td>				
						<div class="text-center d-flex">
							@if($access->edit)
							<a href="{!!url(getRoleName().'/blogs/'.$value->id.'/edit'.$url)!!}"><button type="button" class="btn mr-2 btn-success btn-xs" data-name="{!! $value->name !!}" data-id="{!!$value->id!!}" data-status="{!! $value->status !!}" ><b><i class="fa fa-edit"></i></b></button></a>
							@endif
							@if($access->remove)
							<form action="{!!url(getRoleName().'/blogs/'.$value->id.'/delete/')!!}" method="post" enctype="Multipart/form-data">
							<input name="_method" type="hidden" value="PUT">
							{{ csrf_field() }}
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
		Displaying {{$blogs->count()+$page}} of {{ $blogs->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$blogs->appends(Request::except('page'))->render()}}
	</div>
</div>
<!-- /basic responsive configuration -->

<!-- Cuisine modal -->
<div id="modal_cuisine" class="modal fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Add/Edit Cuisine</h5>
			</div>

			<form action="{!!url(getRoleName().'/blogs/store')!!}" method="POST" enctype="Multipart/form-data">
				{{ csrf_field() }}{{ method_field('PUT') }}
				<div class="modal-body">
					<div class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<label>Cuisine name</label>
								<input type="text" name="c_name" id="c_name" placeholder="Cuisine name" class="form-control" required="">
							</div>

							<div class="col-sm-6">
								<label>Status</label>
								<select name="c_status" id="c_status" class="select-search" required="">
									<option value="">select any one</option>
									<option value="active">Active</option>
									<option value="inactive">In-Active</option>
								</select>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<input type="hidden" name="c_id" id="c_id">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- /Cuisine modal -->
@endsection
<style type="text/css">
	.img-circle {
		width: 40px;height: 40px;max-width: none;border-radius: 50%;
	}
</style>
@section('script')
<script type="text/javascript">
	"use strict";	
	//response will assign this function
	$(document).on('click','.edit_popup',function(){
		$("#c_id").val($(this).attr('data-id'));
		$("#c_name").val($(this).attr('data-name'));
		$("#c_status").val($(this).attr('data-status')).trigger('change');
		$("#modal_cuisine").modal('show');
	})
	
</script>
@endsection

