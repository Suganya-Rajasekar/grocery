<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Addon;
use App\Exports\AddonExport;
use App\Models\Locations;
use Maatwebsite\Excel\Facades\Excel;

class AddonController extends Controller
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
        $pCount     = 10;
        $innerpage  = ($request->query('innerpage')) ? $request->query('innerpage') : 0;
        $page       = ($request->page) ? $request->page : 0;
        $v_id       = $request->v_id;
        $type       = \Request::segment(4);
        $addon      = Addon::where('user_id',$v_id)->where('type',$type)->paginate($pCount, ['*'], 'innerpage', $innerpage);
        return view('addon.index',compact('addon','v_id','type','innerpage','pCount','page'));
    }

    public function create(Request $request)
    {
        $addon  = [];
        $v_id   = $request->v_id;
        $type   = \Request::segment(4);
        return view('addon.form',compact('addon','v_id','type'));
    }

    public function edit(Request $request, $id)
    {
        $id     = $request->id;
        $addon  = Addon::find($id);
        $v_id   = $request->v_id;
        $type   = \Request::segment(4);
        return view('addon.form',compact('addon','v_id','type'));
    }

    public function update(Request $request)
    {
        if ($request->id > 0) {
            $addon = Addon::find($request->id);
        } else {
            $addon = new Addon;            
        }
        $addon->name=$request->name;
        $addon->type=$request->type;
     // $addon->content=$request->content;
        $addon->price=$request->price;
        $slug   = \Str::slug($request->name,'_');
        if ($slug == '') {
            $slug   = str_replace(' ', '_', $request->name);
        }
        $addon->slug=$slug;
        $addon->user_id=$request->v_id;
        $addon->status=$request->status;
        $addon->reason=isset($request->reason) ? $request->reason : '';
        $addon->save();

        Flash::success(ucfirst($request->type).' details saved successfully.');
        if(getRoleName()=='admin'){
            // return redirect(getRoleName().'/chef/'.$request->v_id.'/'.$request->type);
            return redirect()->back();
        }else{
            return redirect(getRoleName().'/common/'.$request->type);  
        }
    }

    public function unit_index(Request $request)
    {  
        $type=\Request::segment(2);
        $v_id=$request->v_id;
        $pageCount  = 10;
        $page   = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
        // $addon=Addon::where('type',$type)->paginate(10);
        $search    = $request->query('search') ? $request->query('search') : '';
            $status    = $request->query('status') ? $request->query('status') : '';
            $addon=Addon::where(function($query) use ($search, $status, $type) {
                $query->where('type',$type);
            if ($search != '') {
            $query->where('name', 'like', '%'.$search.'%');
            }
            if($status != '') {
            $query->where('status',$status );
            }
            })->paginate(10);// print_r(json_encode( $unit));die;
            $status   = Locations::all();


        return view('unit.index',compact('addon','v_id','type','page','status'));
    }

    public function unit_create(Request $request)
    {
        $addon=[];
        $v_id=$request->v_id;
        $type=\Request::segment(2);
        return view('unit.form',compact('addon','v_id','type'));
    }

    public function unit_edit(Request $request, $id)
    {
        $id=$request->id;
        $addon=Addon::find($id);
        $v_id=$request->v_id;
        $type=\Request::segment(2);
        return view('unit.form',compact('addon','v_id','type'));
    }

    public function unit_update(Request $request){
        if($request->id>0){
            $addon=Addon::find($request->id);
        }else{
            $addon=new Addon;            
        }
        $addon->name=$request->name;
        $addon->type=$request->type;
        // $addon->content=$request->content;
        $addon->price=$request->price;
        $slug   = \Str::slug($request->name,'_');
        if ($slug == '') {
            $slug   = str_replace(' ', '_', $request->name);
        }
        $addon->slug=$slug;
        $addon->user_id=0;


        $addon->status=$request->status;
        $addon->save();

        Flash::success(ucfirst($request->type).' details saved successfully.');
        if(getRoleName()=='admin'){

            return redirect(getRoleName().'/'.$request->type);
        }else{
            return redirect(getRoleName().'/common/'.$request->type);  
        }
    }

    public function destroy($id)
    {
        $result =  Addon::find($id);
        if($result){
            $result = $result->delete();
            if ($result) {
                Flash::success('variant detail is deleted.');
            }
        }else{
            Flash::success('Please Refresh Your Page...');
        }
        return redirect(getRoleName().'/unit');
    }
    public function addonexport(Request $request,$id,$slug)
    {
        $request->all();
        $exporter = app()->makeWith(AddonExport::class, compact('request','id'));
        return $exporter->download('AddonExport'.date('Y-m-d').'.'.$slug);
    }

}
