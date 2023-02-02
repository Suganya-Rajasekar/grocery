<!DOCTYPE html>
<html>
	<head>
	    <title>Emperica-Invoice</title>
		<meta charset="utf-8">
	  	<meta name="viewport" content="width=device-width, initial-scale=1">
	  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	  	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	</head>
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
								<span class="">Invoice #{!!$resultData->m_id!!}</span>
								<ul class="p-0">
									<li>Ordered Date: <span class="">{!!date('M d, Y', strtotime($resultData->created_at))!!}</span></li>
									<li>Due date: <span class="">{!!date('M d, Y', strtotime($resultData->max('date')))!!}</span></li>
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
									<li class="address">{!!isset($resultData->getUserAddress) ? $resultData->getUserAddress['address'] : ''!!}</li>
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
										<li>Due date: <span class="text-semibold">{!!date('M d, Y', strtotime($resultData->max('date')))!!}</span></li>
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
							<table class="table">
								<tbody>
									@if($resultData)
										@if(!empty($resultData))
												<thead class="" style="background-color:#90A4AE;border-top:2px; color: white;">
													<tr class="" >
														<th colspan="" class="" style="">
															<div class="">
																<b>Order #</b>
																<b>{!! ($resultData->s_id)!!} [{!! $resultData->order_status!!}]
																</b>
															</div>
														</th>
														<th></th>
														<th colspan="2" class="text-right">
															<b class="">Delivery Date/Time :<br></b> [ {!!  date('d,M Y',strtotime($resultData->date))!!} - {!! $resultData->timeslotmanagement->time_slot !!} ]
														</th>
														<th class="text-right" >
															<div class="">
																<b>Chef : </b>
																{!!$resultData->restaurant->name!!}
															</div>
														</th>
													</tr>
												</thead>
												<thead>	
													<tr style="border-bottom: 1px solid grey;">
														<th scope="col"colspan="2" class="">Description</th>
														<th scope="col" class=" text-right">Rate</th>
														<th scope="col" class=" text-right">Quantity</th>
														<th scope="col" class=" text-right">Total</th>
													</tr>
												</thead>
												<?php
												$dataJson	= /*json_decode(*/$resultData->food_items/*,true)*/;
												?>
												@if(count($dataJson)>0)
													@foreach($dataJson as $fKey=>$fVal)
														<tr class="">

															<td scope="col"colspan="2" class="text-nowrap">{!!$fVal['name']!!}{!!($fVal['unit'])!='' ? '-'.$fVal['unit'] : ''!!}</td>
															<td class="col-md-2 text-right">Rs - {!!number_format($fVal['price'],2,'.','')!!}</td>
															<td class="col-md-2 text-right">{!!$fVal['quantity']!!}</td>
															<td class="col-md-2 text-right text-nowrap">Rs - {!!number_format(($fVal['quantity']*$fVal['price']),2,'.','')!!}</td>
														</tr>
														@if(count($fVal['addon'])>0)
															@foreach($fVal['addon'] as $aKey=>$aVal)
																<tr class="" style="color: grey"> 								
																	<td scope="col" colspan="2" class="text-nowrap">{!!$aVal['name']!!}</td>
																	<td class="col-md-2 text-right">Rs - {!!number_format($aVal['price'],2,'.','')!!}</td>
																	<td class="col-md-2 text-right">{!!$aVal['quantity']!!}</td>
																	<td class="col-md-2 text-nowrap text-right">Rs - {!!number_format(($aVal['quantity']*$aVal['price']),2,'.','')!!}</td>
																</tr>
															@endforeach
														@endif


													@endforeach
												@endif
												{{-- <tr style="border-top: 1px #c1c1c1 solid;">
													<th scope="col" colspan="4" class="text-right">Total Food Cost :</th>
													<td scope="col" class="text-right">Rs - {!!number_format($value->total_food_amount,2,'.','')!!}</td>
												</tr> --}}
												{{-- <tr>
													<td></td>
													<td></td>
													<th class="text-right">Chef Cost :</th>
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
												</tr> --}}
												<tr style="border-top:1px solid grey;">
													<th scope="col" colspan="4" class="text-right">Total Amount :</th>
													<td scope="col" class="text-right">Rs - {!!number_format($resultData->grand_total,2,'.','')!!}</td>
												</tr>
											
										@endif
									@endif
									<tr style="background-color: #00BCD4;color: white;">
										<td scope="col" colspan="5"><b>Summary of payment statement</b></td>
									</tr>
									{{-- <tr class="bg">
										<td scope="col" colspan="4" class="text-right"><b>Total Food Cost :</b></td>
										<td scope="col" class="text-right">Rs - {!!number_format($resultData->total_food_amount,2,'.','')!!}</td>
									</tr> --}}
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
									<tr class="bg">
										<td scope="col" colspan="4" class="text-right"><b>Delivery charge :</b></td>
										<td scope="col" class="text-right">Rs - {!!number_format($resultData->del_charge,2,'.','')!!}</td>

									</tr>
									<tr class="bg" style="border-top: 1px #c1c1c1 solid;">
										<td scope="col" colspan="4" class="text-right"><b>Grand Total :</b></td>
										<td scope="col" class="text-right">Rs - {!!number_format($resultData->grand_total,2,'.','')!!}</td>

									</tr>
								</tbody>
							</table>
						</div>
					</div>
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
		</style>

		<footer>
			<p style="text-align:center;"><BR>Emperica tech Solutions pvt ltd</p>
		</footer>
	</body>
</html>






