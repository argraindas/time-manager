<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => $password ?: $password = bcrypt('secret'),
        'api_token' => Str::random(60),
        'remember_token' => Str::random(10),
    ];
});
