<?php

namespace App\Http\Controllers;
use App\Exports\CuisinesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Flash,Response;
use App\Models\Cuisines;
use File;


class CuisineController extends Controller
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
    public function cuisineexport(Request $request,$slug) 
    {
        $request->all();
        $exporter = app()->makeWith(CuisinesExport::class, compact('request'));  
        return $exporter->download('CuisineExport_'.date('Y-m-d').'.'.$slug);
    }

    public function index()
    {
       $cuisines=Cuisines::all();
       return view('cuisines.index',compact('cuisines'));
    }

    public function create()
    {
       $cuisines=[];
        return view('cuisines.form',compact('cuisines'));
    }

    public function edit(Request $request, $id)
    {
        $cuisines=Cuisines::find($id);
        if(!$cuisines){
            $cuisines=Cuisines::find($id);
        }
        return view('cuisines.form',compact('cuisines'));
    }

    public function update(Request $request){
        if($request->c_id > 0) {
            $cuisines   = Cuisines::find($request->c_id);
        } else {
            $cuisines   = new Cuisines;
        }
        $cuisines->name         = $request->c_name;
        $cuisines->status       = $request->c_status;
        $cuisines->description  = $request->description;
        $slug   = \Str::slug($request->c_name,'_');
        if ($slug == '') {
            $slug   = str_replace(' ', '_', $request->c_name);
        }
        $cuisines->slug = $slug;
        if( $request->hasFile('image')) {
            $filenameWithExt    = $request->file('image')->getClientOriginalName();
            $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension          = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore    = 'fcuisine_'.time().'.'.$extension;
            if($request->c_id > 0) {
                Storage::delete($cuisines->image ?? null);
            }
            $avatar_path        = $request->file('image')->storeAs('public/cuisines', $fileNameToStore);
            $cuisines->image    = $fileNameToStore;
        }
        $cuisines->save();

        $return = '?status='.$request->status.'&search='.$request->search.'&page='.$request->page;

        Flash::success('Cuisine details saved successfully.');
        return redirect(getRoleName().'/cuisines/'.$cuisines->id.'/edit'.$return);
    }

    public function destroy($id)
    {
        $result =  Cuisines::find($id);
        if($result){
            $result = $result->delete();
            if ($result) {
                Flash::success('Cuisine detail is deleted.');
            }
        }else{
            Flash::success('Please Refresh Your Page...');
        }
        return redirect(getRoleName().'/common/cuisines');

    }
    public function exploreoption(Request $request)
    {
        $result = Cuisines::find($request->id);
        if($result && $request->action != '') {
            ($request->action == 'checked') ? $result->explore = 'yes' : $result->explore = 'no';    
        }
        $result->save();
        if($result->explore == 'yes'){
            return Response::json(['success'=>'Cuisine marked as explore successfully']);
        } else {
            return Response::json(['remove' => 'Cuisine unmarked as explore successfully']);
        }
    }
    
}
