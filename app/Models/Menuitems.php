<?php
namespace App\Models;

use File, Auth, App;
use App\Models\Category;
use App\Models\Themes;
use App\Models\Preferences;
use App\Traits\Log;
use App\Traits\PushNotification;
use App\Models\Homeeventsection;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\EloquentEagerLimit\HasEagerLimit;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menuitems extends Model
{
	use Log,HasFactory, HasEagerLimit,PushNotification;
	protected $table	= "menu_items";
	protected $fillable = ['restaurant_id','name', 'status','vendor_id','description','price','preparation_time','item_type','main_category','stock_status','quantity','addons','unit','discount','tag_type','food_type','purchase_quantity','minimum_order_quantity','themes','preferences','meal'];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'created_at', 'updated_at', 'created_by', 'updated_by', /*'newPrice', 'customize'*/
	];

	protected $appends = [
		'preparation_time_text','is_favourites', 'unit_detail', 'cart_count', 'comments_count', 'likes_count', 'likes_count_text','discount_price','purchase_quantity_count','today_order_disable','theme_detail','preference_detail','meal_detail','home_event_sections'/*, 'category_name', 'order_quantity'*/
	];

	public function scopeApproved($query)
	{
		$query->where('status','=', 'approved');
	}

	public function scopeLimititem($query,$count=15)
	{
		$query->limit($count);
	}

	public function scopePending($query)
	{
		$query->where('status','=', 'pending');
	}

	public function scopeCancelled($query)
	{
		$query->where('status','=', 'cancelled');
	}

	public function scopeDeleted($query)
	{
		$query->where('status','=', 'deleted');
	}

	public function scopeInstock($query)
	{
		$query->where('stock_status','=', 'instock')/*->where('quantity','>', 0)*/;
	}

	public function scopeOutofstock($query)
	{
		$query->where('stock_status','=', 'outofstock');
	}

	public function scopeCommonselect($query)
	{
		$query->addSelect('id','restaurant_id','vendor_id','name','description','price','status','stock_status','quantity','signature','preparation_time','item_type','addons','main_category','image','customize','unit','discount','purchase_quantity','themes','preferences','meal');
	}

	public function getImageAttribute()
	{
		$url			= getCommonMenuItem();
		if(isset($this->attributes['image'])){
			$path		= 'storage/app/public/menuitems/'.$this->attributes['vendor_id'].'/'.$this->attributes['image'];
			if ($this->attributes['image'] != '' && File::exists(base_path($path))) {
				$url	= \URL::to($path);
			}
		}
		return $url;
	}

	public function setUnitAttribute($value)
	{
		$Aunit	= [];
		if(!empty($value)){
			$unit	= $value['unit'];
			$price	= $value['price_unit'];
			if ( !empty($unit) &&  count($unit) > 0) {
				foreach ($unit as $key => $val) {
					if ($val != '' && $price[$key] > 0) {
						$Aunit[]	= ['unit'=> $val,'price'=> $price[$key]];
					}
				}
			}
		}
		return $this->attributes['unit']	= json_encode($Aunit);
	}

	public function getUnitDetailAttribute()
	{
		$unit		= json_decode($this->attributes['unit'],true);
		$Aunit		= [];
		if ($unit != '' && count($unit) > 0) {
			foreach ($unit as $key => $value) {
				$unit	= Addon::find($value['unit']);
				if (!empty($unit)) {
					$unit_name = $unit->name;
					$discount_price = (isset($this->attributes['discount']) && $this->attributes['discount'] != '' && $this->attributes['discount'] != 0) ? ((int) $value['price'] - ((int) $value['price'] * (int) $this->attributes['discount'] / 100)) : 0;
					$discount = (isset($this->attributes['discount']) && $this->attributes['discount'] != '' && $this->attributes['discount'] != 0) ? $this->attributes['discount'] : 0;
					// $unit_name=$this->hasOne(Addon::class)->where('id', $value['unit'])->first();
					$Aunit[]   = ['id'=> $value['unit'], 'name' => $unit_name, 'price' => (float) $value['price'],'discount_price' => (int) $discount_price,'discount' => $discount];
				}
			}
		}
		return $Aunit;
	}

	public function getUnitExportAttribute()
	{
		$variants_imp = '';
		$units = $this->getUnitDetailAttribute();
		if(!empty($units)){
			foreach($units as $key => $value){
				$variants[]   = $value['name'].':'.$value['price'];
				$variants_imp = implode(',',$variants); 
			}
		}
		return $variants_imp;
	}

	/*public function getPreparationTimeAttribute()
	{
		return $this->attributes['preparation_time'];
	}*/

	public function getPreparationTimeTextAttribute()
	{ 
		/*$str	= '';
		if ($this->preparation_time == '00:00:00') {
			return '0 mins';
		}
		$time	= explode(':', $this->preparation_time);
		foreach ($time as $key => $value) {
			if ($value > 0) {
				if ($key == 0) {
					$str .= $value.' hrs ';
				} else {
					$str .= $value.' mins';
				}
			}
		}*/
		$str = 'Tomorrow Onwards';
		if ($this->preparation_time == '2_to_3hrs') {
			$str = 'In 2 to 3 Hrs';
		}
		return $str;
	}

	public function getAddonsAttribute()
	{
		$addons	= explode(',', $this->attributes['addons']);
		$return	= array();
		$return	= Addon::whereIn('id',$addons)->where('type','addon')->get();
		return $return;
	}

	public function getAddonsExportAttribute()
	{
		$addons_imp = '';
		if(!empty($this->addons)) {
			foreach($this->addons as $k => $v) {
				$addons[]   = $v->name;
				$addons_imp = implode(',',$addons); 
			}
		}
		return $addons_imp;
	}

	public function getIsFavouritesAttribute()
	{
		$f_id = $user_id = 0;
		$userData	= App::call('App\Http\Controllers\Api\Customer\CustomerController@me');
		if (!empty($userData)) {
			$user_id	= $userData->id;
		}
		if($this->vendor_id != '' && $user_id != 0) {
			$favourite	= Favourites::select('id')->where('vendor_id',$this->vendor_id)->where('menu_id',$this->id)->where('user_id',$user_id)->first();
			if(isset($favourite->id) ) {
				$f_id	= 1;
			}
		}
		return $f_id;
	}

	public function getCartCountAttribute()
	{
		$cartcount	= $user_id = 0; $cookie = \Session::get('cookie');
		$userData	= App::call('App\Http\Controllers\Api\Customer\CustomerController@me');
		if (!empty($userData)) {
			$user_id = $userData->id;
		}
		if($user_id != 0 || $cookie != '') {
			$cartcount	= uCartQuery($user_id, $cookie)->where('food_id',$this->id)->sum('quantity');
			return (int) $cartcount;
		}
		return $cartcount;
	}

	/* Items sales count */
	public function getOrderQuantityAttribute()
	{
		$id		= $this->attributes['id'];
		$result	= Orderdetail::where('res_id',$this->attributes['restaurant_id'])->get()->map(function($result)use($id) {
			//NOTE : CHECK THIS IF CONDITION
			if(!empty($result->food_items)) {
				foreach($result->food_items as $key=> $value) {
					if($value['id'] == $id) {
 						return $value['quantity'];
					}
				}
			}
		})->sum();
		return $result;
	}
	/* Items sales price */

	// Item Stock Check 
	public function getStockRemainAttribute()
	{
		$date = date("Y-m-d");
		return getStockMenu($this->attributes['restaurant_id'],$this->attributes['id'],$date,$this->attributes['quantity'])['remaining'];
	}

	public function getOrderPriceAttribute()
	{
		$id		= $this->attributes['id'];
		$result	= Orderdetail::where('res_id',$this->attributes['restaurant_id'])->get()->map(function($result)use($id) {
			if(!empty($result->food_items)) {
				foreach($result->food_items as $key => $value) {
					if($value['id'] == $id) {
						return $value['quantity'] * $value['fPrice'];
					}
				}
			}
		})->sum();
		return $result;
	}

	public function getCategoryNameAttribute()
	{
		return $this->categories()->first()->name ?? '';
	}

	public function getCommentsCountAttribute()
	{
		return $this->comments()->published()->comment()->count();
	}

	public function getLikesCountAttribute()
	{
		return $this->favourites()->count();
	}

	public function getLikesCountTextAttribute()
	{
		$text	= 'Be the first to like';
		$fcount = $this->favourites()->count();
		if ($fcount == 1) {
			$text = $fcount.' Like';
		} else {
			$text = $fcount.' Likes';
		}
		return $text;
	}

	public function getDishBasedOnCategory()
	{
		$this->hasMany(self::class,'main_category','main_category');
	}

	/**
	* Get the categories that owns the Menu.
	*/
	public function categories()
	{
		return $this->belongsTo(Category::class, 'main_category', 'id');
	}

	public function menuscatogory()
	{
		return $this->categories()->groupBy('id');
	}

	public function favourites()
	{
		return $this->hasMany(Favourites::class, 'menu_id', 'id');
	}

	/**
	* Get the comments of Menu.
	*/
	public function comments()
	{
		return $this->hasMany(Comment::class, 'food_id', 'id');
	}

	public function restaurant()
	{
		return $this->hasOne('App\Models\Restaurants','id','restaurant_id');
	}

	public function vendor()
	{
		return $this->hasOne(Chefs::class,'id','vendor_id');
	}

	public function getStockInAttribute() 
	{
		$stockin = false;
		if($this->getStockRemainAttribute() > 0) {
			$stockin = true;
		}
		return $stockin;
	}

	public function getDiscountPriceAttribute() 
	{
		$discount_price = 0;
		if(isset($this->attributes['discount']) && $this->attributes['discount'] > 0) {
			$discount_price = $this->attributes['price'] - ((int) $this->attributes['price'] * (int) $this->attributes['discount'] / 100); 
		}
		return (int) $discount_price; 
	}

	public function getPurchaseQuantityCountAttribute()	
	{
		$purchased_count = $user_id = 0;
		$userData	= App::call('App\Http\Controllers\Api\Customer\CustomerController@me');
		if (!empty($userData)) {
			$user_id = $userData->id;
		}
		$ordered_count = Orderdetail::where('user_id',$user_id)->get()->map(function($result) {
			if(!empty($result->food_items)) {
				foreach ($result->food_items as $key => $value) {
					if($value['id'] == $this->attributes['id'] && isset($value['discount']) && $value['discount'] == (int)$this->attributes['discount'])
					{
						return $value['quantity'];
					}
				}	
			}
		})->sum();
		$purchased_count = $ordered_count + $this->getCartCountAttribute();
		$remaining_count = 9999;
		if (isset($this->attributes['discount']) && $this->attributes['discount'] > 0 && $this->attributes['purchase_quantity'] > 0){
			if ($this->attributes['purchase_quantity'] > $purchased_count) {
				$remaining_count =  $this->attributes['purchase_quantity'] - $purchased_count;
			} elseif ($this->attributes['purchase_quantity'] <= $purchased_count) {
				$remaining_count = 0;
			}
		} 
		return $remaining_count;		
	}

	public function getTodayOrderDisableAttribute() 
	{
		$status = true;
		if(isset($this->restaurant) && !empty($this->restaurant) && $this->restaurant->preparation_time == 'ondemand') {
			$status = false;
			$todayTime  = date("Y-m-d H:i:s");
			if(isset($this->preparation_time) && !empty($this->preparation_time)) {
				$rtime  = date("H:i:s A", strtotime('+2 hours', strtotime($todayTime)));
				if($this->preparation_time == '1_to_2hrs') {
					$rtime  = date("H:i:s A", strtotime('+1 hours', strtotime($todayTime)));
				}
				if($rtime >  '22:46:00 PM') {
					$status = true; 
				}
			}
		}   
		return $status;
	}

	public function getThemeDetailAttribute()
	{
		$theme_det = collect();
		if($this->themes) {
			$theme_det = Themes::whereIn('id',explode(',',$this->themes))->get();
		}
		return $theme_det;
	}

	public function getPreferenceDetailAttribute()
	{
		$prefer_det = collect();
		if($this->preferences) {
			$prefer_det = Preferences::whereIn('id',explode(',',$this->preferences))->get();
		}
		return $prefer_det;
	}

	public function getMealDetailAttribute()
	{
		$meal_det = collect();
		if($this->meal) {
			$meal_det = explode(',', $this->meal);	
		}
		return $meal_det;
	}

	public function getHomeEventSectionsAttribute()
	{
		$data = ($this->food_type == 'home_event_menu') ? Homeeventsection::select('meal_section_name','theme_section_name','preference_section_name','addon_section_name')->first() : collect(['meal_section_name' => '','theme_section_name' => '','preference_section_name' => '','addon_section_name' => '']);
		return $data;
	}
}