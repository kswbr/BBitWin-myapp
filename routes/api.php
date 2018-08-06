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

Route::get('user/info','Admin\UserController@info')->middleware('auth:api','scopes:check-admin');

Route::get('user/info','Admin\UserController@info')->middleware('auth:api','scopes:check-admin');

Route::get('campaigns/{campaign}/lotteries/{lottery}/entries/chart','Admin\EntryController@chart')->middleware('auth:api','scopes:check-admin');

Route::resource('campaigns', 'Admin\CampaignController')->middleware('auth:api','scopes:check-admin');
Route::resource('campaigns.lotteries', 'Admin\LotteryController')->middleware('auth:api','scopes:check-admin');
Route::resource('campaigns.lotteries.entries', 'Admin\EntryController')->middleware('auth:api','scopes:check-admin');

Route::get('votes/{vote}/chart','Admin\VoteController@chart')->middleware('auth:api','scopes:check-admin');
Route::resource('votes', 'Admin\VoteController')->middleware('auth:api','scopes:check-admin');

Route::get('oauth/twitter/redirect','Web\SnsController@twitter_redirect')->middleware('cors');
Route::get('oauth/instantwin/login/twitter','Web\SnsController@twitter_register')->middleware('cors');
Route::get('instantwin/run','Web\InstantWinController@run')->middleware(['cors','auth:api','scopes:instant-win']);
Route::get('instantwin/run/retry','Web\InstantWinController@run')->middleware(['cors','auth:api','scopes:instant-win,retry']);

Route::get('instantwin/run/{campaign_code}/{lottery_code}','Web\InstantWinController@run')->middleware(['cors','auth:api','scopes:instant-win']);
Route::get('instantwin/run/{campaign_code}/{lottery_code}/retry','Web\InstantWinController@run')->middleware(['cors','auth:api','scopes:instant-win,retry']);
Route::get('instantwin/multi/run','Web\InstantWinMultiController@run')->middleware(['cors','auth:api','scopes:instant-win']);
Route::get('instantwin/multi/run/retry','Web\InstantWinMultiController@run')->middleware(['cors','auth:api','scopes:instant-win,retry']);

Route::get('instantwin/run/{campaign_code}','Web\InstantWinController@run')->middleware(['cors','auth:api','scopes:instant-win']);
Route::get('instantwin/run/{campaign_code}/retry','Web\InstantWinController@run')->middleware(['cors','auth:api','scopes:instant-win,retry']);

Route::get('instantwin/winner/regist','Web\InstantWinController@winner_regist')->middleware(['cors','auth:api','scopes:instant-win,winner']);
