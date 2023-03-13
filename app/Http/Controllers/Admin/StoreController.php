<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Restaurants;
use App\Models\Locations;
use App\Models\Cuisines;
use App\Models\Chefs;

class StoreController extends Controller
{
	/**
	* Show the application dashboard.
	*
	* @return \Illuminate\Contracts\Support\Renderable
	*/
	public function index(Request $request)
	{
		$pageCount	= 10;
		$page		= (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
		$city		= Locations::all();
		$chef		= Chefs::find($request->id,['id','name']);
		$restaurant	= Restaurants::where('vendor_id',$request->id)->orderBy('id','DESC')->paginate(10);
		return view('admin.store.index',compact('page','city','restaurant','chef'));
	}

	public function show(Request $request)
	{
		$pageCount  = 10;
		$page       = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
		return view('admin.store.index',compact('page'));
	}

	public function create(Request $request)
	{
		$id			= '';
		$city		= Locations::all();
		$cuisines	= Cuisines::all();
		$chef		= Chefs::find($request->id);
		return view('admin.store.form',compact('id','city','cuisines','chef'));
	}

	public function edit(Request $request, $s_id)
	{
		$id			= $s_id;
		$city		= Locations::all();
		$cuisines	= Cuisines::all();
		$restaurant	= Restaurants::find($s_id);
		$chef		= Chefs::find($request->id,['id','name']);
		return view('admin.store.form',compact('id','city','cuisines','chef','restaurant'));
	}

	public function update( Request $request)
	{
		$v_id   = $request->v_id;
		$nicenames = [];
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
		$response = $this->validateDatas($request->all(),$rules,[],$nicenames,'web');
		if (!empty($response)) {
			\Flash::error($response['message']);
			$request->flash();
			return \Redirect::back()->withErrors($response['validator'])->withInput();
		}
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
		$chef_type = isset($request->type) ? 'Chef event' : 'Chef';   
		\Flash::success($chef_type.' details saved successfully.');
		return redirect()->back();
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
}