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
class Cuisines extends Model
{
	// use Log;
	public $table = 'cuisines';

	public $fillable = [
		'name',
		'slug',
		'image',
		'status',
		'root_id'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'name' => 'string',
		'slug' => 'string',
		'status'    => 'string',
		'image' => 'string',
		'created_dt' => 'datetime'
	];

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
		'name' => 'required|string|max:70',
		'slug' => 'required|string|max:70',
		'status'    => 'required',
		'updated_at' => 'nullable',
		'deleted_at' => 'nullable'
	];

	protected $hidden = [
		'created_at', 'updated_at', 'slug'
	];

	public function getMaincatAttribute()
	{
		// \DB::enableQueryLog();
		$path   = Cuisines::where('id',$this->attributes['root_id'])->first(['id','name']);
		// print_r(\DB::getQueryLog());exit();
		return (!empty($path)) ? $path->name : 'Main category';
	}

	public function getImageAttribute()
	{
		$path   = 'storage/app/public/cuisines/'.$this->attributes['image'];
		if ($this->attributes['image'] != '' && \File::exists(base_path($path))) {
			$url    = \URL::to($path);
		} else {
			$url    = getCommonMenuItem();
		}
		return $url;
	}

	public function scopeMaincat($query)
	{
		$query->where('root_id', 0);
	}

	public function scopeActive($query)
	{
		$query->where('status', 'active');
	}

	public function chefsget()
	{
		return $this->hasMany('App\Models\Chefs', 'cuisine_type', 'id')->where('status','approved')->orwhereRaw('FIND_IN_SET(`id`,`cuisine_type`)');
	}
}
