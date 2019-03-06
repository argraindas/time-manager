<?php

Route::get('/', function () {
    return view('home');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
