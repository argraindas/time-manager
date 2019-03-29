<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'user_id' => auth()->id(),
        'name' => ucfirst($faker->unique()->word),
    ];
});
