<?php
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\UserModule;
use App\Models\Manager;
use App\Models\roles;
use App\Models\User;
use App\Models\usermanage;
use App\Models\Translate;
use App\Models\Menu;
use App\Models\Menuitems;
use App\Models\Restaurants;
use App\Models\LogActivity;

use App\Models\Cart;
use App\Models\Chefs;
use App\Models\Addon;
use App\Models\Orderdetail;
use App\Models\SettingsBoyApi;
use App\Models\Offer;
use App\Models\Notification;
use App\Models\SiteSetting;
use App\Models\RzAccount;
use App\Models\Timeslotmanagement;
use App\Models\Time;
use App\Models\Timeslotcategory;
use App\Models\Cancellation;
use App\Models\Thirdparty;
use App\Models\DeliveryRetry;
use App\Models\DeliveryLog;
use App\Models\Order;
use App\Models\Referralsettings;
use App\Models\WalletHistory;
use App\Models\SettingsApiKey;
use Anam\PhantomMagick\Converter;
use App\Events\WalletActivity;
use App\Http\Controllers\RazorpayPaymentController;
use App\Models\Menuitems as Menuitem;
use Intervention\Image\Facades\Image;

function AppendMaster($Model,$arr)
{
	$Model = '\\App\\Models\\' . $Model;
	$Append = $Model::getDefaultAppends();
	$Append = array_merge($Append,$arr);
	$Model::setStaticAppends($Append);
}

function getModels($path)
{
	$out = [];
	$results = scandir($path);
	foreach ($results as $result) {
		if ($result === '.' or $result === '..') continue;
		$filename = $path . '/' . $result;
		if (is_dir($filename)) {
			$out = array_merge($out, getModels($filename));
		}else{
			$out[] = substr($result,0,-4);
		}
	}
	return $out;
}

function date_format_show($date)
{
	return !empty($date) ? date_format(date_create($date),'d M yy') : '';
}

function date_format_store($date)
{
	return !empty($date) ? date_format(date_create($date),'Y-m-d'): '';
}

function getTranslated( $table, $field_name, $field_fk,$default )
{
	$trans  = Translate::TranslateString( $table, $field_name, $field_fk)->pluck('content')->first();
	return $trans  != '' ? $trans  : $default;
}

function getTranslatedStatic( $key ) 
{
	return (object) Translate::TranslateString( 'translate', $key)->pluck('content','key')->toArray();
}

function uploadImage($fileval,$path,$oldimagename='',$prefix='')
{
	// $imageSize 			= getimagesize($fileval);
	$destinationPath	= base_path($path);
	// $destinationPath= base_path('/thumbnail');
	$extension		= $fileval->getClientOriginalExtension(); 
	$prefix 		= $prefix == '' ? '' : $prefix.'-';
	$newfilename	= $prefix.time().'-'.rand(100, 999).'.'.$extension;
	$imagetoUpload	= $fileval->getRealPath();
	if ($fileval->getMimeType() == 'image/png') {
		$imagetoUpload	= compress_png($fileval);
	}
	$img	= Image::make($fileval->getRealPath());
	$uploadSuccess	= $img->resize(1291, 391)->save($destinationPath.'/'.$newfilename, 30);

	// $uploadSuccess		= $fileval->move($destinationPath, $newfilename);
	if( $uploadSuccess ) {
		$data['image'] = $newfilename; 
		//Delete old File
		if(!empty($oldimagename)){
			unlink($path,$oldimagename);
		}
		$data['success'] = true;
	} else {
		$data['success'] = false;
		$data['id'] = '2';
		$data['message'] = 'Image is not valids';
	}
	return $data;
}

function compress_png($pathToPngFile, $maxQuality = 50)
{
	if (!file_exists($pathToPngFile)) {
		throw new \Exception("File does not exist: $pathToPngFile");
	}

	$min_quality = 30;
	$compressedPngContent = shell_exec("pngquant --quality=$min_quality-$maxQuality - < ".escapeshellarg(    $pathToPngFile));
	/*if (!$compressedPngContent) {
		throw new \Exception("Conversion to compressed PNG failed. Is pngquant 1.8+ installed on the server?");
	}*/
	return $compressedPngContent;
}

function ValidateImage($path , $image)
{
	if(!file_exists( base_path($path).$image) || empty($image))
	{
		return URL::to('/assets/front/img/default.svg');
	}
	return URL::to($path.$image);
}

function getRoles()
{
	return roles::get();
}

function getProfileImage()
{
	$image = \Auth::user()->image;
	if (!filter_var($image, FILTER_VALIDATE_URL)) { 
		$image = URL::to('/assets/front/img/profileimage/'.$image);
	}
	if(empty($image) || \Auth::user()->role != 5 || !@getimagesize($image))
	{
		$image = URL::to('/assets/front/img/profileimage/userprofileicon.jpg');
	}
	return $image;
}

