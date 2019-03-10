<?php

use Faker\Generator as Faker;
use App\User;

$factory->define(App\Record::class, function (Faker $faker) {
    return [
        // Todo: create category dynamically
        'category_id' => 1,
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'time_start' => now()->subMinutes(rand(1, 15)),
//        'time_end' => now(),
        'description' => $faker->sentence(rand(2, 5))
    ];
});
