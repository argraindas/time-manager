<?php

use Faker\Generator as Faker;
use App\User;
use App\Category;

$factory->define(Category::class, function (Faker $faker) {
    $params = [
        'name' => rtrim($faker->unique()->sentence(rand(1, 2), false), '.'),
    ];

    if (auth()->check()) {
        $params['user_id'] = auth()->id();
    }

    return $params;
});

$factory->state(Category::class, 'withUser', function () {
    $user = create(User::class);

    return [
        'user_id' => $user->id,
    ];
});
