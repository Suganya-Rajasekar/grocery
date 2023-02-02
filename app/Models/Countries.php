<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
	protected $table="countries";

	public $timestamps = false;

	protected $append = [ 'flag' ];
    protected $hidden = [
        'nicename' ,'created_at', 'updated_at', 'status', 'iso3', 'iso', 'numcode'
    ];

	public function getFlagAttribute()
	{
		if($this->attributes['iso'] != '')
		{
			return \URL::to('uploads/flags/'.strtolower($this->iso).'.png');
		}
		return '';
	}

	public function getFlagsmallAttribute()
	{
		if($this->attributes['iso'] != '')
		{
			return \URL::to('uploads/flags-small/'.strtolower($this->iso).'.png');
		}
		return '';
	}
}
