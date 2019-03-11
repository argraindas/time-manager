<?php

namespace Tests\Feature;

use App\Category;
use App\Record;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecordsReadTest extends TestCase
{
    use RefreshDatabase;

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
}
