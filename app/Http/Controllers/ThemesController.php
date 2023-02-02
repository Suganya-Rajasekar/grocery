<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use App\Models\Themes;
use App\Models\Homeeventsection;
use Flash;
use Intervention\Image\Image;

class ThemesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->method = \Request::method();
    }

    public function index(Request $request)
    {
        $status = ($request->status) ? $request->status : '';
        $search = ($request->search) ? $request->search : '';
        $pageCount = 10;
        $page   = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
        $themes = Themes::where(function($query) use($status,$search){
            if($status) {
                $query->where('status',$status);
            } 
            if($search) {
                $query->where('name',$search);
            }
        })->paginate($pageCount);
        return view('home_events/themes/index',compact('themes','page'));
    }

    public function create()
    {
        return view('home_events/themes/form');
    }

    public function edit(Request $request) 
    {
        $theme = Themes::find($request->id);
        return view('home_events/themes/form',compact('theme'));
    }

    public function store(Request $request)
    {
        $rules['name']    = 'required';
        $rules['amount']  = 'required';
        if($request->hasFile('theme_images')) {
            $rules['theme_images']   = 'max:4';
            $rules['theme_images.*'] = 'mimes:jpeg,png,jpg|max:2048';
        }
        $response = $this->validateDatas($request->all(),$rules,[],[],'web');
        if($response) {
            Flash::error($response['message']);
            return Redirect::back();
        } 
        $id = !is_null($request->id) ? $request->id : '';
        $images = [];$images_names = '';
        if($request->file('theme_images')) {
            $dir = base_path().'/storage/app/public/themes/';
            if(!(\File::exists($dir))) {
                $dir = \File::makeDirectory($dir,0777,true);
            }
            foreach($request->theme_images as $key=>$value) {
                $filename = time().$key.'.'.$value->getClientOriginalExtension();
                $compress = \Image::make($value)->resize(400,400);
                $compress->save($dir.$filename);
                $images[] = $filename;
            }
            $images_names = implode(',', $images); 
        }
        if(empty($id)) {
           $data = ['name' => $request->name,'amount' => $request->amount,'status' => $request->status,'images' => $images_names];
           $theme  = Themes::create($data);
           Flash::success('Theme created successfully.');
           return redirect(getRoleName().'/home_event/themes/edit/'.$theme->id);

        } else {
            $theme  = Themes::find($id);
            if(isset($request->action) && $request->action == 'delete') {
                $process = $theme->delete();
                if($process) {
                    Flash::success('Theme deleted successfully.');
                }
            } else {            
               if(!empty($theme->images)) {
                 $exist_images = explode(',',$theme->images);
                 $reqcount     = count($request->theme_images);
                 $exist_count  = count($exist_images);
                 $total_count  = $exist_count + $reqcount; 
                 if($total_count <= 4) {
                    array_push($exist_images,$images_names);
                    $images_names = implode(',', $exist_images);
                 } else {
                    Flash::error('You can Only add four images for theme.');
                    return Redirect::back();
                 }
               }
               $theme->amount = $request->amount;
               $theme->name   = $request->name;
               $theme->images = $images_names;
               $theme->status = $request->status;
               $save  = $theme->save();
               if($save) {
                    Flash::success('Theme edited successfully.');
               } else {
                    Flash::error('something went wrong.');
               }
            }
            return Redirect::back();
        } 
    }
    public function destroy(Request $request) 
    {
        $theme  = Themes::find($request->id);
        $images = explode(',', $theme->images);
        foreach ($images as $key => $value) {
            if($request->image_id == $value) {
                unlink(base_path().'/storage/app/public/themes/'.$request->image_id);
                unset($images[$key]); 
            }
        }
        $imp_images = implode(',',$images);
        $theme->images = $imp_images;
        $theme->save();
        return \Response::json('success',200);
    }

    public function homeevent_section(Request $request)
    {
        $data = Homeeventsection::find(1);
        if($this->method == "GET") {
            return view('home_events.home_event_section',compact('data'));
        } elseif ($this->method == "PATCH") {
            $rules['meal_section'] = 'required';
            $rules['preference_section'] = 'required';
            $rules['theme_section'] = 'required';
            $rules['addon_section'] = 'required';
            $response = $this->validateDatas($request->all(),$rules,[],[],'web');
            if($response) {
                Flash::error($response['message']);
            } else {
                $data->meal_section_name        = $request->meal_section;
                $data->preference_section_name  = $request->preference_section;
                $data->theme_section_name       = $request->theme_section;
                $data->addon_section_name       = $request->addon_section;
                $data->save();
                Flash::success('Home event sections saved successfully.');
            }
            return \Redirect::back();
        }
    }
}
