<?php

namespace App\Http\Controllers\Front\Details;

use Illuminate\Http\Request;
use App\Models\Testimonials;
use App\Models\Category;
use App\Models\Service;
use App\Models\Addon;
use App\Models\Time;
use App\Models\Book;
use App\Models\SiteSetting;
use App\Models\subscription_plans as SubscriptionPlans;
use App\Models\User;
use App\Models\Notifyme;
use App\Models\Menuitems;
use Illuminate\Http\Response;
use Cookie;
use Flash;
use Session;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;

class DetailsController extends Controller
{
    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */

    public function chefProfile($id)
    {
        /*$chefProfile =  app()->call('App\Http\Controllers\Api\Customer\CustomerController@chefinfo',[
            'request' => request()->merge(['user_id'=>\Auth::id(), 'id'=>$id])
        ])->getData();*/
        $cookie = request()->get('cookie');
        $user   = $this->authCheck();
        $userId = $user['userId'];
        $catId  = (request()->has('section')) ? request()->get('section') : '';
        $chefProfilenew = app()->call('App\Http\Controllers\Api\Customer\CustomerController@chefinfonew',['request' => request()->merge(['user_id'=>$userId,'cookie' => $cookie, 'id'=>$id,'category_id'=>$catId])])->getData();
        if ($chefProfilenew->status == 200) {
            \Session::put(['current_chefid' => $id]);
            if($chefProfilenew->chefinfo->type != 'event' && $chefProfilenew->chefinfo->type != 'home_event') {  
                $chefCategories = app()->call('App\Http\Controllers\Api\Customer\CustomerController@chefCategories',['request' => request()->merge(['user_id'=>$userId,'cookie' => $cookie, 'id'=>$id])])->getData();
                $chefCats = clone ($chefCategories);
                $chefCats = collect($chefCats->categories);
                $catId = ($catId == 'review') ? $chefCats[0]->id : $catId;
            }
            $chefinfo = (object) $chefProfilenew->chefinfo;
            $offtime = $chefProfilenew->offtime_dates; 
            $offdays = $chefProfilenew->offtime_days; 
            $notify = $chefProfilenew->chefinfo->is_notify;

            if (request()->get('section') != '' && (!$chefCats->contains('id', $catId) || (request()->get('section') == 'review' && count($chefinfo->publishedreviews) == 0))) {
                return \Redirect::to('chef/'.$chefinfo->id);
            }
            /*$timeslot = app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@TableDatasAll',['request' => request()->merge(['requestdata'=>'TimeSlot'])])->getData();
            $timeslots  = (object) $timeslot->timeslot;*/
            if(request()->ajax() && request()->has('section') == 'dishes' ){

               $data['recordCount'] = count($chefinfo->chef_restaurant->approved_dishes);
               $data['app'] = (string) view('frontend.all_dishes',compact('chefinfo','timeslots'));
               return json_encode($data);
           
            } else if(request()->ajax() && request()->has('section') == 'review' ) {

               $data['recordCount'] = count($chefinfo->publishedreviews);
               $data['app'] = (string) view('frontend.reviews',compact('chefinfo','timeslots'));
               return json_encode($data);
            } else {
               $cart_sametime = app()->call('App\Http\Controllers\Api\V1\Emperica\CartController@cartCount')->getData();
               $cartcount = $cart_sametime;
               if($chefinfo->type == 'event' || $chefinfo->type == 'home_event') {
                    return view('frontend.details',compact('chefinfo','offtime','cartcount'));
               } else {
                    return view('frontend.details',compact('chefinfo'/*,'timeslots'*/,'chefCategories','offtime','offdays','notify','cartcount'));
               }
            }
        } else {
            return \Redirect::to('');
        }
    }

    /* send food items*/
    public function send_fooditems(Request $request)
    {
        $user   = $this->authCheck();
        $userId = $user['userId'];
        $res_id = Menuitems::find($request->id,['id','restaurant_id'])->restaurant_id;
        $food_id    = $request->id;
        $date       = $request->date;
        $time_slot  = $request->timeslot;
        $today_date = date('Y-m-d');
        $preorder   = 'yes';
        $is_addon   = 'no';
        $addon      = array();
        $unit       = 0;
        if ($today_date == $date) {
            $preorder = 'no';
        }
        $addon_data = $request->addon;
        $exp_addon  = explode(',', $addon_data);
        $exp_addon  = array_filter($exp_addon);

        if ($exp_addon) {
            $is_addon = 'yes';
            $addon    = $exp_addon;
        }
        $unit_data  = $request->unit;
        if ($unit_data != '') {
            $unit = $unit_data;
        } else {
        }
        $quantity   = $request->quantity;
        $cookie = request('cookie');
        $cart   = app()->call('App\Http\Controllers\Api\V1\Emperica\CartController@addCart',[
            'request' => request()->merge(['food_id'=>$food_id,'date'=>$date,'time_slot'=>$time_slot,'is_preorder'=>$preorder,'is_addon'=>$is_addon,'addons'=>$addon,'unit'=>$unit,'quantity'=>$quantity,'is_samedatetime' => $request->is_samedatetime])
        ]);
        $response['carts']= $cart;
         
        $userCart = uCartQuery($userId, request('cookie'));
        $chefinfos = app()->call('App\Http\Controllers\Api\V1\Emperica\CartController@cartData',[
                'userCart' => $userCart])['cartData'];
        $ucart              = clone ($userCart);
        $chefinfos['price'] = (float) $ucart->sum('price');
        $chefinfos['count']      = $userCart->count();
        $chefinfo = (!empty($chefinfos)) ? $chefinfos : (object) [];
        $call = 'addcart';
        
        $response['detailcart'] = (string) view('frontend.details-cart',compact('chefinfo','call'));
        
        $cart_sametime = app()->call('App\Http\Controllers\Api\V1\Emperica\CartController@cartCount')->getData();
        $response['cartcount'] = $cart_sametime->cart_datetimeslot;
        // dd($cart_sametime->cart_datetimeslot->is_samedatetime); 
        return $response;
    }

