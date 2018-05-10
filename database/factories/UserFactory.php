<?php

use Faker\Generator;
use Faker\Factory;

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

$factory->define(App\User::class, function (Generator $faker) {
    static $password;
    $userPlant = 25;

    return [
        'name' => 'testUser' . ++$_COOKIE['id'],
        'nickname' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'kingdom' => $faker->country . ((rand(1,2) === 1) ? '王国' : '王朝'),
        'capital' => ceil($_COOKIE['id'] / sqrt($userPlant)) * 3 - 1 . ',' . ((($_COOKIE['id']-1) % sqrt($userPlant) + 1) * 3 - 1),
    ];
});
