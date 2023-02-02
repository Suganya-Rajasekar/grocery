<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Favourites;
use App\Models\Customer;
use App\Models\Chefs;
use App\Exports\FavouritesExport;

class FavouritesController extends Controller
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
    $user_id    = $request->query('user_id') ? $request->query('user_id') : '';
     $vendor_id = $request->query('vendor_id')  ? $request->query('vendor_id') : '';
    $pageCount=10;
    $page=(($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
    $resultData = Favourites::
    whereHas('getUserDetails', function($query)use($user_id){
        if($user_id != '') {
            $query->where('user_id',$user_id );
        }
    })
    ->WhereHas('getVendorDetails', function($query)use($vendor_id){
        if (!empty($vendor_id)) {
            //$query->where('name', 'like', '%'.$filter.'%');
            $query->where('vendor_id',$vendor_id );
        }
    })
    // ->orWhereHas('getMenuDetails', function($query)use($filter){
    //     if (!empty($filter)) {
    //         $query->where('name', 'like', '%'.$filter.'%');
    //     }
    // })
    ->paginate($pageCount);
    $customerData = Customer::get();
    $vendorData = Chefs::get();
    return view('favourites.index',compact('resultData','page','customerData','vendorData'));
}
public function destroy($id)
{
    $result =  Favourites::find($id);
    if($result){
        $result = $result->delete();
        if ($result) {
            Flash::success('Favourite detail is deleted.');
        }
    }else{
        Flash::success('Please Refresh Your Page...');
    }
    return redirect(getRoleName().'/customer/favourites');
}
 public function favouritesexport(Request $request,$slug){
        $request->all();
        $exporter = app()->makeWith(FavouritesExport::class, compact('request'));  
        return $exporter->download('FavouritesExport_'.date('Y-m-d').'.'.$slug);
    }
}


