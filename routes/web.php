<?php

Route::blacklist(function () {
    Auth::routes(['verify' => true]);
});

// Home
Route::get('/', 'HomeController@index')->name('home');

// Dashboard
Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard')->middleware('auth');

// Categories
Route::get('/categories', 'CategoriesController@index')->name('categories')->middleware('auth');
Route::get('/categories/create', 'CategoriesController@create')->middleware('auth');

// Records
Route::get('/records', 'RecordsController@index')->name('records')->middleware('auth');
Route::get('/records/create', 'RecordsController@create')->middleware('auth');
Route::post('/records', 'RecordsController@store')->name('records.store')->middleware('auth');


// API
Route::get('/api/categories', 'Api\CategoriesController@index')->name('api.categories')->middleware('auth');
Route::post('/api/categories', 'Api\CategoriesController@store')->name('api.categories.store')->middleware('auth');
Route::delete('/api/categories/{category}', 'Api\CategoriesController@destroy')->name('api.categories.destroy')->middleware('auth');
Route::patch('/api/categories/{category}', 'Api\CategoriesController@update')->name('api.categories.update')->middleware('auth');


// TODO: Try this out
//Route::group([
//    'prefix' => 'admin',
//    'middleware' => 'admin',
//    'namespace' => 'Admin'
//], function () {
//    Route::get('', 'DashboardController@index')->name('admin.dashboard.index');
//    Route::post('channels', 'ChannelsController@store')->name('admin.channels.store');
//});
