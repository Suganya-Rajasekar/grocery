<?php

namespace App\Models;
use App\Models\Language;

use Eloquent as Model;
/**
 * Class roles
 * @package App\Models
 * @version November 2, 2020, 12:53 pm UTC
 *
 * @property string $name
 */
class Translate extends Model
{

    public $fillable = [
        'tbl','lng','field','field_fk','type','key','content','created_at','updated_at'
    ];
    public $table = 'tbl_translate';
    
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
    ];

    public function scopeTranslateString($query, $table, $field_name, $field_fk='' )
    {
        $lang = session('lang');
        if(empty($lang))
        {
            $lang = Language::getBase()->pluck('id')->first();
        }
        $query->where('tbl',$table)->where('lng',$lang);
        if(!empty($field_fk))
        {
            $query->where('field_fk',$field_fk);
        }
        if(is_array($field_name))
        {
            $query->whereIn('key',$field_name);
        }else
        {
            $query->where('key',$field_name);
        }
        return $query;
    }
}
