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
class Customer extends Model
{
    use Log;
    public $table = 'users';

    public $fillable = [
        'name'
    ];
    protected $appends = ['role_id','orderscount','usercity','socket_subscribe_name','socket_room_name'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'created_dt' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:70',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function newQuery($excludeDeleted = true) {
        return parent::newQuery($excludeDeleted)
        ->where('role', 2);
    }
    public function scopeLogintype($query,$value)
    {
        $query->where($value.'_id','!=','');
    }
    public function getOrderscountAttribute()
    {
        return $this->Orders()/*->where('status','completed')*/->count();
    }
    public function Orders()
    {
        return $this->hasMany('App\Models\Orderdetail','user_id','id')->whereHas('chefinfo')->whereHas('userinfo');
    }
    public function getUsercityAttribute()
    {
        return $this->Useraddress()->where('address_type','home')->first();
    }
    public function Useraddress()
    {
        return $this->hasMany('App\Models\Address','user_id','id')->whereHas('getUserDetails');
    }
    public function getRoleIdAttribute()
    {
        return $this->role;
    }
    public function getSocketSubscribeNameAttribute()
    {
        return base64_encode($this->attributes['id']);
    }
    public function getSocketRoomNameAttribute()
    {
        return base64_encode($this->attributes['id']);
    }
    public function getLastorderDateAttribute()
    {
        return $this->Orders()->orderByDesc('created_at')->first();
    }
}
