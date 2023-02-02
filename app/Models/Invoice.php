<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Invoice
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Invoice extends Model
{

    public $table = 'invoice';

    public static function findOrCreate($id)
    {
        $obj = static::where('payout',$id)->first();
        return $obj ?: new static;
    }

    public function newQuery($excludeDeleted = true) {
        if(\Auth::check() &&  \Auth::user()->role==3){
            return parent::newQuery($excludeDeleted)
            ->where('chef', \Auth::user()->id);

        }else{
            return parent::newQuery($excludeDeleted)
            ->where('id', '>',0);
        }
    }
}
