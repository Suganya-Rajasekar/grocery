<?php

namespace App\Http\Controllers\Front\Checkout;

use Illuminate\Http\Request;
use App\Models\Testimonials;
use App\Models\Category;
use App\Models\Service;
use App\Models\Addon;
use App\Models\Time;
use App\Models\Book;
use App\Models\SiteSetting;
use App\Models\subscription_plans as SubscriptionPlans;
use App\Models\User;
use App\Models\Cart;
use App\Models\Offer;
use App\Models\Restaurants;
use App\Models\Address;
use Illuminate\Http\Response;
use Cookie;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;
use App\Models\Order;
class CheckoutController extends Controller
{
	public function showcheckout()
	{
		$cookie     = (\Session::has('cookie')) ? \Session::get('cookie') : self::cookie();
		$cart       = app()->call('App\Http\Controllers\Api\V1\Emperica\CartController@addCart',['request' => request()->merge(['cookie'=>$cookie])])->getData();
		$useraddr   = app()->call('App\Http\Controllers\Api\Customer\CustomerController@userAddress',['request' => request()->merge([])])->getData();
		$carts      = $cart;
		$previous   = url()->previous();
		$offers     =  app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@AvailableOffers');
		$timeslot = app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@TableDatasAll',['request' => request()->merge(['requestdata'=>'TimeSlot'])])->getData();
        $timeslots  = (object) $timeslot->timeslot;	
		return view('frontend.checkout.checkout',compact('carts','useraddr','previous','offers','timeslots'));
	}

	/* send choose address*/
	public function send_chooseaddress(Request $request)
	{
		$cookie     = (\Session::has('cookie')) ? \Session::get('cookie') : self::cookie();
		$address_id = $request->id;
		$cart       = app()->call('App\Http\Controllers\Api\Emperica\CartController@addCart',[ 'request' => request()->merge(['address_id'=>$address_id,'cookie'=>$cookie])]);
		$carts      = (object) $cart;
		return $carts;
	}

	/* send place order*/
	public function send_placeorder(Request $request)
	{
		$type           = $request->type;
		$orderinsert    = app()->call('App\Http\Controllers\Api\Order\OrderController@orderData',['request' => request()->merge(['payment_type'=>$type])]);
		return $orderinsert;
	}

	public function updateCart(Request $request)
	{
		$cookie		= (\Session::has('cookie')) ? \Session::get('cookie') : self::cookie();
		$ucart_id	= $request->ucart_id;
		$quantity	= $request->quantity;
		$cartdata	= app()->call('App\Http\Controllers\Api\V1\Emperica\CartController@addCart',['request' => request()->merge(['ucart_id'=>$ucart_id, 'quantity'=>$quantity,'cookie'=>$cookie])]);
		if ($request->page == 'checkout') {
			$carts		= $cartdata->getData();
			if($cartdata->getStatusCode() == 200) {
				$previous	= url()->previous();
				$offers		= app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@AvailableOffers');
				$res['cart'] = (string) view('frontend.checkout.showcart',compact('carts','offers','previous'));
			} else {
				$res['message']    = $cartdata->getData()->message;	
			}
			return \Response::json($res,$cartdata->getStatusCode());
		} else {
			if($cartdata->getStatusCode() == 200) {
				$carts  = $cartdata->getData();
				$chefinfo['cart_detail']        = $carts;
				$carts->cart_detail['couponId'] = $carts->couponId;
				$carts->cart_detail['coupon']   = $carts->couponCode;
				$carts->cart_detail['price']    = $carts->price;
				$carts->cart_detail['count']    = $carts->count;
				$chefinfo       = json_decode(json_encode($carts));
				$res['cart']    = (string) view('frontend.details-cart',compact('chefinfo'));
			} else {
				$res['message']    = $cartdata->getData()->message;
			}
			return \Response::json($res,$cartdata->getStatusCode());
		}
		return $cartdata;
	}

