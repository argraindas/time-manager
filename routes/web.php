<?php

Route::blacklist(function () {
    Auth::routes(['verify' => true]);
});

// Home
Route::get('/', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
    
    // Dashboard
    Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');
    
    // Categories
    Route::get('categories', 'CategoriesController@index')->name('categories');
    Route::get('categories/create', 'CategoriesController@create');
    
    // Records
    Route::get('records', 'RecordsController@index')->name('records');
    Route::get('records/create', 'RecordsController@create');
    Route::post('records', 'RecordsController@store')->name('records.store');
});

Route::group([
    'middleware' => 'auth',
    'namespace' => 'Api',
    'prefix' => 'api',
], function () {
    // API
    Route::get('categories', 'CategoriesController@index')->name('api.categories');
    Route::post('categories', 'CategoriesController@store')->name('api.categories.store');
    Route::delete('categories/{category}', 'CategoriesController@destroy')->name('api.categories.destroy');
    Route::patch('categories/{category}', 'CategoriesController@update')->name('api.categories.update');
});
