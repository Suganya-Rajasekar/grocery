@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Reviews</span></h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Reviews</li>
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
				<a href="{!!url(getRoleName().'/review/create')!!}"><button type="button" class="btn bg-teal-400 btn-labeled btn-rounded"><b><i class="fa fa-star-o"></i></b> {{ ('Add New') }}</button></a>
			</div>
			@endif
		</div>
		</div>
		<div class="pull-right">
			<form class="form-inline" method="GET">
				<div class="form-group mb-2">
					<div class="dropdown">	
					<button class="btn bg-teal-400  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="icon-download"></i></b> Download </button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						@foreach($dwnload as $dn => $dv)
						<a class="dropdown-item" href="{!! url(getRoleName().'/reviewexport/'.$dn.'?user_id='.\Request::query('user_id').'&rating='.\Request::query('rating').'&date='.\Request::query('date').'&vendor_id='.\Request::query('vendor_id').'&search='.\Request::query('search').'&status='.\Request::query('status')) !!}">{!! $dv !!}</a>
						@endforeach
						</div>
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
				@if(getRoleName()=='admin')
				<div class="form-group mb-2">
					<select name="vendor_id" class="select-search form-control">
						<option value="" selected >All Vendors</option>
						@if(count($vendorData)>0)
						@foreach($vendorData as $vname)
						<option value="{!! $vname->id !!}" @if(\Request::query('vendor_id') != '' && ($vname->id == \Request::query('vendor_id')))  selected @endif>{!! $vname->name !!}</option>
						@endforeach
						@endif
					</select>
				</div>
				@endif
				<div class="form-group mb-2">
					<select name="rating" class="select-search form-control">
						<option value="" selected >All</option>
						<option value="0" @if(\Request::query('rating') == '0') selected @endif >0</option>
						<option value="1" @if(\Request::query('rating') == '1') selected @endif >1</option>
						<option value="2" @if(\Request::query('rating') == '2') selected @endif >2</option>
						<option value="3" @if(\Request::query('rating') == '3') selected @endif >3</option>
						<option value="4" @if(\Request::query('rating') == '4') selected @endif >4</option>
						<option value="5" @if(\Request::query('rating') == '5') selected @endif >5</option>

					</select>
				</div>
				<div class="form-group mb-2">
					<input type="hidden" name="date" value="" id="start_date">
					<input type="text" class="form-control daterange-basic" id="date" value="{!! (\Request::query('date') != '') ? \Request::query('date') : '' !!}">
				</div>
				<div class="form-group mb-2">
					<input type="text" class="form-control" name="search" placeholder="Search id or review" value="{!! \Request::query('search') !!}">
				</div>
				<div class="form-group mb-10">
					<select name="status" class="select-search form-control">
						<option value="">All Status</option>
						<option value="published" @if(\Request::query('status') == 'published') selected @endif >Published</option> 
						<option value="pending" @if(\Request::query('status') == 'pending') selected @endif>Pending</option>
					</select>
				</div>
				<button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
				@if( \Request::query('search') != '' || \Request::query('date') != '' || \Request::query('user_id') != '' || \Request::query('vendor_id') != ''	||	\Request::query('status') != ''	|| \Request::query('rating') != '')
				<a href="{!! url(getRoleName().'/review') !!}" class="btn btn-danger font-monserret ml-2 mb-2"><i class="icon-cancel-circle2"></i> Clear</a>
				@endif
			</form>
		</div>
	</div>

	<div class="table-responsive-xl">
		<table class="table table-bordered">
		
		<thead>
			<tr>
				<th>#</th>
				<th>Order ID</th>
				<th>Customer</th>
				@if(getRoleName()=='admin')
				<th>Chef</th>
				@endif
				<th>Rating</th>
				<th>Type</th>
				<th>Review</th>
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
				<td>@if(!empty($value->order)) {!!$value->order->s_id!!} @endif</td>
				<td>{!!isset($value->getUserDetails) ? $value->getUserDetails['name'] : ''!!}</td>
				@if(getRoleName()=='admin')
				<td>{!!isset($value->getVendorDetails) ? $value->getVendorDetails['name'] : ''!!}</td>
				@endif
				<td>{!!$value->rating!!}</td>
				<td>{!! ($value->r_id == 0) ? 'review': 'reply' !!}</td>
				<td class="review"><p>{!!$value->reviews!!}</p></td>
				<td>{!!ucfirst($value->status)!!}</td>
				<td class="date-review"><p>{!!date('M-d, Y h:i A',strtotime($value->created_at))!!}</p></td>
				<td >
					<div class="d-flex"> 
                        @if(getRoleName()=='admin' && ( $access->edit || $access->remove ))
					    @if($access->edit)
						<a href="{!! ($value->r_id == 0) ? url(getRoleName().'/review/'.$value->id.'/edit'.$url) : 'javascript:void(0)'!!}"><button type="button" class="mr-2 btn btn-success btn-xs" data-review="{{ ($value->r_id != 0) ? 'reply' : '' }}" @if($value->r_id != 0) id="reply_edit" @endif data-id="{!!$value->id!!}"><b><i class="fa fa-edit"></i></b></button></a>
						@endif
						@if($access->remove)
						<form action="{!!url(getRoleName().'/review/'.$value->id)!!}" method="post" enctype="Multipart/form-data">
						<input name="_method" type="hidden" value="DELETE">
						{{ csrf_field() }}
						<button type="submit" class="btn btn-danger btn-xs"  data-id="{!!$value->id!!}" ><b><i class="fa fa-trash"></i></b></button>
						</form>
						@endif
						@if($value->created_by != 0  && $value->r_id ==0)
						<button type="button" class="ml-2 btn btn-primary btn-xs edit_popup"  data-id="{!!$value->id!!}" data-name="{!!$value->reviews!!}" data-status="{!!(!empty($value->order)) ? $value->order->order_id : '';!!}" @if($value->created_by == 0) disabled title="You are not reply for this review" @endif><b><i class="fa fa-reply"></i></b></button>
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
<!-- review reply  modal -->
<div id="modal_cuisine" class="modal fade">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Review Reply </h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="{!!url(getRoleName().'/review/store')!!}" method="POST" enctype="Multipart/form-data">
				{{ csrf_field() }}
		     {{ method_field('PATCH') }}
			<div class="modal-body">
				<div class="form-group">
							<div class="row">
								<div class="col-sm-12">
									<input type="hidden" name="review_id" id="review_id"  class="form-control" >
									<input type="hidden" name="order_id" id="order_id"  class="form-control" >
									<label>Customer review</label>
									<textarea  id="reviews" rows="3" cols="3" class="form-control limitcount"></textarea>
									<label>Review reply</label>
									<textarea name="reply" id="reply" rows="3" cols="3" class="form-control limitcount" placeholder="Enter Your Reply " required=""></textarea>
								</div>
							</div>
						</div>
					</div>
				
				<div class="modal-footer">
					
					<button type="button" class="btn btn-common" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
	</form>	
		</div>
	</div>
