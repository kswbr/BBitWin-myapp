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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
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

$factory->define(App\Repositories\Eloquent\Models\Lottery::class, function (Faker\Generator $faker) {
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
        "user_type" => config("project.sample.user_type.send"),
        "state" => config("campaign.entry.state.win"),
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'lottery_code' => function () {
            return factory(App\Repositories\Eloquent\Models\Lottery::class)->create()->code;
        }
    ];
});

$factory->define(App\Vote::class, function (Faker\Generator $faker) {
    return [
        "name" => "テストキャンペーン",
        "choice" => "sample_1,TEST\nsample_2,TEST2\nsample_3,TEST3\n\n",
        'code' => uniqid(rand()),
        "active" => true,
        "start_date" => Carbon::yesterday(),
        "end_date" => Carbon::tomorrow(),
        "project" => "testcampaign",
    ];
});

$factory->define(App\Vote\Count::class, function (Faker\Generator $faker) {
    return [
        "choice" => "sample_1",
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'vote_code' => function () {
            return factory(App\Vote::class)->create()->code;
        }
    ];
});