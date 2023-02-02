<?php
namespace App\Http\Controllers\Api\Emperica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth, Mail, DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Cart;
use App\Models\Cartaddon;
use App\Models\Menuitems;
use App\Models\Addon;
use App\Models\Address;
use App\Models\Restaurants;
use Illuminate\Support\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @author : Suganya
 * @return \Illuminate\Http\JsonResponse
 */
class CartController extends Controller
{
	function addCart( Request $request)
	{
		// todo validation
		// $c = implode('|',$Cuisine);
		// $subquery->orWhereRaw('cuisine_type REGEXP("('.$c.')")');
		$this->method= $request->method();
		$user		= $this->authCheck();
		$userId		= $user['userId'];
		$userData	= $user['user'];
		$status		= 200; $updateStatus = '';
		$price		= $count = 1; $rules = [];
		$methods	= ['GET','PATCH','PUT'];
		if (!in_array($this->method, $methods) && empty($userData) && $userId == 0) {
			$rules['cookie']	= 'required';
		}
		if ($this->method == 'POST') {
			$foodData	= Menuitems::find(request('food_id'));
			$rules['food_id']		= 'required|exists:menu_items,id|exist_check:menu_items,where:id:=:'.request('food_id').'-where:status:=:approved';
			if($foodData->food_type != 'ticket') {
				$rules['date']			= ['required', 'date_format:Y-m-d','after_or_equal:'.date('Y-m-d')];
				$rules['time_slot']		= 'required|exist_check:time_slot_management,where:status:=:active';
				$rules['is_preorder']	= 'required|in:yes,no';
				$rules['is_addon']		= 'required|in:yes,no';
				if (request('is_addon') == 'yes') {
					if (is_array(request('addons'))) {
						$addons				= implode('~', request('addons'));
						$rules['addons']	= 'required|array';
						$rules['addons.*']	= 'required|exist_check:menu_items,FIND_IN_SET:'.$addons.':addons-where:id:=:'.request('food_id').'|exist_check:addons,where:type:=:addon-where:status:=:active';
					}
				}
				if (request('is_preorder') == 'yes')
					array_push($rules['date'], 'after:today');
				if (!empty($foodData) && $foodData->unit != '')
					$rules['unit']	= ['required','exist_check:addons,where:type:=:unit-where:status:=:active'];
			}
		} elseif($this->method == 'GET') {
		} elseif ($this->method == 'PATCH') {
		} elseif ($this->method == 'PUT') {
			$rules['address_id'] = 'required';
		}
		$this->validateDatas($request->all(),$rules,[],[]);
		// $cookieID	= (request()->get('cookie')) ? request()->get('cookie') : 0;
		$cookieID	= (!is_null($this->segment) && $this->segment == 'api') ? ((request('cookie')) ? request('cookie') : 0) : ((\Session::has('cookie')) ? \Session::get('cookie') : self::cookie());
		$uCartQuery	= uCartQuery($userId, $cookieID);
		$userCart	= clone ($uCartQuery); $pCart = clone ($uCartQuery);$gCart = clone ($uCartQuery); $ptCart = clone ($uCartQuery); $puCart = clone ($uCartQuery);$chCart = clone ($uCartQuery);
		$userCart	= $userCart->get();

		if (empty($userCart) && (in_array($this->method, $methods))) {
			$response['message']	= 'Your cart is empty';
			return \Response::json($response,422);
		}

		if ($this->method == 'POST') {
			$condition['where']	= ['where','where'];
			$condition['col']	= ['id','vendor_id'];
			$condition['cond']	= ['=','='];
			$condition['value']	= [request('food_id'),$userId];
			$vendorFood	= modelData('Menuitems', $condition);
			if (count($vendorFood) > 0) {
				$response['message']	= "You can not add your own dish into cart";
				return \Response::json($response,422);
			}
			$foodtype  = Menuitems::find($request->food_id,['id','food_type'])->food_type;
			$data	= [];$func	= 'save';$unit = 0;
			$aAddons	= (is_array(request('addons'))) ? request('addons') : [];
			$data['food_id']	= request('food_id');
			if ($userId == 0)
				$data['cookie']	= (!is_null($this->segment) && $this->segment == 'api') ? ((request('cookie')) ? request('cookie') : 0) : ((\Session::has('cookie')) ? \Session::get('cookie') : self::cookie());
			$data['quantity']	= request('quantity');
			$data['is_addon']	= request('is_addon');
			$data['is_preorder']= request('is_preorder');
			$data['date']		= request('date');
			$data['time_slot']	= ($foodtype != "ticket") ? request('time_slot') : '';
			$data['unit']		= request('unit');
			$data['user_id']	= $userId;
			$data['res_id']		= $foodData->restaurant_id;
			$data['is_samedatetime']     = isset($request->is_samedatetime) ? request('is_samedatetime') : 'no';
			$data['samedatetime_from'] = ($request->is_samedatetime == 'yes' && $request->date > date('Y-m-d')) ? 'future' : (($request->is_samedatetime == 'yes' && $request->date == date('Y-m-d')) ? 'today' : '');
			$data['food_type']  = ($foodtype == "ticket") ? $foodtype : 'menuitem';
			if(isset($request->samedatetime_from) && $request->samedatetime_from == 'different') {
				$data['samedatetime_from'] = $request->samedatetime_from;
			}

 			if($userCart->isNotEmpty()){
				$data['deliverdistance']	= $userCart[0]['deliverdistance'];
				$data['delivermins']		= $userCart[0]['delivermins'];
				$data['address_id']			= $userCart[0]['address_id'];
				$data['rz_id']				= $userCart[0]['rz_id'];
			}
			if (isset($request->unit) && null !== $request->unit && $request->unit > 0) {
				$key	= array_search($request->unit, array_column($foodData->unit_detail, 'id'));
				$unit	= $foodData->unit_detail[$key]['price'];
			}
				
			if(!empty($chCart->first())){
				$cart_foodtype =  $chCart->first()->food_type;
				if($foodtype == "ticket") {
					if($cart_foodtype == "menuitem") {
						$response['message']  = "Remove the fooditem from the cart to add ticket";
						$response['foodtype'] = "ticket";
					}
				} else {
					if($cart_foodtype == "ticket") {
						$response['message']  = "Remove the ticket from the cart to add fooditem";
						$response['foodtype'] = "menuitem";
					}
				}
				if(isset($response['foodtype'])) {
					return \Response::json($response,422);
				}
			}
		
			$price	= ($unit > 0) ? $unit : $foodData->price;
			$data['price']	= (request('quantity') * $price);
			if($foodtype == "ticket") {
				$fCart	= $pCart->where('food_id', request('food_id'))->first();
			} else {	
				$fCart	= $pCart->where('food_id', request('food_id'))->where('date',request('date'))->where('time_slot',request('time_slot'))->first();
			}
			
			if($request->is_samedatetime == 'yes' && $request->date == date('Y-m-d')) {
				$prep_time = Menuitems::find($request->food_id,['id','preparation_time'])->preparation_time;
				if($prep_time == 'tomorrow'){
					$response['message'] = "This chef is not available for the date and timeslot that you have chosen earlier. Please try another chef.";
					$response['status'] = 422;
					return \Response::json($response,422);
				}
			}
			if (!empty($fCart)) {
				if (request('quantity') > 0) {					
					if ($fCart->is_addon != $request->is_addon) {
						$custid		= request('addons');
						sort($custid);
						$customData	= Cartaddon::select(\DB::raw("group_concat(addon_id) as catIds"))->where('cart_id',$fCart->id)->groupBy("cart_id")->having('catIds', '=', implode(',', $custid))->first();
						if(!empty($customData)) {
							$fCart->increment('quantity',$request->quantity);
							$fCart->increment('price',$data['price']);
							$fCart->save();
							// $fCart->fill($data)->save();
							$cartDetail	= Cart::find($fCart->id);
							$func		= 'update';
						} else {
							$cartDetail	= Cart::create($data);
						}
					} else {
						if($fCart->unit != $request->unit) {
							$cartDetail	= Cart::create($data);	
						} else {
							$fCart->increment('quantity',$request->quantity);
							$fCart->increment('price',$data['price']);
							$fCart->save();
							// $fCart->fill($data)->save();
							$cartDetail	= Cart::find($fCart->id);
						}
					}
				} else {
					Cart::destroy($fCart->id);
				}
			} else {
				if (request('quantity') > 0)
					$cartDetail	= Cart::create($data);
			}
			if (request('is_addon') == 'yes') {
				if (request('quantity') > 0) {
					$addonData = array();
					if (is_array(request('addons')) && !empty($aAddons)) {
						foreach ($aAddons as $key => $value) {
							$maddon	= Menuitems::where('id', request('food_id'))->whereRaw('FIND_IN_SET("'.$value.'",addons)')->first();
							if (!empty($maddon)) {
								$addon	= Addon::find($value,['id','price']);
								$addonData['cart_id']	= $cartDetail->id;
								$addonData['addon_id']	= $value;
								$addonData['quantity']	= request('quantity');
								$addonData['price']		= $addon->price;
								if ($func != 'update') {
									$cartaddon	= Cartaddon::create($addonData);
									$cartDetail->increment('price',$addon->price);
								}
							}
						}
						if ($func == 'update') {
							$cartaddon	= Cartaddon::where('cart_id', $cartDetail->id)->update(['quantity'=>request('quantity')]);
						}
					}
				} else {
					if ($fCart) Cartaddon::where('cart_id',$fCart->id)->delete();
				}
			}
			if (!empty($cartDetail))
				self::offerCalc($cartDetail, $userId, $cookieID);
			$message	= " ";
			$message	.= request('quantity') > 0 ? "Added to " : "Removed from ";
			$message	.= " cart";
		} elseif($this->method == 'GET') {
			$message	= 'View Cart';
		} elseif ($this->method == 'PATCH') {
			if (request('ucart_id') > 0) {
				$uCart = $ptCart->where('id',request('ucart_id'))->with('getcoupon')->first();
				if (!empty($uCart)) {
					if (request('quantity') > 0) {
						$ucartFood	= Menuitems::find($uCart->food_id);
						$fprice		= $addonCart = $atotal = 0;
						if ($uCart->unit != 0) {
							$units	= json_decode($ucartFood->unit);
							foreach ($units as $key => $value) {
								if ($value->unit == $uCart->unit) {
									$fprice = $value->price;
								}
							}
						} else {
							$fprice = $ucartFood->price;
						}
						if ($uCart->is_addon == 'yes') {
							$addonCart	= Cartaddon::where('cart_id', $uCart->id)->sum('price');
						}
						$atotal	= $addonCart + (request('quantity') * $fprice);
						$arr	= ['quantity' => request('quantity'), 'price' => $atotal];
						$uCart->fill(['quantity' => request('quantity')])->save();
						Cart::where('id', request('ucart_id'))->update($arr);
					} else {
						$updateStatus = 'cleared';
						Cart::destroy($uCart->id);
						Cartaddon::where('cart_id', request('ucart_id'))->delete();
					}
					self::offerCalc($uCart, $userId, $cookieID);
					$message	= 'Cart updated successfully';
				} else {
					$status		= 422;
					$message	= 'Cart data does not exists';
				}
			} elseif (isset($request->function)) {
				$status = 422; $message = "Unprocessable entry";
				if($request->function == "datetime_edit") {
					if($request->action == "menu_edit") {
						$uCartQuery = $uCartQuery->where('id',$request->cart_id);	
					}
					$cartData = $uCartQuery->where('res_id',$request->res_id)->where('date',$request->old_date)->where('time_slot',$request->old_timeslot)->get();
					foreach($cartData as $key=>$value) {
						$value->date      = $request->new_date;
						$value->time_slot = $request->new_timeslot;
						$value->save();
					}
					$status  = 200;
					$message = "Delivery date and timeslot changed successfully."; 
				}
			}
		} elseif ($this->method == 'PUT') {
			foreach($userCart as $val) {
				$resInfo	= Restaurants::find($val->res_id);
				$selAddress	= Address::find(request('address_id'));
				$calculate['type']	= 'coordinates';
				$calculate['lat1']	= $resInfo->latitude;
				$calculate['long1']	= $resInfo->longitude;
				$calculate['lat2']	= $selAddress->lat;
				$calculate['long2']	= $selAddress->lang;
				$distCheck	= calculate_distance($calculate);
				if($distCheck['apiStatus'] != 'OK'){
					$response['message']	= "Selected Address is not valid";
					return \Response::json($response,422);
				}
				if($distCheck['total_km'] > getMaxDistance()){
					$response['message']	= "Selected address is far away from the chef";
					return \Response::json($response,422);
				}
		    }
			$puCart->update(['address_id'=>request('address_id'),'deliverdistance'=>$distCheck['total_km'],'delivermins'=>$distCheck['durationmin']]);
			$message = 'Cart updated successfully';
		}
		$response['DelCharge']	= 0;
		$resTax	= 0;
		$response['package_charge'] = 0;
		if (in_array($this->method, $methods)) {
			$userData	= self::cartData(uCartQuery($userId, $cookieID));
			if(count($userCart) > 0 && isset($userData['cartData'][0])){
				$resTax	= $userData['cartData'][0]['taxTotal'];
			}
			$response['type'] = isset($userData['cartData'][0]) ? $userData['cartData'][0]['vendor_info']->type : '';
			$status	= ($status != 200 || $updateStatus != '') ? $status : $userData['status'];
			$response['tax']				= (float) $resTax;
			$response['couponId']			= $userData['couponId'];
			$response['couponCode']			= $userData['couponCode'];
			$response['couponValue']		= $userData['couponValue'];
			$response['cart_detail']		= $userData['cartData'];
			$response['selectedAdress']		= $userData['selectedAdress'];
			$response['subOrder_Count']		= $userData['subOrder_Count'];
			$response['razorKey']			= \Config::get('razorpay')['RAZORPAY_KEY'];
			$response['DelCharge']			= ($userId == 235 || $userId == 274 || (!empty($response['cart_detail']) && $response['cart_detail'][0]['vendor_info']->type == "event")) ? 0 : $response['subOrder_Count'] * getDelCharge();
			$response['package_charge']		= $userData['packagecharge'];
			$response['DelChargePerOrder']	= ($userId == 235 || $userId == 274 || (!empty($response['cart_detail']) && $response['cart_detail'][0]['vendor_info']->type == "event")) ? 0 : (double) getDelCharge();
		}
		$uCart	= uCartQuery($userId, $cookieID);
		$ucart	= clone ($uCart);
		$total_coupon_value = 0;
		$priceTotal	= $ucart->sum('price');
		$response['cartTot'] = $priceTotal;
		$response['uCart']   = $uCart->first();
		$total_coupon_value  = isset($response['couponValue']) ? $response['couponValue'] : 0;
		// $total_coupon_value = $ucart->sum('total_coupon_value');
		if(!empty($ucart->first())) {
			$response['couponPrice']	=  $ucart->first()->total_coupon_value;
		}
		$response['userdetails']= ($userId > 0) ? User::find($userId,['id','name','email','mobile']) : (object) [];
		$response['price']		= ($priceTotal - $total_coupon_value) + $response['DelCharge'] + $resTax + $response['package_charge'];
		$response['count']		= $uCart->count();
		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	function offerCalc($cur_update, $userId, $cookieID)
	{
		if (!empty($cur_update)) {
			$timeslot	= $cur_update->time_slot;
			$date		= $cur_update->date;
			$price		= $cur_update->price;
			$vendor_id	= Restaurants::find($cur_update->res_id,['id','vendor_id'])->vendor_id;
			$uCDataprice= chefTotalPrice($cur_update->user_id,$vendor_id,'',$timeslot,$date);
			$uCartQuery = uCartQuery($userId, $cookieID);
			// \DB::enableQueryLog();
			$applyCoupon	= clone ($uCartQuery);
			$applyCoupon	= $applyCoupon->whereNotIn('coupon_id',[0])->with('getcoupon')->first();
			$applyCoupon	= !is_null($applyCoupon) ? $applyCoupon : '';
			// echo "<pre>";
			// print_r($applyCoupon->getcoupon);
			// print_r(\DB::getQueryLog($applyCoupon));
			// print_r($applyCoupon);
			// exit;
			if ($cur_update->coupon_id != 0) { 
				$total_coupon_value = getPromoCal($cur_update->coupon_id,$uCDataprice);
				// print_r($cur_update->coupon_id);echo "<bR>" ;print_r($uCDataprice);exit;
				if ($uCDataprice <= $cur_update->getcoupon->min_order_value) {
					$upArr	= ['coupon_id' => 0,'total_coupon_value' => 0];
				} else {
					$upArr	= ['total_coupon_value' => $total_coupon_value];
				}
				$uCartQuery->where(['res_id' => $cur_update->res_id,'time_slot' => $timeslot,'date' => $date])->update($upArr);
			} else if ($cur_update->coupon_id == 0 && $applyCoupon != '') {
				if ($uCDataprice >= $applyCoupon->getcoupon->min_order_value) {
					$promocheck			= chefPromos($applyCoupon->coupon_id);
					$total_coupon_value	= getPromoCal($applyCoupon->coupon_id,$uCDataprice);
					if ($promocheck['apply_status'] == false) {
						$coupon_id		= $total_coupon = 0;
						if (in_array($vendor_id,$promocheck['chef_ids'])/* === true*/) {
							$coupon_id		= $applyCoupon->coupon_id;
							$total_coupon	= $total_coupon_value; 
						}
					} else if($promocheck['apply_status'] == true) {
						$coupon_id		= $applyCoupon->coupon_id;
						$total_coupon	= $total_coupon_value;
					}
					$uCartQuery->where(['res_id' => $cur_update->res_id,'time_slot' => $timeslot,'date' => $date])->update(['coupon_id' => $coupon_id,'total_coupon_value' => $total_coupon]);
				}
			}
		}
	}

	function delCart( Request $request)
	{
		$user		= $this->authCheck();
		$userId		= $user['userId'];
		$userData	= $user['user'];
		$status		= 200; $updateStatus = '';
		$price		= $count = 1;$rules = [];
		$rules['function']	= 'required|in:clearcart,removecart,removedish,removeaddon';
		if (empty($userData) && $userId == 0) {
			$rules['cookie']	= 'required';
		}
		$this->validateDatas($request->all(),$rules,[],[]);

		// $cookieID	= (request()->get('cookie')) ? request()->get('cookie') : 0;
		$cookieID	= (!is_null($this->segment) && $this->segment == 'api') ? ((request('cookie')) ? request('cookie') : 0) : ((\Session::has('cookie')) ? \Session::get('cookie') : self::cookie());
		$uCartQuery	= uCartQuery($userId, $cookieID);
		$userCart	= clone ($uCartQuery); $cCart = clone ($uCartQuery);$rCart = clone ($uCartQuery); $rdCart = clone ($uCartQuery); $raCart = clone ($uCartQuery);
		$userCart	= clone ($uCartQuery);
		$userCart	= $userCart->get();

		if ($userCart->isEmpty()) {
			$response['message']	= 'Your cart is empty';
			return \Response::json($response,200);
		}

		if (request('function') == 'clearcart') {
			$updateStatus = 'cleared';
			$cCart->delete();
			$message	= 'Cart cleared successfully';
		} elseif (request('function') == 'removecart') {
			$updateStatus = 'removed';
			$rcCart = $rCart->where('res_id',request('res_id'))->where('date',request('date'))->where('time_slot',request('time_slot'));
			$rcCart = $rcCart->first();
			if (!empty($rcCart)) {
				Cartaddon::where('id', $rcCart->id)->delete();
			}
			$rCart->delete();
			$message	= 'Cart data removed successfully';
		} elseif (request('function') == 'removedish') {
			$rdCart->where('food_id',request('food_id'))->where('date',request('date'))->where('time_slot',request('time_slot'));
			$rDCart = $rdCart->first();
			if (!empty($rDCart)) {
				Cartaddon::where('id', $rDCart->id)->delete();
			}
			$rdCart->delete();
			self::offerCalc($rDCart, $userId, $cookieID);
			$message	= 'Dish was removed from cart';
		} elseif (request('function') == 'removeaddon') {
			$cAddon		= Cartaddon::where('id', request('addon_id'));
			$cAddoncopy = clone ($cAddon);
			$addonData	= $cAddon->first();
			if (empty($addonData)) {
				return \Response::json(['message' => "Addon not exists in your cart"],422);
			}
			$cAddoncopy->delete();
			$raCart		= $raCart->where('id',$addonData->cart_id);
			$raCartcopy = clone ($raCart);
			$raCartcopy = $raCartcopy->first();
			$raCart->update(['price' => ($raCartcopy->price - $addonData->price)]); 
			self::offerCalc($raCartcopy, $userId, $cookieID);
			$message	= 'Addon was removed from cart';
		}
		$response['DelCharge'] = 0;
		$response['tax'] = 0;
		// if (in_array($this->method, $methods)) {
		$userData	= self::cartData($uCartQuery);
		$status		= ($status != 200 || $updateStatus != '') ? $status : $userData['status'];
		if(count($userCart) > 0){
			$response['tax']		= $userCart[0]['vendor_info']->tax;	
		}
		$response['couponId']		= $userData['couponId'];
		$response['couponValue']	= $userData['couponValue'];
		$response['couponCode']		= $userData['couponCode'];
		$response['cart_detail']	= $userData['cartData'];
		$response['selectedAdress']	= $userData['selectedAdress'];
		$response['subOrder_Count']	= $userData['subOrder_Count'];
		$response['DelCharge']		= $response['subOrder_Count'] * getDelCharge();
		$response['DelChargePerOrder'] = getDelCharge();
		// }
		$uCart	= uCartQuery($userId, $cookieID);
		$ucart	= clone ($uCart);
		$total_coupon_value = 0;
		$priceTotal = $ucart->sum('price');
		if(!empty($ucart->first())){
			$total_coupon_value	= $ucart->first()->total_coupon_value;
		}
		if($response['tax'] > 0) {
			$response['tax']	= $priceTotal * ( $response['tax'] / 100 );
		}
		$response['price']		= ($priceTotal - $total_coupon_value)+ $response['DelCharge'];
		$response['couponPrice']= $total_coupon_value;
		$response['count']		= $uCart->count();
		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	public function cartData($userCart,$from='addCart',$restaurant_id=0)
	{
		$message	= 'Your cart is empty';
		$status		= 422;
		$cartData	= [];
		$selectAdd	= null;
		$checkCart	= clone ($userCart);
		$checkAdd	= clone ($userCart);
		$couponData	= clone ($userCart);
		$checkCart	= $checkCart->get();
		$taxTotal	= 0;
		$total_coupon_value = 0;
		$couponData	= $couponData->whereNotIn('coupon_id',[0])->with('getcoupon')->first();
		if (!$checkCart->isEmpty()) {
			$address_id = $checkAdd->first()->address_id;
			if ($address_id > 0) {
				$selectAdd = Address::find($address_id);
			}
			$uCart		= clone ($userCart);
			$groupBy	= ['res_id'];
			if ($from == 'addCart') {
				array_push($groupBy, 'date');
				array_push($groupBy, 'time_slot');
			} else {
				$uCart	= $uCart->where('res_id',$restaurant_id);
			}
			$uCart		= $uCart->groupBy($groupBy)->orderBy('id','DESC')->get();
			if (!empty($uCart)) {
				$tcouponValue = 0;
				$tTotal = 0;
				$packagecharge = 0;
				foreach ($uCart as $rkey => $rvalue) {
					$rgLCart	= clone ($userCart);
					$rgLCart	= $rgLCart->where('res_id',$rvalue->res_id);
					if ($from == 'addCart') {
						$rgLCart= $rgLCart->where('date',$rvalue->date)->where('time_slot',$rvalue->time_slot);
					}
					AppendMaster('Restaurants',['Chef']);
					$rgLCart	= $rgLCart->with(['getcoupon','addon_item','vendor_info' =>function($query) {
						$query->commonselect()->addSelect('fssai','package_charge','type','event_datetime','adrs_line_1')/*->with('vendor_info')*/;
					}])
					->get()->append(['food_name','food_price','time_s','eventavailable']);
					$cartTax = clone ($rgLCart);
					$packagecharge += $rgLCart[0]->vendor_info->package_charge;
					if($rgLCart[0]->vendor_info->tax > 0){
						$tTotal = $cartTax->sum('price') * ($rgLCart[0]->vendor_info->tax/100);
						if($cartTax[0]->total_coupon_value != 0) {
							$promo_discount = $cartTax->sum('price') - $cartTax[0]->total_coupon_value;
							$tTotal = $promo_discount * ($rgLCart[0]->vendor_info->tax/100);
						}
						$taxTotal += $tTotal;
					}
					if ($from == 'addCart') {
						$cartData[$rkey]['vendor_info']		= $rgLCart[0]->vendor_info;
						$cartData[$rkey]['food_items']		= $rgLCart;
						$cartData[$rkey]['food_items'][0]['tax']	= $tTotal;
						$tcouponValue	+= $rgLCart[0]->total_coupon_value;
					} else {
						$returncart			= clone ($userCart);
						if (request('from') != 'mobile') {
							$returncart	= $returncart->where('res_id',$rvalue->res_id);
							$userCart	= $userCart->where('res_id',$rvalue->res_id);
						}
						$cartData['price']	= $returncart->sum('price');
						$cartData['count']	= $userCart->count();
						$cartData['food_items'] = $rgLCart;
					}
					$rgLCart	= $rgLCart->map(function ($result){
						$result->makeHidden('vendor_info');
					});
				}
				$status		= 200;
				$message	= 'View cart';
			}
			$cartData[0]['taxTotal']			= $taxTotal;
		}
		$return['couponCode']	    = (!empty($couponData)) ? $couponData->getcoupon->promo_code : '';
		$return['couponId']		    = (!empty($couponData)) ? $couponData->getcoupon->id : 0;
		$return['couponValue']		= (!empty($couponData)) ? $tcouponValue : 0;
		$return['packagecharge']	= (!empty($packagecharge)) ? $packagecharge : 0;
		$return['message']			= $message;
		$return['status']			= $status;
		$return['selectedAdress']	= $selectAdd;
		// $return['lastChef']			= $uCart[0]->res_id;
		$return['cartData']			= $cartData;
		$return['subOrder_Count']	= count($cartData);
		//dd($return);
		return $return;
	}

	public function cartCount( Request $request)
	{
		$status		= 200;
		$cookieID	= (!is_null($this->segment) && $this->segment == 'api') ? ((request('cookie')) ? request('cookie') : 0) : ((\Session::has('cookie')) ? \Session::get('cookie') : self::cookie());
		$user		= $this->authCheck();
		$userId		= $user['userId'];
		$uCartQuery	= uCartQuery($userId, $cookieID);
		$ucart		= clone ($uCartQuery);$unavailablecart = clone ($uCartQuery); 
		$response['count']	= (int) $ucart->sum('quantity');
		// $response['cart']	= $uCartQuery->first();
		if($response['count'] != 0) {		
			$cart_unavailable = false;
			foreach($unavailablecart->get() as $key => $value) {
				if($value->ordertimeslotavailable['status'] == 0){
					$cart_unavailable = true;
				}
			}
		}
		$cart = $uCartQuery->select('date','time_slot','is_preorder','is_samedatetime','samedatetime_from')->where('samedatetime_from','!=','different')->orderby('id','DESC')->first();
		if($cart) {
			$cart->makeHidden('timeslot_available','ordertimeslotavailable');
		}
		if(isset($cart_unavailable)) {
			$cart->cart_unavailable = $cart_unavailable;
		}

		$response['cart_datetimeslot']    = !is_null($cart) ? $cart : ['date' => '','time_slot' => 0,'is_preorder' => '','is_samedatetime' => 'no','cart_unavailable' => false];
		return \Response::json($response,$status);
	}
}
?>