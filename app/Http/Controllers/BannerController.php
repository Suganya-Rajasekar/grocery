<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Banner;
use App\Exports\BannerExport;
use Maatwebsite\Excel\Facades\Excel;


class BannerController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
		$this->Imagepath=Banner::IMAGE_PATH;
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index(Request $request)
	{
		$pageCount  = 10;
		$page	= (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
		//$banner=Banner::paginate(10);
		$banner=new Banner;
		$date	= $request->query('date') ? $request->query('date') : '';
		if ($request->query('date') != '') {
			$sDate  = explode(" - ",$request->date);
			$banner = $banner->where('start_date', '>=', $sDate[0])->
			where('end_date', '<=', $sDate[1]);
		} 
		if ($request->query('status') != '') {
			$banner	= $banner->where('status',$request->query('status'));
		}
		$banner	= $banner->paginate($pageCount);
		return view('banner.index',compact('banner','page'));
	} 

	public function create(Request $request)
	{
		$banner	= [];
		return view('banner.form',compact('banner'));
	}

	public function edit(Request $request, $id)
	{
		$banner	= Banner::find($id);
		return view('banner.form',compact('banner'));
	}

	public function update(Request $request)
	{
		if ($request->id > 0) {
			$banner = Banner::find($request->id);
		} else {
			$banner = new Banner;
		}
		if(isset($request->image)) {
			$img_data		= uploadImage($request->image,$this->Imagepath,'','');
			$banner->image	= $img_data['image'];
		}

		$banner->status		= $request->status; 
		$date				= explode(" - ", $request->date);
		$banner->start_date	= date('Y-m-d',strtotime($date[0])); 
		$banner->end_date	= date('Y-m-d',strtotime($date[1])); 
		$banner->save();

		\Flash::success('Banner details saved successfully.');
		return redirect(getRoleName().'/banner');
	}

	public function destroy($id)
	{
		$result =  Banner::find($id);
		if($result) {
			$result = $result->delete();
			if ($result) {
				Flash::success('Banner detail is deleted.');
			}
		} else {
			Flash::success('Please Refresh Your Page...');
		}
		return redirect(getRoleName().'/banner');
	}

	public function bannerexport(Request $request,$slug)
	{
		$request->all();
		$exporter = app()->makeWith(BannerExport::class, compact('request'));  
		return $exporter->download('BannerExport_'.date('Y-m-d').'.'.$slug);
	}
}