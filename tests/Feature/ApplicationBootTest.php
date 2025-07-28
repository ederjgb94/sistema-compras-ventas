<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApplicationBootTest extends TestCase
{
    /**
     * Test that application boots successfully
     */
    public function test_application_boots_successfully()
    {
        $this->assertTrue(true);
    }

    /**
     * Test that basic HTTP request works
     */
    public function test_basic_http_request_works()
    {
        $response = $this->get('/');
        $this->assertTrue(in_array($response->status(), [200, 302]));
    }
}
