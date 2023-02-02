<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class logged
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
            if (\Auth::user()->role == 1) {
                return redirect('/admin/dashboard');
            } elseif(\Auth::user()->role == 3) {
                return redirect('/vendor/dashboard');
            } elseif(\Auth::user()->role == 2) {
                return redirect('/user/dashboard/profile');
            } elseif(\Auth::user()->role == 5) {
                return redirect('vendor/dashboard');
            }
        } else {
            return $next($request);
        }
    }
}
