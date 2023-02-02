<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;

/**
 * Class category
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Payout extends Model
{

    public $table = 'payout';
    public function newQuery($excludeDeleted = true) {
        if(\Auth::check() &&  \Auth::user()->role==3){
            return parent::newQuery($excludeDeleted)
            ->where('v_id', \Auth::user()->id)->where('status','processed');

        }else{
            return parent::newQuery($excludeDeleted)
            ->where('id', '>',0);
        }
    }
    function scopeRequest($query){
        $query->whereNotIn('status',['rejected','transferred']);
    }
    public function getVendorDetails()
    {
        return $this->hasOne('App\Models\Chefs','id','v_id');
    } 
    public function orders()
    {
        return $this->hasMany('App\Models\Orderdetail','payout','id')->select('payout','s_id','created_at','vendor_price');
    } 
      
}
