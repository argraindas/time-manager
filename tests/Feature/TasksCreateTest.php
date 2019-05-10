<?php

namespace Tests\Feature;

use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class TasksCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_create_a_task()
    {
        $task = factory(Task::class)->state('withCardAndUser')->make();

        $this->post(route('api.tasks.store'), $task->toArray())
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_create_a_task()
    {
        $this->signIn();

        $task = factory(Task::class)->state('withCardAndUser')->make();

        $this->post(route('api.tasks.store'), $task->toArray())
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('Tasks',  ['name' => $task->name]);
        $this->assertEquals(1, auth()->user()->cards->each->tasks()->count());
    }

    /** @test */
    public function task_name_must_be_valid_and_sanitized()
    {
        $this->signIn();

        $task = factory(Task::class)->state('withCardAndUser')->make(['name' => null]);

        $this->post(route('api.tasks.store'), $task->toArray())
            ->assertSessionHasErrors(['name' => 'Task name is required!']);

        // sanitizing input
        $unsanitizedName = ' <div>my test Task</div> ';
        $sanitizedName = 'My test Task';

        $task = factory(Task::class)->state('withCardAndUser')->make(['name' => $unsanitizedName]);

        $this->post(route('api.tasks.store'), $task->toArray())
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertEquals($sanitizedName, Task::first()->name);
    }


    /** @test */
    public function guest_and_unauthorized_user_can_not_delete_task()
    {
        $task = factory(Task::class)->state('withCardAndUser')->create();

        $this->delete(route('api.tasks.destroy', $task->id))
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->delete(route('api.tasks.destroy', $task->id))
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->deleteJson(route('api.tasks.destroy', $task->id))
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function user_can_delete_task()
    {
        $this->signIn();

        $task = factory(Task::class)->state('withCardAndUser')->create();

        $this->assertEquals(1,  auth()->user()->cards->each->tasks()->count());

        $request = $this->delete(route('api.tasks.destroy',  $task->id));

        $request->assertStatus(Response::HTTP_OK);
        $request->assertJsonFragment([
            'status' => 'success',
            'message' => 'Task was successfully deleted!',
        ]);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
        $this->assertEquals(0, Task::all()->count());
    }

    /** @test */
    public function user_can_update_task()
    {
        $this->signIn();

        /** @var Task $task */
        $task = factory(Task::class)->state('withCardAndUser')->create();

        $newData = [
            'name' => 'New name',
            'card_id' => $task->card_id,
        ];

        $this->patch(route('api.tasks.update', $task->id), $newData)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => 'success',
                'message' => 'Task was successfully updated!',
            ]);

        tap($task->fresh(), function ($task) use ($newData){
            $this->assertEquals($newData['name'], $task->name);
        });
    }

    /** @test */
    public function guest_and_unauthorized_user_can_not_update_task()
    {
        /** @var Task $task */
        $task = factory(Task::class)->state('withCardAndUser')->create();

        $this->patch(route('api.tasks.update', $task->id), ['name' => 'New name'])
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->patch(route('api.tasks.update', $task->id), ['name' => 'New name'])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function authorized_user_passes_validation_on_task_update()
    {
        $this->signIn();

        /** @var Task $task */
        $task = factory(Task::class)->state('withCardAndUser')->create();

        $this->patch(route('api.tasks.update', $task->id), [
            'name' => null,
        ])->assertSessionHasErrors('name');

        $this->patch(route('api.tasks.update', $task->id), [
            'name' => 'aa',
        ])->assertSessionHasErrors('name');

        $this->patch(route('api.tasks.update', $task->id), [
            'name' => 'New name',
            'card_id' => $task->card_id,
        ])->assertStatus(Response::HTTP_OK);
    }

}
