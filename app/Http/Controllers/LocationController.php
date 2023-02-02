<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Location;
use App\Events\LogActivitiyEvent;
use App\Exports\LocationExport;

class LocationController extends Controller
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
        $pageCount  = 10;
        $page   = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
        //$location=Location::paginate(10);
        $location=new Location;
        $search = $request->query('search');
        if ($request->query('search') != ''){ 
            $location = $location->Where('name', 'like', '%' .$search. '%')->orWhere('code', 'like', '%' .$search. '%');
        }
        if ($request->query('status') != '') {
            $location = $location->where('status',$request->query('status'));
        }
         $location = $location->paginate($pageCount);
        return view('location.index',compact('location','page'));
    }
    public function update(Request $request){
        if($request->l_id>0){
            $location=Location::find($request->l_id);
            //echo "<pre>";print_r($location);echo "</pre>";exit;
        }else{
            $location=new Location;
        }
        

        $location->name=$request->l_name;
        $location->code=$request->code;
        $location->status=$request->l_status;
        $location->latitude=$request->latitude;
        $location->longitude=$request->longitude;
        /*echo "<pre>";print_r($location->getOriginal()); print_r($location->getAttributes());exit;*/
        //event(new LogActivitiyEvent(Location::class ,$request->l_id,$location->getOriginal(),$location->getAttributes(),url()->previous(),request()->ip()));
        $location->save();

        //\LogActivity::addToLog(Location::class ,$request->l_id,$request->all());

        /*$option=array(
            'module' => 'location',
            'before_change' => 'john',
            'after_change' => $lname,
        );

        \LogActivity::addToLog($option);*/

        return redirect(getRoleName().'/location');
    }
      public function destroy($id)
    {
        $result =  Location::find($id);
        if($result){
            $result = $result->delete();
            if ($result) {
                Flash::success('Location is deleted.');
            }
        }else{
            Flash::success('Please Refresh Your Page...');
        }
        return redirect(getRoleName().'/location');

    }
    public function locationexport(Request $request,$slug) 
    {
        $request->all();
        $exporter = app()->makeWith(LocationExport::class, compact('request'));  
        return $exporter->download('LocationExport_'.date('Y-m-d').'.'.$slug);
    }

    
}
