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

Route::post('login', function(){
    return view('welcome');
})->name('login');

Route::get('/', 'WelcomeController@index');
Route::get('/admin', 'AdminController@index');
Route::get('/admin/{any}', 'AdminController@index')->where('any','.*');

Route::post('/admin', 'AdminController@login')->middleware('api');

