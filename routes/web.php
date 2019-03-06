<?php

Auth::routes(['verify' => true]);

Route::get('/', 'PageController@index')->name('home');
Route::get('/dashboard', 'PageController@dashboard')->name('dashboard');

Route::get('/records', 'RecordsController@index')->name('record');
Route::post('/records', 'RecordsController@store')->name('record.store');
