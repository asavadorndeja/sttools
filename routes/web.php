<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('f114','f114Controller');
Route::resource('DNVGLRPF114','DNVGLRPF114Controller');
Route::resource('ASME318','ASME318Controller');
Route::resource('DNVGLRPF103','DNVGLRPF103Controller');
Route::resource('DNVGLRPF109','DNVGLRPF109Controller');
Route::resource('DNVGLSTF101WT','DNVGLSTF101WTController');
Route::resource('DNVGLRPF105','DNVGLRPF105Controller');


// Route::get('/send', 'EmailController@send');
Route::post('', 'EmailController@sendReport');
