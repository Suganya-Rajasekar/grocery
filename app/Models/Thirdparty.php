<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Thirdparty
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Thirdparty extends Model
{

    public $table = 'thirdparty_hooks';
    public $timestamps = false;

}
