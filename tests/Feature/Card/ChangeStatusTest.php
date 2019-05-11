<?php

namespace Tests\Feature\Card;

use App\Card;
use App\User;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangeStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_and_unauthorized_user_can_not_change_status()
    {
        /** @var Card $card */
        $card = create(Card::class);

        $this->assertTrue($card->isOpen());

        $this->post(route('api.cardStatus.store', $card), ['status' => Card::STATUS_FINISHED])
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->post(route('api.cardStatus.store', $card), ['status' => Card::STATUS_FINISHED])
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertTrue($card->fresh()->isOpen());
    }

    /** @test */
    public function creator_can_change_status()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);

        $this->assertEquals($card->creator->id, auth()->id());
        $this->assertTrue($card->isOpen());

        $this->post(route('api.cardStatus.store', $card), ['status' => Card::STATUS_FINISHED])
            ->assertStatus(Response::HTTP_OK);
        
        $this->assertTrue($card->fresh()->isFinished());

        $this->post(route('api.cardStatus.store', $card), ['status' => Card::STATUS_CLOSED])
            ->assertStatus(Response::HTTP_OK);

        $this->assertTrue($card->fresh()->isClosed());

        $this->post(route('api.cardStatus.store', $card), ['status' => Card::STATUS_OPEN])
            ->assertStatus(Response::HTTP_OK);

        $this->assertTrue($card->fresh()->isOpen());
    }


    /** @test */
    public function participant_can_change_status()
    {
        /** @var Card $card */
        $card = create(Card::class);

        $this->assertTrue($card->isOpen());

        $participant = create(User::class);
        $card->assignParticipant($participant);

        $participant2 = create(User::class);
        $card->assignParticipant($participant2);

        $this->signIn($participant);

        $this->post(route('api.cardStatus.store', $card), ['status' => Card::STATUS_FINISHED])
            ->assertStatus(Response::HTTP_OK);

        $this->assertTrue($card->fresh()->isFinished());
    }
}