function CheckAccess()
{
	if(\Auth::user()->role == 1 || \Auth::user()->role == 5/*&& request()->segment(2) != 'dashboard'*/) {
		return 1;
	} else {
		$segment	= request()->segments();
		$permission	= request()->method();
		$count		= count($segment);
		$accessPermission = 0;
		if ($count == 2) {
			if ($segment[1] == 'store') {
				$segment[1]	= $segment[0];
			}
			$accessPermission	= getUserModuleAccess($segment[1],$permission);
		} elseif($count == 3) { 
			if ($segment[1] == 'chef' && $segment[2] == 'request') {
				$accessPermission	= getUserModuleAccess('chef/request',$permission);
			} elseif($segment[1] == 'common') {
				if (empty(request()->type)) {
					$access	= $segment[2];
				} else {
					$access	= request()->type;
				}
				$accessPermission	= getUserModuleAccess('common/'.$access,$permission);
			} elseif($segment[1] == 'order') {
				$access				= 'order/'.$segment[2];	
				$accessPermission	= getUserModuleAccess($access,$permission);
			} elseif($segment[1] == 'customer') {
				if ($permission == 'edit') {
					$access	= 'customer/all';
				} else {
					$access	= 'customer/'.$segment[2];
				}
				$accessPermission = getUserModuleAccess($access,$permission);
			} elseif($segment[1] == 'earning_report') {
				$access				= 'earning_report/'.$segment[2];
				$accessPermission	= getUserModuleAccess($access,$permission);
			} elseif($segment[1] == 'payout') {
				$access = 'payout/'.$segment[2];
				if ($permission == 'edit') {
					$ref	= explode('/',Request::server('HTTP_REFERER'));
					$access	= 'payout/'.end($ref);
				}
				$accessPermission = getUserModuleAccess($access,$permission);
			} elseif(strripos( \Request::server('HTTP_REFERER'), 'common') !== false && $permission == 'DELETE') {
				$access				= 'common/'.$segment[1];
				$accessPermission	= getUserModuleAccess($access,$permission);
			} else {
				$accessPermission	= getUserModuleAccess($segment[1],$permission);
			}
		} elseif($count == 4) { 
			if ( $segment[1] == 'customer') {
				$permission	= 'edit';
				$ref		= explode('/',Request::server('HTTP_REFERER'));
				$name		= 'customer/'.end($ref);
			} elseif($segment[1] == 'chef') {
				$permission	= 'edit';
				$name		= $segment[1];
			} elseif(strripos( \Request::server('HTTP_REFERER'), 'common') !== false) {
				$name		= 'common/'.$segment[1];
				$permission	= $segment[3];
			} else {
				$name		= $segment[1];
			}
			$accessPermission = getUserModuleAccess($name,$permission);
		} elseif($count == 5) {
			if ($segment[1] == 'chef') {
				$permission			= 'edit';
				$accessPermission	= getUserModuleAccess($segment[1],$permission);
			} else {
				$accessPermission	= getUserModuleAccess($segment[3],$permission);
			}
		} elseif($count == 6) { 
			if ($segment[1] == 'chef') {
				$permission			= 'edit';
				$accessPermission	= getUserModuleAccess($segment[1],$permission);
			}
		}
		return $accessPermission;	
	}
}

function getAccessArray($modId)
{
	$accessData = UserModule::select('access')->where('user_id',\Auth::user()->id)->pluck('access')->first();
	$accessData =  !empty($accessData) ? json_decode($accessData): [];
	$DBid = array_search($modId,array_column($accessData, 'id'));
	if(!empty($accessData)){
		if(is_numeric($DBid)){
		return $accessData[$DBid]->access;
		}else{
			return [];
		}
	}else{
		return [];
	}
}

function getSideMenuMain()
{
	$menu	= Menu::where('m_id',0)->where('status','active')->orderBy('ordering','ASC')->get();
	return $menu;
}

function getSideMenuSub($m_id)
{
	$menu	= Menu::where('m_id',$m_id)->where('status','active')->orderBy('ordering','ASC')->get();
	return $menu;
}

function getnotificationlog()
{
	$notificationlog=LogActivity::where('is_read', '0')->where('before_change', '!=', '')->where('after_change', '!=', '')->orderBy('id','DESC')->get();
	return $notificationlog;
}

/**
* Validation Error 
*/
function getValidationErrorMsg($validator)
{
	$messages 	= $validator->messages();
	$error 		= $messages->getMessages();
	$val 		= 'Error';
	if(count($error) > 0){
		foreach ($error as $key => $value) {	
			$val= $value[0];
			break;		 	
		}
	}
	return $val;
}

function getVerifyCode($digits = 4)
{
	if($digits == 5){
		$code = mt_rand(10000,99999);
	} else if($digits == 6){
		$code = mt_rand(100000,999990);
	} else {
		$code = mt_rand(1000,9999);
	}
	$code = 1111;
	return $code;
}

function getAppStoreLinks()
{
	$app = \DB::table('options')->where('key','app_store')->first();
	if(!empty($app)){
		$aApp = json_decode($app->value);
		$data['android_playstore_url'] = $aApp->android_playstore_url;
		$data['android_playstore_image'] = $aApp->android_playstore_image;
		$data['ios_playstore_url'] = $aApp->ios_playstore_url;
		$data['ios_playstore_image'] = $aApp->ios_playstore_image;
	} else {
		$data['android_playstore_url'] = '';
		$data['android_playstore_image'] = '';
		$data['ios_playstore_url'] = '';
		$data['ios_playstore_image'] = '';
	}
	return $data;
}

