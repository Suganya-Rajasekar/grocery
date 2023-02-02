<?php

namespace App\Models;
use App\Models\Translate;
use App\Models\Testimonials;
use Eloquent as Model;
/**
 * Class roles
 * @package App\Models
 * @version November 2, 2020, 12:53 pm UTC
 *
 * @property string $name
 */
class Language extends Model
{
    public $table = 'tbl_language';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:45',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    function getTranslateDataAttribute($tbl,$id)
    {
        if(request()->segment(1) == 'translate')
        {
            return Translate::where('tbl',$tbl)->where('lng',$this->id)->where('key',$id)->get()->toArray();
        }
            return Translate::where('tbl',$tbl)->where('lng',$this->id)->where('field_fk',$id)->get()->toArray();
    }

    function scopegetBase($query)
    {
        return $query->where('base',1);
    }
}
