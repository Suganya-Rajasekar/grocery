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
class DeliveryLog extends Model
{
	public $table = 'delivery_log';
	public $timestamps = false;

    public function orderdetail()
    {
        return $this->belongsTo(Orderdetail::class, 'order_id', 'id');
    }
}
