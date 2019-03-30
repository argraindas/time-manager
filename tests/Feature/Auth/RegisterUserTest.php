<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    /** @test */
    public function users_can_register_an_account()
    {
        $response = $this->post('register', $params = $this->validParams());

        $response->assertRedirect(route('home'));

        $this->assertTrue(Auth::check());
        $this->assertCount(1, User::all());

        tap(User::first(), function ($user) use ($params) {
            $this->assertEquals('John Doe', $user->name);
            $this->assertEquals('johndoe@example.com', $user->email);
            $this->assertTrue(Hash::check($params['password'], $user->password));
        });
    }

    /** @test */
    public function authenticated_but_unverified_user_will_be_correctly_redirected()
    {
        $user = create(User::class, ['email_verified_at' => null]);
        $this->signIn($user);

        $this->get(route('dashboard'))
            ->assertRedirect(route('home'))
            ->assertSessionHas('message', 'You must verify your email first!');
    }

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        $this->post('register', $this->validParams());

        Notification::assertSentTo( User::first(), VerifyEmail::class);
    }

    /** @test */
    public function user_can_fully_confirm_their_email_addresses()
    {
        $this->post('register', $this->validParams([
            'email' => 'john@example.com',
        ]));

        $user = User::whereEmail('john@example.com')->first();

        $this->assertFalse($user->hasVerifiedEmail());

        $this->get(URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            ['id' => $user->getKey()]
        ))->assertRedirect(route('dashboard'));

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function confirming_an_invalid_signature()
    {
        $anotherUser = create(User::class);

        // unauthenticated user
        $this->get(URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            ['id' => $anotherUser->id]
        ))->assertStatus(Response::HTTP_UNAUTHORIZED);

        // authenticated, but invalid user_id
        $this->signIn();

        $this->get(URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            ['id' => $anotherUser->id]
        ))->assertStatus(Response::HTTP_FORBIDDEN);

        // authenticated, but signature expired
        $this->get(URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->subMinutes(5),
            ['id' => auth()->id()]
        ))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function name_is_required()
    {
        $this->post(route('register'), $this->validParams([
            'name' => ''
        ]))->assertSessionHasErrors('name');

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function name_cannot_exceed_255_chars()
    {
        $this->from(route('register'));

        $response = $this->post(route('register'), $this->validParams([
            'name' => str_repeat('a', 256),
        ]));

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('name');
        $this->assertFalse(Auth::check());
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function email_is_required()
    {
        $this->from(route('register'));

        $response = $this->post(route('register'), $this->validParams([
            'email' => '',
        ]));

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('email');
        $this->assertFalse(Auth::check());
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function email_is_valid()
    {
        $this->withExceptionHandling();
        $this->from(route('register'));

        $response = $this->post(route('register'), $this->validParams([
            'email' => 'not-an-email-address',
        ]));

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('email');
        $this->assertFalse(Auth::check());
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function email_cannot_exceed_255_chars()
    {
        $this->withExceptionHandling();
        $this->from(route('register'));

        $response = $this->post(route('register'), $this->validParams([
            'email' => substr(str_repeat('a', 256) . '@example.com', -256),
        ]));

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('email');
        $this->assertFalse(Auth::check());
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function email_is_unique()
    {
        create(User::class, ['email' => 'johndoe@example.com']);
        $this->withExceptionHandling();
        $this->from(route('register'));

        $response = $this->post(route('register'), $this->validParams([
            'email' => 'johndoe@example.com',
        ]));

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('email');
        $this->assertFalse(Auth::check());
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function password_is_required()
    {
        $this->from(route('register'));

        $response = $this->post(route('register'), $this->validParams([
            'password' => '',
        ]));

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('password');
        $this->assertFalse(Auth::check());
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function password_must_be_confirmed()
    {
        $this->from(route('register'));

        $response = $this->post(route('register'), $this->validParams([
            'password' => 'foo',
            'password_confirmation' => 'bar'
        ]));

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('password');
        $this->assertFalse(Auth::check());
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function password_must_be_8_chars()
    {
        $this->from(route('register'));

        $response = $this->post(route('register'), $this->validParams([
            'password' => 'foo',
            'password_confirmation' => 'foo',
        ]));

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('password');
        $this->assertFalse(Auth::check());
        $this->assertCount(0, User::all());
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ], $overrides);
    }
}
