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

    /** @test */
    public function guest_and_unauthorized_user_can_not_delete_record()
    {
        $record = factory(Record::class)->state('withUserAndCategory')->create();

        $this->delete(route('api.records.destroy', $record->id))
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->delete(route('api.records.destroy', $record->id))
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->deleteJson(route('api.records.destroy', $record->id))
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas('records', ['id' => $record->id]);
    }

    /** @test */
    public function user_can_delete_record()
    {
        $this->signIn();

        $record = create(Record::class);

        $this->assertEquals(1,  auth()->user()->records()->count());

        $request = $this->delete(route('api.records.destroy',  $record->id));

        $request->assertStatus(Response::HTTP_OK);
        $request->assertJsonFragment([
            'status' => 'success',
            'message' => 'Record was successfully deleted!',
        ]);

        $this->assertDatabaseMissing('records', ['id' => $record->id]);
        $this->assertEquals(0, auth()->user()->records()->count());
    }

    /** @test */
    public function user_can_update_record()
    {
        $this->signIn();

        /** @var Record $record */
        $record = create(Record::class);

        $newData = [
            'description' => 'New description',
            'time_start' => now()->subMinutes(5)->toDateTimeString(),
            'time_end' => now()->toDateTimeString(),
            'category_id' => create(Category::class)->id,
        ];

        $this->patch(route('api.records.update', $record->id), $newData)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => 'success',
                'message' => 'Record was successfully updated!',
            ]);

        tap($record->fresh(), function ($record) use ($newData){
            $this->assertEquals($newData['description'], $record->description);
            $this->assertEquals($newData['time_start'], $record->time_start);
            $this->assertEquals($newData['time_end'], $record->time_end);
            $this->assertEquals($newData['category_id'], $record->category_id);
        });
    }

    /** @test */
    public function guest_and_unauthorized_user_can_not_update_record()
    {
        /** @var Record $record */
        $record = factory(Record::class)->state('withUserAndCategory')->create();

        $this->patch(route('api.records.update', $record->id), ['description' => 'New description'])
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->patch(route('api.records.update', $record->id), ['description' => 'New description'])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function authorized_user_passes_validation_on_record_update()
    {
        $this->signIn();

        /** @var Record $record */
        $record = create(Record::class);

        $this->patch(route('api.records.update', $record->id), [
            'description' => null
        ])->assertSessionHasErrors('description');

        $this->patch(route('api.records.update', $record->id), [
            'category_id' => null
        ])->assertSessionHasErrors('category_id');

        $this->patch(route('api.records.update', $record->id), [
            'time_start' => null
        ])->assertSessionHasErrors('time_start');
    }
}
