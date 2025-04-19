<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private array $credentials = [
        'name' => 'Damon Salvatore',
        'email' => 'damon.salvatore@gmail.com',
        'password' => 'elena123',
        'password_confirmation' => 'elena123'
    ];

    public function test_should_register(): void
    {
        $response = $this->postJson(route('auth.register'), $this->credentials);
        $response->assertNoContent();
        $this->assertDatabaseHas('users', [
            'email' => $this->credentials['email'],
        ]);
    }

    public function test_should_not_register_if_email_is_already_used(): void
    {
        User::create($this->credentials);
        $response = $this->postJson(route('auth.register'), $this->credentials);
        $response->assertJsonValidationErrorFor('email');
    }

    public function test_should_not_register_if_passwords_do_not_match(): void
    {
        $this->credentials['password_confirmation'] = 'elena';
        $response = $this->postJson(route('auth.register'), $this->credentials);
        $response->assertJsonValidationErrorFor('password');
    }

    public function test_should_login(): void
    {
        $user = User::create($this->credentials);
        $response = $this->postJson(route('auth.login'), $this->credentials);
        $response
            ->assertOk()
            ->assertJson([
                'name' => $this->credentials['name'],
                'email' => $this->credentials['email'],
            ]);
        $this->assertAuthenticatedAs($user);
    }

    public function test_should_not_login_if_credentials_are_invalid(): void
    {
        $credentials = [
            'email' => 'stefan.salvatore@gmail.com',
            'password' => 'elena123'
        ];
        $response = $this->postJson(route('auth.login'), $credentials);
        $response->assertUnauthorized();
    }

    public function test_should_logout(): void
    {
        $user = User::create($this->credentials);
        $response = $this->actingAs($user)->deleteJson(route('auth.logout'));
        $response->assertNoContent();
        $this->assertGuest();
    }
}
