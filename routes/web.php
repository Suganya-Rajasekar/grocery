<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('otptest',function () {
	$details['phone']       = 9222479222;
	$details['content']     = '1234';
	$details['template']    = 'KnoshOTP';
	dd(new \App\Events\OtpUserEvent($details));
});

Route::get('referal_generate',[App\Http\Controllers\CustomerController::class, 'referal_generate']);

Route::get('generatebill/{id}',[App\Http\Controllers\Api\Order\OrderController::class,'orderimage'])->where('id','[0-9]+');

Route::post('/importexcel',[App\Http\Controllers\ImportController::class,'import']);
Route::post('/packages',[App\Http\Controllers\ChefController::class,'package']); 
Route::get('/', [App\Http\Controllers\Front\FrontendController::class, 'index'])->name('home');
Route::get('downloadApp', [App\Http\Controllers\Front\FrontendController::class, 'DownloadApp'])->name('downloadApp');
// Route::get('/knosh-world', [App\Http\Controllers\Front\FrontendController::class, 'knoshWorld'])->name('knosh-world');
Route::get('/me',  [App\Http\Controllers\Api\Customer\CustomerController::class, 'me']);
// Tam
Route::get('/user/dashboard/{module}',  [App\Http\Controllers\CustomerController::class, 'showCustomerProfile']);
Route::post('/user/dashboard/profile/update',  [App\Http\Controllers\CustomerController::class, 'updateCustomerProfile']);
Route::post('/user/dashboard/password/update',  [App\Http\Controllers\CustomerController::class, 'updateCustomerPassword']);

Route::post('/removeUserWishlist',  [App\Http\Controllers\CustomerController::class, 'removeUserWishlist']);
Route::post('/wishlist/update',  [App\Http\Controllers\CustomerController::class, 'updateWishlist']);
Route::get('/wishlist/{id}',  [App\Http\Controllers\CustomerController::class, 'showSingleWishlist']);

//Route::get('/chef/{id}',  [admin\Http\Controllers\FrontendController::class, 'chefProfile']);
Route::post('/bookmark/update',  [App\Http\Controllers\CustomerController::class, 'updateBookmark']);
Route::post('/favourite/update',  [App\Http\Controllers\CustomerController::class, 'updateFavourite']);
Route::put('/cancel_order',  [App\Http\Controllers\CustomerController::class, 'cancelOrder']);
Route::get('/order_review_detail',  [App\Http\Controllers\CustomerController::class, 'orderReviewDetail']);
Route::post('/order_review_send',  [App\Http\Controllers\CustomerController::class, 'orderReviewSend']);
Route::PUT('/order_review_send',  [App\Http\Controllers\CustomerController::class, 'orderReviewSend']);

Route::DELETE('/order_review_send',  [App\Http\Controllers\CustomerController::class, 'orderReviewSend']);

// Route::get('/seeMore/{module}/{lat}/{lang}',  [App\Http\Controllers\Front\FrontendController::class, 'showSeeMore']);
Route::get('/seeMore/{module}',  [App\Http\Controllers\Front\FrontendController::class, 'showSeeMore']);

Route::get('/explore/{keyword}',  [App\Http\Controllers\Front\FrontendController::class, 'showexplore']);
// Route::get('/explore/{keyword}/{cuisineid}/{lat}/{lang}',  [App\Http\Controllers\Front\FrontendController::class, 'showCuisineChef']);
Route::get('/explore/{keyword}/{cuisineid}',  [App\Http\Controllers\Front\FrontendController::class, 'showCuisineChef']);
Route::get('/chefoffer/{offerid}/{lat}/{lang}',  [App\Http\Controllers\Front\FrontendController::class, 'showOfferChef']);
Route::get('/search',  [App\Http\Controllers\Front\FrontendController::class, 'showsearch']);
Route::get('/searchchef',[App\Http\Controllers\Front\FrontendController::class, 'searchChefResult']);
Route::post('/setsessionlatlang',  [App\Http\Controllers\Front\FrontendController::class, 'setsessionlatlang']);
Route::post('/showpopular',  [App\Http\Controllers\Front\FrontendController::class, 'showpopular']);
Route::post('/showblog',  [App\Http\Controllers\Front\FrontendController::class, 'showblog']);

