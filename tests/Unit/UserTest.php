<?php

namespace Tests\Unit;

use App\Category;
use App\Record;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_has_records()
    {
        $this->signIn();

        create(Record::class, [
            'user_id' => auth()->id(),
            'category_id' => create(Category::class, ['user_id' => auth()->id()])->id,
        ], 2);

        $this->assertEquals(2, auth()->user()->fresh()->records->count());
    }


}
