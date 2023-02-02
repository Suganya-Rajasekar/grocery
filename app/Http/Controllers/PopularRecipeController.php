<?php

namespace App\Http\Controllers;
use App\Exports\CuisinesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Flash;
use App\Models\Cuisines;
use App\Models\PopularRecipe;
use File;


class PopularRecipeController extends Controller
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
       
       //echo "<pre>";print_r($popular);exit;
       $pageCount  = 10;
       $page       = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
       $popular=PopularRecipe::paginate(10);
       return view('popularrecipe.index',compact('popular','page'));
    }

    public function create()
    {
       $popular=[];
        return view('popularrecipe.form',compact('popular'));
    }

    public function edit(Request $request, $id)
    {
        $popular=PopularRecipe::find($id);
        if(!$popular){
            $popular=PopularRecipe::find($id);
        }
        return view('popularrecipe.form',compact('popular'));
    }

    public function update(Request $request){
        if($request->c_id > 0) {
            $cuisines   = PopularRecipe::find($request->c_id);
        } else {
            $cuisines   = new PopularRecipe;
        }
        $cuisines->name         = $request->c_name;
        $cuisines->status       = $request->c_status;
        $cuisines->description  = (isset($request->description) && $request->description!='') ? $request->description : '';
        $cuisines->type    = (isset($request->type) && $request->type!='') ? $request->type : '';
        //$cuisines->video_url    = (isset($request->video_url) && $request->video_url!='') ? $request->video_url : '';
       /* $slug   = \Str::slug($request->c_name,'_');
        if ($slug == '') {
            $slug   = str_replace(' ', '_', $request->c_name);
        }
        $cuisines->slug = $slug;*/
        if( $request->hasFile('image')) {
            $filenameWithExt    = $request->file('image')->getClientOriginalName();
            $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension          = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore    = 'popular_recipe_'.time().'.'.$extension;

            //Storage::delete($cuisines->image ?? null);
            $avatar_path        = $request->file('image')->storeAs('public/popular', $fileNameToStore);
            $cuisines->image    = $fileNameToStore;
        }

        if($request->hasFile('file')){
           /* $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $path = public_path().'/uploads/';
            echo $path;exit;
            $file->move($path, $filename);
            $cuisines->video    = $filename;*/
            $filenameWithExt= $request->file('file')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore =  'popular_recipe_'.time().'.'.$extension;
            $path = $request->file('file')->storeAs('public/popular/',$fileNameToStore);
            $cuisines->video    = $fileNameToStore;
        }

        $cuisines->save();

        Flash::success('Popular recipe saved successfully.');
        return redirect(getRoleName().'/popular_recipe');
    }

    public function destroy($id)
    {
        $result =  PopularRecipe::find($id);
        if($result){
            $result = $result->delete();
            if ($result) {
                Flash::success('Popular recipe is deleted.');
            }
        }else{
            Flash::success('Please Refresh Your Page...');
        }
        return redirect(getRoleName().'/popular_recipe');

    }
    
}
