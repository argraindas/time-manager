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
        $this->post(route('categories.store'), $category->toArray())
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_create_a_category()
    {
        $this->signIn();

        $category = make(Category::class);

        $response = $this->post(route('categories.store'), $category->toArray());

        $response->assertStatus(302);
        $response->assertRedirect(route('categories'));
        $response->assertSessionHas('flash', 'Category successfully created!');

        $this->assertDatabaseHas('categories',  ['name' => $category->name]);
    }

    /** @test */
    public function category_must_have_name()
    {
        $this->signIn();

        $category = make(Category::class, ['name' => null]);

        $this->post(route('categories.store'), $category->toArray())
            ->assertSessionHasErrors('name');
    }
}
