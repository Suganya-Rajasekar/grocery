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
class SettingsBoyApi extends Model
{
    use Log;
    public $table = 'settings_boyapis';

    public $fillable = [
        'upto4', 'more4','amount'
    ];

}
