<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Emperica\EmpericaController;
use App\Http\Controllers\Api\Partner\PartnerController;
use App\Http\Controllers\Api\Customer\CustomerController;
use App\Http\Controllers\Api\Emperica\CartController;
use App\Http\Controllers\Api\Order\OrderController;
use App\Http\Controllers\Api\Rider\DunzoController;
use App\Http\Controllers\Api\Rider\ShadowController;
use App\Http\Controllers\Api\Razor\PayoutsController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\Google\GoogleController;


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
 
Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

Route::group(['middleware'=>'api', 'prefix'=>'api'/*, 'namespace'=>'Api\Emperica'*/], function () {
	Route::post('login', [AuthController::class, 'login']);
	Route::post('register', [AuthController::class, 'register']);
	Route::post('logout', [AuthController::class, 'logout']);
	Route::post('deactivate', [AuthController::class, 'deactivate']);
	Route::post('verifyotp',[AuthController::class, 'verifyOTP']);
	Route::post('forgetpasswordrequest',[AuthController::class, 'forgetPasswordrequest']);
	Route::post('resetpassword',[AuthController::class, 'resetPassword']);
	Route::post('sociallogin',[AuthController::class, 'socialLogin']);
	Route::post('userprofile',[AuthController::class, 'userProfile']);
	Route::post('profileupdate',[AuthController::class, 'updateCustomerProfile']);
	Route::post('refresh', [AuthController::class, 'refresh']);
	Route::post('me', [AuthController::class, 'me']);
	Route::get('datas',[EmpericaController::class, 'TableDatas']);
	Route::get('test',[EmpericaController::class, 'sendOrderMail']);


	Route::any('offers',[EmpericaController::class, 'Offers']);
	Route::get('alldatas',[EmpericaController::class, 'TableDatasAll']);
	Route::get('search',[CustomerController::class, 'search']);
	Route::get('explore',[CustomerController::class, 'Explore']);
	Route::get('sendrequesttoboy',[OrderController::class, 'sendRequestToBoy']);
	Route::post('Webhook/RazorpayX',[PayoutsController::class, 'Webhook']);
	Route::post('Webhook/Razorpay',[OrderController::class, 'RazorpayWebhook']);
	Route::post('Webhook/{dealer}',[OrderController::class, 'DeliveryPartnerWebhook']);
	/*Route::match(['get', 'post', 'patch', 'put'], 'orders', [
		'uses'	=> 'App\Http\Controllers\Api\Emperica\EmpericaController@orders',
		'as'	=> 'orders',
	]);*/
});

Route::group([/*'namespace'=>'Api\Partner',*/'middleware'=>'api', 'middleware'=>'auth:api','prefix'=>'api'],function() {
	Route::get('partner_categories', [PartnerController::class, 'categories']);
	Route::get('partner_addons', [PartnerController::class, 'addons']);
	Route::get('insights', [PartnerController::class, 'insights']);
	Route::post('schedule', [PartnerController::class, 'schedule']);
	Route::post('notifyme', [CustomerController::class, 'notifyme']);
	Route::DELETE('notifyme',[CustomerController::class, 'notifyme']);
	Route::get('blastnotication', [PartnerController::class, 'blastNotification']);
	Route::match(['get', 'post', 'patch'/*, 'put'*/,'delete'], 'reviews', [
		'uses'	=> 'App\Http\Controllers\Api\Order\OrderController@reviews',
		'as'	=> 'review_rating',
		// 'roles'=> 'HomeController@useroles',
	]);

	Route::match(['get', 'post'], 'availabilty', [
		'uses'	=> 'App\Http\Controllers\Api\Partner\PartnerController@availabilty',
		'as'	=> 'availabilty'
	]);
	Route::match(['get', 'post'], 'unavailabiltyDays', [
		'uses'	=> 'App\Http\Controllers\Api\Partner\PartnerController@workingDays',
		'as'	=> 'unavailabiltyDays'
	]);

	Route::match(['get', 'post'], 'partner_documents', [
		'uses'	=> 'App\Http\Controllers\Api\Partner\PartnerController@documents',
		'as'	=> 'partner_documents'
	]);

	Route::match(['get', 'post', 'patch', 'put'], 'partner_info', [
		'uses'	=> 'App\Http\Controllers\Api\Partner\PartnerController@vendorData',
		'as'	=> 'vendorinfo',
		// 'roles'=> 'HomeController@useroles',
	]);
	Route::match(['get', 'post', 'patch', 'put'], 'partner_menus', [
		'uses'	=> 'App\Http\Controllers\Api\Partner\PartnerController@menusData',
		'as'	=> 'vendormenus',
		// 'roles'=> 'HomeController@useroles',
	]);
	Route::match(['get', 'post', 'patch', 'put'], 'menu_comment', [
		'uses'	=> 'App\Http\Controllers\Api\Customer\CustomerController@menuComment',
		'as'	=> 'menucomment',
	]);
	Route::match(['get', 'post', 'patch', 'put'], 'orders', [
		'uses'	=> 'App\Http\Controllers\Api\Order\OrderController@orderData',
		'as'	=> 'orders',
	]);
});

