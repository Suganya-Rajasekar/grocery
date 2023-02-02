<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\RestaurantDefault' => [
            'App\Listeners\RestaurantDefaultListener',
        ],
        'App\Events\AdminNotification' => [
            'App\Listeners\AdminNotificationListener',
        ],
        'App\Events\LogActivitiyEvent' => [
            'App\Listeners\LogActivitiyListener',
        ],
        'App\Events\OtpUserEvent' => [
            'App\Listeners\OtpUserListener',
        ],
        'App\Events\PushNotificationEvent' => [
            'App\Listeners\PushNotificationListener',
        ],
        'App\Events\WalletActivity' => [
            'App\Listeners\WalletActivityListener',
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
