@extends('layouts.backend.app')
@php $access = getUserModuleAccess('usermanages');
@endphp 
@section('content')
@include('flash::message')
<div class="row">
	<!-- <div class="col-lg-3">
		<div class="panel bg-teal-400">
			<div class="panel-body">
				<div class="heading-elements">
					<span class="heading-text badge bg-teal-800"><i class="fa fa-money"></i></span>
				</div>
				<h3 class="no-margin">0</h3>
				{{ __('Total Earnings') }}
			</div>

			<div class="container-fluid">
			</div>
		</div>
	</div>

	<div class="col-lg-3">
		<div class="panel bg-pink-400">
			<div class="panel-body">
				<div class="heading-elements">
					<span class="heading-text badge bg-teal-800"><i class="fa fa-money"></i></span>
				</div>
				<h3 class="no-margin">0</h3>
				{{ __('Total Payouts') }}
			</div>
		</div>
	</div> -->
	@if(getRoleName()=='admin')
	<!-- <div class="col-lg-3">
		<div class="panel bg-blue-400">
			<div class="panel-body">				
				<div class="heading-elements">
					<span class="heading-text badge bg-teal-800"><i class="fa fa-spoon"></i></span>
				</div>
				<h3 class="no-margin">454</h3>
				{{ __('Total Chef') }}
			</div>
		</div>
	</div>

	<div class="col-lg-3">
		<div class="panel bg-success-400">
			<div class="panel-body">
				<div class="heading-elements">
					<span class="heading-text badge bg-teal-800"><i class="fa fa-users"></i></span>
				</div>
				<h3 class="no-margin">43</h3>
				{{ __('Total Customers') }}
			</div>
		</div>
	</div> -->
	@endif
