<?php


namespace App\Helpers;

use Request;

use App\Models\LogActivity as LogActivityModel;


class LogActivity

{


    public static function addToLog($option)

    {

    	$log = [];

    	$log['module'] = ($option['module']!='') ? $option['module']: NULL;

        $log['before_change'] = ($option['before_change']!='') ? $option['before_change']: NULL;

        $log['after_change'] = ($option['after_change']!='') ? $option['after_change']: NULL;

    	$log['url'] = Request::fullUrl();

    	$log['ip'] = Request::ip();

    	$log['user_id'] = auth()->check() ? auth()->user()->id : 1;

    	LogActivityModel::create($log);

    }


    public static function logActivityLists()

    {

    	return LogActivityModel::latest()->get();

    }


}