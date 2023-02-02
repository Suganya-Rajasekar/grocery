<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Traits\Log;

/**
 * Class Offer
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Customerchat extends Model
{
    use Log;
    public $table = 'customer_chat';

    protected $maps =['message' => 'body'];

    protected $appends = ['body'];

    protected $hidden = ['message'];

    public function getBodyAttribute()
    {
      return $this->attributes['message'];
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
