<?php

namespace Tests\Unit;

use App\Category;
use App\Record;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecordTest extends TestCase
{
    use RefreshDatabase;

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
