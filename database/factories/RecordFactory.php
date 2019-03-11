<?php

use Faker\Generator as Faker;
use App\User;
use App\Category;

$factory->define(App\Record::class, function (Faker $faker) {
    return [
        'time_start' => now()->subMinutes(rand(10, 20)),
        'time_end' => now()->subMinutes(rand(0, 10)),
        'description' => $faker->sentence(rand(2, 5))
    ];
});

$factory->state(App\Record::class, 'withUserAndCategory', function () {
    $user = factory(User::class)->create();
    $category = factory(Category::class)->create(['user_id' => $user->id]);

    return [
        'user_id' => $user->id,
        'category_id' => $category->id,
    ];
});
