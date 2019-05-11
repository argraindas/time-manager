<?php

namespace App\Http\Controllers\Api;

use App\Card;
use App\Http\Controllers\Controller;

class CardStatusController extends Controller
{

    /**
     * @param Card $card
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Card $card)
    {
        $this->authorize('changeStatus', $card);
        
        $validData = request()->validate([
            'status' => 'required'
        ]);

        switch ($validData['status']) {
            case Card::STATUS_OPEN:     $card->open(); break;
            case Card::STATUS_FINISHED: $card->finish(); break;
            case Card::STATUS_CLOSED:   $card->close(); break;
        }
    }

}
