<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class BasicTest extends TestCase
{
    /**
     * Test basic application functionality works
     */
    public function test_basic_application_functionality_works()
    {
        // Test 1: Homepage redirects to login
        $response = $this->get('/');
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/login');

        // Test 2: Login page loads
        $response = $this->get('/login');
        $this->assertEquals(200, $response->status());

        // Test 3: User factory works
        $user = User::factory()->create();
        $this->assertInstanceOf(User::class, $user);
        $this->assertIsString($user->email);
        $this->assertIsString($user->name);

        // Test 4: Dashboard requires authentication
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        // Test 5: Authenticated user can access dashboard
        $this->actingAs(User::find($user->id)); // Asegura que sea una instancia de User/Authenticatable
        $response = $this->get('/dashboard');
        $this->assertEquals(200, $response->status());
    }

    /**
     * Test database connection works
     */
    public function test_database_connection_works()
    {
        // Verify we can create and retrieve a user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);

        $retrievedUser = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($retrievedUser);
        $this->assertEquals('Test User', $retrievedUser->name);
    }
}
