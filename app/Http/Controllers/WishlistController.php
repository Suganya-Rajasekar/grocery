<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Wishlist;
use App\Models\Customer;
use App\Exports\WishlistExport;

class WishlistController extends Controller
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
        $filter = $request->query('filter');
        $pageCount=10;
        $page=(($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
        $resultData = Wishlist::
        whereHas('getUserDetails', function($query)use($filter,$user_id){
            if($user_id != '') {
                $query->where('user_id',$user_id );
            }
        })->Where(function($query)use($filter){
            if (!empty($filter)) {
                $query->where('title', 'like', '%'.$filter.'%')->orWhere('description', 'like', '%'.$filter.'%');
            }
        })
            // ->orWhereHas('getVendorDetails', function($query)use($filter){
        //     if (!empty($filter)) {
        //         $query->where('name', 'like', '%'.$filter.'%');
        //     }
        // })
        ->paginate($pageCount);
        $customerData = Customer::where('role', '=' ,'2')->get();
        return view('wishlist.index',compact('resultData','page','filter','customerData'));
    }
    public function destroy($id)
    {
        $result =  Wishlist::find($id);
        if($result){
            $result = $result->delete();
            if ($result) {
                Flash::success('Wishlist detail is deleted.');
            }
        }else{
            Flash::success('Please Refresh Your Page...');
        }
        return redirect(getRoleName().'/customer/wishlist');

    }
    public function wishlistexport(Request $request,$slug){
        $request->all();
        $exporter = app()->makeWith(WishlistExport::class, compact('request'));  
        return $exporter->download('WishlistExport_'.date('Y-m-d').'.'.$slug);
    }

   }
