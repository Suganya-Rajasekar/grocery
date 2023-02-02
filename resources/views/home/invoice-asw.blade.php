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
	    <div class="container py-5	">
			<div class="">
				<div class="invoice-asw">
					<div class="row justify-content-between">
						<div class="col-md-6 col-lg-6 ">
							<img  src="{{ asset('assets/front/img/logo.svg') }}" alt="" style="width: 80px;">
						</div>
						<div class="col-lg-6 col-md-6 text-right">
							<div class="invoice-details top-1">
								<span class="">Invoice #</span>
								<ul class="p-0">
									<li>Ordered Date: <span class=""></span></li>
									<li>Due date: <span class=""></span></li>
								</ul>
							</div>
						</div>
					</div>

					<div class="top-2" >
						<div class="row">
							<div class="col-sm-6">
								<span class="">Invoice To:</span>
								<ul class="">
									<li>Name</li>
									<li>Email</li>
									<li>Address</li>
									<li>mobile</li>
								</ul>
							</div>
							<div class="col-sm-6 text-right">
								<div class="invoice-details">
									<span class="">Invoice #</span>
									<ul class="">
										<li>Payment Type: <span class=""></span></li>
										<li>Ordered Date: <span class=""></span></li>
										<li>Due date: <span class=""></span></li>
										<li>Number of orders: <span class=""></span></li>
									</ul>
									<br/>
								</div>
							</div>
						</div>
					</div>

					<div class="top-3">
						<div class="">
							<h4 class="">Order List</h4>
						</div>

						<div class="table-responsive invoice">
							<table class="table text-nowrap">
								<thead>
									<tr style="background-color: #ccc;border-top:2px;">
										<th class="col-md-6">Description</th>
										<th class="col-md-2 text-right">Rate</th>
										<th class="col-md-2 text-right">Quantity</th>
										<th class="col-md-2 text-right">Total</th>
									</tr>
								</thead>
								<tbody>
									<tr class="">
										<td colspan="1">Order # </td>
										<td class="">
											<b>Delivery Date/Time :</b> 
										</td><td></td>
										<td class="">
											<b>Chef : </b><span class="label">partner</span>
										</td>
									</tr>
									
									<tr class="">

										<td></td>
										<td class="col-md-2 text-right">Rs - </td>
										<td class="col-md-2 text-right"></td>
										<td class="col-md-2 text-right">Rs - </td>
									</tr>
									<tr class=""> 								
										<td></td>
										<td class="col-md-2 text-right">Rs - </td>
										<td class="col-md-2 text-right"></td>
										<td class="col-md-2 text-right">Rs - </td>
									</tr>

									<tr style="border-top: 1px #c1c1c1 solid;">
										<td></td>
										<td></td>
										<th class="text-right">Total Food Cost :</th>
										<td class="text-right">Rs - </td>
									</tr>
									{{-- <tr>
										<td></td>
										<td></td>
										<th class="text-right">Chef Cost :</th>
										<td class="text-right">Rs - </td>
									</tr>
									<tr>
										<td></td>
										<td></td>
										<th class="text-right">Commission (  %) :</th>
										<td class="text-right">Rs - </td>
									</tr>
									<tr>
										<td>Boy Name: </td>
										<td></td>
										<th class="text-right">Delivery charge (  KM) :</th>
										<td class="text-right">Rs - </td>
									</tr> --}}
									<tr style="background-color:#ccc;border-top:2px;">
										<td></td>
										<td></td>
										<th class="text-right">Total Amount :</th>
										<td class="text-right">Rs - </td>
									</tr>
									<tr style="background-color: lightskyblue;color: black;">
										<td colspan="3"><b>Summary of payment statement</b></td>
										<td class="text-right">

										</td>
									</tr>
									<tr class="bg">
										<td ></td>
										<td ></td>
										<td class="text-right"><b>Total Food Cost :</b></td>
										<td class="text-right">Rs - </td>
									</tr>
									{{-- <tr class="bg">
										<td ></td>
										<td ></td>
										<td class="text-right"><b>Chef Cost :</b></td>
										<td class="text-right">Rs - </td>
									</tr>
									<tr class="bg">
										<td ></td>
										<td ></td>
										<td class="text-right"><b>Commission :</b></td>
										<td class="text-right">Rs - </td>
									</tr> --}}
									<tr class="bg">
										<td ></td>
										<td ></td>
										<td class="text-right"><b>Delivery charge :</b></td>
										<td class="text-right">Rs - </td>

									</tr>
									<tr class="bg" style="border-top: 1px #c1c1c1 solid;">
										<td ></td>
										<td ></td>
										<td class="text-right"><b>Grand Total :</b></td>
										<td class="text-right">Rs - </td>

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
				font-size: 22px;
				font-weight: bold;
				color: #373b5a;
			}

			table tbody .pad td {
				padding: 8px 20px !important;
				font-size: 15px;
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
	        .table tr td span.label{
	        	padding: 4px;
	        	border: 1px solid green;
	        }
	        td{
	        	border:1px solid #ccc;
	        	padding:10px;
	        }
		</style>

		<footer>
			<p style="text-align:center;"><BR>Emperica tech Solutions pvt ltd</p>
		</footer>
	</body>
</html>



