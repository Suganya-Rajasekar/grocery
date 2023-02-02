<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OtpUserEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $phone = '';

    public $content = '';

    public $url = '';

    public $apiKey = '';

    public $template = '';

    public $defualtTemplate = '';

    public $enable = false;

    public $status = false;

    public $message = 'Unaccomplished';

    public function __construct($details)
    {
        $this->phone   = $details['phone'];
        $this->content = $details['content'];
        $crdential     = \Config::get('twofactor');
        $this->url     = $crdential['TWO_FACTOR_URL'];
        $this->apiKey  = $crdential['TWO_FACTOR_API_KEY'];

        $this->template  = isset($details['template']) && isset($crdential['DYNAMIC_TEMPLATE'][$details['template']]) ? $crdential['DYNAMIC_TEMPLATE'][$details['template']] : $crdential['TWO_FACTOR_TEMPLATE'];

        $this->defualtTemplate = $crdential['TWO_FACTOR_TEMPLATE'];

        $this->enable  = $crdential['TWO_FACTOR_OPTION'];

        $this->proceed();

        return ['status' => $this->status,'message' => $this->message];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    private function proceed()
    {
        if ($this->enable) {
            $this->sendOtp();
        } else {
            $this->message = "Please enable in config";
        }
        return true;
    }

    private function sendOtp()
    {
        if(env('APP_ENV') == 'production') {
            $url        = $this->url;
            $apiKey     = $this->apiKey;
            $template   = $this->template;
            $phone      = $this->phone;
            $content    = $this->content;
            $sms        = [];

            try {
                if ($this->defualtTemplate == $template) {
                    if (is_array($content)) {
                        $content = implode('/',$content);
                    }
                    $req = $url.$apiKey."/SMS/".$phone."/".$content;
                    if ($template != '') {
                        $req = $req."/".$this->defualtTemplate;
                    }
                    $sms = file_get_contents($req);
                    $sms = json_decode($sms, true);
                } else {
                    $req = $url.$apiKey."/ADDON_SERVICES/SEND/TSMS";
                    // Make Post Fields Array
                    $data                 = [];
                    $data['From']         = 'KNOSHH';
                    $data['To']           = $phone;
                    $data['TemplateName'] = $template;
                    if (is_array($content)) {
                        foreach ($content as $key => $value) {
                            $data['VAR'.($key+1)] = $value;
                        }
                    } else {
                        $data['VAR1'] = $content;
                    }
                    // Make Post Fields Array End
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL             => $req,
                        CURLOPT_RETURNTRANSFER  => true,
                        CURLOPT_ENCODING        => "",
                        CURLOPT_MAXREDIRS       => 10,
                        CURLOPT_TIMEOUT         => 30000,
                        CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST   => "POST",
                        CURLOPT_POSTFIELDS      => json_encode($data),
                        CURLOPT_HTTPHEADER      => array(
                        // Set here requred headers
                            "accept: */*",
                            "accept-language: en-US,en;q=0.8",
                            "content-type: application/json",
                        ),
                    ));
                    $response   = curl_exec($curl);
                    $err        = curl_error($curl);
                    curl_close($curl);
                    $sms        = json_decode($response,true);
                }
            } catch (Exception $e) {
                $this->message = $e->getMessage();
            }

            if(isset($sms['Status']) && $sms['Status'] == 'Success') {
                $this->status  = true;
                $this->message = 'Message is send successfully.';
            }
            return true;
        }
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
