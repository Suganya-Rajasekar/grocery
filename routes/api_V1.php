<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Emperica\CartController;


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

Route::group(['middleware'=>'api'/*,'middleware'=>'authcheck'*/],function() { 
	Route::any('cart',[CartController::class, 'addCart']);
	Route::DELETE('delcart',[CartController::class, 'delCart']);
});