	public function deleteCart(Request $request)
	{
		$cookie     = (\Session::has('cookie')) ? \Session::get('cookie') : self::cookie();
		$cartdata   = app()->call('App\Http\Controllers\Api\V1\Emperica\CartController@delCart',['request' => request()->merge(['cookie'=>$cookie])]);
		$carts      = $cartdata->getData();
		if (empty($carts->cart_detail) && $request->function == 'clearcart') {
			return \Redirect::to('checkout');
		} else {
			if ($request->page == 'checkout') {
				$previous   = url()->previous();
				$offers     = app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@AvailableOffers');
				$html = (string) view('frontend.checkout.showcart',compact('carts','offers','previous'));
			} else {
				$chefinfo['cart_detail']        = $carts;
				$carts->cart_detail['count']    = $carts->count;
				$carts->cart_detail['couponId'] = $carts->couponId;
				$carts->cart_detail['coupon']   = $carts->couponCode;
				$carts->cart_detail['price']    = $carts->price;
				$chefinfo       = json_decode(json_encode($carts));
				$html = (string) view('frontend.details-cart',compact('chefinfo'));
			}
			$res['cart'] = $html;
			return \Response::json($res);
		}
	}

	public function payment_success()
	{
		$userId = \Auth::id();
		$recentPlacedOrderDetail = Order::select('orders.id','order_detail.m_id','orders.order_type')
		->leftjoin('order_detail','order_detail.order_id','=','orders.id')
		->where('orders.user_id',$userId)
		->whereRaw('order_detail.created_at >=  NOW()-INTERVAL 10 MINUTE')
		->orderBy('order_detail.id','desc')
		->first();
		$recentOrderId = 0;
		if (!empty($recentPlacedOrderDetail))
			$recentOrderId = $recentPlacedOrderDetail->m_id;
			$order_type    = $recentPlacedOrderDetail->order_type;
		if ($recentOrderId)
			return view('frontend.thankyou', compact('recentOrderId','order_type'));
		else
			return \Redirect::to('/');
	}

	public function userAddress(Request $request)
	{
		$address_id     = '';
		if ($request->address_id != '') {
			$address_id   = $request->address_id; 
		}
		$user       = $this->authCheck();
		$auth_id    = $user['userId'];

		if ($request->address_type == 'home' || $request->address_type == 'office') {
			$getuser_add    = Address::where('user_id', $auth_id)->where('address_type',$request->address_type)->first();
			if (!empty($getuser_add)) {
				$useraddress['address_type']   = 'other';
				Address::where('id',$getuser_add->id)->update($useraddress);
			} 
		}
		$request->address_type = ($request->address_type == "other" && !empty($request->address_type_text)) ? $request->address_type_text : $request->address_type;    
		$address    = app()->call('App\Http\Controllers\Api\Customer\CustomerController@userAddress',['request' => request()->merge(['address' => $request->location,'lat' => $request->a_lat,'lang' => $request->a_lang,'building' => $request->building,'landmark' => $request->landmark,'address_type' => $request->address_type, 'pin_code' => $request->pin_code, 'building' => $request->building, 'area' => $request->area, 'city' => $request->city, 'state' => $request->state, 'address_id' => $request->address_id ])]);
		$newaddress = (object) $address;
		return $newaddress;
	}

	public function deleteUseraddress(Request $request)
	{
		$address_id = '';
		if ($request->address_id != '') {
			$address_id   = $request->address_id; 
		}
		$address    = app()->call('App\Http\Controllers\Api\Customer\CustomerController@userAddress',['request' => request()->merge(['address_id' => $request->address_id ])]);
		$newaddress = (object) $address;
		return $newaddress;
	}

	/* Apply coupon*/
	public function send_coupon(Request $request)
	{
		return app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@Offers');
	}

	public function insertname(Request $request)
	{
		request()->merge(['device'=>'web']);
		$insertname    = app()->call('App\Http\Controllers\Api\AuthController@updateCustomerProfile');
		$result      = (object) $insertname;
		return $result;
	} 

	public function send_wallet(Request $request) 
	{
		$wallet_amt = app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@WalletAmountApply');
		if($wallet_amt->getStatusCode() == 200) {
			$offers     =  app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@AvailableOffers');
			$previous   = url()->previous();
			$timeslot = app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@TableDatasAll',['request' => request()->merge(['requestdata'=>'TimeSlot'])])->getData();
			$carts       = app()->call('App\Http\Controllers\Api\V1\Emperica\CartController@addCart',['request' => request()->merge(['request_for'=> 'wallet'])])->getData();
			$response['cart'] = (string) view('frontend.checkout.showcart',compact('carts','previous','offers','timeslot'));
			$response['used_wallet_amount'] = $carts->used_wallet_amount;
			$status = 200;
		} else {
			$response['message'] = $wallet_amt->getData()->message;
			$status = $wallet_amt->getStatusCode(); 
		}
		return \Response::json($response,$status);
	}
}
