<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;

/**
 * Class Service
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Service extends Model
{
    use SoftDeletes;

    public $table = 'tbl_service';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'price',
        'description',
        'created_dt'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'price' => 'string',
        'description' => 'string',
        'created_dt' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:70',
        'description' => 'required|string',
        'price' => 'required',
        // 'created_dt' => 'required',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function getNameAttribute()
    {
        return getTranslated('service', 'name', $this->id , $this->attributes['name']);
    }

    public function getDescriptionAttribute()
    {
        return getTranslated('service', 'description', $this->id , $this->attributes['description']);
    }

    public function getSlugNameAttribute()
    {
        return Str::slug($this->name, '-');
    }
}
