<?php

namespace Tests\Feature;

use App\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryReadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_get_all_his_categories()
    {
        $this->signIn();

        create(Category::class, ['user_id' => auth()->id()], 2);

        $response = $this->getJson(route('api.categories'))->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }

}
