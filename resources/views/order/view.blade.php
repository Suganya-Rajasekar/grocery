@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Orders</span> - All orders</h5>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li><a href="{!! url(getRoleName().'/order/all') !!}">All orders</a></li>
			<li class="active">Order detail <b>(#{!!$resultData->Orderdetail[0]->m_id!!})</b></li>
		</ul>
	</div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
@php 
$pages=[];
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat invoice-asw">
	@php
	$pdfUrl = getRoleName().'/generatepdf/'.$resultData->id;
	@endphp
	<div class="panel panel-white">
		<div class="panel-heading">
			<h6 class="panel-title">Order detail<a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
			<div class="heading-elements">
				<button type="button" class="btn btn-info btn-xs heading-btn" onclick="window.location.href='{!!url($pdfUrl)!!}'" target="_blank"><i class="icon-file-check position-left"></i> Save</button>
				<button type="button" class="btn btn-info btn-xs heading-btn" onclick="window.print()"><i class="icon-printer position-left"></i> Print</button>
			</div>
		</div>
	</div>
	<hr>
	<!-- Dashboard content -->
	<div class="row" id="section-to-print">
		<div class="col-lg-12">
			<div class="panel-body no-padding-bottom">
				<div class="row justify-content-between">
					<div class="col-md-6 col-lg-3 content-group">
						<img  src="{{ asset('assets/front/img/logo.svg') }}" alt="" style="width: 80px;">
						<ul class="list-condensed list-unstyled">
							{{-- <li>City</li>
							<li>State, India</li>
							<li>000-000-0000</li> --}}
						</ul>
					</div>
					<div class="col-lg-6 col-md-6 content-group">
						<div class="invoice-details">
							<span class="text-uppercase text-semibold">Invoice #{!!$resultData->Orderdetail[0]->m_id!!}</span>
							<ul class="list-condensed list-unstyled">
								<li>Ordered Date: <span class="text-semibold">{!!date('M d Y', strtotime($resultData->created_at))!!}</span></li>
								<li>Due date: <span class="text-semibold">{!!date('M d Y', strtotime($resultData->Orderdetail->max('date')))!!}</span></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="row justify-content-between">
					<div class="col-lg-5 col-md-6 content-group">
						<span class="text-muted">Invoice To:</span>
						<ul class="list-condensed list-unstyled">
							<li>{!!isset($resultData->getUserDetails) ? $resultData->getUserDetails['name'] : ''!!}</li>
							<li>{!!isset($resultData->getUserAddress) ? $resultData->getUserAddress->display_address : ''!!}</li>
							@if(\Auth::user()->role == 1 || \Auth::user()->role == 5)
							<li>{!!isset($resultData->getUserDetails) ? $resultData->getUserDetails['mobile'] : ''!!}</li>
							@endif
							@if(\Auth::user()->role == 1 || \Auth::user()->role == 5)
							<li><a href="mailto:{!!isset($resultData->getUserDetails) ? $resultData->getUserDetails['email'] : ''!!}">{!!isset($resultData->getUserDetails) ? $resultData->getUserDetails['email'] : ''!!}</a></li>
							@endif
						</ul>
					</div>
					<div class="col-md-6 col-lg-3 content-group">
						<span class="text-muted">Payment Details:</span>
						<ul class="list-condensed list-unstyled invoice-payment-details">
							<li><h5>Total Amount: <span class="text-right text-semibold">Rs.  {!! $resultData->grand_total !!}</span></h5></li>
							<li>Payment Type: <span class="text-semibold">{!! $resultData->payment_type !!}</span></li>
							<li>Number of orders: <span class="text-semibold">{!!$resultData->order_count!!}</span></li>
						</ul>
					</div>
				</div>
				<div class="panel panel-flat">
					<div class="table-responsive">
						@if($resultData)
						@if(count($resultData->Orderdetail)>0)
						<?php $sum_tot = 0;$taxes = 0;$total_offers = 0;$package_charges =0; ?>
						@foreach($resultData->Orderdetail as $key=>$value)
						<?php $taxes += $value->tax_amount;
						$package_charges += $value->package_charge;
						$total_offers += $value->offer_value;
						?>
						<table class="table text-nowrap">
							<thead class="bg-slate-300">
								<tr>
									<td colspan="1">Order #{!!($value->s_id)!!} [ <b>{!!$value->order_status!!}</b> ] </td>
									<td class="text-right">
										@if($value->order_type != "ticket")
										<b>Delivery Date/Time :</b> [ {!!  date('d M Y',strtotime($value->date))!!} - {!! $value->timeslotmanagement->time_slot !!} ]
										@endif
									</td><!-- <td></td> -->
									<td class="text-right">
										<b>Chef : </b><a href="javascript::void();" style="text-decoration: none; color: #FFF;">{!!$value->getVendorDetails->name!!}</a>
									</td>
									<td class="text-right">
										@if($value->status == 'pending' || $value->status == 'accepted_res' || $value->status == 'accepted_admin' || (\Auth::user()->role == 1 && ($value->status == 'food_ready' || $value->status == 'pickup_boy' || $value->status == 'reached_location' || $value->status == 'reached_restaurant' || $value->status == 'riding')))
										<div class="btn-group">
											<a href="javascript::void();" style="text-decoration: none; color: #fff;" class="label bg-teal dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Order status</a>
											<ul class="dropdown-menu dropdown-menu-right">
												@if($value->status == 'pending')
												<li><a href="javascript::void();" data-orderid="{!! $value->id !!}" style="text-decoration: none;" class="orderstatus order_accept"><span class="status-mark bg-success position-left"></span> Accept </a></li>
												<li><a href="javascript::void();" data-orderid="{!! $value->id !!}" style="text-decoration: none;" class="orderreject order_reject" data-toggle="modal" data-target="#myModal"><span class="status-mark bg-danger position-left"></span> Reject </a></li>
												@elseif($value->status == 'accepted_res' || $value->status == 'accepted_admin')
												<li><a href="javascript::void();" data-orderid="{!! $value->id !!}" style="text-decoration: none;" class="orderstatus order_food_ready"><span class="status-mark bg-success position-left"></span> Food ready </a></li>
												<li><a href="javascript::void();" data-orderid="{!! $value->id !!}" style="text-decoration: none;" class="orderreject order_reject" data-toggle="modal" data-target="#myModal"><span class="status-mark bg-danger position-left"></span> Reject </a></li>
												@elseif($value->status == 'food_ready' || $value->status == 'pickup_boy' || $value->status == 'reached_location' || $value->status == 'reached_restaurant' || $value->status == 'riding')
												<li><a href="javascript::void();" data-orderid="{!! $value->id !!}" style="text-decoration: none;" class="orderstatus order_delivered"><span class="status-mark bg-success position-left"></span> Delivered </a></li>
												@else
												<!-- <li><a href="javascript::void();" data-orderid="{!! $value->id !!}" style="text-decoration: none;" class="orderreject order_reject" data-toggle="modal" data-target="#myModal"><span class="status-mark bg-danger position-left"></span> Reject </a></li> -->
												@endif
											</ul>
										</div>
										@endif
				                   </td>
								</tr>
							</thead>
							<thead>
								<tr>
									<th class="col-md-6">Description</th>
									<th class="col-md-2 text-right">Rate</th>
									<th class="col-md-2 text-right">Quantity</th>
									<th class="col-md-2 text-right">Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$dataJson	= /*json_decode(*/$value->food_items/*,true)*/;
								//echo "<pre>";print_r($dataJson);exit;
								?>
								@if(count($dataJson)>0)
								<?php $tot = 0;?>
								@foreach($dataJson as $fKey=>$fVal)
								<?php
								$tot += $fVal['quantity']*(($fVal['fdiscount_price'] > 0) ? (int) $fVal['fdiscount_price'] : $fVal['fPrice']);
								?>
								<tr class="">
									<td>{!!$fVal['name']!!} {!!($fVal['unit'])!='' ? '- '.unitName($fVal['unit']) : ''!!}</td>
									@if(isset($fVal['discount']) && $fVal['discount'] != 0)
									<td class="col-md-2 text-right">
										<del style="color:red;">Rs. {!!number_format($fVal['fPrice'],2,'.','')!!}</del>
										<div>Rs. {!!number_format(((int)$fVal['fdiscount_price']),2,'.','')!!}</div>
									</td>
									@else
									<td class="col-md-2 text-right">Rs. {!!number_format($fVal['fPrice'],2,'.','')!!}</td>
									@endif
									<td class="col-md-2 text-right">{!!$fVal['quantity']!!}</td>
									@if(isset($fVal['discount']) && $fVal['discount'] != 0)
									<td class="col-md-2 text-right">
										<del style="color:red;">Rs. {!!number_format(($fVal['quantity']*$fVal['fPrice']),2,'.','')!!}</del>
										<div>Rs. {!!number_format(($fVal['quantity'] * (int) $fVal['fdiscount_price']),2,'.','')!!}</div>
									</td>
									@else
									<td class="col-md-2 text-right">Rs. {!!number_format(($fVal['quantity']*$fVal['fPrice']),2,'.','')!!}</td>
									@endif
								</tr>
								@if(count($fVal['addon'])>0)
								@foreach($fVal['addon'] as $aKey=>$aVal)
								
								<tr class="">
									<td><b>Addon : </b>{!!$aVal['name']!!}</td>
									<td class="col-md-2 text-right">Rs. {!!number_format($aVal['price'],2,'.','')!!}</td>
									<td class="col-md-2 text-right">{!!$aVal['quantity']!!}</td>
									<td class="col-md-2 text-right">Rs. {!!number_format(($aVal['quantity']*$aVal['price']),2,'.','')!!}</td>
								</tr>
								@endforeach
								@endif
								@endforeach
								@endif

								<?php $sum_tot +=$tot; ?>
								<tr>
									<td></td>
									<td></td>
									<th class="text-right">Total Food Cost :</th>
									
									<td class="text-right">Rs. {!!number_format($tot,2,'.','')!!}</td>
								</tr>
								<tr>
									<td colspan="2">@if(!($value->rider_info == null))<strong>Delivery Partner: </strong>{!! $value->rider_info->name !!} - {!! $value->rider_info->mobile_number !!} <span class="badge badge-info">{!! ucfirst($value->rider_info->third_part) !!}</span> @endif</td>
									@if($value->del_charge > 0)
									<th class="text-right">Delivery charge ( {!!$value->del_km!!} KM) :</th>
									<td class="text-right">Rs.  {!!number_format($value->del_charge,2,'.','')!!}</td>
									@endif
								</tr>
								@if($value->package_charge > 0)
								<tr>
									<td></td>
									<td></td>
									<th class="text-right">Package charge :</th>
									<td class="text-right">Rs. {!!number_format($value->package_charge,2,'.','')!!}</td>			
								</tr>
								@endif	
								@if($value->tax > 0)
								<tr>
									<td></td>
									<td></td>
									<th class="text-right">Tax ( {!!$value->tax!!} %) :</th>
									<td class="text-right">Rs. {!!number_format($value->tax_amount,2,'.','')!!}</td>
								</tr>
								@endif
								@if($value->offer_value > 0)
								<tr>
									<td></td>
									<td></td>
									<th class="text-right">Offer ( {!!$value->offer_percentage!!} %) :</th>
									<td class="text-right" style="color:red;">Rs. -{!!number_format($value->offer_value,2,'.','')!!}</td>
								</tr>
								@endif
								<tr>
									<td></td>
									<td></td>
									<th class="text-right">Total amount (paid by customer):</th>
									<td class="text-right">Rs. {!!number_format($value->grand_total,2,'.','')!!}</td>
								</tr>
								@if($value->commission > 0)
								<tr>
									<td></td>
									<td></td>
									<th class="text-right">Commission ( {!!$value->commission!!} %) :</th>
									<td class="text-right">Rs. {!!number_format($value->commission_amount,2,'.','')!!}</td>
								</tr>
								@endif
								{{-- @if($value->retries_count > 0)
								<tr>
									<td> Delivery Retry: <button class="btn btn-primary" onclick="window.location='{{ URL::to("admin/delivery_retry?orderid=".$value->id) }}'"> Click</button></td>
								</tr>
								@endif --}}
								@if($value->deliverylog_count > 0)
								<tr class="Delivery_status">
									<td> Delivery Status: <button class="btn btn-info" onclick="window.location='{{ URL::to("admin/delivery_log?orderid=".$value->id) }}'"> Click And View</button></td>
								</tr>
								@endif
								<tr style="border-top: 1px #c1c1c1 solid;border-bottom: 1px #c1c1c1 solid;">
									@if($value->restaurant->fssai != '' && $value
									->restaurant->fssai != null)

									<td><img style="height: 50px;width: auto;opacity: .7;" src="{{ asset('assets/front/img/fssai.png') }}">
									<p class="text-muted mt-2">Lic No.{!! $value->restaurant->fssai !!}</p></td>
									@else
									<td></td>
									@endif
									<td></td>
									@if($value->vendor_price > 0)
									<th class="text-right">Chef cost :</th>
									<td class="text-right">Rs. {!!number_format($value->vendor_price,2,'.','')!!}</td>
									@endif
								</tr>
							</tbody>
						</table>
						@endforeach
						@endif
						@endif
						<table class="table text-nowrap">
							<thead class="bg-slate-300">
								<tr class="bg-info">
									<td colspan="3"><b>Summary of payment statement</b></td>
									<td class="text-right">
									</td>
								</tr>
							</thead>
							<tbody>
								<tr class="bg">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Total Food Cost :</b></td>
									<td class="text-right">Rs. {!!number_format($resultData->total_food_amount,2,'.','')!!}</td>
								</tr>
								@if($resultData->del_charge > 0)
								<tr class="bg">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Delivery charge :</b></td>
									<td class="text-right">Rs. {!!number_format($resultData->del_charge,2,'.','')!!}</td>
								</tr>
								@endif
								@if($package_charges > 0)
								<tr class="bg">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Package charges :</b></td>
									<td class="text-right">Rs. {!!number_format($package_charges,2,'.','')!!}</td>
								</tr>
								@endif
								@if($taxes > 0)
								<tr class="bg">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Taxes :</b></td>
									<td class="text-right">Rs. {!!number_format($taxes,2,'.','')!!}</td>
								</tr>
								@endif
								@if($total_offers > 0)
								<tr class="bg">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Offer discount :</b></td>
									<td class="text-right" style="color:red">Rs. -{!!number_format($total_offers,2,'.','')!!}</td>
								</tr>
								@endif
								@if($resultData->used_wallet_amount > 0)
								<tr class="bg">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Used wallet amount :</b></td>
									<td class="text-right" style="color:red">Rs. -{!! $resultData->used_wallet_amount !!}</td>
								</tr> 
								@endif
								@if($resultData->vendor_price > 0)
								<tr class="bg">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Chef Cost :</b></td>
									<td class="text-right">Rs. {!!number_format($resultData->vendor_price,2,'.','')!!}</td>
								</tr>
								@endif
								@if($resultData->commission_amount > 0)
								<tr class="bg">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Commission :</b></td>
									<td class="text-right">Rs. {!!number_format($resultData->commission_amount,2,'.','')!!}</td>
								</tr>
								@endif
								<tr class="bg" style="border-top: 1px #c1c1c1 solid;">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Grand Total :</b></td>
									<td class="text-right">Rs.  {!!number_format($resultData->grand_total,2,'.','')!!}</td>
								</tr>
							</tbody>
						</table>
						<!-- Modal -->
						<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title" id="myModalLabel">Reject Reason</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									</div>
									<form id="rejectform">
										<!-- @csrf -->
										<div class="modal-body">
											<input type="hidden" name="order_id" id="order_id" value=""/>
											<input type="hidden" name="status" id="status" value="rejected_admin"/>
											<textarea name="reason" required="" rows="5" cols="45"></textarea>
										</div>
										<div class="modal-footer">
											<button type="submit" class="btn btn-primary">Save changes</button>
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /basic responsive configuration -->
	</div>
</div>
@endsection
<!-- /basic responsive configuration -->
@section('style')
<style type="text/css">
	@media print {
		body * {
			visibility: hidden;
		}
		#section-to-print, #section-to-print * {
			visibility: visible;
		}
		#section-to-print {
			position: absolute;
			left: 0;
			top: 0;
		}
		.Delivery_status {
			display: none;
		}
	}
	table tbody .pad td {padding: 8px 20px !important;font-size: 15px}
	.bg{background-color: #e0f7f4;}
</style>
@endsection
@section('script')
<script type="text/javascript">
	"use strict";
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$(document).on('click','.orderstatus',function (argument) {
		var curcls	= $(this).attr('class').split(' ')[1];
		if (curcls == 'order_reject') { var status = 'rejected_admin';} else if(curcls == 'order_food_ready') { var status = 'food_ready'; } else if(curcls == 'order_delivered') {var status = 'completed';} else { var status = 'accepted_admin'; }
		var orderid	= $(this).attr('data-orderid');
		$.ajax({
			type : 'PUT',
			url : base_url+'/admin/orderstatuschange',
			data : {status : status, order_id : orderid},
			success:function(data){
				$('.modal').modal('hide');
				toast(data.message, 'Success!', 'success');
				setTimeout(function(){location.reload()}, 1000);
			},
			error : function(err){ 
				$('.modal').modal('hide');
				var msg = err.responseJSON.message; 
				toast(msg, 'Error!', 'error');

			}
		});
	});

	$(document).on("click", ".orderreject", function () {
		var orderid	= $(this).attr('data-orderid');
		$(".modal-body #order_id").val(orderid);
	});
//save reason
$(document).on('submit','#rejectform',function(e){
	e.preventDefault();
	$.ajax({
		type : 'PUT',
		url : base_url+'/admin/orderstatuschange',
		data : $("#rejectform").serialize(),
		success : function(res){
			$('.modal').modal('hide');
			var msg = JSON.parse(JSON.stringify(res)); 
			$(".error-message-area").css("display","block");
			toast(msg.message, 'Error!', 'error');
			setTimeout(function(){location.reload()}, 1000);
		},
		error : function(err){
			toast('The reason must be atleast 10 characters.', 'Error!', 'error'); 
			$('.modal').modal('hide');
			var msg = err.responseJSON.message; 
			$(".error-content").css("background","#d4d4d4");
			$(".error-message-area").find('.error-msg').text(msg);
			$(".error-message-area").show(); 
		}
	});
});
</script>
@endsection
