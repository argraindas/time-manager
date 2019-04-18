<?php

namespace Tests\Unit;

use App\Category;
use App\Record;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_owner()
    {
        $this->signIn();

        $category = create(Category::class);

        $this->assertInstanceOf(User::class, $category->user);
    }

    /** @test */
    public function it_has_records()
    {
        $this->signIn();

        $category = create(Category::class);

        create(Record::class, [
            'category_id' => $category->id,
            'user_id' => auth()->id(),
        ], 2);

        $this->assertEquals(2, $category->records->count());
    }

    /** @test */
    public function it_emits_sensitive_data()
    {
        $this->signIn();

        $category = create(Category::class);

        // get all categories
        $this->getJson(route('api.categories'))
            ->assertExactJson([
                'data' => [
                    [
                        'id' => $category->id,
                        'name' => $category->name,
                    ]
                ]
            ]);

        // get paginated categories
        $this->getJson(route('api.categories', ['page' => 1]))
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name',
                    ]
                ],
                'links',
                'meta',
            ])
            ->assertJsonFragment([
                'data' => [
                    [
                        'id' => $category->id,
                        'name' => $category->name,
                    ]
                ]
            ]);
    }
}