    public function menuinfo(Request $request)
    {
        $food_id    = $request->id;
        $menuinfos  = app()->call('App\Http\Controllers\Api\Customer\CustomerController@menuinfo',[
            'request' => request()->merge(['menu_id'=>$food_id])
        ])->getData();
        if (isset($menuinfos->menu_items)) {
            $menuinfo           = (object) $menuinfos->menu_items;
            $Response['html']   = (string)view('frontend.menumodel',compact('menuinfo'));
            return $Response;
        } else {
            Flash::success($menuinfos->message);
            return \Redirect::to('');
        }
    }

    public function timeslot(Request $request)
    {
        $menuid     = $request->id; 
        $timeslot   = app()->call('App\Http\Controllers\Api\Customer\CustomerController@menuSlotCheck',['request' => request()->merge(['menu_id'=>$request->id])])->getData();
        $timeslots  = (object) $timeslot->timeSlot;
        $html       = (string)view('frontend.timeslot',compact('timeslots','menuid'));
        return $html;
    }

    public function send_comment(Request $request)
    {
        $food_id = $request->food_id;
        $comment = $request->comment;
        $menucomnt = app()->call('App\Http\Controllers\Api\Customer\CustomerController@menuComment',[
            'request' => request()->merge(['food_id'=>$food_id,'comment'=>$comment ])
        ]);
        $comment=(object) $menucomnt;
        return $comment;  
    }

    public function chefProfileredirect($food_id)
    {
        $menuinfos =  app()->call('App\Http\Controllers\Api\Customer\CustomerController@menuinfo',[
            'request' => request()->merge(['menu_id'=>$food_id])
        ])->getData();
        $menuinfo = (object) $menuinfos->menu_items;
        \Session::put([
            'food_id' => $food_id,
            'vendor_id' => $menuinfo->vendor_id
        ]);
        $device = user_agent();
        if($device == 'pc'){
            return \Redirect::to('chef/'.$menuinfo->vendor_id);
        } else{
            $menuinfo->message = "Redirect app or playstore.";   
            return view('mobileview',compact('menuinfo','device'));            
        }
    }

    public function chefAddonredirect($food_id)
    {
        $menuin     =app()->call('App\Http\Controllers\Api\Customer\CustomerController@menuinfo',[
            'request'   => request()->merge(['menu_id'=>$food_id])
        ]);
 
        $menuinfos  =  app()->call('App\Http\Controllers\Api\Customer\CustomerController@menuinfo',[
            'request'   => request()->merge(['menu_id'=>$food_id])
        ])->getData();
        if ($menuin->status()==200 && isset($menuinfos->menu_items)) {
            $menuinfo   = (object) $menuinfos->menu_items;
            \Session::put([
                'add_food_id' => $food_id,
                'add_vendor_id' => $menuinfo->vendor_id
            ]);
            return \Redirect::to('chef/'.$menuinfo->vendor_id);
        } else {
            Flash::success($menuinfos->message);
            return \Redirect::to('');
        }
    }

    public function loadMoreData(Request $request)
    {
        $chef_id= $request->chef_id;
        $type   = $request->type;
        $cookie = request()->get('cookie');
        $user   = $this->authCheck();
        $userId = $user['userId'];

        $chefProfilenew = app()->call('App\Http\Controllers\Api\Customer\CustomerController@chefinfonew',['request' => request()->merge(['id'=>$chef_id,'menupage'=>2])])->getData();
          
        $chefinfo   = (object) $chefProfilenew->chefinfo;
        $chefdish   = $chefinfo->chef_restaurant->approved_dishes;
        if($type == 'all_dishes' && count($chefDishes) > 0) {
            $Response['html']   = (string)view('frontend.details-dish',compact('chefdish'));
        } else {
         $Response['html']      = ''; 
        }
        return $Response;
    }

    public function continueOrderredirect($chef_id)
    {
        \Session::put([
            'add_continue_redirect' => $chef_id
        ]);
        return \Redirect::to('');
    }

    public function commentlike(Request $request)
    {
        $cmt_like = app()->call('App\Http\Controllers\Api\Customer\CustomerController@menuComment');
        return $cmt_like;
    }

    public function notifyme(Request $request)
    {   
        $response = app()->call('App\Http\Controllers\Api\Customer\CustomerController@notifyme');
        return $response;
    }

    public function deliveryslotchange(Request $request) 
    {
        request()->merge(['function' => 'datetime_edit','action' => $request->action]);
        $cartdata   = app()->call('App\Http\Controllers\Api\V1\Emperica\CartController@addCart');
        $carts      = $cartdata->getData();
        $previous   = url()->previous();
        $offers     = app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@AvailableOffers');
        $res        = (string) view('frontend.checkout.showcart',compact('carts','offers','previous'));
        return $res;
    }
}