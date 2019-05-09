<?php

use Faker\Generator as Faker;
use App\Card;

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
