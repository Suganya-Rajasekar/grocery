<?php

return [
	'TWO_FACTOR_API_KEY'	=> '8dc94163-e9f5-11eb-8089-0200cd936042',
	'TWO_FACTOR_OPTION'		=> true,
	'TWO_FACTOR_URL'		=> 'https://2factor.in/API/V1/',
	'TWO_FACTOR_TEMPLATE'	=> 'KnoshOTP',
	'DYNAMIC_TEMPLATE'		=> [
		'orderInsert'	=> 'order_insert',
		'orderAccept'	=> 'order_accept',
		'orderCompleted'=> 'order_complete',
		'orderReject'	=> 'order_reject',
		'customer_order_insert'		=> 'customer_order_insert',
		'customer_order_reject'		=> 'customer_order_reject',
		'chef_auto_cancellation'	=> 'chef_auto_cancellation',
		'customer_auto_cancellation'=> 'customer_auto_cancellation',
	]
];