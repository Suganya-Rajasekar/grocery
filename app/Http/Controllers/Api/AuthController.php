<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Location;
use App\Models\UserDocuments;
use App\Models\Restaurants;
use App\Models\Orderdetail;
use App\Models\Menuitems;
use App\Models\Referralsettings;

use App\Events\RestaurantDefault;
use App\Events\LogActivitiyEvent;

use Flash;
use File;
use Mail, DB;

use App\Events\OtpUserEvent;

/**
 * Author : Suganya
 * Used for API actions
 */

class AuthController extends Controller
{
	/**
	 * Create a new AuthController instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->segment  = \Request::segment(1);
		$from   = (!is_null($this->segment) && $this->segment == 'api') ? 'mobile' : 'web';
		$cookie = ($from == 'mobile') ? ((request('cookie')) ? request('cookie') : 0) : ((\Session::has('cookie')) ? \Session::get('cookie') : self::cookie());
		\Request::merge(['cookie' => $cookie]);
		self::middleware('auth:api', ['except' => ['login','register','verifyOTP','forgetPasswordrequest','resetPassword','socialLogin']]);
	}

	function cookie()
	{
		$cookie = uniqid();
		\Cookie::queue(\Cookie::make("mycart", $cookie, 45000));
		\Session::put('cookie', $cookie);
		return $cookie;
	}

	public function userBasicDatas()
	{
		return ['id', 'role', 'name', 'email', 'password', 'location_id', 'mobile', 'avatar', 'cuisine_type', 'status', 'email_verified_at','mobile_token','referal_code'];
	}

	/**
	 * Get a JWT via given credentials.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function login( Request $request)
	{
		// print_r($request->header('User-Agent'));exit();
		$from   = 'otp';
		$rules['role_id']		= 'required|in:2,3';
		$rules['email']			= 'required_without:mobile|exists:users|email';
		if ($request->guestlogin !== null && $request->guestlogin == 'yes') {
			$rules['location_id']	= 'required|numeric';
			$rules['mobile']	= 'required';
		} else {
			$rules['mobile']	= 'required_without:email|exists:users|numeric';
		}
		if ($request->role_id == 3 || (isset($request->email) && $request->email != '')) {
			$rules['password']	= 'required';
			$rules['device']	= 'required|in:android,ios,web';
			if ($request->device != 'web')
				$rules['mobile_token']  = 'required';
			$from   = 'data';
		}
		self::validateDatas($request->all(),$rules);

		$user = User::select(self::userBasicDatas());
		if (isset($request->email) && $request->email != '') {
			$user = $user->where('email', $request->email)->first();
		} else {
			$user = $user->where('mobile', $request->mobile)->where('mobile', '!=', 0)->first();
		}

		if ($from == 'data') {
			$data = clone ($user);
		}

		$lastLoc = User::selectRaw("
			SUBSTRING_INDEX(user_code, '-', 1) AS user_code_area,
			SUBSTRING_INDEX(user_code, '-', -1) AS user_code_no"
		)->whereRaw("SUBSTRING_INDEX(user_code, '-', 1) = 'C'")
		->orderBy('id','DESC')
		->first();
		//print_r(DB::getQueryLog($lastLoc));exit;

		$lastLocCount = 1;
		if(!empty($lastLoc)){
			$lastLocCount = $lastLoc->user_code_no + 1;
		}
		//$createArr['chef_code'] = $request->area_code.'-'.$lastLocCount;
		/*Take last record under the location end*/
		if (!empty($user)) {
			if ($request->guestlogin !== null && $request->guestlogin == 'yes') {
				$from   = 'guestaccount';
				$response['message']    = 'You already have an account please login.';
				return \Response::json($response,422);
			} else {
				if (isset($request->email) && $request->email != '' && (empty($user) || ! Hash::check($request->password, $user->password))) {
					$response['message']    = 'The provided credentials are incorrect.';
					return \Response::json($response,401);
				}

				if((($user->role == 3) || ($user->role == 2)) && (is_null($user->email_verified_at) || $user->email_verified_at != '') && $user->status != 'approved') {
					$response['message']    = ($user->role == 2) ? 'Your account is suspended by Admin. Contact Admin' : 'Your account is not approved yet.';
					return \Response::json($response,422);
				}
			}

			if ($from != 'guestaccount' && $from == 'otp') {
				self::generateOTP($request->mobile);
			} else {
				if ($request->device != 'web' && ($request->role_id != $user->role)) {
					$response['message']    = ($user->role == 2) ? "You are a customer please login using customer app" : (($user->role == 1 || $user->role == 5) ? "You are able to login from here." : "You are a chef please login using chef app");
					return \Response::json($response,422);
				}
				$user->device       = $request->device;
				$user->log_status   = 'login';
				$user->mobile_token = $request->mobile_token;
				$user->save();
			}


			if ($from == 'data') {
				if ($request->device == 'web') {
					$login  = \Auth::loginUsingId($user->id);
				} else {
					// $token  = $user->createToken('token')->plainTextToken;
					$token  = auth('api')->tokenById($user->id);
					$response['token']  = $token;
					$response['info']   = $data;
				}
			}

			if (/*$user->role == 2 && */$request->input('cookie') !== null && $request->cookie != '') {
				if (isset($request->email) && $request->email != '') {
					self::updateCartToUser($user->id, $request->cookie);
				}
			}
			$response['message']    = ($from == 'otp') ? "Please enter the OTP send to your mobile number." : "You are logged in successfully." ;
		} else {
			if ($request->guestlogin !== null && $request->guestlogin == 'yes') {
				if(isset($request->referal_code) && !empty($request->referal_code)) {
					$referer_id = User::where('referal_code',$request->referal_code)->first(['id']);
					$ref_settings = Referralsettings::find(1);
					if(is_null($referer_id)) {
						$status = 422;
						$response['message']= "Your referal code is incorrect";
						return \Response::json($response,$status);
					}
				}
				$createArr = [
					'name'          => '',
					'email'         => '',
					'role'          => 2,
					'location_id'   => $request->location_id,
					'mobile'        => $request->mobile,
					'status'        => "approved",
					'remember_token'=> md5(rand()),
					'mobile_otp'    => '1234',
					'user_code'       => 'C'.'-'.$lastLocCount,
				];
				$createArr['referal_code']    = referralgeneration();
				$createArr['referer_user_id'] = (isset($referer_id)) ? $referer_id->id : 0;
				$createArr['wallet']          = (isset($ref_settings)) ? $ref_settings->referral_user_credit_amount : 0;  
				$user   = User::create($createArr);
				if(isset($request->referal_code) && !empty($request->referal_code)) {
					manage_wallet_history('registration',$user->id,$ref_settings->referral_user_credit_amount);
				}
				self::generateOTP($request->mobile);
				$response['message']    = "Please enter the OTP send to your mobile number.";
			} else {
				$response['message']    = "Your log in credentials are incorrect.";
			}
		}
		return \Response::json($response,200);
	}

	/**
	 * Registration.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function register( Request $request)
	{
		$adminCheck = false;
		if($request->device == 'web' && \Auth::check() && \Auth::user()->role == 1){
			$adminCheck = true;
		}
		$rules['role_id']   = ['required', 'max:4', 'min:1'];
		$rules['name']      = ['required', 'string', 'max:255'];
		$rules['email']     = ['required', 'string', 'email', 'max:255', 'unique:users'];
		$rules['mobile']    = ['required','numeric', 'unique:users'];
		$rules['country']   = ['required','numeric'];
		if($adminCheck) {
			$rules['password']  = ['required', 'min:6'];
		} else {
			$rules['password']  = ['required', 'min:6','required_with:cpassword', 'same:cpassword'];
			$rules['cpassword'] = ['required', 'min:6'];
		}
		
		$rules['device']    = 'required|in:android,ios,web';

		if($request->role_id == 3) {
			$rules['profile_name']      = ['required'];
			$rules['cuisine_type']      = ['required','array'];
			$rules['cuisine_type']      = 'required|exists:cuisines,id';
			$rules['area_code']         = 'required|exists:locations,id';
			$rules['fssai_certificate'] = 'mimes:jpeg,jpg,png,pdf|max:2048';
			$rules['aadar_image']       = 'required|mimes:jpeg,jpg,png,pdf|max:2048';
		}

		$nicenames['role_id']       = 'Role id';
		$nicenames['name']          = 'Name';
		$nicenames['email']         = 'Email';
		$nicenames['country']       = 'Country';
		$nicenames['mobile']        = 'Mobile';
		$nicenames['password']      = 'Password';
		$nicenames['cpassword']     = 'Cpassword';
		$nicenames['cuisine_type']  = 'Cuisine type';
		$nicenames['area_code']     = 'Location';
		$nicenames['aadar_image']   = 'Aadhaar';
		$nicenames['fssai_certificate']     = 'FSSAI certificate';
		$nicenames['profile_name']          = 'Business Profile Name';

		// validates other request data
		self::validateDatas($request->all(),$rules,[],$nicenames);

		$name       = $request->name;
		$country    = $request->country;
		$updateCart = false;
		if($request->role_id == 3 || $request->role_id == 2) {
			if($request->role_id == 2 && !empty($request->referal_code)) {
				$referer_id = User::where('referal_code',$request->referal_code)->first(['id']);
				$ref_settings = Referralsettings::find(1);
				if(is_null($referer_id)) {
					$status = 422;
					$response['message']= "Your referal code is incorrect";
					return \Response::json($response,$status);
				}
			}
			$random_no  = md5(rand());
			$slug       = '';
			$createArr = [
				'name'        => $name,
				'role'        => $request->role_id,
				'email'       => strtolower($request->email),
				'location_id' => $request->country,
				'mobile'      => $request->mobile,
				'device'      => $request->device,
				'password'    => Hash::make($request->password),
				'status'            => "approved",
				'remember_token'    => $random_no,
				'mobile_otp'        => '1234',
				'log_status'        => 'register',
			];
			$lastLocCount   = 1;
			$lastLoc        = User::selectRaw("CAST(SUBSTRING_INDEX(user_code, '-', -1) AS UNSIGNED) as user_code_no");
			if($request->role_id == 3) {
				$updateCart         = true;
				$slug   = Str::slug($name,'_');
				if ($slug == '') {
					$slug   = str_replace(' ', '_', $name);
				}
				if($request->device == 'web' && \Auth::check() && \Auth::user()->role == 1){
					$createArr['status']    = $request->status;
					$adminCheck = true;
				} else {
					$createArr['status']    = 'pending';
				}
				$createArr['profile_name']  = $request->profile_name;
				$createArr['business_name'] = $name;
				$createArr['cuisine_type']  = implode(',', $request->cuisine_type);

				/*Take last record under the location*/
				$location   = Location::find($request->area_code);
				$lcode      = $location->code;
				$lastLoc    = $lastLoc->where('role',3)->whereRaw("SUBSTRING_INDEX(user_code, '-', 1) = '".$lcode."'");
				/*Take last record under the location end*/
			} else {
				$lastLoc    = $lastLoc->where('role',2);
				$lcode      = 'C';
				$createArr['status']= 'approved';
				$createArr['referal_code']    = ($request->role_id == 2) ?  referralgeneration() : '';
				$createArr['referer_user_id'] = ($request->role_id == 2 && isset($referer_id)) ? $referer_id->id : 0;
				$createArr['wallet']          = (isset($ref_settings)) ? $ref_settings->referral_user_credit_amount : 0;  
				$updateCart         = true;
			}
			$lastLoc    = $lastLoc->orderByDesc('user_code_no')->first();
			if(!empty($lastLoc) && is_numeric($lastLoc->user_code_no)){
				$lastLocCount = $lastLoc->user_code_no + 1;
			}
			$createArr['user_code'] = $lcode.'-'.$lastLocCount;
			$createArr['slug']      = $slug;
			$user   = User::create($createArr);
			if(isset($referer_id)) {
				manage_wallet_history("registration",$user->id,$createArr['wallet']);
			}
			if($request->role_id == 3) {
				createRZContact($user,'new');
			} else {
				self::generateOTP($user->mobile);
			}
			event(new RestaurantDefault($user->id)); // event
			/*User Documents Upload*/
			if ($user->role_id == '3') {
				$chef  = Restaurants::where('vendor_id',$user->id)->first();
				if(!empty($chef)) {
					$resdetail['time_to_sell']  = ($request->time_to_sell != '') ? $request->time_to_sell : '';
					$resdetail['facebook_link'] = ($request->fa_link != '') ? $request->fa_link : '';
					$resdetail['instagram_link']= ($request->in_link != '') ? $request->in_link : '';
					$resdetail['youtube_link']  = ($request->yo_link != '') ? $request->yo_link : '';
					$chef->fill($resdetail)->save();
				}
				$userDocument           = new UserDocuments();
				$userDocument->user_id  = $user->id;
				$mainPathString         = 'uploads/user_document/'.$user->id.'/';
				$mainPath               = base_path($mainPathString);
				if (!File::exists($mainPath)) {
					$dc = File::makeDirectory($mainPath, 0777, true, true);
				}

				if($request->hasFile('fssai_certificate')){
					$fssai              = $request->file('fssai_certificate');
					$extension          = $fssai->getClientOriginalExtension(); 
					$newfilename        = $user->id.'_fssai_'.$user->user_code.'.'.$extension;
					$uploadSuccess      = $fssai->move($mainPath, $newfilename);
					if ($uploadSuccess) {
						$userDocument->fssai_certificate = $newfilename;
					}
				}
				if($request->hasFile('aadar_image')){
					$aadar              = $request->file('aadar_image');
					$extension          = $aadar->getClientOriginalExtension(); 
					$newfilename        = $user->id.'_aadar_'.$user->user_code.'.'.$extension;
					$uploadSuccess      = $aadar->move($mainPath, $newfilename);
					if ($uploadSuccess) {
						$userDocument->aadar_image = $newfilename;
					}
				}
				$userDocument->save();
			}
			/*Upload documents end*/

			if($updateCart && $request->input('cookie') !== null && $request->cookie != '') {
				self::updateCartToUser($user->id, $request->cookie);
			}

			$maildata['link']           = URL::to('/verification/'.$user->id.'/'.$random_no);
			$maildata['username']       = $user->name;
			$maildata['request_from']   = 'register';
			$user_email                 = $user->email;
			$from           = env('MAIL_FROM_ADDRESS');;
			$subject        = CNF_APPNAME.' - Email verification';
			$view           = 'mail.member.verificationmail';
			// self::sendemail($view,$maildata,$from,$user_email,$subject);
			$status = 200;
			$response['message']= ($request->role == 3) ? "Thanks for registering. We will review your account and get back to you soon." : "Registered successfully please verify otp received on your mobile.";
			if($adminCheck){
				$response['uid'] = $user->id;
			}
		} else {
			$status = 422;
			$response['message']= "Your Role Id was incorrect";
		}
		return \Response::json($response,$status);
	}

	/**
	 * Generate OTP common function.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function generateOTP($mobileNum='')
	{
		if ($mobileNum != '') {
			$user = User::where('mobile', $mobileNum)->where('mobile', '!=', 0)->first();
			if (!empty($user)) {
				$config = \Config::get('twofactor');
				if($config['TWO_FACTOR_OPTION'] && $mobileNum != 9222479222 && $mobileNum != 9222379222 && $mobileNum != 9626847411) {
					$rand_no = (string) rand(1000, 9999);
					$details = array();
					$details['phone']   = $mobileNum;
					$details['content'] = $rand_no;
					event(new OtpUserEvent($details));
				} else {
					$rand_no = '1234';
				}
				$user->mobile_otp = $rand_no;
				$user->save();
			}
		}
	}

	/**
	 * Generate OTP verification function.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function verifyOTP( Request $request)
	{
		$from   = ($request->input('from') !== null) ? $request->from : 'api';
		$rules['mobile']        = ($request->guestlogin !== null && $request->guestlogin == 'yes') ? 'required' : 'required|exists:users';
		$rules['role_id']       = 'required|in:2,3';
		$rules['device']        = 'required|in:android,ios,web';
		if ($request->device != 'web')
			$rules['mobile_token']  = 'required';
		$rules['otp']   = 'required|numeric|min:4';
		self::validateDatas($request->all(),$rules);

		$userBasicDatas = self::userBasicDatas();
		$user = User::select($userBasicDatas)->where('mobile', $request->mobile)->where('mobile', '!=', 0)->where('mobile_otp', $request->otp)->first();
		if (!empty($user)) {
			$message    = 'success';
			$status     = 200;
			if ($user->role_id == 3) {
				if ((is_null($user->email_verified_at) || $user->email_verified_at != '') && $user->status != 'approved') {
					$status     = 422;
					$message    = 'Your account is not approved yet.';
				}
			} elseif ($request->device != 'web' && $user->role_id == 1) {
				$status     = 422;
				$message    = "Admin cannot login through this.";
			}
			if ($status == 200) {
				if ($request->device != 'web' && $request->role_id != $user->role_id) {
					$response['message']    = ($user->role_id == 2) ? "You are a customer please login using customer app" : "You are a chef please login using chef app";
					return \Response::json($response,422);
				}
				$user->device       = $request->device;
				$user->mobile_token = $request->mobile_token;
				$user->log_status   = 'login';
				$user->save();
				$userData   = User::find($user->id,$userBasicDatas);
				if($request->device == 'web') {
					$login = Auth::loginUsingId($userData->id,true);
					$response['csrf_token'] = csrf_token();
				} else {
					// $token  = $userData->createToken('token')->plainTextToken;
					$token  = auth('api')->tokenById($userData->id);
					// $token  = explode('|', $token);
					$message= "OTP matched";
					$response['token']      = $token;
					$response['info']       = $userData;
				}
				if($request->input('cookie') !== null && $request->cookie != ''){
					self::updateCartToUser($user->id, $request->cookie);
				}
			}
		} else {
			$status = 422;
			$message= "The OTP you entered is invalid.Please enter correct OTP";
		}
		$response['message']    = $message;
		return \Response::json($response,$status);
	}

	/**
	 * send mail for forget pwd.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function forgetPasswordrequest(Request $request)
	{
		$rules['email'] = 'required';
		$rules['device']= 'required|in:android,ios,web';
		self::validateDatas($request->all(),$rules);

		$status = 422;
		$exist  = false;
		$user   = User::where('email',$request->email)->first();
		if (!empty($user)) {
			$exist      = true;
			$update_user= User::find($user->id);
		}

		if ($exist) {
			$update_user->device        = $request->device;
			$update_user->reminder      = getVerifyCode();
			$update_user->updated_at    = date('Y-m-d H:i:s');
			$update_user->save();

			$data['user']   = $update_user;
			$data['from']   = 'App';
			$to             = $request->email;
			$from           = env('MAIL_FROM_ADDRESS');
			$subject        = CNF_APPNAME.' Forget Password';
			$view           = 'mail.member.forgetPasswordRequest';
			self::sendemail($view,$data,$from,$to,$subject);

			$status = 200;
			$response['message']    = 'Code sent to your email address';
		} else {
			$response['message']    = 'Cant find user';
		}
		return \Response::json($response,$status);
	}

	/**
	 * reset pwd .
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	function resetPassword(Request $request)
	{
		$rules['email']     = 'required';
		$rules['code']      = 'required';
		$rules['password']  = 'required|min:6|required_with:cpassword|same:cpassword';
		$rules['cpassword'] = 'required|min:6';
		$rules['device']    = 'required|in:android,ios,web';

		self::validateDatas($request->all(),$rules);
		$status = 422;
		$exist  = false;
		$user   = User::where('email',$request->email)->where('reminder',$request->code)->first();
		if (!empty($user)) {
			$exist      = true;
			$update_user= User::find($user->id);
		}

		if ($exist && ($update_user->role != 2 && $update_user->role != 3)) {
			$status     = 422;
			$response['message']    = "This type of user cannot able to reset their password from here";
			return \Response::json($response,$status);
		}

		if ($exist) {
			$update_user->device        = $request->device;
			$update_user->password      = Hash::make($request->password);
			$update_user->reminder      = '';
			$update_user->updated_at    = date('Y-m-d H:i:s');
			$update_user->save();

			if ($request->device == 'web') {
				$response['rurl'] = ($update_user->role == 2) ? 'login' : 'chef/login' ;
			}

			$status = 200;
			$response['message']    = 'Password changed successfully';
		} else {
			$response['message']    =  'Invalid code';
		}
		return \Response::json($response,$status);
	}

	/**
	 * Social login .
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	function socialLogin(Request $request)
	{
		$rules['social_id']     = 'required';
		$rules['provider']      = 'required|in:google,facebook,apple';
		$rules['role_id']       = 'required|in:2';
		$rules['device']        = 'required|in:android,ios,web';
		if($request->device != 'web') {
			$rules['mobile_token']  = 'required';
		}
		self::validateDatas($request->all(),$rules);

		$provider   = $request->provider;
		if ($provider == 'google') {
			$field = 'google_id';
		} elseif ($provider == 'facebook') {
			$field = 'facebook_id';
		} else {
			$field = 'apple_id';
		}

		$user       = User::where($field,$request->social_id)->first();
		$proceed    = false;
		$email      = ($request->input('email') !== null) ? $request->email : '';
		$message    = 'You are logged in successfully.';
		$emessage   = 'You cannot use social login.Only customers can login through this.';
		$status     = 200;
		$lastLoc = User::selectRaw("
			SUBSTRING_INDEX(user_code, '-', 1) AS user_code_area,
			SUBSTRING_INDEX(user_code, '-', -1) AS user_code_no"
		)->whereRaw("SUBSTRING_INDEX(user_code, '-', 1) = 'C'")
		->orderBy('id','DESC')
		->first();
		//print_r(DB::getQueryLog($lastLoc));exit;

		$lastLocCount = 1;
		if(!empty($lastLoc)){
			$lastLocCount = $lastLoc->user_code_no + 1;
		}
		//$createArr['chef_code'] = $request->area_code.'-'.$lastLocCount;
		/*Take last record under the location end*/
		if (!empty($user)) {
			$userId     = $user->id;
			if ($user->role != 2) {
				$status     = 422;
				$message    = $emessage;
			} else {
				if ($email == '' || $email == $user->email) {
					$aUser      = User::find($user->id);
					if ($field != 'apple') {
						$aUser->name    = $request->name;
						if ($email != '') {
							$aUser->email   = $email;
						}
						$aUser->avatar  = $request->avatar;
					}
				}
			}
		} else {
			$proceed = $newUser = true;
			if ($email != '') {
				$aUser = User::where('email',$email)->first();
				if(!empty($aUser)){
					$newUser = false;
					if ($aUser->role != 2) {
						$status     = 422;
						$proceed    = false;
						$message    = $emessage;
					}
				}
			}
			if ($newUser) {
				$aUser      = new User;
				$aUser->name    = $request->name;
				$aUser->email   = $email;
				$aUser->avatar  = $request->avatar;
				$aUser->role    = 2;
			}
		}
		if ($proceed) {
			$aUser->{$field}        = $request->social_id;
			$aUser->mobile_token    = $request->mobile_token;
			$aUser->device          = $request->device;
			$aUser->log_status      = 'sociallogin';
			if ($email != '') {
				$aUser->email_verified_at = date('Y-m-d H:i:s');
			}
			$aUser->user_code       = 'C'.'-'.$lastLocCount;
			$aUser->status          = 'approved';
			$aUser->referal_code    = referralgeneration();
			$aUser->save();
			$userId     = $aUser->id;
		}
		if ($status == 200) {
			$userdata   = User::find($userId,self::userBasicDatas());
			self::updateCartToUser($userdata->id, $request->cookie);
			if($request->device == 'web') {
				Auth::login($userdata,true);
			} else {
				// $token      = $userdata->createToken('token')->plainTextToken;
				$token  = auth('api')->tokenById($userdata->id);
				$response['token']      = $token;
				$response['info']       = $userdata;
			}           
		}
		$response['message']    = $message;
		return \Response::json($response,$status);
	}

	/**
	 * Get the authenticated User.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function me()
	{
		return response()->json(auth('api')->user());
	}

	/**
	 * Log the user out (Invalidate the token).
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout( Request $request)
	{
		// $user = $request->user();
		$user   = auth('api')->user();
		$rules['device']        = 'required|in:android,ios,web';
		$this->validateDatas($request->all(),$rules);

		$data   = User::find($user->id);
		$data->device       = $request->device;
		$data->log_status   = 'logout';
		$data->save();
		if ($request->role_id == 3) {
			/*$exists = \DB::table('orders')->where('vendor_id',Auth::id())->whereNotIn('status',[0,1])->where(function($query) {
				return $query->where('start_later_order',1)->orWhere('show_later_view',1);
			})->exists();
			if ($exists) {
				return response()->json(['status' => false,'message'=>'You have some ongoing orders.'],422);
			}*/
		}
		if($request->device == 'web') {
			\Auth::logout();
			// return \Redirect::to('');
		} else {
			// $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
			auth('api')->logout();
		}
		return response()->json(['status' => true], 200);
	}

	/**
	 * Deactivate the user account (Invalidate the token and inactive the user).
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deactivate( Request $request)
	{
		$user	= auth('api')->user();
		$rules['device']	= 'required|in:ios';
		$this->validateDatas($request->all(),$rules);
		
		$loguser = clone $user; 
		$data		= User::find($user->id);
		if ($data->role_id == 2) {
			$orderCount	= Orderdetail::where('date','>=',date('Y-m-d'))->where('user_id',$user->id)->where(function($query) {
				return $query->where('status','!=', 'pending')->orWhere('status', '!=', 'completed')->orWhere('status', '!=', 'rejected_res')->orWhere('status', '!=', 'rejected_admin')->orWhere('status', '!=', 'cancelled');
			})->count();
			if ($orderCount > 0) {
				$response['message'] = "Some of your order's are incomplete state. So you cannot able to deactivate your account";
				return \Response::json($response,422);
			}
		}
		$module = "User"; $primarykey = $loguser->id; $record_id = $loguser->id; $record = "status"; $original = $loguser->status; $changes = "deleted"; $url = url()->current(); $ip = $request->ip();
		event(new LogActivitiyEvent($module,$primarykey,$record_id,$record,$original,$changes,$url,$ip));
		$appleId	= $data->apple_id;
		$data->email	= $data->apple_id	= $data->google_id	= $data->facebook_id = '';
		$data->name		= $data->profile_name	= 'Deleted User';
		$data->mobile	= 0;
		$data->status	= 'deleted';
		$data->avatar   = 'avatar.jpg';
		$data->log_status	= 'deactivated';
		$data->save();

		auth('api')->logout();
		$response['message']	= "Your account deactivated successfully";
		$response['apple_id']	= $appleId;
		return \Response::json($response,200);
	}

	/**
	 * User profile functionalities.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function userProfile( Request $request)
	{
		// $data   = $request->user();
		$data   = (object) array();
		$guard  = ($this->segment == 'api') ? 'api' : 'web';
		$data   = auth($guard)->user();

		$status     = 200;
		$nicenames  = array();
		$rules['function']  = 'required|in:show,edit,token_update,changepassword';
		$rules['device']    = 'required|in:android,ios,web';
		if ($request->function == 'edit') {
			$rules['mobile']    = 'required|numeric|unique:users,mobile,'.$data->id;
			$rules['location_id']   = 'required';
			if (!isset($request->updateFrom) && $request->updateFrom != 'checkout') {
				if(isset($data->role) && $data->role == 3) {
					$rules['cuisine_type']      = ['required','array'];
					$rules['cuisine_type.*']    = 'required|exists:cuisines,id';
				}
				$rules['email']     = 'required|email|unique:users,email,'.$data->id;
			} else {
				$rules['name']      = 'required';
				if(isset($request->email))
					$rules['email'] = 'required|email|unique:users,email,'.$data->id;
			}
		} elseif ($request->function == "token_update") {
			$rules['mobile_token'] = 'required';
		}
		if (!isset($request->updateFrom) && $request->updateFrom != 'checkout') {
			if( $request->hasFile('avatar'))
				$rules['avatar']    = ['required', 'mimes:png,jpeg,jpg', 'max:5000'];
			if(isset($request->password) && $request->password !== null && $request->device != 'web')
				$rules['password']  = ['required', 'min:6'];
		}
		$nicenames['cuisine_type']  = 'Cuisine type';
		self::validateDatas($request->all(),$rules,[],$nicenames);

		$user       = User::find($data->id,self::userBasicDatas());
		$userType   = ($user->role == 2 || $user->role == 1) ? 'user' : (($user->role == 3) ? 'chef' : '');
		if ($userType == '') {
			$status     = 422;
			$response['status']     = $status;
			$response['message']    = "This type of user cannot able to view or edit their profile from here.";
			return \Response::json($response,$status);
		}
		if ($request->function == 'edit') {
			if ($request->device != 'web' && isset($request->password) && $request->password !== null) {
				$user->password = Hash::make($request->password);
			}
			$user->name = $request->name;
			$user->mobile       = $request->mobile;
			$user->location_id  = $request->location_id;
			if(isset($request->email))
				$user->email    = $request->email;
			if (!isset($request->updateFrom) && $request->updateFrom != 'checkout') {
				if($user->role == 3)
					$user->cuisine_type  = /*implode(',',*/ $request->cuisine_type/*)*/;
				if( $request->hasFile('avatar')) {
					$filenameWithExt    = $request->file('avatar')->getClientOriginalName();
					$filename           = ($user->role == 3) ? 'chef' : 'user'; //pathinfo($filenameWithExt, PATHINFO_FILENAME);
					$extension          = $request->file('avatar')->getClientOriginalExtension();
					$fileNameToStore    = $filename.'_'.time().'.'.$extension;

					Storage::delete($user->avatarpath ?? null);
					$avatar_path        = $request->file('avatar')->storeAs('public/avatar', $fileNameToStore);
					$user->avatar       = $fileNameToStore;
				}
			}
			$user->save();
		} elseif ($request->function == 'token_update') {
			$user->mobile_token = $request->mobile_token;
			$user->save();
			$response['message'] = 'Mobile token updated successfully.';
		}
		$userdata   =  User::find($user->id,self::userBasicDatas());
		$response['user']   = $userdata;
		return \Response::json($response,$status);
	}

	public function updateCustomerProfile( Request $request)
	{
		request()->merge(['function' => 'edit','updateFrom' => 'checkout']);
		return self::userProfile(request());
	}

	/**
	 * Refresh a token.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function refresh()
	{
		return $this->respondWithToken(auth()->refresh());
	}

	/**
	 * Get the token array structure.
	 *
	 * @param  string $token
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function respondWithToken($token)
	{
		return response()->json([
			'access_token'  => $token,
			'token_type'    => 'bearer',
			'expires_in'    => auth('api')->factory()->getTTL() * 60
		]);
	}

	public function updateCartToUser($user_id, $cookie)
	{
		$uCartQuery = uCartQuery(0, $cookie);
		$userCart   = uCartQuery($user_id, 0);
		$ucart      = clone ($userCart); $upCart = clone ($uCartQuery);
		$ucart      = $ucart->get();
		$upCart     = $upCart->get();
		if (count($upCart) > 0) {
			if (count($ucart) > 0) {
				$adel = clone ($userCart);$ucdel = clone ($userCart);
				$adel->get()->map(function ($value) {
					$value->addon_item()->delete();
				});
				$ucdel->delete();
			}
			$upCart->map(function ($res,$index) use ($user_id, $uCartQuery) {
				$condition['where'] = ['where','where'];
				$condition['col']   = ['id','vendor_id'];
				$condition['cond']  = ['=','='];
				$condition['value'] = [$res->food_id,$user_id];
				$vendorFood = modelData('Menuitems', $condition);
				if (count($vendorFood) > 0) {
					$adel.$index = clone ($uCartQuery);$ucdel.$index = clone ($uCartQuery);
					$adel.$index->where('id', $res->id)->get()->map(function ($value) {
						$value->addon_item()->delete();
					});
					$ucdel.$index->where('id', $res->id)->delete();
				} else {
					$upItem.$index = clone ($uCartQuery);
					$menuitem = Menuitems::find($res->food_id);
					if($menuitem->discount != 0 && $menuitem->purchase_quantity != 0 && $menuitem->purchase_quantity_count == 0) {
						$upItem.$index->where('food_id',$res->food_id)->delete();
					}
					$upItem.$index->where('id',$res->id)->update(['user_id' => $user_id,'cookie' => 0]);
				}
			});
		}
	}
}