@extends('layouts.backend.app')
@section('page_header')
<?php
	$cpage	= request()->has('page') ? request()->get('page') : '';
	$from	= request()->has('from') ? request()->get('from') : '';
	$url	= '?from='.$from.'&page='.$cpage;
?>
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">@if(\Request::segment(3) == "create") Review - Add Review @else Review - Edit Review @endif</h5>
			</div>
		</div>
		<div class="breadcrumb-line">
			<ul class="breadcrumb">
				<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
				<li><a href="{!!url(getRoleName().'/review'.$url)!!}">All Reviews</a></li>
				<li class="active">@if(\Request::segment(3) == "create") {!! 'Add Review' !!} @else {!! 'Edit Review' !!} @endif</li>
			</ul>
		</div>
	</div>
	@endsection
	@section('content')
	@include('flash::message')
	<form action="{!!url(getRoleName().'/review/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
		{{ csrf_field() }}
		{{ method_field('PUT') }}
     <div class="col-md-12">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h5 class="panel-title">Basic details</h5>
				</div>
				<div class="panel-body">
					<input type="hidden" name="id" id="id" value="{!!isset($review->id) ? $review->id : ''!!}">
           <div class="row">
					<fieldset class="content-group col-lg-6">
						@if(Request::segment(3)=='create')
						<div class="form-group">
							<label class="text-semibold">Order id</label>
							<select name="order_id" class="select-search" id="orderid" required="">
								<option value="0">Select any one</option>
								@foreach($orders as $k => $val)
								<option value="{!! $val->id !!}" data-vid="{{ $val->vendor_id }}">{!! $val->s_id !!}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label class="text-semibold">Chefs</label>
							<select name="chef_id" class="select-search" id="chefs" required="">
								<option value="0">Select any one</option>
								@foreach($vendorData as $key => $value)
								<option value="{{ $value->id }}">{{ $value->name }}</option>
								@endforeach
							</select>
						</div>
						@endif
						<div class="form-group">
							<label class="text-semibold">Rating</label>
							<select name="rating" id="rating" class="select-search" required="">
								<option {!!isset($review->rating) && $review->rating==1  ? 'selected=""' : ''!!}  value="1">1</option>
								<option {!!isset($review->rating) && $review->rating==2  ? 'selected=""' : ''!!}  value="2">2</option>
								<option {!!isset($review->rating) && $review->rating==3  ? 'selected=""' : ''!!}  value="3">3</option>
								<option {!!isset($review->rating) && $review->rating==4  ? 'selected=""' : ''!!}  value="4">4</option>
								<option {!!isset($review->rating) && $review->rating==5  ? 'selected=""' : ''!!}  value="5">5</option>
							</select>
						</div>
          </fieldset>
           <fieldset class="col-lg-6">
						  <div class="form-group">
							<label class="text-semibold">Status</label>
							<select name="status" id="status" class="select-search" required="">
								<option {!!isset($review->status) && $review->status=='pending'  ? 'selected=""' : ''!!}  value="pending">Pending</option>
								<option {!!isset($review->status) && $review->status=='published'  ? 'selected=""' : 'published'!!}  value="published">Published</option>
								<option {!!isset($review->status) && $review->status=='rejected'  ? 'selected=""' : 'rejected'!!}  value="rejected">Rejected</option> 
							</select>
						</div>
						<div class="col-lg-5" @if((isset($review->status) && $review->status != 'rejected') || (!isset($review->status))) style="display:none;" @endif id="reason">
							<input type="text" name="reason" id="inputreason" value="{!! (isset($review->status) && ($review->status == 'rejected')) ? $review->reason : '' !!}" class="form-control" placeholder="Enter the reason for reject">
						</div>
            <div class="form-group">
              <label class="text-semibold">Review</label>
              <textarea name="reviews" rows="3" cols="3" class="form-control limitcount" placeholder="Enter review">{!!isset($review->reviews) ? $review->reviews : ''!!}</textarea>
            </div>  
					</fieldset>
        </div>
					<div class="text-right">
						<button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
						<a href="{!! url(getRoleName().'/review') !!}" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
					</div>
				</div>
			</div>
		</div>
	</form>
	<script>
		$(document).on('change','#status',function(){
			if ( this.value == 'rejected') {
				$('#reason').show();
				$('#inputreason').attr('required',true);
			}
			else {
				$('#reason').hide();
				$('#inputreason').removeAttr('required',true);
			}
		});
		$(document).on('change','#orderid',function(){
				var vendor_id= $(this).find(':selected').attr('data-vid');
				$('#chefs').val(vendor_id);
				$('#chefs').select2();
				var check = $('#chefs').val();
				if(check == null) {
					$('#chefs').val(0);
					$('#chefs').select2();
				}
		});
		$(document).on('change','#chefs',function(){
				$('#orderid').val(0);
				$('#orderid').select2();
		});
	</script> 
	@endsection
