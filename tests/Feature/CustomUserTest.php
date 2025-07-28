<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class CustomUserTest extends TestCase
{
    /**
     * Test user creation.
     */
    public function test_user_can_be_created()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
    }

    /**
     * Test user authentication.
     */
    public function test_user_can_be_authenticated()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->assertInstanceOf(User::class, $user);

        $this->actingAs($user);

        $this->assertAuthenticated();
    }
}
