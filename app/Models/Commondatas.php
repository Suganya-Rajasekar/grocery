<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Models\Addon;
use App\Traits\Log;

/**
 * Class category
 * @package App\Models
 * @version Feb 2021
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Commondatas extends Model
{
    use Log;
    public $table = 'common_datas';

    public $fillable = [
        'name',
        'status',
        'type'
    ];
    protected $hidden = [
        'created_at', 'updated_at','created_by', 'updated_by', 'type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'        => 'integer',
        'name'      => 'string',
        'status'    => 'string',
        'type'      => 'string',
        // 'created_at'=> 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:70',
        'updated_at' => 'nullable'
    ];

    public function scopeTag($query)
    {
       $query->where('type','tag');
    }
    public function scopeBudget($query)
    {
       $query->where('type','budget');
    }
    public function scopeBlogTag($query)
    {
       $query->where('type','blogtag');
    }
    public function scopeActive($query)
    {
       $query->where('status','active');
    }
}
