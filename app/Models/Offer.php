<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Models\Location;
use App\Traits\Log;
use App\Traits\FindInSetRelationTrait;
/**
 * Class Offer
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Offer extends Model
{
    use Log,FindInSetRelationTrait;
    public $table       = 'promo_offer';
    const IMAGE_PATH    = 'storage/app/public/offers/';

    public $fillable    = [
        'name', 'image', 'offer', 'restaurant', 'start_date', 'end_date', 'status', 'promo_code', 'promo_desc', 'promo_type', 'usage_limit', 'user_limit', 'min_order_value', 'max_discount', 'loc_status', 'res_status', 'location'
    ];

    public $appends = ['offer_text'];

    public function scopeCommonselect($query)
    {
        $query->addSelect('id','location','restaurant','name','promo_code','promo_desc','image','promo_type','offer','min_order_value');
    }

    public function scopeActive($query)
    {
		$now = date('Y-m-d');
        return $query->where('status','1')->where('start_date','<=',$now)->where('end_date','>=',$now);
    }

    /**
     * Scope for Filtering Chefs with tags.
     */
    public function scopeFilter($query,$relation,$fieldname,$tag)
    {
        return $query->withCount([$relationLoaded => function (Builder $squery) use($tag,$fieldname){
            $squery->nearby()->findinset($fieldname,$tag);
        }])->having($relation.'_count', '>' , 0);
    }

    /**
     * Scope for FindInSet Regex.
     */
    public function scopeFindinset($query,$field,$keyword)
    {
        if(!is_array($keyword)){ $keyword = explode(',', $keyword); }
        return $query->whereRaw($field.' REGEXP("('.implode('|',$keyword).')")');
    }

    public function scopeFindchefs($query,$field,$keyword)
    {
        return $query->where('res_status','selected')->findinset($field,$keyword);
    }

    public function scopeFindlocations($query,$field,$keyword)
    {
        return $query->where('loc_status','selected')->findinset($field,$keyword);
    }
    
    public function getImageAttribute()
    {
        return ValidateImage($this::IMAGE_PATH , $this->attributes['image']);
    }
    public function getOfferTextAttribute()
    {
        $attributes = $this->attributes;
        $type       = $attributes['promo_type'];
        $value      = $attributes['offer'];
        $name       = $attributes['name'];

        $text = '';

        if ($type == 'amount') {
            $text = 'Get Flat '.$value. '₹ OFF';
            // $text .= ($this->attributes['min_order_value'] > 0) ? ' up to ₹'.$this->attributes['min_order_value'] : '' ;
        } elseif ($type == 'percentage') {
            $text = 'Get '.$value.'% OFF';
            // $text .= ($this->attributes['min_order_value'] > 0) ? ' up to ₹'.$this->attributes['min_order_value'] : '' ;
        }
        return $text;
    }

    public function locations()
    {
        return $this->FindInSetMany( Location::class, 'location', 'id');
    }

    public function coupon()
    {
        return $this->hasMany(Order::class,'coupon_id','id');
    }

}
