<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Flash;
use App\Models\SubAdmin;
use App\Models\Chefs;
use App\Models\Chefsreq;
use App\Models\Cuisines;
use App\Models\UserModule;
use App\Http\Controllers\Api\AuthController; 
use Illuminate\Support\Facades\Crypt;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exports\SubAdminExport;
use App\Models\Menu;



class SubAdminController extends Controller
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
        $pageCount  = 10;
        $page   = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
        $search = $request->query('search');
        // $subadmin=SubAdmin::paginate(10);
        $subadmin = new SubAdmin;
        if ($request->query('search') != ''){ 
            $subadmin = $subadmin->Where('name', 'like', '%' .$search. '%')->orWhere('email', 'like', '%' .$search. '%')->orWhere('mobile', 'like', '%' .$search. '%');
        }
        if ($request->query('status') != '') {
            $subadmin = $subadmin->where('status',$request->query('status'));
        }
        $subadmin = $subadmin->paginate($pageCount);
        return view('subadmin.index',compact('subadmin','page'));
    }   

     public function show()
    {
       $subadmin=SubAdmin::all();
        return view('subadmin.index',compact('subadmin'));
    } 

    public function create()
    {
       $subadmin=[];
        return view('subadmin.form',compact('subadmin'));
    }

    public function edit(Request $request, $id)
    {
        $subadmin=SubAdmin::find($id);
        return view('subadmin.form',compact('subadmin'));
    }

    public function update(Request $request){
        $rules['name']    = ['required', 'string', 'max:255'];
        $rules['status']  = ['required','in:pending,approved,cancelled'];
        $rules['check']  = 'required|array';
         if ($request->c_id !='') {
                $rules['email']                 = ['required', 'email', 'max:255', 'unique:users,email,'.$request->c_id];
                $rules['phone_number']          = ['required', 'numeric', 'unique:users,mobile,'.$request->c_id];
                if(isset($request->password)){                    
                    $rules['password']          = ['required', 'min:6','same:confirm_password'];
                    $rules['confirm_password']  = ['required', 'min:6'];
                }
            } else{
                $rules['email']             = ['required', 'email', 'max:255' , 'unique:users,email'];
                $rules['phone_number']      = ['required', 'numeric', 'unique:users,mobile'];
                $rules['password']          = ['required', 'min:6','same:confirm_password'];
                $rules['confirm_password']  = ['required', 'min:6'];
            }

        if( $request->hasFile('avatar')) {
            $rules['avatar']    = ['required', 'mimes:png,jpeg,jpg', 'max:5000'];
        }
        $nicenames['check'] = 'Module';
        $response = $this->validateDatas($request->all(),$rules,[],$nicenames,'web');
        if (!empty($response)) {
            Flash::error($response['message']);
            $request->flash();
            return \Redirect::back()->withErrors($response['validator'])->withInput();
        }

        if($request->c_id>0){
            $subadmin=SubAdmin::find($request->c_id);            
        }else{
            $subadmin=new SubAdmin;            
        }
        $subadmin->name=$request->name;
        $subadmin->email=$request->email;
        $subadmin->mobile=$request->phone_number;
        $subadmin->location_id= 91;
        $subadmin->role=5;
        
        if($request->password!=''){
           $subadmin->password=Hash::make($request->password); 
        }
        
        $subadmin->status=$request->status;

          if( $request->hasFile('avatar')) {
                $filenameWithExt    = $request->file('avatar')->getClientOriginalName();
                $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension          = $request->file('avatar')->getClientOriginalExtension();
                $fileNameToStore    = $filename.'_'.time().'.'.$extension;

                Storage::delete($subadmin->avatarpath ?? null);
                $avatar_path        = $request->file('avatar')->storeAs('public/avatar', $fileNameToStore);
                $subadmin->avatar       = $fileNameToStore;
            }

        $subadmin->save();
        $access = $request->check;
        $access2=[];
        foreach ($access as $key => $value) {
            $menu=Menu::where('id',$key)->where('m_id','!=','0')->first();
            if(!empty($menu)){
            $access2[$menu->m_id] = $value;
            }
        }
            $access = $access + $access2; 

        //echo "<pre>";print_r($access);exit;
        $this->setAccess($subadmin->id,$access);

        Flash::success('Sub-Admin details saved successfully.');
        return redirect(url(getRoleName().'/subadmin'));
    }
    
    public function setAccess($id,$access)
    {
        $final_access = [];
        $acc = [];
        $checker = modules_access_names();
        foreach ($access as $key => $value) 
        {
            foreach ($checker as $k => $val) 
            {
                $acc[$val] = isset($value[$val]) ? 1 : 0; 
            }
            $rule = array( "id" => $key,"access" => $acc );
            array_push($final_access, $rule);
        }
        $access = json_encode($final_access);
        $match = ['user_id'=>$id];
        UserModule::updateOrCreate($match,['access'=>$access]);
        return true;
    }
    public function subadminexport(Request $request,$slug) 
    {
        $request->all();
        $exporter = app()->makeWith(SubAdminExport::class, compact('request'));  
        return $exporter->download('SubAdminExport_'.date('Y-m-d').'.'.$slug);
    }
}
