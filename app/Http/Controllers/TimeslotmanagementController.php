<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Time;
use App\Models\Timeslotmanagement;
use App\Models\Timeslotcategory;
use App\Exports\TimeSlotManageExport;

class TimeslotmanagementController extends Controller
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
       $timeslotmanagement=Timeslotmanagement::all();
       $category = Timeslotcategory::where('status','active')->get();
        $time_check = Time::all();
            return view('timeslotmanagement.index',compact('timeslotmanagement'),compact('category' ,'time_check'));
    }
    public function create(Request $request)
    {
        $timeslotmanagement=[];
        return view('timeslotmanagement.form',compact('timeslotmanagement'));
    }
    public function edit(Request $request, $id)
    {
        $id=$request->id;
       $timeslotmanagement=Timeslotmanagement::find($id);
        return view('timeslotmanagement.form',compact('timeslotmanagement'));
    }
    public function update(Request $request){
        if($request->id>0){
            $timeslotmanagement=Timeslotmanagement::find($request->id);
        }else{
            $timeslotmanagement=new Timeslotmanagement;            
        }
        $timeslotmanagement->cat_id =$request->cat_id;
        $timeslotmanagement->start =$request->start;
        $timeslotmanagement->end =$request->end;
        $timeslotmanagement->status=$request->status;
        $timeslotmanagement->save();

        Flash::success('Management details saved successfully.');
        return redirect(getRoleName().'/common/timeslotmanagement');
    }
     public function destroy($id)
    {
        $result =  Timeslotmanagement::find($id);
        if($result){
            $result = $result->delete();
            if ($result) {
                Flash::success('Timeslotmanagement detail is deleted.');
            }
        }else{
            Flash::success('Please Refresh Your Page...');
        }
        return redirect(getRoleName().'/common/timeslotmanagement');

    }
    public function timeslotmanexport(Request $request,$slug){
        $request->all();
        $exporter = app()->makeWith(TimeSlotManageExport::class, compact('request'));  
        return $exporter->download('TimeSlotManageExport_'.date('Y-m-d').'.'.$slug);

    }
    
}
