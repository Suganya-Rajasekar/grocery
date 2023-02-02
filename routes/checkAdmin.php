<?php
Route::middleware(['CheckAdmin'])->group(function () { 
	Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
	Route::get('/vendor/register', [App\Http\Controllers\vendorController::class, 'index'])->name('vendor');
	Route::resource('translate', App\Http\Controllers\TranslateController::class);
	Route::resource('category', App\Http\Controllers\CategoryController::class);
	Route::resource('blog_category', App\Http\Controllers\BlogCategoryController::class);
	Route::resource('usermanages', App\Http\Controllers\usermanageController::class);
	Route::get('admin/dashboard',[App\Http\Controllers\DashboardController::class, 'admin'])->name('admin.dashboard');

	Route::get('/usermanages/status/{id}/{status}',[App\Http\Controllers\usermanageController::class, 'status']);
	Route::get('/usermanages/subscriptions/{id}',[App\Http\Controllers\usermanageController::class, 'subsGet'])->where('id', '[0-9]+');
	Route::resource('subAdmins', App\Http\Controllers\sub_adminController::class);
	Route::resource('roles', App\Http\Controllers\rolesController::class);
	Route::resource('setting', App\Http\Controllers\SettingController::class);
});