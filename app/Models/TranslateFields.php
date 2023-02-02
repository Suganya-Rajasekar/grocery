<?php

namespace App\Models;

use Eloquent as Model;
/**
 * Class roles
 * @package App\Models
 * @version November 2, 2020, 12:53 pm UTC
 *
 * @property string $name
 */
class TranslateFields extends Model
{

    public $fillable = [
        'name','fields','created_at'
    ];
    public $table = 'tbl_translate_fields';
    
    const CREATED_AT = 'created_at';

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

    
}
