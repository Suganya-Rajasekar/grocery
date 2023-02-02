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
							<li>{!!isset($resultData->getUserAddress) ? $resultData->getUserAddress['address'] : ''!!}</li>
							@if(\Auth::user()->role == 1)
							<li>{!!isset($resultData->getUserDetails) ? $resultData->getUserDetails['mobile'] : ''!!}</li>
							@endif
							@if(\Auth::user()->role == 1)
							<li><a href="mailto:{!!isset($resultData->getUserDetails) ? $resultData->getUserDetails['email'] : ''!!}">{!!isset($resultData->getUserDetails) ? $resultData->getUserDetails['email'] : ''!!}</a></li>
							@endif
						</ul>
					</div>
					<div class="col-md-6 col-lg-3 content-group">
						<span class="text-muted">Payment Details:</span>
						<ul class="list-condensed list-unstyled invoice-payment-details">
							<li><h5>Total Amount: <span class="text-right text-semibold">Rs {!! $resultData->grand_total !!}</span></h5></li>
							<li>Payment Type: <span class="text-semibold">{!! $resultData->payment_type !!}</span></li>
							<li>Number of orders: <span class="text-semibold">{!!$resultData->order_count!!}</span></li>
						</ul>
					</div>
				</div>
				<div class="panel panel-flat">
					<div class="table-responsive">
						<table class="table text-nowrap">
							<thead class="bg-slate-300">
								@if($resultData)
								@if(count($resultData->Orderdetail)>0)
								@foreach($resultData->Orderdetail as $key=>$value)
								<tr>
									<td colspan="1">Order #{!!($value->s_id)!!} [ <b>{!!$value->order_status!!}</b> ] </td>
									<td class="text-right">
										<b>Delivery Date/Time :</b> [ {!!  date('d M Y',strtotime($value->date))!!} - {!! $value->timeslotmanagement->time_slot !!} ]
									</td><td></td>
									<td class="text-right">
										<b>Chef : </b><a href="javascript::void();" style="text-decoration: none; color: #FFF;">{!!$value->getVendorDetails->name!!}</a>
									</td>
								</tr>
								@endforeach
								@endif
								@endif
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
								@if($resultData)
								@if(count($resultData->Orderdetail)>0)
								@foreach($resultData->Orderdetail as $key=>$value)
								<?php
								$dataJson	= /*json_decode(*/$value->food_items/*,true)*/;
								?>
								@if(count($dataJson)>0)
								@foreach($dataJson as $fKey=>$fVal)
								<tr class="">
									<td>{!!$fVal['name']!!} {!!($fVal['unit'])!='' ? '- '.$fVal['unit'] : ''!!}</td>
									<td class="col-md-2 text-right">Rs - {!!number_format($fVal['price'],2,'.','')!!}</td>
									<td class="col-md-2 text-right">{!!$fVal['quantity']!!}</td>
									<td class="col-md-2 text-right">Rs - {!!number_format(($fVal['quantity']*$fVal['price']),2,'.','')!!}</td>
								</tr>
								@if(count($fVal['addon'])>0)
								@foreach($fVal['addon'] as $aKey=>$aVal)
								<tr class="">
									<td><b>Addon : </b>{!!$aVal['name']!!}</td>
									<td class="col-md-2 text-right">Rs - {!!number_format($aVal['price'],2,'.','')!!}</td>
									<td class="col-md-2 text-right">{!!$aVal['quantity']!!}</td>
									<td class="col-md-2 text-right">Rs - {!!number_format(($aVal['quantity']*$aVal['price']),2,'.','')!!}</td>
								</tr>
								@endforeach
								@endif
								@endforeach
								@endif
								<tr>
									<td></td>
									<td></td>
									<th class="text-right">Total Food Cost :</th>
									<td class="text-right">Rs - {!!number_format($value->total_food_amount,2,'.','')!!}</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<th class="text-right">Chef cost :</th>
									<td class="text-right">Rs - {!!number_format($value->vendor_price,2,'.','')!!}</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<th class="text-right">Commission ( {!!$value->commission!!} %) :</th>
									<td class="text-right">Rs - {!!number_format($value->commission_amount,2,'.','')!!}</td>
								</tr>
								<tr>
									<td>Boy Name: </td>
									<td></td>
									<th class="text-right">Delivery charge ( {!!$value->del_km!!} KM) :</th>
									<td class="text-right">Rs - {!!number_format($value->del_charge,2,'.','')!!}</td>
								</tr>
								<tr style="border-top: 1px #c1c1c1 solid;border-bottom: 1px #c1c1c1 solid;">
									<td></td>
									<td></td>
									<th class="text-right">Total amount :</th>
									<td class="text-right">Rs - {!!number_format($value->grand_total,2,'.','')!!}</td>
								</tr>
								@endforeach
								@endif
								@endif
								<tr class="bg-info">
									<td colspan="3"><b>Summary of payment statement</b></td>
									<td class="text-right">
									</td>
								</tr>
								<tr class="bg">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Total Food Cost :</b></td>
									<td class="text-right">Rs - {!!number_format($resultData->total_food_amount,2,'.','')!!}</td>
								</tr>
								<tr class="bg">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Chef Cost :</b></td>
									<td class="text-right">Rs - {!!number_format($resultData->vendor_price,2,'.','')!!}</td>

								</tr>
								<tr class="bg">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Commission :</b></td>
									<td class="text-right">Rs - {!!number_format($resultData->commission_amount,2,'.','')!!}</td>

								</tr>
								<tr class="bg">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Delivery charge :</b></td>
									<td class="text-right">Rs - {!!number_format($resultData->del_charge,2,'.','')!!}</td>

								</tr>
								<tr class="bg" style="border-top: 1px #c1c1c1 solid;">
									<td ></td>
									<td ></td>
									<td class="text-right"><b>Grand Total :</b></td>
									<td class="text-right">Rs - {!!number_format($resultData->grand_total,2,'.','')!!}</td>
								</tr>
							</tbody>
						</table>
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
	}
	table tbody .pad td {padding: 8px 20px !important;font-size: 15px}
	.bg{background-color: #e0f7f4;}
</style>
@endsection
@section('script')
<script type="text/javascript">
	"use strict";
</script>
@endsection
