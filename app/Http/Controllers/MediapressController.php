<?php

namespace App\Http\Controllers;
use App\Exports\CuisinesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Flash;
use File;
use App\Models\Mediapress;
use Illuminate\Support\Facades\Validator;

class MediapressController extends Controller
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

    public function index(Request $request)
    {
       
       $pageCount  = 10;
       $page       = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
       $blogs      = Mediapress::paginate(10);
       return view('mediapress.index',compact('blogs','page'));
    }

    public function create()
    {
        return view('mediapress.form');
    }

    public function edit(Request $request, $id)
    {
        $blogs=Mediapress::find($id);
        if(!$blogs){
            $blogs=Mediapress::find($id);
        }
        return view('mediapress.form',compact('blogs'));
    }

    public function update(Request $request){
        $rules['c_name'] = "required";
        $rules['c_status'] = "required";
        $rules['type']   = "required|in:external_link,description";
        ($request->type == 'external_link') ? $rules['media_link'] = "required" : $rules['description'] = "required";
        $validator = $this->validateDatas(request()->all(),$rules,[],[],'web');
        if($validator){
            Flash::error($validator['message']);
            return \Redirect::back()->withErrors($validator['validator']);
        } else{
            if($request->c_id > 0) {
                $cuisines   = Mediapress::find($request->c_id);
            } else {
                $cuisines   = new Mediapress;
            }
            $cuisines->name         = $request->c_name;
            $cuisines->status       = $request->c_status;
            $cuisines->media_type    = $request->type;
            if($request->type == 'external_link'){
                $cuisines->description    = $request->media_link;
            } else {  
                $cuisines->description      = (isset($request->description) && $request->description!='') ? $request->description : '';
            }
            $cuisines->type = "media";
            $cuisines->tags         = (isset($request->tags)) ? implode(',',$request->tags) : '';
            $cuisines->category         = (isset($request->category)) ? implode(',',$request->category) : '';
            if( $request->hasFile('image')) {
                $filenameWithExt    = $request->file('image')->getClientOriginalName();
                $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension          = $request->file('image')->getClientOriginalExtension();
                $fileNameToStore    = 'Media_'.time().'.'.$extension;
                $avatar_path        = $request->file('image')->storeAs('public/blog', $fileNameToStore);
                $cuisines->image    = $fileNameToStore;
            }
            $cuisines->save();

            Flash::success('Mediapress saved successfully.');
            return redirect(getRoleName().'/mediapress');
        }
    }

    public function destroy($id)
    {
        $result =  Mediapress::find($id);
        if($result){
            $result = $result->delete();
            if ($result) {
                Flash::success('Mediapress is deleted.');
            }
        }else{
            Flash::success('Please Refresh Your Page...');
        }
        return redirect(getRoleName().'/mediapress');

    }
   

    
}
