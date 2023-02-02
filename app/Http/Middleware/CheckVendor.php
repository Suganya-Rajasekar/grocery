<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Auth;
class CheckVendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(\Auth::check())
        {
            if(\Auth::user()->role == 2){
             // return $next($request);
             return redirect('/user/dashboard/profile');
            }elseif(\Auth::user()->role == 1){
             return redirect('admin/dashboard');
            }else
            {
             // return redirect('vendor/dashboard');
            }
        }else
        {
             // return redirect('/vendor/login');
        }
    }
}
