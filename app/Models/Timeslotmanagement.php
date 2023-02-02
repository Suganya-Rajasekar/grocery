<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Traits\Log;
use App\Traits\Dynamic;

/**
 * Class category
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Timeslotmanagement extends Model
{
    use Dynamic;
    use Log;
    public $table = 'time_slot_management';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $appends = ['time_slot','time_slot_chef'];
    // public $fillable = [
    //     'id',
    //     'cat_id',
    //     'status',
    //     'start',
    //     'end',
    // ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'id'    => 'integer',
    //     'start'  => 'integer',
    //     'end'  => 'integer',
    // ];

    protected $hidden = [
        'created_at', 'updated_at', 'cat_id'
    ];

    public function timeSlotS()
    {
        return $this->hasOne('App\Models\Time','id','start');
    }

    public function timeSlotE()
    {
        return $this->hasOne('App\Models\Time','id','end');
    }

    public function getTimeSlotAttribute()
    {
       $s_time = $this->timeSlotS()->pluck('name')->first();
       $e_time = $this->timeSlotE()->pluck('name')->first();
        return $s_time.' - '.$e_time;
    }

    public function getTimeSlotChefAttribute()
    {
       $s_time = $this->timeSlotS()->pluck('name')->first();
       $e_time = $this->timeSlotE()->pluck('name')->first();
       $s_timestamp = strtotime($s_time) - 60*60;// 1 hrs less
       $e_timestamp = strtotime($e_time) - 60*60;
        return date('h:i A', $s_timestamp)/*.' - '.date('h:i A', $e_timestamp)*/;
    }

    public function getTimeSlotChefOnehourAttribute()
    {
       $s_time = $this->timeSlotS()->pluck('name')->first();
       $e_time = $this->timeSlotE()->pluck('name')->first();
       $s_timestamp = strtotime($s_time);
        return date('h:i A', $s_timestamp);
    }
}
