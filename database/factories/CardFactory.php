<?php

use Faker\Generator as Faker;
use App\Card;
use App\User;

$factory->define(Card::class, function (Faker $faker) {
    return [
        'name' => 'Card: ' . rtrim($faker->unique()->sentence(rand(4, 5), false), '.'),
        'description' => 'Description: ' . $faker->unique()->sentence(),
        'creator_id' => auth()->check() ? auth()->id() : create(User::class)->id,
        'status' => Card::STATUS_OPEN,
    ];
});
