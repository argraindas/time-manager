<?php

use Faker\Generator as Faker;
use App\User;
use App\Category;

$factory->define(App\Record::class, function (Faker $faker) {
    $user = factory(User::class)->create();

    return [
        'category_id' => function () use ($user) {
            return factory(Category::class)->create(['user_id' => $user->id])->id;
        },
        'user_id' => $user->id,
        'time_start' => now()->subMinutes(rand(1, 15)),
//        'time_end' => now(),
        'description' => $faker->sentence(rand(2, 5))
    ];
});
