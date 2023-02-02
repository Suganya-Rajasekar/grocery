<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Module
 * @package App\Models
 * @version October 28, 2020, 7:56 am UTC
 *
 * @property string $name
 */
class Module extends Model
{

    public $table = 'tbl_modules';
    



    public $fillable = [
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
    ];
    public $timestamps = false;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
    
}
