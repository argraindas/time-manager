<?php

namespace Tests\Unit;

use App\Card;
use App\Http\Resources\CardResource;
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

        /** @var Card $card */
        $card = create(Card::class);
        create(Task::class, ['card_id' => $card->id], 2);

        $this->assertCount(2, $card->tasks);
        $this->assertInstanceOf(Task::class, $card->tasks->first());
    }

    /** @test */
    public function it_can_add_and_remove_tasks()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);

        $task_1 = make(Task::class);
        $task_2 = make(Task::class);

        $this->assertCount(0, $card->tasks);

        $card->addTask($task_1->toArray());
        $card->addTask($task_2->toArray());

        $this->assertCount(2, $card->fresh()->tasks);

        $card->removeTask($card->fresh()->tasks->first());

        $this->assertCount(1, $card->fresh()->tasks);
    }

    /** @test */
    public function it_can_assign_and_remove_participants()
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

    /** @test */
    public function it_can_change_status()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);

        $this->assertEquals(Card::STATUS_OPEN, $card->status);

        $card->finish();
        $this->assertEquals(Card::STATUS_FINISHED, $card->status);
        $this->assertTrue($card->isFinished());

        $card->close();
        $this->assertEquals(Card::STATUS_CLOSED, $card->status);
        $this->assertTrue($card->isClosed());

        $card->open();
        $this->assertEquals(Card::STATUS_OPEN, $card->status);
        $this->assertTrue($card->isOpen());
    }

    /** @test */
    public function it_has_correct_resource_structure()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);
        $card->addTask(make(Task::class)->toArray());

        $user = auth()->user();
        $cards = $user->cards()->orParticipant($user)->with('participants')->get();
        $jsonResource = CardResource::collection($cards)->response()->getContent();
        
        $this->assertJson($jsonResource);

        $cardsArr = json_decode($jsonResource, true);

        $this->assertEquals([
            'data' => [
                [
                    'id' => $card->id,
                    'name' => $card->name,
                    'description' => $card->description,
                    'creator' => [
                        'name' => $card->creator->name,
                    ],
                    'status' => $card->status,
                    'created_at' => $card->created_at->toISOString(),
                    'tasks' => [
                        [
                            'id' => $card->tasks->first()->id,
                            'name' => $card->tasks->first()->name,
                            'creator' => [
                                'name' => $card->tasks->first()->creator->name,
                            ],
                            'status' => $card->tasks->first()->status,
                        ],
                    ],
                ],
            ],
        ], $cardsArr);
    }

}
