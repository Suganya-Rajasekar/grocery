<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use App\Models\User as Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Flash;
use App\Models\Chefs;
use App\Models\Chefsreq;
use App\Models\Cuisines;
use App\Models\Restaurants;
use App\Models\Locations;
use App\Models\Location;
use App\Models\Commondatas;
use App\Models\UserDocuments;
use App\Models\RzAccount;
use App\Events\RestaurantDefault;
use App\Exports\ChefExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Menuitems;
use App\Http\Controllers\Api\Razor\PayoutsController;
use DB;

class ChefController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	/*public function __construct()
	{
		$this->middleware('auth');
	}*/

	/**
	* Show the application dashboard.
	*
	* @return \Illuminate\Contracts\Support\Renderable
	*/
	public function index(Request $request)
	{
		$pageCount	= 10;
		$page	= (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
		$v_id	= $request->v_id;
		$city	= Locations::all();
		$user	= Users::find($v_id,['id','package','amount']);
		if ($v_id!='') {
			$restaurant     = Restaurants::with('rz_account')->select('*','tags as tag')->where('vendor_id',$v_id)->first();
			if (!$restaurant) {
				$restaurant = [];
			}
			$restaurant->opening_time  = date("g:i a", strtotime($restaurant->opening_time));
			$restaurant->closing_time  = date("g:i a", strtotime($restaurant->closing_time));
			$restaurant->opening_time2 = date("g:i a", strtotime($restaurant->opening_time2));
			$restaurant->closing_time2 = date("g:i a", strtotime($restaurant->closing_time2));
			$tags   = Commondatas::where('type','tag')->where('status','active')->get();
			$budget = Commondatas::where('type','budget')->where('status','active')->get();
			return view('chef.business',compact('restaurant','city','tags','budget','v_id','user'));
		} else {
			$chefs	= Chefs::notpending()->with('getChefRestaurant');
			if (isset($request->location_id) && $request->location_id != '') {
				$chefs->whereHas('getChefRestaurant' , function($query) use($request) {
					$query->where('location',$request->location_id);
				});
			} else {
				$chefs->with(['getChefRestaurant']);
			}
			if (isset($request->mode) && $request->mode != '') {
				$chefs->whereHas('singlerestaurant' , function($query) use($request) {
					$query->where('mode',$request->mode);
				});
			}
			if (isset($request->date) && $request->date != '') {
				$sDate  = explode(" - ",$request->date);
				$chefs->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
			}
			if (isset($request->search) && $request->search != '') {
				$chefs->where(function($query) {
					$query->orWhere('name', 'like', '%'.request()->search.'%')->orWhere('email', 'like', '%'.request()->search.'%')->orWhere('mobile', 'like', '%'.request()->search.'%')->orWhere('user_code', 'like', '%'.request()->search.'%');
				});
			}
			if ($request->query('status') != '') {
				$chefs = $chefs->where('status',$request->query('status'));
			}
			if(isset($request->cuisines) && $request->cuisines != ''){
				$chefs = $chefs->where('cuisine_type',$request->cuisines);
			}
			if(isset($request->cheftype) && $request->cheftype != ''){
				$chefs = ($request->cheftype == 'Celebrity') ? $chefs->celebrity() : (($request->cheftype == 'Promoted') ? $chefs->promoted() : $chefs->certified());
			}
			if(isset($request->type) && $request->type != '') {
				$chefs = ($request->type == 'event') ? $chefs->where('type','event') : (($request->type == 'home_event') ? $chefs->where('type','home_event') : ($chefs->where('type','chef'))); 
			}
			if (isset($request->ordering) && $request->ordering != '') {
				$chefs	= $chefs->orderBy('ordering',strtoupper($request->ordering));
			} else {
				$chefs	= $chefs->orderBy('id','DESC');
			}
			$chefs		= $chefs->paginate(10);

			$chefreq	= false;
			$cuisines	= Cuisines::get();
			return view('chef.index',compact('chefs','chefreq','page','city','cuisines'));
		}
	}

	public function show(Request $request)
	{
		$restaurant = Restaurants::all();
		$city       = Locations::all();
		$pageCount  = 10;
		$page       = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
		$chefs      = Chefs::pending();
		if (isset($request->location_id) && $request->location_id != '') {
			$chefs->whereHas('singlerestaurant' , function($query) use($request) {
				$query->where('location',$request->location_id);
			});
		} else {
			$chefs->with(['singlerestaurant']);
		}
		if (isset($request->date) && $request->date != '') {
			$sDate  = explode(" - ",$request->date);
			$chefs->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
		}
		if (isset($request->search) && $request->search != '') {
			$chefs->where(function($qy) use($request){
				$qy->where('name','like','%'.$request->search.'%')->orWhere('email', 'like', '%'.$request->search.'%')->orWhere('mobile', 'like', '%'.$request->search.'%')->orWhere('user_code', 'like', '%'.$request->search.'%');
			});
		}
		$chefs      = $chefs->orderBy('id','DESC')->paginate(10);
		$chefreq    = true;
		$cuisines   = Cuisines::get();
		return view('chef.index',compact('chefs','chefreq','page','city','restaurant','cuisines'));
	}

	public function create()
	{
		$chefs      = [];
		$cuisines   = Cuisines::all();
		$city       = Locations::all();
		$restaurant = Restaurants::all();
		$id         = '';
		$chefpage   = false;
		return view('chef.form',compact('chefs'),compact('cuisines','city','chefpage','id','restaurant'));
	}

	public function edit(Request $request, $id)
	{
		$userdocument   = $restaurant = [];
		$userData   = Users::find($id,['role']);
		if ($userData->role == 2) {
			Flash::error('Customers details can not be editable from here');
			$request->flash();
			$redirect = (getRoleName() == 'admin') ? getRoleName().'/chef' : getRoleName().'/dashboard';
			return \Redirect::to($redirect);
		}
		$chefs      = Chefs::notpending()->find($id);
		if (!$chefs)
			$chefs  = Chefs::pending()->find($id);

		$cuisines   = Cuisines::all();
		$city       = Locations::all();
		$restaurant = Restaurants::select('*','tags as tag')->where('vendor_id',$id)->first();
		$chefpage   = false;
		if ($chefs->status == 'approved' || $chefs->status == 'suspended' || $chefs->status == 'cancelled') {
			$chefpage   = true;
		} else {
			$userdocument   = UserDocuments::where('user_id',$id)->get();
		}
		return view('chef.form',compact('chefs'),compact('cuisines','city','restaurant','chefpage','id','userdocument'));
	}

	public function update( Request $request)
	{
		$v_id   = $request->v_id;
		$nicenames = [];
		if($v_id == '') {
			$rules['name']          = ['required', 'string', 'max:255'];
			$rules['profile_name']  = ['required', 'string', 'max:255'];
			if(!isset($request->type)) {
				$rules['email']         = ['required', 'email', 'max:255', 'unique:users,email,'.$request->c_id];
				if ($request->c_id == 0) {
					$rules['password']  = ['required', 'min:6','same:confirm_password'];
					$rules['confirm_password']  = ['required', 'min:6'];
				}
				$rules['mobile']        = ['required', 'numeric','unique:users,mobile,'.$request->c_id];
				if( $request->hasFile('avatar') ) {
					$rules['avatar'] = 'required|mimes:jpeg,jpg,png';/*|dimensions:max_width=1024,max_height=1024*/
				}
				if ($request->c_id == 0) {
					$rules['aadar_image']= 'required|mimes:jpeg,jpg,png,pdf|max:2048';
				}
				$rules['cuisine_type']  = ['required', 'array'];
				$rules['cuisine_type.*']= ['required', 'exists:cuisines,id'];
			}
			$rules['location_id']   = ['required', 'numeric'];
			if(\Auth::user()->role == 1){
				$rules['status']        = ['required', 'in:pending,approved,suspended,cancelled'];
				if ($request->status == 'suspended' || $request->status == 'cancelled') {
					$rules['reason']        = ['required'];
				}
			}
			$nicenames['name']          = 'Name';
			$nicenames['email']         = 'Email';
			$nicenames['location_id']   = 'Phone Code';
			$nicenames['mobile']        = 'Mobile';
			$nicenames['cuisine_type']  = 'Cuisine';
			$nicenames['password']          = 'Password';
			$nicenames['confirm_password']  = 'Confirm Password';
			if(\Auth::user()->role == 1) {
				if(isset($request->celebrity)){
					$rules['celebrity'] = ['required', 'in:yes,no'];
					$rules['promoted']  = ['required', 'in:yes,no'];
				}
				if ($request->celebrity == 'no') {
					$rules['certified'] = ['required', 'in:yes,no'];
				}
			}
		} else {
			if (isset($request->tags)) {
				$rules['tags']      = 'required|array';
				$rules['tags.*']    = 'required|exist_check:common_datas,where:type:=:tag-whereIn:id:'.implode('~', $request->tags);    
			}
			// $rules['budget']    = 'required|numeric|exists:common_datas,id';
			if($request->type != 'home_event') {
				$rules['budget']     = 'required|numeric';
			}
			$rules['adrs_line_1']= 'required';
			$rules['location']   = 'required|numeric|exists:locations,id';
			$rules['description']       = 'required';
			$rules['tax']    = 'required';
			if(isset($request->type) && $request->type != 'event') {
				$rules['adrs_line_2']= 'required';
				$rules['longitude']  = 'required';
				$rules['latitude']   = 'required';
				if($request->type != 'home_event') {
					$rules['fssai']      = 'required|numeric';
					$rules['preparation_time']  = 'required|in:preorder,ondemand';
				}
				if(\Auth::user()->role == 1 ||\Auth::user()->role == 5) {
					$rules['commission']    = 'required';
				}
			} elseif(isset($request->type) && $request->type == 'event') {
				$rules['event_date_time']   = 'required';
			}
			$nicenames['adrs_line_1'] = 'Address';
		}
		$nicenames['aadar_image'] = 'Aadhar';
		$nicenames['fssai_certificate'] = 'FSSAI';
		$response = $this->validateDatas($request->all(),$rules,[],$nicenames,'web');
		if (!empty($response)) {
			Flash::error($response['message']);
			$request->flash();
			return \Redirect::back()->withErrors($response['validator'])->withInput();
		}
		$location   = Location::where('id',$request->location)->first();
		$code       = '';
		if($location){
			$code = $location->code;
		}
		/*Take last record under the location*/
		$lastLoc = Chefs::selectRaw(" CAST(SUBSTRING_INDEX(user_code, '-', -1) AS UNSIGNED) as user_code_no")->whereRaw("SUBSTRING_INDEX(user_code, '-', 1) = '".$code."'")->orderByDesc('user_code_no')->first();
		$lastLocCount = 1;
		if(!empty($lastLoc) && is_numeric($lastLoc->user_code_no)){
			$lastLocCount = $lastLoc->user_code_no + 1;
		}/* else {
			$lastLocCount = $lastLoc.'-'$lastLocCount;
		}*/
		/*Take last record under the location end*/
		$function   = 'pending';
		$save       = 'update';
		if($v_id == '') {
			$chefreq    = false;
			if($request->c_id > 0) {
				$chef   = Chefs::notpending()->find($request->c_id);
				if (!$chef) {
					$chef       = Chefs::pending()->find($request->c_id);
					$chefreq    = true;
				}
			} else {
				$chef = new Chefs;
				$save = 'new';
			}
			$ven_id = ($request->c_id) ? $request->c_id : $v_id;
			$check  = Restaurants::where('vendor_id',$ven_id)->first();

			$chef->name         = $request->name;
			$chef->profile_name = $request->profile_name;
			$chef->email        = strtolower($request->email);
			$chef->individual_email_1 = strtolower($request->individual_email_1);
			$chef->individual_email_2 = strtolower($request->individual_email_2);
			$chef->mobile       = (isset($request->type)) ? '' : $request->mobile;
			$chef->location_id  = $request->location_id;
			if(\Auth::user()->role == 1) {
				$chef->celebrity    = isset($request->celebrity) ? (($request->homeevent == 'yes') ? 'no' : $request->celebrity) : 'no';
				$chef->promoted     = isset($request->promoted) ? (($request->homeevent == 'yes') ? 'no' : $request->promoted) : 'no';
				$chef->certified    = isset($request->certified) ? (($request->celebrity == 'yes' || $request->homeevent == 'yes') ? 'no' : $request->certified) : 'no' ;
				$chef->home_event   = isset($request->homeevent) ? $request->homeevent :'no';

			}
			$chef->role = 3;
			if (isset($request->cuisine_type) && count($request->cuisine_type) > 0) {
				$chef->cuisine_type = implode(",", $request->cuisine_type);
			}
			if ($request->password != '') {
				$chef->password     = Hash::make($request->password); 
			}
			$chef->status   = $request->status;
			$chef->reason   = ($request->status == 'suspended' || $request->status == 'cancelled') ? $request->reason : '';
			if (is_null($check) || $request->location != $check->location) {
				$chef->user_code    = $restaurant['user_code']  = $code.'-'.$lastLocCount;
			}
			if( $request->hasFile('avatar')) {
				$filenameWithExt= $request->file('avatar')->getClientOriginalName();
				$filename       = pathinfo($filenameWithExt, PATHINFO_FILENAME);
				$extension      = $request->file('avatar')->getClientOriginalExtension();
				$fileNameToStore= 'chef_'.time().'.'.$extension;
				Storage::delete($chef->avatarpath ?? null);
				$avatar_path    = $request->file('avatar')->storeAs('public/avatar', $fileNameToStore);
				$chef->avatar   = $fileNameToStore;
			}
			$chef->type = isset($request->type) ? 'event' : (($request->homeevent == 'yes') ? 'home_event' : 'chef');
			/*echo "<pre>";
			print_r($chef);exit;*/
			$chef->save();
			if(!isset($request->type)) {
				createRZContact($chef,$save);
			}			
			$function = ($request->status == 'approved') ? 'approved' : 'pending' ;
			$restaurant['vendor_id']      = $chef->id;
			//$restaurant['name']           = $request->name;
			$restaurant['name']           = $request->profile_name;
			$restaurant['email']          = isset($request->email) ? $request->email : '';
			$restaurant['status']         = $function;
			$restaurant['location']       = $request->location;
			if (isset($request->cuisine_type) && count($request->cuisine_type) > 0) {
				$restaurant['cuisines']   = implode(",", $request->cuisine_type);
			}
		} else { 
			$function = 'approved';
			// $restaurant['name']         = $request->name;
			$restaurant['name']         = $request->profile_name;
			$restaurant['tags']         = (isset($request->tags)) ? /*implode(',',*/ $request->tags/*)*/ : '';
			$restaurant['budget']       = $request->budget;
			$restaurant['fssai']        = $request->fssai;
			$restaurant['location']     = $request->location;
			$restaurant['description']  = $request->description;
			$restaurant['latitude']     = $request->latitude;
			$restaurant['longitude']    = $request->longitude;
			$restaurant['adrs_line_1']  = $request->adrs_line_1;
			$restaurant['adrs_line_2']  = $request->adrs_line_2;
			$restaurant['locality']     = $request->locality;
			$restaurant['landmark']     = $request->landmark;
			$restaurant['preparation_time']  = $request->preparation_time;
			$restaurant['preorder']          = ($request->preparation_time == 'preorder') ? 'yes' : 'no';
			$restaurant['tax']    = $request->tax;
			$restaurant['package_charge']    = $request->package_charge;
			$restaurant['event_datetime']    = isset($request->event_date_time) ? $request->event_date_time : '';
			$restaurant['sector']    = isset($request->sector) ? $request->sector : '';
			if(\Auth::user()->role == 1 ||\Auth::user()->role == 5) {
				$restaurant['commission']    = $request->commission;
			}
			$chef = Chefs::find($v_id);
			$chef->profile_name = $request->profile_name;
			if ($request->location != $chef->singlerestaurant->location) {
				$chef->user_code    = $restaurant['user_code']  = $code.'-'.$lastLocCount;
			}
			$chef->save();
		}
		event(new RestaurantDefault($chef->id)); // event
		/* Document upload Begin */
		$userDocument           = UserDocuments::where('user_id',$chef->id)->first();
		if (!empty($userDocument)) {
			$userDocument->user_id  = $chef->id;
			$mainPathString         = 'uploads/user_document/'.$chef->id.'/';
			$mainPath               = base_path($mainPathString);
			if (!\File::exists($mainPath)) {
				$dc = \File::makeDirectory($mainPath, 0777, true, true);
			}
			if ($request->hasFile('fssai_certificate')) {
				if (\File::exists($mainPath.'/'.$userDocument->fssai_certificate)) {
					\File::delete($mainPath.'/'.$userDocument->fssai_certificate);
				}
				$fssai              = $request->file('fssai_certificate');
				$extension          = $fssai->getClientOriginalExtension(); 
				$newfilename        = $chef->id.'_fssai_'.$chef->user_code.'.'.$extension;
				$uploadSuccess      = $fssai->move($mainPath, $newfilename);
				if ($uploadSuccess) {
					$userDocument->fssai_certificate = $newfilename;
				}
			}
			if ($request->hasFile('aadar_image')) {
				if (\File::exists($mainPath.'/'.$userDocument->aadar_image)) {
					\File::delete($mainPath.'/'.$userDocument->aadar_image);
				}
				$aadar              = $request->file('aadar_image');
				$extension          = $aadar->getClientOriginalExtension(); 
				$newfilename        = $chef->id.'_aadar_'.$chef->user_code.'.'.$extension;
				$uploadSuccess      = $aadar->move($mainPath, $newfilename);
				if ($uploadSuccess) {
					$userDocument->aadar_image = $newfilename;
				}
			}
			$userDocument->save();
		}
		/* Document upload End */
		if ($function == 'approved' || $save == 'new') {
			$ven_id= ($v_id == '') ? $chef->id : $v_id;
			$check  = Restaurants::where('vendor_id',$ven_id)->first();
			if(!empty($check)) {
				if (!empty($request->time_to_sell) || !empty($request->fa_link) || !empty($request->in_link) || !empty($request->yo_link)) {
					$restaurant['time_to_sell']  = ($request->time_to_sell != '') ? $request->time_to_sell : '';
					$restaurant['facebook_link'] = ($request->fa_link != '') ? $request->fa_link : '';
					$restaurant['instagram_link']= ($request->in_link != '') ? $request->in_link : '';
					$restaurant['youtube_link']  = ($request->yo_link != '') ? $request->yo_link : '';
				}
				$check->fill($restaurant)->save();
			} else {
				$check = Restaurants::create($restaurant);
			}
			if ($check->preparation_time == 'preorder') {
				Menuitems::where('restaurant_id',$check->id)->update(['preparation_time'=>'tomorrow']);
			}
		}
		$chef_type = isset($request->type) ? 'Chef event' : 'Chef';   
		Flash::success($chef_type.' details saved successfully.');
		if($v_id == '') {
			return redirect(getRoleName().'/chef/'.$chef->id.'/edit');
		} else {
			return redirect()->back();
			// return redirect(getRoleName().'/chef/'.$v_id.'/edit_business');
		}
	}

	public function ordering( Request $request)
	{
		if ($request->method() == 'GET') {
			$chefs	= Chefs::select('id','name','celebrity','promoted','certified','user_code','type')->notpending()->orderBy('ordering','ASC')->get();
			return view('chef.ordeing',compact('chefs'));
		} elseif ($request->method() == 'POST') {
			// echo "<pre>";
			// print_r($request->all());exit;
			if (isset($request->cOrder) && is_array($request->cOrder)) {
				foreach ($request->cOrder as $key => $value) {
					$chef	= array();
					$chef	= Chefs::find($value,['id','ordering']);
					$chef->ordering	= $key + 1;
					$chef->save();
				}
				\Flash::error("Saved successfully");
				$request->flash();
			}
			return \Redirect::to(getRoleName().'/chefordering');
		} else {
			\Flash::error("You don't have access");
			$request->flash();
			$redirect	= (getRoleName() == 'admin') ? getRoleName().'/chef' : getRoleName().'/dashboard';
			return \Redirect::to($redirect);
		}
	}

	public function locationCode(Request $request)
	{
		$location_id    = $request->location;
		$location       = Locations::where('id',$location_id)->get();
		$code           = '';
		if($location){
			$code   = $location[0]->code;
		}
		return $code;
	}

	public function schedule(Request $request)
	{
		$v_id          = $request->v_id;
		$date_range    = explode(" - ",$request->date_range); 
		$start_date    = date('Y-m-d',strtotime($date_range[0]));
		$end_date      = date('Y-m-d',strtotime($date_range[1]));
		$start_time    = date('H:i:s',strtotime($date_range[0]));
		$end_time      = date('H:i:s',strtotime($date_range[1]));

		$result = app()->call('App\Http\Controllers\Api\Partner\PartnerController@schedule',[
			'request' => request()->merge(['from'=>'web','start_date'=>$start_date,'end_date'=>$end_date,'start_time'=>$start_time,'end_time'=>$end_time,'v_id'=>$v_id ])
		]);
		if (!empty($result) && $result->getStatusCode() == 422) {
			$response = $result->getData();
			// dd($response->validator);
			Flash::error($response->message);
			$request->flash();
			return \Redirect::back()->withErrors([])->withInput();
		}
		//return $result;
		Flash::success($result->getData()->message);
		return redirect()->back();
	}

	public function availability(Request $request)
	{
		$v_id                     = $request->v_id;
		$request['opening_time']  = date("H:i:s", strtotime($request->opening_time));
		$request['closing_time']  = date("H:i:s", strtotime($request->closing_time));
		$request['opening_time2'] = date("H:i:s", strtotime($request->opening_time2));
		$request['closing_time2'] = date("H:i:s", strtotime($request->closing_time2));
		$result = app()->call('App\Http\Controllers\Api\Partner\PartnerController@availabilty');
		if($result->status() != 200){
			//dd($result->status());
			Flash::error($result->getData()->message);
			$request->flash();
			return \Redirect::back()->withErrors([])->withInput();
		}
		Flash::success($result->getData()->message);
		return redirect()->back();
	}

	public function working_days(Request $request)
	{
		$v_id                     = $request->v_id;
		$result = app()->call('App\Http\Controllers\Api\Partner\PartnerController@workingDays');
		if($result->status() != 200){
			Flash::error($result->getData()->message);
			$request->flash();
			return \Redirect::back()->withErrors([])->withInput();
		}

		Flash::success($result->getData()->message);
		return redirect()->back();
	}

	public function OnandOffline_update(Request $request)
	{
		$mode          = $request->mode;
		$v_id          = $request->v_id;
		$result = app()->call('App\Http\Controllers\Api\Partner\PartnerController@vendorData',[
			'request' => request()->merge(['mode' => $mode,'v_id' => $v_id ])
		]);
		return $result;
	}

	public function destroy($id)
	{
		$chefs  = Chefs::find($id);
		if ($chefs) {
			$chefs = $chefs->delete();
			if ($chefs) {
				Flash::success('deleted successfully.');
			}
		} else {
			Flash::success('Please Refresh Your Page...');
		}
		return \Redirect::back();
	}

	public function multidelete(Request $request)
	{
		if(is_array($request->delete) && count($request->delete) > 0){
			Chefs::whereIn('id',$request->delete)->delete();   
			Flash::success('deleted successfully.');

		}else{
			Flash::success('Please Refresh Your Page...');
		}
		return \Redirect::back();
	}

	public function chefexport(Request $request,$slug)
	{
		$request->all();
		$exporter = app()->makeWith(ChefExport::class, compact('request'));  
		return $exporter->download('ChefExport_'.date('Y-m-d').'.'.$slug);
	}

	public function fundaccount(Request $request)
	{
		$request['name'] = $request->b_name;
		$request['ifsc'] = $request->ifsc_code;
		$fund = app()->call('App\Http\Controllers\Api\Razor\PayoutsController@createFund');
		if($fund->status() == 200){
			$c = $fund->getData()->data;
			RzAccount::where('contact',$request->contact_id)->update(['fund'=>$c->id,'fund_status'=>$c->active,'ifsc_code' => $c->bank->ifsc,'name'=>$c->bank->name,'account_number'=>$c->bank->account_number]);
			Flash::success('Bank Details Updated successfully..');
		}else{
			Flash::error($fund->getData()->message);
		}
		return \Redirect::back();
	}

	public function createRzAccount(Request $request)
	{
		if(\Auth::user()->role == 1) {
			$chef   = Chefs::find($request->chef);
			if(!empty($chef)) {
				createRZContact($chef,"new");
			}
		}
		return \Response::json(true,200);
	}

	public function package(Request $request)
	{
		$v_id = $request->v_id;
		$rules['package']  = 'required|in:Silver,Gold,Platinum';
		$rules['amount']   = 'required|in:0,1000,1500,3000,6000';
		$response = $this->validateDatas($request->all(),$rules,[],[],'web');
		if (!empty($response)) {
			Flash::error($response['message']);
			$request->flash();
			return \Redirect::back()->withErrors($response['validator'])->withInput();
		}
		$user = Users::find($v_id,['id','package','amount']);
		$user->package = $request->package;
		$user->amount  = $request->amount;
		$user->save();
		return redirect()->back();
		// return redirect(getRoleName().'/chef/'.$v_id.'/edit_business');  
	}

	public function preparationtime_change(Request $request)
	{
		if($request->action == 'get'){
			$firstmenuitem = Menuitems::select('id','preparation_time')->where('vendor_id',$request->chef_id)->orderBy('id','ASC')->first();
			if($firstmenuitem){
				return \Response::json($firstmenuitem->preparation_time,200);	
			} 
		}
		$menuitems = Menuitems::select('id','preparation_time')->where('vendor_id',$request->v_id)->get();
		if($menuitems){
			foreach ($menuitems as $key => $value) {
				$value->preparation_time = $request->preparation_time;
				$value->save();
			}
			Flash::message("Menuitems preparation time changed successfully.");
			return redirect()->back();
		}
	}
}
