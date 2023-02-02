<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Models\Addon;

/**
 * Class category
 * @package App\Models
 * @author Suganya
 * @version Mar 17, 2021
 */
class Address extends Model
{
	public $table = 'user_address';
	public $appends = ['display_address'];
	protected $fillable = [
		'user_id',
		'address_type',
		'landmark',
		'building',
		'address',
		'lat',
		'lang',
		'area',
		'pin_code',
		'city',
		'state',
	];

	public function getDisplayAddressAttribute()
	{
		return $this->attributes['building'].', '.$this->attributes['area'].', '.$this->attributes['city'].', '.$this->attributes['state'].', '.$this->attributes['pin_code'];
	}

	public function getUserDetails()
    {
        return $this->hasOne('App\Models\Customer','id','user_id');
    }
}
