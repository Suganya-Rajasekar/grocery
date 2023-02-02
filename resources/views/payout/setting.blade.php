@extends('layouts.backend.app')
@section('content')
@include('flash::message')
@php 
$pages=[];
$access = getUserModuleAccess('payout/'.\Request::segment(3));
@endphp
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Payout Settings</span></h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Payout Settings</li>
		</ul>
	</div>
</div>
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
    <div class="panel-body">
		<div class="ui segment attached rate-card">
		   <div class="ui section">
		      <div class="ui equal width grid">
		         <div class="row">
		            <div class="col-md-6">
		   				<h4 class="panel-title"><b>Commission</b></h4>
		               <table class="ui very basic collapsing single line table rate-card commissions-slabs">
		                  <tbody>
		                     <tr>
		                        <td>Commission (Emperica logistics)</td>
		                        <td>{!! $account->singlerestaurant->commission ?? 0 !!}%</td>
		                     </tr>
		                  </tbody>
		               </table>
		            </div>
		            <div class="col-md-6">
		   				<h4 class="panel-title"><b>POC Details</b></h4>
		               <table class="ui very basic collapsing single line table rate-card commissions-slabs">
		                  <tbody>
		                     <tr>
		                        <td>AM Email </td>
		                        <td>info@empericafoods.com</td>
		                     </tr>
		                     <tr>
		                        <td>AM Mobile</td>
		                        <td>+91 7678422802</td>
		                     </tr>
		                  </tbody>
		               </table>
		            </div>
		         </div>
		      </div>
		      <hr>
		   </div>
		</div>
		<div class="ui segment attached rate-card">
		   <div class="ui section">
		      <div class="ui equal width grid">
		         <div class="row">
		            <div class="col-md-6">
		   				<h4 class="panel-title"><b>Order Notifications</b></h4>
		               <table class="ui very basic collapsing single line table rate-card commissions-slabs">
		                  <tbody>
		                     <tr>
		                        <td>Chef Email</td>
		                        <td>{!! \Auth()->user()->email !!}</td>
		                     </tr>
		                     <tr>
		                        <td>Chef Mobile</td>
		                        <td>+91 {!! \Auth()->user()->mobile !!}</td>
		                     </tr>
		                  </tbody>
		               </table>
		            </div>
		            @if(!empty($account->rzaccount_active))
		            <div class="col-md-6">
		   				<h4 class="panel-title"><b>Contract and Payment Details</b></h4>
		               <table class="ui very basic collapsing single line table rate-card commissions-slabs">
		                  <tbody>
		                     <tr>
		                        <td>Beneficiary Name</td>
		                        <td>{!! ($account->rzaccount_active->name) !!}</td>
		                     </tr>
		                     <tr>
		                        <td>Account Number</td>
		                        <td>{!! ccMasking($account->rzaccount_active->account_number) !!}</td>
		                     </tr>
		                     <tr>
		                        <td>IFSC Code</td>
		                        <td>{!! ccMasking($account->rzaccount_active->ifsc_code) !!}</td>
		                     </tr>
		                  </tbody>
		               </table>
		            </div>
		            @else
		            <div class="col-md-6">
		   				<h4 class="panel-title"><b>Contract and Payment Details</b></h4>
		            	<p class="mt-20">No Bank Account Registered! Kindly Contact the Admin and register your Bank Account.</p>
		            </div>
		            @endif
		         </div>
		      </div>
		      <hr>
		   </div>
		</div>
    </div>
  </div>
<!-- /basic responsive configuration -->
@endsection
@section('script')
<script type="text/javascript">
	"use strict";	
	$(document).on('click','.edit_popup',function(){
		$("#id").val($(this).attr('data-id'));
		$("#status").val($(this).attr('data-status')).trigger('change');
		$("#modal_status").modal('show');
	})
	
</script>
@endsection
