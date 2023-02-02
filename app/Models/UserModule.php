<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class UserModule
 * @package App\Models
 * @version October 28, 2020, 7:56 am UTC
 *
 * @property string $name
 */
class UserModule extends Model
{

    public $table = 'tbl_modules_access';
    
    public $fillable = [
        'user_id','access'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
