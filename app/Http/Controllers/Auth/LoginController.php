<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Api\AuthController as apicon;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
         if (\Auth::check() && (\Auth::User()->role->id == 1 || \Auth::User()->role->id == 5)) {
            $this->redirectTo = route('admin.dashboard');
        } else if (\Auth::check() && \Auth::User()->role->id == 3) {
            $this->redirectTo = route('store.dashboard');
        } else if (\Auth::check() && \Auth::User()->role->id == 4) {
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
                $this->redirectTo = route('login');
            }
        }
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        if(!session()->has('url.intended'))
        {
            session(['url.intended' => url()->previous()]);
        }
        return view('auth.login');
    }

    public function Login( Request $request)
    {
        $apicon = new apicon;
        return $apicon->Login($request);
    }

    public function verifyOTP( Request $request)
    {
        $apicon = new apicon;
        return $apicon->verifyOTP($request);
    }

    public function logout()
    {
        \Auth::logout();
        return \Redirect::back();
    }
}
