<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Tds
 * @package App\Models
 * @version October 9, 2020, 7:06 am UTC
 *
 * @property string|\Carbon\Carbon $created_dt
 */
class Tds extends Model
{

    public $table = 'tds_certificate';

    public function chefDetails()
    {
    	return $this->hasOne('App\Models\Chefs', 'id', 'chef');
    }

}
