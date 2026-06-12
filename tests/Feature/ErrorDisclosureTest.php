<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ErrorDisclosureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Register a temporary route that throws an exception to simulate an internal server error
        Route::get('/_test_error_route', function () {
            throw new \RuntimeException('Sensitive DB query details or app secrets leaked');
        });
    }

    public function test_sensitive_errors_are_not_disclosed_when_debug_is_false(): void
    {
        // Force app.debug to false as if in production environment
        config(['app.debug' => false]);

        $response = $this->get('/_test_error_route');

        // Should return a generic 500 Internal Server Error
        $response->assertStatus(500);

        // Ensure sensitive info and traces are completely hidden
        $response->assertDontSee('Sensitive DB query details or app secrets leaked');
        $response->assertDontSee('RuntimeException');
        $response->assertDontSee('Stack trace');
        $response->assertDontSee('Vendor/laravel');
    }

    public function test_method_not_allowed_does_not_disclose_info_when_debug_is_false(): void
    {
        // Force app.debug to false
        config(['app.debug' => false]);

        // GET /logout is not supported (only POST /logout exists)
        $response = $this->get('/logout');

        // Should return 405 Method Not Allowed
        $response->assertStatus(405);

        // Ensure no exception or trace details are exposed
        $response->assertDontSee('MethodNotAllowedHttpException');
        $response->assertDontSee('Stack trace');
    }
}
