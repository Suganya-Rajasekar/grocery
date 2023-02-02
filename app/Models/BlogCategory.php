<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Models\Addon;
use App\Models\Restaurants;
use App\Traits\Log;

/**
 * Class category
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class BlogCategory extends Model
{
    use Log;
    public $table = 'categories';
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
    // public $timestamps       = false;
    protected $hidden = [
        'created_at', 'updated_at','content', 'p_id', 'type', 'slug', 'user_id', 'avatar', 
    ];

    public function newQuery($excludeDeleted = true) {
        return parent::newQuery($excludeDeleted)->where('type', 'tag_category');
    }

    /**
    * Get the menuitems that owns the category.
    */
    public function menuitems()
    {
        return $this->hasMany('App\Models\Menuitems', 'main_category', 'id');
    }

    public function approvedMenus()
    {
        return $this->menuitems()->approved()->instock();
    }

    public function Groupedmenus(){
        return $this->menuitems()->groupBy('vendor_id');
    }

    public function scopeWithAndWhereHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)->with([$relation => $constraint]);
    }
}
