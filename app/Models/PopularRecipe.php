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
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class PopularRecipe extends Model
{
    use Log;
    public $table = 'home_popular_recipe';

    public $fillable = [
        'name',
        'image',
        'status',
        'description',
        //'video_url',
        'video'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'status'    => 'string',
        'image' => 'string',
        'description' => 'string',
        //'video_url'=> 'string',
        'video'=> 'string',
        'created_dt' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:70',
        'status'    => 'required',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getImageAttribute()
    {
        $path   = 'storage/app/public/popular/'.$this->attributes['image'];
        if ($this->attributes['image'] != '' && \File::exists(base_path($path))) {
            $url    = \URL::to($path);
        } else {
            $url    = getCommonMenuItem();
        }
        return $url;
    }
    public function getVideoAttribute()
    {
        $path   = 'storage/app/public/popular/'.$this->attributes['video'];
        if ($this->attributes['video'] != '' && \File::exists(base_path($path))) {
            $url    = \URL::to($path);
        } else {
            $url    = '';
        }
        return $url;
    }

    public function scopeActive($query)
    {
        $query->where('status', 'active');
    }

    public function chefsget()
    {
        return $this->hasMany('App\Models\Chefs', 'cuisine_type', 'id')->where('status','approved')->orwhereRaw('FIND_IN_SET(`id`,`cuisine_type`)');
    }
}
