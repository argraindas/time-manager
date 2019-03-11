<?php

namespace Tests\Feature;

use App\Category;
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

        $record = factory(Record::class)
            ->states('withUserAndCategory')
            ->make(['description' => 'This is task description']);

        $response = $this->post('/records', $record->toArray());
        
        $response->assertStatus(302);
        $response->assertRedirect(route('records'));
        $this->assertDatabaseHas('records', ['description' => 'This is task description']);

        $record = factory(Record::class)
            ->states('withUserAndCategory')
            ->make();

        $this->post('/records', $record->toArray());

        $this->assertEquals(2, auth()->user()->fresh()->records->count());
    }

    /** @test */
    public function user_can_see_all_his_and_only_his_records()
    {
        $this->signIn();

        $category = create(Category::class, [
            'user_id' => auth()->id(),
        ]);

        $record = create(Record::class, [
            'user_id' => auth()->id(),
            'category_id' => $category->id
        ]);

        $record2 = create(Record::class, [
            'user_id' => auth()->id(),
            'category_id' => $category->id
        ]);

        $recordNotAuthUser = factory(Record::class)
            ->states('withUserAndCategory')
            ->create();

        $this->get(route('records'))
            ->assertSee($record->description)
            ->assertSee($record2->description)
            ->assertDontSee($recordNotAuthUser->description);
    }

    /** @test */
    public function record_must_have_a_category()
    {
        $this->signIn();

        $record = factory(Record::class)
            ->states('withUserAndCategory')
            ->create();

        $this->assertInstanceOf(Category::class, $record->category);
    }
}
