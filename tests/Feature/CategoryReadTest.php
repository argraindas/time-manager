<?php

namespace Tests\Feature;

use App\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryReadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_all_his_categories()
    {
        $this->signIn();

        $category = create(Category::class, ['user_id' => auth()->id()]);
        $category2 = create(Category::class, ['user_id' => auth()->id()]);

        $this->get(route('categories'))
            ->assertSee($category->name)
            ->assertSee($category2->name);
    }

}
