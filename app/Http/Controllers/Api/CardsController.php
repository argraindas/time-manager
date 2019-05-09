<?php

namespace App\Http\Controllers\Api;

use App\Card;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Requests\CardRequest;

class CardsController extends Controller
{
    /**
     * @param CardRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function store(CardRequest $request)
    {
        Card::create($request->validated());

        return $this->response('Card was successfully created!', 'success', Response::HTTP_CREATED);
    }

}
