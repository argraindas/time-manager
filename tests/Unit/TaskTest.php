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

    /** @test */
    public function it_knows_adjustments()
    {
        $this->signIn();

        $before = [
            'name' => 'My name'
        ];

        $after = [
            'name' => 'New name 1'
        ];

        /** @var Card $card */
        $card = create(Card::class);
        /** @var Task $task */
        $task = create(Task::class, ['card_id' => $card->id, 'name' => $before['name']]);

        $task->name = $after['name'];
        $task->save();

        $this->assertCount(1, $task->fresh()->adjustments);

        $adjustment = $task->adjustments->first();
        
        $this->assertEquals(json_encode($before), $adjustment->changes->before);
        $this->assertEquals(json_encode($after), $adjustment->changes->after);
        $this->assertEquals(auth()->id(), $adjustment->changes->user_id);

        // Another user makes changes
        $this->signIn(create(User::class));

        $after_2 = [
            'name' => 'New name 2'
        ];

        $task->name = $after_2['name'];
        $task->save();


        $adjustments = $task->fresh()->adjustments;

        $this->assertCount(2, $adjustments);

        $latestAdjustment = $adjustments[0];

        $this->assertEquals(json_encode($after), $latestAdjustment->changes->before);
        $this->assertEquals(json_encode($after_2), $latestAdjustment->changes->after);
        $this->assertEquals(auth()->id(), $latestAdjustment->changes->user_id);

        $after_3 = [
            'name' => 'New name 3'
        ];

        $task->name = $after_3['name'];
        $task->save();

        $adjustments = $task->fresh()->adjustments;

        $this->assertCount(3, $adjustments);

        $latestAdjustment = $adjustments[0];

        $this->assertEquals(json_encode($after_2), $latestAdjustment->changes->before);
        $this->assertEquals(json_encode($after_3), $latestAdjustment->changes->after);
        $this->assertEquals(auth()->id(), $latestAdjustment->changes->user_id);
    }

}
