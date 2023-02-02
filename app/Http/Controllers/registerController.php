<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Input, Redirect, Response, Authorizer ;

class registerController extends AppBaseController
{
    public function index(Request $request)
    {
        return view('register.index');
    }

    public function usersetting(Request $request)
    {
        $getData = ['name','email','phone_number','id'];
        $User = User::select($getData)->find(\Auth::user()->id);
        $name = explode(' ',$User->name);
        $User['first_name'] = $name[0];
        $User['last_name'] = !empty($name[1])?$name[1]:'';
        return view('register.usersetting')->with('UserData',$User);
    }

    public function CheckSocial( Request $request)
    {
        if($request->from == 'gmail')
        {
            $rules = array( 'social_id' => 'required|unique:users,google_id' );
            $check = 'google_id';
        }else
        {
            $rules = array( 'social_id' => 'required|unique:users,fb_id' );
            $check = 'fb_id';
        }
        $validator = Validator::make($request->all(), $rules);
        if($validator->passes()){
            $name = explode(' ', $request->name);
            $rand = getRandom(8);
            $request['first_name']   = $name[0];
            $request['last_name']    = $name[1];
            $request['password'] = $request['confirm_password'] = $request['pass_gen'] = $rand;
            $result = $this->registerUser($request);
            return $result;
        } else {
            $user_detail = \DB::table('users')->where($check,$request->social_id )->first();
            $result = $this->loginUser($request);
            return $result;
        }
    }

    public function registerUser(Request $request)
    {
        if(isset($request->form_data))
            parse_str($request->form_data, $data);
        else
            $data = $request->all();
        $User = new User;
        if(isset($data['id']))
        {
            $rules['email']             = 'required|email|min:5|unique:users,email,'.$data["id"].',id,deleted_at,null';
        }else
        {
            $rules['first_name']            = 'required';
            $rules['email']                 = 'required|unique:users|min:5';
            $rules['password']              = 'required|between:6,12|same:confirm_password';
            $rules['confirm_password']      = 'required|between:6,12';
        }
        $this->validateDatas($data,$rules);
        if(isset($data['social_id']))
        {
            $User->image    = $data['imageURL'];
            $User->pass_gen = $data['pass_gen'];
            if($data['from'] == 'gmail')
            {
                $User->google_id    = $data['social_id'];
            }else
            {
                $User->fb_id        = $data['social_id'];
            }
        }
        if(isset($data['id']))
        {
            $User = User::find($data['id']);
        }else
        {
            $User->role         = 5;
            $User->status       = 1;
            $User->password     = Hash::make($data['password']);
        }
        $User->name         = $data['first_name'].' '.$data['last_name'];
        $User->email        = $data['email'];
        $User->phone_number = isset($data['phone_number']) ? $data['phone_number'] : null;
        $User->save();
        $status = 200;
        $results['result'] = true;
        $email = $data['email'];
        if(!isset($data['id']))
        {
            \Mail::send('mail.register', $data, function($message) use ($email) {
                $message->to('abservetechphp@gmail.com')->subject("Registration Success");
                $message->from('admin@subscripty.com');
            });
            return $this->loginUser($request);
        }else
        {
            return \Response::json($results,$status);
        }
    }

    public function checkEmail(Request $request)
    {
        $email  = $request->email;
        if (!isset($request->login)) {
            $User = User::where('email', $email)->first();
            if (!empty($User) && !isset($request->dashboard)) {
                return json_encode(false);
            } else {
                return json_encode(true);
            }
        } else {
            $detail = User::where('email', $email)->where('role',5)->first();
            if (!empty($detail)) {
                if (isset($request->forgot)) {
                    if ($detail->status == 0) {
                        return json_encode('Your Account is Inactive');
                    } elseif($detail->status == 2) {
                        return json_encode('Your Account is Blocked By Administrator');
                    } else {
                        return json_encode(true);
                    }
                } else {
                    return json_encode(true);
                }
            } else {
                return json_encode(false);
            }
        }
    }

    public function checkMobile(Request $request)
    {
        $mobile  = $request->mobile;
        if (!isset($request->login)) {
            $User = User::where('mobile', $mobile)->first();
            if (!empty($User) && !isset($request->dashboard)) {
                return json_encode(false);
            } else {
                return json_encode(true);
            }
        } else {
            $detail = User::where('mobile', $mobile)->where('role',5)->first();
            if (!empty($detail)) {
                if (isset($request->forgot)) {
                    if ($detail->status == 0) {
                        return json_encode('Your Account is Inactive');
                    } elseif($detail->status == 2) {
                        return json_encode('Your Account is Blocked By Administrator');
                    } else {
                        return json_encode(true);
                    }
                } else {
                    return json_encode(true);
                }
            } else {
                return json_encode(false);
            }
        }
    }

