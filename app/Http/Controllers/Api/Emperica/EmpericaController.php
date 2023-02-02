<?php
namespace App\Http\Controllers\Api\Emperica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App;
use App\Models\Countries;
use App\Models\Cuisines;
use App\Models\Commondatas;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Orderdetail;
use App\Models\Order;
use Illuminate\Support\Facades\Hash; 
use Auth, Mail, DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Offer;
use App\Models\Chefs;
use App\Models\Location;
use App\Models\Restaurants;
use App\Events\OtpUserEvent;

/**
 * @author : Suganya
 * @return \Illuminate\Http\JsonResponse
 */
class EmpericaController extends Controller
{
	/*public function __construct()
	{
		// $request->request->add(['from' => 'mobile']);
	}*/

	public function userBasicDatas()
	{
		return ['id', 'role_id', 'name', 'email', 'password', 'location_id', 'mobile', 'avatar', 'cuisine_type', 'status', 'email_verified_at'];
	}

	// Registration
	public function Register( Request $request)
	{
		$adminCheck = false;
		if($request->device == 'web' && \Auth::check() && \Auth::user()->role_id == 1){
			$adminCheck = true;
		}
		$rules['role_id']	= ['required', 'max:4', 'min:1'];
		$rules['name']		= ['required', 'string', 'max:255'];
		$rules['email']		= ['required', 'string', 'email', 'max:255', 'unique:users'];
		$rules['mobile']	= ['required','numeric', 'unique:users'];
		$rules['country']	= ['required','numeric'];
		if($adminCheck) {
			$rules['password']	= ['required', 'min:6'];
		} else {
			$rules['password']	= ['required', 'min:6','required_with:cpassword', 'same:cpassword'];
			$rules['cpassword']	= ['required', 'min:6'];
		}
		
		$rules['device']	= 'required|in:android,ios,web';

		if($request->role_id == 3) {
			$rules['cuisine_type']		= ['required','array'];
			$rules['cuisine_type.*']	= 'required|exists:cuisines,id';
		}

		$nicenames['role_id']		= 'Role id';
		$nicenames['name']			= 'Name';
		$nicenames['email']			= 'Email';
		$nicenames['country']		= 'Country';
		$nicenames['mobile']		= 'Mobile';
		$nicenames['password']		= 'Password';
		$nicenames['cpassword']		= 'Cpassword';
		$nicenames['cuisine_type']	= 'Cuisine type';

		// validates other request data
		$this->validateDatas($request->all(),$rules,[],$nicenames);
		$name		= $request->name;
		$country	= $request->country;
		if($request->role_id == 3 || $request->role_id == 2) {
			
			$random_no	= md5(rand());
			$slug		= '';
			$createArr = [
				'name'			=> $name,
				'role_id'		=> $request->role_id,
				'email'			=> $request->email,
				'location_id'	=> $request->country,
				'mobile'		=> $request->mobile,
				'device'		=> $request->device,
				'password'		=> Hash::make($request->password),
				'status'			=> "approved",
				'remember_token'	=> $random_no,
				'mobile_otp'		=> '1234',
				'log_status'		=> 'register',
			];
			if($request->role_id == 3) {
				$slug	= Str::slug($name);
				if ($slug == '') {
					$slug	= str_replace(' ', '_', $name);
				}
				if($request->device == 'web' && \Auth::check() && \Auth::user()->role_id == 1){
					$createArr['status']	= $request->status;
					$adminCheck = true;
				} else {
					$createArr['status']	= 'pending';
				}
				$createArr['business_name']	= $name;
				$createArr['cuisine_type']	= implode(',', $request->cuisine_type);

			} else {
				$createArr['status']		= 'approved';
				if($request->input('device_id') !== null && $request->device_id != ''){
					// updateCartToUser($request->device_id);
				}
			}
			$createArr['slug']	= $slug;
			$createArr['files']	= '';

			$user	= User::create($createArr);

			$maildata['link']			= URL::to('/verification/'.$user->id.'/'.$random_no);
			$maildata['username']		= $user->name;
			$maildata['request_from']	= 'register';
			$user_email					= $user->email;
			$from			= env('MAIL_FROM_ADDRESS');
			$subject		= CNF_APPNAME.' - Email verification';
			$view			= 'emails.member.verificationmail';
			$this->sendemail($view,$maildata,$from,$user_email,$subject);
			$status	= 200;
			$response['message']= ($request->role_id == 3) ? "Thanks for registering. We will review your account and get back to you soon." : "Registered successfully please verify otp received on your mobile.";
			if($adminCheck){
				$response['uid'] = $user->id;
			}
		} else {
			$status	= 422;
			$response['message']= "Your Role Id was incorrect";
		}
		return \Response::json($response,$status);
	}

