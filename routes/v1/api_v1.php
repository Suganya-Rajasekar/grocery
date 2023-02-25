<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Middleware\IdentifyRestaurant;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware'=>'api', 'prefix'=>'{res_name}'/*,'middleware'=>\App\Http\Middleware\IdentifyRestaurant::class*/],function() {
	Route::get('home',[HomeController::class, 'homepage']);
	Route::get('categoryList',[HomeController::class, 'categoryList']);
	Route::get('searchProducts',[HomeController::class, 'searchProducts']);
});

