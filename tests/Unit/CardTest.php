<?php

namespace Tests\Unit;

use App\Card;
use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_creator()
    {
        $this->signIn();

        $card = create(Card::class);

        $this->assertInstanceOf(User::class, $card->creator);
    }

    /** @test */
    public function it_has_tasks()
    {
        $this->signIn();

        factory(Task::class, 2)->state('withCardAndUser')->create();

        $this->assertCount(2, auth()->user()->cards->each->count());
    }

    /** @test */
    public function it_can_assign_participants()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);

        $participant_1 = create(User::class);
        $participant_2 = create(User::class);

        $card->assignParticipant($participant_1);
        $card->assignParticipant($participant_2);

        $this->assertCount(2, $card->participants);
    }

    /** @test */
    public function it_can_remove_participants()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);

        $participant_1 = create(User::class);
        $participant_2 = create(User::class);

        $card->assignParticipant($participant_1);
        $card->assignParticipant($participant_2);

        $this->assertCount(2, $card->participants);

        $card->removeParticipant($participant_1);

        $this->assertCount(1, $card->fresh()->participants);
    }
}
