<?php

Auth::routes(['verify' => true]);

Route::get('/', 'PageController@index')->name('home');

Route::resource('/logger', 'LoggerController');
