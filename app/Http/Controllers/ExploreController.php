<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash,Auth;
use App\Models\Explore;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Commondatas;
use Illuminate\Support\Facades\Storage;
use File;


class ExploreController extends Controller
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
    
    $explore=Explore::all();
    return view('explore.index',compact('explore'));
} 
public function create(Request $request)
{
    $explore=[];
    $exploreall=Explore::all();
    $tags   = Commondatas::where('type','tag')->where('status','active')->get();
    return view('explore.form',compact('explore','tags','exploreall'));
}
public function edit(Request $request, $id)
{
    //$id=$request->id;
    if($id =='1' || $id =='2' || $id =='3'){
      return redirect(getRoleName().'/common/explore');  
    }
    $explore=Explore::find($id);
    $exploreall=Explore::all();
    $tags   = Commondatas::where('type','tag')->where('status','active')->get();
    return view('explore.form',compact('explore','tags','exploreall'));
}
public function update(Request $request){
    if($request->id>0){
        $explore=Explore::find($request->id);
    }else{
        $explore=new Explore;            
    }
    $explore->name=$request->name;
    if(isset($request->slug))
    {
        $explore->slug=$request->slug; 
    }
    if(isset($request->status))
    { 
        $explore->status=$request->status;
    }

    $slug   = \Str::slug($request->name,'_');
        if ($slug == '') {
            $slug   = str_replace(' ', '_', $request->name);
        }

        if( $request->hasFile('image')) {
            $filenameWithExt    = $request->file('image')->getClientOriginalName();
            $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension          = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore    = $slug.$request->slug.'.'.$extension;
            if($request->id > 0) {
                Storage::delete($explore->image ?? null);
            }
            $avatar_path        = $request->file('image')->storeAs('public/explore', $fileNameToStore);
            $explore->image    = $fileNameToStore;
        }
    //$explore->image ='';
    $explore->created_by = Auth::user()->id;
    $explore->updated_by =  Auth::user()->id;
    $explore->save();

    Flash::success('Explore details saved successfully.');
    return redirect(getRoleName().'/common/explore');
}

// public function show(Request $request){
//    echo "sfdgfdg";
//     }
public function destroy($id)
{
    $result =  Explore::find($id);
    if($result){
        $result = $result->delete();
        if ($result) {
            Flash::success('Explore detail is deleted.');
        }
    }else{
        Flash::success('Please Refresh Your Page...');
    }
    return redirect(getRoleName().'/common/explore');

}

}
