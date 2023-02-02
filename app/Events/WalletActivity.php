<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WalletActivity
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $type,$message,$amount,$user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user,$type,$message,$amount)
    {
        $this->type         = ($type == 'credit') ? 'Wallet amount credited': 'Wallet amount debited';
        $this->message      = $message;
        $this->amount       = $amount; 
        $this->user = $user;
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
