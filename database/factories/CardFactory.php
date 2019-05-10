<?php

use Faker\Generator as Faker;
use App\Card;
use App\User;

$factory->define(Card::class, function (Faker $faker) {
    return [
        'name' => rtrim($faker->unique()->sentence(rand(4, 5), false), '.'),
        'description' => $faker->unique()->sentence(),
        'creator_id' => auth()->check() ? auth()->id() : create(User::class)->id,
    ];
});
