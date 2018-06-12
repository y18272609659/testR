<?php

use Faker\Generator;
use Faker\Factory;

$factory->define(App\Models\BuildingList::class, function (Generator $faker) {
    return [
        'userId' => $_COOKIE['id'],
        'start' => 0,
        'end' => 0,
        'category' => '',
        'level' => 0,
        'action' => 0,
        'number' => 0,
    ];
});
