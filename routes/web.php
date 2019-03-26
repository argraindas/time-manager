<?php

Route::blacklist(function () {
    Auth::routes(['verify' => true]);
});

Route::get('/', 'PagesController@index')->name('home');
Route::get('/dashboard', 'PagesController@dashboard')->name('dashboard');

Route::get('/records', 'RecordsController@index')->name('records');
Route::get('/records/create', 'RecordsController@create');
Route::post('/records', 'RecordsController@store')->name('records.store');

Route::get('/categories', 'CategoriesController@index')->name('categories');
Route::get('/categories/create', 'CategoriesController@create');


// Inner API
Route::get('/api/categories', 'Api\CategoriesController@index')->name('api.categories');
Route::post('/api/categories', 'Api\CategoriesController@store')->name('api.categories.store');
Route::delete('/api/categories/{category}', 'Api\CategoriesController@destroy')->name('api.categories.destroy');
Route::patch('/api/categories/{category}', 'Api\CategoriesController@update')->name('api.categories.update');


// TODO: Try this out
//Route::group([
//    'prefix' => 'admin',
//    'middleware' => 'admin',
//    'namespace' => 'Admin'
//], function () {
//    Route::get('', 'DashboardController@index')->name('admin.dashboard.index');
//    Route::post('channels', 'ChannelsController@store')->name('admin.channels.store');
//});
