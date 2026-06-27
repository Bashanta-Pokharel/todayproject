<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Create Admin');
    }

    public function test_first_registered_user_becomes_admin(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertSame('admin', User::where('email', 'test@example.com')->firstOrFail()->role);
        $response->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_admin_register_screen_can_create_the_first_admin(): void
    {
        $this->get(route('admin.register'))
            ->assertOk()
            ->assertSee('Create Admin');

        $response = $this->post(route('admin.register.store'), [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertSame('admin', User::where('email', 'admin@example.com')->firstOrFail()->role);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_register_screen_can_upgrade_current_user_when_no_admin_exists(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.register'))
            ->assertOk()
            ->assertSee('Use this account as admin');

        $this->actingAs($user)
            ->post(route('admin.register.store'))
            ->assertRedirect(route('admin.dashboard'));

        $this->assertSame('admin', $user->fresh()->role);
    }

    public function test_later_registered_users_are_customers(): void
    {
        User::factory()->admin()->create();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertSame('customer', User::where('email', 'test@example.com')->firstOrFail()->role);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_existing_admin_is_sent_from_admin_register_to_create_admin_page(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.register'))
            ->assertRedirect(route('admin.users.create'));
    }
}
