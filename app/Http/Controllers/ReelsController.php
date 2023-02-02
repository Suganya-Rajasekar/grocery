<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reels;
use App\Models\Chefs;
use Flash;
use Illuminate\Support\Facades\Storage;

class ReelsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index(Request $request)
	{
		$pageCount	= 10;
		$page	= (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
		$status	= ($request->query('status')) ? $request->query('status') : '';
		$reels	= Reels::select('*');
		if (isset($request->status) && $request->status != ''){
				$reels->where('status',$request->status);
			}
		if (isset($request->date) && $request->date != '') {
			$sDate	= explode(" - ",$request->date);
			$reels->whereBetween('validity_date_time', [date('Y-m-d H:i:s',strtotime($sDate[0])),date('Y-m-d h:i:s',strtotime($sDate[1]))]);
		}
		$reels = $reels->orderByDesc('id')->paginate($pageCount);
		return view('reels.index',compact('reels','page'));
	}
	
	public function create()
	{
		$chefs	= Chefs::select('id','name')->approved()->haveinfo()->get();
		return view('reels.form',compact('chefs'));
	}

	public function edit()
	{
		$id		= \Request::segment(3);
		$chefs	= Chefs::select('id','name')->approved()->haveinfo()->get();
		$reels	= Reels::find($id);
		return view('reels.form',compact('reels','chefs'));
	}

	public function store(Request $request)
	{ 
		$rules['title']		= 'required';
		// $rules['reels'] = 'required|mimes:mp4';
		$rules['is_chef_selected'] = 'required';
		$rules['validity'] = 'required';
		$rules['status'] = 'required';
		$response = $this->validateDatas($request->all(),$rules,[],[],'web');
		if($response) {
			Flash::error($response['message']);
			return \Redirect::back();
		} 
		if(!is_null($request->id)) {
			$reels = Reels::find($request->id);
		} else {
			$reels = new Reels;
		}
		$selected_chef_id = $request->selected_chef_id;
		if($request->selected_chef_id == ''){
			$selected_chef_id = '0';
		}
		/* $a = \Cloudinary\Uploader::upload($request->reels, ["resource_type" => "video", "quality" => "60"]);
		dd($a);*/
		
		$time	= time();
		$file_name	= 'chef/'.$request->selected_chef_id.'/'.$request->title.'-'.$time.'.mp4';
		$path	= Storage::disk('s3')->put($file_name, file_get_contents($request->reels));
		$video_magekit_thumburl ='https://ik.imagekit.io/knosh/tr:n-thumb/'.$request->selected_chef_id.'/'.$request->title.'-'.$time.'.mp4';
		$video_magekit_resizeurl ='https://ik.imagekit.io/knosh/tr:n-normal/'.$request->selected_chef_id.'/'.$request->title.'-'.$time.'.mp4';
		$awsurl    = storage::disk('s3')->url($path);
		$file_path = rtrim($awsurl,'1').$file_name;
		$reels->title = $request->title;
		$reels->description = $request->description;
		$reels->video_magekit_thumburl = $video_magekit_thumburl;
		$reels->video_magekit_resizeurl = $video_magekit_resizeurl;
		$reels->video_url = $file_path;
		$reels->is_chef_selected = $request->is_chef_selected;
		$reels->selected_chef_id = $selected_chef_id;
		$reels->validity_date_time = $request->validity;
		$reels->status = $request->status;
		$reels->save();
		return redirect(getRoleName().'/reels/'.$reels->id.'/edit');
	}

	public function destroy(Request $request)
	{
		$rules['id'] = 'required';
		$response = $this->validateDatas($request->all(),$rules,[],[],'web');
		if($response) {
			Flash::error($response['message']);
			return \Redirect::back();
		}  
		$reels = Reels::find($request->id)->delete();
		Flash::success('Reels details deleted succesfully.');
		return \Redirect::back();
		// storage::disk('s3')->delete($reels->video_url);

	}
}
