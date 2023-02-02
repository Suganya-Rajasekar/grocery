<?php
namespace App\Http\Controllers\Api\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

/**
 * @author : Suriya
 * @return \Illuminate\Http\JsonResponse
 */
class DunzoController extends Controller
{

	private	$Api;
	public function __construct()
	{
		$method = \Route::current()->getActionMethod();
		if (($method == "MasterCron" && isset(\Request::all()['DUNstatus'])) || ($method == 'getQuote' || $method == 'createOrder')) {
			$this->Api	= Http::baseUrl(\Config::get('delivery')['DUNZO_BASE_V2']);
		} else {
			$this->Api	= Http::baseUrl(\Config::get('delivery')['DUNZO_BASE']);
		}
		$this->Api		= $this->Api->withHeaders([
			'client-id'			=> \Config::get('delivery')['DUNZO_CLIENT_ID'],
			'Authorization'		=> \Config::get('delivery')['DUNZO_TOKEN'],
			'Accept-Language'	=> 'en_US',
			'Content-Type'		=> 'application/json'
		]);
	}

	function getToken()
	{
		return $this->Api->withHeaders([
			'client-id' 		=> \Config::get('delivery')['DUNZO_CLIENT_ID'],
			'client-secret' 	=> \Config::get('delivery')['DUNZO_CLIENT_SC'],
			'Accept-Language' 	=> 'en_US',
			'Content-Type' 		=> 'application/json'
		])->get('token');
	}

