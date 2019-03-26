<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'user_id' => auth()->id(),
        'name' => trim($faker->sentence(rand(1, 2)), '.'),
    ];
});
