<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;

/**
 * Class category
 * @package App\Models
 * @author Suganya
 * @version March 13, 2021
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Cartaddon extends Model
{
	public $table = 'cart_addon';
	public $timestamps		= false;
	protected $appends = ['name'];
	public $fillable = [
		'cart_id',
		'addon_id',
		'quantity',
		'price',
	];

	public function getNameAttribute()
	{
        return $this->hasOne(Addon::class, 'id', 'addon_id')->pluck('name')->first();
	}
}
?>