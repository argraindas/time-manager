<?php

namespace Tests\Feature;

use App\Category;
use App\Record;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecordsCreateTest extends TestCase
{
    use RefreshDatabase;

    protected function createRecord($overrides = [])
    {
        $this->withExceptionHandling();

        $this->signIn();

        $category = create(Category::class, ['user_id' => auth()->id()]);

        $record = make(Record::class, [
            'user_id' => auth()->id(),
            'category_id' => $category->id
        ]);

        return $this->post(route('records.store'), array_merge($record->toArray(), $overrides));
    }

    /** @test */
    public function guest_can_not_create_record()
    {
        $record = make(Record::class);
        $response = $this->post('/records', $record->toArray());

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_create_new_record()
    {
        $response = $this->createRecord(['description' => 'This is task description']);

        $response->assertStatus(302);
        $response->assertRedirect(route('records'));
        $response->assertSessionHas('flash', 'Record was successfully added!');

        $this->assertDatabaseHas('records', ['description' => 'This is task description']);
    }

    /** @test */
    function record_must_meet_requirements()
    {
        $this->createRecord(['description' => null])
            ->assertSessionHasErrors('description');

        $this->createRecord(['time_start' => null])
            ->assertSessionHasErrors('time_start');

        $this->createRecord(['category_id' => null])
            ->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function record_category_exists_and_belongs_to_user()
    {
        $this->signIn();

        $record = make(Record::class, [
            'category_id' => 999,
        ]);

        $this->post(route('records.store'), $record->toArray())
            ->assertSessionHasErrors('category_id');

        $anotherUserCategory = create(Category::class, [
            'user_id' => create(User::class)->id,
        ]);

        $record2 = make(Record::class, [
            'category_id' => $anotherUserCategory->id,
        ]);

        $this->post(route('records.store'), $record2->toArray())
            ->assertSessionHasErrors('category_id');
    }
}
