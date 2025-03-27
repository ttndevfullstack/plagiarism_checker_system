<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $adminResponse = $this->get('/admin/login');
        $userResponse = $this->get('/user/login');

        $adminResponse->assertStatus(200);
        $userResponse->assertStatus(200);
    }
}
