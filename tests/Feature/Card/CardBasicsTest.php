<?php

namespace Tests\Feature;

use App\Card;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CardBasicsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_get_cards()
    {
        create(Card::class);

        $this->get(route('api.cards'))
            ->assertRedirect('login');
    }

    /** @test */
    public function user_can_get_cards_he_created_or_was_assigned()
    {
        /** @var Card $card */
        create(Card::class, [], 2);
        $card = create(Card::class);

        $this->signIn();

        create(Card::class, [], 2);

        $card->assignParticipant(auth()->user());

        $response = $this->getJson(route('api.cards'))->json();

        $this->assertCount(3, $response['data']);
    }

    /** @test */
    public function guest_can_not_create_card()
    {
        $card = make(Card::class);

        $this->post(route('api.cards.store'), $card->toArray())
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_create_card()
    {
        $this->signIn();

        $card = make(Card::class);

        $this->assertCount(0, Card::all());

        $this->post(route('api.cards.store'), $card->toArray())
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'status' => 'success',
                'message' => 'Card was successfully created!',
            ]);

        $this->assertCount(1, Card::all());

        $this->assertEquals(1, auth()->user()->cards()->count());
    }

    /** @test */
    public function card_validation_on_create()
    {
        $this->signIn();

        $card = make(Card::class)->toArray();

        // NAME

        $unsanitizedName = ' <div>my test Card</div> ';
        $sanitizedName = 'My test card';

        $this->post(route('api.cards.store'), array_merge($card, [
            'name' => null
        ]))->assertSessionHasErrors(['name' => 'Card name is required!']);

        $this->post(route('api.cards.store'), array_merge($card, [
            'name' => $unsanitizedName
        ]))->assertStatus(Response::HTTP_CREATED);

        $this->assertEquals($sanitizedName, Card::first()->name);

        // DESCRIPTION

        $unsanitizedDescription = " <div>my test Description. I've done it RIGHT. </div> ";
        $sanitizedDescription = "My test Description. I&#39;ve done it RIGHT.";

        $this->post(route('api.cards.store'), array_merge($card, [
            'description' => null
        ]))->assertStatus(Response::HTTP_CREATED);

        $this->post(route('api.cards.store'), array_merge($card, [
            'description' => ''
        ]))->assertStatus(Response::HTTP_CREATED);

        $this->post(route('api.cards.store'), array_merge($card, [
            'description' => 'aa'
        ]))->assertSessionHasErrors('description');

        $this->post(route('api.cards.store'), array_merge($card, [
            'description' => $unsanitizedDescription
        ]))->assertStatus(Response::HTTP_CREATED);

        $this->assertEquals($sanitizedDescription, Card::latest('id')->first()->description);
    }

    /** @test */
    public function guest_and_unauthorized_user_can_not_delete_card()
    {
        $card = create(Card::class);

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

        $this->assertCount(1,  auth()->user()->cards);

        $this->delete(route('api.cards.destroy', $card->id))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => 'success',
                'message' => 'Card was successfully deleted!',
            ]);

        $this->assertDatabaseMissing('cards', ['id' => $card->id]);
        $this->assertEquals(0, auth()->user()->cards()->count());
    }

    /** @test */
    public function guest_and_unauthorized_user_can_not_update_card()
    {
        /** @var Card $card */
        $card = create(Card::class);

        $this->patch(route('api.cards.update', $card->id), ['description' => 'New description'])
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->patch(route('api.cards.update', $card->id), ['description' => 'New description'])
            ->assertStatus(Response::HTTP_FORBIDDEN);
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
    public function card_validation_on_update()
    {
        $this->signIn();

        $card = create(Card::class)->toArray();

        // NAME

        $unsanitizedName = ' <div>my test Card</div> ';
        $sanitizedName = 'My test card';

        $this->patch(route('api.cards.update', $card['id']), array_merge($card, [
            'name' => null
        ]))->assertSessionHasErrors('name');

        $this->patch(route('api.cards.update', $card['id']), array_merge($card, [
            'name' => 'aa'
        ]))->assertSessionHasErrors('name');

        $this->patch(route('api.cards.update', $card['id']), array_merge($card, [
            'name' => $unsanitizedName
        ]))->assertStatus(Response::HTTP_OK);

        $this->assertEquals($sanitizedName, Card::first()->name);

        // DESCRIPTION

        $unsanitizedDescription = " <div>my test Description. I've done it RIGHT. </div> ";
        $sanitizedDescription = "My test Description. I&#39;ve done it RIGHT.";

        $this->patch(route('api.cards.update', $card['id']), array_merge($card, [
            'description' => null
        ]))->assertStatus(Response::HTTP_OK);

        $this->patch(route('api.cards.update', $card['id']), array_merge($card, [
            'description' => ''
        ]))->assertStatus(Response::HTTP_OK);

        $this->patch(route('api.cards.update', $card['id']), array_merge($card, [
            'description' => 'aa'
        ]))->assertSessionHasErrors('description');

        $this->patch(route('api.cards.update', $card['id']), array_merge($card, [
            'description' => $unsanitizedDescription,
        ]))->assertStatus(Response::HTTP_OK);

        $this->assertEquals($sanitizedDescription, Card::latest('id')->first()->description);
    }

}
