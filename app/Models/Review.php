<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Traits\Log;

/**
 * Class category
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Review extends Model
{
    use Log;
    public $table = 'reviews_rating';
    public $appends = ['day','comment_user','reply','vendorinfo'/*,'userinfo','vendorinfo'*/];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'created_at',
    ];

    public function newQuery($excludeDeleted = true) {
        if(\Auth::check() &&  \Auth::user()->role==3){
            return parent::newQuery($excludeDeleted)
            ->where('vendor_id', \Auth::user()->id);

        }else{
            return parent::newQuery($excludeDeleted)
            ->where('id', '>',0);
        }
    }

    public function reviewedUser()
    {
        return $this->hasOne('App\Models\User','id','created_by');
    }

    public function getUserDetails()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function getVendorDetails()
    {
        return $this->hasOne('App\Models\Chefs','id','vendor_id');
    }

    public function order()
    {
        return $this->hasOne('App\Models\Orderdetail','id','order_id');
    }

    public function scopeCommonselect($query)
    {
        $query->addSelect('id','order_id','vendor_id','res_id','user_id','reviews','rating','status');
    }

    public function scopeUser($query)
    {
        $query->where('created_by','!=',0);
    }

    public function getDayAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans(): '-';
    }

    public function getReplyAttribute()
    {
        return $this->replyinfo()->where('status','published')->get();
    }

    public function getPartnerreplyAttribute()
    {
        return $this->replyinfo()->get();
    }

    public function replyinfo()
    {
        return $this->hasMany(Review::class,'r_id','id')->select('id','reviews','status','vendor_id','user_id','created_by');
    }

    public function getCommentUserAttribute()
    {
        return $this->reviewedUser()->select('id','name','avatar')->first();
    }

    public function getUserinfoAttribute()
    {
        return $this->getUserDetails()->select('id','name','avatar')->first();
    }

    public function getVendorinfoAttribute()
    {
        return $this->getVendorDetails()->select('id','name','avatar')->first();
    }
}
