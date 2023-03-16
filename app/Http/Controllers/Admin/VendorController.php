<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chefs;
use App\Models\Cuisines;
use App\Models\Locations;
use App\Models\Restaurants;
use App\Models\User;

class VendorController extends Controller
{
	private $status		= 403;
	private $message	= "Only vendors are allowed";

	public function testRole()
	{
		$guard	= (request('from') == 'mobile') ? 'api' : 'web';
		$user	= auth($guard)->user();
		$role_id= User::where('id', $user->id)->pluck('role')->toArray()[0];

		if(isset($role_id) && ($role_id == 3 || $role_id == 1 || $role_id == 5)){
			return true;
		} else {
			return false;
		}
	}

	/**
	* Show the application dashboard.
	*
	* @return \Illuminate\Contracts\Support\Renderable
	*/
	public function index(Request $request)
	{
		$pageCount	= 10;
		$page	= (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
		$chefs	= Chefs::/*notpending()->*/with('getChefRestaurant');
		if (isset($request->ordering) && $request->ordering != '') {
			$chefs	= $chefs->orderBy('ordering',strtoupper($request->ordering));
		} else {
			$chefs	= $chefs->orderBy('id','DESC');
		}
		$chefs		= $chefs->paginate(10);

		$chefreq	= false;
		$cuisines	= Cuisines::get();
		$city		= Locations::all();
		return view('admin.vendor.index',compact('chefs','chefreq','page','city','cuisines'));
	}

	public function show(Request $request)
	{
	}

	public function create()
	{
		$chefs		= [];
		$cuisines	= Cuisines::all();
		$city		= Locations::all();
		$restaurant	= Restaurants::all();
		$id			= '';
		$chefpage	= false;
		return view('admin.vendor.form',compact('chefs'),compact('cuisines','city','chefpage','id','restaurant'));
	}

	public function edit(Request $request, $id)
	{
		$chefs		= Chefs::find($id);
		$city		= Locations::all();
		$cuisines	= Cuisines::all();
		$restaurant	= Restaurants::where('vendor_id',$id)->first();
		$chefpage	= false;
		return view('admin.vendor.form',compact('chefs'),compact('cuisines','city','restaurant','chefpage','id'/*,'userdocument'*/));
	}

	public function update( Request $request)
	{
		$rules['name']	= ['required', 'string', 'max:255'];
		$rules['email']	= ['required', 'email', 'max:255', 'unique:users,email,'.$request->c_id, 'unique:restaurants,email,'.$request->c_id];
		$rules['phone']	= ['required', 'numeric', 'unique:users,mobile,'.$request->c_id, 'unique:restaurants,phone,'.$request->c_id];
		if( $request->hasFile('logo') ) {
			$rules['logo']= 'required|mimes:jpeg,jpg,png';/*|dimensions:max_width=1024,max_height=1024*/
		}
		if(\Auth::user()->role == 1){
			$rules['status']	= ['required', 'in:pending,approved,suspended,cancelled'];
			if ($request->status == 'suspended' || $request->status == 'cancelled') {
				$rules['reason']= ['required'];
			}
		}
		$rules['adrs_line_1']	= 'required';
		$rules['location']		= 'required|numeric|exists:locations,id';
		$rules['latitude']		= 'required';
		$rules['longitude']		= 'required';
		$rules['commission']	= 'required';

		$nicenames['name']		= 'Name';
		$nicenames['email']		= 'Email';
		$nicenames['mobile']	= 'Mobile';
		$nicenames['reason']	= 'Declined reason';
		$nicenames['adrs_line_1']	= 'Complete Address';
		$nicenames['adrs_line_2']	= 'Exact location';
		// echo "<pre>"; print_r($request->all()); exit;
		$response = $this->validateDatas($request->all(),$rules,[],$nicenames,'web');
		if (!empty($response)) {
			\Flash::error($response['message']);
			$request->flash();
			return \Redirect::back()->withErrors($response['validator'])->withInput();
		}

		$res_id	= ($request->s_id == 0) ? 0 : $request->s_id;
		$restaurant	= ($request->s_id == 0) ? new Restaurants : Restaurants::find($request->s_id);
		$restaurant->vendor_id	= $request->c_id;
		$restaurant->name		= $request->name;
		$slug	= \Str::slug($request->name,'_');
		if ($slug == '') {
			$slug	= str_replace(' ', '_', str_replace('-', '_', $request->name));
		}
		$restaurant->slug		= $slug;
		$restaurant->phone_code	= 91;
		$restaurant->phone		= $request->phone;
		$restaurant->email		= $request->email;
		$restaurant->location	= $request->location;
		$restaurant->tax		= ($request->tax != '') ? $request->tax : 0;
		if(\Auth::user()->role == 1 ||\Auth::user()->role == 5) {
			$restaurant->commission	= $request->commission;
		}
		$restaurant->latitude		= $request->latitude;
		$restaurant->longitude		= $request->longitude;
		$restaurant->adrs_line_1	= $request->adrs_line_1;
		$restaurant->adrs_line_2	= $request->adrs_line_2;
		$restaurant->status			= $request->status;
		$restaurant->declined_reason= ($request->reason != '') ? $request->reason : '';
		if( $request->hasFile('logo')) {
			$filenameWithExt= $request->file('logo')->getClientOriginalName();
			// $filename		= pathinfo($filenameWithExt, PATHINFO_FILENAME);
			$extension		= $request->file('logo')->getClientOriginalExtension();
			$fileNameToStore= $slug.'.'.$extension;
			\Storage::delete($restaurant->avatarpath ?? null);
			$avatar_path	= $request->file('logo')->storeAs('public/avatar', $fileNameToStore);
			$restaurant->avatar	= $fileNameToStore;
		}
		$restaurant->save();

		\Flash::success('Store details saved successfully.');
		if($res_id != '') {
			return redirect(getRoleName().'/vendor/'.$request->c_id.'/store/'.$restaurant->id.'/edit');
		} else {
			return redirect()->back();
		}
	}

	public function update1( Request $request)
	{
		$v_id   = $request->v_id;
		$nicenames = [];
		if($v_id == '') {
			$rules['name']		= ['required', 'string', 'max:255'];
			if(!isset($request->type)) {
				$rules['email']	= ['required', 'email', 'max:255', 'unique:users,email,'.$request->c_id];
				if ($request->c_id == 0) {
					$rules['password']			= ['required', 'min:6','same:confirm_password'];
					$rules['confirm_password']	= ['required', 'min:6'];
				}
				$rules['mobile']	= ['required', 'numeric','unique:users,mobile,'.$request->c_id];
				if( $request->hasFile('avatar') ) {
					$rules['avatar']= 'required|mimes:jpeg,jpg,png';/*|dimensions:max_width=1024,max_height=1024*/
				}
			}
			if(\Auth::user()->role == 1){
				$rules['status']	= ['required', 'in:pending,approved,suspended,cancelled'];
				if ($request->status == 'suspended' || $request->status == 'cancelled') {
					$rules['reason']	= ['required'];
				}
			}
			$nicenames['name']	= 'Name';
			$nicenames['email']	= 'Email';
			$nicenames['mobile']	= 'Mobile';
			$nicenames['password']	= 'Password';
			$nicenames['confirm_password']	= 'Confirm Password';
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
		$response = $this->validateDatas($request->all(),$rules,[],$nicenames,'web');
		if (!empty($response)) {
			\Flash::error($response['message']);
			$request->flash();
			return \Redirect::back()->withErrors($response['validator'])->withInput();
		}
		$function   = 'pending';
		$save       = 'update';
		if($v_id == '') {
			$chefreq    = false;
			if($request->c_id > 0) {
				$chef   = Chefs::/*notpending()->*/find($request->c_id);
				if (!$chef) {
					$chef       = Chefs::/*pending()->*/find($request->c_id);
					$chefreq    = true;
				}
			} else {
				$chef = new Chefs;
				$save = 'new';
			}
			$ven_id = ($request->c_id) ? $request->c_id : $v_id;
			$check  = Restaurants::where('vendor_id',$ven_id)->first();

			$chef->name		= $request->name;
			$chef->email	= strtolower($request->email);
			$chef->mobile	= (isset($request->type)) ? '' : $request->mobile;
			$chef->role		= 3;
			if ($request->password != '') {
				$chef->password     = \Hash::make($request->password); 
			}
			$chef->status	= $request->status;
			$chef->reason	= ($request->status == 'suspended' || $request->status == 'cancelled') ? $request->reason : '';
			if( $request->hasFile('avatar')) {
				$filenameWithExt= $request->file('avatar')->getClientOriginalName();
				$filename		= pathinfo($filenameWithExt, PATHINFO_FILENAME);
				$extension		= $request->file('avatar')->getClientOriginalExtension();
				$fileNameToStore= 'chef_'.time().'.'.$extension;
				\Storage::delete($chef->avatarpath ?? null);
				$avatar_path	= $request->file('avatar')->storeAs('public/avatar', $fileNameToStore);
				$chef->avatar	= $fileNameToStore;
			}
			$chef->save();
			if(!isset($request->type)) {
				// createRZContact($chef,$save);
			}
			$function	= ($request->status == 'approved') ? 'approved' : 'pending' ;
		} else { 
			$function = 'approved';
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
		// event(new RestaurantDefault($chef->id)); // event
		$chef_type = isset($request->type) ? 'Chef event' : 'Chef';   
		\Flash::success($chef_type.' details saved successfully.');
		if($v_id == '') {
			return redirect(getRoleName().'/vendor/'.$chef->id.'/edit');
		} else {
			return redirect()->back();
		}
	}

	public function destroy($id)
	{
		$chefs  = Chefs::find($id);
		if ($chefs) {
			$chefs = $chefs->delete();
			if ($chefs) {
				\Flash::success('deleted successfully.');
			}
		} else {
			\Flash::success('Please Refresh Your Page...');
		}
		return \Redirect::back();
	}

	public function multidelete(Request $request)
	{
		if(is_array($request->delete) && count($request->delete) > 0){
			Chefs::whereIn('id',$request->delete)->delete();   
			\Flash::success('Deleted successfully.');
		} else {
			\Flash::success('Please select record and delete');
		}
		return \Redirect::back();
	}

	public function chefexport(Request $request,$slug)
	{
		$request->all();
		$exporter = app()->makeWith(ChefExport::class, compact('request'));  
		return $exporter->download('ChefExport_'.date('Y-m-d').'.'.$slug);
	}

	/* Chef Info */
	public function vendorData( Request $request)
	{
		$status		= 200;
		$guard		= ($request->from == 'mobile') ? 'api' : 'web';
		$auth_role	= auth($guard)->user()->role;
		$auth_id	= ($auth_role == 1 || $auth_role == 5) ? $request->v_id : auth($guard)->user()->id;
		if ($this->testRole() || $auth_role == 1) {
			$cmessage	= $restaurants = array();
			if ($this->method == 'PATCH') {
				$rules['tags']		= 'required|array';
				$rules['tags.*']	= 'required|exist_check:common_datas,where:type:=:tag-whereIn:id:'.implode('~', $request->tags);
				// $rules['budget']	= 'required|numeric|exists:common_datas,id';
				$rules['tax']		= 'numeric';
				$rules['budget']	= 'required|numeric';
				$rules['fssai']		= 'required|numeric';
				$rules['location']	= 'required|numeric|exists:locations,id';
				$rules['address']	= 'required';
				$rules['locality']	= 'required';
				$rules['latitude']	= 'required';
				$rules['longitude']	= 'required';
				$rules['ext_address']		= 'required';
				$rules['description']		= 'required|min:250';
				$rules['preparation_time']	= 'required|in:preorder,ondemand';
			} elseif ($this->method == 'PUT') {
				$rules['mode']	= 'required|in:open,close';
				$rules['v_id']	= 'required|exists:users,id';
				$rules['s_id']	= 'required|numeric|exist_check:restaurants,where:vendor_id:=:'.$auth_id.'-where:id:=:'.$request->s_id;
			}

			if ($this->method == 'PATCH' || $this->method == 'PUT') {
				$nicenames['preparation_time'] = 'Preparation time';
				$nicenames['s_id'] = 'Store';
				$nicenames['v_id'] = 'Vendor';
				$this->validateDatas($request->all(),$rules,$cmessage,$nicenames);
			}

			$selectArr		= ['id','description', 'tax', 'tags', 'budget', 'location', 'locality', 'landmark', 'preparation_time', 'adrs_line_1 as ext_address','adrs_line_2 as address', 'latitude', 'longitude','mode'];
			$restaurants	= Restaurants::where('vendor_id',$auth_id);
			$restaurants	= ($this->method == 'PUT') ? $restaurants->where('id',$request->s_id)->addSelect('mode','id','vendor_id') : $restaurants->addSelect($selectArr);
			$restaurants	= $restaurants->first();
			// $restaurants Restaurants::find($res->id);
			if ($this->method == 'PATCH') {
				$restaurants->tags			= /*implode(',',*/ $request->tags/*)*/;
				$restaurants->budget		= $request->budget;
				$restaurants->tax			= (isset($request->tax)) ? $request->tax :0;
				$restaurants->location		= $request->location;
				$restaurants->fssai			= $request->fssai;
				$restaurants->locality		= $request->locality;
				$restaurants->landmark		= (isset($request->landmark)) ? $request->landmark :'';
				$restaurants->description	= $request->description;
				$restaurants->adrs_line_2	= $request->address;
				$restaurants->adrs_line_1	= $request->ext_address;
				$restaurants->latitude		= $request->latitude;
				$restaurants->longitude		= $request->longitude;
				$restaurants->preparation_time	= $request->preparation_time;
				$restaurants->preorder			= ($request->preparation_time == 'preorder') ? 'yes' : 'no';

				$restaurants->save();
				$restaurants->makeHidden('preorder', 'adrs_line_2', 'adrs_line_1');
				$message	= 'Secondary info updated successfully';
			} elseif ($this->method == 'PUT') {
				$restaurants->mode	= $request->mode;
				$restaurants->save();
				// $this->schedule($request);
				$message	= 'Mode updated successfully';
			} else {
				$message	= 'Secondary info details fetched';
			}
			/*if (!empty($restaurants) > 0) {
				$restaurants->makeHidden('updated_at');
				if ($this->method != 'PUT') 
					$restaurants->budget_name	= $restaurants->location_name = '';
				if (!empty($restaurants->budget_info)) {
					$restaurants->budget_name	= $restaurants->budget_info->name;
				}
				if (!empty($restaurants->location_info)) {
					$restaurants->location_name	= $restaurants->location_info->name;
				}
				$restaurants->makeHidden('location_info','budget_info');
			}*/
			$response['vendor_secondaryInfo']	= $restaurants;
		} else {
			$status		= 403;
			$message	= "Only vendors are allowed";
		}
		$response['message']= $message;
		$response['status']	= ($status == 200) ? 'success' : 'warning';
		return \Response::json($response,$status);
	}

	/* Schedule off time in Advance */
	public function schedule( Request $request)
	{
		$status	= 200;
		$guard		= ($request->from == 'mobile') ? 'api' : 'web';
		$auth_role	= auth($guard)->user()->role;
		$auth_id	= ($auth_role == 1 || $auth_role == 5) ? $request->v_id : auth($guard)->user()->id;
		if ($this->testRole() || $auth_role == 1) {
			$cmessage	= $rules = $nicenames	= [];
			if ($this->method == 'POST') {
				$rules['start_date']= 'required|date_format:Y-m-d|after_or_equal:'.date('Y-m-d');
				$rules['end_date']	= 'required|date_format:Y-m-d|after_or_equal:start_date';
				$rules['start_time']= ['required','date_format:H:i:s'];
				$rules['end_time']	= ['required','date_format:H:i:s'];
				$start_date	= request('start_date');
				$end_date	= request('end_date');
				if (strtotime($start_date) <= strtotime(date('Y-m-d'))) {
					array_push($rules['start_time'], 'after:'.date('H:i:s'));
				}
				if (strtotime($start_date) === strtotime($end_date)) {
					array_push($rules['end_time'], 'different:start_time');
				}
			} elseif ($this->method == 'PUT') {
				$rules['mode']  = 'required|in:open,close';  
			}
			$validateResponse = $this->validateDatas($request->all(),$rules,$cmessage,$nicenames,$guard);
			if ($guard == 'web' && !empty($validateResponse)) {
				return \Response::json($validateResponse, 422);
			}

			if ($this->method == 'POST') {				
				$data['off_from']	= $start_date.' '.request('start_time');
				$data['off_to']		= $end_date.' '.request('end_time');
				$chef	= Chefs::find($auth_id)/*->get()->where('avalability','not_avail')*/;
				$chef->singlerestaurant()->update($data);

				$extra['vendor_id']		= $extra['created_by'] = $chef->id;
				$extra['restaurant_id']	= $chef->singlerestaurant->id;
				$extra['created_by']	= $chef->id;
				$datamerge	= array_merge($data, $extra);
				$offlineData= new Offtimelog;
				foreach ($datamerge as $key => $value) {
					if($key != 'created_by')
						$offlineData	= $offlineData->where($key,$value);
				}
				$offlineData		= $offlineData->first();
				$data['vendor_id']	= $auth_id;
				$offData	= (!empty($offlineData)) ? $offlineData->fill($data)->save() : Offtimelog::create($data);

				$message	= 'Your are set to OFF from '.$start_date.' '.request('start_time').' to '.$end_date.' '.request('end_time');
			}
			elseif($this->method == 'PUT'){
				if($request->mode == 'close'){
					$res     = Restaurants::select('id')->where('vendor_id',$auth_id)->first();
					$offtime = new Offtimelog;
					$offtime->vendor_id 	= $auth_id;
					$offtime->restaurant_id = $res->id;
					$offtime->type      	= 'mode';
					$offtime->off_from  	= date('Y-m-d H:i:s',time());
					$offtime->save();
					$message	= 'Your are set to OFFTIME';   
				} else {
					$offtime = Offtimelog::where('vendor_id',$auth_id)->where('type','mode')->where('off_to',null)->first();
					if(!empty($offtime)){
						$offtime->off_to = date('Y-m-d H:i:s',time()); 
						$offtime->save();
						$message	= 'You removed the OFFTIME';   
					}					
				}
			}
		} else {
			$status		= 403;
			$message	= "Only vendors are allowed";
		}
		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	public function category(Request $request)
	{
		echo "<pre>"; print_r($request->all());exit;
		$categories	= array_unique($request->categories);
		$maincat	= [];
		if (!empty($categories)) {
			foreach ($categories as $key => $value) {
				$subcat = [];
				$cuisine= Cuisines::with('maincat')->find($value);
				$subcat	= ['id' => $cuisine->id, 'name' => $cuisine->name, 'visibility_mode' => 'on', 'status' => 'active'];
				$category	= ['id' => $cuisine->maincat->id, 'category_name' => $cuisine->maincat->name, 'categories' => [$subcat]];
				if (!empty($maincat)) {
					$val = array_search($cuisine->maincat->id, array_column($maincat, 'id'));
					if ($val != '') {
						$maincat[$val]['categories'][] = $subcat;
					} else {
						$maincat[]	= $category;
					}
				} else {
					$maincat[]	= $category;
				}
			}
			if (!empty($maincat)) {
				// cuisines
			}
		}
		print_r(json_encode($maincat));
		exit();
	}
}