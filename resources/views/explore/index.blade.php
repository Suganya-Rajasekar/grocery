@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Master</span> - Explore</h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Explore</li>
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
		<a href="{!!url(getRoleName().'/explore/create')!!}"><button type="button" class="btn bg-teal-400 btn-labeled btn-rounded" {{-- data-toggle="modal" data-target="#modal_cuisine" --}}><b><i class="icon-atom2"></i></b> {{ ('Add New') }}</button></a>
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
						<a class="dropdown-item" href="{!! url(getRoleName().'/exploreexport/'.$dn.'?&search='.\Request::query('search').'&status='.\Request::query('status')) !!}">{!! $dv !!}</a>
						@endforeach
						</div>
					</div>
				<div class="form-group mb-10">
					<select name="status" class="select-search form-control">
						<option value=""  >All Status</option>
						 <option value="active" @if(\Request::query('status') == 'active')  selected @endif >Active</option> 
						<option value="inactive" @if(\Request::query('status') == 'inactive') selected @endif>In-Active</option>
						
						
					</select>
				</div>
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search name" value="{!! \Request::query('search') !!}">
				</div>
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if(\Request::query('status') != '' || \Request::query('search') != '' )
				<a href="{!! url('admin/common/explore') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
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
				<th>Image</th>
				<th>Name</th>
				<th>Tags</th>
				<th>Status</th>
				@if($access->edit || $access->edit)
				<th class="text-center">Actions</th>
				@endif
			</tr>
		</thead>
		<tbody>

			@if(count($explore)>0)
			@foreach($explore as $key=>$value)

			<?php $used_tags[] = $value->slug;?> 
			<tr>
				<td>{!! ($key+1)+$page !!}</td>
				<td><img src="@if($value->image != ''){!! $value->image !!}@endif" style="width: 40px;height: 40px;max-width: none;" class="img-circle" alt=""></td>
				<td>{!!$value->name!!}</td>
				<td>{!!$value->slug!!}</td>
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
					<!-- @if($value->status=='active')
					<span class="label label-success">Active</span>
					@else
					<span class="label label-warning">In-Active</span>
					@endif -->
				</td>
				@if( $access->edit || $access->remove)
				<td class="text-center">
				@if($value->type=='dynamic' && ( $access->edit || $access->remove))

				<form class="d-flex" action="{!!url(getRoleName().'/explore/'.$value->id)!!}" method="post" enctype="Multipart/form-data">
					@if($access->edit)
					{{-- <button type="button" class="mr-2 btn btn-success btn-xs edit_popup" data-name="{!!$value->name!!}" data-id="{!!$value->id!!}" data-status="{!!$value->status!!}" data-slug="{!!$value->slug!!}"  ><b><i class="fa fa-edit"></i></b></button> --}}
					<a href="{!!url(getRoleName().'/explore/'.$value->id.'/edit'.$url)!!}"><button type="button" class="btn mr-2 btn-success btn-xs" data-name="{!!$value->name!!}" data-id="{!!$value->id!!}" data-status="{!!$value->status!!}" data-slug="{!!$value->slug!!}" ><b><i class="fa fa-edit"></i></b></button></a>
					@endif
					@if($access->remove)
					<input name="_method" type="hidden" value="DELETE">
					{{ csrf_field() }}
					<button type="submit" class="btn btn-danger btn-xs"  data-id="{!!$value->id!!}" ><b><i class="fa fa-trash"></i></b></button>
					@endif
					</form>
				@endif
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
		Displaying {{$explore->count()+$page}} of {{ $explore->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$explore->appends(Request::except('page'))->render()}}
	</div>
</div>
<!-- /basic responsive configuration -->
<!-- Cuisine modal -->
<div id="modal_cuisine" class="modal">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add/Edit Explore</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="{!!url(getRoleName().'/explore/store')!!}" method="POST" enctype="Multipart/form-data">
				<div class="modal-body">

					{{ csrf_field() }}{{ method_field('PUT') }}
					<div class="form-group">
						<div class="row">
							<div class="col-sm-4">
								<label>Category name</label>
								<input type="text" name="name" id="name" placeholder="Category name" class="form-control" required="">
							</div>
							<div class="col-sm-4">
								<label>Select Tag</label>
								<?php //echo "<pre>"; echo $tags; echo "<pre>"; exit; ?>
								<select name="slug" id="tags" class="form-control" required="">
									<option value="">Select Category</option>
									@if(count($tags)>0)

									@foreach($tags as $key=>$value)

									<option value="{!!$value->id!!}"  @if(isset($used_tags) && $used_tags != '' && in_array($value->id,$used_tags)) disabled @endif>{!!$value->name!!}</option> 


									@endforeach
									@endif 
								</select>
							</div>

							<div class="col-sm-4">
								<label>Status</label>
								<select name="status" id="status" class="form-control" required="">
									<option value="">select any one</option>
									<option value="active">Active</option>
									<option value="inactive">In-Active</option>
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
		$("#id").val($(this).attr('data-id'));
		$("#name").val($(this).attr('data-name'));
		$("#tags").val($(this).attr('data-slug')).trigger('change');
		$("#status").val($(this).attr('data-status')).trigger('change');
		$("#modal_cuisine").modal('show');
	})
	
</script>
@endsection
