<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Order;
use App\Models\Orderdetail;
use App\Models\Menuitems;
use App\Models\Chefs;
use App\Models\User;
use App\Models\Customer;
use App\Models\Restaurants;
use App\Models\Location;
use App\Models\Timeslotmanagement;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\OrderEarningExport;
use App\Exports\ItemEarningExport;
use App\Exports\MisEarningExport;
use App\Exports\MisEventEarningExport;
use App\Exports\CustomerEarningExport;
use App\Exports\TicketEarningExport;
use Maatwebsite\Excel\Facades\Excel;

class EarningController extends Controller
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
        $filter     = $request->query('filter');
        $Conditions = ['=','!=','<','<=','>','>='];
        $Tax        = $request->query('tax') ? $request->query('tax') : '';
        $Del_charge = $request->query('del_charge') ? $request->query('del_charge') : '';
        $Revenue    = $request->query('revenue') ? $request->query('revenue') : '';
        $pack_charge= $request->query('pack_charge') ? $request->query('pack_charge') : '';
        $offercode  = $request->query('offer_code') ? $request->query('offer_code') : '';
        $Offer      = $request->query('offer') ? $request->query('offer') : '';
        $Commission = $request->query('commission') ? $request->query('commission') : '';
        $date       = $request->query('date') ? $request->query('date') : '';
        $chef_id    = $request->query('vendor_id') ? $request->query('vendor_id') : '';
        $location_id= $request->query('location_id') ? $request->query('location_id') : '';
        $customer_paid = $request->query('customer_paid') ? $request->query('customer_paid') : '';
        $chefs      = Chefs::approved()->Haveinfo()->Havemenus()->where('type','!=','event')->get();
        $events     = Chefs::approved()->havemenus()->where('type','event')->get();
        $location   = Location::all();
        $type       = \Request::segment(3);
        $pageCount  = 10;
        $user_id    = \Auth::user()->id;
        $role       = \Auth::user()->role;
        $page       = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
        $status     = $request->query('status') ? $request->query('status') : '';
        $chef       = $request->query('chef') ? $request->query('chef') : '';
        $gst_number = $request->query('gst_number') ? $request->query('gst_number') : '';  
        $delivery_place = $request->query('delivery_place') ? $request->query('delivery_place') : '';
        $Commission_amt = $request->query('commission_amt') ? $request->query('commission_amt') : '';

        /*$report=getPayoutReport('4','2021-09-23','2021-09-30');
        echo "<pre>";print_r($report);exit;*/
        
        if($type == 'order' || $type == 'chef' || $type == 'revenueorder') {
            $user_det   = ($type == 'order') || ($type == 'revenueorder') ? 'getUserDetails' : 'getVendorDetails';
            $resultData = Orderdetail::where('order_type','menuitem')->
            where(function($query) use($role,$user_id,$chef_id,$location_id,$status,$offercode) {
                if ($role != 1 && $role != '5') {
                    // echo $chef_id;exit();
                    $query->where('vendor_id',$user_id);

                } elseif ($chef_id != '') {
                    $query->where('vendor_id',$chef_id);
                }
                if ($status != '') {
                    if ($status == 'pending') {
                        $query->where('status','=','pending');
                    } elseif ($status == 'completed') {
                        $query->where('status','=','completed');
                    } elseif ($status == 'food_ready') {
                        $query->where('status','=','food_ready');
                    } elseif ($status == 'accepted') {
                        $query->where(function ($qy) {
                            $qy->orwhere('status','=','accepted_res')->orwhere('status','=','accepted_admin');
                        });
                    } elseif ($status == 'on_d_way') {
                        $query->where('status', '=', 'accepted_boy')->orWhere('status', '=', 'pickup_boy')->orWhere('status', '=', 'reached_location');
                    }
                }
            })
            ->WhereHas('restaurant', function (Builder $query) use($location_id) {
                if ($location_id != '') {
                    $query->where('location',$location_id);
                }
            })

            ->where(function($query) use($filter,$date,$user_det,$type,$role,$Tax,$Del_charge,$Revenue,$Offer,$Commission,$pack_charge,$offercode,$customer_paid,$gst_number,$Commission_amt){
                if (!empty($filter)) {
                    $query->where(function($squery) use($filter,$date,$user_det,$type,$role) {
                        if($role == '1' || $role == '5'){
                            $squery->where(\DB::raw('substr(created_at, 1, 10)'),$filter)
                            ->orWhere('s_id', '=', $filter)
                            ->orWhere('commission_amount', 'like', '%'.$filter.'%')
                            ->orWhere('vendor_price', 'like', '%'.$filter.'%')
                            ->orWhere('grand_total', 'like', '%'.$filter.'%')
                            ->orWhereHas('getUserDetails', function($query) use($filter,$type) {
                                if (!empty($filter) && $type == 'order') {
                                    $query->where('name', 'like', '%'.$filter.'%');
                                }
                            })
                            ->orWhereHas('getVendorDetails', function($query) use($filter) {
                                if (!empty($filter)) {
                                    $query->where('name', 'like', '%'.$filter.'%');
                                }
                            });

                        }else if($role == '3'){
                            $squery->where(\DB::raw('substr(created_at, 1, 10)'),$filter)
                            ->orWhere('s_id', '=', $filter)
                            ->orWhere('vendor_price', 'like', '%'.$filter.'%');
                        }
                    });
                }

                if(!empty($date)) {
                    $sDate  = explode(" - ",$date);
                    $query->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
                }
                $taxcount   = $Tax ? count($Tax) : 0;
                if($taxcount >= 2) {
                    $query->where('tax_amount',$Tax[0],$Tax[1]);
                }
                $del_charge_count = $Del_charge ? count($Del_charge) : 0;
                if($del_charge_count >= 2) {
                    $query->where('del_charge',$Del_charge[0],$Del_charge[1]);
                }
                $revenue_count = $Revenue ? count($Revenue) : 0;
                if($revenue_count >= 2) {
                    $query->where('commission_amount',$Revenue[0],$Revenue[1]);
                }
                $pack_count  = $pack_charge ? count($pack_charge) : 0;
                if($pack_count >= 2) {
                    $query->where('package_charge',$pack_charge[0],$pack_charge[1]);
                }
                $offer_count = $Offer ? count($Offer) : 0;
                if($offer_count >= 2) {
                    $query->where('offer_percentage',$Offer[0],$Offer[1]);
                }
                $commission_count = $Commission ? count($Commission) : 0;
                if($commission_count >= 2) {
                    $query->where('commission',$Commission[0],$Commission[1]);
                }
                $customerpaid_count  = $customer_paid ? count($customer_paid) : 0;
                if($customerpaid_count >= 2) {
                    $query->where('grand_total',$customer_paid[0],$customer_paid[1]);
                } 
                $offercode_count = $offercode ? count($offercode) : 0;
                if($offercode_count >= 2) {                    
                    $query->whereHas('order',function($qqy) use($offercode){
                        $qqy->whereHas('promo',function($qpy) use($offercode){
                            $qpy->where('promo_code',$offercode[0],$offercode[1]);
                        });
                    }); 
                }
                if(!empty($gst_number)) {
                    $query->whereHas('getVendorDetails',function($gstqry) use($gst_number){
                        $gstqry->whereHas('getdocument',function($dqry) use($gst_number){
                            $dqry->where('gst_no',$gst_number);
                        });
                    });
                }
                $Commission_amt_count = $Commission_amt ? count($Commission_amt) : 0;
                if($Commission_amt_count >= 2) {
                    $query->where('commission_amount',$Commission_amt[0],$Commission_amt[1]);
                }
            })->whereHas('order',function($orqry) use($delivery_place){
                if(!empty($delivery_place)) {
                    $orqry->whereHas('getUserAddress',function($addqry) use($delivery_place) {
                        $addqry->where('city',$delivery_place);
                    });
                }
            })->with('order')->orderByDesc('id')->paginate($pageCount);
            return view('earning.index',compact('resultData','page','filter','type','date','chefs','location','Conditions'));
        } elseif ($type == 'mis' || $type == 'mis_events' || $type == 'revenue_events') {
            $chefid = !empty($request->chefid) ? $request->chefid : ''; 
          /*  if ($chef_id) {
                $chef_id        = array_filter($chef_id);
                if (empty($chef_id)) {
                    $chef_id    = '';
                } else {
                    $chef_id    = $chef_id;
                }
            }*/
            if ($location_id) {
                $location_id    = array_filter($location_id);
                if (empty($location_id)) {
                    $location_id    = '';
                } else{
                    $location_id    = $location_id;
                }
            }
            $resultData = Orderdetail::
            where(function($query) use($role,$user_id,$chefid,$type) {
                /*if($role != 1){
                    $query->where('vendor_id',$user_id);
                } else if($chef_id!=''){
                    $query->whereIn('vendor_id',$chef_id);
                }*/
                if(!empty($chefid)) {
                    $query->where('vendor_id',$chefid);
                }
                if($type == 'mis_events' || 'revenue_events') {
                    $query->where('order_type','ticket');
                }
            })
            ->WhereHas('restaurant', function (Builder $query) use($location_id) {
                if($location_id != ''){
                    //$query->where('location',$location_id);
                    $query->whereIn('location',$location_id);
                }
            })
            ->where(function($query) use($filter,$date,$Tax,$Del_charge,$Revenue,$Offer,$Commission) {
                if (!empty($filter)) {
                    $query->where(function($squery) use($filter,$date) {
                        $squery
                        ->where(\DB::raw('substr(created_at, 1, 10)'),$filter)
                        ->orWhere('s_id', 'like', '%'.$filter.'%')
                        ->orWhere('total_food_amount', 'like', '%'.$filter.'%')
                        ->orWhere('vendor_price', 'like', '%'.$filter.'%')
                        ->orWhere('commission', 'like', '%'.$filter.'%')
                        ->orWhere('commission_amount', 'like', '%'.$filter.'%')
                        ->orWhere('del_km', 'like', '%'.$filter.'%')
                        ->orWhere('del_charge', 'like', '%'.$filter.'%')
                        ->orWhere('offer_percentage', 'like', '%'.$filter.'%')
                        ->orWhere('offer_amount', 'like', '%'.$filter.'%')
                        ->orWhere('grand_total', 'like', '%'.$filter.'%')
                        ->orWhereHas('getUserDetails', function($query) use($filter) {
                            if (!empty($filter)) {
                                $query->where('name', 'like', '%'.$filter.'%')->orWhere('email', 'like', '%'.$filter.'%')->orWhere('mobile', 'like', '%'.$filter.'%');
                            }
                        })->orWhereHas('getVendorDetails', function($query) use($filter) {
                            if (!empty($filter)) {
                                $query->where('name', 'like', '%'.$filter.'%');
                            } 
                        })->orWhereHas('order.getUserAddress', function($query) use($filter) {
                            if (!empty($filter)) {
                                $query->having('address', 'like', '%'.$filter.'%');
                            }
                        })->orWhereHas('getBoyinfo', function($query) use($filter) {
                            if (!empty($filter)) {
                                $query->having('name', 'like', '%'.$filter.'%');
                            }
                        })->orWhereHas('order', function($query) use($filter){
                            if (!empty($filter)) {
                                $query->where('payment_type', 'like', '%'.$filter. '%');
                            }
                        });
                    });
                }

                if(!empty($date)) {
                    $sDate  = explode(" - ",$date);
                    $query->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
                }
                $taxcount   = $Tax ? count($Tax) : 0;
                if($taxcount >= 2) {
                    $query->where('tax',$Tax[0],$Tax[1]);
                }
                $del_charge_count = $Del_charge ? count($Del_charge) : 0;
                if($del_charge_count >= 2) {
                    $query->where('del_charge',$Del_charge[0],$Del_charge[1]);
                }
                $revenue_count = $Revenue ? count($Revenue) : 0;
                if($revenue_count >= 2) {
                    $query->where('commission_amount',$Revenue[0],$Revenue[1]);
                }
                $offer_count = $Offer ? count($Offer) : 0;
                if($offer_count >= 2) {
                    $query->where('offer_percentage',$Offer[0],$Offer[1]);
                }
                $commission_count = $Commission ? count($Commission) : 0;
                if($commission_count >= 2) {
                    $query->where('commission',$Commission[0],$Commission[1]);
                }
            });
            if($type == "mis_events" || 'revenue_events') {
                $resultData = $resultData->orderByDesc('id');    
            }
            $resultData = $resultData->paginate($pageCount);
            $blade = 'earning.mis_index';
            if($type == "mis_events" || 'revenue_events'){
               $blade = 'earning.misevents';
           }
           return view($blade,compact('resultData','page','filter','type','date','chefs','location','Conditions','events'));
        } elseif ($type == 'item' || $type == 'ticket') {
            ini_set('memory_limit', '-1');
            $user_id    = \Auth::user()->id;
            if($type == "item") {
                $resultData = Menuitems::where('food_type','menuitem');
            } else {
                $resultData = Menuitems::where('food_type','ticket');
            }
            $resultData = $resultData->where(function($query)use($role,$user_id,$chef_id){
                $check  = Restaurants::where('vendor_id',$chef_id)->exists();
                if($check){
                    $res_id     = Restaurants::where('vendor_id',$chef_id)->first();
                    $query->where('restaurant_id',$res_id->id);
                }
            })
            ->where(function($query) use($filter,$date,$user_id,$chef) {
                if (!empty($filter)) {
                    $query->where(function($squery) use($filter,$date) {
                        $squery->orWhere('name', 'like', '%'.$filter.'%');  
                    });
                }
                if(\Auth::user()->role != 1 || $chef != ''){
                    if($chef){
                        $user_id = $chef;
                    }
                    $query->where('vendor_id',$user_id);
                }

            })->WhereHas('restaurant', function (Builder $query) use($location_id) {
                if($location_id != ''){
                    $query->where('location',$location_id);
                }
            })->whereHas('vendor',function($qry) use($type){
                $qry->approved()->haveinfo();
                if($type == "item") {
                    $qry->havemenus();
                } else {
                    $qry->havetickets();
                }
            })->with('vendor')
            ->paginate($pageCount);
                // $allchef = Chefs::select('id','name')->approved()->haveinfo()->havemenus()->get();
                /*->map(function($item) {
                $title   = $item->id;
                $result  = Orderdetail::where('res_id',$item->restaurant_id)->get()->map(function($result)use($title) {
                if(isset($result->food_items[0]['id']) && $result->food_items[0]['id']==$title) {
                return $result->food_itemsj[0]['price'];
                }
                })->sum();
                $item->order_price = $result;
                return $item;
            });*/
            $blade = ($type == 'item') ? 'earning.item_index' : 'earning.ticket_index'; 
            return view($blade,compact('resultData','page','filter','type','date','chefs','location','events'));
            } elseif ($type == 'customer' || $type ='repeat_customer') {
            $onboard_date   = $request->query('onboard_date') ? $request->query('onboard_date') : '';
            $customer_state = $request->query('customer_state') ? $request->query('customer_state') : ''; 
            $city           = $request->query('city') ? $request->query('city') : ''; 
            $timeslot       = $request->query('timeslot') ? $request->query('timeslot') : '';  
            if ($role != 1) {
                return \Redirect::to(getRoleName().'/dashboard');
            } else {
                        /*$resultData = Orderdetail::select('id','m_id','order_id','user_id')
                        ->with(['getUserDetails' => function($query) {
                        $query->addSelect('id','name','email','mobile','created_at');
                    }])->orderBy('id','desc')->groupBy('user_id')->paginate($pageCount);*/
                        // \DB::enableQueryLog();
                        $resultData = Customer::/*with(['Orders' => function($query) {
                        $query->completed();
                        //$query->groupBy('user_id');
                    }])->*/where(function($ssquery) use($onboard_date,$city) {
                        if(!empty($onboard_date)) {
                            $oDate  = explode(" - ",$onboard_date);
                            $ssquery->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($oDate[0])),date('Y-m-d',strtotime($oDate[1]))]);
                        }
                    })->withCount(['Orders AS countorder' => function($query) use($date) {
                        $query->completed();
                        if(!empty($date)) {
                            $sDate  = explode(" - ",$date);
                            $query->whereBetween(\DB::raw('date'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
                        }
                    },'Orders AS spend_amt' => function ($squery) use($date) {
                        $squery->select(\DB::raw("SUM(grand_total) as paidsum"))->completed();
                        if(!empty($date)) {
                            $sDate  = explode(" - ",$date);
                            $squery->whereBetween(\DB::raw('date'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
                        }
                    },'Orders AS fooditemcount' => function($fquery){
                        $fquery->completed()->where('order_type','menuitem'); 
                    },'Orders AS fooditem_spend_amt' => function($fsquery){  
                        $fsquery->completed()->where('order_type','menuitem')->select(\DB::raw("SUM(grand_total)"));
                    },'Orders AS eventcount' => function($equery) {
                        $equery->completed()->where('order_type','ticket'); 
                    },'Orders AS event_spend_amt' => function($esquery) {
                        $esquery->completed()->where('order_type','ticket')->select(\DB::raw("SUM(grand_total)")); 
                    }]);
                    if($customer_state){
                        ($customer_state == 'ordered') ? $resultData->having('countorder','>',0)->orderByDesc('countorder') : $resultData->having('countorder',0);
                    }
                    if($city){
                        $resultData->whereHas('useraddress',function($cquery) use($city){
                            $cquery->where('address_type','home')->where('city',$city);
                        });
                    }
                    if($timeslot){
                        $resultData->whereHas('Orders',function($tquery) use($timeslot){
                            $tquery->completed()->where('time_slot',$timeslot);
                        });
                    }
                    $resultData = $resultData->with('useraddress')->orderBy('id','desc')->paginate($pageCount);
                    $timeslots = Timeslotmanagement::get();
                        // dd(\DB::getQueryLog($resultData));
                        //echo "<pre>";print_r($resultData);exit;
                        // return \Response::json($resultData);
                    return view('earning.customer',compact('resultData','page','date','chefs','onboard_date','timeslots','type'));
                }
        } elseif ($type == 'downloadfile') {
            $this->downloadfile($request);
        } elseif ($type == 'downloadfilemis') {
            $this->downloadfilemis($request);
        }
    }

    public function downloadfile(Request $request)
    {
        $filter = $request->query('filter');
        
        $date = $request->query('date') ? $request->query('date') : '';
        $user_id=\Auth::user()->id;
        $role=\Auth::user()->role;
       // $type=\Request::segment(3);
        $type = $request->query('type');
        //print_r($type);exit;
        $resultData = Orderdetail::
        where(function($query)use($role,$user_id){
            if($role!=1){
                $query->where('vendor_id',$user_id);
            }
        })
        ->where(function($query)use($filter,$date,$type){
            if (!empty($filter)) {
                $query->where(function($squery)use($filter,$date){
                    $squery->where(\DB::raw('substr(created_at, 1, 10)'),$filter)
                    ->orWhere('s_id', 'like', '%'.$filter.'%')
                    ->orWhere('commission_amount', 'like', '%'.$filter.'%')
                    ->orWhere('vendor_price', 'like', '%'.$filter.'%')
                    ->orWhere('grand_total', 'like', '%'.$filter.'%')
                    ->orWhereHas('getUserDetails', function($query)use($filter,$type){
                        if (!empty($filter) && $type=='order') {
                            $query->where('name', 'like', '%'.$filter.'%');
                        }
                    })
                    ->orWhereHas('getVendorDetails', function($query)use($filter){
                        if (!empty($filter)) {
                            $query->where('name', 'like', '%'.$filter.'%');
                        }
                    });
                });
            }

            
            if(!empty($date)){
                $sDate=explode(" - ",$date);
                $query->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
            }
        })->get();

        $file = 'EarningReportExcelFile-'.date("Y-M-D")."-".time().'.xls';        
        $content = (string)view('earning.download',compact('resultData','type'));
        header("Expires: 0");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");  header("Content-type: application/vnd.ms-excel;charset:UTF-8");
        header('Content-length: '.strlen($content));
        header('Content-disposition: attachment; filename='.basename($file));
        echo $content;
        exit;
    }

    public function downloadfilemis(Request $request)
    {
        $filter = $request->query('filter');$date = $request->query('date') ? $request->query('date') : '';
        $user_id=\Auth::user()->id;
        $role=\Auth::user()->role;
        $resultData = Orderdetail::
        where(function($query)use($role,$user_id){
            if($role!=1){
                $query->where('vendor_id',$user_id);
            }
        })
        ->where(function($query)use($filter,$date){
            if (!empty($filter)) {
                $query->where(function($squery)use($filter,$date){
                    $squery
                    ->where(\DB::raw('substr(created_at, 1, 10)'),$filter)
                    ->orWhere('s_id', 'like', '%'.$filter.'%')
                    ->orWhere('total_food_amount', 'like', '%'.$filter.'%')
                    ->orWhere('vendor_price', 'like', '%'.$filter.'%')
                    ->orWhere('commission', 'like', '%'.$filter.'%')
                    ->orWhere('commission_amount', 'like', '%'.$filter.'%')
                    ->orWhere('del_km', 'like', '%'.$filter.'%')
                    ->orWhere('del_charge', 'like', '%'.$filter.'%')
                    ->orWhere('offer_percentage', 'like', '%'.$filter.'%')
                    ->orWhere('offer_amount', 'like', '%'.$filter.'%')
                    ->orWhere('grand_total', 'like', '%'.$filter.'%')
                    ->orWhereHas('getUserDetails', function($query)use($filter){
                        if (!empty($filter)) {
                            $query->where('name', 'like', '%'.$filter.'%')->orWhere('email', 'like', '%'.$filter.'%')->orWhere('mobile', 'like', '%'.$filter.'%');
                        }
                    })->orWhereHas('getVendorDetails', function($query)use($filter){
                        if (!empty($filter)) {
                            $query->where('name', 'like', '%'.$filter.'%');
                        }
                    })->orWhereHas('order.getUserAddress', function($query)use($filter){
                        if (!empty($filter)) {
                            $query->where('address', 'like', '%'.$filter.'%');
                        }
                    })->orWhereHas('getBoyinfo', function($query)use($filter){
                        if (!empty($filter)) {
                            $query->where('name', 'like', '%'.$filter.'%');
                        }
                    });
                });
            }
            if(!empty($date)){
                $sDate=explode(" - ",$date);
                $query->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
            }
        })->get();

        $file = 'EarningReportExcelFile-'.date("Y-M-D")."-".time().'.xls';        
        $content = (string)view('earning.downloadmis',compact('resultData'));
        header("Expires: 0");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");  header("Content-type: application/vnd.ms-excel;charset:UTF-8");
        header('Content-length: '.strlen($content));
        header('Content-disposition: attachment; filename='.basename($file));
        echo $content;
        exit;
    }

    public function orderearexport(Request $request,$slug)
    {
        $type       = \Request::segment(1);
        // dd($request->all());
         //print_r($type);exit;
        $request->all();
        $exporter = app()->makeWith(OrderEarningExport::class, compact('request','type'));
        return $exporter->download('OrderEarningExport_'.date('Y-m-d').'.'.$slug); 
    }

    public function itemexport(Request $request,$slug)
    {
        $request->all();
        $exporter = app()->makeWith(ItemEarningExport::class, compact('request'));  
        return $exporter->download('ItemEarningExport_'.date('Y-m-d').'.'.$slug);
    }

    public function misearexport(Request $request,$slug)
    {
     $request->all();
     $exporter = app()->makeWith(MisEarningExport::class, compact('request'));  
     return $exporter->download('MisEarningExport_'.date('Y-m-d').'.'.$slug);
    }

    public function customerearrexport(Request $request,$slug)
    {
    $request->all();
    $exporter = app()->makeWith(CustomerEarningExport::class, compact('request'));  
    return $exporter->download('CustomerEarningExport_'.date('Y-m-d').'.'.$slug);
    }

    public function miseventexport(Request $request,$slug)
    {
       $request->all();
       $exporter = app()->makeWith(MisEventEarningExport::class, compact('request'));  
       return $exporter->download('MisEventEarningExport_'.date('Y-m-d').'.'.$slug);
   }

    public function ticketexport(Request $request,$slug)
    {
      $request->all();
      $exporter = app()->makeWith(TicketEarningExport::class, compact('request'));  
      return $exporter->download('ItemEarningExport_'.date('Y-m-d').'.'.$slug);
  }
}
