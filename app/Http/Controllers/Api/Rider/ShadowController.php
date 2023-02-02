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
class ShadowController extends Controller
{

	private	$Api;
	public function __construct()
	{
		$method = \Route::current()->getActionMethod();

		if($method == "DeliveryPartnerWebhook" && isset(\Request::all()['SFXstatus'])){
			$this->Api = Http::baseUrl(\Config::get('delivery')['SHADOW_BASE_SANDBOX']);
		}elseif ($method == 'getQuote') {
			$this->Api = Http::baseUrl(\Config::get('delivery')['SHADOW_BASE_V1']);
		}else{
			$this->Api = Http::baseUrl(\Config::get('delivery')['SHADOW_BASE_V2']);			
		}

		$this->Api = $this->Api->withHeaders([
						// 'User-Agent' => 'PostmanRuntime/7.28.2',
			'Authorization' => 'Token '.\Config::get('delivery')['SHADOW_TOKEN'],
			'Content-Type' => 'application/json',
			'Accept' => 'application/json'
		]);
	}

	function getQuote(Request $request){
		$rules['pickup_lat']	= 'required';
		$rules['pickup_lng']	= 'required';
		$rules['drop_lat']		= 'required';
		$rules['drop_lng']		= 'required';
		$this->validateDatas($request->all(),$rules);

		$req = Array(
			"pickup_latitude" 	=>	(float) ( env('APP_ENV') == 'local' ? 12.9468180 : $request->pickup_lat ),
			"pickup_longitude" 	=>	(float) ( env('APP_ENV') == 'local' ? 77.6572177 : $request->pickup_lng ),
			"drop_latitude" 	=>	(float) ( env('APP_ENV') == 'local' ? 12.9468180 : $request->drop_lat ),
			"drop_longitude" 	=>	(float) ( env('APP_ENV') == 'local' ? 77.6472150 : $request->drop_lng ),
			"paid" 				=>	true,
		);
		$app 		= $this->Api->put('order-serviceability/',$req);
		$response 	= json_decode($app->body());
		$code 		= 422;
		if($app->status() == 200){
			if($response->serviceable == true){
				$code 						= 200; 
				$result['data']['distance'] = null; 
				$result['data']['price'] 	= $response->delivery_cost; 
				$result['data']['pickup']   = $response->pickup_eta; 
				$result['data']['drop'] 	= $response->drop_eta; 
				$result['message'] 			= "Success"; 
			}else{
				$result['message'] 			= "SHADOW: ".$response->reason;
			}
		} else {
			if ($app->status() == 503) {
				$rMessage = "503 Service Unavailable";
			} else {
				$rMessage = $response->message ?? $response->detail;
			}
			$result['message'] 				= "SHADOW: ".$rMessage;
		}
		return \Response::json($result,$code);
	}

	function createOrder(Request $request){
		$rules['order_id']	= 'required';
		$this->validateDatas($request->all(),$rules);
		$req = Array
				(
				"order_details" => Array(
									"order_value"		  => (int)$request->grand_total,
									"paid" 				  => "true",
									"client_order_id"	  => (String)"KNOSH_".$request->order_id,
									"delivery_instruction"=> Array(
																"drop_instruction_text" 	=> $request->rider_note,
																"take_drop_off_picture"		=> false,
																"drop_off_picture_mandatory"=> false
																),
								),
				"client_code"	=> \Config::get('delivery')['SHADOW_CODE'],
				"pickup_details"=> Array(
									"city"			=> $request->rest_city,
									"contact_number"=> (string) $request->restaurant_phone_number,
									"name"			=> $request->restaurant_name,
									"longitude"		=> (float) ( env('APP_ENV') == 'local' ? 77.0563207 : $request->restaurant_lng ),
									"address"		=> $request->rest_address,
									"latitude"		=> (float) ( env('APP_ENV') == 'local' ? 28.5833332 : $request->restaurant_lat )
									),
				"order_items"	=> getShadowOrderItems($request->order_id),
				"drop_details"	=> Array(
									"city"			=> $request->cust_city,
									"contact_number"=> (string) $request->cust_phone_number,
									"name"			=> $request->cust_name,
									"longitude"		=> (float) ( env('APP_ENV') == 'local' ? 77.037531 : $request->cust_lng ),
									"address"		=> $request->cust_address,
									"latitude"		=> (float) ( env('APP_ENV') == 'local' ? 28.588891 : $request->cust_lat )
									)
				);
		
		$log = ['Payload' => json_encode($req),'DEALER' => 'Shadow'];
		\DB::table('tbl_http_logger')->insert(array('request'=>'ORDER_'.$request->order_id.'_Shadow','header'=>json_encode($log)));
		$app 		= $this->Api->post('orders/',$req);
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if ($app->status() == 201) {
			$code 								= 200; 
			$res 	 							= $response->data;
			$result['data']['api_orderid']		= $res->sfx_order_id; 
			$result['data']['status'] 			= $res->status; 
			$result['data']['price'] 			= $res->delivery_cost; 
			$result['data']['pickup']			= $res->order_details->pickup_eta; 
			$result['data']['drop']				= $res->order_details->drop_eta; 
			$result['message'] 					= "Success"; 
		} else {
			if ($app->status() == 503) {
				$result['message']	= "Service Unavailable";
				$result['detail']	= "SHADOW: 503 Service Unavailable";
			} else {
				$result['message']	= $response->message && isset($response->detail) && isset($response->detail->error) ? $response->detail->error : $response->message;
				$result['detail']	= $response;
			}
		}
		return \Response::json($result,$code);
	}


