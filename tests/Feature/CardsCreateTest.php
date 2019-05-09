<?php

namespace Tests\Feature;

use App\Card;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CardsCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_create_a_card()
    {
        $card = make(Card::class);

        $this->post(route('api.cards.store'), $card->toArray())
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_create_a_card()
    {
        $this->signIn();

        $card = make(Card::class);

        $this->post(route('api.cards.store'), $card->toArray())
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('cards',  ['name' => $card->name]);

        $this->assertEquals(1, auth()->user()->cards()->count());
    }

    /** @test */
    public function card_name_must_be_valid_and_sanitized()
    {
        $this->signIn();

        $card = make(Card::class, ['name' => null]);

        $this->post(route('api.cards.store'), $card->toArray())
            ->assertSessionHasErrors(['name' => 'Card name is required!']);

        // sanitizing input
        $unsanitizedName = ' <div>my test Card</div> ';
        $sanitizedName = 'My test card';

        $card = make(Card::class, ['name' => $unsanitizedName]);

        $this->post(route('api.cards.store'), $card->toArray())
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertEquals($sanitizedName, Card::first()->name);
    }

    /** @test */
    public function card_description_must_be_valid_and_sanitized()
    {
        $this->signIn();

        // when description is null
        $card = make(Card::class, ['description' => null]);

        $this->post(route('api.cards.store'), $card->toArray())
            ->assertStatus(Response::HTTP_CREATED);

        // when description is empty
        $card = make(Card::class, ['description' => '']);

        $this->post(route('api.cards.store'), $card->toArray())
            ->assertStatus(Response::HTTP_CREATED);

        // when description is to short
        $card = make(Card::class, ['description' => 'aa']);

        $this->post(route('api.cards.store'), $card->toArray())
            ->assertSessionHasErrors('description');

        // sanitizing input
        $unsanitizedName = " <div>my test Description. I've done it RIGHT. </div> ";
        $sanitizedName = "My test Description. I&#39;ve done it RIGHT.";

        $card = make(Card::class, ['description' => $unsanitizedName]);
        
        $this->post(route('api.cards.store'), $card->toArray())
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertEquals($sanitizedName, Card::latest('id')->first()->description);
    }

}
