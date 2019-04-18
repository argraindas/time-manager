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
        factory(Record::class, 2)
            ->states('withUserAndCategory')
            ->create();

        $this->signIn();

        create(Record::class, [
            'category_id' => create(Category::class)->id
        ], 2);

        $this->assertEquals(4, Record::all()->count());

        $response = $this->getJson(route('api.records'))->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['meta']['total']);
    }
}
