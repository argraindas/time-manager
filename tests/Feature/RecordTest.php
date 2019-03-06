<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_add_record()
    {
        $record = make('App\Record');
        $response = $this->post('/records', $record->toArray());

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_add_new_record()
    {
        $this->signIn();

        $this->withoutExceptionHandling();

        $record = make('App\Record', ['description' => 'This is task description']);
        $response = $this->post('/records', $record->toArray());
        
        $response->assertStatus(302);
        $response->assertRedirect(route('record'));
        $this->assertDatabaseHas('records', ['description' => 'This is task description']);

        $record = make('App\Record');
        $this->post('/records', $record->toArray());

        $this->assertEquals(2, auth()->user()->fresh()->records->count());
    }

}