//Route::post('/sendfooditems',  [App\Http\Controllers\FrontendController::class, 'send_fooditems']);
//Route::post('/menufood',  [App\Http\Controllers\FrontendController::class, 'menuinfo']);
//Route::post('/commentsend',  [App\Http\Controllers\FrontendController::class, 'send_comment']);

 Route::get('/chef/{id}',  [App\Http\Controllers\Front\Details\DetailsController::class, 'chefProfile'])->where('id', '[0-9]+');
 Route::post('/sendfooditems',  [App\Http\Controllers\Front\Details\DetailsController::class, 'send_fooditems']);
 Route::post('/menufood',  [App\Http\Controllers\Front\Details\DetailsController::class, 'menuinfo']);
 Route::post('/timeslot',  [App\Http\Controllers\Front\Details\DetailsController::class, 'timeslot']);
 Route::post('/commentsend',  [App\Http\Controllers\Front\Details\DetailsController::class, 'send_comment']);
 Route::patch('/commentsend',  [App\Http\Controllers\Front\Details\DetailsController::class, 'send_comment']);
 Route::put('/commentlike',  [App\Http\Controllers\Front\Details\DetailsController::class, 'send_comment']);
 Route::get('/chefmenu/{id}',  [App\Http\Controllers\Front\Details\DetailsController::class, 'chefProfileredirect'])->where('id', '[0-9]+');
 Route::get('/menuaddon/{id}',  [App\Http\Controllers\Front\Details\DetailsController::class, 'chefAddonredirect'])->where('id', '[0-9]+');
 Route::get('/continueredirect/{id}',  [App\Http\Controllers\Front\Details\DetailsController::class, 'continueOrderredirect']);
 Route::get('/checkout',  [App\Http\Controllers\Front\Checkout\CheckoutController::class, 'showcheckout']);
 Route::PUT('/sendaddress',  [App\Http\Controllers\Front\Checkout\CheckoutController::class, 'send_chooseaddress']);
 Route::PATCH('/updatecart',  [App\Http\Controllers\Front\Checkout\CheckoutController::class, 'updateCart']);
 Route::DELETE('/deletecart',  [App\Http\Controllers\Front\Checkout\CheckoutController::class, 'deleteCart']);
 Route::post('/placeorder',  [App\Http\Controllers\Front\Checkout\CheckoutController::class, 'send_placeorder']);
 Route::PUT('/apply_coupon',  [App\Http\Controllers\Front\Checkout\CheckoutController::class, 'send_coupon']);
 Route::get('/thankyou',  [App\Http\Controllers\Front\Checkout\CheckoutController::class, 'payment_success']);
 Route::any('/useraddress',  [App\Http\Controllers\Front\Checkout\CheckoutController::class, 'userAddress']);
 Route::DELETE('/deleteuseraddress',  [App\Http\Controllers\Front\Checkout\CheckoutController::class, 'deleteUseraddress']);
 Route::post('/loadMoreDataurl',  [App\Http\Controllers\Front\Details\DetailsController::class, 'loadMoreData']);
 Route::PATCH('apply_wallet', [App\Http\Controllers\Front\Checkout\CheckoutController::class, 'send_wallet']);

