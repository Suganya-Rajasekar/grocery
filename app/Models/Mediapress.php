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
class Mediapress extends Model
{
    use Log;
    public $table = 'blogs';

    public $fillable = [
        'name',
        'image',
        'status',
        'description',
        'type',
        'media_type',
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
        'category'=> 'string',
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

    public function newQuery($excludeDeleted = true) {
        return parent::newQuery($excludeDeleted)
        ->where('type', 'media');
    }

    public function getImageAttribute()
    {
        $path   = 'storage/app/public/blog/'.$this->attributes['image'];
        if ($this->attributes['image'] != '' && \File::exists(base_path($path))) {
            $url    = \URL::to($path);
        } else {
            $url    = getCommonMenuItem();
        }
        return $url;
    }

    public function getVideoAttribute()
    {
        $path   = 'storage/app/public/blog/'.$this->attributes['video'];
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

    public function getTagsAttribute()
    {
        $tags       = explode(',', $this->attributes['tags']);
        $return     = array();
        $return     = Commondatas::whereIn('id',$tags)->get();
        return $return;
    }
    public function getCategoryAttribute()
    {
        $cate       = explode(',', $this->attributes['category']);
        $return     = array();
        $return     = BlogCategory::whereIn('id',$cate)->where('type','tag_category')->get();
        return $return;
    }

    public function chefsget()
    {
        return $this->hasMany('App\Models\Chefs', 'cuisine_type', 'id')->where('status','approved')->orwhereRaw('FIND_IN_SET(`id`,`cuisine_type`)');
    }
}
