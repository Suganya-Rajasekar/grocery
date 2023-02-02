<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Chefs;
use App\Models\LogActivity;
use App\Models\Notification;
use App\Models\BlastnotificationLogs;

class NotificationController extends Controller
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
		$user			= User::find(\Auth::id());
		$notification	= Notification::where('to',\Auth::id())->orderBy('id','desc')->paginate(20);
		$pageCount		= 10;
		$page			= (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
		// $logactivity	= LogActivity::where('before_change', '!=', '')->where('after_change', '!=', '')->orderBy('id','desc')->paginate(10);
		Notification::where('to',\Auth::id())->update(array('is_read'=>'1'));
		return view('notification.index',compact('notification','page'));
	}

	public function updateNotifyIsread(Request $request)
	{
		$notify_id = $request->id;
		\DB::table('tb_notification')->where('id',$notify_id)->update(array('is_read'=>'1'));
		// \DB::table('log_activities')->where('id',$notify_id)->update(array('is_read'=>'1'));
		return $notify_id;  
	}

	public function blastNotification(Request $request)
	{
		$users = User::select('id','role','name')->where('role',2)->get();
		$chefs = User::select('id','role','name')->where('role',3)->get();
		return view('notification.blast_notification',compact('users','chefs'));
	}

	public function blastNotification_send(Request $request)
	{
		$users = Customer::select('id','mobile_token');
		$chefs = Chefs::select('*');
		if($request->users != 'all_users' && $request->users != 'none') {
			$users = $users->whereIn('id',$request->users);
		}	
		if($request->chefs != 'all_chefs' && $request->chefs != 'none'){
			$chefs = $chefs->whereIn('id',$request->chefs);
		}
		$users = ($request->users != 'none') ? $users->where('status',$request->status)->get()->toArray() : [];
		$chefs = ($request->chefs != 'none') ? $chefs->where('status',$request->status)->get()->toArray() : [];
		$allusers = array_merge($chefs,$users);
		foreach ($allusers as $key => $value) {
		if ($value['mobile_token'] != '' && $value['mobile_token'] != null)
			FCM($value['mobile_token'],$request->subject,$request->message);
		}

		$log_chef = $log_users = '';
		if($request->users != 'none'){
			$log_chef = is_array($request->chefs) ? implode(',',$request->chefs) : $request->chefs;
		}
		if($request->users != 'none'){
			$log_users = is_array($request->users) ? implode(',',$request->users) : $request->users;
		}
		$logs = BlastnotificationLogs::create(['subject' => $request->subject,'message' => $request->message,'users' => $log_users,'chefs' => $log_chef,'status' => $request->status]);

		return redirect()->back();
	}

	public function blastNotification_logs(Request $request)
	{
		$logs = BlastnotificationLogs::paginate(10);
		return view('notification.blastnotification_logs',compact('logs'));
	}
}
