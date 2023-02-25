@extends('layouts.backend.app')
@section('content')
@include('flash::message')
@php 
$pages=[];
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Manage Categories</h5>		
		<div class="heading-elements">
			<ul class="icons-list">
				<li><a href="{!!url('chef/'.$v_id.'/category/create')!!}"><button type="button" class="btn bg-teal-400 btn-labeled pull-right"><b><i class="fa fa-cutlery"></i></b> {{ __('Add New') }}</button></a></li>
			</ul>
		</div>
	</div>
	<table class="table datatable-responsive">
		<thead>
			<tr>
				<th>#</th>
				<th>Category ID</th>
				<th>Name</th>
				<!-- <th>Content</th> -->
				<th>Status</th>
				<th class="text-center">Actions</th>
			</tr>
		</thead>
		<tbody>
			@if(count($category)>0)
			@foreach($category as $key=>$value)
			<tr>
				<td>{!!$key+1!!}</td>
				<td>{!!$value->id!!}</td>
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
					<span class="label label-info">In-Active by Partner</span>
					@endif
				</td>
				<td class="text-center">				
					<a href="{!!url('chef/'.$v_id.'/category/edit/'.$value->id)!!}"><button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-edit"></i></b></button></a>
				</td>
			</tr>
			@endforeach
			@endif
		</tbody>
	</table>
</div>
<!-- /basic responsive configuration -->
@endsection
@section('script')
<script type="text/javascript">
	"use strict";
</script>
@endsection