    public function loginUser(Request $request)
    {
        if(isset($request->form_data))
            parse_str($request->form_data, $data);
        else
            $data = $request->all();
        $social_login = isset($data['social_id']) ? true : false;
        $User = new User;
        if(!isset($data['social_id']))
        {
            $rules['password'] = 'required';
        }else
        {
            $data['password'] = '';
        }
        $rules['email'] = 'required';
        $this->validateDatas($data,$rules);
        $user_detail = \DB::table('users')->select('*')->where('email',$data['email'])->where('role',5)->first();
        if($user_detail!= '')
        {
            if($user_detail->status == 1)
            {
                $check = [
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'id' => $user_detail->id
                ];
                if($social_login)
                {
                    $attempt = \Auth::loginUsingId($user_detail->id);
                }else{ 
                    $attempt = \Auth::attempt($check);
                 }
                if($attempt){
                    $response["message"]    = "Logged in";
                    $response["result"]     = true;
                    $code = 200;
                }
                else
                {
                    $response["message"]    = "Your Password Is Incorrect";
                    $code = 422;
                }
            }elseif($user_detail->status == 0)
            {
                $response["message"]    = "Your Account is not yet Active. Please wait for our team to review your account.";
                $code = 422;
            }elseif($user_detail->status == 2)
            {
                $response["message"]    = "Your Account is blocked by the Administrator. Please contact the support for more details";
                $code = 422;
            }
        }else
        {
            $response["message"]    = "Your Email Is Incorrect";
            $code = 422;
        }
        return Response::json($response,$code);
    }

    public function profileImageChange(Request $request)
    {
        $data = $request->all();
        if(isset($data['image']))
        {
            $img_data = uploadImage($data['image'],'assets/front/img/profileimage','',\Auth::user()->id);
            $status = 200;
            $response['result'] = 'Upladed successfully';
            $response['path']   = URL::to('/assets/front/img/profileimage/'.$img_data['image']);
            $User = User::find(\Auth::user()->id);
            $User->image         = $img_data['image'];
            $User->save();
            return \Response::json($response,$status);
        }else
        {
            $status = 422;
            return \Response::json('Upload an Image',$status);
        }
    }

    public function passwordChange(Request $request)
    {
        $rules['password']          = 'required|between:6,12|same:confirm_password';
        $rules['confirm_password']  = 'required|between:6,12';
        $rules['old']  = 'required|between:6,12';
        $this->validateDatas($request->all(),$rules);
        $User = User::find(\Auth::user()->id);
        if(Hash::check($request->old,$User->password))
        {
            $User->password             = Hash::make($request->password);
            $User->save();
            $status = 200;
            $results['result'] = true;
            return \Response::json($results,$status);
        }else
        {
            $status = 422;
            $results['result'] = false;
            $results['message'] = 'Current Password is invalid';
            return \Response::json($results,$status);
        }
    }

    public function deleteAccount(Request $request)
    {
        $id = User::find( \Auth::user()->id );
        $id->del_request = 1;
        $id ->save();
        $id ->delete();
        $status = 200;
        $results['result'] = true;
        return \Redirect('/');
    }

    public function BecomeAChef(Request $request)
    {
        return view('auth.cheflanding');
    }

    public function chefRegister(Request $request)
    {
        $cuisine_location_list =  app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@TableDatasAll',[
            'request' => request()->merge(['requestdata'=>'Cuisines,Locations'])
        ])->getData();

        return view('auth.chefregister')->with('cuisine_location_list',$cuisine_location_list);
    }

    public function send_chefregisterUser(Request $request)
    {
        $role_id    = $request->role_id;
        $name       = $request->name;
        $email      = $request->email;
        $country    = $request->country;
        $mobile     = $request->mobile;
        $password   = $request->password;
        $cpassword  = $request->cpassword;
        $device     = $request->device;
        $locations  = $request->locations;
        $profile_name = $request->profile_name;
        // $cuisine_type   = $request->cuisine_type;
        if (isset($request->cuisine_type) && $request->cuisine_type != '' && !empty($request->cuisine_type) && $request->cuisine_type != null) {
            $request->cuisine_type    = array_filter($request->cuisine_type);
        }
        $partner_reg    = app()->call('App\Http\Controllers\Api\AuthController@register',$request->all());
        $pRegister      = (object) $partner_reg;
        return $pRegister;
    }
}
