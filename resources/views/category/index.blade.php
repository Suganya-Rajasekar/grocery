@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Master</span> - Categories</h5>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Categories</li>
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
				<button type="button" class="btn bg-teal-400 btn-labeled btn-rounded" data-toggle="modal" data-target="#modal_cuisine"><b><i class="fa fa-cube"></i></b> {{ ('Add New') }}</button>
				<a href="{!!url(getRoleName().'/category/create')!!}"><button type="button" class="btn bg-teal-400 btn-labeled btn-rounded"><b><i class="fa fa-cube"></i></b> {{ ('Add New') }}</button></a>
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
							<a class="dropdown-item" href="{!! url(getRoleName().'/categoryexport/'.$dn.'?&search='.\Request::query('search').'&status='.\Request::query('status')) !!}">{!! $dv !!}</a>
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
					<div class="form-group mb-2">
						<input type="text" class="form-control" name="search" placeholder="Search name" value="{!! \Request::query('search') !!}">
					</div>
					<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
					@if(\Request::query('status') != '' || \Request::query('search') != '' )
					<a href="{!! url('admin/common/category') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
					@endif
				</div>
			</form>
			<br><br>
		</div>
		<div class="table-responsive-xl">
			<table class="table datatable-responsive table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<!-- <th>Category ID</th> -->
						<th>Name</th>
						<!-- <th>Content</th> -->
						<th>Status</th>
						@if($access->edit || $access->remove )
						<th class="text-center">Actions</th>
						@endif
					</tr>
				</thead>
				<tbody>
					@if(count($category)>0)
					@foreach($category as $key=>$value)
					<tr>
						<td>{!! ($key+1)+$page !!}</td>
						<!-- <td>{!!$value->id!!}</td> -->
						<td>{!!$value->name!!}</td>
						<!-- <td>{!!$value->content!!}</td> -->
						<td>
							@if($value->status=='active')
							<span class="label label-success">Active</span>
							@elseif($value->status=='inactive')
							<span class="label label-warning">In-Active</span>
							@elseif($value->status=='declined')
							<span class="label label-danger">In-Active</span>
							@else
							<span class="label label-info ws-nowrap">In-Active by Partner</span>
							@endif
						</td>
						@if($access->edit || $access->remove )
						<td >
							<div class="text-center d-flex">
								<a class="mr-2" href="{!!url(getRoleName().'/category/edit/'.$value->id.$url)!!}">
									<button type="button" class=" btn btn-success btn-xs" ><b><i class="fa fa-edit"></i></b></button></a>
									<form action="{!!url(getRoleName().'/category/'.$value->id.'/delete/')!!}" method="post" enctype="Multipart/form-data">
										<input name="_method" type="hidden" value="PUT">
										{{ csrf_field() }}
										<button type="submit" class="btn btn-danger btn-xs"  data-id="{!!$value->id!!}" ><b><i class="fa fa-trash"></i></b></button>
									</form>
								</div>
							<div class="text-center d-flex">
								@if($access->edit)
								<button type="button" class="mr-2 btn btn-success btn-xs edit_popup" data-name="{!!$value->name!!}" data-id="{!!$value->id!!}" data-status="{!!$value->status!!}" ><b><i class="fa fa-edit"></i></b></button>
								@endif
								@if($access->remove)
								<form action="{!!url(getRoleName().'/category/'.$value->id.'/delete/')!!}" method="post" enctype="Multipart/form-data">
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
			Displaying {{$category->count()+$page}} of {{ $category->total() }} data(s).
		</div>
		<div class="pull-right">
			{{$category->appends(Request::except('page'))->render()}}
		</div>
	</div>
	<!-- /basic responsive configuration -->
	<!-- Cuisine modal -->
	<div id="modal_cuisine" class="modal">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Add/Edit Category</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<form action="{!!url('category/store')!!}" method="POST" enctype="Multipart/form-data">
					<div class="modal-body">
						{{ csrf_field() }}{{ method_field('PUT') }}
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label>Category name</label>
									<input type="text" name="name" id="name" placeholder="Category name" class="form-control" required="">
								</div>
								<div class="col-sm-6">
									<label>Status</label>
									<select name="status" id="status" class="select-search" required="">
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
</div>
@endsection
@section('script')
<script type="text/javascript">
	"use strict";
	//response will assign this function
	$(document).on('click','.edit_popup',function(){
		$("#id").val($(this).attr('data-id'));
		$("#name").val($(this).attr('data-name'));
		$("#status").val($(this).attr('data-status')).trigger('change');
		$("#modal_cuisine").modal('show');
	})
</script>
@endsection
