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
class Wishlist extends Model
{

    public $table = 'wishlist';

    public function scopeCommonselect($query)
    {
        $query->addSelect('id','title','description','created_at');
    }

  
    public function getUserDetails()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }
}