function getUserModuleAccess($url,$permission='')
{
	if(\Auth::user()->role != 1)
	{
		$modId = getModuleIdbyUrl($url);
		$access = getAccessArray($modId);
		if(!empty($access))
		{
			$store = in_array('store',request()->segments());
			if($permission == 'GET' || $permission == 'view'){
				$method = 'view';
			}elseif($permission == "DELETE" || ( $permission == 'PUT' && $store == false ) || $permission == 'delete'){
				$method = 'remove';
			}else{
				$method = 'edit';
			}
			return $permission!= '' ? $access->$method:$access;
		}else
		{
			if($permission == '')
			{
				return (object) array('edit' => 0, 'view' => 0, 'create' => 0, 'global' => 0, 'remove' => 0, );
			}
			return 0;
		}
	}else
	{
		$adminAccess = (object) array('edit' => 1, 'view' => 1, 'create' => 1, 'global' => 1, 'remove' => 1, );
		return $permission!= '' ? 1:$adminAccess;
	}
}

function getModuleIdbyUrl($url)
{
	return Menu::select('id')->whereRaw('route REGEXP("('.($url).')")')/*->where('route' , $url)*/->pluck('id')->first();
}

function getRoleName()
{
	return (\Auth::user()->role == 1 || \Auth::user()->role == 5) ? 'admin' : 'vendor';
}

function getUserData($user_id)
{
	$user	= User::find($user_id,['id','name','email','mobile','role']);
	return (empty($user)) ? [] : $user;
}

function getModules()
{
	return Menu::/*orderBy('ordering','ASC')->*/get();
}

function modules_access_names()
{
	return array(/*"global",*/"view",/*"create",*/"edit","remove");
}

function getCommonImageUser()
{
	return \URL::to('storage/app/public/avatar.jpg');
}

function getCommonMenuItem()
{
	return \URL::to('storage/app/public/food.jpg');
}
function getCommonBanner()
{
	return \URL::to('storage/app/public/banner.jpg');
}
function uCartQuery($user_id, $cookie)
{
	return Cart::where(function($query) use ($user_id, $cookie) {
		if ($user_id == 0) {
			return $query->where('cart_detail.cookie', $cookie);
		} else {
			return $query->where('cart_detail.user_id', $user_id);
		}
	});
}

function uCartExistsCheck($user_id, $cookie)
{
	$valid	= $return = [];
	$ucart	= uCartQuery($user_id, $cookie)->where('is_preorder','no')->get()->map(function ($value) use ($return) {
		if ((date('Y-m-d') > $value->date)) {
			$return[] = '1';
		}
		$valid = $return;
		return $valid;
	});
	return $ucart;
}

function getReadabletimeFromSeconds($seconds)
{
	$dtF	= new \DateTime('@0');
	$dtT	= new \DateTime("@$seconds");
	$text	= '';
	$diff	= $dtF->diff($dtT);
	$days	= $diff->format('%a');
	$hours	= $diff->format('%h');
	$minutes	= $diff->format('%i');
	if($days > 0){
		if($text != ''){
			$text .= ', ';
		}
		$text .= $days.' days';
	}
	if($hours > 0){
		if($text != ''){
			$text .= ', ';
		}
		$text .= $hours.' hours';
	}
	if($minutes > 0){
		if($text != ''){
			$text .= ', ';
		}
		$text .= $minutes.' MINS';
	}
	return $text;
}

function calculate_distance($values)
{
	//$keys	= \SiteHelpers::site_setting('googlemap_key');
	$mapKey	= SettingsApiKey::find(1,['id','map_key']);
	// $keys	= 'AIzaSyAUqxCzqXHg1jeS_RUd4p4ukmVrcXckxYA';
	$keys	= $mapKey->map_key;
	$ch		= curl_init();
	if ($values['type'] == 'address') {
		$url = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins='.urlencode($values['from_address']).'&destinations='.urlencode($values['to_address']).'&mode=drive&sensor=false&key='.$keys;
	} elseif ($values['type'] == 'coordinates') {
		$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$values['lat1'].",".$values['long1']."&destinations=".$values['lat2'].",".$values['long2']."&mode=driving&language=pl-PL&key=".$keys;
	} else {
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($values['address']).'&mode=drive&sensor=false&key='.$keys; 
	}
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output	= curl_exec($ch);
	curl_close($ch);
	$rwes	= json_decode(json_encode(json_decode($output)));
	if($values['type'] == 'getLatLong') {
		if(!empty($rwes) && $rwes->status == 'OK') {
			$data['status']		= $rwes->status;
			$data['latitude']	= $rwes->results[0]->geometry->location->lat; 
			$data['longitude']	= $rwes->results[0]->geometry->location->lng;
		}
		return $data;
	}
	$data['rwes']		= $rwes;
	$data['status']		= false;
	$data['apiStatus']	= (!empty($rwes)) ? $rwes->status : [];
	$data['distance']	= $data['duration']	= $data['total_km']	= $data['total_time']	= 0;
	$data['durationText']	= '';
	if(!empty($rwes) && $rwes->status == "OK"){
		if($rwes->rows[0]->elements[0]->status == 'OK'){
			$data['distance']	 = $rwes->rows[0]->elements[0]->distance->value * 0.001;
			$data['duration']	 = $rwes->rows[0]->elements[0]->duration->value;
			$data['durationmin'] = $rwes->rows[0]->elements[0]->duration->value / 60;
			$data['durationText']= $rwes->rows[0]->elements[0]->duration->text;
			$data['status']		 = true;
			$data['total_km']	 = number_format(($data['distance']),2,'.','');
		} else {
			$data['apiStatus'] = $rwes->rows[0]->elements[0]->status;
		}
	}
	return $data;
}