// Auth Routes
Auth::routes();
Route::post('vendor/checkEmail',[App\Http\Controllers\vendorUserController::class, 'checkEmail']);
Route::get('/stripe', [App\Http\Controllers\StripeController::class, 'test']);
Route::post('/login', 'App\Http\Controllers\Auth\LoginController@Login');
Route::post('/verifyotp','App\Http\Controllers\Auth\LoginController@verifyOTP');
Route::post('/sociallogin','App\Http\Controllers\Auth\LoginController@socialLogin');
Route::post('/forgetpasswordrequest','App\Http\Controllers\Auth\LoginController@forgetpasswordrequest');
Route::post('/resetpassword','App\Http\Controllers\Auth\LoginController@resetpassword');
Route::post('checksocial',[App\Http\Controllers\registerController::class, 'CheckSocial']);
Route::post('registerUser',[App\Http\Controllers\registerController::class, 'registerUser']);
Route::post('checkEmail',[App\Http\Controllers\registerController::class, 'checkEmail']);
Route::post('checkMobile',[App\Http\Controllers\registerController::class, 'checkMobile']);
Route::post('loginUser',[App\Http\Controllers\registerController::class, 'loginUser']);
Route::post('/logout', 'App\Http\Controllers\Auth\LoginController@logout');
Route::post('password-change',[App\Http\Controllers\HomeController::class, 'passwordChange']);

Route::get('/become-a-chef', [App\Http\Controllers\registerController::class, 'BecomeAChef']);
Route::get('/chef/register', [App\Http\Controllers\registerController::class, 'chefRegister'])->middleware('logged');
Route::get('/chef/login', function () {
	return view('auth.login');
})->middleware('logged');
Route::post('send_chefregister',[App\Http\Controllers\registerController::class, 'send_chefregisterUser']);
Route::get('pages/contact-us','App\Http\Controllers\Front\FrontendController@showcontactpage');
Route::get('/blogs','App\Http\Controllers\Front\FrontendController@showblogpage');
Route::post('home/contact','App\Http\Controllers\HomeController@contactsave');
Route::put('/onandoffline_update',[App\Http\Controllers\ChefController::class, 'OnandOffline_update']);
// Socila login Routes
Route::get('auth/{provider}', 'App\Http\Controllers\Auth\AuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'App\Http\Controllers\Auth\AuthController@handleProviderCallback');

Route::get('razorpay-payment', [App\Http\Controllers\RazorpayPaymentController::class, 'index']);
Route::post('razorpay-payment', [App\Http\Controllers\RazorpayPaymentController::class, 'store'])->name('razorpay.payment.store');

Route::get('send-sms-notification', [App\Http\Controllers\SmsController::class, 'sendSmsNotificaition']);
//Route::get('admin/chat', [App\Http\Controllers\ChatController::class, 'index']);
Route::get('admin/chat/{user_id}', [App\Http\Controllers\ChatController::class, 'users']);
Route::get('chatMessage', [App\Http\Controllers\ChatController::class, 'getMessages'])->name('getChatMessage');
Route::post('chatMessage', [App\Http\Controllers\ChatController::class, 'storeMessages'])->name('storeChatMessage');

Route::get('cronjob','App\Http\Controllers\Api\Emperica\EmpericaController@MasterCron')->name('cronjob');
Route::get('clearlog','App\Http\Controllers\Api\Emperica\EmpericaController@Clearlog');

Route::get('mailcheck','App\Http\Controllers\Api\Emperica\EmpericaController@mailcheck')->name('mailcheck');

include('vendor.php');
include('admin.php');
include('checkAdmin.php');
include('builder.php');
include('layout.php');
include_once 'api.php';

Route::get('/{slug}','App\Http\Controllers\Front\FrontendController@showpage')->name('page.showpage');
Route::fallback(function () {
	return view("404");
});
Route::post('checkout/insertname','App\Http\Controllers\Front\Checkout\CheckoutController@insertname')->name('insertname');
Route::post('importExcel', [App\Http\Controllers\MenuitemController::class, 'menuitemimport']);

Route::post('notifyme',[App\Http\Controllers\Front\Details\DetailsController::class, 'notifyme']);
Route::DELETE('notifyme',[App\Http\Controllers\Front\Details\DetailsController::class, 'notifyme']);

Route::PATCH('deliveryslot_change','App\Http\Controllers\Front\Details\DetailsController@deliveryslotchange');
// Cron

