<?php
namespace App\Http\Controllers\Api\Razor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\Payoutslots;
use App\Models\Chefs;
use App\Models\Payout;
use App\Models\Orderdetail;
use App\Http\Controllers\InvoiceController;
/**
 * @author : Suriya
 * @return \Illuminate\Http\JsonResponse
 */
class PayoutsController extends Controller
{

	private	$Api;
	public function __construct()
	{
		$auth = base64_encode(\Config::get('razorpay')['RAZORPAY_KEY'] . ":" . \Config::get('razorpay')['RAZORPAY_SECRET']);
		$this->Api = Http::baseUrl('https://api.razorpay.com/v1/')->withHeaders([
					    'Authorization' => 'Basic '.$auth,
					    'Content-Type' => 'application/json'
					]);
	}

	function getContacts(Request $request){
		$app 		= $this->Api->get('contacts');
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 200){
			$code 						= 200; 
			$result['data']['entity']   = $response->entity; 
			$result['data']['count'] 	= $response->count; 
			$result['data']['items']    = $response->items; 
			$result['message'] 			= "Success"; 
		}else{
			$result['message'] 			= $response->error->description;
		}
		return \Response::json($result,$code);
	}

	function getContact(Request $request){
		$rules['contact_id']	= 'required';

		$this->validateDatas($request->all(),$rules);
		$app 		= $this->Api->get('contacts/'.$request->contact_id);
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 200){
			$code 								= 200; 
			$result['data']['id']   			= $response->id;
			$result['data']['entity']   		= $response->entity;
			$result['data']['name']   			= $response->name;
			$result['data']['contact']   		= $response->contact;
			$result['data']['email']   			= $response->email;
			$result['data']['type']   			= $response->type;
			$result['data']['reference_id']   	= $response->reference_id;
			$result['data']['batch_id']   		= $response->batch_id;
			$result['data']['active']   		= $response->active;
			$result['data']['created_at']   	= $response->created_at;
			$result['message'] 					= "Success"; 
		}else{
			$result['message'] 					= $response->error->description;
		}
		return \Response::json($result,$code);
	}

	function createContact(Request $request){
		$rules['name']			= 'required';
		$rules['email']			= 'required|email';
		$rules['contact']		= 'required|numeric';
		$rules['reference_id']	= 'required';
		$rules['action']		= 'required|in:insert,update';
		if (request('action') == 'update') {
			$rules['contact_id']= 'required';
		}
		$this->validateDatas($request->all(),$rules);

		$req = Array(
			"name"			=> $request->name,
			"email"			=> $request->email,
			"contact"		=> $request->contact,
			"type"			=> 'vendor',
			"reference_id"	=> (String)$request->reference_id,
		);
		if($request->action == 'insert'){
			$app 		= $this->Api->post('contacts',$req);
		}else {
			$app 		= $this->Api->patch('contacts/'.$request->contact_id,$req);
		}
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 200 || $app->status() == 201){
			$code 								= 200; 
			$result['data']['id']   			= $response->id;
			$result['data']['entity']   		= $response->entity;
			$result['data']['name']   			= $response->name;
			$result['data']['contact']   		= $response->contact;
			$result['data']['email']   			= $response->email;
			$result['data']['type']   			= $response->type;
			$result['data']['reference_id']   	= $response->reference_id;
			$result['data']['batch_id']   		= $response->batch_id;
			$result['data']['active']   		= $response->active;
			$result['data']['created_at']   	= $response->created_at;
			$result['message'] 					= "Success"; 
		}else{
			$result['message'] 			= $response->error->description;
		}
		return \Response::json($result,$code);
	}


	function createFund(Request $request)
	{
		$rules['contact_id']	= 'required';
		$rules['name']			= 'required';
		$rules['ifsc']			= 'required';
		$rules['account_number']= 'required';
		$this->validateDatas($request->all(),$rules);

		$req = Array(
			"contact_id"	=> $request->contact_id,
			"account_type"	=> "bank_account",
			"bank_account"	=>	Array(
									"name"			=> $request->name,
									"ifsc"			=> $request->ifsc,
									"account_number"=> $request->account_number,
								),
		);
		$app 		= $this->Api->post('fund_accounts',$req);
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 200 || $app->status() == 201){
			$code 										= 200; 
			$result['data']['id']						=	$response->id;
			$result['data']['entity']					=	$response->entity;
			$result['data']['contact_id']				=	$response->contact_id;
			$result['data']['account_type']				=	$response->account_type;
			$result['data']['active']					=	$response->active;
			$result['data']['batch_id']					=	$response->batch_id;
			$result['data']['created_at']				=	$response->created_at;
			$result['data']['bank']['ifsc']				=	$response->bank_account->ifsc;
			$result['data']['bank']['bank_name']		=	$response->bank_account->bank_name;
			$result['data']['bank']['name']				=	$response->bank_account->name;
			$result['data']['bank']['account_number']	=	$response->bank_account->account_number;
			$result['data']['bank']['notes']			=	$response->bank_account->notes;
			$result['message'] 							= 	"Success"; 
		}else{
			$result['message'] 			= $response->error->description;
		}
		return \Response::json($result,$code);
	}

	function getFunds(Request $request) {
		$app 		= $this->Api->get('fund_accounts');
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 200){
			$code 						= 200; 
			$result['data']			    = $response; 
			$result['message'] 			= "Success"; 
		}else{
			$result['message'] 			= $response->error->description;
		}
		return \Response::json($result,$code);
	}

	function getFund(Request $request) {
		$rules['fa_id']	= 'required';

		$this->validateDatas($request->all(),$rules);
		$app 		= $this->Api->get('fund_accounts/'.$request->fa_id);
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 200){
			$code 								= 200; 
			$result['data']			    = $response; 
			$result['message'] 					= "Success"; 
		}else{
			$result['message'] 					= $response->error->description;
		}
		return \Response::json($result,$code);
	}

	function createPayout(Request $request)
	{
		$rules['account_number']  = 'required';
		$rules['fund_account_id'] = 'required';
		$rules['amount'] 		  = 'required';
		$rules['reference_id'] 	  = 'required';
		$rules['narration'] 	  = 'required';
		$this->validateDatas($request->all(),$rules);

		$req = Array(
			"account_number"    	=> $request->account_number,
			"fund_account_id"   	=> $request->fund_account_id,
			"amount"    			=> ( $request->amount ) * 100,
			"currency"    			=> 'INR',
			"mode"    				=> 'IMPS',
			"purpose"   			=> 'payout',
			"queue_if_low_balance"  => true,
			"reference_id"    		=> (String)$request->reference_id,
			"narration"   			=> str_replace('-', '', $request->narration),
		);
		$app 		= $this->Api->post('payouts',$req);
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 200){
			$code 				= 200; 
			$result['data']		=	$response;
			$result['message'] 	= 	"Success"; 
		}else{
			$result['message'] 	= $response->error->description;
		}
		return \Response::json($result,$code);
	}

	function getPayouts(Request $request) {
		$rules['account_number']	= 'required';
		
		$this->validateDatas($request->all(),$rules);
		$req = Array(
			"account_number"    	=> $request->account_number,
		);
		$app 		= $this->Api->get('payouts',$req);
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 200){
			$code 						= 200; 
			$result['data']			    = $response; 
			$result['message'] 			= "Success"; 
		}else{
			$result['message'] 			= $response->error->description;
		}
		return \Response::json($result,$code);
	}

	function getPayout(Request $request) {
		$rules['pay_id']	= 'required';

		$this->validateDatas($request->all(),$rules);
		$app 		= $this->Api->get('payouts/'.$request->pay_id);
		$response 	= json_decode($app->body());
		$code 		= 422; 
		if($app->status() == 200){
			$code 				= 200; 
			$result['data']		= $response; 
			$result['message'] 	= "Success"; 
		}else{
			$result['message'] 	= $response->error->description;
		}
		return \Response::json($result,$code);
	}

	function Webhook(Request $request) {
		Thirdparty('razorX');
 		if(in_array('payout',$request->contains)){
 			$payout = $request->payload['payout']['entity'] ?? '';
 			if(!empty($payout)){
 				$pay = Payout::with('getVendorDetails')->where('rayzorpay_id',$payout['id'])->first();
 				if(!empty($pay)){
 					$pay->status 		= $payout['status'];
 					$pay->razor_reason 	= $payout['failure_reason'];
 					$pay->utr 	 		= $payout['utr'];
 					$pay->fee 	 		= $payout['fees'];
 					$pay->save();
 					if($pay->status == 'processed'){ //if payment transferred to chef
	 					$mMessage		= 'Payout for ('.date('d M',strtotime($pay->created_at)).") has been ".$pay->status." successfully.";
						FCM($pay->getVendorDetails->mobile_token,"Payout Transaction",$mMessage);
						$Invoice 		= new InvoiceController;
						return $Invoice->generateInvoice($pay->id);
 					}
 				}
 			}
 		}
	}

	//payout cron to chefs
	function PayoutsCron(){ 
		$day  = date('d');
		$paySlot = Payoutslots::where('date',$day)->first();
		if(!empty($paySlot)){ //check if payout day
			$year  		= date('Y');
			$slot  		= explode('-',$paySlot->slot);
			$month 		= $paySlot->prev_month == 0 ? date("m", strtotime("first day of previous month")) : date("m");
			$from_date 	= date_format(date_create($year."-".$month."-".$slot[0]),"Y-m-d");
			$to_date 	= date_format(date_create($year."-".$month."-".$slot[1]),"Y-m-d");
			$Chefs 		= Chefs::select('id','mobile_token')->approved()->wherehas('rzaccount_active')->get(); // get active chefs with rzaccounts
			foreach($Chefs as $chef){
				$pay 	= json_decode(getPayoutReport($chef->id,$from_date,$to_date,true)); //get transfer amount
				$amount = $pay->transferable->amount;
				if($amount > 0){
					$payout 				= new Payout();
					$payout->v_id 			= $chef->id;
					$payout->amount 		= $amount;
					$payout->from_date 		= $from_date;
					$payout->to_date 		= $to_date;
					$payout->save();
					$req['account_number']  = 2323230035299378;
					$req['fund_account_id'] = $chef->rzaccount_active->fund;
					$req['amount'] 		    = $amount;
					$req['reference_id']    = $payout->id;
					$req['narration'] 	    = 'Pay - '.$from_date.'-'.$to_date;
					$Rz 					= $this->createPayout(new Request($req));
					$Rzresult  				= $Rz->getData();
					if($Rz->status() == 200){
						$payout->rayzorpay_id 	= $Rzresult->data->id;
						$payout->status 	 	= $Rzresult->data->status;
						$payout->utr 	 		= $Rzresult->data->utr;
						$mMessage				= 'Payout for ('.date('d M',strtotime($from_date)).'-'.date('d M',strtotime($to_date)).") has been ".$payout->status." you will receive your amount soon.";
						FCM($chef->mobile_token,"Payout Transaction",$mMessage);
						if(count($pay->orderid) > 0){
							Orderdetail::where('vendor_id',$payout->v_id)->whereIn('id',$pay->orderid)->update(["payout"=>$payout->id]);
						}
					}else{
						$payout->status 		= 'razorpay_issue';
						$payout->razor_reason	= $Rzresult->message;
					}
					$payout->save();
				}
			}
		}
	}

	function doPayout()
	{

	}

}
?>