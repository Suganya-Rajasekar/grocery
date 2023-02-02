<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUser
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
        if(\Auth::check()) {
            if (\Auth::user()->role == 2) {
                // return $next($request);
                return redirect('/user/dashboard/profile');
            } elseif(\Auth::user()->role == 1) {
                return redirect('/admin/dashboard');
            } else {
                return redirect('/vendor/dashboard');
            }
        } else {
            if(\Request::is('book-now*')) {
                $segment = request()->segment(2);
                return redirect('/signin?redirect=book-now/'.$segment);
            }
            return redirect('/signin');
        }
    }
}
