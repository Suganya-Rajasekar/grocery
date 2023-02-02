<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth; 
use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
	protected $table		= "locations";
	protected $primaryKey	= 'id';
	// public $timestamps		= false;
	protected $hidden = [
		'created_at', 'updated_at', 'status'
	];

	/**
	* Get the vendor in that location.
	*/
	/*public  function vendor()
	{
		return $this->hasMany(User::class, 'location', 'id');
	}*/
}
