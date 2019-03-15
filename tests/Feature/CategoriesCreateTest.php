<?php

namespace Tests\Feature;

use App\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriesCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_create_a_category()
    {
        $category = make(Category::class);
        $this->post(route('api.categories.store'), $category->toArray())
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_create_a_category()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $category = make(Category::class);

        $response = $this->post(route('api.categories.store'), $category->toArray())
            ->assertStatus(201)
            ->assertJsonFragment([
                'user_id' => auth()->id(),
                'name' => $category->name,
            ]);

        $this->assertDatabaseHas('categories',  ['name' => $category->name]);

        $this->assertEquals(1, auth()->user()->categories()->count());
    }

    /** @test */
    public function category_must_have_name()
    {
        $this->signIn();

        $category = make(Category::class, ['name' => null]);

        $this->post(route('api.categories.store'), $category->toArray())
            ->assertSessionHasErrors('name');
    }
}