	// Login
	public function Login( Request $request)
	{
		$from	= 'otp';
		$rules['role_id']		= 'required|in:2,3';
		$rules['email']			= 'required_without:mobile|exists:users';
		if ($request->guestlogin !== null && $request->guestlogin == 'yes') {
			$rules['location_id']	= 'required|numeric';
			$rules['mobile']		= 'required';
		} else {
			$rules['mobile']		= 'required_without:email|exists:users';
		}
		if ($request->role_id == 3 || (isset($request->email) && $request->email != '')) {
			$rules['password']		= 'required';
			$rules['device']		= 'required|in:android,ios,web';
			if ($request->device != 'web')
				$rules['mobile_token']	= 'required';
			$from	= 'data';
		}
		$this->validateDatas($request->all(),$rules);

		$user = User::select($this->userBasicDatas());
		if (isset($request->email) && $request->email != '') {
			$user = $user->where('email', $request->email)->first();
		} else {
			$user = $user->where('mobile', $request->mobile)->where('mobile', '!=', 0)->first();
		} 

		if ($from == 'data') {
			$data = clone ($user);
		}

		if (!empty($user)) {
			if ($request->guestlogin !== null && $request->guestlogin == 'yes') {
				$from	= 'guestaccount';
				$response['message']	= 'You already have an account please login.';
				return \Response::json($response,422);
			} else {
				if (isset($request->email) && $request->email != '' && (empty($user) || ! Hash::check($request->password, $user->password))) {
					$response['message']	= 'The provided credentials are incorrect.';
					return \Response::json($response,401);
				}

				if(($user->role_id == 3) && (is_null($user->email_verified_at) || $user->email_verified_at != '') && $user->status != 'approved') {
					$response['message']	= 'Your account is not approved yet.';
					return \Response::json($response,422);
				}
			}

			if ($from != 'guestaccount' && $from == 'otp') {
				$this->generateOTP($request->mobile);
			} else {
				$user->device		= $request->device;
				$user->log_status	= 'login';
				$user->mobile_token	= $request->mobile_token;
				$user->save();
			}

			if ($user->role_id == 2 && $request->input('device_id') !== null && $request->device_id != '') {
				// updateCartToUser($request->device_id);
			}

			if ($from == 'data') {
				if ($request->device == 'web') {
					$login = \Auth::loginUsingId($user->id);
				} else {
					$token	= $user->createToken('token')->plainTextToken;
					$token	= explode('|', $token);
					$response['token']	= $token[1];
					$response['info']	= $data;
				}
			}
			$response['message']	= ($from == 'otp') ? "Please enter the OTP send to your mobile number." : "You are logged in successfully." ;
		} else {
			if ($request->guestlogin !== null && $request->guestlogin == 'yes') {
				$createArr = [
					'name'			=> '',
					'email'			=> $request->mobile,
					'role_id'		=> 2,
					'location_id'	=> $request->location_id,
					'mobile'		=> $request->mobile,
					'status'		=> "approved",
					'remember_token'=> md5(rand()),
					'mobile_otp'	=> '1234',
				];
				$user	= User::create($createArr);
				$response['message']	= "Please enter the OTP send to your mobile number.";
			} else {
				$response['message']	= "Your log in credentials are incorrect.";
			}
		}
		return \Response::json($response,200);
	}

