<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\WalletActivity;
use Mail;

class WalletActivityListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(WalletActivity $event)
    {
        $from   = env('MAIL_FROM_ADDRESS');
        $data['userData'] = $event->user;
        $data['msg']  = $event->message;
        $data['type'] = $event->type;
        // echo view('mail.wallet.wallet_mail',$data);exit;
        if (!empty($event->user) && $event->amount > 0) {
            if(!empty($event->user->email) && !is_null($event->user->email)) {
                Mail::send('mail.wallet.wallet_mail',$data,function($message) use($event,$from){
                    $message->to($event->user->email)->subject($event->type)->from($from);
                });
            }
            if(!empty($event->user->mobile_token) && !is_null($event->user->mobile_token)) {
                FCM($event->user->mobile_token,$event->type,$event->message);
            }
        }
    }
}
