<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Offer;
use App\Models\Chefs;
use App\Models\Restaurants;
use App\Models\Offtimelog;

class OfftimelogController extends Controller
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
        $chefs = Chefs::select('id','name')->approved()->get();
        if (\Auth::user()->role == 1 || \Auth::user()->role == 5) {  
            $chef_id    = $request->query('chef_id') ? $request->query('chef_id') : '';
            $offtimelog=Offtimelog::where(function($query) use($chef_id) {
                if ($chef_id != '') {
                    $query->where('vendor_id',$chef_id);
                }
            })->paginate(10);
            return view('offtimeLog.index',compact('offtimelog','page','chefs'));
        } elseif(\Auth::user()->role == 3) {
            $offtimelog=Offtimelog::where('vendor_id',\Auth::user()->id)->paginate(10);
            return view('offtimeLog.index_vendor',compact('offtimelog','page'));
        }  
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
        $offer->min_order_value = $request->min_order_value;
        $offer->max_discount = $request->max_discount;
        $offer->user_limit = $request->user_limit;
        $offer->loc_status = $request->loc_status;
        $offer->res_status = $request->res_status;
        $offer->save();

        Flash::success('Offer details saved successfully.');
        return redirect(getRoleName().'/offer');
    }
    public function destroy($id)
    {
        $Offtimelog = Offtimelog::find($id);
        $restaurant_offtime = Restaurants::where('vendor_id',$Offtimelog->vendor_id)->first();
        if($restaurant_offtime){
            $restaurant_offtime->off_from = null;
            $restaurant_offtime->off_to   = null;
            $restaurant_offtime->save();
        }
        $Offtimelog->delete();
        Flash::success('Offtime deleted successfully.');
        return \Redirect::back();
    }
}
