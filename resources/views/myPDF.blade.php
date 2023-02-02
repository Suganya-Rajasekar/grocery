<!DOCTYPE html>
<html>
	<!-- <head>
	    <title>Emperica-Invoice</title>
		<meta charset="utf-8">
	  	<meta name="viewport" content="width=device-width, initial-scale=1">
	  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	  	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	</head> -->
	<style type="text/css">
		.p-0{
			padding: 0px;
		}
		.px-0{
			padding-left: 0px!important;
			padding-right: 0px!important;	
		}
	</style>
	<body>
		<div class="">
			<div class="">
				<div class="invoice-asw">
					<div class="row justify-content-between">
						<div class="col-md-6 col-lg-6 ">
							<img  src="{{ asset('assets/front/img/logo.svg') }}" alt="" style="width: 80px;">
						</div>
						<div class="col-lg-6 col-md-6 text-right">
							<div class="invoice-details top-1">
								<span class="">Invoice #{!!$resultData->Orderdetail[0]->m_id!!}</span>
								<ul class="p-0">
									<li>Ordered Date: <span class="">{!!date('M d, Y', strtotime($resultData->created_at))!!}</span></li>
									<li>Due date: <span class="">{!!date('M d, Y', strtotime($resultData->Orderdetail->max('date')))!!}</span></li>
								</ul>
							</div>
						</div>
					</div>

					<div class="top-2" >
						<div class="row">
							<div class="col-md-6 col-lg-6">
								<span class="">Invoice To:</span>
								<ul class="p-0">
									<li>{!!isset($resultData->getUserDetails) ? $resultData->getUserDetails['name'] : ''!!}</li>
									@if(\Auth::user()->role==1)
									<li>{!!isset($resultData->getUserDetails) ? $resultData->getUserDetails['email'] : ''!!}</li>
									@endif
									<li class="address">{!!isset($resultData->getUserAddress) ? $resultData->getUserAddress->display_address : ''!!}</li>
									@if(\Auth::user()->role==1)
									<li>{!!isset($resultData->getUserDetails) ? $resultData->getUserDetails['mobile'] : ''!!}</li>
									@endif
								</ul>
							</div>
							<div class="col-md-6 col-lg-6 text-right">
								<div class="invoice-details">
									<span class="">Invoice #</span>
									<ul class="">
										<li>Payment Type: <span class="text-semibold">{!!$resultData->payment_type!!}</span></li>
										<li>Ordered Date: <span class="text-semibold">{!!date('M d, Y', strtotime($resultData->created_at))!!}</span></li>
										<li>Due date: <span class="text-semibold">{!!date('M d, Y', strtotime($resultData->Orderdetail->max('date')))!!}</span></li>
										<li>Number of orders: <span class="text-semibold">{!!$resultData->order_count!!}</span></li>
									</ul>
									<br/>
								</div>
							</div>
						</div>
					</div>

					<div class="top-3 mt-n5">
						<div class="">
							<h4 class="">Order List</h4>
						</div>

						<div class="invoice table-responsive">
							<table class="table p-0">
								<tbody>
									@if($resultData)
										@if(count($resultData->Orderdetail)>0)
											<?php $sum_tot = 0;$taxes = 0;$package_charges = 0; ?>
											@foreach($resultData->Orderdetail as $key=>$value)
												<?php $taxes += $value->tax_amount;
												$package_charges += $value->package_charge;
												?>
													<tr class="" style="background-color:#90A4AE;border-top:2px; color: white;">
														<th class="text-left">
															<div class="">
																<b>Order #</b>
																<b>{!! ($value->s_id)!!} [{!! $value->order_status!!}]
																</b>
															</div>
														</th>
														{{-- <th></th> --}}
														<th class="text-left">
															@if($value->order_type != "ticket")
															<b class="">Delivery Date/Time :<br></b> [ {!!  date('d,M Y',strtotime($value->date))!!} - {!! $value->timeslotmanagement->time_slot !!} ]
															@endif
														</th>
														<th class="text-left" colspan="2">
															<div class="">
																<b>Chef : </b>
																{!!$value->restaurant->name!!}
															</div>
														</th>
													</tr>	
													<tr style="border-bottom: 1px solid grey;">
														<th scope="col" align="left">Description</th>
														<th scope="col" align="left">Rate</th>
														<th scope="col" align="left">Quantity</th>
														<th scope="col" align="center">Total</th>
													</tr>
												<?php
												$dataJson	= /*json_decode(*/$value->food_items/*,true)*/;
												?>
												@if(count($dataJson)>0)
													<?php $tot = 0; ?>
													@foreach($dataJson as $fKey=>$fVal)
														<?php
														$tot += $fVal['quantity']*$fVal['fPrice'];?>
														<tr class="">

															<td scope="col" align="left" style="text-align:left;" class="px-0">{!!$fVal['name']!!}{!!($fVal['unit'])!='' ? '-'.unitName($fVal['unit']) : ''!!}</td>
															@if(isset($fVal['discount']) && $fVal['discount'] != 0)
															<td class="col-md-2 text-left px-0" align="left" style="text-align:left;" >
																<del style="color:red;">Rs {!!number_format($fVal['price'],2,'.','')!!}</del>
																<div>Rs {!!number_format($fVal['fdiscount_price'],2,'.','')!!}</div>
															</td>
															@else
															<td class="col-md-2 text-left px-0" align="left" style="text-align:left;" >Rs {!!number_format($fVal['fPrice'],2,'.','')!!}</td>
															@endif
															<td class="col-md-2 text-left" align="left" style="text-align:left;" >{!!$fVal['quantity']!!}</td>
															@if(isset($fVal['discount']) && $fVal['discount'] != 0)
															<td class="col-md-2 text-right text-nowrap" style="text-align:right;" align="right">
																<del style="color:red;">Rs {!!number_format(($fVal['quantity']*$fVal['fPrice']),2,'.','')!!}</del>
																<div>Rs {!!number_format(($fVal['price']),2,'.','')!!}</div>
															</td>

															@else
															<td class="col-md-2 text-right text-nowrap" style="text-align:right;" align="right">Rs {!!number_format(($fVal['quantity']*$fVal['fPrice']),2,'.','')!!}</td>
															@endif
														</tr>
														@if(count($fVal['addon'])>0)
															@foreach($fVal['addon'] as $aKey=>$aVal)
																<tr class="" style="color: grey"> 								
																	<td scope="col" align="left" class="text-nowrap">{!!$aVal['name']!!}</td>
																	<td class="col-md-2 text-left px-0">Rs {!!number_format($aVal['price'],2,'.','')!!}</td>
																	<td class="col-md-2 text-left" align="left">{!!$aVal['quantity']!!}</td>
																	<td class="col-md-2 text-nowrap text-right">Rs {!!number_format(($aVal['quantity']*$aVal['price']),2,'.','')!!}</td>
																</tr>
															@endforeach
														@endif


													@endforeach
												@endif
												<?php
												 
												 $sum_tot +=$tot; ?>
												<tr style="border-top: 1px #c1c1c1 solid;">
													<th scope="col" colspan="3" class="text-right">Total Food Cost :</th>
													<td scope="col" class="text-right">Rs {!!number_format($value->total_food_amount,2,'.','')!!}</td>
												</tr>
												{{-- <tr>
													<td></td>
													<td></td>
													<td></td>
													<th align="right">Chef Cost :</th>
													<td align="right">Rs - {!!number_format($value->vendor_price,2,'.','')!!}</td>
												</tr>
												<tr>
													<td></td>
													<td></td>
													<td></td>
													<th align="right">Commission ( {!!$value->commission!!} %) :</th>
													<td align="right">Rs - {!!number_format($value->commission_amount,2,'.','')!!}</td>
												</tr> --}}
												{{-- <tr>
													<td>Boy Name: </td>
													<td></td>
												</tr> --}}
												@if($value->del_charge > 0)
												<tr>
													<th align="right" colspan="3">Delivery charge ( {!!$value->del_km!!} KM) :</th>
													<td align="right">Rs  {!!number_format($value->del_charge,2,'.','')!!}</td>
												</tr>
												@endif
												@if($value->package_charge > 0)
												<tr>
													<th align="right" colspan="3">Package charge :</th>
													<td align="right">Rs  {!!number_format($value->package_charge,2,'.','')!!}</td>
												</tr>
												@endif
												@if($taxes > 0)
												<tr>
													<th align="right" colspan="3">Tax ( {!!$value->tax!!} %) :</th>
													<td align="right">Rs  {!!number_format($value->tax_amount,2,'.','')!!}</td>
												</tr>
												@endif	
												<tr style="border-top:1px solid grey;">
												@if($value->restaurant->fssai)
												<td colspan="2">
													<img style="height: 50px;width: auto;opacity: .7;" src="{{ asset('assets/front/img/fssai.png') }}">
												<p class="text-muted mt-2" >Lic No:{!! $value->restaurant->fssai !!}</p>
												</td>
												@else
												<td colspan="2"></td>
												@endif
												<th scope="col" align="right">Total Amount:</th>
												<td scope="col" align="right">Rs  {!!number_format($value->grand_total,2,'.','')!!}</td>
												</tr>
											@endforeach
										@endif
									@endif
									<tr style="background-color: #00BCD4;color: white;">
										<td scope="col" colspan="4"><b>Summary of payment statement</b></td>
									</tr>
									<tr class="bg">
										<td scope="col" colspan="3" class="text-right px-0"><b>Total Food Cost :</b></td>
										<td scope="col" class="text-right">Rs {!!number_format($resultData->total_food_amount,2,'.','')!!}</td>
									</tr>
									{{-- <tr class="bg">
										<td ></td>
										<td ></td>
										<td class=""><b>Chef Cost :</b></td>
										<td class="">Rs - {!!number_format($resultData->vendor_price,2,'.','')!!}</td>
									</tr>
									<tr class="bg">
										<td ></td>
										<td ></td>
										<td class=""><b>Commission :</b></td>
										<td class="">Rs - {!!number_format($resultData->commission_amount,2,'.','')!!}</td>
									</tr> --}}
									@if($resultData->del_charge > 0)
									<tr class="bg">
										<td scope="col" colspan="3" class="text-right px-0"><b>Delivery charge :</b></td>
										<td scope="col" class="text-right">Rs  {!!number_format($resultData->del_charge,2,'.','')!!}</td>

									</tr>
									@endif
									@if($package_charges > 0)
									<tr class="bg">
										<td scope="col" colspan="3" class="text-right px-0"><b>Package charges :</b></td>
										<td scope="col" class="text-right">Rs  {!!number_format($package_charges,2,'.','')!!}</td>

									</tr>
									@endif	
									@if($taxes > 0)
									<tr class="bg">
										<td scope="col" colspan="3" class="text-right px-0"><b>Taxes :</b></td>
										<td scope="col" class="text-right">Rs  {!!number_format($taxes,2,'.','')!!}</td>

									</tr>
									@endif
									@if($resultData->Orderdetail[0]->offer_value > 0)
									<tr class="bg">
										<td scope="col" colspan="3" class="text-right px-0"><b>Offer discount :</b></td>
										<td scope="col" class="text-right" style="color:red;">Rs  -{!!number_format($resultData->Orderdetail[0]->offer_value,2,'.','')!!}</td>

									</tr>
									@endif
									@if($resultData->used_wallet_amount > 0)
									<tr class="bg">
										<td scope="col" colspan="3" class="text-right px-0"><b>Used wallet amount :</b></td>
										<td scope="col" class="text-right" style="color:red;">Rs  -{!! $resultData->used_wallet_amount !!}</td>

									</tr>
									@endif
									<tr class="bg" style="border-top: 1px #c1c1c1 solid;">
										<td scope="col" colspan="3" class="text-right px-0"><b>Grand Total :</b></td>
										<td scope="col" class="text-right">Rs  {!!number_format($resultData->grand_total,2,'.','')!!}</td>

									</tr>
								</tbody>
							</table>
						</div>
					</div>
					{{-- <?php exit();?> --}}
				</div>
			</div>
		</div>

		<!-- /basic responsive configuration -->
			
		<style type="text/css">

			.top-1 span, .top-2 span, .top-3 h4{
				font-size: 20px;
				font-weight: bold;
				color: #373b5a;
			}
			.top-1 li span, .top-2 li span{
				font-size: 16px;
				font-weight: 400;
			}
			.top-2 li.address{
				width: 300px;
			}
			table tbody td {
				padding: 8px 20px !important;
				font-size: 14px;
			}
			.bg{
				background-color: #e0f7f4;
			}
			body {
	            margin: 0px;
	        }
	        *{
	            font-family: Verdana, Arial, sans-serif;
	        }
	        ul,li {
	            text-decoration: none;
	            list-style-type: none;
	            margin: 0px;
	        }
	        table {
	            font-size: 14px;
	            border-collapse: collapse; 
	            width: 100%;
	        	padding:10px;
	        }
	        .table tr td, .table thead th, .table th{
	            font-size: 14px;
	            border: 0px solid transparent;
	            border: 0px solid transparent;
	        }
	        .table tr td{
	            padding-left: 0px; 
	            padding-right: 0px;
	        }
	        td{
	        	border:1px solid #ccc;
	        }
	        .text-right{
	        	text-align: right;
	        }
	        .text-left{
	        	text-align: left;
	        }
		</style>

		<footer>
			<p style="text-align:center;"><BR>Emperica Tech Solutions Pvt Ltd</p>
		</footer>
	</body>
</html>
{{-- <?php dd($resultData);?> --}}





