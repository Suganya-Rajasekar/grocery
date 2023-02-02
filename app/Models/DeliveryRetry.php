<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class DeliveryRetry
 * @package App\Models
 * @version October 30, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class DeliveryRetry extends Model
{

    public $table = 'delivery_retry';
    public $timestamps = false;

}
