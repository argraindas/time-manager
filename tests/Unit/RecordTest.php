<?php

namespace Tests\Unit;

use App\Category;
use App\Record;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_must_have_a_category()
    {
        $this->signIn();

        $record = factory(Record::class)
            ->states('withUserAndCategory')
            ->create();

        $this->assertInstanceOf(Category::class, $record->category);
    }

    /** @test */
    public function it_emits_sensitive_data()
    {
        $this->signIn();

        $record = create(Record::class);
        
        // get paginated categories
        $this->getJson(route('api.records', ['page' => 1]))
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'description',
                        'category' => [
                            'id',
                            'name'
                        ],
                        'time_start',
                        'time_end',
                    ]
                ],
                'links',
                'meta',
            ])
            ->assertJsonFragment([
                'data' => [
                    [
                        'id' => $record->id,
                        'description' => $record->description,
                        'category' => [
                            'id' => $record->category->id,
                            'name' => $record->category->name,
                        ],
                        'time_start' => $record->time_start,
                        'time_end' => $record->time_end,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_has_correct_datetime()
    {
        $this->signIn();

        create(Record::class);

        $response = $this->getJson(route('api.records', ['page' => 1]))->json();
        $record = collect($response['data'])->first();

        $this->assertTrue(Carbon::hasFormat($record['time_start'], 'Y-m-d H:i:s'));
        $this->assertTrue(Carbon::hasFormat($record['time_end'], 'Y-m-d H:i:s'));
    }
}
