<?php

namespace App\Policies;

use App\User;
use App\Card;
use Illuminate\Auth\Access\HandlesAuthorization;

class CardPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the card.
     *
     * @param  User   $user
     * @param  Card   $card
     *
     * @return bool
     */
    public function update(User $user, Card $card)
    {
        return $card->creator_id == $user->id;
    }

}
