<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Cancellation
 * @package App\Models
 * @version September 20, 2021, 06:22 pm UTC
 */
class Cancellation extends Model
{
	public $table		= 'cancellation_details';
	const CREATED_AT	= 'created_at';
	const UPDATED_AT	= 'updated_at';

	public $fillable = [
		'order_id',
		'chef_penalty',
		'customer_penalty',
		'sub_order_id'
	];

	public function order_detail()
	{
		return $this->hasOne(Orderdetail::class, 'id', 'order_id');
	}
}
