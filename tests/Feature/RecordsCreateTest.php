<?php

namespace Tests\Feature;

use App\Category;
use App\Record;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class RecordsCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_create_record()
    {
        $record = factory(Record::class)->state('withUserAndCategory')->make();

        $this->post(route('api.records.store'), $record->toArray())
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_create_a_new_record()
    {
        $this->signIn();

        $record = make(Record::class);

        $response = $this->post(route('api.records.store'), $record->toArray());

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Record was successfully added!'
        ]);

        $this->assertDatabaseHas('records', ['description' => $record->description]);
    }

    /** @test */
    function record_must_meet_requirements()
    {
        $this->signIn();

        $recordArr = make(Record::class)->toArray();
        $path = route('api.records.store');

        $this->post($path, array_merge($recordArr, ['description' => null]))
            ->assertSessionHasErrors('description');

        $this->post($path, array_merge($recordArr, ['time_start' => null]))
            ->assertSessionHasErrors('time_start');

        $this->post($path, array_merge($recordArr, ['category_id' => null]))
            ->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function record_category_belongs_to_user()
    {
        $randomUser = create(User::class);
        $randomCategory = create(Category::class, ['user_id' => $randomUser->id]);

        $this->signIn();

        $record = make(Record::class, [
            'category_id' => $randomCategory->id,
        ]);

        $this->post(route('api.records.store'), $record->toArray())
            ->assertSessionHasErrors(['category_id' => 'Category is not valid!']);
        
        // check if record has correct category
        $userCategory = create(Category::class);
        
        $record2 = make(Record::class, [
            'category_id' => $userCategory->id,
        ]);

        $this->post(route('api.records.store'), $record2->toArray())
            ->assertStatus(Response::HTTP_CREATED);

        $userRecord = auth()->user()->records()->first();

        $this->assertEquals($record2->description, $userRecord->description);
        $this->assertEquals($record2->category_id, $userCategory->id);
    }
}
