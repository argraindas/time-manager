<?php

namespace Tests\Feature\Card;

use App\Card;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangeStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_and_unauthorized_user_can_not_change_status()
    {
        $this->markTestSkipped();

        $this->signIn();

        $card = create(Card::class);
    }

    /** @test */
    public function creator_and_participants_can_change_status()
    {
        $this->markTestSkipped();

        $this->signIn();

        $card = create(Card::class);
    }

}
