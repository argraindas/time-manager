<?php

namespace Tests\Feature;

use App\Record;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecordsCreateTest extends TestCase
{
    use RefreshDatabase;

    protected function createRecord($overrides = [])
    {
        $this->withExceptionHandling();
        $this->signIn();

        $thread = make(Record::class, $overrides);

        return $this->post(route('records.store'), $thread->toArray());
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
        $this->signIn();

        $this->withoutExceptionHandling();

        $record = factory(Record::class)
            ->states('withUserAndCategory')
            ->make(['description' => 'This is task description']);

        $response = $this->post('/records', $record->toArray());
        
        $response->assertStatus(302);
        $response->assertRedirect(route('records'));
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

//    /** @test */
//    public function record_category_must_belong_to_user()
//    {
//
//    }
}
