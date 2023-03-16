<?php
Route::group(['as' =>'admin.','prefix'=>'admin','middleware'=>['web','auth','CheckAdmin','StripEmptyParams']],function(){

	Route::get('/pieChart', [App\Http\Controllers\DashboardController::class, 'pieChart']);
	Route::get('dashboard',[App\Http\Controllers\DashboardController::class],'vendor')->name('dashboard');
	Route::get('/profile/{id}/edit',[App\Http\Controllers\CustomerController::class, 'profileedit'])->where('id', '[0-9]+');
	Route::post('/adminprofile_save',[App\Http\Controllers\CustomerController::class, 'adminprofile_store']);

	Route::resource('vendor', App\Http\Controllers\Admin\VendorController::class);
	Route::put('vendor/{id}/delete',[App\Http\Controllers\Admin\VendorController::class, 'delete']);
	Route::post('/vendor/0/multidelete',[App\Http\Controllers\Admin\VendorController::class, 'multidelete'])->name('multidelete');

	Route::get('/vendor/{id}/store/',[App\Http\Controllers\Admin\StoreController::class, 'index'])->where('id', '[0-9]+');
	Route::get('/vendor/{id}/store/create',[App\Http\Controllers\Admin\StoreController::class, 'create'])->where('id', '[0-9]+')->where('s_id', '[0-9]+');
	Route::get('/vendor/{id}/store/{s_id}/edit',[App\Http\Controllers\Admin\StoreController::class, 'edit'])->where('id', '[0-9]+')->where('s_id', '[0-9]+');
	Route::put('/vendor/stores/{id}/delete',[App\Http\Controllers\Admin\StoreController::class, 'delete']);
	Route::put('/vendor/stores/mode',[App\Http\Controllers\Admin\StoreController::class, 'updateVendorStoreMode']);
	Route::post('/vendor/stores/0/multidelete',[App\Http\Controllers\Admin\StoreController::class, 'multidelete'])->name('storemultidelete');
	// Route::get('/vendor/stores/{v_id}/edit_business',[App\Http\Controllers\Admin\StoreController::class, 'index'])->where('v_id', '[0-9]+');

	Route::post('/category',[App\Http\Controllers\Admin\VendorController::class, 'category']);
	Route::post('/vendor/createStore',[App\Http\Controllers\Admin\VendorController::class, 'update']);
	Route::post('/schedule',[App\Http\Controllers\Admin\StoreController::class, 'schedule']);
	Route::post('/availability',[App\Http\Controllers\Admin\StoreController::class, 'availability']);
	Route::post('/working_days',[App\Http\Controllers\Admin\StoreController::class, 'working_days']);
	Route::resource('offtimelog', App\Http\Controllers\OfftimelogController::class);
	Route::DELETE('offtimelog/{id}/delete',[App\Http\Controllers\OfftimelogController::class, 'destroy']);


	Route::match(['get', 'post'], 'chefordering', [
		'uses'	=> 'App\Http\Controllers\Admin\VendorController@ordering',
		'as'	=> 'chef_ordering',
	]);

	Route::get('/store/{v_id}/{res_id}/menu_item',[App\Http\Controllers\MenuitemController::class, 'index'])->where('v_id', '[0-9]+')->where('res_id', '[0-9]+');
	Route::get('/store/{v_id}/{res_id}/menu_item/rearrange',[App\Http\Controllers\MenuitemController::class, 'orderrearrange'])->where('v_id', '[0-9]+')->where('res_id', '[0-9]+');
	Route::put('menuitem/store',[App\Http\Controllers\MenuitemController::class, 'store'])->where('v_id', '[0-9]+')->where('res_id', '[0-9]+');
	Route::get('/store/{v_id}/{res_id}/menu_item/create',[App\Http\Controllers\MenuitemController::class, 'create'])->where('v_id', '[0-9]+')->where('res_id', '[0-9]+');
	Route::post('/store/{v_id}/{res_id}/menu_item/store',[App\Http\Controllers\MenuitemController::class, 'update'])->where('v_id', '[0-9]+')->where('res_id', '[0-9]+');
	Route::get('/store/{v_id}/{res_id}/menu_item/edit/{id}',[App\Http\Controllers\MenuitemController::class, 'edit'])->where('id', '[0-9]+')->where('v_id', '[0-9]+')->where('res_id', '[0-9]+');
	
	Route::get('/vendor/{v_id}/addon',[App\Http\Controllers\AddonController::class, 'index'])->where('v_id', '[0-9]+');
	Route::get('/vendor/{v_id}/addon/create',[App\Http\Controllers\AddonController::class, 'create'])->where('v_id', '[0-9]+');
	Route::post('/vendor/{v_id}/addon/store',[App\Http\Controllers\AddonController::class, 'update'])->where('v_id', '[0-9]+');
	Route::get('/vendor/{v_id}/addon/edit/{id}',[App\Http\Controllers\AddonController::class, 'edit'])->where('id', '[0-9]+')->where('v_id', '[0-9]+');
	
	Route::get('/vendor/{v_id}/unit',[App\Http\Controllers\AddonController::class, 'index'])->where('v_id', '[0-9]+');
	Route::get('/vendor/{v_id}/unit/create',[App\Http\Controllers\AddonController::class, 'create'])->where('v_id', '[0-9]+');
	Route::post('/vendor/{v_id}/unit/store',[App\Http\Controllers\AddonController::class, 'update'])->where('v_id', '[0-9]+');
	Route::get('/vendor/{v_id}/unit/edit/{id}',[App\Http\Controllers\AddonController::class, 'edit'])->where('id', '[0-9]+')->where('v_id', '[0-9]+');
	
	Route::get('/vendor/{v_id}/category',[App\Http\Controllers\CategoryController::class, 'index'])->where('v_id', '[0-9]+');
	Route::get('/vendor/{v_id}/category/create',[App\Http\Controllers\CategoryController::class, 'create'])->where('v_id', '[0-9]+');
	Route::post('/vendor/{v_id}/category/store',[App\Http\Controllers\CategoryController::class, 'update'])->where('v_id', '[0-9]+');
	Route::get('/vendor/{v_id}/category/edit/{id}',[App\Http\Controllers\CategoryController::class, 'edit'])->where('id', '[0-9]+')->where('v_id', '[0-9]+');

	Route::resource('customer/all', App\Http\Controllers\CustomerController::class);
	Route::get('/customer',[App\Http\Controllers\CustomerController::class, 'index']);
	Route::get('/customer/create',[App\Http\Controllers\CustomerController::class, 'create']);
	Route::post('/customer/store',[App\Http\Controllers\CustomerController::class, 'update']);
	Route::get('/customer/{id}/edit',[App\Http\Controllers\CustomerController::class, 'edit'])->where('id', '[0-9]+');

	Route::resource('cuisines', App\Http\Controllers\CuisineController::class);
	Route::put('/cuisines/store',[App\Http\Controllers\CuisineController::class, 'update']);
	Route::get('/cuisines/create',[App\Http\Controllers\CuisineController::class, 'create']);
	Route::post('/cuisines/explore',[App\Http\Controllers\CuisineController::class, 'exploreoption']);
	Route::put('/cuisines/{id}/delete',[App\Http\Controllers\CuisineController::class, 'destroy']);
	Route::get('/cuisines/{id}/edit',[App\Http\Controllers\CuisineController::class, 'edit'])->where('id', '[0-9]+');

	Route::resource('location', App\Http\Controllers\LocationController::class);
	Route::put('/location/store',[App\Http\Controllers\LocationController::class, 'update']);
	Route::put('/location/{id}',[App\Http\Controllers\LocationController::class, 'destroy']);

	Route::resource('banner', App\Http\Controllers\BannerController::class);
	Route::put('/banner/{id}',[App\Http\Controllers\BannerController::class, 'destroy']);
	Route::resource('offer', App\Http\Controllers\OfferController::class);

	Route::get('/notification',[App\Http\Controllers\NotificationController::class, 'index']);
	Route::post('/update_notify_isread',  [App\Http\Controllers\NotificationController::class, 'updateNotifyIsread']);
	Route::get('/logactivity',[App\Http\Controllers\LogActivityController::class, 'index']);

	Route::get('/pages',[App\Http\Controllers\PageController::class, 'index']);
	Route::get('/pages/create',[App\Http\Controllers\PageController::class, 'create']);
	Route::post('/pages/store',[App\Http\Controllers\PageController::class, 'update']);
	Route::get('/pages/{id}/edit',[App\Http\Controllers\PageController::class, 'edit'])->where('id', '[0-9]+');
	Route::put('/pages/{id}/delete',[App\Http\Controllers\PageController::class, 'destroy']);

	Route::get('cuisineexport/{slug}', [App\Http\Controllers\CuisineController::class, 'cuisineexport']);

	Route::get('blast_notification',[App\Http\Controllers\NotificationController::class, 'blastNotification']);
	Route::post('notification_send',[App\Http\Controllers\NotificationController::class, 'blastNotification_send']);
	Route::get('blast_notification/logs',[App\Http\Controllers\NotificationController::class, 'blastNotification_logs']);
	Route::get('vendor/{v_id}/menuitemexport/{slug}', [App\Http\Controllers\MenuitemController::class, 'menuitemexport']);
	Route::get('vendor/{v_id}/addonexport/{slug}', [App\Http\Controllers\AddonController::class, 'addonexport']);
	Route::get('mediapress',[App\Http\Controllers\MediapressController::class,'index']);
	Route::get('mediapress/create',[App\Http\Controllers\MediapressController::class,'create']);
    Route::put('/mediapress/store',[App\Http\Controllers\MediapressController::class, 'update']);
    Route::put('/mediapress/{id}/delete',[App\Http\Controllers\MediapressController::class, 'destroy']);
    Route::get('/mediapress/{id}/edit',[App\Http\Controllers\MediapressController::class, 'edit'])->where('id', '[0-9]+');
});
?>