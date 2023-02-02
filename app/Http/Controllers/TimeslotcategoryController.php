<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Timeslotcategory;
use App\Exports\TimeSlotCatExport;

class TimeslotcategoryController extends Controller
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
       $timeslotcategory=Timeslotcategory::all();
        return view('timeslotcategory.index',compact('timeslotcategory'));
    } 
    public function create(Request $request)
    {
        $timeslotcategory=[];
        return view('timeslotcategory.form',compact('timeslotcategory'));
    }
    public function edit(Request $request, $id)
    {
        $id=$request->id;
       $timeslotcategory=Timeslotcategory::find($id);
        return view('timeslotcategory.form',compact('timeslotcategory'));
    }
    public function update(Request $request){
        if($request->id>0){
            $timeslotcategory=Timeslotcategory::find($request->id);
        }else{
            $timeslotcategory=new Timeslotcategory;            
        }
        $timeslotcategory->name=$request->name;
        $slug   = \Str::slug($request->name,'_');
        if ($slug == '') {
            $slug   = str_replace(' ', '_', $request->name);
        }
        $timeslotcategory->slug=$slug;  
        $timeslotcategory->status=$request->status;
        $timeslotcategory->save();

        Flash::success('Category details saved successfully.');
        return redirect(getRoleName().'/common/timeslotcategory');
    }
    public function destroy($id)
    {
        $result =  Timeslotcategory::find($id);
        if($result){
            $result = $result->delete();
            if ($result) {
                Flash::success('Timeslotcategory detail is deleted.');
            }
        }else{
            Flash::success('Please Refresh Your Page...');
        }
        return redirect(getRoleName().'/common/timeslotcategory');

    }
    public function timecateexport(Request $request,$slug){
       $request->all();
        $exporter = app()->makeWith(TimeSlotCatExport::class, compact('request'));  
        return $exporter->download('TimeSlotCategoryExport_'.date('Y-m-d').'.'.$slug);

    }
    
}
