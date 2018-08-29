<?php

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Repositories\Eloquent\Models\Player::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'provider_id' => uniqid(rand()),
        'etc_data' => ["test" => 1],
        'provider' => 'twitter',
        'type' => 1,
        'project' => 'InstantWin'
    ];
});


$factory->define(App\Repositories\Eloquent\Models\Campaign::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'limited_days' => 1,
        'code' => uniqid(rand()),
        'project' => $faker->word,
    ];
});

$factory->define(App\Repositories\Eloquent\Models\Campaign\Serial::class, function (Faker\Generator $faker) {
    return [
        'campaign_code' => function () {
            return factory(App\Repositories\Eloquent\Models\Campaign::class)->create()->code;
        },
        'total' => 100
    ];
});

$factory->define(App\Repositories\Eloquent\Models\Campaign\Serial\Number::class, function (Faker\Generator $faker) {
    return [
        'serial_id' => function () {
            return factory(App\Repositories\Eloquent\Models\Campaign\Serial::class)->create()->id;
        },
        'player_id' => function () {
            return factory(App\Repositories\Eloquent\Models\Player::class)->create()->id;
        },
        'number' => rand ( 1 , 99999999)
    ];
});


$factory->define(App\Repositories\Eloquent\Models\Lottery::class, function (Faker\Generator $faker) {
    $dt = Carbon::parse();
    return [
        'name' => $faker->name,
        'code' => uniqid(rand()),
        'rate' => 10.5,
        'total' => 100,
        'limit' => 10,
        'start_date' => date("Y-m-d H:i:s",strtotime("-1 day")),
        'end_date' => date("Y-m-d H:i:s",strtotime("+1 day")),
        'active' => true,
        'order' => 0,
        "daily_increment" => 10,
        "daily_increment_time" => $dt->hour,
        "run_time" => Carbon::yesterday(),
        'campaign_code' => function () {
            return factory(App\Repositories\Eloquent\Models\Campaign::class)->create()->code;
        }
    ];
});

$factory->define(App\Repositories\Eloquent\Models\Lottery\Update::class, function (Faker\Generator $faker) {
    $dt = Carbon::parse();
    return [
        "daily_increment" => 10,
        "daily_increment_time" => $dt->hour,
        "run_time" => Carbon::yesterday(),
        'lottery_id' => function () {
            return factory(App\Repositories\Eloquent\Models\Lottery::class)->create()->id;
        }
    ];
});

$factory->define(App\Repositories\Eloquent\Models\Entry::class, function (Faker\Generator $faker) {
    return [
        "player_type" => 1,
        "state" => 1,
        'player_id' => function () {
            return factory(App\Repositories\Eloquent\Models\Player::class)->create()->id;
        },
        'lottery_code' => function () {
            return factory(App\Repositories\Eloquent\Models\Lottery::class)->create()->code;
        }
    ];
});

$factory->define(App\Repositories\Eloquent\Models\Vote::class, function (Faker\Generator $faker) {
    return [
        "name" => "テストキャンペーン",
        "choice" => "sample_1,TEST\nsample_2,TEST2\nsample_3,TEST3\n\n",
        'code' => uniqid(rand()),
        "active" => true,
        "start_date" => (string)Carbon::yesterday(),
        "end_date" => (string)Carbon::tomorrow(),
        "project" => "testcampaign",
    ];
});

$factory->define(App\Repositories\Eloquent\Models\Vote\Count::class, function (Faker\Generator $faker) {
    return [
        "choice" => "sample_1",
        // 'player_id' => function () {
        //     return factory(App\Repositories\Eloquent\Models\Player::class)->create()->id;
        // },
        'vote_code' => function () {
            return factory(App\Repositories\Eloquent\Models\Player::class)->create()->code;
        }
    ];
});
