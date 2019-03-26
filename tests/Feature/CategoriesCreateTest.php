<?php

namespace Tests\Feature;

use App\Category;
use App\User;
use Symfony\Component\HttpFoundation\Response;
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

        $this->post(route('api.categories.store'), $category->toArray())
            ->assertStatus(Response::HTTP_CREATED)
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
    public function category_must_be_unique_for_user()
    {
        $testName = 'My category';

        $otherUser = create(User::class);
        create(Category::class, ['name' => $testName, 'user_id' => $otherUser->id]);

        $this->signIn();

        $category = make(Category::class, ['name' => $testName]);

        // after create
        $this->post(route('api.categories.store'), $category->toArray())
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('categories', ['name' => $testName, 'user_id' => $otherUser->id]);
        $this->assertDatabaseHas('categories', ['name' => $testName, 'user_id' => auth()->id()]);

        $this->post(route('api.categories.store'), $category->toArray())
            ->assertSessionHasErrors('name');

        // after update
        $storedCategory = auth()->user()->categories()->first();

        $this->patch(route('api.categories.update', ['id' => $storedCategory->id]), ['name' => $testName])
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('categories', ['id' => $storedCategory->id, 'name' => $testName]);

        $newName = 'New name';

        $this->patch(route('api.categories.update', ['id' => $storedCategory->id]), ['name' => $newName])
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('categories', ['id' => $storedCategory->id, 'name' => $newName]);
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
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->json('delete', route('api.categories.destroy', $category->id))
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    /** @test */
    public function authorized_user_can_delete_category()
    {
        $this->signIn();

        $category = create(Category::class);

        $this->assertEquals(1, auth()->user()->categories()->count());
        
        $request = $this->json('delete', route('api.categories.destroy', $category->id));
        
        $request->assertStatus(Response::HTTP_OK);
        $request->assertJsonFragment([  
            'status' => 'success',
            'message' => 'Category deleted!',
        ]);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertEquals(0, auth()->user()->categories()->count());
    }

    /** @test */
    public function guest_and_unauthorized_user_can_not_edit_category()
    {
        $user = create(User::class);
        $category = create(Category::class, ['user_id' => $user->id]);

        $updatedName = 'New category name';

        $this->patch(route('api.categories.update', ['id' => $category->id]), ['name' => $updatedName])
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->patch(route('api.categories.update', ['id' => $category->id]), ['name' => $updatedName])
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas('categories', ['name' => $category->name]);
    }

    /** @test */
    public function authorized_user_can_edit_category()
    {
        $this->signIn();

        $category = create(Category::class);

        $updatedName = 'New category name';

        $this->patch(route('api.categories.update', ['id' => $category->id]), ['name' => $updatedName]);

        $this->assertDatabaseHas('categories', ['name' => $updatedName]);
    }
}
