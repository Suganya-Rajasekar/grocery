<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }
    protected $fillable = ['name', 'role', 'email', 'location_id', 'mobile', 'device', 'password', 'status', 'remember_token', 'mobile_otp', 'log_status', 'business_name', 'cuisine_type', 'slug','user_code', 'profile_name', 'reason','mobile_token','avatar','referal_code','referer_user_id','wallet'];

    protected $appends = ['role_id','orderscount','socket_subscribe_name','socket_room_name','room','ReferrerCode'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static $withoutAppends = false;

    public static $withAppends = false;

    public static $withAppendsData = [];

    public function scopeWithoutAppends($query)
    {
        self::$withoutAppends = true;

        return $query;
    }

    public function scopeWithAppends($query,$appends = array())
    {
        self::$withAppends = true;

        if (is_array($appends)) {
            self::$withAppendsData = $appends;
        }

        return $query;
    }

    protected function getArrayableAppends()
    {
        if (self::$withoutAppends){
            return [];
        }elseif (self::$withAppends) {
            return self::$withAppendsData;
        }

        return parent::getArrayableAppends();
    }


    /**
     * The Role as role_id - for api response.
     *
     * @var array
     */
    public function getRoleIdAttribute()
    {
        return $this->role;
    }

    /**
     * Accessor for cuisines property.
     *
     * @return array
     */
    public function getCuisineTypeAttribute()
    {
        $cuisines   = explode(',', $this->attributes['cuisine_type']);
        $return     = array();
        $return     = Cuisines::whereIn('id',$cuisines)->get();
        return $return;
    }

    /**
     * Mutator for cuisine_type property.
     *
     * @param  array|string $ids
     * @return void
     */
    public function setCuisineTypeAttribute($ids)
    {
        $this->attributes['cuisine_type'] = is_string($ids) ? $ids : implode(',', $ids);
        return $this->attributes['cuisine_type'];
    }

    public function address()
    {
        return $this->hasMany('App\Models\Address','user_id','id');
    }

    public function addons()
    {
        return $this->hasMany('App\Models\Addon','user_id')/*->select('id','name','user_id')*/;   
    }

    public function setAvatarAttribute($value)
    {   
        $this->attributes['avatar'] = $value;
        // $this->attributes['avatar'] = url( $value );
    }

    public function getAvatarpathAttribute()
    {
        return $this->attributes['avatar'];
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

    public function getReferrerCodeAttribute()
    {
        $code = 0;
        if($this->referer_user_id != 0) {
            $userData = User::find($this->referer_user_id,['id','referal_code']);
            $code = $userData->referal_code;
        }
        return $code;
    }

    public function getReferrerDetailAttribute()
    {
        $data = '';
        if($this->referer_user_id != 0) {
            $data = User::find($this->referer_user_id,['id','name','email','mobile','created_at']);
        }
        return $data;
    }

    public function getOrderscountAttribute()
    {
        return $this->Orders()/*->where('status','completed')*/->count();
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


    public function getBookmarks()
    {
        return $this->hasMany('App\Models\Bookmarks','user_id','id')->whereHas('getVendorDetails');
    }

    public function getFavourites()
    {
        return $this->hasMany('App\Models\Favourites','user_id','id')->whereHas('getVendorDetails');
    }

    /**
    * Get the notifications that came for the user.
    */
    public  function notifications()
    {
        return $this->hasMany(Notification::class, 'to', 'id');
    }

    /**
    * Get the sendnotifications that came for the user.
    */
    public  function sendnotifications()
    {
        return $this->hasMany(Notification::class, 'from', 'id');
    }

    public function Orders()
    {
        return $this->hasMany('App\Models\Orderdetail','user_id','id');
    }

    public function userChatMessage()
    {
        return $this->hasMany('App\Models\Customerchat','user_id','id');
    }

}
