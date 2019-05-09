<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_user_can_access_admin_area()
    {
        $this->signInAdmin();

        $this->get(route('admin.dashboard'))
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function guest_and_unauthorized_user_can_not_access_admin_area()
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('message', 'You can not access administration area!');
    }

}
