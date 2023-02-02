<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Models\Addon;
use App\Traits\Log;

use App\Traits\FindInSetRelationTrait;

/**
 * Class category
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Location extends Model
{
    use Log,FindInSetRelationTrait;
    public $table = 'locations';

    public $fillable = [
        'name','code'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'code' => 'string',
        'latitude' => 'string',
        'longitude' => 'string',
        'created_dt' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:70',
        'code' => 'required|string|max:20',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function scopeActive($query)
    {
        return $query->where('status','=', 'active');
    }

    public function scopeNearby($query,$latitude=0.00,$longitude=0.00)
    {
        $lat_lng        = " ( round(
          ( 6371 * acos( least(1.0,
            cos( radians(".$latitude.") )
            * cos( radians(latitude) )
            * cos( radians(longitude) - radians(".$longitude.") )
            + sin( radians(".$latitude.") )
            * sin( radians(latitude)
          ) ) )
        ), 2) ) ";
        return $query->active()->whereRaw($lat_lng . '<= 20');
    }

    public function getVendor()
    {
        return $this->hasManyThrough(Chefs::class,Restaurants::class,'location','id','id','vendor_id');
    }
}
