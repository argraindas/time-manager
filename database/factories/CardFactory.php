<?php

use Faker\Generator as Faker;
use App\Card;
use App\User;

$factory->define(Card::class, function (Faker $faker) {
    $params = [
        'name' => rtrim($faker->unique()->sentence(rand(4, 5), false), '.'),
        'description' => $faker->unique()->sentence(),
    ];

    if (auth()->check()) {
        $params['creator_id'] = auth()->id();
    }

    return $params;
});

$factory->state(Card::class, 'withUser', function () {
    $user = create(User::class);

    return [
        'creator_id' => $user->id,
    ];
});
