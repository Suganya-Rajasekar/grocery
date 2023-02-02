<?php

namespace App\Listeners;

use App\Events\LogActivitiyEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Models\Location;
use App\Models\Restaurants;
use App\Models\LogActivity;

class LogActivitiyListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $model,$primarykey,$record,$original,$changes,$url,$ip;

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LogActivitiyEvent  $event
     * @return void
     */
    public function handle(LogActivitiyEvent $event)
    {
        $logactivity['user_id']      = $event->primarykey;
        $logactivity['record_id']    = $event->record_id;
        $logactivity['record']       = $event->record;
        $logactivity['module']       = $event->model;
        $logactivity['url']          = $event->url;
        $logactivity['ip']           = $event->ip;
        $logactivity['before_change']       = $event->original;
        $logactivity['after_change']       = $event->changes;
        /*if(\Auth::user()->role ?? 1 == 3){
            if($event->model == 'Addon'){
                $type =  'addonUpdate';
                //$url = 'admin/chef/'.\Auth::user()->id.'/addon/edit/'.$event->record_id;
                $url = $event->url;
                $title = $note= 'addonEdit';
            }else if($event->model == 'Menuitems'){
                $type  = 'menuUpdate';
                //$url = 'admin/chef/'.\Auth::user()->id.'/menu_item/edit/'.$event->record_id;
                $url = $event->url;
                $title = $note = 'menuEdit';
            } else if($event->model == 'Restaurants'){
                $type  = 'businessUpdate';
                //$url = 'admin/chef/'.\Auth::user()->id.'/menu_item/edit/'.$event->record_id;
                $url = $event->url;
                $title = $note = 'businessEdit';
            }
           if($event->model == 'Addon' || $event->model == 'Menuitems' || $event->model == 'Restaurants'){
                getNotification(\Auth::user()->id,1,$type,$url,$title,$note);
            }
        }*/
        $test = LogActivity::create($logactivity);
        $data['message'] = 'Log updated';
        $data['status'] = true;
        return \Response::json($data,200);
    }
}
