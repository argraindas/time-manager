<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'name' => trim($faker->sentence(rand(1, 2)), '.'),
    ];
});
