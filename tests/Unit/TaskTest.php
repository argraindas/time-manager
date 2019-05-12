<?php

namespace Tests\Unit;

use App\Card;
use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_creator()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);
        $task = create(Task::class, ['card_id' => $card->id]);

        $this->assertInstanceOf(User::class, $task->creator);
    }

    /** @test */
    public function it_belongs_to_card()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);
        $task = create(Task::class, ['card_id' => $card->id]);

        $this->assertInstanceOf(Card::class, $task->card);
    }

}