</div>
<?php $requestResturent=[];?>
<div class="row dashboard-asw">
	@if(getRoleName() == 'admin')
	{{-- <div class="col-lg-6">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title">{{ __('New chef Request') }}</h3>
				<div class="heading-elements">
					<a href="{{ url('/admin/resturents/requests') }}"><span class="label bg-success heading-text">Count - {!!count($requestResturent) !!}</span></a>
				</div>
			</div>
			<div class="table-responsive"></div>
			<div class="table-responsive">
				<table class="table text-nowrap table-bordered">
					<thead>
						<tr>
							<th>Chef</th>
							<th class="col-md-2">Time</th>
							<th class="col-md-2">Status</th>
						</tr>
					</thead>
					<tbody>
						@foreach($requestResturent as $row)
						<tr>
							<td>
								<div class="media-left media-middle">
									<a href="#"><img src="{{ asset($row->avatar) }}" class="img-circle img-xs" alt=""></a>
								</div>
								<div class="media-left">
									<div class=""><a href="{{ url('/admin/user',$row->id) }}" class="text-default text-semibold">{{ $row->business_name }}</a></div>
									<div class="text-muted text-size-small">
										<span class="status-mark border-blue position-left"></span>
										{{ $row->email }}
									</div>
								</div>
							</td>
							<td><span class="text-muted">{{ $row->created_at->diffforHumans() }}</span></td>
							<td>@if(empty($row->email_verified_at))
								<span class="label bg-danger">{{  __('Not Verified') }}</span>
								@else
								<span class="label bg-blue">{{ __('Verified') }}</span>
								@endif
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div> --}}
	@endif
	<?php $requestRider=[];?>
	<div class="col-lg-6" style="display: none;">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title">{{ __('New Rider Request') }}</h3>
				<div class="heading-elements">
					<a href="{{ url('/admin/rider/requests') }}"><span class="label bg-success heading-text">Count - {!!count($requestRider) !!}</span></a>
				</div>
			</div>
			<div class="table-responsive"></div>
			<div class="table-responsive">
				<table class="table text-nowrap table-bordered">
					<thead>
						<tr>
							<th>Rider</th>
							<th class="col-md-2">Time</th>
							<th class="col-md-2">Status</th>
						</tr>
					</thead>
					<tbody>
						@foreach($requestRider as $row)
						<tr>
							<td>
								<div class="media-left media-middle">
									<a href="#"><img src="{{ asset($row->avatar) }}" class="img-circle img-xs" alt=""></a>
								</div>
								<div class="media-left">
									<div class=""><a href="{{ url('/admin/user',$row->id) }}" class="text-default text-semibold">{{ $row->name }}</a></div>
									<div class="text-muted text-size-small">
										<span class="status-mark border-blue position-left"></span>
										{{ $row->email }}
									</div>
								</div>
							</td>
							<td><span class="text-muted">{{ $row->created_at->diffforHumans() }}</span></td>
							<td>@if(empty($row->email_verified_at))
								<span class="label bg-danger">{{  __('Not Verified') }}</span>
								@else
								<span class="label bg-blue">{{ __('Verified') }}</span>
								@endif
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php $newOrders=[];?>
	{{-- <div class="col-lg-6">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title">{{ __('New Order') }}</h3>
				<div class="heading-elements">
					<a href="{{ url('/admin/order') }}"><span class="label bg-success heading-text">Count - {!!count($newOrders) !!}</span></a>
				</div>
			</div>
			<div class="table-responsive"></div>
			<div class="table-responsive">
				<table class="table text-nowrap table-bordered">
					<thead>
						<tr>
							<th>{{ __('Order Type') }}</th>
							<th>{{ __('Amount') }}</th>
							<th>{{ __('Chef Name') }}</th>
							<th>{{ __('Action') }}</th>

						</tr>
					</thead>
					<tbody>
						<?php $newOrders=[];?>
						@foreach($newOrders as $row)                       
						<tr>
							<td>
								@if($row->order_type == 1)
								<span class="badge badge-success">{{ __('Home Delivery') }}</span>
								@else
								<span class="badge badge-success">{{ __('Pickup') }}</span>
								@endif

							</td>
							<td>{{ number_format($row->grand_total) }}</td>
							<td>
								<a href="{{ url('/admin/user',$row->vendor_id) }}" class="font-weight-600"><img src="{{ asset($row->vendor->avatar) }}" alt="" width="30" class="rounded-circle mr-1"> {{ $row->vendor->business_name }}</a>
							</td>
							<td>
								<a href="{{ url('/admin/order',$row->id) }}" class="btn btn-primary btn-action mr-1"><i class="fa fa-eye"></i></a>

							</td>
						</tr>
						@endforeach

					</tbody>
				</table>
			</div>
		</div>
	</div> --}}
	@if (getRoleName() == 'admin' || getRoleName() == 'vendor')
	<div class="col-lg-6">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title"><b>Planned Orders</b><a class="heading-elements-toggle"><i class="icon-more"></i></a></h3>
			</div>
			<div class="table table-hover table-striped table-bordered"><div id="PlannedOrders"></div></div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title"><b>Orders Count</b><a class="heading-elements-toggle"><i class="icon-more"></i></a></h3>
			</div>
			<div class="table table-hover table-striped table-bordered"><div id="OrdersCount"></div></div>
		</div>
	</div>
	@endif
	@if (getRoleName() == 'vendor')
	<div class="col-lg-6">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title"><b>Revenue Data (Daily)</b><a class="heading-elements-toggle"><i class="icon-more"></i></a></h3>
			</div>
			<div class="table table-hover table-striped table-bordered"><div id="RevenueGraph"></div></div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title"><b>Revenue Data (Weekly)</b><a class="heading-elements-toggle"><i class="icon-more"></i></a></h3>
			</div>
			<div class="table table-hover table-striped table-bordered"><div id="WeekRevenueGraph"></div></div>
		</div>
	</div>
	@endif
	@if (getRoleName() == 'admin')
	<div class="col-lg-6">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title"><b>Nearby Orders</b><a class="heading-elements-toggle"><i class="icon-more"></i></a></h3>
			</div>
			<div class="table table-hover table-striped table-bordered"><div id="OrdersNearby"></div></div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title"><b>Total chefs</b><a class="heading-elements-toggle"><i class="icon-more"></i></a></h3>
			</div>
			<div class="table table-hover table-striped table-bordered"><div id="Chefs"></div></div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title"><b>Chefs in each category</b><a class="heading-elements-toggle"><i class="icon-more"></i></a></h3>
			</div>
			<div class="table table-hover table-striped table-bordered"><div id="ChefsinCategory"></div></div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title"><b>Chefs in each cuisine</b><a class="heading-elements-toggle"><i class="icon-more"></i></a></h3>
			</div>
			<div class="table table-hover table-striped table-bordered"><div id="ChefsinCuisine"></div></div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title"><b>Top performing chefs of the day</b><a class="heading-elements-toggle"><i class="icon-more"></i></a></h3>
			</div>
			<div class="table table-hover table-striped table-bordered"><div id="TopPerformingDay"></div></div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title"><b>Top performing chefs of the month</b><a class="heading-elements-toggle"><i class="icon-more"></i></a></h3>
			</div>
			<div class="table table-hover table-striped table-bordered"><div id="TopPerformingMonth"></div></div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title"><b>Top performing chefs of the year</b><a class="heading-elements-toggle"><i class="icon-more"></i></a></h3>
			</div>
			<div class="table table-hover table-striped table-bordered"><div id="TopPerformingYear"></div></div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title"><b>Top performing cities of the day</b><a class="heading-elements-toggle"><i class="icon-more"></i></a></h3>
			</div>
			<div class="table table-hover table-striped table-bordered"><div id="TopPerformingCityDay"></div></div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title"><b>Top performing cities of the month</b><a class="heading-elements-toggle"><i class="icon-more"></i></a></h3>
			</div>
			<div class="table table-hover table-striped table-bordered"><div id="TopPerformingCityMonth"></div></div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h3 class="panel-title"><b>Top performing cities of the year</b><a class="heading-elements-toggle"><i class="icon-more"></i></a></h3>
			</div>
			<div class="table table-hover table-striped table-bordered"><div id="TopPerformingCityYear"></div></div>
		</div>
	</div>
		<!-- <div class="col-lg-6">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h3 class="panel-title"><b>Top performing</b><a class="heading-elements-toggle"><i class="icon-more"></i></a></h3>
				</div>
					<ul>
						<li class="list-style-type mb-4">
							<div class="tick float-left">
								<i class="fa fa-check-circle"></i>							
							</div>
							<div class="tcontent">
								<span class="ml-3">Dashboard design</span>
								<span class="float-right mr-3">24 May</span>
							</div>
						</li>
						<li class="list-style-type mb-4">
							<div class="tick float-left">
								<i class="fa fa-times-circle"></i>							
							</div>
							<div class="tcontent">
								<span class="ml-3">Dashboard design</span>
								<span class="float-right mr-3">24 May</span>
							</div>
						</li>
						<li class="list-style-type mb-4">
							<div class="tick float-left">
								<i class="fa fa-check-circle"></i>							
							</div>
							<div class="tcontent">
								<span class="ml-3">Dashboard design</span>
								<span class="float-right mr-3">24 May</span>
							</div>
						</li>
						<li class="list-style-type mb-4">
							<div class="tick float-left">
								<i class="fa fa-check-circle"></i>							
							</div>
							<div class="tcontent">
								<span class="ml-3">Dashboard design</span>
								<span class="float-right mr-3">24 May</span>
							</div>
						</li>
						
					</ul>
			</div>
		</div> -->
	@endif
