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

    /** @test */
    public function it_can_change_status()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);
        /** @var Task $task */
        $task = create(Task::class, ['card_id' => $card->id]);

        $this->assertEquals(Task::STATUS_NEW, $task->status);

        $task->inProgress();
        $this->assertEquals(Task::STATUS_IN_PROGRESS, $task->status);
        $this->assertTrue($task->isInProgress());

        $task->done();
        $this->assertEquals(Task::STATUS_DONE, $task->status);
        $this->assertTrue($task->isDone());

        $task->rejected();
        $this->assertEquals(Task::STATUS_REJECTED, $task->status);
        $this->assertTrue($task->isRejected());

        $task->new();
        $this->assertEquals(Task::STATUS_NEW, $task->status);
        $this->assertTrue($task->isNew());
    }

}
