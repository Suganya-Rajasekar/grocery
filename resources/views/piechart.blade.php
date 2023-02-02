<script type="text/javascript" src="{{ asset('admin/assets/js/core/libraries/jquery.min.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/data.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>
<div id="container" style="height: 400px"></div>
<button id="button1" class="autocompare">5 years</button>
<button id="button2" class="autocompare">10 years</button>
<button id="button3" class="autocompare">All</button>
<script type="text/javascript">
	var colors = ["#f7a79c", "#f7a35c", "#90ed7c", "#7cb5ec", "#434348"]

	let chart = Highcharts.stockChart('container', {
		"chart": {
			"type": "column",
		},
		"rangeSelector": {
			"enabled": true,
			"allButtonsEnabled": true,
			"selected": 0,
			"inputEnabled": false,
			"buttonPosition": {
				"align": "left"
			},
			"labelStyle": {
				"display": "none"
			},
			"buttons": [{
				"type": "year",
				"count": 5,
				"text": "5 years"
			},
			{
				"type": "year",
				"count": 10,
				"text": "10 years"
			},
			{
				"type": "all",
				"text": "All"
			}
			],
			"buttonSpacing": 7,
			"buttonTheme": {
				"width": 60,
				"height": 15,
				"padding": 5,
				"stroke": "#0057ae",
				"fill": "transparent",
				"stroke-width": 1,
				"fontWeight": "normal",
				"r": 0,
				"style": {},
				"states": {
					"hover": {
						"fill": "transparent"
					},
					"select": {
						"stroke-width": 3,
						"fill": "transparent",
						"style": {
							"fontWeight": "bold",
							"color": "#0057ae"
						}
					}
				}
			}
		},
		"navigator": {
			"enabled": false
		},
		"scrollbar": {
			"enabled": false
		},
		"plotOptions": {
			"column": {
				"stacking": "normal",
				"dataGrouping": {
					"enabled": false
				}
			}
		},
		"series": [{
			"name": "Bonus",
			"data": [{
				"x": new Date('2017-01-01T00:00:00Z').getTime(),
				"y": 0.042007
			}],
			"color": "#b6cfe0",
			"currency": "ZAR"
		},
		{
			"name": "Annual",
			"data": [{
				"x": new Date('2011-01-01T00:00:00Z').getTime(),
				"y": 0.046428
			},
			{
				"x": new Date('2012-01-01T00:00:00Z').getTime(),
				"y": 0.073661
			},
			{
				"x": new Date('2013-01-01T00:00:00Z').getTime(),
				"y": 0.13145
			},
			{
				"x": new Date('2014-01-01T00:00:00Z').getTime(),
				"y": 0.156164
			},
			{
				"x": new Date('2015-01-01T00:00:00Z').getTime(),
				"y": 0.114728
			},
			{
				"x": new Date('2016-01-01T00:00:00Z').getTime(),
				"y": 0.152276
			},
			{
				"x": new Date('2018-01-01T00:00:00Z').getTime(),
				"y": 0.02295
			},
			{
				"x": new Date('2019-01-01T00:00:00Z').getTime(),
				"y": 0.138227
			},
			{
				"x": new Date('2020-01-01T00:00:00Z').getTime(),
				"y": 0.138227
			}
			],
		}
		]
	});

	$('#button1').click(function() {
		chart.xAxis[0].setExtremes(
			Date.UTC(2016, 0, 1),
			Date.UTC(2020, 11, 31)
			);
	});
	$('#button2').click(function() {
		chart.xAxis[0].setExtremes(
			Date.UTC(2010, 0, 1),
			Date.UTC(2020, 11, 31)
			);
	});
	$('#button3').click(function() {
		chart.xAxis[0].setExtremes(
			Date.UTC(2010, 0, 1),
			Date.UTC(2020, 11, 31)
			);
	});

</script>