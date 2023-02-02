<?php

namespace App\Listeners;

use App\Events\AdminNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AdminNotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AdminNotification  $event
     * @return void
     */
    public function handle(AdminNotification $event)
    {
        //
    }
}
