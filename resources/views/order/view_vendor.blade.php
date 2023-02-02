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
			<li class="active">Order detail <b>(#{!!$resultData->s_id!!})</b></li>
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
	$pdfUrl  = getRoleName().'/generatepdf/'.$resultData->id;
	$kotbill = imageConvert($resultData->id,'kot');
	@endphp
	<div class="panel panel-white">
		<div class="panel-heading">
			<h6 class="panel-title">Order detail<a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
			<div class="heading-elements">
				{{-- <button type="button" class="btn btn-info btn-xs heading-btn" onclick="window.location.href='{!!url($pdfUrl)!!}'" target="_blank"><i class="icon-file-check position-left"></i> Save</button> --}}
				<iframe srcdoc="{{ $kotbill }}" name="billframe" style="display:none;"></iframe>
				<button type="button" class="btn btn-info btn-xs heading-btn" onclick="a= frames['billframe'].print()"><i class="icon-printer position-left"></i> KOT</button>
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
							<span class="text-uppercase text-semibold">Invoice #{!!$resultData->s_id!!}</span>
							<ul class="list-condensed list-unstyled">
								<li>Ordered Date: <span class="text-semibold">{!!date('M d Y', strtotime($resultData->created_at))!!}</span></li>
								<li>Due date: <span class="text-semibold">{!! date('M d Y', strtotime($resultData->date)) !!}</span></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="row justify-content-between">
					<div class="col-lg-5 col-sm-6 content-group" style="">
						<span class="text-muted">Invoice To:</span>
						<ul class="list-condensed list-unstyled">
							<li>{!!isset($resultData->order->getUserDetails) ? $resultData->order->getUserDetails['name'] : ''!!}</li>
							{{-- <li>{!!isset($resultData->order->getUserAddress) ? $resultData->order->getUserAddress['address'] : ''!!}</li> --}}
						</ul>
					</div>
					<div class="col-md-6 col-lg-3 content-group">
						<span class="text-muted">Order Details:</span>
						<ul class="list-condensed list-unstyled invoice-payment-details">
							<li><h5>Total Earning: <span class="text-right text-semibold">&#8377; {!! number_format($resultData->vendor_price,2,'.','') !!}</span></h5></li>
							<li>Order Status: <span class="text-semibold">{!! $resultData->order_status !!}</span></li>
							{{-- <li>Date to deliver: <span class="text-semibold">{!!  date('d M Y',strtotime($resultData->date))!!}</span></li> --}}
							<li>Time to deliver: <span class="text-semibold">{{--  $resultData->timeslotmanagement->time_slot --}} {!! $resultData->timeslotmanagement->time_slot_chef !!}</span></li>
							@if(!($resultData->rider_info == null))
							<li>Delivery Partner:<span class="text-semibold"><i class="fa fa-user mr-2"></i>{!! $resultData->rider_info->name !!}<br> <i class="fa fa-phone "></i> {!! $resultData->rider_info->mobile_number !!} <br><span class="badge badge-info">{!! ucfirst($resultData->rider_info->third_part) !!}</span></span></li>
							 @endif
						</ul>
					</div>
				</div>
				<div class="panel panel-flat">
					<div class="table-responsive">
						<table class="table text-nowrap">
							<thead class="bg-slate-300">
								<tr>
									<th class="col-md-6">Description</th>
									<th class="col-md-2 text-right">Rate</th>
									<th class="col-md-2 text-right">Quantity</th>
									<th class="col-md-2 text-right">Total</th>
								</tr>
							</thead>
							<tbody>
								@if($resultData)
								<?php $dataJson=/*json_decode(*/$resultData->food_items/*,true)*/; ?>
								@if(count($dataJson)>0)
								@foreach($dataJson as $fKey=>$fVal)
								<tr class="">
									<td>{!!$fVal['name']!!} {{-- {!!($fVal['unit'])!='' ? '- '.$fVal['unit'] : ''!!} --}}</td>
									@if(isset($fVal['discount']) && $fVal['discount'] != 0)
									<td class="col-md-2 text-right">
										<del style="color:red;">Rs. {!!number_format($fVal['fPrice'],2,'.','')!!}</del>
										<div>Rs. {!!number_format(($fVal['fdiscount_price']),2,'.','')!!}</div>
									</td>
									@else
									<td class="col-md-2 text-right">Rs. {!!number_format($fVal['fPrice'],2,'.','')!!}</td>
									@endif
									<td class="col-md-2 text-right">{!!$fVal['quantity']!!}</td>
									@if(isset($fVal['discount']) && $fVal['discount'] != 0)
									<td class="col-md-2 text-right">
										<del style="color:red;">Rs. {!!number_format(($fVal['quantity']*$fVal['fPrice']),2,'.','')!!}</del>
										<div>Rs. {!!number_format(($fVal['price']),2,'.','')!!}</div>
									</td>
									@else
									<td class="col-md-2 text-right">Rs. {!!number_format(($fVal['quantity']*$fVal['fPrice']),2,'.','')!!}</td>
									@endif
								</tr>
								@if(count($fVal['addon'])>0)
								@foreach($fVal['addon'] as $aKey=>$aVal)
								<tr class=""> 								
									<td><b>Addon : </b>{!!$aVal['name']!!}</td>
									<td class="col-md-2 text-right">&#8377; {!!number_format($aVal['price'],2,'.','')!!}</td>
									<td class="col-md-2 text-right">{!!$aVal['quantity']!!}</td>
									<td class="col-md-2 text-right">&#8377; {!!number_format(($aVal['quantity']*$aVal['price']),2,'.','')!!}</td>
								</tr>
								@endforeach
								@endif
								@endforeach
								@endif
								<tr>
									<td></td>
									<td></td>
									<th class="text-right">Total Food Cost :</th>
									<td class="text-right">&#8377; {!!number_format($resultData->total_food_amount,2,'.','')!!}</td>
								</tr>
								@if($resultData->tax_amount > 0)
								<tr>
									<td></td>
									<td></td>
									<th class="text-right">Tax ( {!!$resultData->tax!!} %) :</th>
									<td class="text-right">&#8377; {!!number_format($resultData->tax_amount,2,'.','')!!}</td>
								</tr>
								@endif
								@if($resultData->offer_value > 0)
								<tr>
									<td></td>
									<td></td>
									<th class="text-right">Offer ( {!!$resultData->offer_percentage!!} %) :</th>
									<td class="text-right" style="color:red;">- &#8377; {!!number_format($resultData->offer_value,2,'.','')!!}</td>
								</tr>
								@endif
								@if($resultData->commission_amount > 0)
								<tr>
									<td></td>
									<td></td>
									<th class="text-right">Commission ( {!!$resultData->commission!!} %) :</th>
									<td class="text-right">&#8377; {!!number_format($resultData->commission_amount,2,'.','')!!}</td>
								</tr>
								@endif
								<tr style="border-top: 1px #cacaca solid;border-bottom: 1px #cacaca solid">
									<td></td>
									<td></td>
									<th class="text-right">Total earned Amount :</th>
									<td class="text-right">&#8377; {!!number_format($resultData->vendor_price,2,'.','')!!}</td>
								</tr>
								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /basic responsive configuration -->
@endsection
@section('style')
<style type="text/css">
	@media print {
		body * {
			visibility: hidden;
		}
		#section-to-print, #section-to-print * {
			visibility: visible;
			width: 100%;

		}
		#section-to-print {
			position: absolute;
			left: 0;
			top: 0;
		}

	}
	table tbody td {padding: 8px 20px !important;font-size: 15px}
	.bg{background-color: #e0f7f4;}
</style>
@endsection
@section('script')
<script type="text/javascript">
	"use strict";
</script>
@endsection