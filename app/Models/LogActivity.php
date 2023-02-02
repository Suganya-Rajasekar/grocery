<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Models\Addon;
use App\Traits\Dynamic;

/**
 * Class category
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class LogActivity extends Model
{
    use Dynamic;
    public $table = 'log_activities';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'user_id',
        'record_id',
        'module',
        'url',
        'ip',
        'before_change',
        'after_change',
        'record',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        // 'name' => 'string',
        // 'price' => 'string',
        // 'image' => 'string',
        // 'description' => 'string',
        'created_at' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'name' => 'required|string|max:70',
        // 'description' => 'required|string',
        // 'image' => 'required',
        // 'price' => 'required',
        // 'created_dt' => 'required',
        'updated_at' => 'nullable',
    ];

 
}
