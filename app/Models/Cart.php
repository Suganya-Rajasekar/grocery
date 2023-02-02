<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Models\Themes;
use App\Models\Preferences;

/**
 * Class category
 * @package App\Models
 * @author Suganya
 * @version March 13, 2021
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Cart extends Model
{
    public $table = 'cart_detail';

    public $fillable = [
		'food_id',
		'cookie',
		'quantity',
		'is_addon',
		'is_preorder',
		'date',
		'time_slot',
		'unit',
		'user_id',
		'res_id',
        'price',
        'deliverdistance',
        'delivermins',
        'address_id',
        'coupon_id',
        'total_coupon_value',
        'is_samedatetime',
        'samedatetime_from',
        'food_type',
        'base_price',
        'discount_price',
        'discount',
        'used_wallet_amount',
        'theme',
        'preferences',
        'meals_count'
	];

     public $appends = ['timeslot_available','ordertimeslotavailable','theme_data'];

    public  function vendor_info()
    {
        return $this->hasOne(Restaurants::class, 'id', 'res_id');
    }

    public  function menu_item()
    {
        return $this->hasOne(Menuitems::class, 'id', 'food_id');
    }

    public function getFoodDetails()
    {
        return $this->hasOne(Menuitems::class, 'id', 'food_id');
    }

    public function getFoodNameAttribute()
    {
        return $this->getFoodDetails()->pluck('name')->first();
    }

    public function getPriceAttribute()
    {
        return (float) $this->attributes['price'];
    }

    public function getFoodPriceAttribute()
    {
        $foodData   = $this->getFoodDetails()->first();
        $unitPrice  = $foodData->price;
        if ($this->attributes['unit'] > 0) {
            $key    = array_search($this->unit, array_column($foodData->unit_detail, 'id'));
            $unit       = $foodData->unit_detail[$key];
            $unitPrice  = $unit['price'];
        }
        return (float) $unitPrice;
    }

    /*public function getDiscountFoodPriceAttribute()
    {
       $unitPrice  = 0;
       $foodData   = $this->getFoodDetails()->first();
       if($foodData->discount != 0) {
         $unitPrice  = $foodData->price - ($foodData->price * (int)($foodData->discount/100));
         if ($this->attributes['unit'] > 0) {
            $key    = array_search($this->unit, array_column($foodData->unit_detail, 'id'));
            $unit       = $foodData->unit_detail[$key];
            $unitPrice  = $unit['discount_price'];
        }
       }
        return (float) $unitPrice;
    }*/
    public function scopeUserid($query,$id)
    {
        return $query->where('user_id',$id);
    }

    public  function addon_item()
    {
        return $this->hasMany(Cartaddon::class, 'cart_id', 'id');
    }

    public function getAddonCountAttribute()
    {
        $ratingSum   = $this->addon_item()->sum('id');
    }

    public function getTimeSAttribute()
    {
        if($this->attributes['food_type'] == 'menuitem') {
            return Timeslotmanagement::where('id',$this->attributes['time_slot'])->first()->time_slot;
        } else {
            return '';
        }
    }
    public function getTimeChefAttribute()
    {
        return Timeslotmanagement::where('id',$this->attributes['time_slot'])->first()->time_slot_chef;
    }

    /*public function getTaxAttribute()
    {
        if($this->vendor_info->tax > 0) {
            return $this->price * ( $this->vendor_info->tax / 100 );
        }
        return 0;
    }*/

    public function getTimeslotAvailableAttribute()
    {
        $vendor=$this->vendor_info()->available()->first();
        if (!empty($vendor)) {
            $times= Timeslotmanagement::where('id',$this->attributes['time_slot'])->with('timeSlotS','timeSlotE')->first();
            $now    = ceil(time()/1800)*1800;
            $chunks = explode('-', $times->time_slot);
            $open_time = strtotime($chunks[0]);
            $close_time = strtotime($chunks[1]);
            if(((($open_time <= $now) || ($open_time >= $now)) && ($now <= $close_time)) && ($this->attributes['date'] >= date('Y-m-d'))){
                return true;
            } else {
                return false;
            }
        } else {
            return false; 
        }
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }

    public function getcoupon()
    {
        return $this->hasOne(Offer::class, 'id', 'coupon_id')->select('name','promo_code','id','promo_type','offer','min_order_value');
    }

    public function getOrdertimeslotavailableAttribute()
    {   
        $response['status'] = true; 
        $response['message'] = 'Please change the delivery slot';
        if($this->attributes['food_type'] == 'menuitem') {
            $timeslot = Timeslotmanagement::where('id',$this->attributes['time_slot'])->first()->time_slot;
            $time = explode('-',$timeslot);
            if($this->attributes['is_preorder'] == 'no')  {
                if ((date('Y-m-d') < $this->attributes['date'])) {
                    $response['status'] = false;
                    return $response;
                } 
                if($this->menu_item->preparation_time == '1_to_2hrs') {
                    if(time() > strtotime($time[0]) - (30*60)) {
                        $response['status'] = false;
                        return $response;    
                    } 
                } elseif ($this->menu_item->preparation_time == '2_to_3hrs') {
                    if(time() > strtotime($time[0]) - (90*60)) {
                        $response['status'] = false;
                        return $response;    
                    }
                }
            } elseif($this->attributes['is_preorder'] == 'yes') {
                $orderdatetime = $this->attributes['date'].' '.$time[0];
                $today = date('Y-m-d H:i:s');

                if($this->menu_item->preparation_time == '1_to_2hrs') {
                    if($today > date('Y-m-d H:i:s',strtotime($orderdatetime) - (30*60))) {
                        $response['status'] = false;
                        return $response;    
                    } 
                } elseif ($this->menu_item->preparation_time == '2_to_3hrs') {
                    if($today > date('Y-m-d H:i:s',strtotime($orderdatetime) - (90*60))) {
                        $response['status'] = false;
                        return $response;    
                    }
                } 
            }
        } 
        $response['message'] = '';
        return $response;
    }

    public function getEventavailableAttribute()
    {
        $event_status = 'not_avail';
        $vendor = $this->vendor_info()->EventAvailable()->first();
        if($this->attributes['food_type'] == "ticket") {
            if($vendor) {
                $event_status = 'avail';
            }
        } else {
            $event_status = 'not a event';
        }
        return $event_status;
    }

    public function getThemeDataAttribute()
    {
        $theme = [];
        if($this->theme) {
            $theme[] = Themes::find($this->theme,['id','name','amount','images'])->makeHidden('theme_images')->toArray();
        }   
        return $theme;
    }

    public function getPreferencesDataAttribute()
    {
        $preferences = [];
        if($this->preferences) {
            foreach (explode(',',$this->preferences) as $key => $value) {
                $preferences[] = Preferences::find($value,['id','name'])->toArray();
            }
        }   
        return $preferences;
    }

    public function getMealsDataAttribute() 
    {
        $meal = ($this->meals_count) ? json_decode($this->meals_count,true) : (object) [];
        return $meal;
    }
    /*public function delete()
    {
        $this->addon_item()->delete();
        return parent::delete();
    }

    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();
        static::deleting(function($query) { // before delete() method call this
            $query->addon_item()->delete(); // do the rest of the cleanup...
        });
    }*/
}
?>