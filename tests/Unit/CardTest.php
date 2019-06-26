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
    public function in_has_participants()
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

        $this->assertCount(3, User::all());

        $card->assignParticipant($participant_1);
        $card->assignParticipant($participant_2);

        $this->assertCount(2, $card->participants);

        $card->removeParticipant($participant_1);

        $this->assertCount(1, $card->fresh()->participants);

        $this->assertCount(3, User::all());
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
        /** @var Task $task */
        $task = $card->addTask(make(Task::class)->toArray())->fresh();
        /** @var User $particiant */
        $particiant = create(User::class);
        /** @var User $user */
        $user = auth()->user();

        $card->assignParticipant($particiant);

        $cards = $user->cards()->orParticipant($user)->get();
        $jsonResource = CardResource::collection($cards)->response()->getContent();

        $this->assertJson($jsonResource);

        $cardsArr = json_decode($jsonResource, true);

        $this->assertEquals([
            'data' => [
                [
                    'uuid' => $card->uuid,
                    'name' => $card->name,
                    'description' => $card->description,
                    'creator' => [
                        'id' => $card->creator->id,
                        'name' => $card->creator->name,
                    ],
                    'status' => $card->status,
                    'created_at' => $card->created_at->toISOString(),
                    'tasks' => [
                        [
                            'id' => $task->id,
                            'card_uuid' => $card->uuid,
                            'name' => $task->name,
                            'status' => $task->status,
                        ],
                    ],
                    'participants' => [
                        [
                            'id' => $particiant->id,
                            'name' => $particiant->name,
                        ],
                    ]
                ],
            ],
        ], $cardsArr);
    }

    /** @test */
    public function it_knows_users_that_are_not_assigned()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);
        create(User::class, [], 2);

        $this->assertCount(3, User::all());
        $this->assertCount(3, $card->availableUsers());

        $particiant = $card->availableUsers()->last();
        $card->assignParticipant($particiant);

        $this->assertCount(2, $card->availableUsers());

        $this->assertCount(1, $card->availableUsers()->except(auth()->id()));
    }

    /** @test */
    public function it_knows_adjustments()
    {
        $this->signIn();

        $before = [
            'name' => 'My name',
            'description' => 'My description',
        ];

        $after = [
            'name' => 'New name 1',
            'description' => 'New description 1',
        ];

        /** @var Card $card */
        $card = create(Card::class, $before);

        $card->name = $after['name'];
        $card->description = $after['description'];
        $card->save();

        $this->assertCount(1, $card->fresh()->adjustments);

        $adjustment = $card->adjustments->first();

        $this->assertEquals(json_encode($before), $adjustment->changes->before);
        $this->assertEquals(json_encode($after), $adjustment->changes->after);
        $this->assertEquals(auth()->id(), $adjustment->changes->user_id);

        // Another user makes changes
        $this->signIn(create(User::class));

        $after_2 = [
            'name' => 'New name 2',
            'description' => 'New description 2',
        ];

        $card->name = $after_2['name'];
        $card->description = $after_2['description'];
        $card->save();

        $adjustments = $card->fresh()->adjustments;

        $this->assertCount(2, $adjustments);

        $latestAdjustment = $adjustments[0];

        $this->assertEquals(json_encode($after), $latestAdjustment->changes->before);
        $this->assertEquals(json_encode($after_2), $latestAdjustment->changes->after);
        $this->assertEquals(auth()->id(), $latestAdjustment->changes->user_id);

        $after_3 = [
            'name' => 'New name 3',
            'description' => 'New description 3',
        ];

        $card->name = $after_3['name'];
        $card->description = $after_3['description'];
        $card->save();

        $adjustments = $card->fresh()->adjustments;

        $this->assertCount(3, $adjustments);

        $latestAdjustment = $adjustments[0];

        $this->assertEquals(json_encode($after_2), $latestAdjustment->changes->before);
        $this->assertEquals(json_encode($after_3), $latestAdjustment->changes->after);
        $this->assertEquals(auth()->id(), $latestAdjustment->changes->user_id);
    }

}