</div>
@if(getRoleName() == 'vendor' || getRoleName() == 'admin')
<script type="text/javascript">
	$(document).ready(function(){
		var d		= new Date();
		var month	= d.getMonth()+1;
		var day		= d.getDate();
		var output	= d.getFullYear() + '/' +(month<10 ? '0' : '') + month + '/' +(day<10 ? '0' : '') + day;
		<?php if (getRoleName() == 'admin') { ?>
			var URL = base_url+"/admin/pieChart";
		<?php } else { ?>
			var URL = base_url+"/vendor/pieChart";
		<?php } ?>
		$.ajax({
			type : 'GET',
			dataType : 'json',
			url:URL,
			success: function (data) {
				<?php if (getRoleName() == 'admin' || getRoleName() == 'vendor') { ?>
				Highcharts.chart('PlannedOrders', {
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false,
						type: 'pie'
					},
					title: {
						text: ''
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					},
					accessibility: {
						point: {
							valueSuffix: '%'
						}
					},
					credits: {
						enabled: false
					},
					plotOptions: {
						pie: {
							size : 200,
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: false
							},
							showInLegend: true
						}
					},
					series: [{
						name: 'Orders',
						colorByPoint: true,
						data: data.PlannedOrders
					}],
					exporting: {
						filename: 'PlannedOrders'+output,
						showTable: true
					}
				});

				Highcharts.chart('OrdersCount', {
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false,
						type: 'pie'
					},
					title: {
						text: ''
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					},
					accessibility: {
						point: {
							valueSuffix: '%'
						}
					},
					credits: {
						enabled: false
					},
					plotOptions: {
						pie: {
							size : 200,
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: false
							},
							showInLegend: true
						}
					},
					series: [{
						name: 'Orders',
						colorByPoint: true,
						data: data.OrdersCount
					}],
					exporting: {
						filename: 'OrdersCount'+output,
						showTable: true
					}
				});

				<?php } ?>
				<?php if (getRoleName() == 'vendor') { ?>

				Highcharts.chart('RevenueGraph', {
					chart: {
						type: 'column'
					},
					title: {
						text: 'Your Order revenue for last 7 days'
					},
					xAxis: {
						categories: data.RevenueCategory.category
					},
					credits: {
						enabled: false
					},
					series: [{
						name: 'Net receivable',
						data: data.RevenueCategory.netamount
					}, {
						name: 'Deductions',
						data: data.RevenueCategory.deductions
					}/*, {
						name: 'Joe',
						data: [3, 4, 4, -2, 5]
					}*/]
				});
				Highcharts.chart('WeekRevenueGraph', {
					chart: {
						type: 'column'
					},
					title: {
						text: 'Your Order revenue for last 7 weeks'
					},
					xAxis: {
						categories: data.RevenueCategoryWeek.category
					},
					credits: {
						enabled: false
					},
					series: [{
						name: 'Net receivable',
						data: data.RevenueCategoryWeek.netamount
					}, {
						name: 'Deductions',
						data: data.RevenueCategoryWeek.deductions
					}/*, {
						name: 'Joe',
						data: [3, 4, 4, -2, 5]
					}*/]
				});
				<?php } ?>	
				<?php if (getRoleName() == 'admin') { ?>
				Highcharts.chart('OrdersNearby', {
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false,
						type: 'pie'
					},
					title: {
						text: ''
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					},
					accessibility: {
						point: {
							valueSuffix: '%'
						}
					},
					credits: {
						enabled: false
					},
					plotOptions: {
						pie: {
							size : 200,
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: false
							},
							showInLegend: true
						}
					},
					series: [{
						name: 'Nearby Orders',
						colorByPoint: true,
						data: data.OrdersNearby
					}],
					exporting: {
						filename: 'OrdersNearby'+output,
						showTable: true
					}
				});

				Highcharts.chart('Chefs', {
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false,
						type: 'pie'
					},
					title: {
						text: ''
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					},
					accessibility: {
						point: {
							valueSuffix: '%'
						}
					},
					credits: {
						enabled: false
					},
					plotOptions: {
						pie: {
							size : 200,
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: false
							},
							showInLegend: true
						}
					},
					series: [{
						name: 'chefs live',
						colorByPoint: true,
						data: data.Chefs
					}],
					exporting: {
						filename: 'TotalChefs'+output,
						showTable: true
					}
				});

				Highcharts.chart('ChefsinCategory', {
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false,
						type: 'pie'
					},
					title: {
						text: ''
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					},
					accessibility: {
						point: {
							valueSuffix: '%'
						}
					},
					credits: {
						enabled: false
					},
					plotOptions: {
						pie: {
							size : 200,
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: false
							},
							showInLegend: true
						}
					},
					series: [{
						name: 'Chefs in category',
						colorByPoint: true,
						data: data.ChefsinCategory
					}],
					exporting: {
						filename: 'ChefsinCategory'+output,
						showTable: true
					}
				});

				Highcharts.chart('ChefsinCuisine', {
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false,
						type: 'pie'
					},
					title: {
						text: ''
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					},
					accessibility: {
						point: {
							valueSuffix: '%'
						}
					},
					credits: {
						enabled: false
					},
					plotOptions: {
						pie: {
							size : 200,
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: false
							},
							showInLegend: true
						}
					},
					series: [{
						name: 'Chefs in cuisine',
						colorByPoint: true,
						data: data.ChefsinCuisine
					}],
					exporting: {
						filename: 'ChefsinCuisine'+output,
						showTable: true
					}
				});

				Highcharts.chart('TopPerformingDay', {
					chart: {
						type: 'variablepie'
					},
					title: {
						text: ''
					},
					credits: {
						enabled: false
					},
					tooltip: {
						headerFormat: '',
						pointFormat: '<span style="color:{point.color}">\u25CF</span> <b> {point.name}</b><br/>' +
						'Rank (Ranked By Orders): <b>{point.rank}</b><br/>' +
						'Orders (Completed Order Count): <b>{point.z}</b><br/>'
					},
					series: [{
						keys: ['Rank', 'Orders'],
						minPointSize: 3,
						maxPointSize: 100,
						innerSize: '10%',
						zMin: 0,
						name: 'countries',
						data: data.TopPerformingDay
					}],
					exporting: {
						filename: 'TopPerformingChefinDay'+output,
						showTable: true,
						csv: {
							columnHeaderFormatter: function(item, key) {
								if (!item || item instanceof Highcharts.Axis) {
									return 'Chefs'
								} else {
									return key;
								}
							}
						}
					}
				});

				Highcharts.chart('TopPerformingMonth', {
					chart: {
						type: 'variablepie'
					},
					title: {
						text: 'Top performing chefs of the month'
					},
					credits: {
						enabled: false
					},
					tooltip: {
						headerFormat: '',
						pointFormat: '<span style="color:{point.color}">\u25CF</span> <b> {point.name}</b><br/>' +
						'Rank (Ranked By Orders): <b>{point.Rank}</b><br/>' +
						'Orders (Completed Order Count): <b>{point.z}</b><br/>'
					},
					series: [{
						keys: ['Rank', 'Orders'],
						minPointSize: 3,
						maxPointSize: 100,
						innerSize: '10%',
						zMin: 0,
						name: 'countries',
						data: data.TopPerformingMonth
					}],
					exporting: {
						filename: 'TopPerformingChefinMonth'+output,
						showTable: true,
						csv: {
							columnHeaderFormatter: function(item, key) {
								if (!item || item instanceof Highcharts.Axis) {
									return 'Chefs'
								} else {
									return key;
								}
							}
						}
					}
				});

				Highcharts.chart('TopPerformingYear', {
					chart: {
						type: 'variablepie'
					},
					title: {
						text: 'Top performing chefs of the year'
					},
					credits: {
						enabled: false
					},
					plotOptions: {
						pie: {
							size : 80,
						}
					},
					tooltip: {
						headerFormat: '',
						pointFormat: '<span style="color:{point.color}">\u25CF</span> <b> {point.name}</b><br/>' +
						'Rank (Ranked By Orders): <b>{point.rank}</b><br/>' +
						'Orders (Completed Order Count): <b>{point.z}</b><br/>'
					},
					series: [{
						keys: ['Rank', 'Orders'],
						minPointSize: 3,
						maxPointSize: 100,
						innerSize: '10%',
						zMin: 0,
						name: 'countries',
						data: data.TopPerformingYear
					}],
					exporting: {
						filename: 'TopPerformingChefinYear'+output,
						showTable: true,
						csv: {
							columnHeaderFormatter: function(item, key) {
								if (!item || item instanceof Highcharts.Axis) {
									return 'Chefs'
								} else {
									return key;
								}
							}
						}
					}
				});

				Highcharts.chart('TopPerformingCityDay', {
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: 0,
						plotShadow: false
					},
					title: {
						text: 'Top performing cities of the day',
						align: 'center',
						// verticalAlign: 'middle',
						y: 60
					},
					credits: {
						enabled: false
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					},
					accessibility: {
						point: {
							valueSuffix: '%'
						}
					},
					plotOptions: {
						pie: {
							dataLabels: {
								enabled: true,
								distance: -50,
								style: {
									fontWeight: 'bold',
									color: 'white'
								}
							},
							startAngle: -90,
							endAngle: 90,
							center: ['50%', '75%'],
							size: 300
						}
					},
					series: [{
						type: 'pie',
						name: 'Browser share',
						innerSize: '40%',
						data: data.TopPerformingCityDay
					}],
					exporting: {
						filename: 'TopPerformingCityinDay'+output,
						showTable: true
					}
				});

				Highcharts.chart('TopPerformingCityMonth', {
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: 0,
						plotShadow: false
					},
					title: {
						text: 'Top performing cities of the month',
						align: 'center',
						// verticalAlign: 'middle',
						// y: 60
					},
					credits: {
						enabled: false
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					},
					accessibility: {
						point: {
							valueSuffix: '%'
						}
					},
					plotOptions: {
						pie: {
							dataLabels: {
								enabled: true,
								distance: -50,
								style: {
									fontWeight: 'bold',
									color: 'white'
								}
							},
							startAngle: -90,
							endAngle: 90,
							center: ['50%', '75%'],
							size: 300
						}
					},
					series: [{
						type: 'pie',
						name: 'Browser share',
						innerSize: '40%',
						data: data.TopPerformingCityMonth
					}],
					exporting: {
						filename: 'TopPerformingCityinMoth'+output,
						showTable: true
					}
				});

				Highcharts.chart('TopPerformingCityYear', {
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: 0,
						plotShadow: false
					},
					title: {
						text: 'Top performing cities of the year',
						align: 'center',
						// verticalAlign: 'middle',
						// y: 60
					},
					credits: {
						enabled: false
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					},
					accessibility: {
						point: {
							valueSuffix: '%'
						}
					},
					plotOptions: {
						pie: {
							dataLabels: {
								enabled: true,
								distance: -50,
								style: {
									fontWeight: 'bold',
									color: 'white'
								}
							},
							startAngle: -90,
							endAngle: 90,
							center: ['50%', '75%'],
							size: 300
						}
					},
					series: [{
						type: 'pie',
						name: 'Browser share',
						innerSize: '40%',
						data: data.TopPerformingCityYear
					}],
					exporting: {
						filename: 'TopPerformingCityinYear'+output,
						showTable: true
					}
				});
				<?php } ?>
			},
			error: function (data,response) {
				// console.log(data);
			}
		});
});
</script>
@endif
@endsection
