<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Offer;
use App\Models\Chefs;
use App\Exports\OfferExport;

class OfferController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->Imagepath=Offer::IMAGE_PATH;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $pageCount  = 10;
        $page   = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
        $date    = $request->query('date') ? $request->query('date') : '';
        $search = $request->query('search');
        $offer  =   new Offer;
        if ($request->query('date') != '') {
            $sDate  = explode(" - ",$request->date);
            $offer = $offer->where('start_date', '>=', $sDate[0])->
            where('end_date', '<=', $sDate[1]);
        } 
        if ($request->query('promo_type')  != '') {
            $offer = $offer->where('promo_type',$request->query('promo_type'));
        }
        if ($request->query('status') != '') {
            $offer = $offer->where('status',$request->query('status'));
        }
        if ($request->query('search') != ''){ 
            $offer = $offer->Where('name', 'like', '%' .$search. '%')->orWhere('promo_code', 'like', '%' .$search. '%')->orWhere('offer', 'like', '%' .$search. '%');
        }
        $offer = $offer->paginate($pageCount);
        return view('offer.index',compact('offer','page'));
    } 
    public function create(Request $request)
    {
        $offer=[];
        $chefs = Chefs::select('id','name')->approved()->get();
        return view('offer.form',compact('offer','chefs'));
    }
    public function edit(Request $request, $id)
    {
       $offer=Offer::find($id);
       $chefs = Chefs::select('id','name')->approved()->get();
        return view('offer.form',compact('offer','chefs'));
    }
    public function update(Request $request){
        // echo "<pre>";
        // print_r($request->all());exit();
        if($request->id>0){
            $offer=Offer::find($request->id);
        }else{
            $offer=new Offer;            
        }
        if(isset($request->image)){            
           $img_data = uploadImage($request->image,$this->Imagepath,'','');
           $offer->image=$img_data['image'];
       }        
        $offer->status=$request->status; 
        $date=explode(" - ", $request->date);
        $offer->start_date=date('Y-m-d',strtotime($date[0])); 
        $offer->end_date=date('Y-m-d',strtotime($date[1])); 
        $offer->restaurant = ($request->res_status == 'selected') ? implode(',',$request->restaurant) : '';
        $offer->location = ($request->loc_status == 'selected') ? implode(',',$request->location) : '';
        $offer->name = $request->name;
        $offer->offer = $request->offer;
        $offer->promo_code = $request->promo_code;
        $offer->promo_desc = $request->promo_desc;
        $offer->promo_type = $request->promo_type;
        $offer->usage_limit = $request->usage_limit;
        $offer->min_order_value = ($request->min_order_value!='') ? $request->min_order_value : 0;
        $offer->max_discount = $request->max_discount;
        $offer->user_limit = $request->user_limit;
        $offer->loc_status = $request->loc_status;
        $offer->res_status = $request->res_status;
        $offer->offer_visibility = $request->offer_visible;
        $offer->save();

        Flash::success('Offer details saved successfully.');
        return redirect(getRoleName().'/offer');
    }
    public function offerexport(Request $request,$slug){
        $request->all();
        $exporter = app()->makeWith(OfferExport::class, compact('request'));  
        return $exporter->download('OfferExport_'.date('Y-m-d').'.'.$slug);

    }
    
}
