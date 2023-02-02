<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Flash;
use App\Models\UserModule;
use App\Models\Menu;

class CheckAdmin
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
        if (\Auth::check()) {
            if (\Auth::user()->role == 1 || \Auth::user()->role == 5) {
                    if (CheckAccess() != 1) {
                        $accessData = UserModule::select('access')->where('user_id',\Auth::user()->id)->pluck('access')->first();
                        $accessData = !empty($accessData) ? json_decode($accessData): [];
                        if (!empty($accessData)) {
                            $menu = Menu::find($accessData[0]->id);
                            if (!empty($menu)) {
                                $menu   = !empty($menu) ? json_decode($menu): [];
                                $message    = "You do not have access";
                                $url        = '/admin/'.$menu->route;
                            } else {
                                $message    = "You do not have access";
                                $url        = '';
                            }
                        } else {
                            $message    = "You do not have any access";
                            $url        = '';
                        }
                        Flash::error($message);
                        return \Redirect::to($url);
                    }
                    return $next($request);
            } elseif(\Auth::user()->role == 2) {
                return redirect('/user/dashboard/profile');
            } else {
                return redirect('/vendor/dashboard');
            }
        } else {
            return redirect('/login');
        }
    }
}
