<?php

namespace App\Listeners;

use App\Events\PushNotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\models\Menuitems;
use App\models\Restaurants;
use App\models\User;
use App\models\Addon;

class PushNotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $model,$url;

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PushNotificationEvent  $event
     * @return void
     */
    public function handle(PushNotificationEvent $event)
    {   
        $mArr = (class_basename($event->model) == 'Addon') ? ['id','user_id'] : ['id','vendor_id'];
        $model = (class_basename($event->model) == 'Addon') ? new Addon : ((class_basename($event->model) == 'Restaurants') ? new Restaurants : new Menuitems);
        
        $v_id = $model::find($event->model->id,$mArr);
        if(class_basename($model) == 'Restaurants' || class_basename($model) == 'Menuitems') {
            $user = User::find($v_id->vendor_id,['id','role']); 
        } else {
            $user = User::find($v_id->user_id,['id','role']);  
        }
        // dd($user);
        if($user->role == 3 || $user->role == 1){
            if(class_basename($event->model) == 'Addon'){
                $type =  'addonCreate';
                $url = 'admin/chef/'.$user->id.'/addon/edit/'.$event->model->id;
                $title = $note= 'addonCreate';
            }else if(class_basename($event->model) == 'Menuitems'){
                $type  = 'menuCreate';
                $url = 'admin/chef/'.$user->id.'/menu_item/edit/'.$event->model->id;
                $title = $note = 'menuCreate';
            } else if(class_basename($event->model) == 'Restaurants'){
                $type  = 'businessCreate';
                $url = 'admin/chef/'.$user->id.'/menu_item/edit/'.$event->model->id;
                $title = $note = 'businessCreate';
            }
           if(class_basename($event->model) == 'Addon' || class_basename($event->model) == 'Menuitems' || class_basename($event->model) == 'Restaurants'){
                getNotification($user->id,1,$type,$url,$title,$note);
            }
        }
    }
}
