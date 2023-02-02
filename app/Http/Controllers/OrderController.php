<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Order;
use App\Models\Orderdetail;
use App\Models\Customer;
use App\Models\Timeslotmanagement;
use App\Models\Chefs;
use App\Models\Time;
use App\Exports\OrderExport;
use App\Exports\OrderCsvExport;
use App\Exports\VendorOrderExport;



class OrderController extends Controller
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
    public function show(Request $request)
    {
        $customerData   = Customer::select('id','name')->get();
        $aConditions    = ['=','!=','<','<=','>','>='];
        $filter         = $request->query('filter');
        $type           = \Request::segment(3);
        $pageCount      =10;
        $search         = $request->query('search') ? $request->query('search') : '';
        $status         = $request->query('status') ? $request->query('status') : '';
        $cOrder_count   = $request->query('customer_order_count') ? $request->query('customer_order_count') : '';
        $cus_count      = $cOrder_count ? count($cOrder_count) : 0 ;
        $com_amount     = $request->query('commission_amount') ? $request->query('commission_amount') : '';
        $com_count      = $com_amount ? count($com_amount) : 0;
        $date           = $request->query('date') ? $request->query('date') : '';
        $page           = (($request->query('page')) ? $request->query('page')-1 : 0) * 10;
        $time           = $request->query('time') ? $request->query('time') : ''; 
        $timeslot       = $request->query('timeslot') ? $request->query('timeslot') : '';  
        if (\Auth::user()->role == 1 || \Auth::user()->role == 5 ) {
            // \DB::enableQueryLog();
            $resultData     = new Order;
            if ($request->query('user_id') != '') {
                $resultData = $resultData->where('user_id',$request->query('user_id'));
            }
            if($request->query('type') != '') {
                $resultData = $resultData->where('order_type',$request->query('type'));
            }
            if ($com_count >= 2 && isset($com_amount[0]) && isset($com_amount[1])) {
                $resultData = $resultData->where('commission_amount',$com_amount[0],$com_amount[1]);
            }
            if ($request->query('payment_type') != '') {
                $resultData = $resultData->where('payment_type',$request->query('payment_type'));
            }
            if ($request->query('payment_status') != '') {
                $resultData = $resultData->where('payment_status',$request->query('payment_status'));
            }
            if(!empty($date)) {
                $sDate  = explode(" - ",$date);
                $resultData = $resultData->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
            }
            $resultData = $resultData->withCount(['Orderdetail'=> function($query)use($search,$status,$type,$timeslot){
                if($search != '') {
                    $query->where('m_id', 'like', '%'.$search.'%');
                }
                if($timeslot){
                    $query->where('time_slot',$timeslot);
                }
                if ($status != '') {
                    if ($status == 'pending') {
                        $query->where('status','=','pending');
                    } elseif ($status == 'completed') {
                        $query->where('status','=','completed');
                    } elseif ($status == 'food_ready') {
                        $query->where('status','=','food_ready');
                    } elseif ($status == 'accepted') {
                        $query->where('status','=','accepted_res')->orWhere('status','=','accepted_admin');
                    } elseif ($status == 'on_d_way') {
                        $query->where('status', '=', 'accepted_boy')->orWhere('status', '=', 'pickup_boy')->orWhere('status', '=', 'reached_location');
                    }
                }
                /*if($type != 'all'&& $type !='today'&& $type != 'competed' && $type != 'rejected') {
                    $query->where('status', 'like', '%'.$type.'%');
                }*/
                if($type == 'today') {
                    $today  = date('Y-m-d');
                    $todayrecord = $query->where('date',$today)->whereNotIn('status',['rejected_admin','rejected_res','rejected_cus','cancelled','completed']);

                } elseif($type == 'competed'){
                    $query->where(function($qy){
                        $qy->where('status', 'completed');
                    });
                } elseif($type == 'rejected'){
                    $query->where(function($qy){
                        $qy->where('status','=','rejected_admin')->orWhere('status', '=', 'rejected_res');
                    });  
                } elseif($type == 'accepted'){
                    $query->where(function($qy){
                        $qy->where('status','=','food_ready')->orWhere('status', '=', 'pickup_boy')->orWhere('status', '=', 'reached_location')->orWhere('status', '=', 'reached_restaurant')->orWhere('status', '=', 'riding')->orWhere('status', '=', 'accepted_boy')->orWhere('status', '=', 'accepted_res')->orWhere('status', '=', 'accepted_admin');
                    });
                } elseif($type == 'pending'){
                    $query->where('status','=','pending');
                } elseif($type == 'cancelled'){
                    $query->where('status','=','cancelled');
                } elseif($type == "tomorrow") {
                    $tomorrow = date('Y-m-d',strtotime('+1 day'));
                    $query->where('date',$tomorrow)->whereNotIn('status',['rejected_admin','rejected_res','rejected_cus','cancelled','completed']);
                } elseif($type == "nextweek") {
                    $start_date = date('Y-m-d',strtotime('+1 days'));
                    $end_date   = date('Y-m-d',strtotime('+7 days',strtotime($start_date)));
                    $query->whereBetween('date',[$start_date,$end_date]);
                } 
            }])->having('Orderdetail_Count','>',0);
            if ($cus_count >= 2){
                $resultData     = $resultData->withCount('Customerorderdetail as y')->having('y',$cOrder_count[0],$cOrder_count[1]);
            }
            if ($request->query('vendor_id') != '') {
                $resultData = $resultData->where('vendor_id',$request->query('vendor_id'));
            }
            $resultData = $resultData->orderByDesc('id')->paginate($pageCount); 
            $timeslots = Timeslotmanagement::get();
            // echo "<pre>";print_r(\DB::getQueryLog($resultData));exit;
            return view('order.index',compact('resultData','page','filter','type','customerData','aConditions','timeslots'));
        } elseif(\Auth::user()->role == 3) {
            $totamt  = $request->query('totamt') ? $request->query('totamt') : '';
            $user_id = $request->query('user_id') ? $request->query('user_id') : '';
            $status  = $request->query('status') ? $request->query('status') : '';

            $com_amount   = $request->query('commission_amount') ? $request->query('commission_amount') : '';
            $resultData = Orderdetail::where(function($query) use($type,$search,$totamt,$user_id,$com_amount,$status){
             if($search != '') {
                $query->where('s_id', 'like', '%'.$search.'%');
            }
            if($totamt != '') {
                $query->where('vendor_price', 'like', '%'.$totamt.'%');
            }
            if($user_id != ''){
               $query->where('user_id', 'like', '%'.$user_id.'%');
           }
           if($com_amount != ''){
             $query->where('commission_amount',$com_amount[0],$com_amount[1]);
         }
         if($status != ''){
            if ($status == 'completed') {
                $query->where(function($qy){
                    $qy->where('status','=','food_ready')->orWhere('status', '=', 'pickup_boy')->orWhere('status', '=', 'reached_location')->orWhere('status', '=', 'reached_restaurant')->orWhere('status', '=', 'riding')->orWhere('status', '=', 'accepted_boy')->orWhere('status', '=', 'completed');
                });
            }else{
                $query->where('status','like', '%'.$status.'%');
            }
        }
        if($type != 'all'&& $type !='today'&& $type != 'competed') {
            $query->where('status', 'like', '%'.$type.'%');
        } elseif($type == 'today') {
            $today  = date('Y-m-d');
            $todayrecord = $query->where('date',$today);
        } elseif($type == 'competed'){
            $query->where(function($qy){
                $qy->where('status','=','food_ready')->orWhere('status', '=', 'pickup_boy')->orWhere('status', '=', 'reached_location')->orWhere('status', '=', 'reached_restaurant')->orWhere('status', '=', 'riding')->orWhere('status', '=', 'accepted_boy')->orWhere('status', '=', 'completed');
            });
        } elseif($type == 'rejected'){
            $query->where(function($qy){
                $qy->where('status','=','rejected_admin')->orWhere('status', '=', 'rejected_res');
            });
        }
    })->where('vendor_id',\Auth::user()->id);
            if(!empty($date)) {
                $sDate  = explode(" - ",$date);
                $resultData = $resultData->whereBetween('date',[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
            }

            $resultData = $resultData->orderByDesc('id')->paginate(10);

            $vendorData     = Chefs::get();
            $time           = Time::select('id','name')->whereRaw('MOD(id,2) != 0 ')->get();
            return view('order.index_vendor',compact('resultData','page','filter','type','vendorData','customerData','aConditions','time'));
        }
    }

    public function view(Request $request)
    {
        if (\Auth::user()->role == 1 || \Auth::user()->role == 5) {
            $resultData     = Order::where('id',$request->id)->with('Orderdetail')->has('Orderdetail','>',0)->first();
            if($resultData == ''){
                return redirect()->back();
            }
            if($resultData->order_type == "ticket") {
                return view('order.eventview',compact('resultData'));
            } else {
                return view('order.view',compact('resultData'));
            }
        } else {
            $resultData = Orderdetail::where('id',$request->id)->first();
            return view('order.view_vendor',compact('resultData'));
        }
    }

    public function orderStatusChange(Request $request)
    {
        return app()->call('App\Http\Controllers\Api\Order\OrderController@orderData');
    }

    public function orderexport(Request $request,$slug) 
    {
        // echo "<pre>";print_r($request->all());exit;
        $request->all();
        $exporter = app()->makeWith(OrderExport::class, compact('request'));  
        return $exporter->download('OrderExport_'.date('Y-m-d').'.'.$slug);
    }
    public function ordercsvexport(Request $request,$slug) 
    {
        $request->all();
        $exporter = app()->makeWith(OrderCsvExport::class, compact('request'));  
        return $exporter->download('OrderExport_'.date('Y-m-d').'.'.$slug);
    }
    public function vendororderexport(Request $request,$slug) 
    {
        $request->all();
        $exporter = app()->makeWith(VendorOrderExport::class, compact('request'));  
        return $exporter->download('VendorOrderExport_'.date('Y-m-d').'.'.$slug);
    }
}
