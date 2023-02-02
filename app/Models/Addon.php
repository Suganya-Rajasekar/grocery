<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Traits\Log;
use App\Traits\PushNotification;

/**
 * Class category
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Addon extends Model
{
    use Log,PushNotification;
    public $table = 'addons';

    public $fillable = [
        'name',
        'slug',
        'content',
        'price',
        'status',
        'user_id',
        'reason'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'        => 'integer',
        'name'      => 'string',
        'slug'      => 'string',
        'content'   => 'string',
        'price'     => 'float',
        'status'    => 'string',
        'user_id'   => 'integer',
        'created_dt'=> 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name'      => 'required|string|max:70',
        'slug'      => 'required',
        'price'     => 'required',
        'status'    => 'required',
        'user_id'   => 'required',
        'updated_at'=> 'nullable',
        'deleted_at'=> 'nullable'
    ];

    // public $timestamps       = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'user_id', 'slug', 'type'
    ];

    /**
    * Get the menuitems that owns the addons.
    */
    /*public  function menuitems()
    {
        return $this->hasMany(Menuitems::class, 'addons', 'id');
    }*/

    public function scopeActive($query)
    {
        $query->where('status','active');
    }

    public function scopeActivevariations($query)
    {
        $query->where('type','unit')->active();
    }
}
