<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UIRenderingTest extends TestCase
{
    /**
     * Test modernized pages rendering correctly.
     */
    public function test_modernized_pages_render_correctly(): void
    {
        $this->withoutExceptionHandling();

        // Verify login page renders for guests
        $loginResponse = $this->get('/login');
        $loginResponse->assertStatus(200);

        // Fetch the existing admin user
        $user = User::where('email', 'admin@example.com')->first();

        if (!$user) {
            $this->markTestSkipped('Admin user not found in database.');
        }

        $this->actingAs($user);

        // Define routes to verify
        $routes = [
            '/machines',
            '/machines/create',
            '/settings/departments',
            '/settings/locations',
            '/audit-logs',
            '/',
            '/machines/1',
        ];

        foreach ($routes as $route) {
            echo "Testing $route\n";
            $response = $this->get($route);

            $response->assertStatus(200);
            
            // Just verifying it doesn't throw exceptions and returns successful response
        }
    }
}
