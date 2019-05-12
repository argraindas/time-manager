<?php

namespace Tests\Feature\Card;

use App\Card;
use App\Task;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class TasksBasicsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_and_unauthorized_user_can_not_create_a_task()
    {
        $card = create(Card::class);
        $task = make(Task::class);

        $this->post(route('api.tasks.store', $card), $task->toArray())
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->post(route('api.tasks.store', $card), $task->toArray())
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertCount(0, Task::all());
    }

    /** @test */
    public function creator_can_add_task()
    {
        $this->signIn();

        $card = create(Card::class);
        $task = make(Task::class);

        $this->post(route('api.tasks.store', $card), $task->toArray())
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => 'success',
                'message' => 'Task was successfully created!',
            ]);

        $this->assertDatabaseHas('Tasks',  ['name' => $task->name]);
        $this->assertEquals(1, auth()->user()->cards->each->tasks()->count());
    }

    /** @test */
    public function participant_can_add_task()
    {
        /** @var Card $card */
        $card = create(Card::class);
        $task = make(Task::class);

        $participant = create(User::class);

        $this->signIn($participant);

        $this->post(route('api.tasks.store', $card), $task->toArray())
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertCount(0, $card->fresh()->tasks);

        $card->assignParticipant($participant);

        $this->post(route('api.tasks.store', $card), $task->toArray())
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertCount(1, $card->fresh()->tasks);
    }

    /** @test */
    public function task_validation_on_create()
    {
        $this->signIn();

        $card = create(Card::class);
        $task = make(Task::class);

        // sanitizing input
        $unsanitizedName = ' <div>my test Task</div> ';
        $sanitizedName = 'My test Task';

        $this->post(route('api.tasks.store', $card), array_merge($task->toArray(), [
            'name' => null
        ]))->assertSessionHasErrors(['name' => 'Task name is required!']);

        $this->post(route('api.tasks.store', $card), array_merge($task->toArray(), [
            'name' => $unsanitizedName
        ]))->assertStatus(Response::HTTP_CREATED);

        $this->assertEquals($sanitizedName, Task::first()->name);
    }

    /** @test */
    public function guest_and_unauthorized_user_can_not_delete_task()
    {
        /** @var Card $card */
        $card = create(Card::class);
        $task = make(Task::class, ['creator_id' => $card->creator_id]);
        $card->addTask($task->toArray());

        /** @var Card $card_2 */
        $card_2 = create(Card::class);
        $task_2 = make(Task::class, ['creator_id' => $card_2->creator_id]);
        $card_2->addTask($task_2->toArray());

        $this->assertCount(1,  $card->tasks);
        $this->assertCount(1, $card_2->tasks);

        $this->delete(route('api.tasks.destroy', [$card, $card->tasks->first()]))
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->delete(route('api.tasks.destroy', [$card, $card->tasks->first()]))
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertCount(1, $card->fresh()->tasks);

        $this->signIn($card->creator);

        $this->delete(route('api.tasks.destroy', [$card, $card_2->tasks->first()]))
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertCount(1, $card_2->fresh()->tasks);
    }

    /** @test */
    public function creator_can_delete_task()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);
        $task_1 = make(Task::class);
        $task_2 = make(Task::class);

        $card->addTask($task_1->toArray());
        $card->addTask($task_2->toArray());

        $this->assertCount(2, $card->tasks);

        $this->delete(route('api.tasks.destroy', [$card, $card->tasks->first()]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => 'success',
                'message' => 'Task was successfully deleted!',
            ]);

        $this->assertCount(1,  $card->fresh()->tasks);
    }

    /** @test */
    public function participant_can_delete_task()
    {
        /** @var Card $card */
        $card = create(Card::class);
        $task = make(Task::class, ['creator_id' => $card->creator_id]);

        $card->addTask($task->toArray());

        $this->assertCount(1,  $card->tasks);

        $participant = create(User::class);

        $this->signIn($participant);

        $this->delete(route('api.tasks.destroy', [$card, $card->tasks->first()]))
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertCount(1,  $card->fresh()->tasks);

        $card->assignParticipant($participant);

        $this->delete(route('api.tasks.destroy', [$card, $card->tasks->first()]))
            ->assertStatus(Response::HTTP_OK);

        $this->assertCount(0,  $card->fresh()->tasks);
    }

    /** @test */
    public function guest_and_unauthorized_user_can_not_update_task()
    {
        /** @var Card $card */
        $card = create(Card::class);
        $task = make(Task::class, ['creator_id' => $card->creator_id]);
        $card->addTask($task->toArray());

        /** @var Card $card_2 */
        $card_2 = create(Card::class);
        $task_2 = make(Task::class, ['creator_id' => $card_2->creator_id]);
        $card_2->addTask($task_2->toArray());

        $newData = [
            'name' => 'New name'
        ];

        $this->patch(route('api.tasks.update', [$card, $card->tasks->first()]), $newData)
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->patch(route('api.tasks.update', [$card, $card->tasks->first()]), $newData)
            ->assertStatus(Response::HTTP_FORBIDDEN);

        tap($card->fresh()->tasks->first(), function ($task) use ($newData){
            $this->assertNotEquals($newData['name'], $task->name);
        });

        $this->signIn($card->creator);

        $this->patch(route('api.tasks.update', [$card, $card_2->tasks->first()]), $newData)
            ->assertStatus(Response::HTTP_FORBIDDEN);

        tap($card_2->fresh()->tasks->first(), function ($task) use ($newData){
            $this->assertNotEquals($newData['name'], $task->name);
        });
    }

    /** @test */
    public function creator_can_update_task()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);
        $task = make(Task::class);
        $card->addTask($task->toArray());

        $newData = [
            'name' => 'New name'
        ];

        $this->patch(route('api.tasks.update', [$card, $card->tasks->first()]), $newData)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => 'success',
                'message' => 'Task was successfully updated!',
            ]);
        
        tap($card->fresh()->tasks->first(), function ($task) use ($newData){
            $this->assertEquals($newData['name'], $task->name);
        });
    }

    /** @test */
    public function participant_can_update_task()
    {
        /** @var Card $card */
        $card = create(Card::class);
        $task = make(Task::class, ['creator_id' => $card->creator_id]);
        $card->addTask($task->toArray());

        $participant = create(User::class);

        $this->signIn($participant);

        $newData = [
            'name' => 'New name'
        ];

        $this->patch(route('api.tasks.update', [$card, $card->tasks->first()]), $newData)
            ->assertStatus(Response::HTTP_FORBIDDEN);

        tap($card->fresh()->tasks->first(), function ($task) use ($newData){
            $this->assertNotEquals($newData['name'], $task->name);
        });

        $card->assignParticipant($participant);

        $this->patch(route('api.tasks.update', [$card, $card->tasks->first()]), $newData)
            ->assertStatus(Response::HTTP_OK);

        tap($card->fresh()->tasks->first(), function ($task) use ($newData){
            $this->assertEquals($newData['name'], $task->name);
        });
    }

    /** @test */
    public function task_validation_on_update()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);
        $task = make(Task::class);
        $card->addTask($task->toArray());

        // sanitizing input
        $unsanitizedName = ' <div>my test Task</div> ';
        $sanitizedName = 'My test Task';

        $task = $card->tasks->first();

        $this->patch(route('api.tasks.update', [$card, $task]), array_merge($task->toArray(), [
            'name' => null
        ]))->assertSessionHasErrors(['name' => 'Task name is required!']);

        $this->patch(route('api.tasks.update', [$card, $task]), array_merge($task->toArray(), [
            'name' => 'aa'
        ]))->assertSessionHasErrors('name');

        $this->patch(route('api.tasks.update', [$card, $task]), array_merge($task->toArray(), [
            'name' => $unsanitizedName
        ]))->assertStatus(Response::HTTP_OK);

        $this->assertEquals($sanitizedName, Task::first()->name);
    }
}