	public function verifyOTP( Request $request)
	{
		$from	= ($request->input('from') !== null) ? $request->from : 'api';
		$rules['mobile']		= ($request->guestlogin !== null && $request->guestlogin == 'yes') ? 'required' : 'required|exists:users';
		$rules['device']		= 'required|in:android,ios,web';
		if ($request->device != 'web')
			$rules['mobile_token']	= 'required';
		$rules['otp']	= 'required|numeric|min:4';
		$this->validateDatas($request->all(),$rules);

		$user = User::select($this->userBasicDatas())->where('mobile', $request->mobile)->where('mobile', '!=', 0)->where('mobile_otp', $request->otp)->first();
		if (!empty($user)) {
			$message	= 'success';
			$status		= 200;
			if ($user->role_id == 3) {
				if ((is_null($user->email_verified_at) || $user->email_verified_at != '') && $user->status != 'approved') {
					$status		= 422;
					$message	= 'Your account is not approved yet.';
				}
			} elseif ($request->device != 'web' && $user->role_id == 1) {
				$status		= 422;
				$message	= "Admin cannot login through this.";
			}
			if ($message == 'success') {
				$user->device		= $request->device;
				$user->log_status	= 'login';
				$user->save();
				$userData	= User::find($user->id,$this->userBasicDatas());
				if($request->device == 'web') {
					$login = Auth::loginUsingId($userData->id,true);
					$response['csrf_token'] = csrf_token();
				} else {
					$token	= $userData->createToken('token')->plainTextToken;
					$token	= explode('|', $token);
					$message= "OTP matched";
					$response['token']		= $token[1];
					$response['info']		= $userData;
				}
			}
		} else {
			$status	= 422;
			$message= "The OTP you entered is invalid.Please enter correct OTP";
		}
		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	public function generateOTP($mobileNum='')
	{
		if ($mobileNum != '') {
			$user = User::where('mobile', $mobileNum)->where('mobile', '!=', 0)->first();
			if (!empty($user)) {
				$user->mobile_otp = '1234';
				$user->save();
			}
		}
	}

	function forgetPasswordrequest(Request $request)
	{
		$rules['email'] = 'required';
		$rules['device']= 'required|in:android,ios,web';
		$this->validateDatas($request->all(),$rules);

		$status	= 422;
		$exist	= false;
		$user	= User::where('email',$request->email)->first();
		if(!empty($user)){
			$exist		= true;
			$update_user= User::find($user->id);
		}

		if($exist){
			$update_user->device		= $request->device;
			$update_user->reminder		= getVerifyCode();
			$update_user->updated_at	= date('Y-m-d H:i:s');
			$update_user->save();

			$data['user']	= $update_user;
			$data['from']	= 'App';
			$from 			= env('MAIL_FROM_ADDRESS');
			$to				= $request->email;
			$subject		= CNF_APPNAME.' Forget Password';
			$view			= 'emails.member.forgetPasswordRequest';
			$this->sendemail($view,$data,$from,$to,$subject);

			$status	= 200;
			$response['message']	= 'Code sent to your email address';
		} else {
			$response['message']	= 'Cant find user';
		}
		return \Response::json($response,$status);
	}

	function resetPassword(Request $request)
	{
		$rules['email']		= 'required';
		$rules['code']		= 'required';
		$rules['password']	= 'required|min:6|required_with:cpassword|same:cpassword';
		$rules['cpassword']	= 'required|min:6';
		$rules['device']	= 'required|in:android,ios,web';

		$this->validateDatas($request->all(),$rules);
		$status = 422;
		$exist	= false;
		$user	= User::where('email',$request->email)->where('reminder',$request->code)->first();
		if(!empty($user)){
			$exist		= true;
			$update_user= User::find($user->id);
		}
		if ($update_user->role_id != 2 && $update_user->role_id != 3) {
			$status		= 422;
			$response['message']	= "This type of user cannot able to reset their password from here";
			return \Response::json($response,$status);
		}
		if($exist){
			$update_user->device		= $request->device;
			$update_user->password		= Hash::make($request->password);
			$update_user->reminder		= '';
			$update_user->updated_at	= date('Y-m-d H:i:s');
			$update_user->save();

			if ($request->device == 'web') {
				$role_id = ($update_user->role_id == 2) ? 'chef/login' : 'user/login' ;
				$response['rurl']	= 'Password changed successfully';
			}

			$status	= 200;
			$response['message']	= 'Password changed successfully';
		} else {
			$response['message']	=  'Invalid code';
		}
		return \Response::json($response,$status);
	}

	function socialLogin(Request $request)
	{
		$rules['social_id']		= 'required';
		$rules['provider']		= 'required|in:google,facebook,apple';
		$rules['role_id']		= 'required|in:2';
		$rules['device']		= 'required|in:android,ios,web';
		if($request->device != 'web') {
			$rules['mobile_token']	= 'required';
		}
		$this->validateDatas($request->all(),$rules);

		$provider	= $request->provider;
		if ($provider == 'google') {
			$field = 'google_id';
		} elseif ($provider == 'facebook') {
			$field = 'facebook_id';
		} else {
			$field = 'apple_id';
		}

		$user		= User::where($field,$request->social_id)->first();
		$proceed	= false;
		$email		= $request->input('email') !== null ? $request->email : '';
		$message	= 'You are logged in successfully.';
		$status		= 200;
		if (!empty($user)) {
			$userId		= $user->id;
			if ($user->role_id != 2) {
				$status		= 422;
				$message	= 'You cannot use social login';
			} else {
				if ($email == '' || $email == $user->email) {
					$aUser		= User::find($user->id);
					if ($field != 'apple') {
						$aUser->name    = $request->name;
						if ($email != '') {
							$aUser->email	= $email;
						}
						$aUser->avatar	= $request->avatar;
					}
				}
			}
		} else {
			$proceed = $newUser	= true;
			if ($email != '') {
				$aUser = User::where('email',$email)->first();
				if(!empty($aUser)){
					$newUser = false;
					if ($aUser->role_id != 2) {
						$status		= 422;
						$proceed	= false;
						$message	= 'You cannot use social login';
					}
				}
			}
			if ($newUser) {
				$aUser		= new User;
				$aUser->name	= $request->name;
				$aUser->email	= $email;
				$aUser->avatar	= $request->avatar;
				$aUser->role_id	= 2;
			}
		}
		if ($proceed) {
			$aUser->{$field}		= $request->social_id;
			$aUser->mobile_token	= $request->mobile_token;
			$aUser->device			= $request->device;
			$aUser->log_status		= 'sociallogin';
			if ($email != '') {
				$aUser->email_verified_at = date('Y-m-d H:i:s');
			}
			$aUser->save();
			$userId		= $aUser->id;
		}
		if ($status == 200) {
			$userdata	= User::find($userId,$this->userBasicDatas());
			// updateCartToUser($request->device_id);
			if($request->device == 'web') {
				Auth::login($userdata,true);
			} else {
				$token		= $userdata->createToken('token')->plainTextToken;
				$token		= explode('|', $token);
				$response['token']		= $token[1];
				$response['info']		= $userdata;
			}			
		}
		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	//user logout
	public function Logout(Request $request)
	{
		$user = $request->user();
		$rules['device']		= 'required|in:android,ios,web';
		$this->validateDatas($request->all(),$rules);

		$data = User::find($user->id);
		$data->device		= $request->device;
		$data->log_status	= 'logout';
		$data->save();
		if ($request->role_id == 3) {
			$exists = \DB::table('orders')->where('vendor_id',Auth::id())->whereNotIn('status',[0,1])->where(function($query) {
				return $query->where('start_later_order',1)->orWhere('show_later_view',1);
			})->exists();
			if ($exists) {
				return response()->json(['status' => false,'message'=>'You have some ongoing orders.'],422);
			}
		}
		if($request->device == 'web') {
			\Auth::logout();
			// return \Redirect::to('');
		} else {
			$user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
		}
		return response()->json(['status' => true], 200);
	}

	public function userProfile(Request $request)
	{
		$data	= $request->user();
		$status	= 200;

		$rules['function']	= 'required|in:show,edit,changepassword';
		$rules['device']	= 'required|in:android,ios,web';
		if ($request->function == 'edit') {
			$rules['name']		= 'required';
			$rules['mobile']	= 'required|numeric|unique:users,mobile,'.$data->id;
			$rules['email']		= 'required|unique:users,email,'.$data->id;
			$rules['location_id']	= 'required';
			if($data->role == 3) {
				$rules['cuisine_type']		= ['required','array'];
				$rules['cuisine_type.*']	= 'required|exists:cuisines,id';
			}
		}
		if( $request->hasFile('avatar'))
			$rules['avatar']	= ['required', 'mimes:png,jpeg,jpg', 'max:5000'];
		if(isset($request->password) && $request->password !== null && $request->device != 'web')
			$rules['password']	= ['required', 'min:6'];
		$this->validateDatas($request->all(),$rules);

		$user		= User::find($data->id,$this->userBasicDatas());
		$userType	= ($user->role == 2) ? 'user' : (($user->role == 3) ? 'chef' : '');
		if ($userType == '') {
			$status		= 422;
			$response['message']	= "This type of user cannot able to view or edit their profile from here.";
			return \Response::json($response,$status);
		}
		if ($request->function == 'edit') {
			if ($request->device != 'web' && isset($request->password) && $request->password !== null) {
				$user->password = Hash::make($request->password);
			}
			$user->email		= $request->email;
			$user->mobile		= $request->mobile;
			$user->name			= $request->name;
			$user->location_id	= $request->location_id;
			if( $request->hasFile('avatar')) {
				$filenameWithExt	= $request->file('avatar')->getClientOriginalName();
				$filename			= pathinfo($filenameWithExt, PATHINFO_FILENAME);
				$extension			= $request->file('avatar')->getClientOriginalExtension();
				$fileNameToStore	= $filename.'_'.time().'.'.$extension;

				Storage::delete($user->avatarpath ?? null);
				$avatar_path		= $request->file('avatar')->storeAs('public/avatar', $fileNameToStore);
				$user->avatar		= $fileNameToStore;
			}
			$user->save();
		}
		$userdata	=  User::find($user->id,$this->userBasicDatas());
		$response['user']	= $userdata;
		return \Response::json($response,$status);
	}

	public function TableDatas( Request $request)
	{
		$rules['requestdata']	= 'required|in:Countries,Cuisines,Budget,Tag,Locations';
		$this->validateDatas($request->all(),$rules);
		$table		= $request->requestdata;
		$return		= ($table == 'Locations') ? 'App\\Models\\'.$table : (($table == 'Budget' || $table == 'Tag') ? 'App\\Models\\Commondatas' : ('App\\Models\\'.$table));
		$return		= $return::where('status','active');
		if ($table == 'Countries') {
			$return	= $return->get()->append('flag');
		} elseif ($table == 'Budget' || $table == 'Tag') {
			$return	= $return->where('type',strtolower($table))->get();
		} else {
			$return	= $return->get();
		}
		$response['message']= (count($return) > 0) ? "Data fetched successfully." : "There is no data in this module";
		$response['data']	= (count($return) > 0) ? $return : [];
		return \Response::json($response,200);
	}

	public function TableDatasAll( Request $request)
	{
		// echo "<pre>"; print_r($request->all());exit();
		$rules['requestdata']	= 'required';
		$this->validateDatas($request->all(),$rules);
		$tables		= explode(',', $request->requestdata);
		$response	= [];
		foreach ($tables as $key => $value) {
			$return		= array();
			$dataName	= strtolower($value);
			if($value == 'Variants') {
				$value  = 'Addon';
			}
			$return		= ($value == 'Budget' || $value == 'Tag') ? 'App\\Models\\Commondatas' : (($value == 'TimeSlot') ? 'App\\Models\\Timeslotcategory' : ('App\\Models\\'.$value));
			$return		= $return::where('status','active');
			if ($value == 'TimeSlot') {
				$return	= $return->with('slots')->get();
			} elseif ($value == 'Countries') {
				$return	= $return->get()->append('flag');
			} elseif ($value == 'Budget' || $value == 'Tag') {
				$return	= $return->where('type',$dataName)->get();
			} elseif ($value == 'Addon') {
				$return	= $return->activevariations()->get()->makeHidden(['status','price']);
			} else {
				$return	= $return->get();
			}
			// if($value == 'TimeSlot') $return->slots->makeHidden(['cat_id']);
			$response[$dataName]	= $return;
		}
		return \Response::json($response,200);
	}

	/*public function Orders(Request $request)
	{
		$user = auth('api')->user();
		if($user){
			$userId   = auth('api')->user()->id;
			$userData = User::find($user->id);
			$aCart 	  = Cart::select('res_id','address_id')->where('user_id',$userId)->groupBy('res_id')->get();
			if(!$aCart->isEmpty()){
				$orderArray 	= array();
				$orderArray['user_id'] = $userId; 
				$orderArray['mobile_number'] = $userData->mobile; 
				$orderArray['total_food_amount'] = 0; 
				$orderArray['vendor_price'] = 0; 
				$orderArray['commission_amount'] = 0; 
				$orderArray['del_charge'] = 0; 
				$orderArray['grand_total'] = 0; 
				$orderArray['address_id'] = $aCart[0]->address_id; 
				if($request->payment_type == 'cod')
				{
					$orderArray['payment_status'] = 'pending'; 
					$orderArray['payment_type'] = $request->payment_type; 
				}else{
					$orderArray['payment_token'] = null; 
					$orderArray['online_pay_status'] = null; 
				}
				$MainOrder = Order::create($orderArray);
				$MainOrderid = $MainOrder->id;

				foreach($aCart as $key => $cart){

					$RestaurantCart = Cart::select('date')->where('res_id',$cart->res_id)->groupBy('date')->get();
					foreach($RestaurantCart as $ke => $ResCart){
						$TimeCart = Cart::select('time_slot')->where('res_id',$cart->res_id)->where('date',$ResCart->date)->groupBy('time_slot')->get();
						$slotCount = $TimeCart->count();
						foreach($TimeCart as $ke => $TmCart){
							$SlotCart = Cart::where('res_id',$cart->res_id)->where('time_slot',$TmCart->time_slot)->where('date',$ResCart->date)->with('vendor_info','menu_item','addon_item')->get();

							$priceTotal 	 =  0;
							foreach ($SlotCart as $k => $fCart) {
								$priceTotal    += $fCart->price;
								$foodDetails[$k]['name']   	   =  $fCart->menu_item->name;
								$foodDetails[$k]['quantity']   =  $fCart->quantity;
								$foodDetails[$k]['price']      =  $fCart->price;
								$foodDetails[$k]['unit']       =  $fCart->unit;
								$foodDetails[$k]['addon']	   =  $fCart->is_addon == 'yes' ? $fCart->addon_item : [];
							}
							$fCart				 = $SlotCart[0];
							$del_charge			 = 0;
							$vendorInfo 	 	 = $fCart->vendor_info;
							$commission  		 = $vendorInfo->commission;
							$vendor_price  		 = $priceTotal- $commission;
							$commission_amount	 = $priceTotal * ( $commission / 100 );
							$offer_amount 	 = 0; $offer_value= 0; $tax = 0;
							if($vendorInfo->discount_status == 'yes')
							{
								$offer_amount	 = $vendorInfo->discount_type == 'price' ? ($priceTotal - $vendorInfo->discount) : ($priceTotal * ( $vendorInfo->discount / 100 ));
								$offer_value= $vendorInfo->discount;
							}
							if($vendorInfo->tax > 0)
							{
								$tax =	$priceTotal * ( $vendorInfo->tax / 100 );
							}
							$orderkey = $ke+1;
							$orderDetail['m_id'] 				=	$MainOrderid;
							$orderDetail['user_id'] 			= 	$userId;
							$orderDetail['vendor_id'] 			=	$fCart->vendor_info->vendor_id;
							$orderDetail['res_id'] 				= 	$fCart->res_id;
							$orderDetail['total_food_amount'] 	= 	$priceTotal;
							$orderDetail['vendor_price'] 		= 	$vendor_price;
							$orderDetail['commission'] 			= 	$commission;
							$orderDetail['commission_amount'] 	= 	$commission_amount;
							$orderDetail['offer_value'] 		=   $offer_value;
							$orderDetail['offer_type'] 			=   $vendorInfo->discount_type;
							$orderDetail['offer_amount'] 		= 	$offer_amount;
							$orderDetail['del_km'] 				= 	$fCart->delivermins;
							$orderDetail['del_charge'] 			= 	$del_charge;
							$orderDetail['grand_total'] 		= 	$priceTotal - $offer_amount + $tax + $del_charge;
							$orderDetail['status'] 				=   'pending';
							$orderDetail['tax'] 				= 	$tax;
							$orderDetail['food_items'] 			= 	json_encode($foodDetails);
							$orderDetail['is_preorder'] 		= 	$fCart->is_preorder;
							$orderDetail['date'] 				= 	$fCart->date;
							$orderDetail['time_slot'] 			= 	$fCart->time_slot;
							$orderDetail['s_id'] 				=   '100'.$MainOrderid.'-'.$fCart->vendor_info->vendor_id.'-'.$slotCount.'-'.$orderkey;
							$orderDetail = Orderdetail::create($orderDetail);
							$MainOrder->increment('total_food_amount',$priceTotal);
							$MainOrder->increment('vendor_price',$vendor_price);
							$MainOrder->increment('commission_amount',$commission_amount);
							$MainOrder->increment('del_charge',$del_charge);
							$MainOrder->increment('grand_total',$orderDetail->grand_total);
							unset($orderDetail);
						}
					}
				}
				$TotalOrder = Orderdetail::select('s_id','id')->where('m_id',$MainOrderid);
				$TotalOrderCount = $TotalOrder->count();
				$TotalOrder->get()->map(function ($item) use($TotalOrderCount) {
					$ID = explode('-', $item->s_id);
					$ID[2] = $TotalOrderCount;
					$item->s_id = implode('-', $ID);
					Orderdetail::where('id',$item->id)->update(['s_id'=>$item->s_id]);
				        return $item;
				});

				return response()->json(['status' => true,'message'=>'Order Placed Successfully'], 200); 
			}else{
				return response()->json(['status' => false,'message'=>'No Items In Cart'], 422);
			}
		}else{
			return response()->json(['status' => false,'message'=>'UnAuthorized User'], 401);
		}
	}*/

	public function Offers(Request $request)
	{
		$rules	= array();
		$getChef = $promos	= array();
		$status	= 422; $message	= 'No Offers Found';
		if ($this->method == 'GET') {
			$rules['latitude']	= 'required';
			$rules['longitude']	= 'required';
			$cPage		= $menupage	= 1;
			$perPage	= $this->sub_paginate;
			$vendorId	= 0;
			if (isset($request->menupage) && $request->menupage != '' && is_numeric($request->menupage)) {
				$menupage	= $request->menupage;
				$rules['vendor_id']	= 'required';
				if (isset($request->vendor_id) && $request->vendor_id != '' && is_numeric($request->vendor_id)) {
					$vendorId	= $request->vendor_id;
				}
			}
			if (isset($request->chefpage) && $request->chefpage != '' && is_numeric($request->chefpage)) {
				$cPage	= $request->chefpage;
			}
		} elseif ($this->method == 'PUT') {
			$rules['promo_code']	= 'required_without:promo_id|exists:promo_offer,promo_code';
			$rules['promo_id']		= 'required_without:promo_code|numeric|exists:promo_offer,id';
		}
		$this->validateDatas($request->all(),$rules);
		if ($this->method == 'GET') {
			if (isset($request->id) && $request->id != null) {
				$promo	= Offer::find($request->id);
				if (!empty($promo)) {
					$getChef	= Chefs::commonselect()->approved()->with(
						'ChefRestaurant',function($query) use($promo, $perPage, $menupage, $cPage, $vendorId){
							$query->select(['id','vendor_id','budget']);
							if ($vendorId != 0) {
								$query->where('vendor_id',$vendorId);
							}
							if ($promo->loc_status == 'selected') {
								$query->whereIn('location',explode(',',$promo->location));
							}
							if ($promo->res_status == 'selected') {
								$query->whereIn('vendor_id',explode(',',$promo->restaurant));
							}
							$query->with('approvedDishes', function($squery) use($perPage, $menupage) {
								$squery->select(['id','vendor_id','restaurant_id','name','image','description','price','status','stock_status','quantity','preparation_time','unit'])->paginate($perPage, ['*'], 'menupage', $menupage);
							});
						}
					);
					if ($promo->res_status == 'selected') {
						$getChef	= $getChef->whereIn('id',explode(',',$promo->restaurant));
					}
					$getChef	= $getChef->nearby($request->latitude,$request->longitude)->paginate($perPage, ['*'], 'chefpage', $cPage);
					$response['chefs'] = $getChef;
					$status		= 200; $message	= 'Offers Found';
				}
			} else {
				$locations	= Location::select(\DB::raw('group_concat(`id`) as locations'))->nearby($request->latitude,$request->longitude)->active()->first()->locations;
				$chefs		= Chefs::select(\DB::raw('group_concat(`id`) as chefs'))->nearby($request->latitude,$request->longitude)->approved()->first()->chefs;
				$promos		= Offer::commonselect();
				if (!empty($locations)) {
					$promos	= $promos->findlocations('location',$locations);
				}
				if (!empty($chefs)) {
					$promos	= $promos->findchefs('restaurant',$chefs);
				}
				$promos		= $promos->orWhere('loc_status','all')->orWhere('res_status','all')->active()->where('offer_visibility','on')->get();
				// $promos = $promos->map( function ( $item ) { return $item->load('locations.getVendor'); });
				$promos->makeHidden(['location', 'restaurant']);
				if (!$promos->isEmpty()) {
					$status	= 200; $message	= 'Offers Found';
					$response['promos']		= $promos;
				}
			}
			$response['message']	= $message;
			// $response['locations']	= $locations;
		} elseif ($this->method == 'POST') {
			$return		= self::AvailableOffers();
			$status		= $return['status'];
			$message	= $return['message'];
			$response['promos']	= $return['promos'];
		} elseif ($this->method == 'PUT') {
			$user		= $this->authCheck();
			$userId		= $user['userId'];
			if ($userId == 0 && $request->action != 'remove') {
				$response['message']	= 'Please log in and avail offer!.';
				$status = 401;
				return \Response::json($response,$status);
			}
			$cookieID	= (request('cookie')) ? request('cookie') : 0;
			$uCartQuery	= uCartQuery($userId, $cookieID);
			$uCartData_price	= clone ($uCartQuery); $uCartData	= clone ($uCartQuery);
			$uCartCoupon		= clone ($uCartQuery);
			$uCartData			= $uCartData->get();
			$chef_ids			= [];

			if ($uCartData->isEmpty()) {
				$response['message']	= 'Your cart is empty';
				return \Response::json($response,$status);
			}

			$col = !empty($request->promo_id) ? 'id' : 'promo_code';
			$req = !empty($request->promo_id) ? $request->promo_id : $request->promo_code;

			$promos	= Offer::where($col,$req)->active()->withCount('coupon');
			$Apromos= clone ($promos); $Mpromos= clone ($promos);$Upromos= clone($promos); 
			$Apromos= $Apromos->first();
			if (empty($Apromos)) {
				$response['message']	= 'Coupon unavailable';
				return \Response::json($response,$status);
			}

			$uCartData_price	= $uCartData_price->sum('price');
			$Coupon_id			= $uCartCoupon->first()->coupon_id;
			if($request->promo_id && $request->action == 'remove'){
				$Coupon_id = $Apromos->id;
			}
			$coupon_user_limit = Order::where('user_id',$userId)->where('Coupon_id',$Apromos->id)->count();
			/* selected chef total price calculation Begin */
			$chef_Promos = chefPromos($Apromos->id);
			if($chef_Promos['apply_status'] !== true){
				if(!empty($chef_Promos['chef_ids'])){
					$chef_ids			= $chef_Promos['chef_ids'];
					// $chef_Total_Price	= chefTotalPrice($userId,$chef_ids,'chefs');
					// $uCartData_price	= $chef_Total_Price;	
				}
			} else {
				$current_cart = $uCartQuery->get();
				foreach($current_cart as $key => $val){
					$chef_ids[] = chefId($val->res_id);
				}
			}
			/* selected chef total price calculation End */
			
			$Upromos	= (!is_null($Apromos->usage_limit)) ? $Upromos->where('usage_limit','>',$Apromos->coupon_count)->first() :  'unlimit';
			$Mpromos	= (!is_null($Apromos->user_limit)) ? $Mpromos->where('user_limit','>',$coupon_user_limit)->first() : 'unlimit';
			if(empty($Upromos)) {
				$message	= 'Coupon usage limit exceeded';
				$response['message']	= $message;
				return \Response::json($response,$status);
			}
			if(empty($Mpromos)){
				$message	= 'Your user limit exceeded';
				$response['message']	= $message;
				return \Response::json($response,$status);
			}
			if ($Coupon_id == 0) {
				$total_coupon_value = getPromoCal($Apromos->id,$uCartData_price);
				if (empty($chef_ids)) {
					$uCartQuery	    = $uCartQuery->update(['coupon_id' => $Apromos->id,'total_coupon_value' => $total_coupon_value]);
				} else {
					$chef_exist = Promochef($chef_ids,$uCartQuery);
					if($chef_exist == 1) {
						foreach($chef_ids as $key => $value){
							${"uca".$value}	= clone ($uCartQuery);
							$ch_id 			= $value;
							$chefdata 		= Chefs::find($ch_id,['id']);
							$cart_time		= ${"uca".$value}->where('res_id',$chefdata->ChefRestaurant->id)->get();

							foreach($cart_time as $k => $v ){
								if($v->food_type == "ticket") {
									${"ucat".$v}		= clone ($uCartQuery);
									$price 				= $v->price; 
									$uCartData_price   	= chefTotalPrice($userId,$ch_id,'','','');
									$total_coupon_value = getPromoCal($Apromos->id,$uCartData_price);
									if($Mpromos->min_order_value <= $uCartData_price){
										$cupdate = ${"ucat".$v}->where('res_id',$chefdata->ChefRestaurant->id)->update(['coupon_id' => $Apromos->id,'total_coupon_value' => $total_coupon_value]);
										$co_status = 1;
									}
								} else {
									${"ucat".$v}		= clone ($uCartQuery);
									$timeslot          	= $v->time_slot;
									$date              	= $v->date;
									$price 				= $v->price; 
									$uCartData_price   	= chefTotalPrice($userId,$ch_id,'',$timeslot,$date);
									$total_coupon_value = getPromoCal($Apromos->id,$uCartData_price);
									if($Mpromos->min_order_value <= $uCartData_price){
										$cupdate = ${"ucat".$v}->where(['res_id' => $chefdata->ChefRestaurant->id,'time_slot' => $timeslot,'date' => $date])->update(['coupon_id' => $Apromos->id,'total_coupon_value' => $total_coupon_value]);
										$co_status = 1;
									} 									
								}
							}
						}
					} else {
						$response['message']	= 'This promo offer is not valid for your cart chef';
						return \Response::json($response,$status);
					}

				}
				if(isset($co_status) && $co_status == 1){
					$msg		= 'Offers applied successfully';
					$status		= 200;
				}
				else{
					$msg	= 'Your order value should be more than '.$Mpromos->min_order_value;
				}
			} else {
				$uCartQuery->update(['coupon_id' => 0,'total_coupon_value' => 0]);
				$msg		= 'Offers removed successfully';
				$status		= 200;
			}
			$status		= $status; $message	= $msg;
			$response['message']	= $message;
		}
		return \Response::json($response,$status);
	}

	public function AvailableOffers()
	{
		$status		= 422; $message	= 'No Offers Found'; $promos = [];
		$user		= $this->authCheck();
		$userId		= $user['userId'];
		$cookieID	= (request('cookie')) ? request('cookie') : 0;
		$uCartData	= uCartQuery($userId, $cookieID)->addSelect(\DB::raw("group_concat(`res_id`) as restaurants"))->pluck('restaurants')->first();
		$promos	= Offer::commonselect();
		if (!empty($uCartData)) {
			
			$chefs= Restaurants::select(\DB::raw('group_concat(`vendor_id`) as chefs'),\DB::raw('group_concat(`location`) as locations'))->whereIn('id',explode(',',$uCartData))->pluck('chefs')->first();
			$locations= Restaurants::select(\DB::raw('group_concat(`location`) as locations'))->whereIn('id',explode(',',$uCartData))->pluck('locations')->first();


			if (!empty($locations)) {
				$promos	= $promos ->where(function($query) use ($locations){
					return $query->findlocations('location',$locations)->orWhere('loc_status','all');
				});
				//$promos	= $promos->findlocations('location',$locations);
			}

			if (!empty($chefs)) {
				$promos	= $promos ->where(function($query) use ($chefs){
					return $query->findchefs('restaurant',$chefs)->orWhere('res_status','all');
				});
				//$promos	= $promos->findchefs('restaurant',$chefs);	
			}
			$promos		= $promos/*->orWhere('loc_status','all')->orWhere('res_status','all')*/;

		}
		$promos		= $promos->where('offer_visibility','on')->active()->get();
		if (!$promos->isEmpty()) {
			$promos->makeHidden(['location', 'restaurant']);
			$status	= 200; $message	= 'Offers Found';
		}
		$return['promos']	= $promos;
		$return['status']	= $status;
		$return['message']	= $message;
		return $return;
	}

	public function Offersold(Request $request)
	{
		$rules['latitude']	= 'required';
		$rules['longitude']	= 'required';
		$this->validateDatas($request->all(),$rules);
		if (isset($request->offer) && $request->offer != '') {
			$request['seemore']	= 'nearByChefs';
			$request['offer']	= $request->offer;
			$aData = App::call('App\Http\Controllers\Api\Customer\CustomerController@homepage')->original['nearByChefs'] ?? [];
		} else {
			$ChefIds = Chefs::select('id')->with('restaurants')->approved()->nearby($request->latitude,$request->longitude)->get()->pluck('restaurants.id')->toArray();
			if(!empty($ChefIds)) {
				$aData = Offer::active()->whereRaw('restaurant REGEXP("('.implode('|',$ChefIds).')")')->get()->makeHidden(['created_at','updated_at','status']);
			}
		}
		if(!$aData->isEmpty()){
			return response()->json(['status' => true,'aData'=>$aData], 200);
		}
		return response()->json(['status' => false,'message'=>'No Offers Found'], 422);
	}

	public function sendOrderMail(Request $request)
	{
		if(env('APP_ENV') == 'production') {
			$s_id	= $request->s_id;
			$type	= 'pending';
			$type	= ($type == 'new') ? 'pending' : $type;
			$orders	= Orderdetail::with('userinfo','chefinfo'/*,'reviewinfo'*/)->where('s_id',$s_id)->first()->append(['order_status','time_s','user_addr','boy_info','reviewinfo','food_items_count']);
			switch ($request->status) { 
				case 'orderInsert':
				$userId		= [$orders->user_id, $orders->vendor_id];
				$tempName	= ['customer_order_insert', 'orderInsert'];
				$subject	= ['New order Placed','New order received'];
				$mMessage	= ['Thank you for placing order with '.env('APP_NAME').' and your Order Id is #'.$s_id.'. You will get notified as soon as the Chef accepts your order. Celebrate Life ....Celebrate Food .Let\'s Knosh ! (Trivia-Knosh means "Food" /"To Eat")','You have received new order and Order Id is #'.$s_id .'. Celebrate Life ....Celebrate Food .Let\'s Knosh ! (Trivia-Knosh means "Food" /"To Eat")'];
				break;

				case 'orderAccept':
				$userId		= [$orders->user_id];
				$tempName	= ['orderAccept'];
				$subject	= ['Order acceptance'];
				$mMessage	= ['Your order was accepted by Chef (Order Id : #'.$s_id.'). Let\'s Knosh !'];
				break;

				case 'orderCompleted':
				$userId		= [$orders->user_id, $orders->vendor_id];
				$tempName	= ['orderCompleted', 'orderCompleted'];
				$subject	= ['Order delivered','Order delivered'];
				$mMessage	= ['Your order (Order Id : #'.$s_id.') was delivered successfully. Thank you for being an amazing customer. Let\'s Knosh Again!','Your order was delivered successfully (Order Id : #'.$s_id.'). Let\'s Knosh Again!'];
				break;

				case 'orderReject':
				if ($request->curStatus == 'rejected_res' || $request->curStatus == 'rejected_admin') {
					$userId		= [$orders->user_id];
					$tempName	= ['orderReject'];
					$subject	= ['Order Rejected'];
					$mMessage	= ['Your order #'.$s_id.' was not accepted by the Chef due to heavy rush of orders. Sorry for the inconvenience. Let\'s Knosh again!'];
				} else {
					$userId		= [$orders->vendor_id];
					$tempName	= ['customer_order_reject'];
					$subject	= ['Order Cancelled'];
					$mMessage	= ['Your order #'.$s_id.' was cancelled by the customer. Team Knosh'];
				}
				break;

				case 'Cancelled':
				$userId		= [$orders->user_id,$orders->vendor_id];
				$subject	= ['Order Cancelled','Order Cancelled'];
				$tempName	= ['customer_auto_cancellation','chef_auto_cancellation'];
				$mMessage	= ['Your order #'.$s_id.' was cancelled because of delay in approval, Sorry for the inconvenience. Your amount will get refunded within next 3 working days. Let\'s Knosh','Your order #'.$s_id.' was cancelled because of inactivity .There is penalty of'.$request->chef_penalty.'. Let\'s Knosh'];
				break;

				case 'retriesover':
				$userId		= [$orders->user_id,$orders->vendor_id];
				$subject	= ['Order Cancelled','Order Cancelled'];
				$tempName	= ['customer_auto_cancellation','chef_auto_cancellation'];
				$mMessage	= ['Your order #'.$s_id.' was cancelled because of delivery service not available in your location.Sorry for the inconvenience','Order was cancelled because of delivery service not available in customers location'];
				break;

				case 'CancelledbyDealer':
				$userId		= [$orders->user_id,$orders->vendor_id];
				$subject	= ['Order Cancelled','Order Cancelled'];
				$tempName	= ['customer_auto_cancellation','chef_auto_cancellation'];
				$mMessage	= ['Your order #'.$s_id.' was cancelled because of delivery service not available in your location.Sorry for the inconvenience','Order was cancelled because of delivery service not available in customers location'];
				break;

				case 'EventBooked':
				$userId		= [$orders->user_id, $orders->vendor_id];
				$subject	= ['New Event Booked','New Event Booked'];
				$tempName	= [/*'customer_event_insert'*/];
				$mMessage	= ['Thank you for your purchase from Knosh Events .To view your event pass, please check in your app under Profile section > My event.','New Event ticket has been booked.'];
				break;

				case 'Home_Event_Booked':
				$userId		= [$orders->user_id, $orders->vendor_id];
				$subject	= ['New Home Event Booked','New Home Event Booked'];
				$tempName	= [/*'customer_event_insert'*/];
				$mMessage	= ['Thank you for your purchase from Home Events .To view your event details, please check in your app under Profile section > My Home event.','New Home Event ticket has been booked.'];
				break;

				default:
				$tempName	= $userId	= [];
				break;
			}

			if (!empty($tempName)) {
				$userinfo	= $orders->getUserDetails;
				$vendorinfo	= $orders->getVendorDetails;
				foreach ($tempName as $key => $value) {
					if ($value == 'customer_order_insert' || $value == 'orderAccept' || $value == 'orderCompleted' || $value == 'orderReject' || $value == 'customer_auto_cancellation' || $value == 'Customer_Event_Booked') {
						$userdata = $userinfo;
					} else {
						$userdata = $vendorinfo;
					}
					if ((!empty($userdata) && $userdata->mobile != '')) {
						$details	= array();
						$details['phone']		= $userdata->mobile;
						$details['content']		= ($value == 'chef_auto_cancellation') ? [$userdata->name,'#'.$s_id,$request->chef_penalty] : [$userdata->name,'#'.$s_id];
						$details['template']	= $value;
						$sms	= (new OtpUserEvent($details));
					}
				}
			}

			if (!empty($userId)) {
				foreach ($userId as $key => $value) {
					$user	= User::find($value);
					if (!empty($user)) {
						if ($user->mobile_token != '' && $user->mobile_token != null)
							FCM($user->mobile_token,$subject[$key],$mMessage[$key]);
						$data['msg']		= $mMessage[$key];
						$data['userData']	= $user;
						$data['past_val']	= $orders;
						// echo "<pre>";
						// print_r($data['userData']);exit();
						// dd($data['past_val']);
						// echo view('mail.member.orderDetailsTemplate',$data);exit;
						$from 	= env('MAIL_FROM_ADDRESS');
						if ($user->email != '') {
							$sub = $subject[$key];
							\Mail::send('mail.member.orderDetailsTemplate', $data,function($message) use ($sub,$user,$from) {
								$message->to($user->email)->subject($sub);
								if($sub == "New order received") {
									$cc_ids = array_filter(array($user->individual_email_1,$user->individual_email_2));									
									$message->cc($cc_ids);
								}
								$message->from($from,CNF_APPNAME);
							});
						}
					}
				}
				if( count(Mail::failures() ) > 0 ) {
					return 'Mail not sent';
				} else {
					return 'Mail sent successfully';
				}
			}
		}
	}

	public function mailcheck()
	{
		$data['msg']		= "TEST MESSAGE";
		$data['userData']	= User::find(1);;
		$data['past_val']	= Orderdetail::with('userinfo','chefinfo'/*,'reviewinfo'*/)->where('id',166)->first()->append(['order_status','time_s','user_addr','boy_info','reviewinfo','food_items_count']);;
		// echo "<pre>";
		// print_r($data['userData']);exit();
		// echo "string";
		return view('mail.member.orderDetailsTemplate',$data);die;
	}

	public function MasterCron()
	{
		App::call('App\Http\Controllers\Api\Order\OrderController@cronPendingBoy');
		App::call('App\Http\Controllers\Api\Order\OrderController@cronAutoCancellation');
		App::call('App\Http\Controllers\Api\Razor\PayoutsController@PayoutsCron');
	}

	public function Clearlog()
	{
		\DB::table('tbl_http_logger')->truncate();
	}

	public function WalletAmountApply(Request $request)
	{
		$response['message'] = "No cart items";$status  = 422;
		$guard	= ($request->from == 'mobile') ? 'api' : 'web';
		$user	= auth($guard)->user();
		$response['used_amount'] = '0.00';
		if($user) {
			$ucart	= Cart::where('user_id',$user->id)->get();
			if($ucart->count() > 0) {
				if($user->wallet >= $request->used_wallet_amt) {
					foreach ($ucart as $key => $value) {
						$value->used_wallet_amount = $request->used_wallet_amt;
						$value->save();
					}
					$response['used_amount'] = $request->used_wallet_amt;
					$response['message'] = "Wallet amount applied for orders successfully.";
					$status = 200;
				} elseif($user->wallet < $request->used_wallet_amt) {
					$response['message'] = "Insufficient balance in your wallet.Please check your wallet balance in the Profile section and re-enter the amount.";
					$status = 422;
				}		
			}	
		} else {
			$response['message'] = "You should login to use wallet";
			$status = 401;
		}
		return \Response::json($response,$status); 
	}
}