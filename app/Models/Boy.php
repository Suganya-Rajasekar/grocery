<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Translate;
use App\Models\Addon;

/**
 * Class category
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Boy extends Model
{

    public $table = 'delivery_boy';
    
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public static function findOrCreate($name,$mobile_number,$rider='dunzo')
    {
        $obj = static::where('third_part',$rider)->where('name',$name)->where('mobile_number',$mobile_number)->first();
        if(empty($obj)){
        	$obj  = new static;
        	$obj->third_part = strtolower($rider);
        	$obj->name = $name;
        	$obj->mobile_number = $mobile_number;
        	$obj->save();
        }
        return $obj;
    }

}
