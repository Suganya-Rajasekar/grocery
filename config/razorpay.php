<?php
if(env('APP_ENV') == 'local'){
	return [
		'RAZORPAY_KEY'  	  => 'rzp_test_M0UgXres2NRmN1',
		'RAZORPAY_SECRET'     => 'bURdp3dlYlyzxdK7x4xEp8id',
	];
}
//PRODUCTION
return [
	'RAZORPAY_KEY'  	  => 'rzp_live_FYNEV6p6R6xgcJ',
	'RAZORPAY_SECRET'     => 'pm3bUcslRVGIMOQMMiev0DJC',
];