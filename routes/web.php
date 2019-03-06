<?php

Route::get('/', function () {
    return view('welcome');
})->middleware('verified');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
