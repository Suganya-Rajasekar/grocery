<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authcheck
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
        $guard = ($request->from == 'mobile') ? 'api' : 'web';
        return auth($guard)->user();
        /*if(\Auth::check()) {
            return dd("logged");
        } else {
            return dd("not logged");
        }*/
    }
}
