<?php

use App\Card;
use Illuminate\Support\Facades\Gate;

Broadcast::channel('tasks.{card}', function ($user, Card $card) {
    return Gate::allows('taskBroadcast', [$card, $user]);
});

Broadcast::channel('users', function ($user) {
    if (auth()->check()) {
        return [
            'name' => $user->name
        ];
    }
});
