<?php

namespace Tests\Feature\Card;

use App\Card;
use App\User;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipantBasicsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_and_unauthorized_user_can_not_get_users()
    {
        $card = create(Card::class);

        $this->get(route('api.cardParticipants', $card))
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->get(route('api.cardParticipants', $card))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function creator_can_get_only_not_assigned_users()
    {
        $this->signIn();

        create(User::class, [], 3);

        /** @var Card $card */
        $card = create(Card::class);
        $participant = create(User::class);

        $this->assertCount(5, User::all());

        $card->assignParticipant($participant);

        $response = $this->getJson(route('api.cardParticipants', $card))->json();

        $this->assertCount(3, $response['data']);

        $usersIds = collect($response['data'])->pluck('id');

        $this->assertFalse($usersIds->contains(auth()->id()));
        $this->assertFalse($usersIds->contains($participant->id));
    }

    /** @test */
    public function guest_and_unauthorized_user_can_not_assign_participant()
    {
        $card = create(Card::class);
        $participant = create(User::class);

        $this->post(route('api.cardParticipants.store', $card), ['user_id' => $participant->id])
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->post(route('api.cardParticipants.store', $card), ['user_id' => $participant->id])
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertCount(0, $card->fresh()->participants);
    }

    /** @test */
    public function creator_can_assign_only_users_that_are_not_assigned_yet()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);
        $card_2 = create(Card::class);
        $participant = create(User::class);

        $this->post(route('api.cardParticipants.store', $card), ['user_id' => $participant->id])
            ->assertStatus(Response::HTTP_CREATED);

        $this->post(route('api.cardParticipants.store', $card_2), ['user_id' => $participant->id])
            ->assertStatus(Response::HTTP_CREATED);

        $this->post(route('api.cardParticipants.store', $card_2), ['user_id' => $participant->id])
            ->assertSessionHasErrors('user_id');

        $this->assertCount(1, $card->fresh()->participants);
    }

    /** @test */
    public function guest_and_unauthorized_user_can_not_remove_participant()
    {
        $card = create(Card::class);
        $participant = create(User::class);

        $card->assignParticipant($participant);

        $this->assertCount(1, $card->participants);

        $this->delete(route('api.cardParticipants.destroy', $card), ['user_id' => $participant->id])
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->delete(route('api.cardParticipants.destroy', $card), ['user_id' => $participant->id])
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertCount(1, $card->fresh()->participants);
    }

    /** @test */
    public function creator_can_remove_participants()
    {
        $this->signIn();

        $card = create(Card::class);
        $participant = create(User::class);

        $card->assignParticipant($participant);

        $this->assertCount(1, $card->participants);

        $this->delete(route('api.cardParticipants.destroy', $card), ['user_id' => $participant->id])
            ->assertStatus(Response::HTTP_OK);

        $this->assertCount(0, $card->fresh()->participants);
    }
}
