<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Bookmarks;
use App\Models\Customer;
use App\Models\Chefs;
use App\Exports\BookmarkExport;
use Maatwebsite\Excel\Facades\Excel;



class BookmarkController extends Controller
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
        $resultData = Bookmarks::whereHas('getUserDetails', function($query)use($user_id){
            if($user_id != '') {
                $query->where('user_id',$user_id );
            }
        })->whereHas('getVendorDetails',function($query)use($vendor_id){
            if($vendor_id != '') {
                //$query->where('name', 'like', '%'.$vendor_id.'%');
                $query->where('vendor_id',$vendor_id );
            }
        })->paginate($pageCount);
        // echo "<pre>";print_r(json_encode($resultData));die;
        // print_r($resultData);die;
        $customerData = Customer::get();
        //print_r($customerData);die;
        $vendorData = Chefs::get();
        return view('bookmark.index',compact('resultData','page','customerData','vendorData'));
    }

    public function destroy($id)
    {
        $result =  Bookmarks::find($id);
        if($result){
            $result = $result->delete();
            if ($result) {
                Flash::success('Bookmark detail is deleted.');
            }
        }else{
            Flash::success('Please Refresh Your Page...');
        }
        return redirect(getRoleName().'/customer/bookmark');

    }
    public function bookmarkexport(Request $request,$slug)
    {
        $request->all();
        $exporter = app()->makeWith(BookmarkExport::class, compact('request'));  
        return $exporter->download('BookmarkExport'.date('Y-m-d').'.'.$slug);
    }
   }
