<?php

use Faker\Generator as Faker;
use App\User;
use App\Category;
use App\Record;

$factory->define(Record::class, function (Faker $faker) {

    $params = [
        'time_start' => now()->subMinutes(rand(10, 20)),
        'time_end' => now()->subMinutes(rand(0, 10)),
        'description' => $faker->sentence(rand(2, 5))
    ];

    if (auth()->check()) {
        $params['user_id'] = auth()->id();
        $params['category_id'] = create(Category::class)->id;
    }

    return $params;
});

$factory->state(Record::class, 'withUserAndCategory', function () {
    $user = create(User::class);
    $category = create(Category::class, ['user_id' => $user->id]);

    return [
        'user_id' => $user->id,
        'category_id' => $category->id,
    ];
});
