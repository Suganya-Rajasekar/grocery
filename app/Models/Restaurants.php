<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Models\Addon;
// use App\Traits\Dynamic;
// use App\Traits\Log;
// use App\Traits\PushNotification;

/**
 * Class category
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Restaurants extends Model
{
	// use Log,Dynamic,PushNotification;
	public $table = 'restaurants';
	
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';

	public $fillable = [
		'vendor_id',
		'name',
		'email',
		'description',
		'location',
		'cuisines',
		'tags',
		'mode',
		'delivery_time',
		'adrs_line_1',
		'adrs_line_1',
		'locality',
		'landmark',
		'budget',
		'latitude',
		'longitude',
		'preparation_time',
		'status',
		'tax',
		'package_charge',
		'commission',
		'time_to_sell',
		'facebook_link',
		'instagram_link',
		'youtube_link',
		'reason',
		'user_code',
		'fssai',
		'type',
		'event_datetime',
		'sector',
		'home_event'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		// 'name' => 'string',
		// 'price' => 'string',
		// 'image' => 'string',
		// 'description' => 'string',
		'created_dt' => 'datetime'
	];

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
		// 'name' => 'required|string|max:70',
		// 'description' => 'required|string',
		// 'image' => 'required',
		// 'price' => 'required',
		// 'created_dt' => 'required',
		'updated_at' => 'nullable',
		'deleted_at' => 'nullable'
	];

	protected $appends       = [/*'tags',*/'budget_name','event_time'/*,'chef_sector'*/];

	/**
	* Get the budget that owned by restaurant.
	*/
	public  function budget_info()
	{
		return $this->belongsTo(Commondatas::class, 'budget', 'id')->where('type','budget');
	}

	public function scopeFindinset($query,$field,$keyword)
	{
		if(!is_array($keyword)){ $keyword = explode(',', $keyword); }
		$query->whereRaw($field.' REGEXP("('.implode('|',$keyword).')")');
	}

	/**
	* Get the location that owned by restaurant.
	*/
	public  function location_info()
	{
		return $this->belongsTo(Locations::class, 'location', 'id');
	}

	/**
	* Get the location that owned by restaurant.
	*/
	public  function vendor_info()
	{
		return $this->belongsTo(Chefs::class, 'vendor_id', 'id');
	}

	/**
	 * Accessor for Cuisines property.
	 *
	 * @return array
	 */
	/*public function getCuisinesAttribute()
	{
		$cuisines   = explode(',', $this->attributes['cuisines']);
		$return     = array();
		$return     = Cuisines::whereIn('id',$cuisines)->get();
		return $return;
	}*/

	/**
	 * Accessor for tags property.
	 *
	 * @return array
	 */
	public function getEventTimeAttribute() 
	{
		$event_date_time = '';
		if(!empty($this->attributes['event_datetime'])) {			
			$event_date_time = date('Y-m-d h:i A',strtotime($this->attributes['event_datetime']));
			$event_date_time = explode(' ',$event_date_time,2);
		} 
		return $event_date_time;
	}

	public function getLocationNameAttribute() 
	{
		return $this->location_info->name;
	}

	public function getTagsAttribute()
	{
		$tags	= explode(',', $this->attributes['tags']);
		$return	= array();
		$return	= Commondatas::whereIn('id',$tags)->get();
		return $return;
	}

	public function setLogoAttribute($value)
	{
		$this->attributes['logo'] = $value;
	}

	public function getLogopathAttribute()
	{
		return $this->attributes['logo'];
	}

	public function getLogoAttribute()
	{
		$path	= 'storage/app/public/restaurant/'.$this->attributes['logo'];
		if ($this->attributes['logo'] != '' && \File::exists(base_path($path))) {
			$url	= \URL::to($path);
		} else {
			$url	= getCommonBanner();
		}
		return $url;
	}

	public function getBudgetNameAttribute()
	{
		$budgetName = '';
		// $budget = $this->getBudgetDetails()->first();
		// if (!empty($budget)) {
		//     $budgetName = $budget->name;
		// }
		if (isset($this->attributes['budget'])) {
			$budgetName = $this->attributes['budget'].' for two';
		}

		return $budgetName;
	}

	function getChefAttribute()
	{
		return $this->vendor_info()->select('id','cuisine_type','avatar','celebrity','promoted','certified','type')->first();   
	}

	/**
	 * Mutator for tags property.
	 *
	 * @param  array|string $ids
	 * @return void
	 */
	/*public function setTagsAttribute($ids)
	{
		$this->attributes['tags'] = is_string($ids) ? $ids : implode(',', $ids);
		return $this->attributes['tags'];
	}*/

	public function scopeApproved($query)
	{
		$query->where('status','=', 'approved');
	}

	public function scopeHavemenus($query)
	{
		$query->has('getApprovedFoodItems','>', '0');
	}

	public function scopeAvailable($query)
	{
		$currentDate = date('Y-m-d H:i:s'); $time   = date('H:i:s');
		$currentDay =strtolower(date('l'));
		$query->where('mode', 'open')
		->whereRaw('? not between off_from and off_to', [$currentDate])
		->where(function($qu) use ($time,$currentDay) {
			$qu->where(function($query) use ($time,$currentDay) {
				$query->where('opening_time','<=',$time)->where('closing_time','>=',$time);
				$query->whereRaw('JSON_EXTRACT(working_days, "$.'.$currentDay.'") = 0');
			})/*->orwhere(function($query) use ($time) {
				$query->where('opening_time2','<=',$time)->where('closing_time2','>=',$time);
			})*/;
		});
	}

	public function scopeEventAvailable($query)
	{
		$currentDate = date('Y-m-d H:i:s');
		$query->where('event_datetime','>',$currentDate)->where('mode','open')->whereRaw('? not between off_from and off_to', [$currentDate]);
	}

	public function scopeHomeEventAvailable($query)
	{
		$query->where('mode','open');
	}

	public function scopeCommonselect($query)
	{
		$query->addSelect('id','vendor_id','name','description','slug','mode','status','budget','adrs_line_1','latitude','longitude','preparation_time','tax');
	}

	public function getFoodItems()
	{
		return $this->hasMany('App\Models\Menuitems','restaurant_id','id');
	}

	public function categoryList()
	{
		return $this->getFoodItems()->groupBy('main_category');
	}

	/*public function rcategories()
	{
		return Category::class;
		// return $this->hasMany('App\Models\Category',1,1);
	}*/

	public function getApprovedFoodItems()
	{
		return $this->getFoodItems()->approved()->instock();
	}

	public function approvedDishes()
	{
		return $this->getFoodItems()->approved()->instock()->has('categories');
	}

	public function approvedTickets() 
	{
		return $this->getFoodItems()->approved();
	}

	public function approvedHomeevents() 
	{
		return $this->getFoodItems()->approved();
	}

	public function getRandomFoodItems($count = 4)
	{
		return $this->getFoodItems()->approved()->instock()->take($count);
	}
 
	/**
	 * The categories that belong to the restaurant.
	 */
	public function categories()
	{
		return $this->belongsToMany(Category::class, Menuitems::class, 'restaurant_id', 'main_category', 'id')->groupBy('main_category')/*->has('approvedMenus', '>', 0)->whereHas('menuitems')*/;
	}

	public function approvedCategories()
	{
		return $this->categories()/*->has('approvedMenus', '>', 0)*/->whereHas('approvedMenus');
	}

	public function allCategories()
	{
		return $this->categories()->has('menuitems', '>', 0)/*->whereHas('menuitems')*/;
	}

	public function getBudgetDetails()
	{
		return $this->hasOne('App\Models\Commondatas','id','budget')->budget()->active();
	}

	public  function rz_account()
	{
	   return $this->hasOneThrough(RzAccount::class,Chefs::class,'id','chef','vendor_id','id')->approvedcontact();
	}
}
