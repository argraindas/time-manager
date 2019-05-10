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

    /** @test */
    public function guest_and_unauthorized_user_can_not_delete_card()
    {
        $card = factory(Card::class)->state('withUser')->create();

        $this->delete(route('api.cards.destroy', $card->id))
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->delete(route('api.cards.destroy', $card->id))
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->deleteJson(route('api.cards.destroy', $card->id))
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas('cards', ['id' => $card->id]);
    }

    /** @test */
    public function user_can_delete_card()
    {
        $this->signIn();

        $card = create(Card::class);

        $this->assertEquals(1,  auth()->user()->cards()->count());

        $request = $this->delete(route('api.cards.destroy',  $card->id));

        $request->assertStatus(Response::HTTP_OK);
        $request->assertJsonFragment([
            'status' => 'success',
            'message' => 'Card was successfully deleted!',
        ]);

        $this->assertDatabaseMissing('cards', ['id' => $card->id]);
        $this->assertEquals(0, auth()->user()->cards()->count());
    }

    /** @test */
    public function user_can_update_card()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);

        $newData = [
            'name' => 'New name',
            'description' => 'New description',
        ];

        $this->patch(route('api.cards.update', $card->id), $newData)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => 'success',
                'message' => 'Card was successfully updated!',
            ]);

        tap($card->fresh(), function ($card) use ($newData){
            $this->assertEquals($newData['name'], $card->name);
            $this->assertEquals($newData['description'], $card->description);
        });
    }

    /** @test */
    public function guest_and_unauthorized_user_can_not_update_card()
    {
        /** @var Card $card */
        $card = factory(Card::class)->state('withUser')->create();

        $this->patch(route('api.cards.update', $card->id), ['description' => 'New description'])
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->patch(route('api.cards.update', $card->id), ['description' => 'New description'])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function authorized_user_passes_validation_on_card_update()
    {
        $this->signIn();

        /** @var Card $card */
        $card = create(Card::class);

        $this->patch(route('api.cards.update', $card->id), [
            'name' => null
        ])->assertSessionHasErrors('name');

        $this->patch(route('api.cards.update', $card->id), [
            'name' => 'aa'
        ])->assertSessionHasErrors('name');

        $this->patch(route('api.cards.update', $card->id), [
            'description' => null
        ])->assertSessionHasErrors('name');

        $this->patch(route('api.cards.update', $card->id), [
            'description' => 'aa'
        ])->assertSessionHasErrors('name');

        $this->patch(route('api.cards.update', $card->id), [
            'name' => 'New name',
            'description' => 'New description',
        ])->assertStatus(Response::HTTP_OK);
    }

}