</div>
<!-- review reply modal -->
<div id="modal_reply" class="modal fade">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> Reply Edit </h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="{!!url(getRoleName().'/review/store')!!}" method="POST" enctype="Multipart/form-data">
				{{ csrf_field() }}
		     {{ method_field('PUT') }}
		     <div class="modal-body">
				<div class="form-group">
							<div class="row">
								<div class="col-sm-12">
									<input type="hidden" name="review_id" id="reply_review_id"  class="form-control" >
									<input type="hidden" name="order_id" id="order_id"  class="form-control">
									<label>Review reply</label>
									<textarea name="reply" rows="3" cols="3" class="form-control limitcount" placeholder="Enter Your Reply" required="" id="edit_reply"></textarea>
									<br>
									<label>Status</label>
									<select name="status" id="status" class="select-search" required="">
										<option value="pending">Pending</option>
										<option value="published">Published</option>
										<option value="rejected">Rejected</option> 
									</select>
								</div>
							</div>
						</div>
					</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-common" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
	</form>	
		</div>
	</div>
</div>

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
	
	
</script>
<script type="text/javascript">
	"use strict";	
	//response will assign this function
	$(document).on('click','.edit_popup',function(){
		$("#review_id").val($(this).attr('data-id'));
		$("#reviews").val($(this).attr('data-name'));
		$("#order_id").val($(this).attr('data-status')).trigger('change');
		var review_id = $(this).attr('data-id'); 
		$.ajax({
			url:base_url + '/admin/review/'+review_id+'/edit',
			type:'get',
			data:{action:'reply_check'},
			success:function(res){
				if(res != ''){
					$('#reply').val(res.reviews);
				}
				$("#modal_cuisine").modal('show');
			}
		});
	})
	$(document).on('click','#reply_edit',function(){
		var review_id = $(this).attr('data-id');
		$.ajax({
			url:base_url + '/admin/review/'+review_id+'/edit',
			type:'get',
			success:function(res){
				$('#reply_review_id').val(res.id);
				$('#edit_reply').val(res.reviews);
				$('#status').val(res.status);
				$('#status').select2();
				$("#modal_reply").modal('show');
			}
		});
	});
</script>
@endsection
