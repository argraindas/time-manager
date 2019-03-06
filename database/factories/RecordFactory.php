<?php

use Faker\Generator as Faker;

$factory->define(App\Record::class, function (Faker $faker) {
    return [
        // Todo: create category dynamically
        'category_id' => 1,
        'time_start' => now()->subMinutes(rand(1, 15)),
//        'time_end' => now(),
        'description' => $faker->sentence(rand(2, 5))
    ];
});
