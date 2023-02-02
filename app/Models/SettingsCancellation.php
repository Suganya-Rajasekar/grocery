<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class category
 * @package App\Models
 * @version September, 2021
 */
class SettingsCancellation extends Model
{
    public $table       = 'settings_cancellation';
    const CREATED_AT    = 'created_at';
    const UPDATED_AT    = 'updated_at';

    public $fillable = [
        'timeline',
        'hours',
        'chef_penalty',
        'customer_penalty',
        'status',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'integer',
        'hours'             => 'integer',
        'chef_penalty'      => 'integer',
        'customer_penalty'  => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'hours'             => 'required|numeric',
        'chef_penalty'      => 'required|numeric',
        'customer_penalty'  => 'required|numeric',
        'status'    => 'required|in:active,inactive',
        'timeline'  => 'required|in:before,upto,after',
    ];
}
