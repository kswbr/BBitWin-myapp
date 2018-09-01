<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'project' => 'TESTPROJECT',
        'allow_campaign' => true,
        'allow_vote' => true,
        'allow_user' => true,
        'allow_serial_campaign' => true,
        'allow_over_project' => true,
        'role' => 1,
        'remember_token' => str_random(10),
    ];
});
