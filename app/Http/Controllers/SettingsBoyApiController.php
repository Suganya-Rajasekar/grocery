<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Offer;
use App\Models\Chefs;
use App\Models\SettingsBoyApi;
use App\Models\SiteSetting;

class SettingsBoyApiController extends Controller
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
        //$pageCount  = 10;
        //$page   = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
        //$settingboyapi=SettingsBoyApi::paginate(10);
        //return view('settingboyapi.index',compact('settingboyapi','page'));
        $settingboyapi=SettingsBoyApi::find(1);
        return view('settingboyapi.form',compact('settingboyapi'));
    
    } 
    public function create(Request $request)
    {
        $settingboyapi=[];
        $chefs = Chefs::select('id','name','amount')->approved()->get();
        return view('settingboyapi.form',compact('settingboyapi','chefs'));
    }
    public function edit(Request $request, $id)
    {
       $settingboyapi=SettingsBoyApi::find($id);
       $chefs = Chefs::select('id','name','amount')->approved()->get();
       return view('settingboyapi.form',compact('settingboyapi','chefs'));
    }
    public function update(Request $request){
         /*echo "<pre>";
         print_r($request->all());exit();*/
         
         //$data['amount']=$request->input('amount');
        if($request->id>0){
            $settingboyapi=SettingsBoyApi::find($request->id);
        }else{
            $settingboyapi=new SettingsBoyApi;            
        }
         $settingboyapi->amount = $request->input('amount');  
        $settingboyapi->upto4 = isset($request->upto4) ? implode(',',$request->upto4) : '';
        $settingboyapi->more4 = isset($request->more4) ? implode(',',$request->more4) : '';
      
        $settingboyapi->amount = $request->amount;
        $settingboyapi->save();
        $site_setting = SiteSetting::find(1);
        $site_setting->nearby = $request->input('limit');
        $site_setting->save();

        Flash::success('Setting Boy Api details saved successfully.');
        return redirect(getRoleName().'/settings/settingsboyapi');
    }
    
}
