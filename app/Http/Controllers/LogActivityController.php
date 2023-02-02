<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LogActivity;


class LogActivityController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	* Show the application dashboard.
	*
	* @return \Illuminate\Contracts\Support\Renderable
	*/
	public function index(Request $request)
	{
		$path		= app_path() . "/Models";
		$module    	= getModels($path);
		$user			= User::find(\Auth::id());
		$notification	= $user->notifications;
		$pageCount		= 10;
		$page			= (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
		$search 	= $request->query('search');
		$filter 	= $request->query('filter');
		$logactivity 	= new LogActivity;
		$customerData   = User::get();
		$logactivity	= $logactivity->where('before_change', '!=', '')->where('after_change', '!=', '')->orderBy('id','desc');
		if ($request->query('user_id') != '') {
            $logactivity 	= $logactivity->where('user_id',$request->query('user_id'));
        }
		if ($request->query('date') != '') {
            $sDate  = explode(" - ",$request->date);
            $logactivity    = $logactivity->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
        } 
        if ($request->query('search') != ''){
            $logactivity 	= $logactivity->Where('record', 'like', '%'.$search.'%')->orWhere('before_change', 'like', '%'.$search.'%')->orWhere('after_change', 'like', '%'.$search.'%');
        }
        if ($request->query('filter') != ''){
            $logactivity 	= $logactivity->Where('record_id', 'like', '%'.$filter.'%');
        }
        if ($request->query('module') != '') {
            $logactivity 	= $logactivity->where('module',$request->query('module'));
        }
        $logactivity = $logactivity->orderByDesc('id')->paginate($pageCount);
		return view('logactivity.index',compact('notification','logactivity','page','customerData','module'));
	}

	public function updateNotifyIsread(Request $request)
	{
		$notify_id = $request->id;
		//\DB::table('tb_notification')->where('id',$notify_id)->update(array('is_read'=>'1'));
		\DB::table('log_activities')->where('id',$notify_id)->update(array('is_read'=>'1'));
		return $notify_id;  
	}
}
