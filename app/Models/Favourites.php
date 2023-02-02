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
class Favourites extends Model
{
    public $table = 'favourites';

    public function scopeCommonselect($query)
    {
        $query->addSelect('id','user_id','vendor_id','restaurant_id');
    }
  
    public function getUserDetails()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function getVendorDetails()
    {
        return $this->hasOne('App\Models\Chefs','id','vendor_id');
    }

    public function getRestaurantDetails()
    {
        return $this->hasOne('App\Models\Restaurants','id','restaurant_id');
    }

    public function getMenuDetails()
    {
        return $this->hasOne('App\Models\Menuitems','id','menu_id');
    }

    public function getAllfavorite()
    {
        return $this->hasMany(self::class,'vendor_id','vendor_id');
    }

    public function getAllFavoriteAlter()
    {
         return $this->hasManyThrough('App\Models\Menuitems','App\Models\Favourites','vendor_id','id','vendor_id','menu_id');
    }
    
    public function isFavourites($vendorId, $userId)
    {
        $f_id = 0; 
        if($vendorId != '' && $userId != ''){
            $favourite = Favourites::select('id')->where('vendor_id',$vendorId)->where('user_id',$userId)->first();
            if(isset($favourite->id) ){
                $f_id = 1;
            }
        }
        return $f_id;
    }

}
