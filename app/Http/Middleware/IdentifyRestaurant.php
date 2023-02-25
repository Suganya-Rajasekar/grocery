<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Restaurants;

class IdentifyRestaurant
{
	/**
	* @var App\Models\Restaurants
	*/
	protected $restaurantManager;
	
	public function __construct(Restaurants $restaurantManager)
	{
		$this->restaurantManager = $restaurantManager;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$segment = $request->segment(3);
		print_r($segment);exit();
		return $next($request);
	}
}