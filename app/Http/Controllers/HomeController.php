<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Flash;
use App\Models\Homecontent;
use DB,Redirect;
use App\Models\Contact;
use App\Models\Menu;
use App\Models\User as usermodel; 


class HomeController extends Controller
{
     protected $redirectTo = RouteServiceProvider::HOME;
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
    public function index()
    {
        if (\Auth::check() && \Auth::User()->role == 1) {
            $this->redirectTo = route('admin.dashboard');
        } else if (\Auth::check() && \Auth::User()->role == 3) {
            $this->redirectTo = route('vendor.dashboard');

        } else if (\Auth::check() && \Auth::User()->role == 4) {
            $this->redirectTo = route('rider.dashboard');
        } else {
            if (\Session::has('user_login')) {
                if(\Session::get('user_login')['status'] == true) {
                    \Session::put('user_login',[
                        'status' => false
                    ]);
                    $this->redirectTo = route('checkout.index');
                }
            } else {
                //$this->redirectTo = url('user/dashboard/profile');
                $this->redirectTo =  redirect()->intended()->getTargetUrl();
            }
        }
        return redirect($this->redirectTo);
    }

    public  function  contactSave( Request $request) {
        // $this->beforeFilter('csrf', array('on'=>'post'));
        $rules = array(
                'name'      =>'required',
                'subject'   =>'required',
                'phone_number'=>'required',
                'message'   =>'required',
                'sender'    =>'required|email'          
        );
        $t=date("H:i:s");
        $this->validateDatas($request->all(), $rules);
       
            $values = array('name'=>$request->input('name'),'email'=>$request->input('sender'),'mobile'=>$request->input('phone_number'),'subject'=>$request->input('subject'),'mesage'=>$request->input('message'),'created_at'=>$t);
            // \DB::table('abserve_contactus')->insert($values);
             Contact::insert($values);
            $user = usermodel::find(1);
            $data = array(
                'name'=>$request->input('name'),
                'sender'=>$request->input('sender'),
                'phone_number'=>$request->phone_number,
                'subject'=>$request->input('subject'),
                'notes'=>$request->input('message'),
                'userData'=>$user,
                );
            $to   = 'support@knosh.in';;
            $from = env('MAIL_FROM_ADDRESS');;
            $subject     = $request->input('subject');
            // echo view('contact',$data);exit;
            \Mail::send('contact', $data, function($message) use ($from,$to,$subject,$data) {
             $message->to($to)->subject($subject);
             $message->from($from);
            });
            Flash::success('Thank you for your feedback.We will revert to you within 24 hours.');
            return Redirect::to('pages/'.$request->input('redirect'));   
                
          
    }

    public function passwordChange(Request $request)
    {
        $rules['password']          = 'required|between:6,12|same:confirm_password';
        $rules['confirm_password']  = 'required|between:6,12';
        $this->validateDatas($request->all(),$rules);
        $User = User::find(\Auth::user()->id);
        $User->password             = Hash::make($request->password);
        $User->first_login          = 0;
        $User->pass_gen             = null;
        $User->save();
        $status = 200;
        $results['result'] = true;
        Flash::success('Password changed successfully.');
        return \Response::json($results,$status);
    }

    public function home_content()
    {
        $content = Homecontent::find(1);
        return view('homecontent',compact('content'));
    }

    public function homecontent_store(Request $request)
    {
        // echo "<pre>";print_r($request->all());exit;
        $rules['title']     = ['required'];
        $rules['subtitle']  = ['required'];
        if( $request->hasFile('banimg')) {
            $rules['banimg']    = ['required', 'mimes:png,jpeg,jpg', 'max:5000'];
        }
        $rules['section1']  = 'required';
        $rules['section2']  = 'required';
        $rules['section3']  = 'required';
        $response = $this->validateDatas($request->all(),$rules,[],[],'web');
        if (!empty($response)) {
            Flash::error($response['message']);
            $request->flash();
            return \Redirect::back()->withErrors($response['validator'])->withInput();
        }

        $input['title']    = $request->title;
        $input['subtitle'] = $request->subtitle;

        if( $request->hasFile('banimg')) {
            $filenameWithExt    = $request->file('banimg')->getClientOriginalName();
            $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension          = $request->file('banimg')->getClientOriginalExtension();
            $fileNameToStore    = /*$filename*/'banner'.'_'.time().'.'.$extension;

            Storage::delete($customer->avatarpath ?? null);
            $avatar_path        = $request->file('banimg')->storeAs('public/homecontent', $fileNameToStore);
            $input['banimg']      = $fileNameToStore;
        } else {
            $input['banimg']      = $request->hidden_img;
        }

        Homecontent::where('id', 1)->update(['title'=>$input['title'], 'subtitle'=>$input['subtitle'], 'banimg'=>$input['banimg'],'section1'=>$request->section1,'section2'=>$request->section2,'section3'=>$request->section3]);
        $data['m_id']   = [61,62,65];
        foreach($data['m_id'] as $key => $id) {
        if($id == 61)
            $m_name = $request->section1;
        else if($id == 62) 
            $m_name = $request->section2;
        else if($id == 65)
            $m_name = $request->section3;
        Menu::where('id',$id)->update(['menu_name' => $m_name]);
       }
    Flash::success('Home content saved successfully.');
    return redirect(url(getRoleName().'/homecontent/'));
    }
}
