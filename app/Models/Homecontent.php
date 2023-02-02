<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Models\Addon;
use App\Traits\Log;
use File;

/**
 * Class category
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Homecontent extends Model
{
    use Log;
    public $table = 'home_content';

    public $fillable = [
        'title','subtitle','banimg'
    ];
    public $timestamps = false;
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'subtitle' => 'string',
        'banimg' => 'string',
        'created_dt' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
        'subtitle' => 'required',
        'banimg' => 'required',
    ];

    public function getbanImageAttribute()
    {
        $url            = getCommonBanner();
        if(isset($this->attributes['banimg'])){
            $path       = 'storage/app/public/homecontent/'.$this->attributes['banimg'];
            if ($this->attributes['banimg'] != '' && File::exists(base_path($path))) {
                $url    = \URL::to($path);
            }
        }
        return $url;
    }
}
