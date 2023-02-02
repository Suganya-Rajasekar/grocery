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
use App\Models\WhatsTrending;
use File;


class WhatsTrendingController extends Controller
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
       $trending=WhatsTrending::paginate(10);
       return view('whatstrending.index',compact('trending','page'));
    }

    public function create()
    {
       $trending=[];
        return view('whatstrending.form',compact('trending'));
    }

    public function edit(Request $request, $id)
    {
        $trending=WhatsTrending::find($id);
        if(!$trending){
            $trending=WhatsTrending::find($id);
        }
        return view('whatstrending.form',compact('trending'));
    }

    public function update(Request $request){
        //echo "<pre>";print_r($request->all());exit;
        if($request->c_id > 0) {
            $cuisines   = WhatsTrending::find($request->c_id);
        } else {
            $cuisines   = new WhatsTrending;
        }
        $cuisines->name     = $request->c_name;
        $cuisines->status   = $request->c_status;
        $cuisines->type     = (isset($request->type) && $request->type!='') ? $request->type : '';
        //$cuisines->video_url    = (isset($request->video_url) && $request->video_url!='') ? $request->video_url : '';
       /* $slug   = \Str::slug($request->c_name,'_');
        if ($slug == '') {
            $slug   = str_replace(' ', '_', $request->c_name);
        }
        $cuisines->slug = $slug;*/
        if($request->type == 'url'){
        $cuisines->video       = (isset($request->video_url) && $request->video_url!='') ? $request->video_url : '';
        }

         if( $request->hasFile('image')) {
            $filenameWithExt    = $request->file('image')->getClientOriginalName();
            $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension          = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore    = 'Wtrending_'.time().'.'.$extension;
            //Storage::delete($cuisines->image ?? null);
            $avatar_path        = $request->file('image')->storeAs('public/trending', $fileNameToStore);
            $cuisines->image    = $fileNameToStore;
        }
 
        if($request->hasFile('file')){
           /* $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $path = public_path().'/uploads/';
            echo $path;exit;
            $file->move($path, $filename);
            $cuisines->video    = $filename;*/
            $filenameWithExt    = $request->file('file')->getClientOriginalName();
            $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension          = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore    = 'Trending_'.time().'.'.$extension;
            $path               = $request->file('file')->storeAs('public/trending/',$fileNameToStore);
            $cuisines->video    = $fileNameToStore;
        }
      
        $cuisines->save();

        Flash::success('Whats Trending saved successfully.');
        return redirect(getRoleName().'/whats_trending');
    }

    public function destroy($id)
    {
        $result =  WhatsTrending::find($id);
        if($result){
            $result = $result->delete();
            if ($result) {
                Flash::success('Whats Trending is deleted.');
            }
        }else{
            Flash::success('Please Refresh Your Page...');
        }
        return redirect(getRoleName().'/whats_trending');

    }
    
}
