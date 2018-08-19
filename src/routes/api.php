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
    Route::get('users/role_list', 'Admin\UserController@role_list');

    Route::group(['middleware' => ['checkIfLoggedInUserData']], function () {
        Route::patch('user/{user}/change_password', 'Admin\UserController@change_password');
        Route::resource('user', 'Admin\UserController', ['only' => ['show','update']]);
    });

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

Route::group(['middleware' => ['cors']], function () {

    Route::get('oauth/twitter/redirect','Web\SnsController@twitter_redirect');
    Route::get('oauth/instantwin/login/twitter','Web\SnsController@twitter_register');

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('instantwin/run','Web\InstantWinController@run')->middleware(['scopes:instant-win']);
        Route::get('instantwin/run/retry','Web\InstantWinController@run')->middleware(['scopes:instant-win,retry']);

        Route::get('instantwin/run/{campaign_code}/{lottery_code}','Web\InstantWinController@run')->middleware(['scopes:instant-win']);
        Route::get('instantwin/run/{campaign_code}/{lottery_code}/retry','Web\InstantWinController@run')->middleware(['scopes:instant-win,retry']);
        Route::get('instantwin/multi/run','Web\InstantWinMultiController@run')->middleware(['scopes:instant-win']);
        Route::get('instantwin/multi/run/retry','Web\InstantWinMultiController@run')->middleware(['scopes:instant-win,retry']);

        Route::get('instantwin/run/{campaign_code}','Web\InstantWinController@run')->middleware(['scopes:instant-win']);
        Route::get('instantwin/run/{campaign_code}/retry','Web\InstantWinController@run')->middleware(['scopes:instant-win,retry']);

        Route::get('instantwin/winner/regist','Web\InstantWinController@winner_regist')->middleware(['scopes:instant-win,winner']);
        Route::get('instantwin/form/init','Web\InstantWin\FormController@init')->middleware(['scopes:instant-win,form']);

        Route::post('vote/run','Web\VoteController@run')->middleware(['scopes:vote-event']);
        Route::post('vote/run/{vote_code}','Web\VoteController@run')->middleware(['scopes:vote-event']);
        Route::post('instantwin/form/confirm','Web\InstantWin\FormController@confirm')->middleware(['scopes:instant-win,form']);
        Route::post('instantwin/form/post','Web\InstantWin\FormController@post')->middleware(['scopes:instant-win,post']);
        Route::get('instantwin/form/thanks','Web\InstantWin\FormController@thanks')->middleware(['scopes:thanks']);
    });

    Route::get('vote/pie/{vote_code}','Web\VoteController@pie');
});
