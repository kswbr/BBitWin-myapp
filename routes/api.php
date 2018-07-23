<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('campaigns/{campaign}/lotteries/{lottery}/entries/chart','Admin\EntryController@chart')->middleware('auth:api');
Route::get('campaigns/{campaign}/lotteries/{lottery}/entries/state_list','Admin\EntryController@state_list')->middleware('auth:api');

Route::resource('campaigns', 'Admin\CampaignController')->middleware('auth:api');
Route::resource('campaigns.lotteries', 'Admin\LotteryController')->middleware('auth:api');
Route::resource('campaigns.lotteries.entries', 'Admin\EntryController')->middleware('auth:api');


