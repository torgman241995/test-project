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


// Погода 
Route::get('/weather', 'WeatherController@index')->name('weather');
Route::get('/weather/{city_name}', 'WeatherController@index')->name('weather');
// Заказы
Route::get('/orders', 'OrdersController@index')->name('orders');
Route::get('/update/{id}', 'OrdersController@update')->name('orders');
Route::get('/save', 'OrdersController@save')->name('orders');
Route::post('/save', 'OrdersController@save')->name('orders');