	function getQuote(Request $request)
	{
		/*$rules['pickup_details']		= 'required|array';
		$rules['pickup_details.*.lat']	= 'required|between:0,99.99';
		$rules['pickup_details.*.lng']	= 'required|between:0,99.99';
		$rules['pickup_details.*.reference_id']	= 'required';

		$rules['drop_details']			= 'required|array';
		$rules['drop_details.*.lat']	= 'required|between:0,99.99';
		$rules['drop_details.*.lng']	= 'required|between:0,99.99';
		$rules['drop_details.*.reference_id']	= 'required';*/
		$rules['pickup_lat']	= 'required';
		$rules['pickup_lng']	= 'required';
		$rules['drop_lat']		= 'required';
		$rules['drop_lng']		= 'required';
		$this->validateDatas($request->all(),$rules);
		$req = [
			'pickup_details' =>  [
				[
					'lat' => (float) (env('APP_ENV') == 'local' ? 12.9672 : $request->pickup_lat),
					'lng' => (float) (env('APP_ENV') == 'local' ? 77.6721 : $request->pickup_lng),
					'reference_id' => $request->order_ref_id
				]
			],
			// 'optimised_route' => true,
			'drop_details' => [
				[
					'lat' => (float) (env('APP_ENV') == 'local' ? 12.9612 : $request->drop_lat),
					'lng' => (float) (env('APP_ENV') == 'local' ? 77.6356 : $request->drop_lng),
					'reference_id' => $request->order_ref_id
				]
			],
			// 'delivery_type' => 'SCHEDULED',
			// 'schedule_time' => 1597095046,
		];
		$app 		= $this->Api->post('quote',$req);
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 200){
			$code 						= 200; 
			$result['data']['distance'] = $response->distance; 
			$result['data']['price'] 	= $response->estimated_price; 
			$result['data']['pickup']   = $response->eta->pickup; 
			$result['data']['drop'] 	= $response->eta->dropoff; 
			$result['message'] 			= "Success"; 
		}else{
			$result['message'] 			= "DUNZO : ".$response->message;
		}
		return \Response::json($result,$code);
	}

	function createOrder(Request $request)
	{
		$rules['order_id']	= 'required';
		$this->validateDatas($request->all(),$rules);
		/*$req = Array(
			"request_id"	=> (String)"KNOSH_".$request->order_id,
			"pickup_details"=> Array(
								"lat"	=> (float) ( env('APP_ENV') == 'local' ? 12.9468180 : $request->restaurant_lat ),
								"lng"	=> (float) ( env('APP_ENV') == 'local' ? 77.6572177 : $request->restaurant_lng ),
								"address"=>Array(
												"appartment_address"=> $request->rest_building,
												"street_address_1"	=> $request->rest_address,
												"landmark"			=> $request->rest_landmark,
												"city"				=> $request->rest_city,
												"state"				=> $request->rest_state,
												"pincode"			=> $request->rest_pin_code,
												"country"			=> "India"
											),
								),
			"drop_details"	=> Array(
								"lat"	=> (float) ( env('APP_ENV') == 'local' ? 12.9468180 : $request->cust_lat ),
								"lng"	=> (float) ( env('APP_ENV') == 'local' ? 77.6472150 : $request->cust_lng ),
								"address"=>Array(
												"appartment_address"=> $request->cust_building,
												"street_address_1"	=> $request->cust_address,
												"landmark"			=> $request->cust_landmark,
												"city"				=> $request->cust_city,
												"state"				=> $request->cust_state,
												"pincode"			=> $request->cust_pin_code,
												"country"			=> "India"
											),
								),
			"sender_details"=> Array(
								"name"			=> $request->restaurant_name,
								"phone_number"	=> (string) $request->restaurant_phone_number,
								),
			"receiver_details"=> Array(
								"name"			=> $request->cust_name,
								"phone_number"	=> (string) $request->cust_phone_number,
								),
			"otp_required"	=> false,
			"package_approx_value"	=> (int) $request->grand_total,
			"special_instructions"	=> $request->rider_note,
			// "delivery_type"			=> $request->is_preorder == 'yes' ? 'SCHEDULED' : '',
			"schedule_time"			=> $request->is_preorder == 'yes' ? $request->schedule_time : '',
			"package_content"		=> Array('Food | Flowers'),
		);*/

		$req = [
			'request_id'		=> (String)"KNOSH_".$request->order_id,
			'reference_id'		=> (String) "#".$request->order_ref_id ,
			'pickup_details'	=> [
				[
					'reference_id'	=> (String) "#".$request->order_ref_id,
					'address'		=> [
						'apartment_address'	=> $request->rest_building,
						'street_address_1'	=> $request->rest_address,
						'landmark'			=> $request->rest_landmark,
						'city'				=> $request->rest_city,
						'state'				=> $request->rest_state,
						'pincode'			=> $request->rest_pin_code,
						'country'			=> 'India',
						'lat'				=> (float) ( env('APP_ENV') == 'local' ? 12.96722 : $request->restaurant_lat ),
						'lng'				=> (float) ( env('APP_ENV') == 'local' ? 77.67211 : $request->restaurant_lng ),
						'contact_details'	=> [
							'name'			=> $request->restaurant_name,
							'phone_number'	=> (string) $request->restaurant_phone_number,
						],
					],
				],
			],
			// 'optimised_route' => true,
			'drop_details' => [
				[
					'reference_id'	=> (String) "#".$request->order_ref_id,
					'address'		=> [
						'apartment_address'	=> $request->cust_building,
						'street_address_1'	=> $request->cust_address,
						'landmark'			=> $request->cust_landmark,
						'city'				=> $request->cust_city,
						'state'				=> $request->cust_state,
						'pincode'			=> $request->cust_pin_code,
						'lat'				=> (float) ( env('APP_ENV') == 'local' ? 12.9468180 : $request->cust_lat ),
						'lng'				=> (float) ( env('APP_ENV') == 'local' ? 77.6472150 : $request->cust_lng ),
						'country'			=> 'India',
						'contact_details'	=> [
							'name'			=> $request->cust_name,
							'phone_number'	=> (string) $request->cust_phone_number,
						],
					],
					// 'otp_required'			=> false,
					'special_instructions'	=> $request->rider_note,
					/*'payment_data'	=> [
						'payment_method'	=> 'COD',
						'amount'			=> (float) $request->grand_total,
					],*/
				]
			],
			'payment_method' => 'DUNZO_CREDIT', // (opt for this in case you do not want to collect cash for the delivery fee from the customer)
			// 'delivery_type' => 'SCHEDULED',
			// 'schedule_time' => 1597095046,
		];
		/*if($request->payment_type == 'cod'){
			$req['payment_method']	= 'COD';
			$req['payment_data']	= Array(
				"amount"					=> $request->grand_total,
				"collect_delivery_charge"	=> $request->payment_type =='cod' ? true : false,
			);
		}*/
		$log = ['Payload' => json_encode($req),'DEALER' => 'Dunzo'];
		\DB::table('tbl_http_logger')->insert(array('request'=>'ORDER_'.$request->order_id.'_Dunzo','header'=>json_encode($log)));
		$app 		= $this->Api->post('tasks',$req);
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 201){
			$code 								= 200; 
			$result['data']['api_orderid']		= $response->task_id; 
			$result['data']['status'] 			= $response->state; 
			$result['data']['price'] 			= $response->estimated_price; 
			$result['data']['pickup']			= $response->eta->pickup; 
			$result['data']['drop']				= $response->eta->dropoff; 
			$result['message'] 					= "Success"; 
		}else{
			$result['message'] 					= $response->message;
		}
		return \Response::json($result,$code);
	}


	/*
		Complete Order Status
		1) created
		2) queued
		3) accepted
		4) reached
		5) picked
		6) riding
		7) arrived
		8) delivered
		9) rider_cancelled - ( Wait for another Rider )
		10)order_cancelled - ( Try another Rider Api )
	*/

	function getOrderStatus(Request $request)
	{
		$rules['api_orderid']	= 'required';
		$this->validateDatas($request->all(),$rules);
		$task_id    = $request->api_orderid;
		$app 		= $this->Api->get('tasks/'.$task_id.'/status');
		$response 	= json_decode($app->body());
		$code 		= 422;
		if($app->status() == 200){
			$code 								= 200; 
			$result['data']['runner']			= null; 
			$result['data']['api_orderid']		= $response->task_id; 
			$result['data']['status'] 			= DunzoBoyStatus($response->state);
			$result['data']['api_status']		= $response->state;
			$result['data']['event_time']		= $response->request_timestamp; 
			if(isset($response->eta)) {
				$result['data']['pickup']		= $response->eta->pickup ?? 0; 
				$result['data']['drop']			= $response->eta->dropoff; 
			}
			if(isset($response->runner)) {
				$result['data']['runner']		= $response->runner; 
			}
			if(isset($response->total_time)) {
				$result['data']['total_time']	= $response->total_time; 
				$result['data']['price']		= $response->price; 
			}
			if(isset($response->cancelled_by)) {
				$result['data']['cancelled_by']	= $response->cancelled_by; 
				$result['data']['reason']		= $response->cancellation_reason; 
			}
			$result['message'] 					= "Success"; 
		}else{
			$result['message'] 					= $response->message;
		}
		return \Response::json($result,$code);
	}

	function cancelOrder(Request $request)
	{
		$rules['api_orderid']		= 'required';
		$rules['reason']			= 'required';
		// $rules['reference_ids']		= 'required|array';
		$this->validateDatas($request->all(),$rules);
		$task_id    = $request->api_orderid;
		$req = Array(
			"cancellation_reason" 	=>	$request->reason,
		);
		$app 		= $this->Api->post('tasks/'.$task_id.'/_cancel',$req);
		/*$req = Array(
			"task_id"					=> $task_id,
			"cancelled_reference_ids"	=> (env('APP_ENV') == 'local') ? [
				(String) "#".$request->order_ref_id
			] : $request->reference_ids
		);
		$app 		= $this->Api->post('tasks/cancel-drops',$req);*/
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 204){
			$code 								= 200; 
			$result['message'] 					= "Order Cancelled"; 

		}else{
			$result['message'] 					= $response->message;
		}
		return \Response::json($result,$code);
	}

	///////////////////////////// TESTING API ////////////////////////////////
	function postmoveNextStatus(Request $request)
	{
		$rules['api_orderid']	= 'required';
		$this->validateDatas($request->all(),$rules);
		$task_id    = $request->api_orderid;
		$req 		= Array(
			"test" 	=>	true,
		);
		$app 		= $this->Api->post('test/tasks/'.$task_id.'/next_state',$req);
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 204){
			$code 								= 200; 
			$result['message'] 					= "Success"; 
		}else{
			$result['message'] 					= $response->message;
		}
		return \Response::json($result,$code);
	}

	function postmoveRunnerCancel(Request $request)
	{
		$rules['api_orderid']	= 'required';
		$this->validateDatas($request->all(),$rules);
		$task_id    = $request->api_orderid;
		$req 		= Array(
			"test" 	=>	true,
		);
		$app 		= $this->Api->post('test/tasks/'.$task_id.'/runner_cancel',$req);
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 200){
			$code 								= 200; 
			$result['data']['api_orderid']		= $response->task_id; 
			$result['data']['status'] 			= $response->state; 
			$result['message'] 					= "Success"; 

		}else{
			$result['message'] 					= $response->message;
		}
		return \Response::json($result,$code);
	}
	///////////////////////////// TESTING API ////////////////////////////////
}
?>