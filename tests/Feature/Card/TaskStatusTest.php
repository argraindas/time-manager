<?php

namespace Tests\Feature\Card;

use App\Card;
use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_and_unauthorized_user_can_not_change_status()
    {
        /** @var Card $card */
        $card = create(Card::class);
        /** @var Task $task */
        $task = create(Task::class, ['card_id' => $card->id, 'creator_id' => $card->creator_id]);

        $this->patch(route('api.taskStatus.update', [$task]), ['status' => Task::STATUS_DONE])
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->patch(route('api.taskStatus.update', [$task]), ['status' => Task::STATUS_DONE])
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertTrue($task->isNew());
    }

    /** @test */
    public function creator_can_change_status()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);
        /** @var Task $task */
        $task = create(Task::class, ['card_id' => $card->id]);

        $this->assertTrue($task->isNew());

        $this->patch(route('api.taskStatus.update', [$task]), ['status' => Task::STATUS_IN_PROGRESS])
            ->assertStatus(Response::HTTP_OK);

        $this->assertTrue($task->fresh()->isInProgress());

        $this->patch(route('api.taskStatus.update', [$task]), ['status' => Task::STATUS_DONE])
            ->assertStatus(Response::HTTP_OK);

        $this->assertTrue($task->fresh()->isDone());

        $this->patch(route('api.taskStatus.update', [$task]), ['status' => Task::STATUS_REJECTED])
            ->assertStatus(Response::HTTP_OK);

        $this->assertTrue($task->fresh()->isRejected());

        $this->patch(route('api.taskStatus.update', [$task]), ['status' => Task::STATUS_NEW])
            ->assertStatus(Response::HTTP_OK);

        $this->assertTrue($task->fresh()->isNew());
    }

    /** @test */
    public function participant_can_change_status()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);
        /** @var Task $task */
        $task = create(Task::class, ['card_id' => $card->id]);

        $participant = create(User::class);
        $this->signIn($participant);

        $this->patch(route('api.taskStatus.update', [$task]), ['status' => Task::STATUS_DONE])
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertTrue($task->isNew());

        $card->assignParticipant($participant);

        $this->patch(route('api.taskStatus.update', [$task]), ['status' => Task::STATUS_DONE])
            ->assertStatus(Response::HTTP_OK);

        $this->assertTrue($task->fresh()->isDone());
    }
}
