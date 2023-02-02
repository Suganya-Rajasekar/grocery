<?php
namespace App\Traits;
use Illuminate\Database\Eloquent\Model;
use App\Events\PushNotificationEvent;
trait PushNotification
{
    public static function bootPushNotification()
    {
        static::created(function (Model $model) {
				$model 		= $model;
				$url        = url()->previous();
				event(new PushNotificationEvent($model,$url));
        });
    }

}