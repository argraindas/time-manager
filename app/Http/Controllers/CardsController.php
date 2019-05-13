<?php

namespace App\Http\Controllers;

use App\Http\Resources\CardResource;

class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $cards = $user->cards()->orParticipant($user)->with('participants')->get();

        return view('cards.index', [
            'cardsResource' => CardResource::collection($cards)->response()->getContent()
        ]);
    }

}
