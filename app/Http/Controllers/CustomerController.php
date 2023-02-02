<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Flash;
use App\Models\Customer;
use App\Models\Chefs;
use App\Models\User;
use App\Models\Chefsreq;
use App\Models\Cuisines;
use App\Models\WalletHistory;
use App\Models\Orderdetail;
use App\Http\Controllers\Api\AuthController; 
use Illuminate\Support\Facades\Crypt;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exports\CustomerExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WalletHistoryExport;
use DB;

class CustomerController extends Controller
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
        $pageCount      = 10;
        $page           = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
        $search         = $request->query('search') ? $request->query('search') : '';
        $date           = $request->query('date') ? $request->query('date') : '';
        $logintype      = $request->query('logintype') ? $request->query('logintype') : '';
        $device         = $request->query('device') ? $request->query('device') : ''; 
        $customer=Customer::where(function($query) use ($search,$date,$logintype,$device) {
        if ($search != '') {
        $query->where('name', 'like', '%'.$search.'%')->orWhere('user_code', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')->orWhere('mobile', 'like', '%'.$search.'%');
        }
        if (isset($request->date) && $request->date != '') {
            $sDate  = explode(" - ",$request->date);
            $customer->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
        }
        if(isset($logintype) && $logintype != ''){
            $query->logintype($logintype);
        }
        if(isset($device) && $device != '') {
            $query->where('device',$device);
        }
        })->orderBy('id','desc')->paginate(10);
        return view('customer.index',compact('customer','page'));
    }

    public function show()
    {
       $customer    = Customer::all();
        return view('customer.index',compact('customer'));
    } 

    public function showCustomerProfile()
    {
        request()->merge(['function'=>'show','device'=>'web','user_id'=>\Auth::id()]);
        $data = [];
        $data['module'] = request()->module;
        // print_r(request()->all());die;
        if ($data['module'] == 'profile') {
            $profile            =  app()->call('App\Http\Controllers\Api\AuthController@userProfile')->getData();
            if ($profile->user->role == 3) {
                return \Redirect::to('')->withErrors("This type of user cannot able to view or edit their profile from here.");
            }
            $data['profile']    = $profile->user;
        } elseif ($data['module'] == 'wishlist') {
            $wishlist           = app()->call('App\Http\Controllers\Api\Customer\CustomerController@userwishlists')->getData();
            $data['wishlist']   = $wishlist->wishlists;
        } elseif ($data['module'] == 'bookmark') {
            $bookmarkLists      = app()->call('App\Http\Controllers\Api\Customer\CustomerController@userbookmarks',[  request()->request->all() ])->getData();
            $data['bookmark']   = $bookmarkLists->bookmarks;
        } elseif ($data['module'] == 'favourites') {
            $favouritesLists    = app()->call('App\Http\Controllers\Api\Customer\CustomerController@userfavourites',[  request()->request->all() ])->getData();
            $data['favourites'] = $favouritesLists->favourites;
        } elseif ($data['module'] == 'address') {
            $addressLists       = app()->call('App\Http\Controllers\Api\Customer\CustomerController@userAddress')->getData();
            $data['address']    = $addressLists->addressDetail;
        } elseif ($data['module'] == 'myOrders') {
            $data['inprocess']  = app()->call('App\Http\Controllers\Api\Order\OrderController@orderData',['request' => request()->merge(['type'=>'in_process', 'page'=>request()->Page ])])->getData();

            $data['past_order'] = app()->call('App\Http\Controllers\Api\Order\OrderController@orderData',['request' => request()->merge(['type'=>'past', 'page'=>request()->Page])])->getData();
        } elseif ($data['module'] == 'events') {
            $data['events'] = app()->call('App\Http\Controllers\Api\Order\OrderController@orderData',['request' => request()->merge(['type'=>'event', 'page'=>request()->Page])])->getData();
        } elseif ($data['module'] == 'home_events') {
            $data['home_events'] = app()->call('App\Http\Controllers\Api\Order\OrderController@orderData',['request' => request()->merge(['type'=>'home_event', 'page'=>request()->Page])])->getData();
        } elseif ($data['module'] == 'referral') {
            $data['referral'] = app()->call('App\Http\Controllers\Api\Customer\CustomerController@userReferral')->getData();
        } elseif($data['module'] == "wallet") {
            $wallet = app()->call('App\Http\Controllers\Api\Customer\CustomerController@userWallet')->getData();
            $data['wallet'] = $wallet->wallet_amount;
            $data['wallet_history'] = $wallet->wallet_history;
        }

        if (isset(request()->bPage) && $data['module'] == 'bookmark') {
            $data['recordCount'] = count($data['bookmark']->get_bookmarks);
            $data['app'] = (string) view('frontend.userprofile.bookmark',$data);
            return json_encode($data);
        } else if (isset(request()->fPage) && $data['module'] == 'favourites') {
            $data['recordCount'] = count($data['favourites']->get_favourites);
            $data['app'] = (string) view('frontend.userprofile.favorite',$data);
            return json_encode($data);
        } else if (isset(request()->pageNumber) && $data['module'] == 'wishlist') {
            $data['recordCount'] = count($data['wishlist']->data);
            $data['app'] = (string) view('frontend.userprofile.wishlist',$data);
            return json_encode($data);
        } else if (isset(request()->page) && $data['module'] == 'myOrders') {
            if (isset(request()->isPastOrder) && request()->isPastOrder == 1) {
                $data['recordCount'] = count($data['past_order']->orders);
                $data['action'] = 'past';
                $data['app'] = (string) view('frontend.userprofile.orders.pastorder',$data);
            } else {
                $data['recordCount'] = count($data['inprocess']->orders);
                $data['action'] = 'progress';
                $data['app'] = (string) view('frontend.userprofile.orders.progress',$data);
            }
            return json_encode($data);
        } elseif(isset(request()->page) && $data['module'] == "events"){
            $data['recordCount'] = count($data['events']->orders);
            $data['app'] = (string) view('frontend.userprofile.orders.eventorder',$data); 
            return json_encode($data);
        } elseif(isset(request()->page) && $data['module'] == "home_events") {
            $data['recordCount'] = count($data['home_events']->orders);
            $data['action'] = 'home_events';
            $data['app'] = (string) view('frontend.userprofile.orders.home_event_order',$data); 
            return json_encode($data);
        } elseif (isset(request()->page) && $data['module'] == "referral") {
            $data['app'] = (string) view('frontend.userprofile.referral',$data);
            return json_encode($data);             
        } elseif(isset(request()->page) && $data['module'] == 'wallet') {
            $data['app'] = (string) view('frontend.userprofile.wallet',$data);
            return json_encode($data); 
        } else {
            return view('home.customerProfile',$data);
        }
    }

    public function updateCustomerProfile(Request $request)
    {
        $upd = app()->call('App\Http\Controllers\Api\AuthController@userProfile',[
            'request' => request()->merge(['function'=>'edit','device'=>'web','user_id'=>\Auth::id() ])
        ]);
       return true;
    }

    public function removeUserWishlist(Request $request)
    {

        $upd = app()->call('App\Http\Controllers\Api\Customer\CustomerController@deletewishlist',[
            'request' => request()->merge(['user_id'=>\Auth::id() ])
        ]);
       return true;
    }

    public function updateWishlist(Request $request)
    {

        $upd = app()->call('App\Http\Controllers\Api\Customer\CustomerController@updatewishlist',[
            'request' => request()->merge(['user_id'=>\Auth::id() ])
        ]);
       return $upd;
    }

    public function showSingleWishlist($id)
    {

        $wishlist =  app()->call('App\Http\Controllers\Api\Customer\CustomerController@userwishlists',[
            'request' => request()->merge(['user_id'=>\Auth::id(), 'id' => $id])
        ])->getData(); 
        $wishlist = (object) $wishlist->wishlists->data;
        print_r(json_encode($wishlist));die;
        return view('home.dashboard-f',compact('wishlist'));
    }

    public function updateBookmark(Request $request)
    {
        $upd = app()->call('App\Http\Controllers\Api\Customer\CustomerController@updatebookmark',[
            'request' => request()->merge(['user_id'=>\Auth::id() ])
        ]); 
        return $upd;
    }

    public function updateFavourite(Request $request)
    {
        
        $upd = app()->call('App\Http\Controllers\Api\Customer\CustomerController@updatefavourite',[
            'request' => request()->merge(['user_id'=>\Auth::id() ])
        ]); 
        return $upd;
    }
    public function cancelOrder(Request $request)
    {
        $upd = app()->call('App\Http\Controllers\Api\Order\OrderController@orderData',[
            'request' => request()->merge(['order_id'=>$request->order_id,'status'=>'rejected_cus','reason' => $request->reason])
        ]); 
        return $upd;
    }
    public function orderReviewDetail(Request $request)
    {
        $review_data =  app()->call('App\Http\Controllers\Api\Order\OrderController@reviews',[
            'request' => request()->merge(['order_id'=>$request->order_id])
        ])->getData();
        //echo "<pre>";print_r($review_data);exit;
        if (isset($review_data)) {
            $menuinfo           = (object) $review_data;
            $Response['html']   = (string)view('frontend.userprofile.orders.ratings',compact('review_data'));
            return $Response;
        } else {
            Flash::success($menuinfos->message);
            return \Redirect::to('');
        }

    }
    public function orderReviewSend(Request $request)
    {
        if($request->action && $request->action == 'remove'){
            $order_review = app()->call('App\Http\Controllers\Api\Order\OrderController@reviews');
        } else {
            $order_review = app()->call('App\Http\Controllers\Api\Order\OrderController@reviews',[
                'request' => request()->merge(['rating'=>$request->rating,'reviews'=>$request->reviews,'order_id'=>$request->order_id,'vendor_id'=>$request->vendor_id])
            ]);
            $review=(object) $order_review;
            return $review;
        }  
    }

    public function create()
    {
       $customer=[];
        return view('customer.form',compact('customer'));
    }

    public function edit(Request $request, $id)
    {
       $customer=Customer::find($id);
       if(!$customer){
        $customer=Customer::find($id);
       }
        return view('customer.form',compact('customer'));
    }

    public function update(Request $request)
    {
        if ($request->c_id =='') {
            $rules['name']          = ['required', 'string', 'max:255'];
            $rules['email']         = ['required', 'email', 'max:255', 'unique:users,email,'.$request->c_id];
            $rules['password']  = ['required', 'min:6','same:confirm_password'];
            $rules['confirm_password']  = ['required', 'min:6'];
            $rules['phone_number']  = ['required', 'min:10', 'max:10'];
        } else{
            $rules['name']=['required', 'string', 'max:255'];
            $rules['email']         = ['required', 'email', 'max:255'];
            if(isset($request->password)) {
                $rules['password']  = ['required', 'min:6','same:confirm_password'];
                $rules['confirm_password']  = ['required', 'min:6'];
            }
        }

        if( $request->hasFile('avatar')) {
            $rules['avatar']    = ['required', 'mimes:png,jpeg,jpg', 'max:5000'];
        }
        $response = $this->validateDatas($request->all(),$rules,[],[],'web');
        if (!empty($response)) {
            Flash::error($response['message']);
            $request->flash();
            return \Redirect::back()->withErrors($response['validator'])->withInput();
        }

        if($request->c_id>0){
            $customer=Customer::find($request->c_id);            
        }else{
            $customer=new Customer;            
        }
        $customer->name=$request->name;
        $customer->email=$request->email;
        $customer->mobile=$request->phone_number;
        $customer->location_id=$request->phone_code;
        $customer->role=2;

        if($request->password!=''){
            $customer->password=Hash::make($request->password); 
        }

        $customer->status=$request->status;

        if( $request->hasFile('avatar')) {
            $filenameWithExt    = $request->file('avatar')->getClientOriginalName();
            $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension          = $request->file('avatar')->getClientOriginalExtension();
            $fileNameToStore    = $filename.'_'.time().'.'.$extension;

            Storage::delete($customer->avatarpath ?? null);
            $avatar_path        = $request->file('avatar')->storeAs('public/avatar', $fileNameToStore);
            $customer->avatar       = $fileNameToStore;
        }

        /*Take last record under the location*/

        $lastLoc = User::selectRaw("
            SUBSTRING_INDEX(user_code, '-', 1) AS user_code_area,
            SUBSTRING_INDEX(user_code, '-', -1) AS user_code_no"
        )->whereRaw("SUBSTRING_INDEX(user_code, '-', 1) = 'C'")
        ->orderBy('id','DESC')
        ->first();
        //print_r(DB::getQueryLog($lastLoc));exit;

        $lastLocCount = 1;
        if(!empty($lastLoc)){
            $lastLocCount = $lastLoc->user_code_no + 1;
        }
        //$createArr['chef_code'] = $request->area_code.'-'.$lastLocCount;
        /*Take last record under the location end*/
        if(isset($customer) && $customer->user_code==''){
        $customer->user_code      = 'C'.'-'.$lastLocCount;
        }
        $customer->save();

        Flash::success('Customer details saved successfully.');
        return redirect(url(getRoleName().'/customer'));
    }

    public function updateCustomerPassword(Request $request)
    {
        $rules['current_password']  = ['required'];
        $rules['password']  = ['required', 'min:6','same:confirm_password'];
        $rules['confirm_password']  = ['required', 'min:6'];

        $response = $this->validateDatas($request->all(),$rules,[],[],'web');
        if (!empty($response)) {
            $res['message']    = $response['message'];
            $status=422;
            return \Response::json($res,$status);
        }

        $user=User::find($request->user_id);  
        if(!Hash::check($request->current_password, $user->password)) {
            $status     = 422;
            $response['message']    = "The current password is incorrect.";
            return \Response::json($response,$status);
        }

        if($request->password!=''){
            $user->password=Hash::make($request->password); 
        }
        $user->save();

        $status     = 200;
        $response['message']    = "Customer password updated successfully.";
        return \Response::json($response,$status);
    }

    public function profileedit($id)
    {
        $customer = User::find(\Auth::user()->id);
        return view('adminProfileform',compact('customer'));
    }

    public function adminprofile_store(Request $request)
    {
        if ($request->c_id =='') {
            $rules['name']          = ['required', 'string', 'max:255'];
            $rules['email']         = ['required', 'email', 'max:255', 'unique:users,email,'.$request->c_id];
            $rules['password']  = ['required', 'min:6','same:confirm_password'];
            $rules['confirm_password']  = ['required', 'min:6'];
        } else{
            $rules['name']=['required', 'string', 'max:255'];
            $rules['email']         = ['required', 'email', 'max:255'];
            if(isset($request->password)){
                $rules['password']  = ['required', 'min:6','same:confirm_password'];
                $rules['confirm_password']  = ['required', 'min:6'];
            }
        }

        if( $request->hasFile('avatar')) {
            $rules['avatar']    = ['required', 'mimes:png,jpeg,jpg', 'max:5000'];
        }
        $response = $this->validateDatas($request->all(),$rules,[],[],'web');
        if (!empty($response)) {
            Flash::error($response['message']);
            $request->flash();
            return \Redirect::back()->withErrors($response['validator'])->withInput();
        }

        if($request->c_id>0){
            $user=User::find($request->c_id);
        }
        $user->name=$request->name;
        $user->email=$request->email;
        $user->mobile=$request->phone_number;


        if($request->password!=''){
            $user->password=Hash::make($request->password); 
        }

        if( $request->hasFile('avatar')) {
            $filenameWithExt    = $request->file('avatar')->getClientOriginalName();
            $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension          = $request->file('avatar')->getClientOriginalExtension();
            $fileNameToStore    = $filename.'_'.time().'.'.$extension;

            Storage::delete($customer->avatarpath ?? null);
            $avatar_path        = $request->file('avatar')->storeAs('public/avatar', $fileNameToStore);
            $user->avatar       = $fileNameToStore;
        }

        $user->save();

        Flash::success('profile details saved successfully.');
        return redirect(url(getRoleName().'/profile/'.$request->c_id.'/edit'));
    }
    public function customerexport(Request $request,$slug){
       $request->all();
        $exporter = app()->makeWith(CustomerExport::class, compact('request'));  
        return $exporter->download('CustomerExport_'.date('Y-m-d').'.'.$slug);

    }
    public function WalletAmountPush(Request $request) 
    {
        $rules['type'] = 'required|in:credit,debit';
        $rules['wallet_amt'] = 'required|numeric|gt:0';
        $validator = $this->validateDatas($request->all(),$rules,[],[],'web');
        if(!empty($validator)) {
            Flash::error($validator['message']);
            return \Redirect::back();
        } else {    
            $customer = User::find($request->id);      
            $amount   = ($request->type == 'credit') ? $customer->wallet + $request->wallet_amt : $customer->wallet - $request->wallet_amt;
            $customer->wallet = $amount;
            $customer->save();
            $from = ($request->type == 'credit') ? 'admin_credit' : 'admin_debit';
            manage_wallet_history($from,$customer->id,$request->wallet_amt);
            Flash::success('Wallet amount '.$request->type.'ed successfully.');
            return \Redirect::back();
        }
    }

    public function wallet_history(Request $request) 
    {
        $pageCount           = 10;
        $page                = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
        $user_id             = $request->id ? $request->id : '';
        $cutomerpage_from    = url()->previous();
        $type                = $request->query('type') ? $request->query('type') : '';
        $user                = User::select('id','name','user_code')->where('role',2)->where('status','approved')->get();
        $w_history = WalletHistory::where(function($query) use($user_id,$type){
            if($user_id) {
                $query->where('user_id',$user_id);
            }
            if($type) {
                $query->where('type',$type);
            }
        })->paginate($pageCount);
        return view('wallet_history.index',compact('w_history','page','cutomerpage_from','user')); 
    }

    public function wallethistory_export(Request $request,$slug)
    {
      $exporter = app()->makeWith(WalletHistoryExport::class, compact('request'));  
      return $exporter->download('ItemEarningExport_'.date('Y-m-d').'.'.$slug);
    }

    public function referal_generate(Request $request)
    {
        $users = Customer::get()->map(function($user) {
            $user->referal_code = referralgeneration();
            $user->save();
        });
        return \Response('success',200);
    }
       
}
