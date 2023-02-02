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
 * @author Roja
 * @version Mar 2021
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Order extends Model
{
    public $table       = 'orders';
    protected $appends  = ['address'];
    protected $fillable = [ 'user_id', 'mobile_number', 'total_food_amount', 'vendor_price', 'commission_amount', 'del_charge', 'grand_total', 'address_id', 'payment_status', 'payment_type', 'payment_token', 'online_pay_status','coupon_id','order_type','used_wallet_amount','wallet_history_id'];

    public function scopeAll($query)
    {
        // $query->where('id','title','description','created_at');
    }

    public function getUserDetails()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    } 
   
    public function getUserAddress()
    {
        return $this->hasOne('App\Models\Address','id','address_id');
    }

    public function Orderdetail()
    {
        return $this->hasMany('App\Models\Orderdetail','order_id','id')->with('rider_info')->whereHas('chefinfo')->withCount('retries');
    }

    public function getAddressAttribute()
    {
        return $this->hasMany('App\Models\Address','id','address_id')->pluck('address')->first();
    }

    public function Customerorderdetail()
    {
        return $this->hasMany('App\Models\Orderdetail','user_id','user_id');
    }

    public function vendorOrderDetail()
    {
        return $this->hasMany('App\Models\Orderdetail','vendor_id','vendor_id');
    }

    public function getOrderCountAttribute()
    {
    	return $this->Orderdetail()->count();
    }

    public function getCustomerOrderCountAttribute()
    {
        return $this->Customerorderdetail()->count();
    }

    public function getVendorOrderCountAttribute()
    {
        return $this->vendorOrderDetail()->count();
    }

    public function getchefnamesAttribute()
    {
        $chef_data = $this->Orderdetail()->with('getVendorDetails')->get();
        $chef_names = [];
        foreach ($chef_data as $key => $value) {
            $chef_names[] = $value->getVendorDetails['name'];
        }
        $chefs = implode(' | ',array_unique($chef_names));
        return $chefs;
    }

    public function getDeliveryTimeslotsAttribute()
    {
        $alltimes = '';
        if($this->attributes['order_type'] != "ticket" && $this->attributes['order_type'] != "home_event_menu") {            
            $deliverytime = $this->Orderdetail()->with('timeslotmanagement')->get();
            foreach($deliverytime as $key => $value){
                $dtime[] = date('d-M-Y',strtotime($value->date)). ' - ' .$value->timeslotmanagement->time_slot;
            }
            $alltimes = implode(' | ', $dtime);
        }
        return $alltimes;
    }

    public function getTimeslotAttribute()
    {
        $alltimes = '';
        if($this->attributes['order_type'] != "ticket" && $this->attributes['order_type'] != "home_event_menu") {            
            $deliverytimeslot = $this->Orderdetail()->with('timeslotmanagement')->get();
            foreach($deliverytimeslot as $key => $value){
                $dtime[] = $value->timeslotmanagement->time_slot;
            }
            $alltimes = implode(' | ', $dtime);
        }
        return $alltimes;
    } 

    public function promo()
    {
        return $this->hasOne(Offer::class,'id','coupon_id');
    }

    public function orderdetailchef() 
    {
        return $this->hasMany('App\Models\Orderdetail','order_id','id')->with('chefinfo');
    }


}
