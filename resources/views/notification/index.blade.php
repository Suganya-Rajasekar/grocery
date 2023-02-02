@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Notification</span></h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Notification</li>
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
	{{-- <div class="panel-body">
		<div class="pull-left">
			@if($access->edit)
			<div class="panel-heading ml-3">
				<label class="checkbox-inline">
					Select All
					<input type="checkbox" name="delete[]" value="0" form="DeleteMultiple" class="styled selectAll">
				</label>
				<input form="DeleteMultiple" type="submit" class="btn bg-danger ml-2" value="Delete selected">
			</div>
			@endif
		</div>
		<div class="pull-right"></div>
	</div>
	<form id="DeleteMultiple" action="{!! route('admin.multidelete') !!}" method="POST">
		{{ csrf_field() }}
	</form>
	<br><br> --}}
	<div class="table-responsive">
		<table class="table datatable-responsive table-bordered">
			<thead>
				<tr>
					<th>S.No</th>
					<th>From</th>
					{{-- <th>To</th> --}}
					{{-- <th>Type</th> --}}
					{{-- <th>Url</th> --}}
					<th>Title</th>
					<th>Note</th>
					{{-- <th>Is Read</th> --}}
					<th>Date</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>
			<tbody>
				@if(count($notification)>0)
				@foreach($notification as $key=>$value)
				<tr>
					<a href="{!! $value->url !!}" class="isread" data-id="{!! $value->id !!}">
						<td>
							<label class="checkbox-inline">
								{!! ($key+1)+$page !!}
								{{-- <input type="checkbox" form="DeleteMultiple" name="delete[]" value="{!!$value->id!!}"  class="styled chefscheck"  > --}}
							</label>
						</td> 
						<td>{!! $value->from_user_info->name ?? '' !!}</td>
						{{-- <td>{!! $value->to!!}</td> --}}
						{{-- <td>{!! $value->type!!}</td> --}}
						{{-- <td>{!! $value->url!!}</td> --}}
						<td>{!! $value->title !!}</td>
						<td>{!! $value->note !!}</td>
						{{-- <td>{!! $value->is_read!!}</td> --}}
						<td>{!! date('M d Y - H:i', strtotime($value->created_at)) !!}</td>
						<td class="text-center">
						<a href="{!! $value->url !!}" class="isread" data-id="{!! $value->id !!}"><button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-pencil"></i></b></button></a>
						</td>
					</a>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
<div class="mt-10">
	<div class="pull-left font-montserrat">
		Displaying {{$notification->count()+$page}} of {{ $notification->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$notification->appends(Request::except('page'))->render()}}
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
	</script>
	@endsection
