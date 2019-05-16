<?php

namespace App\Http\Controllers\Api;

use App\Card;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Response;

class CardParticipantsController extends Controller
{
    /**
     * @param Card $card
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Card $card)
    {
        $this->authorize('assign', $card);

        return UserResource::collection(
            $card->availableUsers()->except(auth()->id())
        );
    }

    /**
     * @param Card $card
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Card $card)
    {
        $this->authorize('assign', $card);

        $validData = request()->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        // TODO: protect against unexpected users

        $user = $card->assignParticipant(User::findOrFail($validData['user_id']));

        return response([
            'status' => 'success',
            'message' => 'New participant was assigned!',
            'user' => new UserResource($user),
        ], Response::HTTP_CREATED);
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