function rangeMonth($datestr)
{
	date_default_timezone_set (date_default_timezone_get());
	$dt = strtotime ($datestr);
	return array (
		"start"	=> date ('Y-m-d', strtotime ('first day of this month', $dt)),
		"end"	=> date ('Y-m-d', strtotime ('last day of this month', $dt))
	);
}

function rangeWeek($datestr)
{ 
	$dt = strtotime ($datestr);
	return array (
		"start"	=> date ('N', $dt) == 1 ? date ('Y-m-d', $dt) : date ('Y-m-d', strtotime ('last monday', $dt)),
		"end"	=> date('N', $dt) == 7 ? date ('Y-m-d', $dt) : date ('Y-m-d', strtotime ('next sunday', $dt))
	);
}

function lastWeek()
{
	date_default_timezone_set (date_default_timezone_get());
	$monday	= strtotime("last monday");
	$monday	= date('W', $monday)==date('W') ? $monday-7*86400 : $monday;
	$sunday	= strtotime(date("Y-m-d",$monday)." +6 days");
	return array(
		"start"	=> date("Y-m-d",$monday),
		"end"	=> date("Y-m-d",$sunday),
	);
}

function lastMonth()
{
	date_default_timezone_set (date_default_timezone_get());
	return array (
		"start"	=> date ('Y-m-d', strtotime ('first day of previous month')),
		"end"	=> date ('Y-m-d', strtotime ('last day of previous month'))
	);
}

function getrestaurantdata()
{
	$restaurants = Restaurants::where('vendor_id',\Auth::user()->id)->first();
	return $restaurants;
}

function getShadowArray($status)
{
	$arr = array(
			"pending"=>"allot",
			"accepted_admin"=>"allot",
			"accepted_res"=>"allot",
			"food_ready"=>"allot",
			"accepted_boy"=>"update-rider-arrival",
			"reached_restaurant"=>"collect",
			"pickup_boy"=>"customer-doorstep-arrival",
			"reached_location"=>"deliver",
			);
	return isset($arr[$status]) ? $arr[$status] : $status;
}

function DunzoBoyStatus($status)
{
	$arr = array(
			"created"				=> "created",
			"queued"				=> "queued",
			"runner_accepted"		=> "accepted_boy",
			"reached_for_pickup"	=> "reached_restaurant",
			"pickup_complete"		=> "pickup_boy",
			"started_for_delivery"	=> "riding",
			"reached_for_delivery"	=> "reached_location",
			"delivered"				=> "completed",
			"runner_cancelled"		=> "rider_cancelled",
			"cancelled"				=> "order_cancelled",
			);
	return isset($arr[$status]) ? $arr[$status] : $status;
}

function ShadowBoyStatus($status)
{
	$arr = array(
			"ACCEPTED"					=> "created",
			"queued"					=> "queued",
			"ALLOTTED"					=> "accepted_boy",
			"ARRIVED"					=> "reached_restaurant",
			"DISPATCHED"				=> "pickup_boy",
			"started_for_delivery"		=> "riding",
			"ARRIVED_CUSTOMER_DOORSTEP"	=> "reached_location",
			"DELIVERED"					=> "completed",
			"CANCELLED"					=> "rider_cancelled",
			"CANCELLED_BY_CUSTOMER"		=> "order_cancelled",
		   );
	return isset($arr[$status]) ? $arr[$status] : $status;
}

function minutes($time)
{
	$time = explode(':', $time);
	return ($time[0]*60) + ($time[1]);
}

