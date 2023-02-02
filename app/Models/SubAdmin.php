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
class SubAdmin extends Model
{
    use Log;
    public $table = 'users';

    public $fillable = [
        'name'
    ];

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
        ->where('role', 5);
    }
    
    public function getAccessAttribute()
    {
        $res = UserModule::select('access')->where('user_id',$this->id)->first();
        return !empty($res) ? json_decode($res->access): '';
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
}
