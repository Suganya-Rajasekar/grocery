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
class Explore extends Model
{
    use Log;
    public $table = 'explore';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'name',
        'slug',
        'image',
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
        'image' => 'string',
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

    public function getImageAttribute()
    {
        $path   = 'storage/app/public/explore/'.$this->attributes['image'];
        if ($this->attributes['image'] != '' && \File::exists(base_path($path))) {
            $url    = \URL::to($path);
        } else {
            $url    = \URL::to('storage/app/public/cuisine.png');
        }
        return $url;
    }
}
