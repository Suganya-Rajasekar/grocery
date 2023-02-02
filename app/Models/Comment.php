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
* Class Comment
* @package App\Models
* @author Roja
* @version
*/
class Comment extends Model
{
    use Log;
    public $table   = 'menu_comment';
    public $appends = ['day','like_count','like_count_text','reply_count','reply_count_text','likeinfo','replyinfo','userinfo'];

    public function newQuery($excludeDeleted = true) {
        if(\Auth::check() &&  \Auth::user()->role == 3){
            return parent::newQuery($excludeDeleted)
            ->where('vendor_id', \Auth::user()->id);
        } else {
            return parent::newQuery($excludeDeleted)
            ->where('id', '>',0);
        }
    }

    public function scopePublished($query)
    {
        $query->where('status','published');
    }

    public function scopeComment($query)
    {
        $query->where('c_id',0);   
    } 

    public function getDayAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans(): '-';
    }

    public function getLikeCountAttribute()
    {
        return $this->likeinfo()->count();
    }

    public function getLikeCountTextAttribute()
    {
        $text   = '';
        $lcount = $this->getLikeCountAttribute();
        if ($lcount == 1) {
            $text = $lcount.' Like';
        } elseif ($lcount > 0) {
            $text = $lcount.' Likes';
        }
        return $text;
    }

    public function getReplyCountAttribute()
    {
        return $this->comments()->published()->count();
    }

    public function getReplyCountTextAttribute()
    {
        $text   = '';
        $rcount = $this->getReplyCountAttribute();
        if ($rcount == 1) {
            $text = 'View Reply ('.$rcount.')';
        } elseif ($rcount > 0) {
            $text = 'View Replies ('.$rcount.')';
        }
        return $text;
    }

    public function getLikeinfoAttribute()
    {
        return $this->likeinfo()->get();
    }

    public function getReplyinfoAttribute()
    {
        return $this->comments()->addSelect('id','comment','status','user_id')->published()->get();
    }

    public function getUserinfoAttribute()
    {
        return $this->getUserDetails()->addSelect('id','name','avatar')->first()/*->makeHidden('role_id')*/;
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment','c_id','id');
    }

    public function likeinfo()
    {
        return $this->hasMany('App\Models\Commentlike','c_id','id');
    }

    public function getUserDetails()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function userdetails()
    {
        return $this->getUserDetails()->select('id','name','avatar');
    }

    public function getFoodDetails()
    {
        return $this->hasOne('App\Models\Menuitems','id','food_id');
    }

    public function getReply()
    {
        return $this->hasOne('App\Models\Comment','id','c_id');
    }

    public function scopeRoot($query)
    {
        $query->where('c_id',0)->where('status','published');
    }
}