	/*Complete Order Status 

	1) created
	2) queued
	3) accepted
	4) reached
	5) picked
	6) riding
	7) arrived
	8) delivered
	9) rider_cancelled - ( Wait for another Rider )
	10)order_cancelled - ( Try another Rider Api ) */

	function getOrderStatus(Request $request){
		$rules['api_orderid']	= 'required';
		$this->validateDatas($request->all(),$rules);
		$task_id    = $request->api_orderid;
		$app 		= $this->Api->get('orders/'.$task_id.'/status/');
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 200){
			$code 								= 200; 
			$result['message'] 					= $response->message;
			$response 							= $response->data;
			$result['data']['runner']			= null; 
			$result['data']['api_orderid']		= $response->sfx_order_id; 
			$result['data']['status'] 			= ShadowBoyStatus($response->status);
			$result['data']['api_status']		= $response->status;
			$result['data']['event_time']		= $response->order_details->scheduled_time; 
			if(isset($response->order_details)) {
				$result['data']['pickup']		= $response->order_details->pickup_eta; 
				$result['data']['drop']			= $response->order_details->drop_eta; 
			}
			if(isset($response->rider_details->rider_name)) {
				$result['data']['runner']['name'] 			 = $response->rider_details->rider_name;
				$result['data']['runner']['phone_number'] 	 = $response->rider_details->rider_phone;
				$result['data']['runner']['location']['lat'] = $response->rider_details->rider_location->latitude ?? '';
				$result['data']['runner']['location']['lng'] = $response->rider_details->rider_location->longitude ?? '';
			}
			if(isset($response->total_time)) {
				$result['data']['total_time']	= $response->order_details->pickup_eta + $response->order_details->drop_eta; 
			}
			if(isset($response->price)) {
				$result['data']['price']		= $response->price; 
			}
				
			if(isset($response->cancelled_by)) {
				$result['data']['cancelled_by']	= null; 
				$result['data']['reason']		= null; 
			}

		}else{
			$result['message'] 					= $response->message && isset($response->detail) && isset($response->detail->error) ? $response->detail->error : $response->message;
		}
		return \Response::json($result,$code);
	}

	function cancelOrder(Request $request){
		$rules['api_orderid']			= 'required';
		$rules['reason']				= 'required|max:128';
		$this->validateDatas($request->all(),$rules);
		$task_id    = $request->api_orderid;
		$req = Array(
			"reason" 	=>	$request->reason,
			"user"	 	=>	"Customer",
		);
		$app 		= $this->Api->put('orders/'.$task_id.'/cancel/',$req);
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 200){
			$code 								= 200; 
			$result['message'] 					= "Order Cancelled"; 

		}else{
			$result['message'] 					= $response->message && isset($response->detail) && isset($response->detail->error) ? $response->detail->error : $response->message;
		}
		return \Response::json($result,$code);
	}

	///////////////////////////// TESTING API////////////////////////////////
	function postmoveNextStatus(Request $request){
		$rules['api_orderid']	= 'required';
		$this->validateDatas($request->all(),$rules);
		$task_id	= $request->api_orderid;
		$status		= $request->SFXstatus;
		if($status == "allot"){
			$app 		= $this->Api->put('clusters/orders/'.$task_id.'/'.$status.'/',['rider_id'=>2052]);
		}elseif($status == 'update-rider-arrival'){
			$app 		= $this->Api->post('order/'.$task_id.'/'.$status.'/',['time_arrival'=>"2018-01-09 18:40:00"]);
		} else {
			$app 		= $this->Api->put('order/'.$task_id.'/'.$status.'/',['rider_id'=>2052]);
		}
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 204){
			$code				= 200; 
			$result['message']	= "Success"; 
		}else{
			$result['message']	= $response->message && isset($response->detail) && isset($response->detail->error) ? $response->detail->error : $response->message;
		}
		return \Response::json($result,$code);
	}

	function postmoveRunnerCancel(Request $request){
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
			$result['message'] 					= $response->message && isset($response->detail) && isset($response->detail->error) ? $response->detail->error : $response->message;
		}
		return \Response::json($result,$code);
	}

	///////////////////////////// TESTING API////////////////////////////////
}
?>