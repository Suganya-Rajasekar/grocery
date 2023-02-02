<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * Class SiteSetting
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class SiteSetting extends Model
{
    public $table = 'site_details';

}
