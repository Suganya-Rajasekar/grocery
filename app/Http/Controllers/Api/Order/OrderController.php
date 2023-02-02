<?php
namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Orderdetail;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Auth, Mail, DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Cart;
use App\Models\Offer;
use App\Models\Chefs;
use App\Models\Restaurants;
use App\Models\Boy;
use App\Models\SettingsCancellation;
use App\Models\Cancellation;
use Illuminate\Support\Facades\Redis;


use App\Http\Controllers\Api\Rider\DunzoController as Dunzo;
use App\Http\Controllers\RazorpayPaymentController;
/**
 * @author : Suriya , Roja, Suganya
 * @return \Illuminate\Http\JsonResponse
 */
class OrderController extends Controller
{
	public function role()
	{
		$guard	= (request('from') == 'mobile') ? 'api' : 'web';
		$user	= auth($guard)->user();
		if(!empty($user)){
			$role_id= User::where('id', $user->id)->pluck('role')->toArray()[0];
			if(isset($role_id) && ($role_id == 3 || $role_id == 2 || $role_id == 1 || $role_id == 5)){
				return true;
			} else {
				return false;
			}
		}
		return false;
	}

	public function orderData(Request $request)
	{
		$status		= 200;
		$guard		= ($request->from == 'mobile') ? 'api' : 'web';
		if(isset($request->webhook) && $this->method == 'POST'){
			$auth_id	= $request->auth_id;
			$auth_role	= $request->auth_role;
			$user 		= User::find($auth_id);

		}else{
			$auth_id	= auth($guard)->user()->id;
			$auth_role	= auth($guard)->user()->role;
			$user 		= auth($guard)->user();
		}
		$cmessage	= $rules = $nicenames	= [];
		if ($this->method == 'POST') {
			$rules['payment_type']	= 'required|in:cod,razorpay';
		} elseif ($this->method == 'GET') {
			if ($auth_role == 3) {
				$rules['type']	= 'required|in:new,accepted,completed,today';
			} else {
				$rules['type']	= 'required|in:in_process,past,today,event,home_event';		
			}
		} elseif ($this->method == 'PUT') {
			$rules['status']	= 'required|in:accepted_res,rejected_res,accepted_admin,rejected_admin,food_ready,rejected_cus,completed';
			$orderRule = ['required','exists:order_detail,id'];
			if ($request->status == 'accepted_res' || $request->status == 'rejected_res' || $request->status == 'accepted_admin' || $request->status == 'rejected_admin') {
				if ($request->status != 'rejected_cus' && $auth_role != 1 && $auth_role != 5) {
					array_push($orderRule, 'exist_check:order_detail,where:vendor_id:=:'.$auth_id);
				}
				array_push($orderRule, 'condition_check:order_detail,where:status:=:pending');
			} elseif ($request->status == 'food_ready') {
				array_push($orderRule, 'condition_check:order_detail,where:status:=:accepted_admin,orwhere:status:=:accepted_res');
			} elseif ($request->status == 'completed' && $auth_role == 1) {
				array_push($orderRule, 'condition_check:order_detail,where:status:=:food_ready,orwhere:status:=:pickup_boy,orwhere:status:=:reached_location,orwhere:status:=:reached_restaurant,orwhere:status:=:riding');
			}

			if ($request->status == 'rejected_res' || $request->status == 'rejected_admin' || $request->status == 'rejected_cus') {
				$rules['reason']	= 'required|min:5';
			}
			$rules['order_id']		= $orderRule;
		} elseif ($this->method == 'PATCH') {
		}
		$this->validateDatas($request->all(),$rules,$cmessage,$nicenames);

		if (!($this->role()) && $auth_role != 1 && !isset($request->webhook)) {
			return \Response::json(['message'	=> "Only vendors and customers are allowed"],403);
		}
		if ($this->method == 'POST') {
			$uCartQuery	= uCartQuery($auth_id, 0);
			$cartclo  = clone ($uCartQuery);
			$aCart		= Cart::select('res_id','address_id','coupon_id','total_coupon_value','date','time_slot','food_id','is_preorder','food_type','used_wallet_amount','theme','preferences','meals_count')->userid($auth_id)->groupBy('res_id')->get();
			$uncart = $uCartQuery->select('res_id','address_id','coupon_id','total_coupon_value','date','time_slot','food_id','is_preorder','food_type')->get();
			
			$food_type = $cartclo->select('id','food_type','is_preorder')->first()->food_type;
			if($food_type == 'menuitem') {	
				if(!isset($request->webhook)) {
					$res = cart_unavailable_check($food_type,$uncart);
					if($res['unavailable'] == true) {
						return \Response::json(['message'	=> $res['message']],422);
					}
				}	
			} else {
				$res = cart_unavailable_check($food_type,$uncart);
				if($res['unavailable'] == true) {
					return \Response::json(['message'	=> $res['message']],422);
				}
			}
			if (!$aCart->isEmpty()) {				
				/*if($guard != 'api' && $request->payment_type != 'razorpay'){
					$ucartValid	= uCartExistsCheck($auth_id, 0);
					if (in_array(1, $ucartValid[0])) {
						return \Response::json(['message'	=> "Please remove the items which are marked as unavailable in your cart and then place your order"],422);
					}
				}*/
				if($guard == 'api' && $request->payment_type != 'cod'){
					if($food_type == 'menuitem') {	
						if(!isset($request->webhook)) {
							$res = cart_unavailable_check($food_type,$uncart);
							if($res['unavailable'] == true) {
								return \Response::json(['message'	=> $res['message']],422);
							}
						}	
					} else {
						$res = cart_unavailable_check($food_type,$uncart);
						if($res['unavailable'] == true) {
							return \Response::json(['message'	=> $res['message']],422);
						}
					}
					$proxy	= Request::create('','GET',[]);
					$cart	= app()->call('App\Http\Controllers\Api\V1\Emperica\CartController@addCart',['request' => $proxy])->getData();
					$pay		= new RazorpayPaymentController;
					$rz_id		= $pay->getNewOrderid($cart->price);
					$uCartQuery->update(['rz_id'=>$rz_id]);
					$response['rz_id']		= $rz_id;
					$response['orderId']	= 0;
					$response['message']	= 'Order Placed Successfully';
					return \Response::json($response,$status);
				}
				// dd($aCart[0]->coupon_id);
				$orderArray 	= array();
				$orderArray['user_id'] 			 = $auth_id; 
				$orderArray['mobile_number'] 	 = $user->mobile; 
				$orderArray['total_food_amount'] = 0; 
				$orderArray['vendor_price'] 	 = 0; 
				$orderArray['commission_amount'] = 0; 
				$orderArray['del_charge'] 		 = 0;
				$orderArray['offer_value'] 		 = 0; 
				$orderArray['grand_total'] 		 = 0; 
				$orderArray['address_id'] 		 = $aCart[0]->address_id;
				$orderArray['coupon_id'] 		 = $aCart[0]->coupon_id;
				$orderArray['order_type']        = $food_type; 
				$orderArray['used_wallet_amount']= $aCart[0]->used_wallet_amount; 
				if ($request->payment_type == 'cod') {
					$orderArray['payment_status']	 = 'pending'; 
					$orderArray['payment_type']  	 = $request->payment_type; 
				} else {
					$orderArray['payment_type']  	 = "online"; 
					if($guard == 'web'){
						$orderArray['payment_status'] 	 = 'paid'; 
						$orderArray['online_pay_status'] = 'captured'; 
						$orderArray['payment_token'] 	 = $request->payment_token;
					}
				}
				$MainOrder		= Order::create($orderArray);
				$MainOrderid	= $MainOrder->id;
				wallet_debit($aCart[0]->used_wallet_amount,$user,$MainOrderid);
				foreach($aCart as $keys => $cart) {
					$RestaurantCart = Cart::select('id','date')->where('res_id',$cart->res_id)->groupBy('date')->userid($auth_id)->get();
					foreach($RestaurantCart as $key => $ResCart) {
						$TimeCart	= Cart::select('time_slot','id','coupon_id','total_coupon_value')->where('res_id',$cart->res_id)->where('date',$ResCart->date)->groupBy('time_slot')->userid($auth_id)->get();
						$slotCount	= $TimeCart->count();
						foreach($TimeCart as $ke => $TmCart){
							$SlotCart	= Cart::where('res_id',$cart->res_id)->where('time_slot',$TmCart->time_slot)->where('date',$ResCart->date)->with('vendor_info','menu_item','addon_item')->userid($auth_id)->get();
							$priceTotal	=  0;
							foreach ($SlotCart as $k => $fCart) {
								$priceTotal		+= $fCart->price;
								$foodDetails[$k]['id']				= $fCart->menu_item->id;
								$foodDetails[$k]['name']			= $fCart->menu_item->name;
								$foodDetails[$k]['fPrice']			= $fCart->menu_item->price;
								$foodDetails[$k]['quantity']		= $fCart->quantity;
								$foodDetails[$k]['price']			= $fCart->price;
								$foodDetails[$k]['fdiscount_price'] = ($fCart->discount != 0) ? (int) ($fCart->menu_item->price - ((int)$fCart->menu_item->price * ((int)$fCart->menu_item->discount/100))) : 0;
								$foodDetails[$k]['discount'] 		= $fCart->discount;
								$foodDetails[$k]['unit']			= $fCart->unit;
								$foodDetails[$k]['addon']			= ($fCart->is_addon == 'yes') ? $fCart->addon_item : [];
								$foodDetails[$k]['theme']           = ($fCart->theme != '') ? $fCart->theme_data : [];
								$foodDetails[$k]['preferences']     = ($fCart->preferences != '') ? $fCart->preferences_data : [];
								if ($fCart->meals_count != '') {
									$foodDetails[$k]['meals_count'][]= json_decode($fCart->meals_count,true); 
								} else {
									$foodDetails[$k]['meals_count']   = []; 
								}
							} 
							$fCart		= $SlotCart[0];
							$del_charge	= getDelCharge();
							if($food_type == "ticket" || $food_type == "home_event_menu" || $auth_id == 235){
								$del_charge = 0;
							}
							$vendorInfo	= $fCart->vendor_info;
							$commission	= $vendorInfo->commission;
							$offer_amount	= 0;$offer_value	= 0;$tax	= 0;
							/*if($vendorInfo->discount_status == 'yes') {
								$offer_amount	= $vendorInfo->discount_type == 'price' ? ($priceTotal - $vendorInfo->discount) : ($priceTotal * ( $vendorInfo->discount / 100 ));
								$offer_value	= $vendorInfo->discount;
							}*/
							$coupon_detail = Offer::where('id',$TmCart->coupon_id)->active()->first();
							//find($TmCart->coupon_id);
							if (!empty($coupon_detail)) {
								$orderDetail['offer_value']	= $offer_amount = $TmCart->total_coupon_value;
								$orderDetail['offer_type']	= $coupon_detail->promo_type;
								if ($coupon_detail->promo_type == 'percentage') {
									$orderDetail['offer_percentage'] = $coupon_detail->offer;
								} else  {
									$orderDetail['offer_amount']= $coupon_detail->offer;
								}
							}
							$commission_amount	= ($priceTotal - $offer_amount) * ( $commission / 100 );
							$vendor_price	= ($priceTotal - $commission_amount);
							if($vendorInfo->tax > 0) {
								$tax	=	$priceTotal * ( $vendorInfo->tax / 100 );
							}
							// $orderkey	= ($keys+1);
							$mID		= (100000+$MainOrderid);
							$orderDetail['order_id']	= $MainOrderid;
							$orderDetail['m_id']		= $mID;
							$orderDetail['user_id']		= $auth_id;
							$orderDetail['vendor_id']	= $fCart->vendor_info->vendor_id;
							$orderDetail['res_id']		= $fCart->res_id;
							$orderDetail['vendor_price']= $vendor_price;
							$orderDetail['commission']	= $commission;
							$orderDetail['del_km']		= $fCart->deliverdistance;
							$orderDetail['del_charge']	= $del_charge;
							$orderDetail['status']		= ($food_type == "ticket" || $food_type == "home_event_menu") ? 'completed' : 'pending';
							$orderDetail['tax']			= $vendorInfo->tax;
							$orderDetail['package_charge'] = $vendorInfo->package_charge;
							
							$orderDetail['tax_amount']	= $tax;
							$orderDetail['food_items']	= json_encode($foodDetails);
							$orderDetail['is_preorder']	= $fCart->is_preorder;
							$orderDetail['date']		= $fCart->date;
							$orderDetail['time_slot']	= $fCart->time_slot;
							$orderDetail['s_id']		= $mID.'-'.$fCart->vendor_info->user_code.'-'.$slotCount/*.'-'.$orderkey*/;
							$orderDetail['grand_total']	= $priceTotal + $tax + $del_charge + $vendorInfo->package_charge - $TmCart->total_coupon_value - $aCart[0]->used_wallet_amount;
							$orderDetail['total_food_amount']= $priceTotal;
							$orderDetail['commission_amount']= $commission_amount;
							$orderDetail['order_type']  = $food_type;
							$orderDetail	= Orderdetail::create($orderDetail);
							$MainOrder->increment('total_food_amount',$priceTotal);
							$MainOrder->increment('vendor_price',$vendor_price);
							$MainOrder->increment('commission_amount',$commission_amount);
							$MainOrder->increment('del_charge',$del_charge);
							$MainOrder->increment('offer_value',$offer_amount);
							$MainOrder->increment('grand_total',$orderDetail->grand_total);
							unset($orderDetail);
							unset($foodDetails);
						}
					} 
				} 
				$order_grand_total		= Order::find($MainOrderid,['id','total_food_amount','grand_total','del_charge','offer_value']);
				$Order		= Orderdetail::where('order_id',$MainOrderid);
				$TotalOrder	= clone ($Order); $TotalOrderCount	= clone ($Order);
				$Order		= $Order->addSelect('s_id','id','vendor_id');
				$TotalOrder	= $TotalOrder->addSelect(\DB::raw('GROUP_CONCAT(DISTINCT(`vendor_id`)) as vendors'));
				$TotalOrderCount= $TotalOrderCount->count();

				$i	= 1; $arr = []; $cnt = [];
				$Order->get()->map(function ($item, $e) use($TotalOrderCount, $TotalOrder, &$arr, &$cnt, &$i) {
					if (in_array($item->vendor_id, explode(',', $TotalOrder->first()->vendors))) {
						$kee	= array_search($item->vendor_id, $arr);
						if (!empty($arr) && $kee !== false) {
							$ky = $cnt[$kee] += 1;
						} else {
							array_push($arr, $item->vendor_id);
							array_push($cnt, $i);
							$ky = $i;
						}
					}
					$ID			= explode('-', $item->s_id);
					$ID[3]		= $TotalOrderCount;
					$item->s_id	= implode('-', $ID);
					$item->s_id	= $item->s_id.'-'.$ky;
					Orderdetail::where('id',$item->id)->update(['s_id'=>$item->s_id]);
					return $item;
				});
				/*$response['rz_id']	= '';
				if($guard == 'api' && $request->payment_type != 'cod'){
					$pay						= new RazorpayPaymentController;
					$response['rz_id']			= $pay->getNewOrderid($MainOrder->grand_total);
					$MainOrder->payment_token	= $response['rz_id'];
					$MainOrder->save();
				}*/
				if($request->payment_type == 'cod' || ($request->payment_type != 'cod' && $MainOrder->payment_status == 'paid'))
				{
					/*send order mail*/
					// if($MainOrder->order_type != 'ticket') {
						$orderDetails	= Orderdetail::where('order_id',$MainOrder->id)->get();
						$status_text 		= ($MainOrder->order_type == 'ticket') ? 'EventBooked' : (($MainOrder->order_type == 'home_event_menu') ? 'Home_Event_Booked' : 'orderInsert');
						foreach ($orderDetails as $val_order) {
							$send_mail = app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@sendOrderMail',['request' => request()->merge(array('s_id'=>$val_order->s_id,'status'=>$status_text))]);
						}
					// }
					/*send order mail*/
					/* Clear cart data begin */
					$adel	= clone ($uCartQuery); $ucdel = clone ($uCartQuery);
					$adel->get()->map(function ($value) {
						$value->addon_item()->delete();
					});
					$ucdel->delete();
					/* Clear cart data end */
				}
				$message= 'Order Placed Successfully';
				$response['orderId']	= $MainOrderid;
            	Redis::publish('Message',json_encode(['order','new']));
			} else {
				$status		= 422;
				$message	= 'Your cart is empty & you can not able to place an order.';
			}
		} else if ($this->method == 'GET') {
			$field	= ($auth_role == 3) ? 'vendor_id' : 'user_id';
			$type	= ($request->type == 'new') ? 'pending' : $request->type;
			$resultData	= Orderdetail::where(function($query) use($type, $field){
				if ($type != 'in_process' && $type != 'past' && $type != 'event' && $type != 'home_event') {
					// $query->where('status', 'like', '%'.$type.'%');
					if ($field == 'vendor_id' && $type == 'completed') {
						$query->orWhereIn('status',['food_ready','pickup_boy','reached_location','reached_restaurant','riding','accepted_boy','completed','rejected_res','rejected_cus','rejected_admin','cancelled']);
					} else if ($field == 'vendor_id' && $type == 'accepted') {
						$query->orWhereIn('status',['accepted_res','accepted_admin']);
					} else if ($field == 'vendor_id' && $type == 'pending') {
						$query->where('status','pending');
					}
				} else if ($type == 'today') {
					$query->where('date', date('Y-m-d'));
				} else if ($type == "event") {
					$query->where('order_type','ticket')->where('status','completed');
				} else if($type == "home_event") {
					$query->where('order_type','home_event_menu')->where('status','completed');
				} else {
					if ($type == 'in_process') {
						$query->whereIN('status',['pending','accepted_admin','accepted_res','accepted_boy','food_ready','pickup_boy','reached_location','reached_restaurant','riding'])->where('order_type','menuitem');
					} else {
						$query->whereIN('status',['completed','rejected_res','rejected_cus','rejected_admin','cancelled'])->where('order_type','menuitem');
					}
				}
			})
			// ->whereHas('reviewinfo')
			->with('userinfo','chefinfo','rider_info','cancellationData'/*,'reviewinfo'*/)->whereHas('chefinfo')
			->where($field,$auth_id);

			/* TODAY ORDER Begin */
			if ($auth_role == 3) {
				$todayData	= clone $resultData;
				$todayData	= $todayData->where('date', date('Y-m-d'))->orderByDesc('id')->paginate(10)->append(['order_status','time_s','time_chef','user_addr','boy_info','reviewinfo','food_items_count']);
				$todayData->makeHidden([/*'created_at',*/'updated_at','time_slot',/*'m_id',*/'boy_id']);
				$todayData->makeHidden(['grand_total','del_charge','del_km','offer_percentage','offer_amount']);
				$response['today']	= $todayData;
			}
			/* TODAY ORDER End */
			if ($field == 'vendor_id' && $type == 'accepted') {
				// this will omit today orders in accepted orders list
				$resultData	=	$resultData->where('date','<>', date('Y-m-d'));
			}
			$resultData	=	$resultData->orderByDesc('id')->paginate(10)->append(['order_status','time_s','time_chef','user_addr','reviewinfo','food_items_count']);
			$resultData->makeHidden([/*'created_at',*/'updated_at','time_slot',/*'m_id',*/'boy_id']);
			if ($auth_role == 3) {
				$resultData->makeHidden(['grand_total','del_charge','del_km','offer_percentage','offer_amount']);
			}
			$response['orders']	= $resultData;
			$message			= "success";
		} elseif ($this->method == 'PUT') {
			$order_id	= $request->order_id;
			$rStatus	= $request->status;
			$check		= Orderdetail::where('id',$order_id);
			if ($auth_role == 3) {
				$check->where('vendor_id',$auth_id);
				$sta_acc	= 'accepted_res';
			} else if($auth_role == 1 || $auth_role == 5) {
				$sta_acc	= 'accepted_admin';
			} else {
				$check->where('user_id',$auth_id);
				$sta_acc	= 'accepted_admin';
			}
			$check = $check->first();
			if ($check) {
				$checkS = ($rStatus == 'rejected_res' || $rStatus == 'rejected_admin') ? ['accepted_res','accepted_admin','pending'] : (($rStatus == 'food_ready') ? ['accepted_res','accepted_admin'] : (($rStatus == 'completed') ? ['food_ready','pickup_boy','reached_location','reached_restaurant','riding'] : ['pending'])); // Valid current status check
				if ($checkS == 'food_ready') {
					$oStatus = Orderdetail::where('id', $order_id)->first();
					if ($oStatus->date > date('Y-m-d')) {
						$status		= 422;
						$message	= "You can't able to make this order as ready now";
						$response['message']	= $message;
						return \Response::json($response,$status);
					}
				}
				$checkStatus = Orderdetail::where('id', $order_id)->whereIn('status', $checkS)->first();
				if ($checkStatus) {
					$status	= 200;
					$order	= Orderdetail::find($order_id);
					$order->status = $rStatus;
					$order->save();
       				Redis::publish('orderstatus',json_encode($order));
					$this->sendRequestToBoy($request);
					/*if ($rStatus == 'rejected_res' || $rStatus == 'rejected_admin' || $rStatus == 'rejected_cus') {
						$penalty	= self::cancellationHours($order);
						if (($rStatus == 'rejected_res' && $penalty['chef_penalty'] > 0) || ($rStatus == 'rejected_cus' && $penalty['customer_penalty'] > 0)) {
							$cancelled['order_id']		= $order->id;
							$cancelled['customer_penalty']= ($rStatus == 'rejected_cus') ? $penalty['customer_penalty'] : '0.00';
							$cancelled['chef_penalty']	= ($rStatus != 'rejected_cus') ? $penalty['chef_penalty'] : '0.00';
							$cancelled['sub_order_id']	= $order->s_id;
							CancellationSave($cancelled);
						}
						if ($rStatus != 'rejected_cus') {
							$order->reason	= $request->reason;
						}
						if ($order->order->payment_type == 'online' && $order->order->payment_token != '' && $order->order->online_pay_status == 'captured') {
							RazorRefund($order->order->payment_token, $cancelled['customer_penalty']);
						}
					}*/
					if ($rStatus == 'accepted_res' || $rStatus == 'accepted_admin' /*|| $rStatus == 'rejected_res' || $rStatus == 'rejected_cus'*/) {
						$reQ	= array('s_id'=>$check->s_id,'curStatus'=>$check->status);
						$reQ['status']	= ($rStatus == 'accepted_res' || $rStatus == 'accepted_admin') ? 'orderAccept' : 'orderReject';
						request()->merge($reQ);
						$send_mail	= app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@sendOrderMail');
					}
					// imageConvert($order_id);
					$message	= "Order ";
					$message	.= ($rStatus == 'accepted_res' || $rStatus == 'accepted_admin') ? 'accepted' : (($rStatus == 'food_ready') ? 'moved to ready state' : (($rStatus == 'rejected_cus') ? 'cancelled' : (($rStatus == 'completed') ?  'delivered' : 'rejected')));
					$message	.= " successfully";
				} else {
					$status		= 422;
					$message	= "Order already ";
					$message	.= ($check->status == 'accepted_res' || $check->status == 'accepted_admin') ? 'accepted' : (($check->status == 'food_ready') ? 'in ready state' : (($check->status == 'rejected_cus') ? 'cancelled by customer' : 'rejected'));
				}
			} else {
				$status		= 403;
				$message	= "Its not your order";
			}
		} elseif ($this->method == 'PATCH') {
			$message	= 'PATCH';
		}
		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	public function cancellationHours($order)
	{
		$hours	= 0;
		$return	= ['chef_penalty' => 0, 'customer_penalty' => 0];
		if (!empty($order)) {
			$time1	= date('Y-m-d H:i:s', strtotime($order->date.' '.explode('-', $order->time_s)[0]));
			$hours	= round((strtotime($time1) - strtotime(date('Y-m-d H:i:s')))/3600, 1);
			if ($hours > 0) {
				$cCharge	= SettingsCancellation::where(function($query) use ($hours) {
					return $query->where(function($query) use ($hours) {
						$query->where('timeline', 'upto')->where('hours', '>=', $hours);
					})->orWhere(function($query) use ($hours) {
						$query->where('timeline', 'before')->where('hours', '<=', $hours);
					});
				})->orderBy('hours','ASC')->first();
				if (!empty($cCharge)) {
					$return['chef_penalty']		= $order->vendor_price * ($cCharge->chef_penalty / 100);
					$return['customer_penalty']	= $order->grand_total * ($cCharge->customer_penalty / 100);
				}
			}
		}
		return $return;
	}

	public function cronAutoCancellation()
	{		
		$orders = Orderdetail::with('restaurant','userinfo','chefinfo')->where('status', 'pending')->where('date', date('Y-m-d H:i:s'))->whereRaw('ABS(TIMESTAMPDIFF(MINUTE, created_at, ?)) >= 10', [date('Y-m-d H:i:s')])->get();
		if (count($orders) > 0) {
			foreach($orders as $key => $order) {
				self::orderCancellation($order,'autoreject');
			}
		}
	}

	public function orderCancellation($order,$reason='')
	{
		if (!empty($order) && $reason != '') {
			$reQ	= ['s_id' => $order->s_id,'curStatus' => $order->status];
			if ($reason == 'autoreject' || ($reason == 'rejected_res' || $reason == 'rejected_admin' || $reason == 'rejected_cus')) {
				$penalty	= self::cancellationHours($order);
				$reQ['chef_penalty']	= $penalty['chef_penalty'];
				$cancelled['order_id']			= $order->id;
				$cancelled['customer_penalty']	= ($penalty['customer_penalty'] > 0) ? $penalty['customer_penalty'] : '0.00';
				$cancelled['chef_penalty']		= ($penalty['chef_penalty'] > 0) ? $penalty['chef_penalty'] : '0.00';
				$cancelled['sub_order_id']		= $order->s_id;
				CancellationSave($cancelled);
				$reQ['status']	= ($reason == 'autoreject') ? 'Cancelled' : 'orderReject';

				if ($reason == 'autoreject') {
					$order->status	= 'cancelled';
					$order->save();
					// RazorRefund($order->order->payment_token, $order->grand_total);
				} else {
					if ($order->order->payment_type == 'online' && $order->order->payment_token != '' && $order->order->online_pay_status == 'captured') {
						// RazorRefund($order->order->payment_token, $cancelled['customer_penalty']);
					}
				}
			}
			request()->merge($reQ);
			$send_mail		= app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@sendOrderMail');
		} else {
			return false;
		}
	}

	public function sendRequestToBoy(Request $request)
	{
		AppendMaster('Orderdetail',['call_boy','user_addr']);
		$order = Orderdetail::with('restaurant','userinfo','chefinfo')->withCount('retries')->find($request->order_id);
		if (!empty($order)) {
			$order_id	= $request->order_id;
			switch ($request->status) {
				case 'accepted_res':case 'accepted_admin': //Call Boy Request
				self::createBoyOrder($order);
				break;
				case 'accepted_boy':case 'reached_restaurant':case 'pickup_boy':case 'riding':case 'reached_location':case 'completed':
				$order->status	= $request->status;
				if (isset($request->rider)) {
					$info		= Boy::findOrCreate($request->rider->name,$request->rider->phone_number,$request->dealer);
					$order->rider	= $info->id;   
				}
				$order->save();
				$check_completed = Orderdetail::find($order_id);
				if ($request->status == 'completed' && $check_completed->status != 'completed') {
					/*send order mail*/
					$send_mail	= app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@sendOrderMail',['request'	=> request()->merge(array('s_id'=>$order->s_id,'status'=>'orderCompleted'))]);
					/* send order mail */
				}
				break;
				case 'order_cancelled':
				$order->rider_order	= null;
				$order->rider		= null;
				$order->dealer		= '';
				$order->save();
				if (isset($request->cancelled_by)) {
					if ($order->retries_count >= 4) {
						self::orderCancellation($order,'retriesover');
					}
					$order->cancelled_by	= $request->cancelled_by;
					$order->reason			= $request->reason;
					$order->save();
				}
				break;
				case 'rejected_res':case 'rejected_admin':case 'rejected_cus':
				$order->reason	= $request->reason;
				$order->save();
				if ($order->rider_order != null) {
					$req = ['api_orderid' => $order->rider_order, 'reason' => $order->reason];
					$res = app()->call('App\Http\Controllers\Api\Rider\\'.ucfirst($order->dealer).'Controller@cancelOrder',['request' => request()->merge($req)]);
				}
				self::orderCancellation($order,$request->status);
				break;
				default:
				return $request->status;
			}
		}
	}

	public function cronPendingBoy()
	{
		$orders = Orderdetail::checkifretry()->with('restaurant','userinfo','chefinfo')->has('retries', '<=', 4)->where(function($query){$query->where('status', 'accepted_admin')->orWhere('status', 'accepted_res')->orWhere('status', 'food_ready');})
		->where('date',date('Y-m-d'))->whereNull('rider_order')->get();
		foreach($orders as $key => $order) {
			self::createBoyOrder($order);
		}
	}

	public function createBoyOrder($order)
	{
		if ($order->call_boy) {
			$req	= getCreateOrderRequest($order);
			$check	= new Request();
			$check['pickup_lat']	= $req['restaurant_lat'];
			$check['pickup_lng']	= $req['restaurant_lng'];
			$check['drop_lat']		= $req['cust_lat'];
			$check['drop_lng']		= $req['cust_lng'];
			$check['order_id']		= $req['order_id'];
			$check['order_ref_id']	= $req['order_ref_id'];
			$del = self::checkdeliveryPartner($check,$order->del_km);
			if(!$del){ return false; } else { $dealer = $del; }
			$res = app()->call('App\Http\Controllers\Api\Rider\\'.ucfirst($dealer).'Controller@createOrder',['request' => request()->merge($req)]);
			if ($res->status() == 200) {
				$response			= $res->getData()->data;
				$order->rider_order	= $response->api_orderid;
				$order->dealer		= $dealer;
				$order->save();
				$logStatus	= ($dealer == 'dunzo') ? DunzoBoyStatus($response->status) : ShadowBoyStatus($response->status);
				$logMessage	= $response->api_orderid.' order id';
				$return	= true;
			} else {
				$log = ['REQUEST' => json_encode($req),'RESPONSE' => $res,'DEALER' => $dealer];
				\DB::table('tbl_http_logger')->insert(array('request'=>'ORDER_'.$req['order_id'].'_'.$dealer,'header'=>json_encode($log)));
				DeliveryRetry($dealer,$req['order_id'],'CREATE_ORDER',$res->getData()->message);
				$logStatus	= 'CREATE_ORDER';
				$logMessage	= $res->getData()->message;
				$return	= false;
			}
			DeliveryLog($dealer,$req['order_id'],$logStatus,$logMessage);
			return $return;
		}
		return false;
	}

	public function checkdeliveryPartner($req,$km,$retry=false)
	{
		$dealer = DunzoOrShadow($km,$retry,$req['order_id']);
		if (!$dealer) {
			$oID	= $req['order_id'];
			$order	= Orderdetail::find('id',$oID);
			request()->merge(['order_id' => $oID, 'status' => 'order_cancelled']);
			self::sendRequestToBoy();
			return false;
		}
		if ($dealer == 'dunzo') request()->merge(['DUNstatus' => 'dunzo']);
		$res	= app()->call('App\Http\Controllers\Api\Rider\\'.ucfirst($dealer).'Controller@getQuote',['request' => $req]);
		if ($res->status() != 200) {
			$log = ['REQUEST' => json_encode($req),'RESPONSE' => $res,'DEALER' => $dealer];
			\DB::table('tbl_http_logger')->insert(array('request'=>'EXCEPTION_'.$dealer,'header'=>json_encode($log)));
			DeliveryRetry($dealer,$req['order_id'],'GET_QUOTE',$res->getData()->message);
			$logMessage	= $res->getData()->message;
			DeliveryLog($dealer,$req['order_id'],'GET_QUOTE',$logMessage);
			if ($retry) {
				$return = false;
			}
			$return = self::checkdeliveryPartner($req,$km,$dealer);
		} else {
			$logMessage	= 'Quote created';
			$return = $dealer;
			DeliveryLog($dealer,$req['order_id'],'GET_QUOTE',$logMessage);
		}
		// if ($dealer)
		// 	DeliveryLog($dealer,$req['order_id'],'GET_QUOTE',$logMessage);
		return $return;
	}

	public function DeliveryPartnerWebhook(Request $request, $dealer)
	{
		Thirdparty($dealer);
		if(isset($request->order_id)){
			$order   = Orderdetail::where('id',$request->order_id)->first();
			$task_id = $order->rider_order;
			if (empty($task_id)) {
				return json_encode('no order');
			}
			if ($dealer == "Dunzo") {
				$json = '{"task_id":"'.$task_id.'","state":"delivered","event_timestamp":1625311751747,"price":200,"total_time":50,"request_timestamp":1625314413506,"contact":"999999991","is_return_task":false}';
				$rider  = 'dunzo';
			} else {
				$json = '{"allot_time":"2017-11-30T09:25:17.000000Z","rider_name":"AmitKumar","sfx_order_id":"'.$task_id.'","client_order_id":"19424482","order_status":"ALLOTTED","rider_contact":"9898989898","rider_latitude":12.343424,"rider_longitude":77.987987987,"track_url":"http://api.shadowfax.in/track/1C3CEC76E35579F1844F346C1D15F603/","pickup_eta":5,"drop_eta":20}';
				$status   = getShadowArray($order->status);
				$request['SFXstatus'] = $status;
				$rider  = 'shadowfax';
			}
			$Data = json_decode($json);
			// print_r($request['SFXstatus']);exit;
			$task = $Data->task_id ?? $Data->sfx_order_id;
			$response = app()->call('App\Http\Controllers\Api\Rider\\'.$dealer.'Controller@postmoveNextStatus',['request' => request()->merge(array('api_orderid'=>$task))]);
		} else {
			if ($dealer == "Dunzo") {
				$task = $request->task_id;	
			} else {
				$task = $request->sfx_order_id;
			}
			$request['api_orderid'] = $task;
		}
		$order = Orderdetail::where('rider_order',$task)->first();
		if (!empty($order)) {
			$order_id	= $order->rider_order;
			$req		= getOrderStatusRequest($order_id);
			unset($request['SFXstatus']);
			$res		= app()->call('App\Http\Controllers\Api\Rider\\'.$dealer.'Controller@getOrderStatus',['request' => request()->merge($req)]);
			if ($res->status() == 200) {
				$response		= $res->getData()->data;
				$req			= new Request;
				$req['order_id']= $order->id;
				$req['status']	= $response->status;
				$logStatus	= ($dealer == 'dunzo') ? DunzoBoyStatus($response->status) : ShadowBoyStatus($response->status);
				$logMessage	= $res->getData()->message;
				if(isset($response->runner)){
					$req['rider']	=  $response->runner;
					$req['dealer']	=  $dealer;
				}
				if (isset($response->cancelled_by)) {
					DeliveryRetry($dealer,$req['order_id'],'CANCELLED',$response->reason);
					$req['cancelled_by']=  $response->cancelled_by;
					$req['reason']		=  $response->reason;
				}
				DeliveryLog($dealer,$req['order_id'],$logStatus,$logMessage);
				return $this->sendRequestToBoy($req);
			}else{
				return $res;
			}
		} else {
			return json_encode('no order');
		}
	}

	public function RazorpayWebhook(Request $request)
	{
		Thirdparty('razor');
		if(isset($request->entity) && $request->entity == "event" && in_array('payment',$request->contains)){
			if(isset($request->payload['payment']['entity']))
			{
				$payment = $request->payload['payment']['entity'];
				if($payment['status'] == 'captured'){
					$ordersuccess = false;
					$order = Order::where('payment_token',$payment['id'])->orwhere('payment_token',$payment['order_id'])->first();
					if(!empty($order)){
						$order->payment_status 	  = 'paid';
						$order->online_pay_status = 'captured';
						$order->payment_token 	  = $payment['id'];
						$order->save();
					}else{
						$aCart	= Cart::where('rz_id',$payment['order_id'])->first();
						if(!empty($aCart)){
							$req 				 = new Request;
							$req['payment_type'] = 'razorpay';
							$req['payment_token']= $payment['id'];
							$req['from']		 = 'web';
							$req['auth_id']		 = $aCart->user_id;
							$req['auth_role']	 = 2;
							$req['webhook']		 = true;
							app()->call('App\Http\Controllers\Api\Order\OrderController@orderData',['request' => $req])->getData();
						}
					}
				}
			}
		}
	}

	public function reviews( Request $request)
	{
		$status	= 200;
		$guard	= ($request->from == 'mobile') ? 'api' : 'web';
		$this->method= $request->method();
		if ($this->role()) {
			if (isset($request->_method) && $request->_method == 'POST') {
				$rules['user_id']	= 'required';
			}
			$auth_id	= (isset($request->_method) && $request->_method == 'POST') ? $request->user_id : auth($guard)->user()->id;
			$role_id	= auth($guard)->user()->role;
            $cmessage	= $rules = $nicenames	= [];
		    if ($this->method == 'POST' || (isset($request->_method) && $request->_method == 'POST')) {
				$rules['rating']	= 'required';
				// $rules['reviews']	= 'required';
				$rules['order_id']	= 'required_without:chef_id|exists:order_detail,id|exist_check:order_detail,where:user_id:=:'.$auth_id.'-where:status:=:completed';
				$rules['vendor_id']	= 'required_if:chef_id,null';
			} elseif ($this->method == 'PATCH') {
				$rules['review_id'] = ['required'];
				$rules['reply']		= ['required'];

				$nicenames['review_id'] = 'Review Id';
				$nicenames['reply']		= 'Reply';
			} elseif ($this->method == 'GET') {
			}
			$validate =$this->validateDatas($request->all(),$rules,$cmessage,$nicenames,$guard);
			if($guard == 'web' && !empty($validate))
			{
			   return \Response::json($response, 422);
			}

			if (!($this->role())) {
				return \Response::json(['message'=> "Only vendors and customers are allowed"],403);
			}
			if ($this->method == 'POST' || (isset($request->_method) && $request->_method == 'POST')) {
				if($request->order_id){ 
					$orderDetail	= Orderdetail::select('res_id','user_id')->where('id',$request->order_id)->first();
					$checkStatus	= Review::where('order_id',$request->order_id)->where('user_id',$auth_id)->first();
				} elseif($request->chef_id){
					$res_id 		 = Restaurants::select('id')->where('vendor_id',$request->chef_id)->first();
					$checkStatus = Review::where('vendor_id',$request->chef_id)->where('user_id',$auth_id)->first();
				}
				if(!$checkStatus){
					$review	= new Review;
					$review->rating		= $request->rating;
					$review->reviews	= !empty($request->reviews) ? $request->reviews : '';
					$review->res_id		= isset($orderDetail) ? $orderDetail->res_id : $res_id->id;
					$review->order_id	= isset($request->order_id) ? $request->order_id : 0;
					$review->vendor_id	= isset($request->vendor_id) ? $request->vendor_id : $request->chef_id;
					$review->user_id	= ($role_id != 2 && (isset($orderDetail))) ?  $orderDetail->user_id  : $auth_id;
					$review->created_by	= ($role_id != 2 && (!isset($orderDetail))) ? 0 : $auth_id;
					$review->save();
					$message	= "Your review was added successfully.It will reflect in chef profile once admin published.";
				} else {
					return \Response::json(['message'=> "You already reviewed this order"],422);
				}
			} elseif ($this->method == 'PATCH') {
				$auth_id	= (isset($request->_method) && $request->_method == 'PATCH') ? $request->user_id : auth($guard)->user()->id;
				$reviews = Review::where('id',request('review_id'))->where('user_id',$auth_id)->first();
				if (empty($reviews)) {
					return \Response::json(['message'=> "You do not have access"],403);
				} else {
					$reviewcheck = Review::where('r_id',request('review_id'))->where('user_id',$auth_id)->first();
					if (empty($reviewcheck) && $reviews->r_id == 0) {
						$review	= new Review;
						$review->rating		= 0;
						$review->res_id		= $reviews->res_id;
						$review->order_id	= $reviews->order_id;
						$review->user_id	= ($role_id != 2) ? $reviews->user_id : $auth_id;
						$review->r_id		= $request->review_id;
						$review->reviews	= $request->reply;
						$review->vendor_id	= $reviews->vendor_id;
						$review->created_by	= $auth_id;
						$review->save();
						$message = 'Your reply was added successfully.It will reflect in your profile once admin published';
					} else {
						return \Response::json(['message'=> "You already replied to this review"],422);
					}
				}
			} elseif ($this->method == 'GET') {
				if ($role_id == 3) {
					$rpage		= 1; $perPage= $this->sub_paginate;
					$reviewinfo	= Chefs::select('id','avatar')->where('id',$auth_id);
					if (isset($request->rpage) && $request->rpage != '' && is_numeric($request->rpage)) {
						$rpage	= $request->rpage;
					}
					$reviewinfo	= $reviewinfo->with('chefratings', function($query) use($perPage, $rpage) {
						$query->paginate($perPage, ['*'], 'rpage', $rpage);
					})->first();
					$reviewinfo->makeHidden('cuisines');
					$reviewinfo->chefratings->makeHidden('res_id');
					$reviewinfo->chefratings->append(['partner_reply'/*,'userinfo','vendorinfo'*/]);
					$message	= 'Reviews data';
				} else {
					$order_id	= $request->order_id;
					$order		= Orderdetail::select('vendor_id')->find($order_id);
					$vendor		= $order->vendor_id;
					$reviewinfo	= Chefs::select('id','name','email','avatar')->addSelect(DB::raw("$order_id as order_id"))->with('getOrders' ,function($qu) use($order_id){$qu->addSelect('s_id','id','vendor_id')->where('id',$order_id);})->with(['chefratingOrder' => function($query) use($order_id,$vendor){
						$query->where('order_id',$order_id)->where('vendor_id',$vendor);
					}])->find($vendor);
					// $reviewinfo	= chef::with('getVendorDetails')->where('order_id',$request->order_id)->where('user_id',$auth_id)->first();
					$message	= 'Reviews data';
				}
				$response['reviews']	= $reviewinfo;
				// echo "<pre>";print_r($reviewinfo);exit;
			}elseif ($this->method == 'PUT') {
				$review = Review::find($request->review_id);
				if(!empty($review)){
					$review->rating  = isset($request->rating) ? $request->rating : '';
					$review->reviews = isset($request->reply) ? $request->reply : $request->reviews;
					if(isset($request->reply)) {
						$review->status  = $request->status;  
					}
					$review->save();
					$message = "Your review updated successfully.";
				} else {
					return \Response::json(['message' => "Your review is not found"],422);
				} 
			} elseif ($this->method == 'DELETE') {
				$message	= "Something went wrong.";
				$review		= Review::where('id',$request->review_id)->delete();
				if ($review) {
					$message = "Your review deleted successfully.";
				}
			}
		} else {
			$status		= 403;
			$message	= "Only vendors are allowed";
		}
		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	public function orderimage(Request $request,$id)
	{
		return imageConvert($id);
	}
}
?>