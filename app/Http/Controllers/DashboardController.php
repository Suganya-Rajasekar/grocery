<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Orderdetail;
use App\Models\Chefs;
use App\Models\Restaurants;
use App\Models\Category;
use App\Models\Cuisines;
use App\Models\Location;

use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    protected $redirectTo = RouteServiceProvider::HOME;
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
    public function admin()
    {
        return view('home');
    }

    public function insights(Request $request)
    {
        $func   = 'App\Http\Controllers\Api\Partner\PartnerController@insights';
        $func1   = 'App\Http\Controllers\Api\Partner\PartnerController@Sales';
        $today  =  app()->call($func,[request()->merge(['range'=>'today'])])->getData();
        $week   =  app()->call($func,[request()->merge(['range'=>'week'])])->getData();
        $month  =  app()->call($func,[request()->merge(['range'=>'month'])])->getData();
        $sales  =  app()->call($func1,['auth_id' => \Auth::user()->id]);
        $insights['insights']['today']  = $today;
        $insights['insights']['week']   = $week;
        $insights['insights']['month']  = $month;
        $insights['sales']  = $sales;
        $from         = $request->query('from_date') ? $request->query('from_date') : '';
        $to           = $request->query('to_date') ? $request->query('to_date') : '';
        $revenue_data = getPayoutReport(\Auth::user()->id,$from,$to);
        $revenue      = json_decode($revenue_data);
        return view('insights.insights',compact('insights','revenue'));
    }

    public function pieChart()
    {
        $today      = date('Y-m-d');
        $tomorrow   = date("Y-m-d", strtotime("+1 day"));
        $orderDtl   = new Orderdetail;
        $pToday     = $orderDtl::where('date',$today)->whereNotIn('status',['rejected_admin','rejected_res','rejected_cus','cancelled']);
        $pTomrw     = $orderDtl::where('date',$tomorrow);
        $pOrders    = $orderDtl::whereRaw('date > date(now()) - interval "10" day');
        $oMonth     = $orderDtl::whereRaw('MONTH(date) = MONTH(CURDATE())');
        $oCMonth    = $orderDtl::completed()->whereRaw('MONTH(date) = MONTH(CURDATE())');
        $oYear      = $orderDtl::whereRaw('YEAR(date) = YEAR(CURDATE())');
        $oCYear     = $orderDtl::completed()->whereRaw('YEAR(date) = YEAR(CURDATE())');
        if (\Auth::user()->role == 3) {
            $pToday = $pToday->where('vendor_id',\Auth::id());
            $pTomrw = $pTomrw->where('vendor_id',\Auth::id());
            $pOrders= $pOrders->where('vendor_id',\Auth::id());
            $oMonth = $oMonth->where('vendor_id',\Auth::id());
            $oCMonth= $oCMonth->where('vendor_id',\Auth::id());
            $oYear  = $oYear->where('vendor_id',\Auth::id());
            $oCYear = $oCYear->where('vendor_id',\Auth::id());
        }
        $pToday     = $pToday->count();
        $pTomrw     = $pTomrw->count();
        $pOrders    = $pOrders->count();
        $oMonth     = $oMonth->count();
        $oCMonth    = $oCMonth->count();
        $oYear      = $oYear->count();
        $oCYear     = $oCYear->count();

        $response['PlannedOrders'] = array(
            array(
                'name'   =>  'PLANNED ORDERS FOR TODAY', // Legend Name
                'y'      =>  $pToday,
            ),
            array(
                'name'   =>  'PLANNED ORDERS FOR TOMORROW',
                'y'      =>  $pTomrw,
            ),
            array(
                'name'   =>  'PLANNED ORDERS FOR NEXT 10 DAYS( INCLUDING TODAY )',
                'y'      =>  $pOrders,
            ),
        );

        $response['OrdersCount'] = array(
            array(
                'name'   =>  'NUMBER OF ORDERS RECEIVED MTD',
                'y'      =>  $oMonth,  
            ),
            array(
                'name'   =>  'NUMBER OF ORDERS DELIVERED MTD',
                'y'      =>  $oCMonth,  
            ),
            array(
                'name'   =>  'NUMBER OF ORDERS RECEIVED YTD',
                'y'      =>  $oYear,
            ),
            array(
                'name'   =>  'NUMBER OF ORDERS DELIVERED YTD',
                'y'      =>  $oCYear,
            ),
        );
        $today          = date('Y-m-d');
        $response['OrdersNearby'] = array(
            array(
                'name'   =>  'ORDERS WITHIN 5 KM RANGE',
                'y'      =>  Orderdetail::where('date',$today)->where('del_km','<',5)->count(), 
            ),
            array(
                'name'   =>  'ORDERS IN 5-8 KM RANGE',
                'y'      =>  Orderdetail::where('date',$today)->where('del_km','>=',5)->where('del_km','<=',8)->count(), 
            ),
        );

        $response['Chefs'] = array(
            array(
                'name'   =>  'TOTAL CHEFS LIVE ON KNOSH',
                'y'      =>  Chefs::approved()->get()->where('avalability','avail')->count(), 
            ),
            array(
                'name'   =>  'NEW FORMS RECEIVED MTD',
                'y'      =>  Chefs::pending()->get()->where('documentverify','no')->count(),
            ),
        );

        $chefsin_eachcategory       = Category::withCount('Groupedmenus as y')->having('y', '>' , 0)->get()->makeHidden(['id']);
        $response['ChefsinCategory']= $chefsin_eachcategory;
        $chefsin_eachcuisine        = Cuisines::where('status','active')->withCount('chefsget as y')->having('y', '>' , 0)->get()->makeHidden(['id']);

        $response['ChefsinCuisine'] = $chefsin_eachcuisine;
        $response['ChefsinCuisineCategory'] = array(
            array(
                'name'   =>  'CHEFS IN EACH CUISINE',
                'y'      =>  $chefsin_eachcuisine, 
            ),
            array(
                'name'   =>  'CHEFS IN EACH CATGEORY',
                'y'      =>  $chefsin_eachcategory, 
            ),
        );
        $TopPerformingDay = Orderdetail::where('date',date('Y-m-d'))->withCount('getChefOrder')->having('get_chef_order_count','>',0)->orderBy('get_chef_order_count','desc')->groupBy('vendor_id')->take(5)->get();
        $TopPerformingDayArray = array();

        if(!empty($TopPerformingDay)){
            $totalRank = count($TopPerformingDay);
            foreach ($TopPerformingDay as $key => $value) {
                $userArr =  array(
                    'Rank' => $key + 1,
                    'Orders' => $value->get_chef_order_count,
                    'name' => $value->chefinfo->name,
                    'y'    => $totalRank-$key,
                    'z'    => $value->get_chef_order_count,
                );
                $TopPerformingDayArray[] = $userArr;
            }
        }
        $response['TopPerformingDay'] = $TopPerformingDayArray;


        $TopPerformingMonth = Orderdetail::whereRaw('MONTH(date) = MONTH(CURDATE())')->withCount('getChefOrder')->having('get_chef_order_count','>',0)->orderBy('get_chef_order_count','desc')->groupBy('vendor_id')->take(5)->get();

        $TopPerformingMonthArray = array();

        if(!empty($TopPerformingMonth)){
            $totalRank = count($TopPerformingMonth);
            foreach ($TopPerformingMonth as $key => $value) {
               $userArr =  array(
                    'Rank' => $key + 1,
                    'Orders' => $value->get_chef_order_count,
                    'name' => $value->chefinfo->name,
                    'y'    => $totalRank - $key,
                    'z'    => $value->get_chef_order_count,
                );
               $TopPerformingMonthArray[] = $userArr;
            }
        }
        $response['TopPerformingMonth'] = $TopPerformingMonthArray;
        $TopPerformingYear = Orderdetail::whereRaw('YEAR(date) = YEAR(CURDATE())')->withCount('getChefOrder')->having('get_chef_order_count','>',0)->orderBy('get_chef_order_count','desc')->groupBy('vendor_id')->take(5)->get();

        $TopPerformingYearArray = array();

        if(!empty($TopPerformingYear)){
            $totalRank = count($TopPerformingYear);
            foreach ($TopPerformingYear as $key => $value) {
                $userArr =  array(
                    'Rank' => $key + 1,
                    'Orders' => $value->get_chef_order_count,
                    'name' => $value->chefinfo->name,
                    'y'    => $totalRank-$key,
                    'z'    => $value->get_chef_order_count,
                );
                $TopPerformingYearArray[] = $userArr;
            }
        }
        $response['TopPerformingYear'] = $TopPerformingYearArray;
        $TopPerformingCityDay = Location::with(['getVendor'=>function($query){
            $query/*->addSelect(`users`.`id`)*/
            ->withCount(['getOrders'=>function($oQuery){
                $oQuery->where('date',date('Y-m-d'));
            }]);
        }])->active()->get()->map(function($city){
            $totalOrder = 0;
            $newObject = array();
            if (!is_null($city->getVendor)) {
                $totalOrder = $city->getVendor->sum('get_orders_count');
            }
            $newObject['id'] = $city->id;
            $newObject['name'] = $city->name;
            $newObject['code'] = $city->code;
            $newObject['y'] = $totalOrder;
            return collect($newObject);
        })->sortByDesc('totalOrder')->take(5)->where('y','!=',0)->all();

        $collectionTopPerformingCityDay = collect($TopPerformingCityDay);
        $totalDayCount =  $collectionTopPerformingCityDay->sum('y');
        $response['TopPerformingCityDay'] =$collectionTopPerformingCityDay->map(function($result) use($totalDayCount){
            $neededResult = array();
            $neededResult['y'] = $result['y'] * (100/$totalDayCount);
            $neededResult['name'] = $result['name'];
            return collect($neededResult);
        });

        $TopPerformingCityMonth = Location::with(['getVendor'=>function($query){
            $query/*->addSelect(`users`.`id`)*/
            ->withCount(['getOrders'=>function($oQuery){
                $oQuery->whereRaw('MONTH(date) = MONTH(CURDATE())');
            }]);
        }])->active()->get()->map(function($city){
            $totalOrder = 0;
            $newObject = array();
            if (!is_null($city->getVendor)) {
                $totalOrder = $city->getVendor->sum('get_orders_count');
            }
            $newObject['id'] = $city->id;
            $newObject['name'] = $city->name;
            $newObject['code'] = $city->code;
            $newObject['y'] = $totalOrder;
            return collect($newObject);
        })->sortByDesc('totalOrder')->take(5)->where('y','!=',0)->all();

        $collectionTopPerformingCityMonth = collect($TopPerformingCityMonth);
        $totalMonthCount =  $collectionTopPerformingCityMonth->sum('y');
        $response['TopPerformingCityMonth'] =$collectionTopPerformingCityMonth->map(function($result) use($totalMonthCount){
            $neededResult = array();
            $neededResult['y'] = $result['y'] * (100/$totalMonthCount);
            $neededResult['name'] = $result['name'];
            return collect($neededResult);
        });

        $TopPerformingCityYear = Location::with(['getVendor'=>function($query){
            $query->addSelect('users.id')->withCount(['getOrders'=>function($oQuery){
                $oQuery->whereRaw('YEAR(date) = YEAR(CURDATE())');
            }]);
        }])->active()->get()->map(function($city){
            $totalOrder = 0;
            $newObject = array();
            if (!is_null($city->getVendor)) {
                $totalOrder = $city->getVendor->sum('get_orders_count');
            }
            $newObject['id'] = $city->id;
            $newObject['name'] = $city->name;
            $newObject['code'] = $city->code;
            $newObject['y'] = $totalOrder%100;
            return collect($newObject);
        })->sortByDesc('totalOrder')->take(5)->where('y','!=',0)->all();

        $collectionTopPerformingCityYear = collect($TopPerformingCityYear);
        $totalYearCount =  $collectionTopPerformingCityYear->sum('y');
        $response['TopPerformingCityYear'] =$collectionTopPerformingCityYear->map(function($result) use($totalYearCount){
            $neededResult = array();
            $neededResult['y'] = $result['y'] * (100/$totalYearCount);
            $neededResult['name'] = $result['name'];
            return collect($neededResult);
        });

            $date = date('Y-m-d');
            $date = strtotime($date);
            $daily=[];
            for($i=1; $i<=7; $i++){

                if($i!=1) {
                    $to = strtotime("+1 day", $to);
                    $from = strtotime("+1 day", $from);
                } else{
                    $to = strtotime("-7 day", $date);
                    $from = strtotime("-7 day", $to);   
                }

                /*echo date('M d, Y', $from).'--from--';
                echo date('M d, Y', $to).'--to--';
                echo "<br>";*/

                $daily[$i-1]['from']= date('Y-m-d', $from);
                $daily[$i-1]['to']= date('Y-m-d', $to);

                $revenue_data = getPayoutReport(\Auth::user()->id,date('Y-m-d', $from),date('Y-m-d', $to));
                // print_r($revenue_data->gross_revenue);exit();
                // print_r(json_decode($revenue_data)->gross_revenue);exit();
                $daily[$i-1]['net_amount'] = json_decode($revenue_data)->net_recievable->amount;
                $daily[$i-1]['deductions'] = json_decode($revenue_data)->deductions->amount;

                $cate['category'][]   = date('d M', $from).' '.date('d M', $to);
                $cate['netamount'][]  = (float)json_decode($revenue_data)->net_recievable->amount;
                $cate['deductions'][] = - ((float)json_decode($revenue_data)->deductions->amount);

            }
            $response['RevenueCategory'] = $cate;
            $category=[];
            $netamount=[];
            $deductions=[];
            for($i=1; $i<=7; $i++){
                if($i!=1) {
                    $end_week = strtotime("-1 day", $start_week);
                    $start_week = strtotime("-6 day", $end_week);
                } else{
                    $start_week = strtotime("last sunday midnight",$date);
                    $end_week = $date;
                }           
                //echo date("Y-m-d",$start_week).' '.date("Y-m-d",$end_week).'<br>';
                $week_revenue_data = getPayoutReport(\Auth::user()->id,date('Y-m-d', $start_week),date('Y-m-d', $end_week));
                $category[]   = date('M d', $start_week).'-'.date('M d', $end_week);
                $netamount[]  = (float)json_decode($week_revenue_data)->net_recievable->amount;
                $deductions[] = - ((float)json_decode($week_revenue_data)->deductions->amount);
            }
            $weekcate['category']  = array_reverse($category);
            $weekcate['netamount'] = array_reverse($netamount);
            $weekcate['deductions'] = array_reverse($deductions);

            $response['RevenueCategoryWeek'] = $weekcate;

        return \Response::json($response,200);
    }

    public function vendor(Request $request)
    {
        $UserData = User::onlyTrashed()->get();
            $date = date('Y-m-d');
            $date = strtotime($date);
            $daily=[];
            for($i=1; $i<=7; $i++){

                if($i!=1) {
                    $to = strtotime("+1 day", $to);
                    $from = strtotime("+1 day", $from);
                } else{
                    $to = strtotime("-7 day", $date);
                    $from = strtotime("-7 day", $to);   
                }

                /*echo date('M d, Y', $from).'--from--';
                echo date('M d, Y', $to).'--to--';
                echo "<br>";*/

                $daily[$i-1]['from']= date('Y-m-d', $from);
                $daily[$i-1]['to']= date('Y-m-d', $to);

                $revenue_data = getPayoutReport(\Auth::user()->id,date('Y-m-d', $from),date('Y-m-d', $to));
                // print_r($revenue_data->gross_revenue);exit();
                // print_r(json_decode($revenue_data)->gross_revenue);exit();
                $daily[$i-1]['net_amount'] = json_decode($revenue_data)->net_recievable->amount;
                $daily[$i-1]['deductions'] = json_decode($revenue_data)->deductions->amount;
                $cate['category'][]   = date('d M', $from).' '.date('d M', $to);
                $cate['netamount'][]  = json_decode($revenue_data)->net_recievable->amount;
                $cate['deductions'][] = json_decode($revenue_data)->deductions->amount;
            }
            //echo "<pre>";print_r($cate);echo "</pre>";
            for($i=1; $i<=7; $i++){
                if($i!=1) {
                    $end_week = strtotime("-1 day", $start_week);
                    $start_week = strtotime("-6 day", $end_week);
                } else{
                    $start_week = strtotime("last sunday midnight",$date);
                    $end_week = $date;
                }           
                //echo date("Y-m-d",$start_week).' '.date("Y-m-d",$end_week).'<br>';
                $week_revenue_data = getPayoutReport(\Auth::user()->id,date('Y-m-d', $start_week),date('Y-m-d', $end_week));
                $category[]   = date('M d', $start_week).'-'.date('M d', $end_week);
                $netamount[]  = (float)json_decode($week_revenue_data)->net_recievable->amount;
                $deductions[] = - ((float)json_decode($week_revenue_data)->deductions->amount);
            }
                $weekcate['category']  = array_reverse($category);
                $weekcate['netamount'] = array_reverse($netamount);
                $weekcate['deductions'] = array_reverse($deductions);
            //echo "<pre>";print_r($weekcate);echo "</pre>";


        return view('home')->with('usermanages',$UserData);
    }

    public function passwordChange(Request $request)
    {
        $rules['password']          = 'required|between:6,12|same:confirm_password';
        $rules['confirm_password']  = 'required|between:6,12';
        $this->validateDatas($request->all(),$rules);
        $User = User::find(\Auth::user()->id);
        $User->password             = Hash::make($request->password);
        $User->first_login          = 0;
        $User->pass_gen             = null;
        $User->save();
        $status             = 200;
        $results['result']  = true;
        Flash::success('Password changed successfully.');
        return \Response::json($results,$status);
    }
}
