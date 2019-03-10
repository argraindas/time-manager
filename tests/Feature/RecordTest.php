<?php

namespace Tests\Feature;

use App\Record;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_add_record()
    {
        $record = make(Record::class);
        $response = $this->post('/records', $record->toArray());

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_add_new_record()
    {
        $this->signIn();

        $this->withoutExceptionHandling();

        $record = make(Record::class, ['description' => 'This is task description']);
        $response = $this->post('/records', $record->toArray());
        
        $response->assertStatus(302);
        $response->assertRedirect(route('record'));
        $this->assertDatabaseHas('records', ['description' => 'This is task description']);

        $record = make(Record::class);
        $this->post('/records', $record->toArray());

        $this->assertEquals(2, auth()->user()->fresh()->records->count());
    }

    /** @test */
    public function user_can_see_all_his_and_only_his_records()
    {
        $this->signIn();

        $record = create(Record::class, ['user_id' => auth()->id()]);
        $record2 = create(Record::class, ['user_id' => auth()->id()]);
        $recordNotAuthUser = create(Record::class);

        $this->get(route('record'))
            ->assertSee($record->description)
            ->assertSee($record2->description)
            ->assertDontSee($recordNotAuthUser->description);
    }
}
