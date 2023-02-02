<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LogActivitiyEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $model,$primarykey,$original,$changes,$url,$ip;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($model,$primarykey,$record_id,$record,$original,$changes,$url,$ip)
    {
        $this->model      = $model;
        $this->primarykey = $primarykey;
        $this->record_id  = $record_id;
        $this->record     = $record;
        $this->original   = $original;
        $this->changes    = $changes;
        $this->url        = $url;
        $this->ip         = $ip;
        //echo "<pre>";dd($changes);exit;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
