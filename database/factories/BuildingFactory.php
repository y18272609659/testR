<?php

use Faker\Generator;
use Faker\Factory;

$factory->define(App\Models\Building::class, function (Generator $faker) {
    return [
        'userId' => $_COOKIE['id'],
    ];
});
