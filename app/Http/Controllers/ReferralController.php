<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Referralsettings;
use Redirect,Response;
use App\Models\User;

class ReferralController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $referral = Referralsettings::find(1);
        return view('referral.index',compact('referral'));
    }

    public function store(Request $request) 
    {   
        $ref_settings = Referralsettings::find(1);
        $ref_settings->referral_user_credit_amount = $request->referral_user_credit_amt;
        $ref_settings->referral_user_orders_count  = $request->referral_user_orders_count;
        $ref_settings->referer_user_credit_amount  = $request->referrer_user_credit_amt;
        $ref_settings->referral_share_description  = $request->referral_share_desc;
        $ref_settings->save();
        return \Redirect::back();
    }

    public function referral_users_list(Request $request) 
    {
        $perpageCount = 10;
        $page   = (($request->query('page')) ? $request->query('page')-1 : 0) * $perpageCount;  
        $user_id = $request->query('user_id') ? $request->query('user_id') : '';
        $referrer_user = User::where('role',2)->where('referer_user_id','!=',0)->get(['id','referer_user_id']);
        $users = User::where(function($query) use($user_id){
            $query->where('role',2)->where('referer_user_id','!=',0);
            if($user_id != '') {
                $query->where('referer_user_id',$user_id);
            }
        })->paginate($perpageCount);
        return view('referral.referralusers',compact('users','page','referrer_user'));
    }
}
