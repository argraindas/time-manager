<?php

namespace App\Http\Controllers\Api;

use App\Card;
use App\Http\Controllers\Controller;
use App\User;

class CardParticipantsController extends Controller
{

    /**
     * @param Card $card
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Card $card)
    {
        $this->authorize('assign', $card);

        $validData = request()->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $card->assignParticipant(User::findOrFail($validData['user_id']));
    }

    /**
     * @param Card $card
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Card $card)
    {
        $this->authorize('assign', $card);

        $validData = request()->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $card->removeParticipant(User::findOrFail($validData['user_id']));
    }

}
