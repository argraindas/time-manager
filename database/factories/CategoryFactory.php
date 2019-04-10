<?php

use Faker\Generator as Faker;
use App\User;
use App\Category;

$factory->define(Category::class, function (Faker $faker) {
    $params = [
        'name' => ucfirst($faker->unique()->word),
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
