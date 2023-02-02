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
use App\Models\Blogs;
use App\Models\Commondatas;
use App\Models\Category;
use App\Models\BlogCategory;
use File;


class BlogsController extends Controller
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
       $blogs      = Blogs::paginate(10);
       $tags       = Commondatas::where('type','blogtag')->where('status','active')->get();
       $category   = BlogCategory::where('status','active')->get();
       return view('blogs.index',compact('blogs','page','tags','category'));
    }

    public function create()
    {
        $blogs=[];
        $tags       = Commondatas::where('type','blogtag')->where('status','active')->get();
        $category   = BlogCategory::where('status','active')->get();
        return view('blogs.form',compact('blogs','tags','category'));
    }

    public function edit(Request $request, $id)
    {
        $blogs=Blogs::find($id);
        if(!$blogs){
            $blogs=Blogs::find($id);
        }
        $tags       = Commondatas::where('type','blogtag')->where('status','active')->get();
        $category   = BlogCategory::where('status','active')->get();
        return view('blogs.form',compact('blogs','tags','category'));
    }

    public function update(Request $request){
        if($request->c_id > 0) {
            $cuisines   = Blogs::find($request->c_id);
        } else {
            $cuisines   = new Blogs;
        }
        $cuisines->name         = $request->c_name;
        $cuisines->status       = $request->c_status;
        $cuisines->description  = (isset($request->description) && $request->description!='') ? $request->description : '';
        $cuisines->tags         = (isset($request->tags)) ? implode(',',$request->tags) : '';
        $cuisines->category         = (isset($request->category)) ? implode(',',$request->category) : '';
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
            $fileNameToStore    = 'Blog_'.time().'.'.$extension;

            //Storage::delete($cuisines->image ?? null);
            $avatar_path        = $request->file('image')->storeAs('public/blog', $fileNameToStore);
            $cuisines->image    = $fileNameToStore;
        }


        $cuisines->save();

        Flash::success('Blog saved successfully.');
        return redirect(getRoleName().'/blogs');
    }

    public function destroy($id)
    {
        $result =  Blogs::find($id);
        if($result){
            $result = $result->delete();
            if ($result) {
                Flash::success('Blog is deleted.');
            }
        }else{
            Flash::success('Please Refresh Your Page...');
        }
        return redirect(getRoleName().'/blogs');

    }
    
}
