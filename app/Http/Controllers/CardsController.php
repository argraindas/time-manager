<?php

namespace App\Http\Controllers;

use App\Http\Resources\CardResource;
use App\User;

class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        $cards = $user->cards()->orParticipant($user)->get();

        return view('cards.index', [
            'cardsResource' => CardResource::collection($cards)->response()->getContent()
        ]);
    }

}
