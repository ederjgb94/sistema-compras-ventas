<?php

use Illuminate\Support\Facades\DB;

test('system is working correctly', function () {
    // Test 1: Application boots
    expect(app())->toBeInstanceOf(\Illuminate\Foundation\Application::class);

    // Test 2: Environment is testing
    expect(app()->environment())->toBe('testing');

    // Test 3: Config loads
    expect(config('app.name'))->toBeString();

    // Test 4: Database connection works
    expect(DB::connection()->getPdo())->not->toBeNull();
});

test('routes are accessible', function () {
    // Test homepage redirect
    $response = $this->get('/');
    expect($response->status())->toBe(302);

    // Test login page
    $response = $this->get('/login');
    expect($response->status())->toBe(200);
});
