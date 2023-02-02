<?php
namespace App\Traits;
use Illuminate\Database\Eloquent\Model;
use App\Events\LogActivitiyEvent;
trait Log
{
    public static function bootLog()
    {
        static::updating(function (Model $model) {
            //dd(class_basename($model));
            $k_name=$model->getKeyName();
            $changes = $model->getDirty();

		if (count($changes) > 0)
		{
			$user = \Auth::user();
			foreach ($changes as $key => $value) {
				$module     = class_basename($model);
				$primarykey = $user->id ?? 1;
				$original   = $model->getOriginal($key);
				$changes    = $value;
				$url        = url()->previous();
				$ip         = request()->ip();
				$record     = $key;
				$record_id  = $model->{$k_name};
				event(new LogActivitiyEvent($module,$primarykey,$record_id,$record,$original,$changes,$url,$ip));
			}
	    
		}

        });

    }
}