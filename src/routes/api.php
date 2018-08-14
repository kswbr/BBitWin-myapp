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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['auth:api','scopes:check-admin']], function () {
    Route::get('user/info','Admin\UserController@info');

    Route::group(['middleware' => ['can:allow_user']], function () {
        Route::patch('users/{user}/change_password', 'Admin\UserController@change_password');
        Route::get('users/role_list', 'Admin\UserController@role_list');
        Route::resource('users', 'Admin\UserController');
    });

    Route::group(['middleware' => ['can:allow_campaign']], function () {
        Route::get('campaigns/{campaign}/lotteries/{lottery}/entries/chart','Admin\EntryController@chart');
        Route::get('campaigns/{campaign}/lotteries/{lottery}/entries/state_list','Admin\EntryController@state_list');
        Route::resource('campaigns', 'Admin\CampaignController');
        Route::resource('campaigns.lotteries', 'Admin\LotteryController');
        Route::resource('campaigns.lotteries.entries', 'Admin\EntryController');
    });

    Route::group(['middleware' => ['can:allow_vote']], function () {
        Route::get('votes/{vote}/chart','Admin\VoteController@chart');
        Route::resource('votes', 'Admin\VoteController');
    });
});

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

Route::get('instantwin/form/init','Web\InstantWin\FormController@init')->middleware(['cors','auth:api','scopes:instant-win,form']);