function getCreateOrderRequest($order)
{
	return Array(
		'order_id'		=> $order->id,
		'order_ref_id'	=> $order->s_id,
		'restaurant_lat'=> $order->restaurant->latitude,
		'restaurant_lng'=> $order->restaurant->longitude,
		'rest_building'	=> $order->restaurant->locality,
		'rest_address'	=> $order->restaurant->adrs_line_1,
		'rest_landmark'	=> $order->restaurant->landmark,
		'rest_city'		=> $order->restaurant->location_info->name,
		'rest_state'	=> $order->restaurant->state,
		'rest_pin_code'	=> $order->restaurant->zipcode,
		'cust_lat'		=> $order->order->getUserAddress->lat ?? '',
		'cust_lng'		=> $order->order->getUserAddress->lang ?? '',
		'cust_building'	=> $order->order->getUserAddress->building ?? '',
		'cust_address'	=> $order->order->getUserAddress->display_address ?? '',
		'cust_landmark'	=> $order->order->getUserAddress->landmark ?? '',
		'cust_city'		=> $order->order->getUserAddress->city ?? '',
		'cust_state'	=> $order->order->getUserAddress->state ?? '',
		'cust_pin_code'	=> $order->order->getUserAddress->pin_code ?? '',
		'restaurant_name'			=> $order->restaurant->name,
		'restaurant_phone_number'	=> $order->restaurant->vendor_info->mobile ?? '',
		'cust_name'			=> $order->userinfo->name,
		'cust_phone_number'	=> $order->userinfo->mobile,
		'grand_total'		=> $order->grand_total,
		'rider_note'		=> /*$order->notes*/env('APP_NAME').": Top Food Delivery Service",
		'payment_type'		=> 'online',
		'is_preorder'		=> 'no'
		//schedule_time		=>
	);
}

function getOrderStatusRequest($order)
{
	return Array(
		'api_orderid'=>$order,
	);
}

function getShadowOrderItems($order)
{
	$Food	= Orderdetail::find($order)->food_items;
	$s		= array_map(function($e) { 
		unset($e['addon']);
		unset($e['fPrice']);
		unset($e['unit']);
		$e['price']	= (float)$e['price'];
		$e['id']	= (string)$e['id'];
		return $e;
	},$Food);
	return $s;
}

function DunzoOrShadow($distance, $retry=false/*, $order_id*/)
{
	$field	= 'upto4';
	if ($distance > 4) {
		$field	= 'more4';
	}
	$api	= SettingsBoyApi::find(1)->pluck($field)->first();
	$api	= str_replace('fax','',$api);
	$api	= explode(',',$api);
	$cancelled	= [];
	$cancelled	= DeliveryLog::select(\DB::RAW('DISTINCT(`dealer`)'))->where('order_id',$order_id)->get(['dealer'])->toArray();
	$cancelled = (count($cancelled) > 0) ? [$cancelled[0]['dealer']] : [];
	if ($retry) {
		array_push($cancelled,$retry);
	}
	$api = array_diff($api,array_unique($cancelled));
	if (count(array_diff($api,array_unique($cancelled))) == 0 ) {
		return false;
	}
	/*if ($retry) {
		return $api[1];
	}*/
	$return = array_values($api);
	return $return[0];
}

function getDelCharge()
{
	return SettingsBoyApi::find(1)->amount;
}

function getMaxDistance()
{
	$nearby = SiteSetting::find(1)->nearby;
	return ($nearby > 0) ? $nearby : 15;
}

function getPromoCal($cid,$gtotal)
{
	$promos	= Offer::where('id',$cid)->active()->first();
	$result_promo = 0;
	if(isset($promos) && !empty($promos)){
		$promo_amount = 0;
		if($promos->offer > 0){
			if($promos->promo_type=='percentage'){
				$promo_amount = ($gtotal * ($promos->offer / 100));
			} else {
				$promo_amount = ($gtotal - $promos->offer);
			}
		}
        $result_promo = ($gtotal - $promo_amount);
		if(($promos->max_discount == 0) || ($promos->max_discount >= $promo_amount)) {
			$result_promo = $promo_amount;
		} else{
			$result_promo = $promos->max_discount;
		}
	}
	return $result_promo;
}

function getNotification($from,$to,$type,$url,$title,$note)
{
	$notification = new Notification;
	$notification->from	= $from;
	$notification->to	= $to;
	$notification->type	= $type;
	$notification->url	= $url;
	$notification->title= $title;
	$notification->note	= $note;
	$notification->save();
}

function getStockMenu($restaurant,$menu,$date='',$max='',$want='')
{
	$result = false;
	if(empty($max)){	
		$max = Menuitems::find($menu)->quantity;
	}
	if(empty($date)){	
		$date = date("Y-m-d");
	}
	$date = date_format_store($date);
	$used	= Orderdetail::where('res_id',$restaurant)->where('date',$date)->get()->map(function($result)use($menu) {
					if(isset($result->food_items[0]['id']) && $result->food_items[0]['id'] == $menu) {
						return $result->food_itemsj[0]['quantity'];
					}
				})->sum();
	$remaining = $max-$used;
	if($max == 0){
		$result = true;
	}else{
		if(!empty($want)){
			if($remaining >= $want){
				$result = true;
			}
		}
	}
	$resultData['result'] 	 = $result;
	$resultData['remaining'] = $remaining;
	return $resultData;
}

function modelData($modelName, $condition = array())
{
	$modelName	= '\\App\\Models\\' . $modelName;
	$data		= new $modelName;
	$count		= count($condition['where']);
	for ($i=0; $i < $count; $i++) {
		$data = $data->where($condition['col'][$i], $condition['cond'][$i], $condition['value'][$i]);
	}
	$data	= $data->get();
	return $data;
}

