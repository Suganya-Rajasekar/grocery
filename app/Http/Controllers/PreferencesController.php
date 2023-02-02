<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect,Flash;
use App\Models\Preferences;

class PreferencesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) 
    {
        $search      = ($request->search) ? $request->search : '';
        $pageCount   = 10;
        $page        = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
        $preferences = Preferences::where(function($query) use($search){
            if($search) {
                $query->where('name',$search);
            }
        })->paginate($pageCount);
        return view('home_events.preferences.index',compact('preferences','page'));
    }

    public function create() 
    {
        return view('home_events/preferences/form');
        
    }

    public function edit(Request $request) 
    {
        $id         = \Request::segment(4);
        $preference = Preferences::find($id);
        return view('home_events.preferences.form',compact('preference'));
    }

    public function store(Request $request) 
    {
        $rules['name'] = 'required';
        $response = $this->validateDatas($request->all(),$rules,[],[],'web');
        if($response) {
            Flash::error($response['message']);
            return Redirect::back();
        } 
        if(is_null($request->id)) {
            $data = ['name' => $request->name];
            $preference = Preferences::create($data);
            if($preference) {
                Flash::success('Preference created successfully.');
                return redirect(getRoleName().'/home_event/preferences/'.$preference->id.'/edit');
            } else {
                Flash::error('something went wrong.');
                return Redirect::back();
            }  
        } else {
            $preference = Preferences::find($request->id);
            $preference->name   = $request->name;
            $preference->status = $request->status;
            $process = $preference->save();
            $save  = $preference->save();
            if($save) {
                Flash::success('Preference edited successfully.');
            } else {
                Flash::error('something went wrong.');
            }
            return Redirect::back();
        }
    }
}
