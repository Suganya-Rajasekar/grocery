<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Models\Addon;
use App\Traits\Log;

/**
 * Class category
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Banner extends Model
{
	use Log;
	public $table		= 'banners';
	const IMAGE_PATH	= 'assets/img/banner/';

	public $fillable	= [
		'image',
		'status',
		'start_date',
		'end_date',
		'created_dt'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts	= [
		'id' => 'integer',
		'image' => 'string',
		'status' => 'string',
		'start_date' => 'date',
		'end_date' => 'date'
	];

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules	= [
		'image' => 'required',
		'status' => 'required|string',
		'start_date' => 'required',
		'end_date' => 'required',
		'updated_at' => 'nullable',
		'deleted_at' => 'nullable'
	];

	protected $hidden	= [
	   'created_at', 'updated_at',  'image'
	];

	public $appends		= ['image_src'];

	public function scopeActive($query)
	{
		$date = date('Y-m-d');
		$query->where('status','active')/*->where('start_date','<=',$date)->where('end_date','>=',$date)*/;
	}

	public function getImageSrcAttribute()
	{ 
		return ValidateImage($this::IMAGE_PATH , $this->image);
	}
}