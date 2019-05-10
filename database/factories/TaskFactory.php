<?php

use Faker\Generator as Faker;
use App\Card;
use App\Task;
use App\User;

$factory->define(Task::class, function (Faker $faker) {
    $params = [
        'name' => $faker->unique()->sentence(rand(2, 5), false),
    ];

    if (auth()->check()) {
        $params['creator_id'] = auth()->id();
    }

    return $params;
});

$factory->state(Task::class, 'withCardAndUser', function () {
    $user = auth()->check() ? auth()->user() : create(User::class);
    $card = create(Card::class, ['creator_id' => $user->id]);

    return [
        'card_id' => $card->id,
        'creator_id' => $user->id,
    ];
});