function getPayoutReport($vendor_id,$from='',$to='',$webhook=false)
{
	$data	= array();
	$cmplet	= $deduct	= $cPrice	= $vPrice	= $addi_amount	= $deduc_amount	= 0;
	$data['orderid']= [];
	$query	= Orderdetail::where('vendor_id',$vendor_id);
	if(!empty($from) && !empty($to)) {
		$query  = $query->where(function ($whr) use ($from, $to) {
			$whr/*->addSelect('order_detail.*')*/->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($from)),date('Y-m-d',strtotime($to))]);
		});
	}
	$statusarr  = ['accepted_boy','food_ready','rejected_boy','pickup_boy','riding','accepted_res','accepted_admin','completed','reached_location','reached_restaurant'];
	$cmplet	    = clone ($query);
	$cmplet		= $cmplet->whereIn('status',$statusarr);
	$cmpletcnt	= clone ($cmplet); $vPrice	= clone ($cmplet);
	$vPrice		= $vPrice->sum('total_food_amount');
	$cPrice		= $cmplet->sum('commission_amount');
	$cmpletcnt	= $cmpletcnt->count();
	$query	= $query->whereIn('status',['completed','rejected_admin','rejected_res']);
	$deduct	= clone ($query);
	$deduct	= $deduct->withCount([
		'cancellationData AS chefPenalty' => function ($query) {
			$query->select(DB::raw("SUM(chef_penalty)"));
		}
	])->get();
	$deduct = $deduct->sum('chefPenalty');
	$query	= $query->get()->map(function ($value) use ($webhook) {
		if($webhook)
			$data['orderid'][$key]	= $value->id;
	});
	$deduct			= $deduct + $cPrice;
	$net_receivable	= abs(($vPrice + $addi_amount) - $deduct);
	$data['gross_revenue']['amount']		= $vPrice;
	$data['gross_revenue']['order_count']	= $cmpletcnt;
	$data['additions']['amount']			= $addi_amount;
	$data['deductions']['amount']			= $deduct;
	$data['net_recievable']['amount']		= number_format($net_receivable,2);
	$data['transferable']['amount']			= number_format($net_receivable,2);
	return json_encode($data);
}

function createRZContact($chef,$save)
{
	$req = Array(
		"name"        =>$chef->name,
		"email"       =>$chef->email,
		"contact"     =>$chef->mobile,
		"reference_id"=>$chef->id,
		"action"      => "insert",
	);
	$call = true;
	if($save != 'new'){
		$rz   = RzAccount::where('chef',$chef->id)->first();
		if(!empty($rz)){
			$res               = array_intersect_key($chef->getChanges(),$req);
			$req['contact_id'] = $rz->contact;
			$req["action"]     = "update";
			$call              = count($res) > 0 ? true : false;
		}
	}
	if($call){
		$contact = app()->call('App\Http\Controllers\Api\Razor\PayoutsController@createContact',['request' => new Request($req)]);
		if($contact->status() == 200){
			$c = $contact->getData()->data;
			$Acc = RzAccount::updateOrCreate(
				['chef' => $chef->id],
				['contact' => $c->id,'contact_status'=>$c->active,'chef'=>$chef->id]
			);
		}
	}
}

function timeSlot($timing='00:00:00')
{
	$exp_time = explode(':',$timing);
	if($exp_time[1] < 30){
		$exp_time[1] = "00";$exp_time[2] = "00";
		$timing = implode(':',$exp_time);
	}
	$time = Time::selectRaw('GROUP_CONCAT(`id`) as times')->where('timing', '>=', $timing)->first();
	$timeSlots	= Timeslotcategory::with('slots')->where('status','active')->get();
	if (!empty($time)) {
		$timings	= explode(',', $time->times);
		foreach ($timeSlots as $key => $value) {
			foreach ($value->slots as $ke => $val) {
				if (!(in_array($val->start, $timings))) {
					$val->status = 'inactive';
				}
			}
		}
	}
	return $timeSlots;
}

function FCM($token,$title,$body)
{
	if(empty($token))
		return false;
	$req = Array(
		"title"	=> $title,
		"body"	=> $body,
		"token"	=> $token,
	);
	return $result = app()->call('App\Http\Controllers\Api\Google\GoogleController@Fcm',['request' => new Request($req)]);
}

function RazorRefund($Order, $amount="")
{
	$pay	= new RazorpayPaymentController;
	return $pay->refund($Order,$amount);
}

function ccMasking($number, $maskingCharacter = 'X')
{
	return str_repeat($maskingCharacter, strlen($number) - 8) . substr($number, -4);
}

function Thirdparty($dealer='',$order='')
{
	// dealer = 'razor', 'razorX', 'dunzo', 'shadow', ''
	$log = ['URI' => \Request::fullUrl(),'REQUEST_BODY' => \Request::all(),'HEADER' => \Request::header(),];
	$Data = new Thirdparty;
	$Data->dealer	= $dealer;
	$Data->order_id	= $order;
	$Data->content	= json_encode($log);
	$Data->save();
}

