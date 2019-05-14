<?php

use Faker\Generator as Faker;
use App\Task;

$factory->define(Task::class, function (Faker $faker) {
    $params = [
        'name' => 'Task: ' . $faker->unique()->sentence(rand(2, 5), false),
        'status' => Task::STATUS_NEW,
    ];

    if (auth()->check()) {
        $params['creator_id'] = auth()->id();
    }

    return $params;
});
