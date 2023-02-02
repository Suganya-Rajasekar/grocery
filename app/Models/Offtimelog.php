<?php

namespace App\Models;

use Eloquent;

/**
 * Class Offtimelog
 * @package App\Models
 * @author Suganya
 * @version Mar 22, 2021, 12:10 pm Asia\Kolkatta
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Offtimelog extends Eloquent
{
	public $table = 'vendor_offtime_log';

	protected $fillable = [
		'vendor_id', 'restaurant_id', 'off_from','off_to','created_by', 'updated_by'
	];

	public $appends = [];

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
		'vendor_id'		=> 'required|numeric',
		'restaurant_id'	=> 'required|numeric',
		'off_from'		=> 'required|date_format("Y-m-d H:i:s")',
		'off_to'		=> 'required|date_format("Y-m-d H:i:s")',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'created_by', 'updated_by','created_at', 'updated_at',
	];
}
?>