function DeliveryRetry($dealer='',$order='',$status='',$reason='')
{
	//
	$Data 			= new DeliveryRetry;
	$Data->dealer	= $dealer;
	$Data->order_id	= $order;
	$Data->status	= $status;
	$Data->reason	= $reason;
	$Data->save();
}

function DeliveryLog($dealer='',$order='',$status='',$reason='')
{
	strtolower($dealer);
	if ($dealer == 'shadow')
		 $dealer = str_replace('shadow', 'shadowfax', $dealer);
	$Data 			= new DeliveryLog;
	$Data->dealer	= $dealer;
	$Data->order_id	= $order;
	$Data->status	= $status;
	$Data->message	= $reason;
	$Data->save();
}

function CancellationSave($value)
{
	$cancelled	= new Cancellation;
	$cancelled->order_id		= $value['order_id'];
	$cancelled->customer_penalty= $value['customer_penalty'];
	$cancelled->chef_penalty	= $value['chef_penalty'];
	$cancelled->sub_order_id	= $value['sub_order_id'];
	$cancelled->save();
}

function chefPromos($promo_id)
{
	$data['apply_status'] = true;
	$offers = Offer::where('id',$promo_id)->first();
	if($offers->res_status == 'selected'){
		$data['chef_ids'] = explode(',',$offers->restaurant);
		$data['apply_status'] = false;
		// $data['restaurant_ids'] = Restaurants::select('id')->whereIn('vendor_id',$chef_ids)->get();
	}
	return $data;
}

function chefTotalPrice($userid,$chef_id,$cond='',$timeslot = '',$date = '')
{
	$total_price = 0;
	$chef	= Chefs::with('getChefRestaurant');
	if (!empty($cond)) {
		$chef	= $chef->whereIn('id',$chef_id);
	} else {
		$chef	= $chef->where('id',$chef_id);
	}
	$chef	= $chef->approved()->haveinfo();
	if(cheftype($chef_id) != "event") {
		$chef	= $chef->havemenus();
	} 
	$chef   = $chef->get()->map(function($res,$key) use($total_price,$userid,$timeslot,$date){
		$total_price	= Cart::where('user_id',$userid)->where('res_id',$res->getChefRestaurant->id);
		if($timeslot != '' && $date != ''){
			$total_price= $total_price->where('time_slot',$timeslot)->where('date','=',$date);
		}
		$total_price	= $total_price->sum('price');
		return $total_price;
	})->toArray();
	$tot_price = array_sum($chef);
	return number_format($tot_price,2,'.','');
}

function unitName($unit_id)
{
	$unit_name = Addon::select('name')->where('id',$unit_id)->where('type','unit')->first();
	return !empty($unit_name) ? $unit_name->name : '';
}

function imageConvert($order_id,$action='')
{
	$resultData = Orderdetail::where('id',$order_id)->first();
	$html = (string) view('order.orderimage',compact('resultData'));
	if($action == "kot"){
		return $html; 
	}
	\File::put(public_path('report.html'), $html);
	$path = public_path('report.html');
	$conv = new Converter();
	$conv->source($path)->toJpg()->portrait()->width('292')->quality(100)->save(storage_path('app/public/kot/bill'.$order_id.'.jpg'));
	\File::delete(public_path('report.html'));
	return true;
}

function chefId($res_id)
{
  $v_id = Restaurants::select('vendor_id')->where('id',$res_id)->first();
  return $v_id->vendor_id;
}

function new_category($res_id,$cate_id)
{
	$menuitem   = Menuitems::select(\DB::raw('GROUP_CONCAT(main_category) as Cats'))->where('restaurant_id',$res_id)->approved()->first();
	$menui = explode(',', $menuitem->Cats);
	if(in_array($cate_id, $menui)){
		return false;
	}
	return true;
}

function chefApprovedMenuCatgories($chef)
{
	$category	= $chef->food_items->toArray();
	$category	= array_column($category, 'menuscatogory');
	$category	= array_values(array_unique(array_column($category, 'id')));
	return $category;
}

function user_agent()
{	
	if(preg_match("/Android|webOS/", $_SERVER['HTTP_USER_AGENT'])){
		return 'Android';
	} elseif (preg_match("/iPhone|iPad|iPod/", $_SERVER['HTTP_USER_AGENT'])) {
		return 'Ios';
	} else {
		return 'pc';
	}
}

function PromoChef($chefs,$cartquery)
{ 
	$promochef = 0;
	foreach ($cartquery->get() as $key => $value) {
		$chef_id = chefId($value->res_id);
		$chef_check = in_array($chef_id,$chefs);
		if($chef_check) {
			$promochef = 1;
		}
	}
	return $promochef;
}

function tags_status($tags)
{
	$tag['none'] = $tag['must_try'] = $tag['bestsell'] = $tag['special'] = 0;
	if($tags != 'newmenu') {		
		$menu_tags = explode(',',$tags);
		foreach($menu_tags as $key => $val) {
			if($val == 'none')
				$tag['none'] = 1; 
			else if($val == 'must try')
				$tag['must_try'] = 1; 
			else if($val == 'chef special')
				$tag['special'] = 1; 
			else if($val == 'bestseller')
				$tag['bestsell'] = 1; 
		}
	}
	return $tag;
}

