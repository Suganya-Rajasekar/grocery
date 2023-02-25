<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use App\Models\Translate;
use App\Models\Language;

/**
 * Class Testimonials
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Testimonials extends Model
{
	use SoftDeletes;

	public $table = 'tbl_testimonials';
	
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	const IMAGE_PATH = '/assets/front/img/testimonials/';


	protected $dates = ['deleted_at'];



	public $fillable = [
		'name',
		'image',
		'description',
		'created_dt'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'name' => 'string',
		'image' => 'string',
		'description' => 'string',
		'created_dt' => 'datetime'
	];

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
		'name' => 'required|string|max:70',
		'description' => 'required|string',
		'image' => 'required',
		// 'created_dt' => 'required',
		'updated_at' => 'nullable',
		'deleted_at' => 'nullable'
	];

	public function getImageSrcAttribute()
	{
		return ValidateImage($this::IMAGE_PATH , $this->image);
	}

	public function getNameAttribute()
	{
		return getTranslated('testimonials', 'name', $this->id , $this->attributes['name']);
	}

	public function getDescriptionAttribute()
	{
		return getTranslated('testimonials', 'description', $this->id , $this->attributes['description']);
	}
}