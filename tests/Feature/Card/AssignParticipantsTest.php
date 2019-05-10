<?php

namespace Tests\Feature\Card;

use App\Card;
use App\User;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssignParticipantsTest extends TestCase
{
    use RefreshDatabase;

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
    public function creator_can_assign_participants()
    {
        $this->signIn();

        $card = create(Card::class);
        $participant = create(User::class);

        $this->post(route('api.cardParticipants.store', $card), ['user_id' => $participant->id])
            ->assertStatus(Response::HTTP_OK);

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
