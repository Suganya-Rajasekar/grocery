<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /* Customize validation */
        // \DB::enableQueryLog();
        \Validator::extend('exist_check', function($attribute, $value, $parameters) {
            if (count($parameters) > 0) {
                $checkcnt = 1;
                foreach ($parameters as $key => $value) {
                    if($key == 0){
                        $query = \DB::table($value);
                    } else {
                        $cLoop = explode('-', $value);
                        foreach ($cLoop as $ckey => $cvalue) {
                            $aCondition = explode(':', $cvalue);
                            $cond   = $aCondition[0];
                            if ($cond == 'FIND_IN_SET') {
                                if(!is_array($aCondition[1])){ $aCondition[1] = explode('~', $aCondition[1]); }
                                $query  = $query->whereRaw($aCondition[2].' REGEXP("('.implode('|',$aCondition[1]).')")');
                                // $query  = $query->whereRaw('FIND_IN_SET(?,'.$aCondition[2].')', explode('~', $aCondition[1]));
                            } elseif ($cond == 'whereIn') {
                                $condValues = explode('~', $aCondition[2]);
                                $checkcnt = count($condValues);
                                $query  = $query->$cond($aCondition[1],$condValues);
                            } else {
                                $query  = $query->$cond($aCondition[1],$aCondition[2],$aCondition[3]);
                            }
                        }
                        // print_r($query->toSql());
                    }
                }
                // echo "<pre>";print_r(\DB::getQueryLog($query));exit();
                $totCnt = $query->count();
                $res = $totCnt >= $checkcnt ? true : false;
                return $res;
            }
            return false;
        }, 'The selected :attribute is not exists');


        \Validator::extend('condition_check', function($attribute, $value, $parameters) {
            if (count($parameters) > 0) {
                $checkcnt = 1;
                foreach ($parameters as $key => $value) {
                    if($key == 0){
                        $query = \DB::table($value);
                    } else {
                        $cLoop = explode('-', $value);
                        foreach ($cLoop as $ckey => $cvalue) {
                            $aCondition = explode(':', $cvalue);
                            $cond   = $aCondition[0];
                            if ($cond == 'FIND_IN_SET') {
                                if(!is_array($aCondition[1])){ $aCondition[1] = explode('~', $aCondition[1]); }
                                $query  = $query->whereRaw($aCondition[2].' REGEXP("('.implode('|',$aCondition[1]).')")');
                                // $query  = $query->whereRaw('FIND_IN_SET(?,'.$aCondition[2].')', explode('~', $aCondition[1]));
                            } elseif ($cond == 'whereIn') {
                                $condValues = explode('~', $aCondition[2]);
                                $checkcnt = count($condValues);
                                $query  = $query->$cond($aCondition[1],$condValues);
                            } else {
                                $query  = $query->$cond($aCondition[1],$aCondition[2],$aCondition[3]);
                            }
                        }
                    }
                }
                $totCnt = $query->count();
                $res = $totCnt >= $checkcnt ? true : false;
                return $res;
            }
            return false;
        }, 'The selected :attribute is not exists');

        Paginator::useBootstrap(); // for Pagination design
    }



}