function cheftype($id)
{
	$chef = Chefs::find($id,['id','type']);
	return isset($chef) ? $chef->type : '';
}

function referralgeneration() 
{
	$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$code = "";
	for ($i = 0; $i < 7; $i++) {
		$code .= $chars[mt_rand(0, strlen($chars)-1)];
	}
	return $code;
}

function wallet_debit($debit_amt,$user,$order_id) 
{
	$ref_settings = Referralsettings::find(1);
	if($debit_amt != 0) {
		$user->wallet = $user->wallet - $debit_amt;
		$user->save();
		manage_wallet_history('order_placed_debit',$user->id,$debit_amt,$order_id);
	}
	if($user->orderscount < $ref_settings->referral_user_orders_count && $user->referer_user_id != 0) {
		$referrer_user = User::find($user->referer_user_id);
		$referrer_user->wallet = $referrer_user->wallet + $ref_settings->referer_user_credit_amount;
		$referrer_user->save();
		manage_wallet_history('order_placed_credit',$referrer_user->id,$ref_settings->referer_user_credit_amount);
	}  
}

function manage_wallet_history($from,$user_id,$amount,$order_id = '')
{
	$userData = User::find($user_id);
	if($from == "registration") {
		$type    = 'credit';
		$notes   = "Register with your friend code(".$userData->referrer_code.")";
		$message = "Your Knosh Wallet has been credited with ₹".$amount.".";
	} elseif ($from == "order_placed_credit") {
		$type    = 'credit';
		$notes   = "Your friend place an order";
		$message = "Your Knosh Wallet has been credited with ₹".$amount."."; 
	} elseif ($from == "order_placed_debit") {
		$MainOrderid = (100000+$order_id);
		$type    = 'debit';
		$notes   = "Wallet amount used for orders";
		$message = "Your Knosh Wallet has been debited with ₹".$amount." for Order no #".$MainOrderid;
	} elseif ($from == "admin_credit") {
		$type    = 'credit';
		$notes   = "Gift from KNOSH";
		$message = "Your Knosh Wallet has been credited with ₹".$amount.".";
	} elseif ($from == "admin_debit") {
		$type    = 'debit';
		$notes   = "Admin debited amount";
		$message = "Your Knosh Wallet has been debited with ₹".$amount;
	}
	$arr['user_id'] = $userData->id; 
	$arr['amount']  = $amount;
	$arr['type']    = $type;
	$arr['notes']   = $notes;
	$arr['balance'] = $userData->wallet;
	$wallet_history = WalletHistory::create($arr);
	if($order_id) {
		$order = Order::find($order_id);
		$order->wallet_history_id = $wallet_history->id;
		$order->save(); 
	}
	/* for send mail,notification for users */
	event(new WalletActivity($userData,$type,$message,$amount)); 
}

function cart_unavailable_check($foodtype,$cartdata)
{
	$message 	 = 'available';
	$unavailableAttr = false; 
	if($foodtype == "menuitem") {	
		foreach($cartdata as $key => $unavailable) {
			if($unavailable->ordertimeslotavailable['status'] == false){
				$unavailableAttr = true;
				break; 
			}
		} 
		if(!$cartdata->isEmpty() && $unavailableAttr == true) {
			$log = ['URI' => \Request::fullUrl(),'REQUEST_BODY' => $cartdata,'HEADER' => \Request::header()];
			\DB::table('tbl_http_logger')->insert(['request'=>'unavailable_deliveryslot','header'=>json_encode($log)]);
			$message = 'Please select the next delivery slot.'; 
		}

		if (!$cartdata->isEmpty() && $cartdata[0]->address_id == 0) {
			$log = ['URI' => \Request::fullUrl(),'REQUEST_BODY' => $cartdata,'HEADER' => \Request::header()];
			\DB::table('tbl_http_logger')->insert(array('request'=>'address_check','header'=>json_encode($log)));
			$message = 'Choose address to proceed.'; 
			$unavailableAttr = true;
		}	
	} elseif($foodtype == "home_event_menu") {
		if (!$cartdata->isEmpty() && $cartdata[0]->address_id == 0) {
			$log = ['URI' => \Request::fullUrl(),'REQUEST_BODY' => $cartdata,'HEADER' => \Request::header()];
			\DB::table('tbl_http_logger')->insert(array('request'=>'address_check','header'=>json_encode($log)));
			$message = 'Choose address to proceed.'; 
			$unavailableAttr = true;
		}		
	} else {
		foreach($cartdata as $key => $unavailable) {
			if($unavailable->eventavailable == 'not_avail'){
				$unavailableAttr = true;
				break; 
			}
		}
		if(!empty($cartdata) && $unavailableAttr == true) {
			$message = "Please remove the unavailable events from the cart.";
		} 
	}

	$response = array('message' => $message,'unavailable' => $unavailableAttr);
	return $response;
}

?>

