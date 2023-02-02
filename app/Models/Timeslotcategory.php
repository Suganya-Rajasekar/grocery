<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Traits\Log;

/**
 * Class category
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Timeslotcategory extends Model
{
    use Log;
    public $table = 'time_slot_categories';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'name',
        'slug',
        'status',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'    => 'integer',
        'name'  => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name'  => 'required|string|max:70',
        'status'=> 'required|in:active,inactive,declined,p_inactive',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

   /**
    * Get the slots that owns the Category.
    */
    public function slots()
    {
        return $this->hasMany('App\Models\Timeslotmanagement', 'cat_id', 'id');
    }
}
