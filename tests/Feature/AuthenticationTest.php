<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_registration_pages_are_available_to_guests(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Log in to your account')
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false);

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Create your account')
            ->assertSee('name="name"', false)
            ->assertSee('name="password_confirmation"', false)
            ->assertSee('Use at least 8 characters.');
    }

    public function test_registration_creates_a_user_and_signs_them_in(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Henry Student',
            'email' => 'henry@example.com',
            'password' => 'student-password',
            'password_confirmation' => 'student-password',
        ]);

        $response->assertRedirect(route('overview'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Henry Student',
            'email' => 'henry@example.com',
        ]);
        $this->assertNotSame('student-password', User::firstOrFail()->password);
    }

    public function test_registration_shows_validation_errors_for_invalid_input(): void
    {
        $this->from(route('register'))
            ->post(route('register.store'), [
                'name' => '',
                'email' => 'not-an-email',
                'password' => 'short',
                'password_confirmation' => 'different',
            ])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors(['name', 'email', 'password']);

        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_login_rejects_invalid_credentials_and_accepts_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'henry@example.com',
            'password' => 'student-password',
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => 'henry@example.com',
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();

        $this->post(route('login.store'), [
            'email' => 'henry@example.com',
            'password' => 'student-password',
        ])->assertRedirect(route('overview'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_task_pages_require_authentication_and_logout_ends_the_session(): void
    {
        $this->get(route('overview'))->assertRedirect(route('login'));
        $this->get(route('tasks.index'))->assertRedirect(route('login'));

        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('tasks.index'))
            ->assertOk()
            ->assertSee('data-user-id="'.$user->id.'"', false);

        $this->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
