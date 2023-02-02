<?php

namespace App\Listeners;

use App\Events\RestaurantDefault;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Models\Chefs;
use App\Models\Location;
use App\Models\Restaurants;

class RestaurantDefaultListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $user = '';
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RestaurantDefault  $event
     * @return void
     */
    public function handle(RestaurantDefault $event)
    {
        $userId  = $event->userId;

        if (User::where('id',$userId)->where('role',3)->exists()) {
            $this->user = Chefs::find($userId);
            $this->defaultRecord();
        }else{
            return false;
        }

    }

    public function defaultRecord()
    {
        $user = $this->user;
        $code = $user->user_code;
        $locationCode = explode('-',$code);
        $location = Location::where('code',$locationCode[0])->first();
        if (!empty($location)) {
            $restaurant['vendor_id']    = $user->id;
            $restaurant['name']         = $user->profile_name;
            $restaurant['reason']       = $user->reason;
            // $restaurant['description']  = '';
            $restaurant['email']        = $user->email;
            $restaurant['location']     = $location->id;
            $restaurant['user_code']    = $user->user_code;
            $restaurant['type']         = $user->type;
            $restaurant['home_event']   = $user->home_event;
            if ($user->cuisine_type != null && $user->cuisine_type != '' || $user->type == 'event') {
                $restaurant['cuisines'] = ($user->type != 'event') ? $user->cuisine_type : '';
                $check  = Restaurants::where('vendor_id',$user->id)->first();
                if(!empty($check)) {
                    $check->fill($restaurant)->save();
                } else {
                    $test = Restaurants::create($restaurant);
                }
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
