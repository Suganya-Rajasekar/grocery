<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chefs;
use App\Models\Cuisines;
use App\Models\Locations;
use App\Models\Restaurants;

class VendorController extends Controller
{
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
}