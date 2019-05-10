<?php

namespace Tests\Feature\Card;

use App\Card;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssignParticipantsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_and_unauthorized_user_can_not_assign_participant()
    {
        $this->markTestSkipped();

        $this->signIn();

        $card = create(Card::class);
    }

    /** @test */
    public function creator_can_assign_participants()
    {
        $this->markTestSkipped();

        $this->signIn();

        $card = create(Card::class);
    }

}
