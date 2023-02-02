<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Flash;
use LRedis;
use App\Models\Customerchat;
use App\Models\Customer;

class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {     

        return view('chat.admin.chat');
        $pageCount  = 10;
        $page       = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
        $search    = $request->query('search') ? $request->query('search') : '';
        $date    = $request->query('date') ? $request->query('date') : '';
        $customer=Customer::where(function($query) use ($search,$date) {
            if ($search != '') {
                $query->where('name', 'like', '%'.$search.'%')->orWhere('user_code', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')->orWhere('mobile', 'like', '%'.$search.'%');
            }
            if (isset($request->date) && $request->date != '') {
                $sDate  = explode(" - ",$request->date);
                $customer->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
            }
        })->paginate(10);
        return view('chat.admin.users',compact('customer','page'));
    }

    public function users(Request $request,$userId)
    {
        $customer=Customer::find($userId);
        if (!empty($customer)) {
            return view('chat.admin.index',compact('customer'));
        }else{
            return \Redirect::back();
        }
    }

    public function getMessages(Request $request)
    {

        $rules = array();
        $rules['user_id'] = 'required';
        $rules['room']    = 'required';

        $this->validateDatas($request->all(),$rules);

        $messages = array();

        $messageUser = User::where('id',$request->user_id)->first();


        $messageUser = Customerchat::with(['user' => function($uQuery){
            $uQuery->withoutAppends()->addSelect('id','name','avatar');
        }])->where('room_id',$request->room)->get();

        $messages = [];

        if (!empty($messageUser)) {
            $messages = $messageUser;
        }

        $data['messages'] = $messages;
        $data['room'] = $request->room;

        return response()->json($data);
    }

    public function storeMessages(Request $request)
    {
        try {
            $redis      = LRedis::connection();
            $message    = new \stdClass();

            $room        = $request->room;
            $messageText = $request->message;
            $user_id     = $request->user_id;

            $customer_chat = new Customerchat();

            $customer_chat->message = $messageText;
            $customer_chat->user_id = $user_id;
            $customer_chat->room_id = $room;
            $customer_chat->seen    = '0';
            $customer_chat->notify  = '0';
            $customer_chat->save();

            $redis->publish('Message',json_encode($message));

            $message = Customerchat::with(['user' => function($uQuery){
                $uQuery->withoutAppends()->addSelect('id','name','avatar');
            }])->where('id',$customer_chat->id)->first();

            $status = 'Message Saved';

        } catch (\Exception $e) {
            
            $status = $e->getMessage();

        }

        return response()->json(['message' => $message,'status' => $status]);
    } 

    public function lastSeenUpdate(Request $request)
    {
        $userstatus = User::find($request->userid);
        $userstatus->online     = $request->status;
        $redis  = LRedis::connection();
        $user['name']   = $userstatus->username;
        $user['id']     = $userstatus->id;
        $user['avatar'] = $userstatus->avatar;
        $user['room']   = $userstatus->socket_subscribe_name;
        if ($request->status == 'online') {
            $redis->publish('onlineUser',json_encode($user));
        } else {
            $redis->publish('offlineUser',json_encode($user));
        }
        $userstatus->last_seen  = date('Y-m-d H:s:i');
        $userstatus->save();
    }

    public function onlineUsers(Request $request)
    {

        $rules = array();
        $rules['user'] = 'required';
        $rules['page'] = 'required';

        $this->validateDatas($request->all(),$rules);

        $users = [];

        try {

            $user = $request->user;

            $page = $request->page;

            // $users = User::withAppends(['room'])->addSelect('id','name','avatar')->where('online', 'online')->orderByDesc('last_seen')->paginate(10,$page)->getCollection();
           
            $chatuser = Customerchat::addSelect('user_id')->groupBy('user_id')->whereHas('user')->with(['user' => function($query){   
              $query->withAppends(['room'])->addSelect('id','name','avatar','user_code');  
            }])->get()->makeHidden(['body','message','user_id','user']);
            
            foreach($chatuser as $k => $v){
                if(empty($v->user->name)) {
                    $v->user->name = !is_null($v->user->user_code) ? 'User-'.$v->user->user_code : 'User-'.$v->user->id;
                }
                unset($v->user->user_code); 
                $val        = collect($v->user);
                $previous_users[] = $val;
            }
            $online_users = User::withAppends(['room'])->addSelect('id','name','avatar','user_code')->where('online', 'online')->orderByDesc('last_seen')->paginate(10,$page)->toArray(); 

            foreach($online_users['data'] as $k => $online){
              if(empty($online['name'])) {
                    $online['name'] = !is_null($online['user_code']) ? 'User-'.$online['user_code'] : 'User-'.$online['id'];
                }
                unset($online['user_code']);  
              foreach($previous_users as $key => $previous){
                if($previous['name'] == $online['name']){
                    unset($previous_users[$key]);
                }
              }
            }

            foreach(array_reverse($online_users['data']) as $k => $v){
                if($v['id'] != 1){
                    array_unshift($previous_users,collect($v));
                }
            }
            $users = $previous_users;
            $status = 'Online users are listouted';

        }catch (\Exception $e) {

            $users = [];
            
            $status = $e->getMessage();

        }

        return response()->json(['users' => $users,'status' => $status]);
    }

    public function updateOnline(Request $request)
    {

        $rules = array();
        $rules['user'] = 'required';
        $rules['mode'] = 'required';

        $this->validateDatas($request->all(),$rules);

        $user = [];

        $mode = 'offline';

        try {

            $user = $request->user;

            $mode = $request->mode == 1 ? 'online' : 'offline';

            User::where('id', $user)->update(['online' => $mode]);

            $user = User::withAppends(['room'])->addSelect('id','name','avatar')->where('id', $user)->first();

            $status = 'User mode is '.$mode.'.';

        }catch (\Exception $e) {

            $user = [];
            
            $status = $e->getMessage();

        }

        return response()->json(['users' => $user,'mode' => $mode,'status' => $status]);
    }

    public function searchUser(Request $request)
    {
        $searchname = new \stdClass();
        if(isset($request->username[3])){
            $searchname = User::where(function($qy) use($request){
                $qy->where('name', 'like', '%'.$request->username.'%');
            })->get();
        }
        return $searchname;
    }
}
