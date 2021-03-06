<?php

Route::blacklist(function () {
    Auth::routes(['verify' => true]);
});

// Home
Route::get('/', 'HomeController@index')->name('home');

Route::group([
    'middleware' => 'auth'
], function () {

    // Dashboard
    Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');

    // Cards
    Route::get('cards', 'CardsController@index')->name('cards');

    // Categories
    Route::get('categories', 'CategoriesController@index')->name('categories');

    // Records
    Route::get('records', 'RecordsController@index')->name('records');
});

// API
Route::group([
    'middleware' => 'auth',
    'namespace' => 'Api',
    'prefix' => 'api',
], function () {

    // Categories
    Route::get('categories', 'CategoriesController@index')->name('api.categories');
    Route::post('categories', 'CategoriesController@store')->name('api.categories.store');
    Route::delete('categories/{category}', 'CategoriesController@destroy')->name('api.categories.destroy');
    Route::patch('categories/{category}', 'CategoriesController@update')->name('api.categories.update');

    // Cards
    Route::get('cards', 'CardsController@index')->name('api.cards');
    Route::post('cards', 'CardsController@store')->name('api.cards.store');
    Route::delete('cards/{card}', 'CardsController@destroy')->name('api.cards.destroy');
    Route::patch('cards/{card}', 'CardsController@update')->name('api.cards.update');

    // Card Participants
    Route::get('cards/{card}/participants', 'CardParticipantsController@index')->name('api.cardParticipants');
    Route::post('cards/{card}/participants', 'CardParticipantsController@store')->name('api.cardParticipants.store');
    Route::delete('cards/{card}/participants/{user}', 'CardParticipantsController@destroy')->name('api.cardParticipants.destroy');

    // Card Status
    Route::post('cards/{card}/status', 'CardStatusController@store')->name('api.cardStatus.store');

    // Tasks
    Route::post('cards/{card}/tasks', 'TasksController@store')->name('api.tasks.store');
    Route::delete('cards/{card}/tasks/{task}', 'TasksController@destroy')->name('api.tasks.destroy');
    Route::patch('cards/{card}/tasks/{task}', 'TasksController@update')->name('api.tasks.update');

    // Task Status
    Route::patch('tasks/{task}/status', 'TaskStatusController@update')->name('api.taskStatus.update');

    // Records
    Route::get('records', 'RecordsController@index')->name('api.records');
    Route::post('records', 'RecordsController@store')->name('api.records.store');
    Route::delete('records/{record}', 'RecordsController@destroy')->name('api.records.destroy');
    Route::patch('records/{record}', 'RecordsController@update')->name('api.records.update');
});

// Admin
Route::group([
    'middleware' => 'admin',
    'namespace' => 'Admin',
    'prefix' => 'admin',
], function () {
    Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard');
});
