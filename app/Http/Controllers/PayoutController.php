<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Account;
use App\Models\Chefs;
use App\Models\Order;
use App\Models\Payout;
use App\Models\Tds;
use App\Exports\PayoutExport;
use App\Models\Orderdetail;
use App\Http\Controllers\Api\AuthController; 
use Illuminate\Support\Facades\Crypt;


class PayoutController extends Controller
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
    public function index()
    {
       $customer=[];
        return view('customer.index',compact('customer'));
    }   
     public function show(Request $request)
    {
        $filter = $request->query('filter');
        $pageCount=10;
        $page=(($request->query('page')) ? $request->query('page')-1 : 0) * $pageCount;     
        $type= \Request::segment(3);
        $customer=[];
        if( (\Auth::user()->role==1 || \Auth::user()->role==3 || \Auth::user()->role==5) && $type=='account'){
            $resultData=Chefs::accountselect()
            ->where(function($query)use($filter){
                if(!empty($filter)){
                    $query->where('name','like','%'.$filter.'%')->orWhere('email','like','%'.$filter.'%');
                }
            })
            ->paginate($pageCount);
        return view('payout.account',compact('resultData','filter','page'));
        } elseif( \Auth::user()->role==3 && $type=='request'){
            $resultData=$this->wallet(); 
        return view('payout.vendor_request',compact('resultData','page'));
        } elseif ($type == 'payout_tds') {
            $resultData        = Tds::with('chefDetails');
            if(\Auth::user()->role== 3){
             $resultData =$resultData->where('chef',\Auth::user()->id);
         }
             $resultData =$resultData->paginate($pageCount);
                return view('payout.payout_tds',compact('resultData','page')); 
        }elseif($type=='history'){
            $month = date('m');
            $year  = date('Y');
            if(!empty($request->month)){
                $filt = explode('-',$request->month);
                if(count($filt) == 2){
                    $year  = $filt[1];
                    $month = $filt[0];
                }
            }
            $resultData=Payout::
            where(function($query)use($filter){
                if(!empty($filter)){
                    $query->where('status','like','%'.$filter.'%')->orWhere('rayzorpay_id','like','%'.$filter.'%')->orWhere('amount','like','%'.$filter.'%')->orWhere(\DB::raw('substr(created_at, 1, 10)'),$filter)->orWhereHas('getVendorDetails', function($query)use($filter){               
                        $query->where('name', 'like', '%'.$filter.'%')->orWhere('email', 'like', '%'.$filter.'%');             
                    });
                }
            })->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)
            ->paginate($pageCount);
            $monthpick = $month.'-'.$year;
        return view('payout.history',compact('resultData','filter','page','type','monthpick'));
        }elseif( (\Auth::user()->role==1 || \Auth::user()->role==3 || \Auth::user()->role==5) && $type=='request'){
            $resultData=Payout::request()
            ->where(function($query)use($filter){
                if(!empty($filter)){
                    $query->where('status','like','%'.$filter.'%')->orWhere('rayzorpay_id','like','%'.$filter.'%')->orWhere('amount','like','%'.$filter.'%')->orWhere(\DB::raw('substr(created_at, 1, 10)'),$filter)->orWhereHas('getVendorDetails', function($query)use($filter){               
                        $query->where('name', 'like', '%'.$filter.'%')->orWhere('email', 'like', '%'.$filter.'%');             
                    });
                }
            })
            ->paginate($pageCount);
        return view('payout.history',compact('resultData','filter','page','type'));
        }elseif($type=="setting"){
            $account = Chefs::with('rzaccount_active')->find(\Auth::user()->id);
            return view('payout.setting',compact('account'));
        }
    } 
    public function update(Request $request){


        if($request->id>0){
            $payout=Payout::find($request->id);
        }else{
            $payout=new Payout;            
        }
        if((\Auth::user()->role==1 || \Auth::user()->role==5)){
            Flash::success('Payout status updated successfully.');
            $payout->status=$request->status;
        }else{
            $payout->amount=$request->amount;
            $payout->v_id=\Auth::user()->id;
          

            if($this->wallet()['wallet_amount']<$request->amount){
                Flash::error('Your requested amount should not be greater than your wallet amount.');
                return redirect(getRoleName().'/payout/'.$request->type);
            }else{
                  Flash::success('Your request sent successfully.');
            }
        }
        
        $payout->save();

        
        return redirect(getRoleName().'/payout/'.$request->type);
    }

    function wallet(){
        $resultData=[];
        $resultData['no_of_orders']=Orderdetail::where('vendor_id',\Auth::user()->id)->where('status','completed')->count();
        $resultData['total_amount']=Orderdetail::where('vendor_id',\Auth::user()->id)->where('status','completed')->sum('vendor_price');
        $resultData['transferred_amount']=Payout::where('v_id',\Auth::user()->id)->where('status','transferred')->sum('amount');
        $resultData['requested_amount']=Payout::where('v_id',\Auth::user()->id)->whereIN('status',['pending','accepted'])->sum('amount');
        $resultData['wallet_amount']=$resultData['total_amount'] - ($resultData['transferred_amount']+$resultData['requested_amount']);
        return $resultData;
    }

    function orderList($payoutid,$action='html'){
        $payout           = Payout::with('orders')->find($payoutid);
        if($action=='html'){
            $response['html']   = (String) view('payout.orderlist',compact('payout'));
            return \Response::json($response,200);
        }else{
            $request['payout'] = $payout;
            $slug = 'csv';
            $exporter = app()->makeWith(PayoutExport::class, compact('request'));  
            return $exporter->download('PayoutExport_'.date('Y-m-d').'.'.$slug);
        }
    }
}
