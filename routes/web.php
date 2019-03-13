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
Route::post('/categories', 'CategoriesController@store')->name('categories.store');


// Inner API
Route::get('/api/categories', 'Api\CategoriesController@index')->name('api.categories');
