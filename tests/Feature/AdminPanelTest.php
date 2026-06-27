<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_has_management_shortcuts(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Add Product');
        $response->assertSee('Add Category');
        $response->assertSee('Add Attribute');
        $response->assertSee('Create Admin');
    }

    public function test_dashboard_redirects_admins_to_admin_panel(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_customer_is_sent_to_admin_register_when_no_admin_exists(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.register'));
    }

    public function test_admin_trash_routes_open_their_trash_pages(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.product.trashed'))
            ->assertOk()
            ->assertViewIs('admin.product.trashed');

        $this->actingAs($admin)
            ->get(route('admin.category.trashed'))
            ->assertOk()
            ->assertViewIs('admin.category.trashed');

        $this->actingAs($admin)
            ->get(route('admin.attribute.trashed'))
            ->assertOk()
            ->assertViewIs('admin.attribute.trashed');
    }

    public function test_admin_can_create_another_admin_user(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Store Manager',
            'email' => 'manager@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $newAdmin = User::where('email', 'manager@example.com')->firstOrFail();

        $response->assertRedirect(route('admin.users.index'));
        $this->assertSame('admin', $newAdmin->role);

        $this->actingAs($newAdmin)
            ->get(route('admin.product.create'))
            ->assertOk();
    }

    public function test_customer_cannot_create_admin_when_an_admin_exists(): void
    {
        User::factory()->admin()->create();
        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->get(route('admin.users.create'))
            ->assertForbidden();

        $this->actingAs($customer)->post(route('admin.users.store'), [
            'name' => 'Store Manager',
            'email' => 'manager@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertForbidden();

        $this->assertDatabaseMissing('users', [
            'email' => 'manager@example.com',
            'role' => 'admin',
        ]);
    }
}
