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

$log = ['URI' => \Request::fullUrl(),'REQUEST_BODY' => \Request::all(),'HEADER' => \Request::header(),];
 \DB::table('tbl_http_logger')->insert(array('request'=>'API_CALL','header'=>json_encode($log)));

Route::group(['middleware'=>'api'/*,'middleware'=>'authcheck'*/],function() { 
	Route::any('cart',[CartController::class, 'addCart']);
	Route::DELETE('delcart',[CartController::class, 'delCart']);
});