Route::group(['middleware'=>'api','prefix'=>'api'/*,'middleware'=>'authcheck'*/],function() {
	Route::get('webview/{page}',[CustomerController::class, 'webview']);
	Route::get('homepage',[CustomerController::class, 'homepage']);
	Route::get('home',[HomeController::class, 'homepage']);
	Route::get('categoryList',[HomeController::class, 'categoryList']);
	Route::get('searchProducts',[HomeController::class, 'searchProducts']);
	Route::get('populardishes',[CustomerController::class, 'popularDishes']);
	Route::get('chefinfo',[CustomerController::class, 'chefinfo']);
	Route::get('chefinfonew',[CustomerController::class, 'chefinfonew']);
	Route::get('chefcategories',[CustomerController::class, 'chefCategories']);
	Route::get('menuinfo',[CustomerController::class, 'menuinfo']);
	Route::get('popularrecipe',[CustomerController::class, 'getPopularRecipe']);
	Route::get('blog',[CustomerController::class, 'getBlog']);
	Route::get('menuslots',[CustomerController::class, 'menuSlotCheck']);

	Route::any('cart',[CartController::class, 'addCart']);
	Route::DELETE('delcart',[CartController::class, 'delCart']);
	Route::get('cartcount',[CartController::class, 'cartCount']);
	Route::PATCH('applywallet',[EmpericaController::class,'WalletAmountApply']);
	/*Route::match(['get', 'post', 'patch', 'put'], 'cart', [
		'uses'	=> [CartController::class, 'addCart'],
		'as'	=> 'cartApis',
		// 'roles'=> 'HomeController@useroles',
	]);*/
});
Route::group(['middleware'=>'auth:api'],function() {
	Route::get('userbookmarks', [CustomerController::class, 'userbookmarks']);
	Route::post('updatebookmark', [CustomerController::class, 'updatebookmark']);
	Route::post('userfavourites', [CustomerController::class, 'userfavourites']);
	Route::post('updatefavourite', [CustomerController::class, 'updatefavourite']);
	Route::post('userwishlists', [CustomerController::class, 'userwishlists']);
	Route::post('updatewishlist', [CustomerController::class, 'updatewishlist']);
	Route::post('deletewishlist', [CustomerController::class, 'deletewishlist']);
	Route::any('address',		  [CustomerController::class, 'userAddress']);
	Route::get('userreferral', [CustomerController::class,'userReferral']);
	Route::get('userwallet', [CustomerController::class, 'userWallet']);
	/*Route::match(['get', 'post', 'patch', 'put'], 'address', [
		'uses'	=> 'App\Http\Controllers\Api\Customer\CustomerController@userAddress',
		'as'	=> 'address',
	]);*/
});

Route::group(['middleware'=>'api','prefix'=>'api/dunzo'],function() {
	Route::get('getToken',[DunzoController::class, 'getToken']);
	Route::get('getQuote',[DunzoController::class, 'getQuote']);
	Route::get('createOrder',[DunzoController::class, 'createOrder']);
	Route::get('getOrderStatus',[DunzoController::class, 'getOrderStatus']);
	Route::get('cancelOrder',[DunzoController::class, 'cancelOrder']);
	Route::get('nextStatus',[DunzoController::class, 'postmoveNextStatus']);
	Route::get('RunnerCancel',[DunzoController::class, 'postmoveRunnerCancel']);
});

Route::group(['middleware'=>'api','prefix'=>'api/shadow'],function() {
	Route::get('getQuote',[ShadowController::class, 'getQuote']);
	Route::get('createOrder',[ShadowController::class, 'createOrder']);
	Route::get('getOrderStatus',[ShadowController::class, 'getOrderStatus']);
	Route::get('cancelOrder',[ShadowController::class, 'cancelOrder']);
	Route::get('nextStatus',[ShadowController::class, 'postmoveNextStatus']);
});

Route::group(['middleware'=>'api','prefix'=>'api/razor'],function() {
	Route::any('test',[PayoutsController::class, 'PayoutsCron']);
});

Route::group(['middleware'=>'api','prefix'=>'api/google'],function() {
	Route::get('auth',[GoogleController::class, 'Auth']);
	Route::post('Fcm',[GoogleController::class, 'Fcm']);
});

Route::group(['prefix'=>'api/chat'],function() {

	Route::get('chatMessage', [App\Http\Controllers\ChatController::class, 'getMessages']);

	Route::post('chatMessage', [App\Http\Controllers\ChatController::class, 'storeMessages']);

	Route::post('onlineUsers', [App\Http\Controllers\ChatController::class, 'onlineUsers']);

	Route::post('updateOnline', [App\Http\Controllers\ChatController::class, 'updateOnline']);
});

