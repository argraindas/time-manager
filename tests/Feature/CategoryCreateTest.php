<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_add_category()
    {
        // TODO

        $this->assertTrue(true);
    }

}
