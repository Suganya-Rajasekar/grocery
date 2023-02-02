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
class Bookmarks extends Model
{
    public $table       = 'bookmarks';
    protected $fillable = ['user_id', 'vendor_id'];
    public $sortable    = ['user_id', 'vendor_id'];

    public function scopeCommonselect($query)
    {
        $query->addSelect('id','user_id','vendor_id');
    }
  
    public function getUserDetails()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function getVendorDetails()
    {
        return $this->hasOne('App\Models\Chefs','id','vendor_id')->select('name','id');
    }

    public function getVendorFoodDetails()
    {
        return  $this->hasMany('App\Models\Menuitems','vendor_id','vendor_id');
    }
}
