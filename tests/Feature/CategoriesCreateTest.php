<?php

namespace Tests\Feature;

use App\Category;
use App\User;
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

    /** @test */
    public function guest_and_unauthorized_user_can_not_delete_category()
    {
        $user = create(User::class);
        $category = create(Category::class, ['user_id' => $user->id]);

        $this->delete(route('api.categories.destroy', $category->id))
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->delete(route('api.categories.destroy', $category->id))
            ->assertStatus(403);

        $this->json('delete', route('api.categories.destroy', $category->id))
            ->assertStatus(403);

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    /** @test */
    public function authorized_user_can_delete_category()
    {
        $this->signIn();

        $category = create(Category::class, ['user_id' => auth()->id()]);

        $this->assertEquals(1, auth()->user()->categories()->count());
        
        $request = $this->json('delete', route('api.categories.destroy', $category->id));
        
        $request->assertStatus(200);
        $request->assertJsonFragment([  
            'status' => 'success',
            'message' => 'Category deleted!',
        ]);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertEquals(0, auth()->user()->categories()->count());
    }
}
