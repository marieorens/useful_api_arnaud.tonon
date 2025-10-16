<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Module;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;

class Module0Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // run the seeder for modules to put data into the database
        $this->artisan('db:seed', ['--class' => 'ModuleSeeder']);
    }

    public function test_register_returns_201()
    {
        $payload = [
            'name' => 'Arnaud Leboss',
            'email' => 'arnaudboss@example.com',
            'password' => 'password123',
        ];

        $resp = $this->postJson('/api/register', $payload);

        $resp->assertStatus(201)
            ->assertJsonStructure(['id','name','email','created_at']);
    }

    public function test_login_returns_token()
    {
        $user = User::factory()->create(['password' => Hash::make('secret123')]);

        $resp = $this->postJson('/api/login', ['email' => $user->email, 'password' => 'secret123']);

        $resp->assertStatus(200)
            ->assertJsonStructure(['token','user_id']);
    }

    public function test_modules_index_requires_auth_and_returns_list()
    {
        $user = User::factory()->create();

        // unauthenticated
        $this->getJson('/api/modules')->assertStatus(401);

        Sanctum::actingAs($user);

        $this->getJson('/api/modules')->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_activate_and_deactivate_module()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/modules/1/activate')->assertStatus(200)
            ->assertJson(['message' => 'Module activated']);

        $this->assertDatabaseHas('user_modules', ['user_id' => $user->id, 'module_id' => 1, 'active' => 1]);

        $this->postJson('/api/modules/1/deactivate')->assertStatus(200)
            ->assertJson(['message' => 'Module deactivated']);

        $this->assertDatabaseHas('user_modules', ['user_id' => $user->id, 'module_id' => 1, 'active' => 0]);
    }

    public function test_register_validation_fails()
    {
        $resp = $this->postJson('/api/register', ['name' => '', 'email' => 'not-an-email', 'password' => 'short']);
        $resp->assertStatus(422)
            ->assertJsonStructure(['error','details']);
    }

    public function test_activate_nonexistent_module_returns_404()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/modules/999/activate')->assertStatus(404)
            ->assertJson(['error' => 'Module not found']);
    }

    public function test_check_module_active_middleware_blocks_and_allows()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->getJson('/api/wallet/info')->assertStatus(403)
            ->assertExactJson(['error' => 'Module inactive. Please activate this module to use it.']);

        $this->postJson('/api/modules/2/activate')->assertStatus(200);

        // doit etre accessible maintenant aprèsactivation
        $this->getJson('/api/wallet/info')->assertStatus(200)
            ->assertJson(['message' => 'Wallet endpoint accessible']);
    }
}
