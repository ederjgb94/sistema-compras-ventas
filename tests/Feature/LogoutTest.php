<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('logout functionality works', function () {
    $user = User::factory()->create();

    // Login the user
    $this->actingAs($user);

    // Verify user is authenticated
    expect(Auth::check())->toBeTrue();

    // Perform logout
    $response = $this->post('/logout');

    // Should redirect after logout
    expect($response->status())->toBe(302);

    // User should no longer be authenticated
    expect(Auth::check())->toBeFalse();
});
