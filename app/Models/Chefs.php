<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Models\Location;
use App\Models\Addon;
use File, App;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Log;

use App\Http\Libraries\EloquentEagerLimit\HasEagerLimit;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class category
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Chefs extends Model
{
    use Log,HasFactory, HasEagerLimit;
    public $table = 'users';

    protected $fillable = [
        'name','profile_name', 'email', 'password','role','location_id', 'mobile', 'cuisine_type','status','user_code','type','individual_email_1','individual_email_2'
    ];

    public $appends = ['cuisines','reviewscount','ratings','avatar','is_bookmarked','documentverify','chef_description'/*,'available_offer_text_arr'*/,'socket_subscribe_name','socket_room_name','room','is_notify','event_location','event_time'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'        => 'integer',
        'name'      => 'string',
        'created_dt'=> 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name'      => 'required|string|max:70',
        'updated_at'=> 'nullable',
        'deleted_at'=> 'nullable'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function newQuery($excludeDeleted = true) {
        return parent::newQuery($excludeDeleted)
        ->where('role', 3);
    }

    public function scopePending($query)
    {
        $query->where('status','pending');
    }

    public function scopeApproved($query)
    {
        $query->where('status', 'approved');
    }

    public function scopeNotpending($query)
    {
        $query->where('status','!=', 'pending');
    }

    public function scopeCelebrity($query)
    {
        $query->where('celebrity','yes');
    }

    public function scopePopular($query)
    {
        $query->where('celebrity','no');
    }

    public function scopeNotpromoted($query)
    {
        $query->where('promoted','no');
    }

    public function scopePromoted($query)
    {
        $query->where('promoted','yes');
    }
    public function scopeCertified($query)
    {
        $query->where('certified','yes');
    }

    public function scopeNearby($query,$latitude=0.00,$longitude=0.00)
    {
        $lat_lng        = " ( round(
          ( 6371 * acos( least(1.0,
            cos( radians(".$latitude.") )
            * cos( radians(latitude) )
            * cos( radians(longitude) - radians(".$longitude.") )
            + sin( radians(".$latitude.") )
            * sin( radians(latitude)
          ) ) )
        ), 2) ) ";
        $query = $query->whereHas('restaurants', function ($query) use ($lat_lng) {
            $query->whereRaw($lat_lng . '<= '.getMaxDistance());
        });
        return $query;
    }

    public function scopeHaveinfo($query)
    {
        $query->has('restaurants', '>' ,'0');
    }

    public function scopeAvail($query)
    {
        $query->has('getFirstRestaurant','>','0');
    }

    public function scopeNotavail($query)
    {
        $query->where('avalability','not_avail');
    }
    /**
     * Scope for Filtering Chefs with tags.
     */
    public function scopeFilter($query,$relation,$fieldname,$tag)
    {
        return $query->withCount([$relation => function (Builder $squery) use($tag,$fieldname){
            $squery->findinset($fieldname,$tag);
        }])->having($relation.'_count', '>' , 0);
    }

    /**
     * Scope for FindInSet Regex.
     */
    public function scopeFindinset($query,$field,$keyword)
    {
        if(!is_array($keyword)){ $keyword = explode(',', $keyword); }
        //return $query->whereRaw($field.' REGEXP("('.implode('|',$keyword).')")');
        return $query->whereRaw($field.' REGEXP("[[:<:]]'.implode('|',$keyword).'[[:>:]]")');
        
    }

    public function scopeCommonselect($query)
    {
        $query->addSelect('id','role','status','profile_name as name','avatar','cuisine_type','celebrity','promoted','certified');
    }

    public function scopeAccountselect($query)
    {
        $query->addSelect('id','role','email','name','rayzorpay_id');
    }

    public function scopeToprated($query)
    {
        $query->has('toprated', '>' ,'0')->withCount('toprated')->orderBy('toprated_count', 'desc');
    }

    public function scopeHavemenus($query)
    {
        $query->has('food_items', '>' ,'0');
    }

    public function scopeHavetickets($query)
    {
        $query->has('event_fooditems','>','0');
    }

    public function getAvalabilityAttribute()
    {
        $return         = 'not_avail';
        $cdateTime      = strtotime(date('Y-m-d H:i:s'));
        $time           = date('H:i:s');
        $resData        = $this->getFirstRestaurant()->available()->first();
        if($this->type == "event") {
            $resData        = $this->getFirstRestaurant()->eventavailable()->first();
        } elseif ($this->type == "home_event") {
            $resData        = $this->getFirstRestaurant()->homeeventavailable()->first();
        }
        if (!empty($resData)) {
            $return      = 'avail';
        }
        return $return;
    }

    public function getChefLocalityAttribute()
    {
        $resData     = $this->getFirstRestaurant()->first(['locality']);
        if (!empty($resData)) {
            return (($resData->locality != '')) ? $resData->locality : '' ;
        }
        return '';
    }

    public function getChefDescriptionAttribute()
    {
        $resData     = $this->getFirstRestaurant()->first(['description']);
        if (!empty($resData)) {
            return (($resData->description != '')) ? $resData->description : '' ;
        }
        return '';
    }

    public function getEventLocationAttribute()
    {
        $resData     = $this->singlerestaurant()->first(['adrs_line_1','type']);
         if (!empty($resData) && $resData->type == 'event') {
            return (($resData->adrs_line_1 != '')) ? $resData->adrs_line_1 : '' ;
        }
        return '';
    }

    public function getEventDatetimeAttribute()
    {
        $resData     = $this->singlerestaurant()->first();
        if (!empty($resData) && $resData->type == 'event') {
            return (($resData->event_datetime != '')) ? date('d-m-Y h:i A',strtotime($resData->event_datetime)) : '' ;
        }
        return '';
    }

    public function getEventTimeAttribute()
    {
        $resData     = $this->singlerestaurant()->first();
        if (!empty($resData) && $resData->type == 'event') {
            return (($resData->event_datetime != '')) ? date('d-m-Y h:i A',strtotime($resData->event_datetime)) : '' ;
        }
        return '';
    }

    public function getDocumentVerifyAttribute()
    {
        $return = 'no';
        $resData     = $this->getDocument()/*->whereRaw('MONTH(updated_at) = MONTH(CURDATE())')*/->first();
        if (!empty($resData)) {
            if($resData->on_boarding_form!='' && $resData->enrollment_form!='' && $resData->pan_card!='' && $resData->gst_certificate!='' && $resData->aadar_image!='' && $resData->fssai_certificate!='' && $resData->cancelled_cheque!='' && $resData->address_proof!=''){
              $return ='yes';  
            }
        }
        return $return;
    }

    public  function getAvailableOfferAttribute()
    {
        $chefId     = $this->attributes['id'];
        $locations  = $this->locations;
        $locationsId= array();
        if (!empty($locations)) {
            $locationsId = $locations->pluck('id');
        }

        $offers = Offer::active()
        ->where(function($checkRes) {
            $checkRes->where(function($resQuery) use ($chefId) {
                $resQuery->where('res_status','selected')->whereIn('restaurant',[$chefId]);
            })
            ->orWhere('res_status','all');
        })->where(function($checkLoc) use($locationsId) {
            $checkLoc->where(function($locQuery) use($locationsId) {
                $locQuery->where('loc_status','selected')->whereIn('location',[$locationsId]);
            })
            ->orWhere('loc_status','all');
        })->commonselect()->get();
        return $offers;
    }

    public  function getAvailableOfferTextArrAttribute()
    {
        $offerTextArr = array();
        $offer = $this->getAvailableOfferAttribute();
        if (!empty($offer)) {
            $offerTextArr = $offer->pluck('offer_text');
        }
        return $offerTextArr;
    }


    /**
    * Get the restaurants that owned by a vendor (chef).
    */
    public  function restaurants()
    {
        return $this->hasMany(Restaurants::class, 'vendor_id', 'id')/*->approved()->available()*/;
    }

    public  function offers()
    {
        return $this->hasMany(Restaurants::class);
    }

    public  function locations()
    {
       return $this->hasManyThrough(Location::class,Restaurants::class,'vendor_id','id','id','location');
    }

    public  function getDocument()
    {
        return $this->hasOne(UserDocuments::class, 'user_id', 'id');
    }

    public  function getFirstRestaurant()
    {
        return $this->singlerestaurant()->approved()/*->available()*/;
    }
    public  function getChefRestaurant()
    {
        return $this->singlerestaurant()->approved();
    }

    public  function ChefRestaurant()
    {
        return $this->singlerestaurant()->approved()/*->available()*/;
    }

    public function singlerestaurant()
    {
        return $this->hasOne(Restaurants::class, 'vendor_id', 'id');
    }

    public  function getApprovedRestaurant()
    { 
        return $this->getFirstRestaurant()->approved();
    }

    /**
     * Accessor for cuisines property.
     *
     * @return array
     */
    public function getCuisinesAttribute()
    {
        $cuisines   = explode(',', $this->cuisine_type);
        $return     = array();
        $return     = Cuisines::select('id','name')->whereIn('id',$cuisines)->get();
        return $return;
    }

    public function getChefaddress($res_id)
    {
        $address = '';
        if($res_id != ''){
            $restaurant = Restaurants::select('id','adrs_line_1')->where('id',$res_id)->first();
            if(!empty($restaurant)){
                $address = $restaurant->adrs_line_1;
            }
        }
        return $address;
    }

    public function getChefBudget($res_id)
    {
        $budget = '';
        if($res_id != ''){
            $restaurant = Restaurants::select('id','budget')->where('id',$res_id)->first();
            if(!empty($restaurant)){
                $budget = $restaurant->budget_name;
            }
        }
        return $budget;
    }

    public function getRatingsAttribute()
    {
       $count       = $this->getTotalreviewscountAttribute();
       $ratingSum   = $this->publishedreviews()->sum('rating');
       return ($count>0 && $ratingSum>0) ? round($ratingSum/$count) : 0 ;
    }

    public function getReviewscountAttribute()
    {
        return $this->publishedreviews()->where('created_by','!=',0)->count();
    }

    public function getTotalreviewscountAttribute()
    {
        return $this->publishedreviews()->count();
    }

    public function chefratings()
    {
       return $this->hasMany(Review::class,'vendor_id','id')->where('r_id',0)->has('reviewedUser');
    }

    public function chefratingOrder()
    {
       return $this->hasOne(Review::class,'vendor_id','id')->where('r_id',0);
    }

    public function publishedreviews()
    {
       return $this->chefratings()->where('status','published');
    }

    public function toprated()
    {
       return $this->publishedreviews()->where('rating','>=',4);
    }    

    public function getAvatarAttribute()
    {
        $path   = 'storage/app/public/avatar/'.$this->attributes['avatar'];
        if ($this->attributes['avatar'] != '' && \File::exists(base_path($path))) {
            $url    = \URL::to($path);
        } else {
            $url    = getCommonImageUser();
        }
        return $url;
    }

    /*public function isBookmarkedOld($chefId, $userId)
    {
        $b_id = 0; 
        if($chefId != '' && $userId != ''){
            $bookmark = Bookmarks::select('id')->where('vendor_id',$chefId)->where('user_id',$userId)->first();
            if(isset($bookmark->id) ){
                $b_id = 1;
            }
        }
        return $b_id;
    }*/

    public function getIsBookmarkedAttribute()
    {
        $b_id = $user_id = 0;
        $userData   = App::call('App\Http\Controllers\Api\Customer\CustomerController@me');
        if (!empty($userData)) {
            $user_id    = $userData->id;
        }
        if($this->id != '' && $user_id != 0) {
            $bookmark  = Bookmarks::select('id')->where('vendor_id',$this->id)->where('user_id',$user_id)->exists();
            if($bookmark) {
                $b_id   = 1;
            }
        }
        return $b_id;
    }

    public function getIsNotifyAttribute()
    {
        $res = $user_id = $id = 0;
        $userData   = App::call('App\Http\Controllers\Api\Customer\CustomerController@me');
        if (!empty($userData)) {
            $user_id    = $userData->id;
        }
        if($this->id != '' && $user_id != 0) {
            $notify     = Notifyme::where('user_id',$user_id)->where('vendor_id',$this->id)->where('status',"0")->first();
            $res   = (!empty($notify)) ? 1 : 0;
        }
        $return['id']      = (!empty($notify)) ? $notify->id : 0;
        $return['status']  = $res;
        return $return;
    }

    public function getVendorFoodDetails()
    {
        return  $this->hasMany('App\Models\Menuitems','vendor_id','id')->approved()->instock()->has('categories')->limititem(10);
    }

    public function food_items()
    {
        return  $this->hasMany('App\Models\Menuitems','vendor_id','id')->approved()->instock()->has('categories');
    }

    public function active_offers()
    {
        return  $this->hasMany('App\Models\Offer','restaurant','id')->orwhereRaw('FIND_IN_SET(`id`,`restaurant`)');
    }

    public function getOrders()
    {
        return $this->hasMany(Orderdetail::class,'vendor_id','id');
    }

    public function rzaccount()
    {
        return $this->hasOne(RzAccount::class,'chef','id');
    }

    public function rzaccount_active()
    {
        return $this->hasOne(RzAccount::class, 'chef', 'id')->approvedcontact()->approvedfund();
    }

    public function getSocketSubscribeNameAttribute()
    {
        return base64_encode($this->attributes['id']);
    }

    public function getSocketRoomNameAttribute()
    {
        return base64_encode($this->attributes['id']);
    }

    public function getRoomAttribute()
    {
        return base64_encode($this->attributes['id']);
    }

    public function event_fooditems() 
    {
        return $this->hasMany('App\Models\Menuitems','vendor_id','id')->approved();
    }
}
