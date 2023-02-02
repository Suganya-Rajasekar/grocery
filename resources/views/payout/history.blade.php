@extends('layouts.backend.app')
@section('content')
@include('flash::message')
@php 
$pages=[];
$access = getUserModuleAccess('payout/'.\Request::segment(3));
$a_role=(\Auth::user()->role==1 || \Auth::user()->role==5) ? 'admin' : 'vendor';
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Payout history</h5>		
		
	</div>
	<div class="panel-body pull-right">
		@include('filter')
	</div>
	<div class="table-responsive-xl">
		<table class="table table-bordered">
			
			<thead>
				<tr>
					<th class="ws-nowrap">#</th>
					<th class="ws-nowrap">UTR</th>
					<th class="ws-nowrap">Chef</th>
					<th class="ws-nowrap">Amount Transferred</th>
					<th class="ws-nowrap">Date</th>
					<th class="ws-nowrap">Status</th>
					<th class="ws-nowrap">Action</th>
				</tr>
			</thead>
			<tbody>

				@if(count($resultData)>0)
				@foreach($resultData as $key=>$value)
				<tr>
					<td>{!!($key+1)+$page!!}</td>
					<td>{!!$value->utr ?? '<span class="label label-danger">Not yet updated</span>'!!}</td>
					<td>{!!$value->getVendorDetails['name'] ?? ''!!}</td>
					<td>{!!$value->amount!!}</td>
					<td class="date-payout"><p>{!!date('d M Y',strtotime($value->created_at))!!}</p></td>
					<td>{!!  $value->status == "razorpay_issue" ? $value->razor_reason : ucfirst($value->status)!!}</td>
					<td><span data-id="{!!$value->id!!}" class="badge badge-info cursor-pointer viewOrders">View</span>
						<a href="{!! URL('/').'/'.$a_role !!}/payout/orderlist/{!! $value->id !!}/download">Download</a>
					</td>
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
<div id="modal_status" class="modal" aria-modal="true" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
	            <h5 class="modal-title">Settlement Details</h5>
	            <button type="button" class="close" data-dismiss="modal">×</button>
	        </div>
			<div class="modal-body orderList">

			</div>
		</div>
	</div>
</div>
<!-- /Cuisine modal -->
@endsection
@section('script')
<style type="text/css">
.datepicker td span {
	height: 36px !important;
	line-height: 28px !important;
}
.datepicker td span.active {
	background-image: linear-gradient(to bottom, #ef606e, #eb405e) !important;
}
.datepicker table thead tr:first-child th, .datepicker table tfoot tr th {
	padding-top: 10px !important;
	padding-bottom: 10px !important;
}
</style>
<script type="text/javascript" src="https://www.eyecon.ro/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="https://www.eyecon.ro/bootstrap-datepicker/css/datepicker.css">
<script type="text/javascript">
	$(document).ready(function(){
	$('.monthonly').datepicker({
		autoclose: true
	}).on('change', function(){
        $('.monthonly').hide();
    });;
	});
	"use strict";	
	$(document).on('click','.edit_popup',function(){
		$("#id").val($(this).attr('data-id'));
		$("#status").val($(this).attr('data-status')).trigger('change');
		$("#modal_status").modal('show');
	})
	$(document).on('click','.viewOrders',function(e){
		var payoutid = $(this).attr('data-id');
	    $.ajax({
	        type : 'GET',
	        url : base_url+'/{!! $a_role !!}/payout/orderlist/'+payoutid+"/html",
	        success : function(res){
				$('.orderList').html(res.html);
				$('#modal_status').modal('show');
	        },
	        error : function(err){
	        	
	        }
    	});
    });
</script>
@endsection
