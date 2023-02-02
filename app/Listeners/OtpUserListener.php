<?php

namespace App\Listeners;

use App\Events\OtpUserEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OtpUserListener
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
     * @param  SmsToUserEvent  $event
     * @return void
     */
    public function handle(OtpUserEvent $event)
    {
        //
    }
}
