<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Traits\Dynamic;

/**
 * Class category
 * @package App\Models
 * @author Roja
 * @version Mar 2021
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Orderdetail extends Model
{
    use Dynamic;
    public $table       = 'order_detail';
    // protected $appends  = ['food_items_count'];
    public $fillable    = ['order_id', 'm_id', 's_id', 'user_id', 'vendor_id', 'res_id', 'boy_id', 'total_food_amount', 'vendor_price', 'commission', 'commission_amount', 'offer_percentage', 'offer_amount', 'del_km', 'del_charge', 'grand_total', 'status', 'tax', 'package_charge', 'food_items', 'is_preorder', 'date', 'time_slot','rider_order','rider','payout','offer_type','offer_value','tax_amount','order_type'];

    public $appends = ['ticket_types_count','ticket_total_count','used_wallet_amount'];

    public function setPopular()
    {
        return json_decode($this->food_items, true);
    }

    public function restaurant()
    {
        return $this->hasOne('App\Models\Restaurants','id','res_id');
    }

    public function locationrestaurant()
    {
        return $this->hasOne('App\Models\Restaurants','id','location');
    }

    public function getUserDetails()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function getVendorDetails()
    {
        return $this->hasOne('App\Models\Chefs','id','vendor_id');
    }

    public function timeslotmanagement()
    {
        return $this->hasOne('App\Models\Timeslotmanagement','id','time_slot');
    }

    public function userinfo()
    {
        return $this->hasOne('App\Models\Customer','id','user_id')->select('id','name','email','mobile');
    }

    public function chefinfo()
    {
        return $this->hasOne('App\Models\Chefs','id','vendor_id')->commonselect();
    }

    public function getReviewinfoAttribute()
    {
        $data   = Review::where('order_id',$this->attributes['id'])->commonselect()->first();
        return ($data != '') ? $data : (object)[];
    }

    public function getUserAddrAttribute()
    {
        /*$data           = $this->order()->first();
        $userAddress    = '';
        if (!empty($data)) {
            $userAddress = $data->getUserAddress()->first()->address;
        }
        return $userAddress;*/
        if ( is_null($this->order->getUserAddress) )
            return '';
        else
            return $this->order->getUserAddress->address;
    }

    public function getUserAddrlatlangAttribute()
    {
        $latlang =array(
            'lat'   => $this->order->getUserAddress->lat,
            'lang'  => $this->order->getUserAddress->lang,
        );
        return $latlang;
    }

    public function order()
    {
        return $this->hasOne('App\Models\Order','id','order_id');
    }

    public function getBoyinfo()
    {
        return $this->hasOne('App\Models\Boy','id','boy_id');
    }

    public function getTimeSAttribute()
    {
        if($this->attributes['order_type'] != 'ticket' && $this->attributes['order_type'] != 'home_event_menu') {
            return Timeslotmanagement::where('id',$this->attributes['time_slot'])->first()->time_slot;
        }
        return '';
    }

    public function getTimeChefAttribute()
    {
        // return Timeslotmanagement::where('id',$this->attributes['time_slot'])->first()->time_slot;
        if($this->attributes['order_type'] != 'ticket' && $this->attributes['order_type'] != 'home_event_menu') {
            $menu = Menuitems::find($this->food_items[0]['id'],['id','preparation_time']);
            if($menu->preparation_time == '1_to_2hrs'){
                return Timeslotmanagement::where('id',$this->attributes['time_slot'])->first()->time_slot_chef_onehour;
            }
            return Timeslotmanagement::where('id',$this->attributes['time_slot'])->first()->time_slot_chef;
        }
        return '';
    }

    public function getCallBoyAttribute()
    {
        if ($this->date == date('Y-m-d') && $this->order_type != 'ticket' && $this->order_type != 'home_event_menu') {
            $time       = $this->getTimeSAttribute();
            $order_time = explode(' - ', $time)[0];
            $order_time = date("H:i", strtotime($order_time));
            $time1      = new \DateTime($order_time.':00');
            $time2      = new \DateTime();
            $interval   = $time1->diff($time2);
            $minutes    = minutes($interval->format('%h:%i'));
            return ($minutes <= 15 && $minutes > 0) ? true : false;
        }
        return false;
    }

    public function getFoodItemsAttribute()
    {
        $Food = json_decode($this->attributes['food_items'],true);
        $Food = array_map(function($e)
            { 
                if(isset($e['unit']) && $e['unit'] > 0){
                    $Menu = Menuitems::find($e['id'])->unit_detail;
                    $unit = array_search($e['unit'], array_column($Menu,'id'));
                    if($unit !== false){
                        $e['fPrice'] = $Menu[$unit]['price'];
                        $e['fdiscount_price'] = $Menu[$unit]['price'] - ((int)$Menu[$unit]['price'] * ((int)$Menu[$unit]['discount']/100));
                    } 
                } 
                if(!isset($e['fdiscount_price']) && !isset($e['discount'])) {
                     $e['discount'] = $e['fdiscount_price'] = 0; 
                } elseif(isset($e['fdiscount_price'])) {
                    $e['fdiscount_price'] = (double)$e['fdiscount_price'];
                }
                return $e; 
            },$Food);
        return $Food;
    }

    public function getFoodItemsCountAttribute()
    {
        $menuItems = json_decode($this->attributes['food_items'],true);
        $arr       = 0;
        $arr       = array_map(function($subarray){ return count($subarray['addon']) + $subarray['quantity']; },$menuItems);
        return array_sum($arr);
    }

    public function getFoodItemsjAttribute()
    {
        return json_decode($this->attributes['food_items'],true);
    }

    public function getBoyInfoAttribute()
    {
        return $this->attributes['boy_id']!='' ? Boy::where('id',$this->attributes['boy_id'])->first() : (object)[];
    }

    public function getOrderStatusAttribute()
    {
        if($this->attributes['status']=='pending') {
            $status = 'Pending';
        } elseif($this->attributes['status']=='cancelled') {
            $status = 'Automatically Cancelled';
        } elseif($this->attributes['status']=='accepted_res') {
            $status = 'Accepted by Chef';
        } elseif($this->attributes['status']=='accepted_admin') {
            $status = 'Accepted by Admin';
        } elseif($this->attributes['status']=='accepted_boy') {
            $status = 'Accepted by Delivery Partner';
        } elseif($this->attributes['status']=='food_ready') {
            $status = 'Food Prepared';
        } elseif($this->attributes['status']=='pickup_boy') {
            $status = 'Delivery Partner Picked';
        } elseif($this->attributes['status']=='reached_location') {
            $status = 'Reached Location';
        } elseif($this->attributes['status']=='completed') {
            $status = 'Delivered';
        } elseif($this->attributes['status']=='rejected_res') {
            $status = 'Rejected by Chef';
        } elseif($this->attributes['status']=='rejected_admin') {
            $status = 'Rejected by Admin';
        } elseif($this->attributes['status']=='rejected_cus') {
            $status = 'Cancelled';
        } elseif($this->attributes['status']=='reached_restaurant') {
            $status = 'Reached chef';
        } elseif($this->attributes['status']=='riding') {
            $status = 'Out for Delivery';
        } else {
            $status= 'Rejected by Delivery Partner';
        }
        return $status;
    }

    public function scopeCompleted($query)
    {
        return $query->where('status','completed');
    }

    public function getChefOrder()
    {
        return $this->hasMany('App\Models\Orderdetail','vendor_id','vendor_id')->whereHas('chefinfo');
    }

    public function rider_info()
    {
        return $this->hasOne(Boy::class,'id','rider');
    }

    public function review_info()
    {
        return $this->hasOne(Review::class,'order_id','id');
    }

    // public function review_infoorder()
    // {
    //     return $this->hasOne('App\Models\Orderdetail','id','order_id')->doesntHave('review_info');
    // }

    public function scopeCheckifreview($query)
    {
        return $query->has('review_info', 0);
    }

    public function cancellationData()
    {
        return $this->hasMany(Cancellation::class, 'order_id', 'id');
    }

    public function scopeCheckifretry($query)
    {
        return $query->has('timeretry', 0);
    }

    public function retries()
    {
        return $this->hasMany(DeliveryRetry::class, 'order_id', 'id');
    }

    public function timeretry()
    {
        return $this->retries()->whereRaw('ABS(TIMESTAMPDIFF(MINUTE, created_at, ?)) <= 3', [date('Y-m-d H:i:s')])->latest()->limit(1);
    }

    public function getDeliverylogCountAttribute()
    {
        return $this->deliverylog()->count();
    }

    public function deliverylog()
    {
       return $this->hasMany(DeliveryLog::class,'order_id','id');
    }

    public function getTicketTypesCountAttribute() 
    {   
        $ticket_types = '';
        if($this->order_type == "ticket") {
           $food = json_decode($this->attributes['food_items'],true);
           foreach($food as $key => $value){
                $tickets[] = $value['name'].'-'.$value['quantity'];
           }
           $ticket_types = implode(',',$tickets);            
        }
        return $ticket_types;
    }
    public function getTicketTotalCountAttribute() 
    {   
        $ticket_total = 0;
        if($this->order_type == "ticket") {
           $food = json_decode($this->attributes['food_items'],true);
           foreach ($food as $key => $value) {
               $ticket_total = $ticket_total + $value['quantity'];
           }
        }
        return $ticket_total;
    }
    public function getUsedWalletAmountAttribute()
    {
        return $this->order->used_wallet_amount;   
    }
}