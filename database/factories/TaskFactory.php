<?php

use Faker\Generator as Faker;
use App\Card;
use App\Task;
use App\User;

$factory->define(Task::class, function (Faker $faker) {
    $params = [
        'name' => 'Task: ' . $faker->unique()->sentence(rand(2, 5), false),
    ];

    if (auth()->check()) {
        $params['creator_id'] = auth()->id();
    }

    return $params;
});
